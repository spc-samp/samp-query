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

namespace Samp_Query\Exceptions;

class Query_Exception extends \Exception {}
class Invalid_Argument_Exception extends Query_Exception {}
class Connection_Exception extends Query_Exception {}
class Timeout_Exception extends Connection_Exception {}
class Malformed_Packet_Exception extends Query_Exception {}
class Rcon_Exception extends Query_Exception {}