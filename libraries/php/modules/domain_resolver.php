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

use Samp_Query\Constants\DNS as DNS_Config;
use Samp_Query\Exceptions\Query_Exception;

final class Domain_Resolver {
    public function Resolve(string $hostname): string {
        if (filter_var($hostname, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
            return $hostname;

        $cache_file = DNS_Config::CACHE_DIR . md5($hostname) . '.cache';

        if (file_exists($cache_file) && (time() - filemtime($cache_file)) < DNS_Config::CACHE_TTL_SECONDS) {
            $ip = file_get_contents($cache_file);

            if ($ip && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
                return $ip;
        }
        
        $ip = gethostbyname($hostname);

        if ($ip === $hostname || !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
            throw new Query_Exception("Domain resolution failed for '{$hostname}'. Could not find a valid IPv4 address.");

        if (!is_dir(DNS_Config::CACHE_DIR))
            mkdir(DNS_Config::CACHE_DIR, 0755, true);

        file_put_contents($cache_file, $ip);

        return $ip;
    }

    public static function Clean_Expired_Cache(): void {
        if (!is_dir(DNS_Config::CACHE_DIR))
            return;

        foreach (scandir(DNS_Config::CACHE_DIR) as $file) {
            if ($file === '.' || $file === '..')
                continue;

            $path = DNS_Config::CACHE_DIR . $file;

            if (is_file($path) && (time() - filemtime($path)) >= DNS_Config::CACHE_TTL_SECONDS)
                unlink($path);
        }
    }
}