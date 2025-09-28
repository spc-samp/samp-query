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

use Samp_Query\Constants\Protocol;
use Samp_Query\Exceptions\Malformed_Packet_Exception;
use Samp_Query\Exceptions\Rcon_Exception;
use Samp_Query\Models\{Server_Info, Players_Detailed, Players_Basic, Server_Rule};

final class Packet_Parser {
    private int $offset = Protocol::MIN_PACKET_LENGTH;
    private readonly int $data_length;

    public function __construct(private readonly string $data) {
        $this->data_length = strlen($this->data);

        if (substr($this->data, 0, 4) !== 'SAMP' || $this->data_length < Protocol::MIN_PACKET_LENGTH)
            throw new Malformed_Packet_Exception("Invalid response header or packet too short.");
    }

    public function Parse(Opcode $opcode): mixed {
        return match ($opcode) {
            Opcode::INFO => $this->Parse_Info(),
            Opcode::RULES => $this->Parse_Rules(),
            Opcode::PLAYERS_DETAILED => $this->Parse_Players(),
            Opcode::PLAYERS_BASIC => $this->Parse_Players_Basic(),
            Opcode::PING => true,
        };
    }
    
    private function Extract_String(int $length_bytes): string {
        if ($this->offset + $length_bytes > $this->data_length)
            return '';

        $format = match ($length_bytes) {
            1 => 'C',
            2 => 'v',
            4 => 'V',
            default => '',
        };

        if (empty($format))
            return '';

        $length_data = unpack($format, substr($this->data, $this->offset, $length_bytes));

        if ($length_data === false)
            return '';

        $length = $length_data[1];
        $this->offset += $length_bytes;

        if ($this->offset + $length > $this->data_length) {
            $this->offset -= $length_bytes;

            return '';
        }

        if ($length === 0)
            return '';

        $string = substr($this->data, $this->offset, $length);
        $this->offset += $length;
        
        return mb_convert_encoding($string, 'UTF-8', 'Windows-1252');
    }

    public function Parse_Info(): Server_Info {
        if ($this->offset + 5 > $this->data_length)
            throw new Malformed_Packet_Exception("Insufficient data for server info.");

        $info_data = unpack('cpassword/vplayers/vmax_players', substr($this->data, $this->offset, 5));

        if ($info_data === false)
            throw new Malformed_Packet_Exception("Corrupt server info packet.");

        $this->offset += 5;

        return new Server_Info((bool) $info_data['password'], (int) $info_data['players'], (int) $info_data['max_players'], $this->Extract_String(4), $this->Extract_String(4), $this->Extract_String(4));
    }
    
    public function Parse_Rules(): array {
        if ($this->offset + 2 > $this->data_length)
            return [];

        $count_data = unpack('v', substr($this->data, $this->offset, 2));

        if ($count_data === false)
            return [];

        $rule_count = $count_data[1];
        $this->offset += 2;
        $rules = [];

        for ($i = 0; $i < $rule_count; $i++) {
            if ($this->offset >= $this->data_length)
                break;
            
            $rules[] = new Server_Rule($this->Extract_String(1), $this->Extract_String(1));
        }

        return $rules;
    }
    
    public function Parse_Players(): array {
        if ($this->offset + 2 > $this->data_length)
            return [];

        $count_data = unpack('v', substr($this->data, $this->offset, 2));

        if ($count_data === false)
            return [];
        
        $player_count = $count_data[1];
        $this->offset += 2;
        $players = [];

        for ($i = 0; $i < $player_count; $i++) {
            if ($this->offset + 10 > $this->data_length)
                break;

            $id = unpack('C', $this->data[$this->offset++])[1];
            $name = $this->Extract_String(1);

            if ($this->offset + 8 > $this->data_length)
                break;

            $stats_data = unpack('Vscore/Vping', substr($this->data, $this->offset, 8));

            if ($stats_data === false)
                break;

            $this->offset += 8;
            $players[] = new Players_Detailed($id, $name, $stats_data['score'], $stats_data['ping']);
        }

        return $players;
    }

    public function Parse_Players_Basic(): array {
        if ($this->offset + 2 > $this->data_length)
            return [];

        $count_data = unpack('v', substr($this->data, $this->offset, 2));

        if ($count_data === false)
            return [];

        $player_count = $count_data[1];
        $this->offset += 2;
        $players = [];

        for ($i = 0; $i < $player_count; $i++) {
            if ($this->offset + 5 > $this->data_length)
                break;

            $name = $this->Extract_String(1);

            if ($this->offset + 4 > $this->data_length)
                break;

            $stats_data = unpack('Vscore', substr($this->data, $this->offset, 4));

            if ($stats_data === false)
                break;

            $this->offset += 4;
            $players[] = new Players_Basic($name, $stats_data['score']);
        }

        return $players;
    }

    public function Parse_Rcon(): string {
        if ($this->offset + 2 > $this->data_length)
            return "RCON response was empty. Check the RCON password.";

        $length_data = unpack('v', substr($this->data, $this->offset, 2));

        if ($length_data === false)
            return '';

        $length = $length_data[1];
        $this->offset += 2;

        if ($this->offset + $length > $this->data_length)
            throw new Malformed_Packet_Exception("RCON response length mismatch.");

        $response_text = substr($this->data, $this->offset, $length);
        $this->offset += $length;
        
        return mb_convert_encoding($response_text, 'UTF-8', 'Windows-1252');
    }
}