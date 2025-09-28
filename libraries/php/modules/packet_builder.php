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

final class Packet_Builder {
    private readonly array $ip_parts;

    public function __construct(private readonly string $ip, private readonly int $port) {
        $this->ip_parts = array_map('intval', explode('.', $this->ip));
    }

    private function Build_Header(): string {
        $packet = 'SAMP';
        $packet .= pack('c4', ...$this->ip_parts);
        $packet .= pack('v', $this->port);

        return $packet;
    }
    
    public function Build(string $opcode): string {
        $packet = $this->Build_Header();
        $packet .= $opcode;

        if ($opcode === Opcode::PING->value)
            $packet .= random_bytes(4); 

        return $packet;
    }

    public function Build_Rcon(string $rcon_password, string $command): string {
        $packet = $this->Build_Header();
        $packet .= Opcode::RCON->value;
        $packet .= pack('v', strlen($rcon_password));
        $packet .= $rcon_password;
        $packet .= pack('v', strlen($command));
        $packet .= $command;
        
        return $packet;
    }
}