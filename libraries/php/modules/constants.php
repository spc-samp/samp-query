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

namespace Samp_Query\Constants;

// General query behavior settings
final class Query {
    public const ATTEMPTS = 5;
    public const RETRY_DELAY_MS = 50;
    public const LARGE_PLAYER_THRESHOLD = 150;
    public const FAST_RETRY_ATTEMPTS = 2;
}

// Constants related to the network protocol and buffers
final class Protocol {
    public const MIN_PACKET_LENGTH = 11;
    public const UDP_READ_BUFFER_SIZE = 32768;
    public const KERNEL_RECEIVE_BUFFER_SIZE = 4194304;
}

// Timeout and performance settings
final class Performance {
    public const METADATA_TIMEOUT = 0.8;
    public const PLAYER_LIST_BASE_TIMEOUT = 1.0;
    public const INITIAL_RETRY_INTERVAL = 0.08;
    public const BACKOFF_FACTOR = 1.3;
    public const PING_MULTIPLIER = 2;
    public const PARALLEL_TIMEOUT = 1.2;
    public const FAST_PING_TIMEOUT = 0.3;
    public const MIN_RESPONSE_TIME = 0.005;
}

// Logger settings
final class Logger {
    public const FILE = __DIR__ . '/../logs.log';
    public const MAX_SIZE_BYTES = 10 * 1048576;
}

// DNS resolver cache settings
final class DNS {
    public const CACHE_DIR = __DIR__ . '/../dns/';
    public const CACHE_TTL_SECONDS = 3600;
}