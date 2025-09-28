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
use Samp_Query\Exceptions\Connection_Exception;

final class Socket_Manager {
    private $socket = null;
    private float $current_timeout = 2.0;
    
    public function __construct(private readonly string $host, private readonly int $port) {}

    public function __destruct() {
        $this->Disconnect();
    }
    
    public function Get_Socket_Resource(float $timeout) {
        $this->Connect();
        $this->Set_Timeout($timeout);

        return $this->socket;
    }

    private function Connect(): void {
        if (is_resource($this->socket))
            return;

        $default_timeout = (float)ini_get("default_socket_timeout");

        $context = stream_context_create(['socket' => ['so_rcvbuf' => Protocol::KERNEL_RECEIVE_BUFFER_SIZE]]);
        $this->socket = stream_socket_client("udp://{$this->host}:{$this->port}", $err_no, $err_str, $default_timeout, STREAM_CLIENT_CONNECT, $context);

        if ($this->socket === false)
            throw new Connection_Exception("Could not create socket for {$this->host}:{$this->port}. Error: {$err_str} ({$err_no})");
    }

    public function Set_Timeout(float $timeout): void {
        if (!is_resource($this->socket) || $this->current_timeout === $timeout)
            return;

        $sec = (int)floor($timeout);
        $usec = (int)(($timeout - $sec) * 1_000_000);

        if (stream_set_timeout($this->socket, $sec, $usec))
            $this->current_timeout = $timeout;
    }
    
    public function Disconnect(): void {
        if (is_resource($this->socket)) {
            fclose($this->socket);
            $this->socket = null;
        }
    }
}