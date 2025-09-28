<?php
/* ============================================================================= *
 * SA-MP Query - PHP query library for SA-MP (San Andreas Multiplayer) and ↓     *
 * OMP (Open Multiplayer)                                                        *
 * ============================================================================= *
 *                                                                               *
 * Copyright (c) 2025, SPC (SA-MP Programming Community)                         *
 * All rights reserved.                                                          *
 *                                                                               *
 * Developed by: Calasans                                                        *
 * Repository: https://github.com/spc-samp/samp-query                            *
 *                                                                               *
 * ============================================================================= *
 *                                                                               *
 * Licensed under the MIT License (MIT);                                         *
 * you may not use this file except in compliance with the License.              *
 * You may obtain a copy of the License at:                                      *
 *                                                                               *
 *     https://opensource.org/licenses/MIT                                       *
 *                                                                               *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR    *
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,      *
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE   *
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER        *
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, *
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN     *
 * THE SOFTWARE.                                                                 *
 *                                                                               *
 * ============================================================================= */

declare(strict_types=1);

namespace Samp_Query;

require_once __DIR__ . '/bootstrap/autoloader.php';

use Samp_Query\Constants\Performance;
use Samp_Query\Constants\Protocol;
use Samp_Query\Constants\Query;
use Samp_Query\Exceptions\Query_Exception;
use Samp_Query\Exceptions\Connection_Exception;
use Samp_Query\Exceptions\Malformed_Packet_Exception;
use Samp_Query\Exceptions\Invalid_Argument_Exception;
use Samp_Query\Exceptions\Rcon_Exception;
use Samp_Query\Models\Server_Info;

final class Samp_Query {
    public readonly string $ip;
    private readonly Logger $logger;
    private readonly Socket_Manager $socket_manager;
    private readonly Packet_Builder $packet_builder;
    private ?int $cached_ping = null;
    private ?Server_Info $cached_info = null;
    private array $response_cache = [];
    private float $last_successful_query = 0;

    public function __construct(public readonly string $hostname, public readonly int $port) {
        $this->logger = new Logger();

        if (empty($hostname))
            throw new Invalid_Argument_Exception("Hostname cannot be empty.");
        
        if ($this->port <= 0 || $this->port > 65535)
            throw new Invalid_Argument_Exception("Invalid port: {$this->port}");
        
        try {
            Domain_Resolver::Clean_Expired_Cache();

            $this->ip = (new Domain_Resolver())->Resolve($this->hostname);
            $this->socket_manager = new Socket_Manager($this->ip, $this->port);
            $this->packet_builder = new Packet_Builder($this->ip, $this->port);
        }
        catch (Query_Exception $e) {
            $this->logger->Log($e->getMessage());

            throw $e;
        }
    }

    public function __destruct() {
        $this->socket_manager->Disconnect();
    }
    
    private function Execute_Query_Phase(array $jobs, ?int &$ping, float $timeout): array {
        $socket = $this->socket_manager->Get_Socket_Resource($timeout);
        stream_set_blocking($socket, false);
        $opcode_map = [];

        foreach ($jobs as $key => $opcode)
            $opcode_map[$opcode->value] = $key;

        $start_time = microtime(true);
        $pending_jobs = $jobs;
        $phase_results = [];
        
        for ($i = 0; $i < 2; $i++) {
            foreach ($pending_jobs as $opcode)
                fwrite($socket, $this->packet_builder->Build($opcode->value));

            if ($i == 0)
                usleep(5000);
        }

        $last_send_time = microtime(true);
        $current_retry_interval = Performance::INITIAL_RETRY_INTERVAL;
        $packets_received = 0;

        while (!empty($pending_jobs) && (microtime(true) - $start_time) < $timeout) {
            $now = microtime(true);

            if (($now - $last_send_time) > $current_retry_interval) {
                foreach ($pending_jobs as $opcode) {
                    fwrite($socket, $this->packet_builder->Build($opcode->value));

                    if ($opcode === Opcode::INFO || $opcode === Opcode::PING)
                        fwrite($socket, $this->packet_builder->Build($opcode->value));
                }

                $last_send_time = $now;
                $current_retry_interval = min($current_retry_interval * Performance::BACKOFF_FACTOR, 0.2);
            }

            $read = [$socket]; $write = $except = null;

            if (stream_select($read, $write, $except, 0, 10000) > 0) {
                $response = stream_socket_recvfrom($socket, Protocol::UDP_READ_BUFFER_SIZE);

                if (!is_string($response) || $response === '')
                    continue;
            
                if (strlen($response) < 11)
                    continue;
                
                $response_opcode = $response[10] ?? null;

                if (!$response_opcode || !isset($opcode_map[$response_opcode]))
                    continue;
            
                $job_key = $opcode_map[$response_opcode];

                if (!isset($pending_jobs[$job_key]))
                    continue;
            
                if ($ping === null && $packets_received === 0)
                    $ping = max(1, (int)round((microtime(true) - $start_time) * 1000));
            
                try {
                    $data = (new Packet_Parser($response))->Parse($jobs[$job_key]);

                    if ($data !== null && $this->Validate_Response($data, $jobs[$job_key])) {
                        $phase_results[$job_key] = $data;
                        unset($pending_jobs[$job_key]);
                        $packets_received++;
                        
                        $this->response_cache[$job_key] = [
                            'data' => $data,
                            'time' => microtime(true)
                        ];
                    }
                }
                catch (Malformed_Packet_Exception $e) {
                    fwrite($socket, $this->packet_builder->Build($jobs[$job_key]->value));
                    $this->logger->Log("Malformed packet for '{$job_key}', retrying immediately: " . $e->getMessage());
                }
            }
            
            if (!empty($pending_jobs))
                usleep(1000);
        }

        return $phase_results;
    }

    private function Validate_Response($data, Opcode $opcode): bool {
        switch ($opcode) {
            case Opcode::INFO:
                return $data instanceof Server_Info && $data->max_players > 0 && $data->players >= 0 && $data->players <= $data->max_players && !empty($data->hostname);
            
            case Opcode::RULES:
                if (!is_array($data))
                    return false;

                if (!empty($data)) {
                    $first = reset($data);

                    return $first instanceof \Samp_Query\Models\Server_Rule;
                }

                return true;
            
            case Opcode::PLAYERS_DETAILED:
                if (!is_array($data))
                    return false;

                if (!empty($data)) {
                    $first = reset($data);

                    return $first instanceof \Samp_Query\Models\Players_Detailed;
                }

                return true;
            
            case Opcode::PLAYERS_BASIC:
                if (!is_array($data))
                    return false;

                if (!empty($data)) {
                    $first = reset($data);

                    return $first instanceof \Samp_Query\Models\Players_Basic;
                }

                return true;
            
            case Opcode::PING:
                return $data === true;
            
            default:
                return $data !== null;
        }
    }

    private function Attempt_Query(array $jobs, float $timeout, bool $critical = false): array {
        $max_attempts = $critical ? Query::ATTEMPTS * 2 : Query::ATTEMPTS;
        $result = [];
        
        $now = microtime(true);

        foreach ($jobs as $key => $opcode) {
            if (isset($this->response_cache[$key]) && ($now - $this->response_cache[$key]['time']) < 2.0) {
                $result[$key] = $this->response_cache[$key]['data'];
                unset($jobs[$key]);
            }
        }
        
        if (empty($jobs))
            return $result;
        
        for ($attempt = 1; $attempt <= Query::FAST_RETRY_ATTEMPTS; $attempt++) {
            $ping_ref = $this->cached_ping;
            $phase_result = $this->Execute_Query_Phase($jobs, $ping_ref, $timeout * 0.6);
            
            if ($this->cached_ping === null || $ping_ref < $this->cached_ping)
                $this->cached_ping = $ping_ref;
            
            foreach ($phase_result as $key => $data) {
                if ($this->Validate_Response($data, $jobs[$key])) {
                    $result[$key] = $data;
                    unset($jobs[$key]);
                }
            }
            
            if (empty($jobs))
                return $result;
        }
        
        for ($attempt = Query::FAST_RETRY_ATTEMPTS + 1; $attempt <= $max_attempts; $attempt++) {
            $ping_ref = $this->cached_ping;
            $adjusted_timeout = $timeout * (1 + ($attempt - Query::FAST_RETRY_ATTEMPTS) * 0.1);
            $phase_result = $this->Execute_Query_Phase($jobs, $ping_ref, $adjusted_timeout);
            
            if ($this->cached_ping === null || $ping_ref < $this->cached_ping)
                $this->cached_ping = $ping_ref;
            
            foreach ($phase_result as $key => $data) {
                if ($this->Validate_Response($data, $jobs[$key])) {
                    $result[$key] = $data;
                    unset($jobs[$key]);
                }
            }
            
            if (empty($jobs))
                return $result;
            
            if ($attempt < $max_attempts) {
                $delay = Query::RETRY_DELAY_MS * (1 + ($attempt - Query::FAST_RETRY_ATTEMPTS) * 0.5);
                usleep((int)($delay * 1000));
            }
        }
        
        if ($critical && !empty($jobs)) {
            foreach ($jobs as $key => $opcode) {
                $emergency_result = $this->Execute_Query_Phase([$key => $opcode], $ping_ref, $timeout * 2);

                if (!empty($emergency_result[$key]) && $this->Validate_Response($emergency_result[$key], $opcode))
                    $result[$key] = $emergency_result[$key];
            }
        }
        
        return $result;
    }
    
    private function Fetch_Server_State(): void {
        if ($this->cached_info !== null && (microtime(true) - $this->last_successful_query) < 5.0)
            return;

        $result = $this->Attempt_Query(['info' => Opcode::INFO], Performance::METADATA_TIMEOUT, true);

        if (empty($result['info']) || !$this->Validate_Response($result['info'], Opcode::INFO))
            throw new Connection_Exception("Server failed to respond with valid info after maximum attempts. Server may be offline or unreachable.");

        $this->cached_info = $result['info'];
        $this->last_successful_query = microtime(true);

        if($this->cached_ping === null) {
            $start = microtime(true);
            $ping_ref = null;
            $this->Execute_Query_Phase(['ping' => Opcode::PING], $ping_ref, Performance::FAST_PING_TIMEOUT);
            $this->cached_ping = $ping_ref ?? max(1, (int)round((microtime(true) - $start) * 1000));
        }
    }

    private function Send_Single_Rcon_Request(Socket_Manager $manager, string $password, string $command): ?string {
        $packet = $this->packet_builder->Build_Rcon($password, $command);
        $socket = $manager->Get_Socket_Resource(1.0);
        
        for ($i = 0; $i < 3; $i++) {
            if (fwrite($socket, $packet) === false && $i == 2)
                throw new Connection_Exception("Failed to send RCON packet after 3 attempts.");

            if ($i < 2)
                usleep(10000);
        }

        $read = [$socket]; $write = $except = null;
        $attempts = 0;
        
        while ($attempts < 5) {
            if (stream_select($read, $write, $except, 0, 200000) > 0) {
                $response_data = stream_socket_recvfrom($socket, Protocol::UDP_READ_BUFFER_SIZE);

                if (is_string($response_data) && strlen($response_data) >= 11) {
                    $parsed = (new Packet_Parser($response_data))->Parse_Rcon();

                    if (!empty($parsed))
                        return $parsed;
                }
            }
            
            if ($attempts < 4) {
                fwrite($socket, $packet);
                usleep(50000);
            }

            $attempts++;
        }

        return null;
    }

    private function Fetch_Player_Data(Opcode $opcode): ?array {
        $this->Fetch_Server_State();

        if ($this->cached_info->players === 0)
            return [];
            
        if ($this->cached_info->players >= Query::LARGE_PLAYER_THRESHOLD)
            return [];

        $timeout = Performance::PLAYER_LIST_BASE_TIMEOUT + (($this->cached_ping ?? 50) * Performance::PING_MULTIPLIER / 1000);
        
        $query_key = $opcode === Opcode::PLAYERS_DETAILED ? 'players_detailed' : 'players_basic';
        $result = $this->Attempt_Query([$query_key => $opcode], min($timeout, 2.0), true);
        
        if (isset($result[$query_key]) && is_array($result[$query_key])) {
            $data = $result[$query_key];

            if (!empty($data)) {
                $first_item = reset($data);

                if ($opcode === Opcode::PLAYERS_DETAILED && $first_item instanceof \Samp_Query\Models\Players_Detailed)
                    return $data;
                elseif ($opcode === Opcode::PLAYERS_BASIC && $first_item instanceof \Samp_Query\Models\Players_Basic)
                    return $data;
            }
            
            if ($this->cached_info->players > 0) {
                $result = $this->Attempt_Query([$query_key => $opcode], $timeout * 1.5, true);

                return $result[$query_key] ?? [];
            }

            return [];
        }

        return null;
    }

    public function Get_All(): array {
        $start_time = microtime(true);
        
        $this->cached_info = null;
        $this->Fetch_Server_State();
        
        if (!$this->cached_info || !$this->Validate_Response($this->cached_info, Opcode::INFO))
            throw new Connection_Exception("Failed to get valid server information.");
        
        $results = ['info' => $this->cached_info];
        
        $parallel_jobs = ['rules' => Opcode::RULES];
        
        if ($this->cached_info->players > 0 && $this->cached_info->players < Query::LARGE_PLAYER_THRESHOLD)
            $parallel_jobs['players_detailed'] = Opcode::PLAYERS_DETAILED;

        $parallel_timeout = Performance::PARALLEL_TIMEOUT + (($this->cached_ping ?? 50) * Performance::PING_MULTIPLIER / 1000);
        $parallel_results = $this->Attempt_Query($parallel_jobs, min($parallel_timeout, 2.5), true);
        
        $results['rules'] = $parallel_results['rules'] ?? [];

        if (empty($results['rules'])) {
            $rules_retry = $this->Attempt_Query(['rules' => Opcode::RULES], $parallel_timeout * 1.2, true);
            $results['rules'] = $rules_retry['rules'] ?? [];
        }

        if ($this->cached_info->players > 0 && $this->cached_info->players < Query::LARGE_PLAYER_THRESHOLD) {
            $results['players_detailed'] = $parallel_results['players_detailed'] ?? [];
            
            if (empty($results['players_detailed'])) {
                $basic_result = $this->Attempt_Query(['players_basic' => Opcode::PLAYERS_BASIC], $parallel_timeout, true);
                $results['players_basic'] = $basic_result['players_basic'] ?? [];
                
                if (empty($results['players_basic']) && $this->cached_info->players > 0) {
                    usleep(100000);
                    $last_try = $this->Attempt_Query(['players_basic' => Opcode::PLAYERS_BASIC], $parallel_timeout * 1.5, true);
                    $results['players_basic'] = $last_try['players_basic'] ?? [];
                }
            }
            else
                $results['players_basic'] = [];
        }
        else {
            $results['players_detailed'] = [];
            $results['players_basic'] = [];
        }

        return [
            'is_online' => true, 
            'ping' => $this->cached_ping ?? 1, 
            'info' => $results['info'], 
            'rules' => $results['rules'],
            'players_detailed' => $results['players_detailed'], 
            'players_basic' => $results['players_basic'],
            'execution_time_ms' => round((microtime(true) - $start_time) * 1000, 2)
        ];
    }
    
    public function Is_Online(): bool {
        try {
            $this->cached_info = null;
            $this->response_cache = [];
            $this->Fetch_Server_State();

            return $this->cached_info !== null && $this->Validate_Response($this->cached_info, Opcode::INFO);
        }
        catch (Connection_Exception) {
            try {
                $result = $this->Attempt_Query(['info' => Opcode::INFO], Performance::METADATA_TIMEOUT * 2, true);

                return !empty($result['info']) && $this->Validate_Response($result['info'], Opcode::INFO);
            }
            catch (\Exception) {
                return false;
            }
        }
    }
    
    public function Get_Ping(): ?int {
        $this->Fetch_Server_State();
        
        if ($this->cached_ping === null) {
            $start = microtime(true);
            $ping_ref = null;

            $this->Execute_Query_Phase(['ping' => Opcode::PING], $ping_ref, Performance::FAST_PING_TIMEOUT);
            $this->cached_ping = $ping_ref ?? max(1, (int)round((microtime(true) - $start) * 1000));
        }
        
        return $this->cached_ping;
    }

    public function Get_Info(): ?Server_Info {
        $this->Fetch_Server_State();
        
        if (!$this->cached_info || !$this->Validate_Response($this->cached_info, Opcode::INFO)) {
            $this->cached_info = null;
            $this->Fetch_Server_State();
        }

        return $this->cached_info;
    }

    public function Get_Rules(): array {
        $this->Fetch_Server_State();
        
        $timeout = Performance::METADATA_TIMEOUT + (($this->cached_ping ?? 50) * Performance::PING_MULTIPLIER / 1000);
        $result = $this->Attempt_Query(['rules' => Opcode::RULES], min($timeout, 2.0), true);
        
        if (empty($result['rules'])) {
            usleep(50000);
            $result = $this->Attempt_Query(['rules' => Opcode::RULES], $timeout * 1.5, true);
        }

        return $result['rules'] ?? [];
    }

    public function Get_Players_Detailed(): array {
        $detailed_players = $this->Fetch_Player_Data(Opcode::PLAYERS_DETAILED);

        if ($detailed_players !== null) {
            if (is_array($detailed_players))
                return $detailed_players;
            
            $detailed_players = $this->Fetch_Player_Data(Opcode::PLAYERS_DETAILED);

            return is_array($detailed_players) ? $detailed_players : [];
        }
        
        return [];
    }

    public function Get_Players_Basic(): array {
        $basic_players = $this->Fetch_Player_Data(Opcode::PLAYERS_BASIC);
        
        if (is_array($basic_players))
            return $basic_players;
        
        $basic_players = $this->Fetch_Player_Data(Opcode::PLAYERS_BASIC);

        return is_array($basic_players) ? $basic_players : [];
    }
    
    public function Send_Rcon(string $rcon_password, string $command): string {
        if (empty($rcon_password))
            throw new Invalid_Argument_Exception("RCON password cannot be empty.");

        if (empty($command))
            throw new Invalid_Argument_Exception("RCON command cannot be empty.");
        
        $rcon_socket_manager = new Socket_Manager($this->ip, $this->port);
        $max_rcon_attempts = 3;

        try {
            $auth_response = null;
            
            for ($i = 0; $i < $max_rcon_attempts; $i++) {
                $auth_response = $this->Send_Single_Rcon_Request($rcon_socket_manager, $rcon_password, 'varlist');
                
                if ($auth_response !== null && !empty($auth_response))
                    break;
                    
                if ($i < $max_rcon_attempts - 1)
                    usleep(100000);
            }

            if ($auth_response === null || empty($auth_response))
                throw new Rcon_Exception("RCON authentication failed after {$max_rcon_attempts} attempts. Check password or server RCON status.");
        
            if (strtolower(trim($command)) === 'varlist')
                return $auth_response;
        
            $real_response = null;

            for ($i = 0; $i < $max_rcon_attempts; $i++) {
                $real_response = $this->Send_Single_Rcon_Request($rcon_socket_manager, $rcon_password, $command);
                
                if ($real_response !== null && !empty($real_response))
                    break;
                    
                if ($i < $max_rcon_attempts - 1)
                    usleep(100000);
            }

            if ($real_response !== null && !empty($real_response))
                return $real_response;
        
            $last_try = $this->Send_Single_Rcon_Request($rcon_socket_manager, $rcon_password, $command);

            if ($last_try !== null && !empty($last_try))
                return $last_try;
                
            return "Command sent successfully. (No text response from server after {$max_rcon_attempts} attempts)";
        }
        catch (\Exception $e) {
            if ($e instanceof Query_Exception)
                throw $e;
            
            $this->logger->Log("RCON command failed: " . $e->getMessage());
            
            throw new Rcon_Exception("RCON command failed: " . $e->getMessage(), 0, $e);
        }
        finally {
            $rcon_socket_manager?->Disconnect();
        }
    }
}