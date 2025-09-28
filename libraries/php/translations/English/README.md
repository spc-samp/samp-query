# SA-MP Query - PHP

<div align="center">

![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Architecture](https://img.shields.io/badge/Protocol-UDP-blueviolet?style=for-the-badge)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**A robust and resilient PHP library, designed to query the state and information of SA-MP (San Andreas Multiplayer) and OMP (Open Multiplayer) servers.**

</div>

## Languages

- Português: [README](../../)
- Deutsch: [README](../Deutsch/README.md)
- Español: [README](../Espanol/README.md)
- Français: [README](../Francais/README.md)
- Italiano: [README](../Italiano/README.md)
- Polski: [README](../Polski/README.md)
- Русский: [README](../Русский/README.md)
- Svenska: [README](../Svenska/README.md)
- Türkçe: [README](../Turkce/README.md)

## Table of Contents

- [SA-MP Query - PHP](#sa-mp-query---php)
  - [Languages](#languages)
  - [Table of Contents](#table-of-contents)
  - [Overview](#overview)
  - [Design and Architecture Principles](#design-and-architecture-principles)
    - [Modular Architecture](#modular-architecture)
    - [Resilience: Backoff, Retries, and Caching](#resilience-backoff-retries-and-caching)
    - [Performance Optimization: Parallelism and Timeout Adaptation](#performance-optimization-parallelism-and-timeout-adaptation)
    - [Modern Object-Oriented Programming (OOP) (PHP 8.1+)](#modern-object-oriented-programming-oop-php-81)
  - [Requirements](#requirements)
  - [Installation and Basic Usage](#installation-and-basic-usage)
    - [Initializing the `Samp_Query` Class](#initializing-the-samp_query-class)
    - [`Get_All()`: Complete and Optimized Query](#get_all-complete-and-optimized-query)
    - [`Is_Online()`: Quick Status Check](#is_online-quick-status-check)
    - [`Get_Ping()`: Getting the Server Ping](#get_ping-getting-the-server-ping)
    - [`Get_Info()`: Essential Server Details](#get_info-essential-server-details)
    - [`Get_Rules()`: Configured Server Rules](#get_rules-configured-server-rules)
    - [`Get_Players_Detailed()`: List of Players with Details](#get_players_detailed-list-of-players-with-details)
    - [`Get_Players_Basic()`: Basic Player List](#get_players_basic-basic-player-list)
    - [`Send_Rcon()`: Sending Remote Commands](#send_rcon-sending-remote-commands)
  - [Detailed Library Structure and Execution Flow](#detailed-library-structure-and-execution-flow)
    - [1. `constants.php`: The Heart of Configuration](#1-constantsphp-the-heart-of-configuration)
    - [2. `opcode.php`: The Protocol Opcodes Enum](#2-opcodephp-the-protocol-opcodes-enum)
    - [3. `exceptions.php`: The Custom Exception Hierarchy](#3-exceptionsphp-the-custom-exception-hierarchy)
    - [4. `server_types.php`: The Immutable Data Models](#4-server_typesphp-the-immutable-data-models)
    - [5. `autoloader.php`: The Automatic Class Loader](#5-autoloaderphp-the-automatic-class-loader)
    - [6. `logger.php`: The Logging Subsystem](#6-loggerphp-the-logging-subsystem)
    - [7. `domain_resolver.php`: Domain Resolution with Persistent Cache](#7-domain_resolverphp-domain-resolution-with-persistent-cache)
    - [8. `socket_manager.php`: The Robust UDP Connection Manager](#8-socket_managerphp-the-robust-udp-connection-manager)
    - [9. `packet_builder.php`: The Binary Packet Builder](#9-packet_builderphp-the-binary-packet-builder)
    - [10. `packet_parser.php`: The Packet Decoder with Encoding Handling](#10-packet_parserphp-the-packet-decoder-with-encoding-handling)
    - [11. `samp-query.php`: The Main Class (The Complete Orchestrator)](#11-samp-queryphp-the-main-class-the-complete-orchestrator)
      - [Query Lifecycle: A Packet's Journey](#query-lifecycle-a-packets-journey)
        - [1. Initialization and Domain Resolution](#1-initialization-and-domain-resolution)
        - [2. `Fetch_Server_State()`: Cache and Critical INFO/PING Query](#2-fetch_server_state-cache-and-critical-infoping-query)
        - [3. `Attempt_Query()`: The Optimized Retries Strategy](#3-attempt_query-the-optimized-retries-strategy)
        - [4. `Execute_Query_Phase()`: The Communication Engine with Ping Detection](#4-execute_query_phase-the-communication-engine-with-ping-detection)
        - [5. `Validate_Response()`: The Semantic Validation Layer](#5-validate_response-the-semantic-validation-layer)
      - [Adaptive Timeout Calculation and Management](#adaptive-timeout-calculation-and-management)
      - [Public Query Methods](#public-query-methods)
      - [RCON Communication (`Send_Rcon`)](#rcon-communication-send_rcon)
  - [Error Diagnosis and Exceptions](#error-diagnosis-and-exceptions)
    - [`Invalid_Argument_Exception`](#invalid_argument_exception)
    - [`Connection_Exception`](#connection_exception)
    - [`Malformed_Packet_Exception`](#malformed_packet_exception)
    - [`Rcon_Exception`](#rcon_exception)
  - [License](#license)
    - [Terms and Conditions of Use](#terms-and-conditions-of-use)
      - [1. Granted Permissions](#1-granted-permissions)
      - [2. Mandatory Conditions](#2-mandatory-conditions)
      - [3. Copyright](#3-copyright)
      - [4. Disclaimer of Warranty and Limitation of Liability](#4-disclaimer-of-warranty-and-limitation-of-liability)

## Overview

The **SA-MP Query - PHP** library is a high-performance, fault-tolerant solution for PHP developers who need to interact with game servers based on the SA-MP/OMP (UDP) protocol. Its purpose is to encapsulate the complexity of the binary query protocol into a clean and intuitive PHP API, allowing web applications, launchers, and utilities to obtain detailed information about the server's state (players, rules, ping, etc.) quickly and reliably.

The library's design focuses on three main pillars: **Resilience**, **Performance**, and **Modularity**. It is built to handle the unreliable nature of the UDP protocol, implementing an advanced system of retries and *backoff* to ensure that information is obtained even under unfavorable network conditions or on high-latency servers.

## Design and Architecture Principles

### Modular Architecture

The library is divided into single-responsibility components, each encapsulated in its own class and file.

- **Network Infrastructure:** `Domain_Resolver`, `Socket_Manager`.
- **Protocol:** `Packet_Builder`, `Packet_Parser`, `Opcode` (Enum).
- **Business Logic (Orchestration):** `Samp_Query`.
- **Data Models:** `Server_Info`, `Players_Detailed`, etc.

### Resilience: Backoff, Retries, and Caching

The UDP protocol does not guarantee packet delivery. The `Samp_Query` class mitigates this failure with a sophisticated query cycle.

- **Multiple Adaptive Retries:** The `Attempt_Query` method implements a loop with up to `Query::ATTEMPTS` (5 by default) and double that for critical queries.
- **Backoff Strategy:** Exponential *backoff* is implemented in `Execute_Query_Phase`. After the first send, the listening retry interval (`while` loop) increases from `Performance::INITIAL_RETRY_INTERVAL` (0.08s) by the `Performance::BACKOFF_FACTOR` (1.3), up to a limit of 0.2s. This avoids packet overload and increases the chance of a timely response.
- **Response Caching:** Recent responses (valid for 2.0 seconds) are stored in `response_cache`, eliminating the need to repeat metadata queries during the execution of `Get_All()`.

### Performance Optimization: Parallelism and Timeout Adaptation

- **Parallel Queries (Fan-out):** The `Get_All()` method sends requests for `INFO`, `RULES`, and `PLAYERS` simultaneously (in `$jobs`), allowing responses to arrive out of order, minimizing total wait time.
- **Persistent DNS Caching:** The `Domain_Resolver` stores the resolved IP address in a local file cache with a TTL of 3600 seconds, avoiding domain resolution delays in subsequent calls.
- **Adaptive Timeout:** The *timeout* for large data queries (like the player list) is dynamically adjusted based on the server's `cached_ping`:
   ```
   Adjusted Timeout = Base Timeout + (Cached Ping * Ping Multiplier / 1000)
   ```
   This logic (implemented in `Fetch_Player_Data`) ensures that high-latency servers have enough time to respond without compromising speed on low-latency servers.

### Modern Object-Oriented Programming (OOP) (PHP 8.1+)

The library uses modern PHP features to ensure safety and clarity:

- **Strict Typing** (`declare(strict_types=1)`).
- **Read-Only Properties** (`public readonly` in `Samp_Query` and data models) to ensure data immutability.
- **Typed Enums** (`enum Opcode: string`) for safe protocol control.
- **Constructor Property Promotion** (in `Samp_Query::__construct` and models).

## Requirements

- **PHP:** Version **8.1 or higher**.
- **PHP Extensions:** `sockets` and `mbstring` (for handling UTF-8 encoding).

## Installation and Basic Usage

To start using the **SA-MP Query - PHP** library, simply include the `samp-query.php` file in your project. This file will automatically load all dependencies through its internal autoloader.

```php
<?php
// Include the main class. It will handle loading dependencies via autoloader.
require_once 'path/to/samp-query/samp-query.php'; 

// Use the namespace of the main class
use Samp_Query\Samp_Query;
// Include exceptions for more specific error handling
use Samp_Query\Exceptions\Invalid_Argument_Exception;
use Samp_Query\Exceptions\Connection_Exception;
use Samp_Query\Exceptions\Rcon_Exception;

// Instantiate the Samp_Query class, wrapping it in a try-catch block to handle initialization errors.
try {
    $server_query = new Samp_Query("play.example-samp-server.com", 7777);
    // You can now use the public methods of $server_query
}
catch (Invalid_Argument_Exception $e) {
    echo "Argument Error: " . $e->getMessage() . "\n";
}
catch (Connection_Exception $e) {
    echo "Connection Error: " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Unexpected error during initialization: " . $e->getMessage() . "\n";
}
```

### Initializing the `Samp_Query` Class

The `Samp_Query` class is the entry point for all functionalities. Its constructor requires the `hostname` (or IP address) and `port` of the server you want to query.

```php
/**
 * Initializes a new instance of the Samp_Query library.
 *
 * @param string $hostname The hostname or IP address of the SA-MP/OMP server.
 * @param int $port The UDP port of the server (usually 7777).
 * 
 * @throws Invalid_Argument_Exception If the hostname is empty or the port is invalid.
 * @throws Connection_Exception If DNS resolution fails or the socket cannot be created.
 */
public function __construct(public readonly string $hostname, public readonly int $port)
```

<br>

---

### `Get_All()`: Complete and Optimized Query

This is the most comprehensive and recommended method. It executes several queries (INFO, RULES, PLAYERS) in parallel and in an optimized way, minimizing the total response time and returning a complete associative array with all available information.

```php
/**
 * Returns all available server information in a single optimized call.
 * Includes: is_online, ping, info (Server_Info), rules (array of Server_Rule),
 * players_detailed (array of Players_Detailed), players_basic (array of Players_Basic),
 * and execution_time_ms.
 *
 * @return array An associative array containing all server information.
 * 
 * @throws Connection_Exception If the INFO query, essential for server state, fails.
 */
public function Get_All(): array
```

Example Usage:

```php
<?php
// ... (class initialization $server_query) ...

try {
    $data = $server_query->Get_All();
    
    if ($data['is_online']) {
        echo "## Server Online: {$data['info']->hostname} ({$server_query->ip}:{$server_query->port}) ##\n";
        echo "Ping: {$data['ping']}ms | Total Query Time: {$data['execution_time_ms']}ms\n";
        echo "Players: {$data['info']->players} / {$data['info']->max_players}\n";
        echo "Game Mode: {$data['info']->gamemode}\n";
        echo "Language: {$data['info']->language}\n";
        echo "Password Protected: " . ($data['info']->password ? "Yes" : "No") . "\n\n";

        // Example of detailed player list
        if (!empty($data['players_detailed'])) {
            echo "--- Detailed Players ({$data['info']->players} Active) ---\n";

            foreach ($data['players_detailed'] as $player)
                echo "ID: {$player->id}, Name: {$player->name}, Ping: {$player->ping}ms, Score: {$player->score}\n";
        }
        elseif (!empty($data['players_basic'])) {
            echo "--- Basic Players ({$data['info']->players} Active) (Fallback) ---\n";

            foreach ($data['players_basic'] as $player)
                echo "Name: {$player->name}, Score: {$player->score}\n";
        }
        else
            echo "No players online or list unavailable (perhaps too many players).\n";
        
        // Example of server rules
        if (!empty($data['rules'])) {
            echo "\n--- Server Rules ---\n";

            foreach ($data['rules'] as $rule)
                echo "{$rule->name}: {$rule->value}\n";
        }
    }
    else
        echo "The server is currently offline or inaccessible.\n";
}
catch (Connection_Exception $e) {
    echo "Connection failure when trying to get all information: " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Unexpected error when querying all information: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Is_Online()`: Quick Status Check

Checks if the server is online and responding to queries, without fetching additional details. Ideal for a simple "liveness check".

```php
/**
 * Checks if the server is online and accessible.
 *
 * @return bool Returns true if the server is online and responds with valid INFO, false otherwise.
 */
public function Is_Online(): bool
```

Example Usage:

```php
<?php
// ... (class initialization $server_query) ...

if ($server_query->Is_Online())
    echo "The SA-MP server is online and responding!\n";
else
    echo "The SA-MP server is offline or did not respond in time.\n";
```

<br>

---

### `Get_Ping()`: Getting the Server Ping

Returns the server's latency (ping) in milliseconds. This value is cached internally for optimization.

```php
/**
 * Returns the current server ping in milliseconds.
 * If the ping has not yet been calculated, a quick PING query will be executed.
 *
 * @return int|null The ping value in milliseconds, or null if it cannot be obtained.
 */
public function Get_Ping(): ?int
```

Example Usage:

```php
<?php
// ... (class initialization $server_query) ...

try {
    $ping = $server_query->Get_Ping();

    if ($ping !== null)
        echo "The server's ping is: {$ping}ms.\n";
    else
        echo "Could not get the server's ping.\n";
}
catch (Connection_Exception $e) {
    echo "Error getting ping: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Info()`: Essential Server Details

Gets the basic server information, such as hostname, game mode, number of players, etc. Returns a `Server_Info` object.

```php
/**
 * Returns the basic server details (hostname, players, gamemode, etc.).
 * The data is cached for a short period for optimization.
 *
 * @return Server_Info|null A Server_Info object, or null if the information cannot be obtained.
 */
public function Get_Info(): ?Server_Info
```

Example Usage:

```php
<?php
use Samp_Query\Models\Server_Info;
// ... (class initialization $server_query) ...

try {
    /** @var Server_Info|null $info */
    $info = $server_query->Get_Info();

    if ($info) {
        echo "--- Server Information ---\n";
        echo "Hostname: {$info->hostname}\n";
        echo "Gamemode: {$info->gamemode}\n";
        echo "Players: {$info->players} / {$info->max_players}\n";
        echo "Language: {$info->language}\n";
        echo "Password Protected: " . ($info->password ? "Yes" : "No") . "\n";
    }
    else
        echo "Could not get server information.\n";
}
catch (Connection_Exception $e) {
    echo "Error getting server information: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Rules()`: Configured Server Rules

Retrieves all the rules configured on the server, such as `mapname`, `weburl`, `weather`, etc., returning them as an array of `Server_Rule` objects.

```php
/**
 * Returns an array of Server_Rule objects, each containing the name and value of a server rule.
 * The data is cached for optimization.
 *
 * @return array An array of Samp_Query\Models\Server_Rule. Can be empty if there are no rules.
 */
public function Get_Rules(): array
```

Example Usage:

```php
<?php
use Samp_Query\Models\Server_Rule;
// ... (class initialization $server_query) ...

try {
    $rules = $server_query->Get_Rules();

    if (!empty($rules)) {
        echo "--- Server Rules ---\n";

        foreach ($rules as $rule)
            echo "{$rule->name}: {$rule->value}\n";

        // Example of how to access a specific rule:
        $mapname_rule = array_filter($rules, fn(Server_Rule $r) => $r->name === 'mapname');

        if (!empty($mapname_rule))
            echo "\nCurrent map: " . reset($mapname_rule)->value . "\n";
    }
    else
        echo "No rules found for this server.\n";
}
catch (Connection_Exception $e) {
    echo "Error getting server rules: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Players_Detailed()`: List of Players with Details

Gets a detailed list of currently online players, including ID, name, score, and ping.

> [!CAUTION]
> To optimize performance and avoid excessively large UDP packets that can be lost or fragmented, this method will not fetch the detailed player list if the total number of players is equal to or greater than `Query::LARGE_PLAYER_THRESHOLD` (150 by default). In such cases, an empty array will be returned. Consider using `Get_Players_Basic()` as a fallback.

```php
/**
 * Returns an array of Players_Detailed objects (ID, name, score, ping) for each online player.
 * This query may be skipped if the number of players is too high (see Query::LARGE_PLAYER_THRESHOLD).
 *
 * @return array An array of Samp_Query\Models\Players_Detailed. Can be empty.
 */
public function Get_Players_Detailed(): array
```

Example Usage:

```php
<?php
use Samp_Query\Models\Players_Detailed;
// ... (class initialization $server_query) ...

try {
    $players_detailed = $server_query->Get_Players_Detailed();

    if (!empty($players_detailed)) {
        echo "--- Online Players (Detailed) ---\n";

        foreach ($players_detailed as $player)
            echo "ID: {$player->id}, Name: {$player->name}, Score: {$player->score}, Ping: {$player->ping}ms\n";
    }
    else
        echo "No players online or detailed list unavailable.\n";
}
catch (Connection_Exception $e) {
    echo "Error getting the detailed player list: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Players_Basic()`: Basic Player List

Provides a lighter list of players, containing only name and score. Useful as an alternative when the detailed list is unavailable or to reduce data load.

```php
/**
 * Returns an array of Players_Basic objects (name, score) for each online player.
 * Useful as a lighter alternative or fallback when Get_Players_Detailed() is not feasible.
 *
 * @return array An array of Samp_Query\Models\Players_Basic. Can be empty.
 */
public function Get_Players_Basic(): array
```

Example Usage:

```php
<?php
use Samp_Query\Models\Players_Basic;
// ... (class initialization $server_query) ...

try {
    $players_basic = $server_query->Get_Players_Basic();

    if (!empty($players_basic)) {
        echo "--- Online Players (Basic) ---\n";

        foreach ($players_basic as $player)
            echo "Name: {$player->name}, Score: {$player->score}\n";
    }
    else
        echo "No players online or basic list unavailable.\n";
}
catch (Connection_Exception $e) {
    echo "Error getting the basic player list: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Send_Rcon()`: Sending Remote Commands

Allows sending commands to the server's RCON console, such as changing rules, banning players, etc. Requires the server's RCON password.

> [!WARNING]
> The RCON function is sensitive and can cause changes to the server. Use with caution and only with trusted passwords.
> It is crucial that the RCON password is **correct** and that RCON is **enabled** on the server (`rcon_password` setting in `server.cfg`).

```php
/**
 * Sends an RCON command to the server.
 * Performs authentication with 'varlist' and sends the command.
 *
 * @param string $rcon_password The RCON password of the server.
 * @param string $command The command to be executed (e.g., "gmx", "kick ID").
 * @return string The server's response to the RCON command, or a status message.
 * 
 * @throws Invalid_Argument_Exception If the RCON password or command is empty.
 * @throws Rcon_Exception If RCON authentication fails or the command gets no response.
 * @throws Connection_Exception In case of a connection failure during the RCON operation.
 */
public function Send_Rcon(string $rcon_password, string $command): string
```

Example Usage:

```php
<?php
// ... (class initialization $server_query) ...

$rcon_password = "your_secret_password_here"; 
$command_to_send = "gmx"; // Example: restart the gamemode

try {
    echo "Attempting to send RCON command: '{$command_to_send}'...\n";
    $response = $server_query->Send_Rcon($rcon_password, $command_to_send);
    echo "RCON Response: {$response}\n";

    // Example of a command to "say" something on the server (requires RCON)
    $response_say = $server_query->Send_Rcon($rcon_password, "say Hello from my PHP script!");
    echo "RCON Response (say): {$response_say}\n";
}
catch (Invalid_Argument_Exception $e) {
    echo "RCON Error (Invalid Argument): " . $e->getMessage() . "\n";
}
catch (Rcon_Exception $e) {
    echo "RCON Error: Authentication failed or command not executed. " . $e->getMessage() . "\n";
}
catch (Connection_Exception $e) {
    echo "RCON Error (Connection): " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Unexpected error while sending RCON: " . $e->getMessage() . "\n";
}
```

## Detailed Library Structure and Execution Flow

The **SA-MP Query - PHP** library is meticulously organized into several files, each with a well-defined responsibility. This section explores each component in detail, revealing the design decisions and underlying logic.

### 1. `constants.php`: The Heart of Configuration

This file centralizes all the "magic" configuration parameters of the library, ensuring that aspects like *timeouts*, number of retries, and buffer sizes are easily adjustable and consistent throughout the project.

```php
// ...
final class Query {
    public const ATTEMPTS = 5; // Maximum number of query attempts
    public const LARGE_PLAYER_THRESHOLD = 150; // Player limit for detailed query
}

final class Protocol {
    public const UDP_READ_BUFFER_SIZE = 32768; // 32KB for the read buffer
    public const KERNEL_RECEIVE_BUFFER_SIZE = 4194304; // 4MB for the kernel buffer
}
// ...
```

- **Final Classes and Constants:** The classes are `final` and the properties are `public const`, ensuring immutability and global accessibility at compile time.
- **Granularity and Semantics:** The constants are categorized by their domain (`Query`, `Protocol`, `Performance`, `Logger`, `DNS`), making them easier to understand and maintain. For example, `Query::LARGE_PLAYER_THRESHOLD` defines the point at which fetching detailed player lists can be skipped for optimization, due to the volume of data and potential for *timeouts*.

### 2. `opcode.php`: The Protocol Opcodes Enum

This file defines the operation codes (opcodes) used for the different queries to the SA-MP/OMP server, encapsulating them in a typed `enum`.

```php
// ...
enum Opcode: string {
    case INFO = 'i';
    case RULES = 'r';
    case PLAYERS_DETAILED = 'd';
    // ...
}
```

- **Typed `Enum` (PHP 8.1+):** The use of a typed `enum` (`Opcode: string`) with `string` values ensures that the opcodes are always valid and that the code is semantically clear. This replaces the use of "magic" string literals, making the code more readable and less prone to typos.

### 3. `exceptions.php`: The Custom Exception Hierarchy

This file establishes a hierarchy of custom exceptions, allowing for more granular and specific error handling for the various types of failures that can occur in the library.

```php
// ...
class Query_Exception extends \Exception {}
class Invalid_Argument_Exception extends Query_Exception {}
class Connection_Exception extends Query_Exception {}
// ...
```

- **Inheritance from `\Exception`:** All exceptions inherit from `Query_Exception` (which in turn extends `\Exception`), allowing you to catch groups of errors (`Connection_Exception` and `Timeout_Exception` are more specific than `Query_Exception`) or all library exceptions with a more generic `catch`.
- **Clarity in Diagnosis:** The descriptive names of the exceptions facilitate error diagnosis and recovery in the client application.

### 4. `server_types.php`: The Immutable Data Models

This file defines the classes that represent the data models for the information returned by the server, ensuring data integrity through immutability.

```php
// ...
final class Server_Info {
    public function __construct(
        public readonly bool $password,
        public readonly int $players,
        // ... other readonly properties ...
    ) {}
}
// ...
```

- **`final class`:** The classes are `final`, preventing extensions and guaranteeing their structure and behavior.
- **`public readonly` Properties (PHP 8.1+):** All properties are declared as `public readonly`. This means that once the object is constructed, its values cannot be changed, ensuring the integrity of the data received from the server.
- **Constructor Property Promotion (PHP 8.1+):** The properties are declared directly in the constructor, simplifying the code and reducing *boilerplate*.

### 5. `autoloader.php`: The Automatic Class Loader

This file is responsible for dynamically loading the library's classes when they are needed, following the PSR-4 standard.

```php
// ...
spl_autoload_register(function ($class) {
    $prefix = 'Samp_Query\\';
    $base_dir = __DIR__ . '/../modules/'; // Maps the namespace to the directory
    // ... loading logic ...
});

// Includes essential files that are not classes, or that need to be loaded early
require_once __DIR__ . '/../modules/constants.php';
require_once __DIR__ . '/../modules/exceptions.php';
// ...
```

- **`spl_autoload_register()`:** Registers an anonymous function that is automatically called by PHP when an undefined class is referenced, speeding up development and maintenance.
- **Direct Inclusion of Configurations:** Files like `constants.php` and `exceptions.php` are included directly. This ensures that their definitions are available before any class that uses them is instantiated by the autoloader.

### 6. `logger.php`: The Logging Subsystem

The `Logger` class provides a simple mechanism for recording error messages and important events to a log file, useful for debugging and monitoring.

```php
// ...
final class Logger {
    public function Log(string $message): void {
        if (is_file(Logger_Config::FILE) && filesize(Logger_Config::FILE) > Logger_Config::MAX_SIZE_BYTES)
            unlink(Logger_Config::FILE); // Removes the log if it exceeds the size

        $timestamp = date('Y-m-d H:i:s');
        $log_entry = "[{$timestamp}] {$message}" . PHP_EOL;
        
        file_put_contents(Logger_Config::FILE, $log_entry, FILE_APPEND | LOCK_EX); // Appends with a lock
    }
}
```

- **Automatic Cleanup:** The logger checks the size of the log file (`Logger_Config::FILE`). If it exceeds `Logger_Config::MAX_SIZE_BYTES` (10 MB by default), the file is deleted to prevent it from growing indefinitely.
- **File Locking (`LOCK_EX`):** `file_put_contents` uses `LOCK_EX` to ensure that only one process writes to the log file at a time, preventing corruption in multi-threaded/multi-process environments.

### 7. `domain_resolver.php`: Domain Resolution with Persistent Cache

The `Domain_Resolver` class is responsible for converting hostnames (like `play.example.com`) into IP addresses (like `192.0.2.1`). It implements a disk-based caching system to optimize performance.

```php
// ...
final class Domain_Resolver {
    public function Resolve(string $hostname): string {
        if (filter_var($hostname, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
            return $hostname; // Already an IP

        $cache_file = DNS_Config::CACHE_DIR . md5($hostname) . '.cache';

        if (file_exists($cache_file) && (time() - filemtime($cache_file)) < DNS_Config::CACHE_TTL_SECONDS) {
            $ip = file_get_contents($cache_file);

            if ($ip && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
                return $ip;
        }
        
        $ip = gethostbyname($hostname); // Actual DNS resolution
        // ... validation and caching logic ...

        return $ip;
    }
    // ...
}
```

- **Persistent Cache:** Before calling `gethostbyname()`, it checks if the IP is already stored in a cache file (`dns/` + MD5 hash of the hostname). The cache is considered valid if it has not exceeded `DNS_Config::CACHE_TTL_SECONDS` (3600 seconds or 1 hour by default).
- **Robust Validation:** The resolved IP (or the one read from the cache) is validated with `filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)` to ensure it is a valid IPv4. If the resolution fails, a `Query_Exception` is thrown.

### 8. `socket_manager.php`: The Robust UDP Connection Manager

The `Socket_Manager` class encapsulates the complexity of creating, configuring, and managing a UDP socket for communication with the game server.

```php
// ...
final class Socket_Manager {
    private $socket = null;
    // ...
    private function Connect(): void {
        if (is_resource($this->socket))
            return;

        $context = stream_context_create([
            'socket' => ['so_rcvbuf' => Protocol::KERNEL_RECEIVE_BUFFER_SIZE] // Increases buffer to 4MB
        ]);

        $this->socket = stream_socket_client(
            "udp://{$this->host}:{$this->port}", 
            $err_no, $err_str, 
            (float)ini_get("default_socket_timeout"), 
            STREAM_CLIENT_CONNECT, // Connects the socket to the remote address
            $context
        );

        if ($this->socket === false)
            throw new Connection_Exception("Could not create socket...");
    }
    // ...
}
```

- **`stream_socket_client` with `STREAM_CLIENT_CONNECT`:** This *flag* instructs the operating system to "connect" the UDP socket to the remote address. Although UDP is connectionless, "connecting" the socket allows for kernel-level performance optimizations, such as not needing to specify the remote address in every `fwrite` or `stream_socket_recvfrom` call, resulting in lower overhead.
- **Kernel Receive Buffer:** `stream_context_create` is used to increase the size of the kernel's receive buffer (`so_rcvbuf`) to `Protocol::KERNEL_RECEIVE_BUFFER_SIZE` (4MB). This is crucial to prevent packet loss (buffer overflow) when receiving large responses, such as detailed player lists from busy servers.
- **RAII via `__destruct`:** The `Disconnect()` method is automatically invoked in the destructor (`__destruct`), ensuring that the socket is closed and resources are released, even in the event of exceptions.
- **Dynamic Timeout:** `Set_Timeout` precisely adjusts the socket's read/write timeouts using `stream_set_timeout`, which is fundamental for the *retries* and *backoff* logic.

### 9. `packet_builder.php`: The Binary Packet Builder

The `Packet_Builder` class is responsible for serializing the query data into a specific binary format that the SA-MP/OMP server can understand.

```php
// ...
final class Packet_Builder {
    // ...
    private function Build_Header(): string {
        $packet = 'SAMP';
        $packet .= pack('c4', ...$this->ip_parts); // IP in 4 bytes
        $packet .= pack('v', $this->port); // Port in 2 bytes (little-endian)

        return $packet;
    }
    
    public function Build(string $opcode): string {
        $packet = $this->Build_Header();
        $packet .= $opcode;

        if ($opcode === Opcode::PING->value)
            $packet .= random_bytes(4); // Random payload for PING
        
        return $packet;
    }
    // ...
}
```

- **`pack()` for Binary Format:** Uses PHP's `pack()` function to convert data (IP, port, string lengths) into its correct binary format, such as `c4` for 4 character bytes (IP) and `v` for a 16-bit unsigned integer (port and lengths), which is *little-endian*.
- **Standard Header:** `Build_Header()` creates the 10-byte 'SAMP' header that precedes all packets.
- **RCON Structure:** `Build_Rcon` formats the RCON packet with the 'x' opcode followed by the password length, the password, the command length, and the command itself.

### 10. `packet_parser.php`: The Packet Decoder with Encoding Handling

The `Packet_Parser` class is the counterpart to the `Packet_Builder`, responsible for interpreting the binary responses received from the server and converting them into structured PHP data.

```php
// ...
final class Packet_Parser {
    private int $offset = Protocol::MIN_PACKET_LENGTH; // Starts after the header (11 bytes)
    // ...
    public function __construct(private readonly string $data) {
        // Initial validation of the 'SAMP' header
        if (substr($this->data, 0, 4) !== 'SAMP' || strlen($this->data) < Protocol::MIN_PACKET_LENGTH)
            throw new Malformed_Packet_Exception("Invalid response header or packet too short.");
    }
    
    private function Extract_String(int $length_bytes): string {
        // ... logic to read the length and the string ...
        $string = substr($this->data, $this->offset, $length);
        $this->offset += $length;
        
        // **CRITICAL ENCODING CONVERSION:** SA-MP/OMP servers use Windows-1252
        return mb_convert_encoding($string, 'UTF-8', 'Windows-1252');
    }
    // ...
}
```

- **`offset` and `data_length`:** The `offset` is used to track the current position in the packet reading, while `data_length` prevents reads beyond the buffer's limits.
- **Header Validation:** The constructor validates the "magic string" `SAMP` to immediately reject malformed packets.
- **`Extract_String()` - Crucial Encoding Conversion:** This is one of the most important features. The SA-MP protocol transmits strings using the **Windows-1252** encoding. To ensure that special characters (like accents or Cyrillic letters) are displayed correctly in PHP applications (which usually operate in UTF-8), the `mb_convert_encoding($string, 'UTF-8', 'Windows-1252')` method is applied.
- **Variable Length Extraction:** The `Extract_String` method supports different length prefix sizes for strings (1, 2, or 4 bytes), making it flexible for various protocol fields.

### 11. `samp-query.php`: The Main Class (The Complete Orchestrator)

The `Samp_Query` class is the main entry point and the orchestrator of all operations. It ties all the components together, managing the query state, retry logic, and timeouts.

#### Query Lifecycle: A Packet's Journey

The entire process of querying a server follows a sequence of carefully orchestrated steps, aiming for maximum resilience and performance.

##### 1. Initialization and Domain Resolution

When an instance of `Samp_Query` is created:
- **Quick Validation:** The constructor validates the `$hostname` and `$port` parameters. Any inconsistency results in an `Invalid_Argument_Exception`.
- **DNS Cache Cleanup:** `Domain_Resolver::Clean_Expired_Cache()` is invoked to ensure that only valid and unexpired DNS entries are considered.
- **IP Resolution:** The `Domain_Resolver` is used to convert the `$hostname` into an IP address (`$this->ip`). This IP is cached on disk for future requests, and a `Query_Exception` is thrown if the resolution fails.
- **Resource Setup:** `Logger`, `Socket_Manager`, and `Packet_Builder` are instantiated, preparing the infrastructure for communication.

##### 2. `Fetch_Server_State()`: Cache and Critical INFO/PING Query

This internal method is a performance and consistency guardian, ensuring that the basic server information (`Server_Info`) and `ping` are always up-to-date before any main query.

- **Primary Cache (5 Seconds):** Before initiating any communication, it checks if `$this->cached_info` (the server's `Server_Info` object) has data less than 5 seconds old (compared to `$this->last_successful_query`). If the data is fresh, the function returns immediately, avoiding unnecessary network traffic.
- **Critical INFO Query:** If the cache is expired or empty, the `Attempt_Query` method is invoked to get the `INFO` data. This query is marked as **critical** (`true`), which triggers a higher number of retries and more generous *timeouts*. A `Connection_Exception` is thrown if the INFO response is invalid after all attempts.
- **Ping Calculation:** If `$this->cached_ping` is still null, a quick `PING` query (`Execute_Query_Phase` with `Performance::FAST_PING_TIMEOUT`) is performed. The ping is calculated as the elapsed time until the **first** response is received, ensuring accuracy.

##### 3. `Attempt_Query()`: The Optimized Retries Strategy

This is the brain of the library's resilience, managing the high-level retry cycle for one or more `$jobs` (opcode queries).

- **Response Cache (2 Seconds):** First, it checks if the responses for any of the `$jobs` are already in `$this->response_cache` (less than 2.0 seconds old). This prevents unnecessary *retries* for volatile but non-critical data.
- **Fast Retries Phase:** The library first tries `Query::FAST_RETRY_ATTEMPTS` (2 by default) with a shorter *timeout* (`$timeout * 0.6`). The goal is to get a response as quickly as possible without introducing significant delays.
- **Standard Retries Phase with Backoff:** If the fast phase is not enough, the cycle continues with the remaining `Query::ATTEMPTS`. In this phase, the `$adjusted_timeout` increases progressively with each attempt, giving the server more time to respond. More importantly, `usleep()` introduces a growing delay (based on `Query::RETRY_DELAY_MS` and an increasing factor) between calls to `Execute_Query_Phase`, allowing the network and server to stabilize.
- **Emergency Retries (for Critical Queries):** For `$jobs` marked as `critical`, if all previous attempts fail, a final *retry* is made for each job individually, using an even longer *timeout* (`$timeout * 2`). This is a last chance to obtain vital information.

##### 4. `Execute_Query_Phase()`: The Communication Engine with Ping Detection

This low-level method is where the actual interaction with the UDP socket happens. It manages the sending and receiving of packets for a group of `$jobs` simultaneously in a single network phase.

```php
// ... (inside Execute_Query_Phase)
private function Execute_Query_Phase(array $jobs, ?int &$ping, float $timeout): array {
    $socket = $this->socket_manager->Get_Socket_Resource($timeout);
    stream_set_blocking($socket, false); // Non-blocking socket

    // Sends packets twice immediately for greater UDP delivery guarantee
    for ($i = 0; $i < 2; $i++) {
        foreach ($pending_jobs as $opcode)
            fwrite($socket, $this->packet_builder->Build($opcode->value));

        if ($i == 0)
            usleep(5000); // Small delay
    }

    $last_send_time = microtime(true);
    $current_retry_interval = Performance::INITIAL_RETRY_INTERVAL; // 0.08s
    
    while (!empty($pending_jobs) && (microtime(true) - $start_time) < $timeout) {
        $now = microtime(true);

        if (($now - $last_send_time) > $current_retry_interval) { // Resend logic with backoff
            // ... resends pending packets ...
            $current_retry_interval = min($current_retry_interval * Performance::BACKOFF_FACTOR, 0.2); // Increases the retry interval
        }

        $read = [$socket]; $write = $except = null

        if (stream_select($read, $write, $except, 0, 10000) > 0) { // Waits for data (max 10ms)
            $response = stream_socket_recvfrom($socket, Protocol::UDP_READ_BUFFER_SIZE);
            // ... parsing, ping calculation, and validation logic ...
        }

        if (!empty($pending_jobs))
            usleep(1000); // Small delay to avoid CPU spin
    }
    return $phase_results;
}
```

- **Non-Blocking Socket:** `stream_set_blocking($socket, false)` is essential. It allows PHP to send packets and then wait for responses without blocking execution, by using `stream_select`.
- **Double Send for Robustness:** The packets for all `$pending_jobs` are sent **twice** consecutively (with a small `usleep(5000)` between them) at the beginning of the phase. This practice is fundamental in UDP protocols to significantly increase the probability of delivery on unstable or lossy networks, mitigating the unreliable nature of UDP. For `INFO` and `PING`, a third additional send is made during the *retries* in the main loop.
- **Receive Loop with Adaptive Backoff:**
   - A main `while` loop continues until all `$jobs` are completed or the phase *timeout* expires.
   - **Dynamic Resend:** If the time elapsed since the last send (`$now - $last_send_time`) exceeds the `$current_retry_interval`, the packets for the `$pending_jobs` are resent. The `$current_retry_interval` is then increased (`min($current_retry_interval * Performance::BACKOFF_FACTOR, 0.2)`), implementing an exponential *backoff* that avoids overloading the server and maximizes the chances of a response.
   - **Optimized `stream_select`:** `stream_select($read, $write, $except, 0, 10000)` is used to wait for data for a maximum of 10 milliseconds. This allows the library to be responsive and process packets as soon as they arrive.
   - **Precise Ping Measurement:** When the **first** valid packet is received (`$packets_received === 0`), the `ping` is calculated with high precision as the difference between `microtime(true)` at the start of sending the first batch of packets and the exact time of receiving the **first** valid packet.
- **Response Processing and Validation:** The received responses are decoded by the `Packet_Parser`. If a `Malformed_Packet_Exception` is detected, it is logged, and the packet is immediately resent to the server to try again. The decoded response is then validated by `Validate_Response()`. If it is valid, it is added to the `$phase_results` and `$this->response_cache`.

##### 5. `Validate_Response()`: The Semantic Validation Layer

This crucial method, implemented in the `Samp_Query` class, checks the integrity and logical consistency of the decoded data before it is delivered to the user.

```php
// ... (inside Validate_Response)
private function Validate_Response($data, Opcode $opcode): bool {
    switch ($opcode) {
        case Opcode::INFO:
            // Ensures the hostname is not empty and player counts are logical
            return $data instanceof Server_Info && $data->max_players > 0 && $data->players >= 0 && $data->players <= $data->max_players && !empty($data->hostname);
        
        case Opcode::RULES:
            if (!is_array($data))
                return false;

            // ...

            return true;
        // ... validations for PLAYERS_DETAILED, PLAYERS_BASIC, PING ...
    }
}
```

- **Validation by Opcode:** Each `Opcode` has its own specific validation logic. For example:
   - For `Opcode::INFO`: Ensures that `$data` is an instance of `Server_Info`, that `$data->max_players > 0`, `$data->players` is between 0 and `max_players`, and that `$data->hostname` is not empty.
   - For `Opcode::RULES` or player lists: Checks if the return is an `array` and, if not empty, if the first element is of the expected model instance (`Server_Rule`, `Players_Detailed`, etc.).
- **Robustness:** If the validation fails, the response is considered invalid and is discarded. This forces the system to continue retrying, as if the packet never arrived, protecting the application from corrupt or inconsistent server data.

#### Adaptive Timeout Calculation and Management

The library implements a sophisticated *timeout* strategy to balance speed and resilience:

- **`Performance::METADATA_TIMEOUT`:** (0.8 seconds) This is the base *timeout* for quick queries like `INFO` and `RULES`.
- **`Performance::PLAYER_LIST_BASE_TIMEOUT`:** (1.0 second) This is the base *timeout* for player list queries.
- **`Performance::PING_MULTIPLIER`:** (2) Used to adjust the *timeout* based on the ping.
- **Adjustment by Ping:** In the `Fetch_Player_Data` method, the *timeout* for getting the player list is dynamically adjusted:
   ```
   Player Timeout = PLAYER_LIST_BASE_TIMEOUT + (Cached Ping * PING_MULTIPLIER / 1000)
   ```
   This approach allows high-latency servers (high ping) to have a longer *timeout*, increasing the chance of successfully obtaining the complete player list, which can be large and time-consuming for the server to process.
- **Timeout Limit:** `min($timeout, 2.0)` is used in several calls to impose a maximum limit of 2.0 seconds for metadata queries, preventing excessive waits.

#### Public Query Methods

| Method | Detailed Description | Internal Caching Behavior |
| :--- | :--- | :--- |
| `Get_All()` | **Recommended method for general use.** Orchestrates getting `INFO`, `RULES`, `PLAYERS_DETAILED` (or `PLAYERS_BASIC` as a fallback) in parallel. This minimizes the total query time, as packets are sent almost simultaneously and responses are processed as they arrive. Includes a total `execution_time_ms` measurement. | Uses the 2.0s cache (`$this->response_cache`) for each opcode queried within the parallel phase. |
| `Is_Online()` | Performs a quick `INFO` query and returns `true` if the server responds with a valid `Server_Info` within the *timeout*, `false` otherwise. It is robust, using critical *retries*. | Internally, it invokes `Fetch_Server_State()`, which uses the 5.0s cache for `INFO`. |
| `Get_Ping()` | Returns the server's most recent ping in milliseconds. If `cached_ping` is null, it forces a dedicated `PING` query with `Performance::FAST_PING_TIMEOUT` (0.3s) to get a quick measurement. | The `ping` is cached and updated whenever `Execute_Query_Phase` receives the first response. |
| `Get_Info()` | Returns a `Server_Info` object with details such as hostname, gamemode, number of players, etc. | Invokes `Fetch_Server_State()`, which uses the 5.0s cache. |
| `Get_Rules()` | Returns an `array` of `Server_Rule` objects containing all the rules configured on the server (e.g., `mapname`, `weburl`). Includes additional *retries* in case of initial failure. | Uses the 2.0s cache for `Opcode::RULES`. |
| `Get_Players_Detailed()` | Returns an `array` of `Players_Detailed` objects (id, name, score, ping for each player). **Important:** This query is skipped if the number of players on the server (`$this->cached_info->players`) is greater than or equal to `Query::LARGE_PLAYER_THRESHOLD` (150 by default), due to the risk of prolonged *timeouts* or packet fragmentation. | Uses the 2.0s cache for `Opcode::PLAYERS_DETAILED`. |
| `Get_Players_Basic()` | Returns an `array` of `Players_Basic` objects (name, score for each player). It is lighter than the detailed query. It is generally called as a *fallback* if `Get_Players_Detailed()` fails or is skipped. | Uses the 2.0s cache for `Opcode::PLAYERS_BASIC`. |

#### RCON Communication (`Send_Rcon`)

The `Send_Rcon(string $rcon_password, string $command)` method allows sending commands to the server's remote console.

1. **Argument Validation:** Throws `Invalid_Argument_Exception` if the password or command is empty.
2. **Isolated Socket:** Creates a new `Socket_Manager` instance (`$rcon_socket_manager`) for the RCON session, isolating it from the main query socket to prevent interference.
3. **Authentication (`varlist`):** Before sending the actual command, the library sends the "varlist" command (up to 3 attempts) to authenticate the RCON password. If `Send_Single_Rcon_Request` returns `null` or an empty response, an `Rcon_Exception` is thrown, indicating authentication failure or that RCON is not enabled.
4. **Sending the Actual Command:** After successful authentication, the `$command` is sent, also with up to 3 attempts.
5. **Response Handling:** `Packet_Parser::Parse_Rcon()` decodes the text response from RCON. If the server does not return a textual response after all attempts, a generic success message is returned.
6. **Cleanup:** The destructor of `$rcon_socket_manager` ensures that the RCON socket is closed after the operation.

## Error Diagnosis and Exceptions

The library uses a hierarchy of custom exceptions for clean and predictable error handling. In case of failure, one of the following exceptions will be thrown.

### `Invalid_Argument_Exception`

**Cause:**
- **Empty Hostname:** The `hostname` provided in the `Samp_Query` constructor is an empty string.
- **Invalid Port:** The `port` provided in the constructor is outside the valid range of 1 to 65535.
- **RCON:** RCON password or RCON command provided to `Send_Rcon` are empty.

### `Connection_Exception`

**Cause:** Network failure or lack of an essential response.
- **Domain Resolution Failed:** `Domain_Resolver` cannot convert the hostname into a valid IPv4.
- **Socket Creation Failed:** `Socket_Manager` cannot create or connect the UDP socket.
- **Server Inaccessible/Offline:** The server fails to respond with a valid `INFO` packet after all attempts and *timeouts* (including emergency *retries*), usually indicating that the server is offline, the IP/port is incorrect, or a firewall is blocking communication.

### `Malformed_Packet_Exception`

**Cause:** Data corruption at the protocol level.
- **Invalid Header:** `Packet_Parser` detects a packet that does not start with the "magic string" `SAMP` or has an insufficient total length.
- **Invalid Packet Structure:** `Packet_Parser` finds inconsistencies in the binary structure, such as a string length that points outside the packet's boundaries.
- **Resilience:** This exception is often handled internally by `Execute_Query_Phase` to trigger an immediate *retry*, but it can be propagated if the problem persists.

### `Rcon_Exception`

**Cause:** Error during RCON communication.
- **RCON Authentication Failure:** The server did not respond to the RCON authentication (via `varlist` command) after 3 attempts, suggesting an incorrect password or that RCON is disabled on the server.
- **RCON Command Send Failure:** The actual RCON command received no response after 3 attempts.

## License

Copyright © **SA-MP Programming Community**

This software is licensed under the terms of the MIT License ("License"); you may use this software according to the License terms. A copy of the License can be obtained at: [MIT License](https://opensource.org/licenses/MIT)

### Terms and Conditions of Use

#### 1. Granted Permissions

This license grants, free of charge, to any person obtaining a copy of this software and associated documentation files, the following rights:
* To use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the software without restriction
* To permit persons to whom the software is furnished to do so, subject to the following conditions

#### 2. Mandatory Conditions

All copies or substantial portions of the software must include:
* The above copyright notice
* This permission notice
* The disclaimer notice below

#### 3. Copyright

The software and all associated documentation are protected by copyright laws. The **SA-MP Programming Community** retains the original copyright of the software.

#### 4. Disclaimer of Warranty and Limitation of Liability

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.

IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

---

For detailed information about the MIT License, visit: https://opensource.org/licenses/MIT