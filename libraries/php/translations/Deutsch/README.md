# SA-MP Query - PHP

<div align="center">

![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Architecture](https://img.shields.io/badge/Protocol-UDP-blueviolet?style=for-the-badge)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**Eine robuste und widerstandsfähige PHP-Bibliothek, die entwickelt wurde, um den Status und die Informationen von SA-MP (San Andreas Multiplayer) und OMP (Open Multiplayer) Servern abzufragen.**

</div>

## Sprachen

- Português: [README](../../)
- English: [README](../English/README.md)
- Español: [README](../Espanol/README.md)
- Français: [README](../Francais/README.md)
- Italiano: [README](../Italiano/README.md)
- Polski: [README](../Polski/README.md)
- Русский: [README](../Русский/README.md)
- Svenska: [README](../Svenska/README.md)
- Türkçe: [README](../Turkce/README.md)

## Inhaltsverzeichnis

- [SA-MP Query - PHP](#sa-mp-query---php)
  - [Sprachen](#sprachen)
  - [Inhaltsverzeichnis](#inhaltsverzeichnis)
  - [Überblick](#überblick)
  - [Design- und Architekturprinzipien](#design--und-architekturprinzipien)
    - [Modulare Architektur](#modulare-architektur)
    - [Widerstandsfähigkeit: Backoff, Retries und Caching](#widerstandsfähigkeit-backoff-retries-und-caching)
    - [Leistungsoptimierung: Parallelität und Timeout-Anpassung](#leistungsoptimierung-parallelität-und-timeout-anpassung)
    - [Moderne Objektorientierte Programmierung (OOP) (PHP 8.1+)](#moderne-objektorientierte-programmierung-oop-php-81)
  - [Anforderungen](#anforderungen)
  - [Installation und grundlegende Verwendung](#installation-und-grundlegende-verwendung)
    - [Initialisierung der `Samp_Query`-Klasse](#initialisierung-der-samp_query-klasse)
    - [`Get_All()`: Vollständige und optimierte Abfrage](#get_all-vollständige-und-optimierte-abfrage)
    - [`Is_Online()`: Schnelle Statusüberprüfung](#is_online-schnelle-statusüberprüfung)
    - [`Get_Ping()`: Abrufen des Server-Pings](#get_ping-abrufen-des-server-pings)
    - [`Get_Info()`: Wesentliche Serverdetails](#get_info-wesentliche-serverdetails)
    - [`Get_Rules()`: Konfigurierte Serverregeln](#get_rules-konfigurierte-serverregeln)
    - [`Get_Players_Detailed()`: Liste der Spieler mit Details](#get_players_detailed-liste-der-spieler-mit-details)
    - [`Get_Players_Basic()`: Grundlegende Spielerliste](#get_players_basic-grundlegende-spielerliste)
    - [`Send_Rcon()`: Senden von Fernbefehlen](#send_rcon-senden-von-fernbefehlen)
  - [Detaillierte Struktur der Bibliothek und Ausführungsablauf](#detaillierte-struktur-der-bibliothek-und-ausführungsablauf)
    - [1. `constants.php`: Das Herzstück der Konfiguration](#1-constantsphp-das-herzstück-der-konfiguration)
    - [2. `opcode.php`: Das Enum der Protokoll-Opcodes](#2-opcodephp-das-enum-der-protokoll-opcodes)
    - [3. `exceptions.php`: Die Hierarchie der benutzerdefinierten Ausnahmen](#3-exceptionsphp-die-hierarchie-der-benutzerdefinierten-ausnahmen)
    - [4. `server_types.php`: Die unveränderlichen Datenmodelle](#4-server_typesphp-die-unveränderlichen-datenmodelle)
    - [5. `autoloader.php`: Der automatische Klassenlader](#5-autoloaderphp-der-automatische-klassenlader)
    - [6. `logger.php`: Das Logging-Subsystem](#6-loggerphp-das-logging-subsystem)
    - [7. `domain_resolver.php`: Die Domain-Auflösung mit persistentem Cache](#7-domain_resolverphp-die-domain-auflösung-mit-persistentem-cache)
    - [8. `socket_manager.php`: Der robuste UDP-Verbindungsmanager](#8-socket_managerphp-der-robuste-udp-verbindungsmanager)
    - [9. `packet_builder.php`: Der Ersteller von Binärpaketen](#9-packet_builderphp-der-ersteller-von-binärpaketen)
    - [10. `packet_parser.php`: Der Paket-Decoder mit Kodierungsbehandlung](#10-packet_parserphp-der-paket-decoder-mit-kodierungsbehandlung)
    - [11. `samp-query.php`: Die Hauptklasse (Der vollständige Orchestrator)](#11-samp-queryphp-die-hauptklasse-der-vollständige-orchestrator)
      - [Lebenszyklus der Abfrage: Die Reise eines Pakets](#lebenszyklus-der-abfrage-die-reise-eines-pakets)
        - [1. Initialisierung und Domain-Auflösung](#1-initialisierung-und-domain-auflösung)
        - [2. `Fetch_Server_State()`: Cache und kritische Abfrage von INFO/PING](#2-fetch_server_state-cache-und-kritische-abfrage-von-infoping)
        - [3. `Attempt_Query()`: Die optimierte Wiederholungsstrategie](#3-attempt_query-die-optimierte-wiederholungsstrategie)
        - [4. `Execute_Query_Phase()`: Die Kommunikations-Engine mit Ping-Erkennung](#4-execute_query_phase-die-kommunikations-engine-mit-ping-erkennung)
        - [5. `Validate_Response()`: Die semantische Validierungsschicht](#5-validate_response-die-semantische-validierungsschicht)
      - [Berechnung und Verwaltung des adaptiven Timeouts](#berechnung-und-verwaltung-des-adaptiven-timeouts)
      - [Öffentliche Abfragemethoden](#öffentliche-abfragemethoden)
      - [RCON-Kommunikation (`Send_Rcon`)](#rcon-kommunikation-send_rcon)
  - [Fehlerdiagnose und Ausnahmen](#fehlerdiagnose-und-ausnahmen)
    - [`Invalid_Argument_Exception`](#invalid_argument_exception)
    - [`Connection_Exception`](#connection_exception)
    - [`Malformed_Packet_Exception`](#malformed_packet_exception)
    - [`Rcon_Exception`](#rcon_exception)
  - [Lizenz](#lizenz)
    - [Nutzungsbedingungen](#nutzungsbedingungen)
      - [1. Gewährte Berechtigungen](#1-gewährte-berechtigungen)
      - [2. Verpflichtende Bedingungen](#2-verpflichtende-bedingungen)
      - [3. Urheberrecht](#3-urheberrecht)
      - [4. Gewährleistungsausschluss und Haftungsbeschränkung](#4-gewährleistungsausschluss-und-haftungsbeschränkung)

## Überblick

Die Bibliothek **SA-MP Query - PHP** ist eine hochleistungsfähige und fehlertolerante Lösung für PHP-Entwickler, die mit Spieleservern interagieren müssen, die auf dem SA-MP/OMP-Protokoll (UDP) basieren. Ihr Zweck ist es, die Komplexität des binären Abfrageprotokolls in einer sauberen und intuitiven PHP-API zu kapseln, sodass Webanwendungen, Launcher und Dienstprogramme detaillierte Informationen über den Serverstatus (Spieler, Regeln, Ping usw.) schnell und zuverlässig abrufen können.

Das Design der Bibliothek konzentriert sich auf drei Hauptpfeiler: **Widerstandsfähigkeit**, **Leistung** und **Modularität**. Sie ist so konzipiert, dass sie mit der unzuverlässigen Natur des UDP-Protokolls umgehen kann, indem sie ein fortschrittliches System von Wiederholungsversuchen und *Backoff* implementiert, um sicherzustellen, dass Informationen auch bei ungünstigen Netzwerkbedingungen oder Servern mit hoher Latenz abgerufen werden.

## Design- und Architekturprinzipien

### Modulare Architektur

Die Bibliothek ist in Komponenten mit jeweils einer einzigen Verantwortlichkeit unterteilt, die jeweils in ihrer eigenen Klasse und Datei gekapselt sind.

- **Netzwerkinfrastruktur:** `Domain_Resolver`, `Socket_Manager`.
- **Protokoll:** `Packet_Builder`, `Packet_Parser`, `Opcode` (Enum).
- **Geschäftslogik (Orchestrierung):** `Samp_Query`.
- **Datenmodelle:** `Server_Info`, `Players_Detailed`, usw.

### Widerstandsfähigkeit: Backoff, Retries und Caching

Das UDP-Protokoll garantiert nicht die Zustellung von Paketen. Die `Samp_Query`-Klasse mindert diesen Mangel mit einem ausgeklügelten Abfragezyklus.

- **Mehrere adaptive Versuche:** Die `Attempt_Query`-Methode implementiert einen Zyklus mit bis zu `Query::ATTEMPTS` (standardmäßig 5) und dem Doppelten davon für kritische Abfragen.
- **Backoff-Strategie:** Exponentielles *Backoff* wird in `Execute_Query_Phase` implementiert. Nach dem ersten Senden erhöht sich das Intervall für neue Abhörversuche (Schleife `while`) von `Performance::INITIAL_RETRY_INTERVAL` (0,08s) um den `Performance::BACKOFF_FACTOR` (1,3) bis zur Grenze von 0,2s. Dies vermeidet eine Überlastung durch Pakete und erhöht die Chance auf eine rechtzeitige Antwort.
- **Caching von Antworten:** Aktuelle Antworten (gültig für 2,0 Sekunden) werden in `response_cache` gespeichert, wodurch die Notwendigkeit entfällt, Metadatenabfragen während der Ausführung von `Get_All()` zu wiederholen.

### Leistungsoptimierung: Parallelität und Timeout-Anpassung

- **Parallele Abfragen (Fan-out):** Die `Get_All()`-Methode sendet Anfragen für `INFO`, `RULES` und `PLAYERS` gleichzeitig (in `$jobs`), sodass die Antworten in beliebiger Reihenfolge eintreffen können, was die Gesamtwartzeit minimiert.
- **Persistentes DNS-Caching:** Der `Domain_Resolver` speichert die aufgelöste IP-Adresse in einem lokalen Dateicache mit einer TTL von 3600 Sekunden, um Verzögerungen bei der Domain-Auflösung bei nachfolgenden Aufrufen zu vermeiden.
- **Adaptives Timeout:** Das *Timeout* für Abfragen großer Datenmengen (wie die Spielerliste) wird dynamisch basierend auf dem `cached_ping` des Servers angepasst:
   ```
   Angepasstes Timeout = Basis-Timeout + (Gecachter Ping * Ping-Multiplikator / 1000)
   ```
   Diese Logik (implementiert in `Fetch_Player_Data`) stellt sicher, dass Server mit hoher Latenz genügend Zeit zum Antworten haben, ohne die Geschwindigkeit bei Servern mit niedriger Latenz zu beeinträchtigen.

### Moderne Objektorientierte Programmierung (OOP) (PHP 8.1+)

Die Bibliothek verwendet moderne PHP-Funktionen, um Sicherheit und Klarheit zu gewährleisten:

- **Strikte Typisierung** (`declare(strict_types=1)`).
- **Schreibgeschützte Eigenschaften** (`public readonly` in `Samp_Query` und den Datenmodellen), um die Unveränderlichkeit der Daten zu gewährleisten.
- **Typisierte Enums** (`enum Opcode: string`) zur sicheren Steuerung des Protokolls.
- **Constructor Property Promotion** (in `Samp_Query::__construct` und den Modellen).

## Anforderungen

- **PHP:** Version **8.1 oder höher**.
- **PHP-Erweiterungen:** `sockets` und `mbstring` (für die Handhabung der UTF-8-Kodierung).

## Installation und grundlegende Verwendung

Um die Bibliothek **SA-MP Query - PHP** zu verwenden, binden Sie einfach die Datei `samp-query.php` in Ihr Projekt ein. Diese Datei lädt automatisch alle Abhängigkeiten über ihren internen Autoloader.

```php
<?php
// Binden Sie die Hauptklasse ein. Sie wird die Abhängigkeiten über den Autoloader laden.
require_once 'path/to/samp-query/samp-query.php'; 

// Verwenden Sie den Namespace der Hauptklasse
use Samp_Query\Samp_Query;
// Binden Sie die Ausnahmen für eine spezifischere Fehlerbehandlung ein
use Samp_Query\Exceptions\Invalid_Argument_Exception;
use Samp_Query\Exceptions\Connection_Exception;
use Samp_Query\Exceptions\Rcon_Exception;

// Instanziieren Sie die Samp_Query-Klasse und umschließen Sie sie mit einem try-catch-Block, um Initialisierungsfehler zu behandeln.
try {
    $server_query = new Samp_Query("play.example-samp-server.com", 7777);
    // Jetzt können Sie die öffentlichen Methoden von $server_query verwenden
}
catch (Invalid_Argument_Exception $e) {
    echo "Argumentfehler: " . $e->getMessage() . "\n";
}
catch (Connection_Exception $e) {
    echo "Verbindungsfehler: " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Unerwarteter Fehler während der Initialisierung: " . $e->getMessage() . "\n";
}
```

### Initialisierung der `Samp_Query`-Klasse

Die `Samp_Query`-Klasse ist der Einstiegspunkt für alle Funktionalitäten. Ihr Konstruktor erfordert den `hostname` (oder die IP-Adresse) und den `port` des Servers, den Sie abfragen möchten.

```php
/**
 * Initialisiert eine neue Instanz der Samp_Query-Bibliothek.
 *
 * @param string $hostname Der Hostname oder die IP-Adresse des SA-MP/OMP-Servers.
 * @param int $port Der UDP-Port des Servers (normalerweise 7777).
 * 
 * @throws Invalid_Argument_Exception Wenn der Hostname leer ist oder der Port ungültig ist.
 * @throws Connection_Exception Wenn die DNS-Auflösung fehlschlägt oder der Socket nicht erstellt werden kann.
 */
public function __construct(public readonly string $hostname, public readonly int $port)
```

<br>

---

### `Get_All()`: Vollständige und optimierte Abfrage

Dies ist die umfassendste und empfohlene Methode. Sie führt verschiedene Abfragen (INFO, RULES, PLAYERS) parallel und optimiert aus, minimiert die Gesamtantwortzeit und gibt ein vollständiges assoziatives Array mit allen verfügbaren Informationen zurück.

```php
/**
 * Gibt alle verfügbaren Serverinformationen in einem einzigen optimierten Aufruf zurück.
 * Enthält: is_online, ping, info (Server_Info), rules (Array von Server_Rule),
 * players_detailed (Array von Players_Detailed), players_basic (Array von Players_Basic),
 * und execution_time_ms.
 *
 * @return array Ein assoziatives Array, das alle Serverinformationen enthält.
 * 
 * @throws Connection_Exception Wenn die INFO-Abfrage, die für den Serverstatus unerlässlich ist, fehlschlägt.
 */
public function Get_All(): array
```

Anwendungsbeispiel:

```php
<?php
// ... (Initialisierung der $server_query-Klasse) ...

try {
    $data = $server_query->Get_All();
    
    if ($data['is_online']) {
        echo "## Server Online: {$data['info']->hostname} ({$server_query->ip}:{$server_query->port}) ##\n";
        echo "Ping: {$data['ping']}ms | Gesamtabfragezeit: {$data['execution_time_ms']}ms\n";
        echo "Spieler: {$data['info']->players} / {$data['info']->max_players}\n";
        echo "Spielmodus: {$data['info']->gamemode}\n";
        echo "Sprache: {$data['info']->language}\n";
        echo "Passwortgeschützt: " . ($data['info']->password ? "Ja" : "Nein") . "\n\n";

        // Beispiel für eine detaillierte Spielerliste
        if (!empty($data['players_detailed'])) {
            echo "--- Detaillierte Spieler ({$data['info']->players} Aktiv) ---\n";

            foreach ($data['players_detailed'] as $player)
                echo "ID: {$player->id}, Name: {$player->name}, Ping: {$player->ping}ms, Score: {$player->score}\n";
        }
        elseif (!empty($data['players_basic'])) {
            echo "--- Grundlegende Spieler ({$data['info']->players} Aktiv) (Fallback) ---\n";

            foreach ($data['players_basic'] as $player)
                echo "Name: {$player->name}, Score: {$player->score}\n";
        }
        else
            echo "Keine Spieler online oder Liste nicht verfügbar (vielleicht zu viele Spieler).\n";
        
        // Beispiel für Serverregeln
        if (!empty($data['rules'])) {
            echo "\n--- Serverregeln ---\n";

            foreach ($data['rules'] as $rule)
                echo "{$rule->name}: {$rule->value}\n";
        }
    }
    else
        echo "Der Server ist derzeit offline oder nicht erreichbar.\n";
}
catch (Connection_Exception $e) {
    echo "Verbindungsfehler beim Abrufen aller Informationen: " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Unerwarteter Fehler beim Abfragen aller Informationen: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Is_Online()`: Schnelle Statusüberprüfung

Überprüft, ob der Server online ist und auf Abfragen antwortet, ohne zusätzliche Details abzurufen. Ideal für einen einfachen "Liveness Check".

```php
/**
 * Überprüft, ob der Server online und erreichbar ist.
 *
 * @return bool Gibt true zurück, wenn der Server online ist und mit gültigen INFO antwortet, andernfalls false.
 */
public function Is_Online(): bool
```

Anwendungsbeispiel:

```php
<?php
// ... (Initialisierung der $server_query-Klasse) ...

if ($server_query->Is_Online())
    echo "Der SA-MP-Server ist online und antwortet!\n";
else
    echo "Der SA-MP-Server ist offline oder hat nicht rechtzeitig geantwortet.\n";
```

<br>

---

### `Get_Ping()`: Abrufen des Server-Pings

Gibt die Latenzzeit (Ping) des Servers in Millisekunden zurück. Dieser Wert wird intern zur Optimierung zwischengespeichert.

```php
/**
 * Gibt den aktuellen Ping des Servers in Millisekunden zurück.
 * Wenn der Ping noch nicht berechnet wurde, wird eine schnelle PING-Abfrage durchgeführt.
 *
 * @return int|null Der Ping-Wert in Millisekunden oder null, wenn er nicht abgerufen werden kann.
 */
public function Get_Ping(): ?int
```

Anwendungsbeispiel:

```php
<?php
// ... (Initialisierung der $server_query-Klasse) ...

try {
    $ping = $server_query->Get_Ping();

    if ($ping !== null)
        echo "Der Ping des Servers beträgt: {$ping}ms.\n";
    else
        echo "Der Ping des Servers konnte nicht abgerufen werden.\n";
}
catch (Connection_Exception $e) {
    echo "Fehler beim Abrufen des Pings: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Info()`: Wesentliche Serverdetails

Ruft die grundlegenden Informationen des Servers ab, wie Hostname, Spielmodus, Anzahl der Spieler usw. Gibt ein `Server_Info`-Objekt zurück.

```php
/**
 * Gibt die grundlegenden Details des Servers zurück (Hostname, Spieler, Spielmodus usw.).
 * Die Daten werden zur Optimierung für einen kurzen Zeitraum zwischengespeichert.
 *
 * @return Server_Info|null Ein Server_Info-Objekt oder null, wenn die Informationen nicht abgerufen werden können.
 */
public function Get_Info(): ?Server_Info
```

Anwendungsbeispiel:

```php
<?php
use Samp_Query\Models\Server_Info;
// ... (Initialisierung der $server_query-Klasse) ...

try {
    /** @var Server_Info|null $info */
    $info = $server_query->Get_Info();

    if ($info) {
        echo "--- Serverinformationen ---\n";
        echo "Hostname: {$info->hostname}\n";
        echo "Spielmodus: {$info->gamemode}\n";
        echo "Spieler: {$info->players} / {$info->max_players}\n";
        echo "Sprache: {$info->language}\n";
        echo "Passwortgeschützt: " . ($info->password ? "Ja" : "Nein") . "\n";
    }
    else
        echo "Die Serverinformationen konnten nicht abgerufen werden.\n";
}
catch (Connection_Exception $e) {
    echo "Fehler beim Abrufen der Serverinformationen: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Rules()`: Konfigurierte Serverregeln

Ruft alle auf dem Server konfigurierten Regeln ab, wie `mapname`, `weburl`, `weather` usw., und gibt sie als Array von `Server_Rule`-Objekten zurück.

```php
/**
 * Gibt ein Array von Server_Rule-Objekten zurück, von denen jedes den Namen und den Wert einer Serverregel enthält.
 * Die Daten werden zur Optimierung zwischengespeichert.
 *
 * @return array Ein Array von Samp_Query\Models\Server_Rule. Kann leer sein, wenn keine Regeln vorhanden sind.
 */
public function Get_Rules(): array
```

Anwendungsbeispiel:

```php
<?php
use Samp_Query\Models\Server_Rule;
// ... (Initialisierung der $server_query-Klasse) ...

try {
    $rules = $server_query->Get_Rules();

    if (!empty($rules)) {
        echo "--- Serverregeln ---\n";

        foreach ($rules as $rule)
            echo "{$rule->name}: {$rule->value}\n";

        // Beispiel, wie man auf eine bestimmte Regel zugreift:
        $mapname_rule = array_filter($rules, fn(Server_Rule $r) => $r->name === 'mapname');

        if (!empty($mapname_rule))
            echo "\nAktuelle Karte: " . reset($mapname_rule)->value . "\n";
    }
    else
        echo "Keine Regeln für diesen Server gefunden.\n";
}
catch (Connection_Exception $e) {
    echo "Fehler beim Abrufen der Serverregeln: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Players_Detailed()`: Liste der Spieler mit Details

Ruft eine detaillierte Liste der aktuell online befindlichen Spieler ab, einschließlich ID, Name, Score und Ping.

> [!CAUTION]
> Um die Leistung zu optimieren und übermäßig große UDP-Pakete zu vermeiden, die verloren gehen oder fragmentiert werden könnten, ruft diese Methode die detaillierte Spielerliste nicht ab, wenn die Gesamtzahl der Spieler gleich oder größer als `Query::LARGE_PLAYER_THRESHOLD` (standardmäßig 150) ist. In diesen Fällen wird ein leeres Array zurückgegeben. Erwägen Sie die Verwendung von `Get_Players_Basic()` als Fallback.

```php
/**
 * Gibt ein Array von Players_Detailed-Objekten (ID, Name, Score, Ping) für jeden Online-Spieler zurück.
 * Diese Abfrage kann übersprungen werden, wenn die Anzahl der Spieler zu hoch ist (siehe Query::LARGE_PLAYER_THRESHOLD).
 *
 * @return array Ein Array von Samp_Query\Models\Players_Detailed. Kann leer sein.
 */
public function Get_Players_Detailed(): array
```

Anwendungsbeispiel:

```php
<?php
use Samp_Query\Models\Players_Detailed;
// ... (Initialisierung der $server_query-Klasse) ...

try {
    $players_detailed = $server_query->Get_Players_Detailed();

    if (!empty($players_detailed)) {
        echo "--- Online-Spieler (Detailliert) ---\n";

        foreach ($players_detailed as $player)
            echo "ID: {$player->id}, Name: {$player->name}, Score: {$player->score}, Ping: {$player->ping}ms\n";
    }
    else
        echo "Keine Spieler online oder detaillierte Liste nicht verfügbar.\n";
}
catch (Connection_Exception $e) {
    echo "Fehler beim Abrufen der detaillierten Spielerliste: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Players_Basic()`: Grundlegende Spielerliste

Bietet eine schlankere Spielerliste, die nur Name und Score enthält. Nützlich als Alternative, wenn die detaillierte Liste nicht verfügbar ist oder um die Datenlast zu reduzieren.

```php
/**
 * Gibt ein Array von Players_Basic-Objekten (Name, Score) für jeden Online-Spieler zurück.
 * Nützlich als leichtere Alternative oder Fallback, wenn Get_Players_Detailed() nicht machbar ist.
 *
 * @return array Ein Array von Samp_Query\Models\Players_Basic. Kann leer sein.
 */
public function Get_Players_Basic(): array
```

Anwendungsbeispiel:

```php
<?php
use Samp_Query\Models\Players_Basic;
// ... (Initialisierung der $server_query-Klasse) ...

try {
    $players_basic = $server_query->Get_Players_Basic();

    if (!empty($players_basic)) {
        echo "--- Online-Spieler (Grundlegend) ---\n";

        foreach ($players_basic as $player)
            echo "Name: {$player->name}, Score: {$player->score}\n";
    }
    else
        echo "Keine Spieler online oder grundlegende Liste nicht verfügbar.\n";
}
catch (Connection_Exception $e) {
    echo "Fehler beim Abrufen der grundlegenden Spielerliste: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Send_Rcon()`: Senden von Fernbefehlen

Ermöglicht das Senden von Befehlen an die RCON-Konsole des Servers, wie z. B. das Ändern von Regeln, das Bannen von Spielern usw. Erfordert das RCON-Passwort des Servers.

> [!WARNING]
> Die RCON-Funktion ist sensibel und kann Änderungen am Server verursachen. Verwenden Sie sie mit Vorsicht und nur mit vertrauenswürdigen Passwörtern.
> Es ist entscheidend, dass das RCON-Passwort **korrekt** ist und dass RCON auf dem Server **aktiviert** ist (Einstellung `rcon_password` in `server.cfg`).

```php
/**
 * Sendet einen RCON-Befehl an den Server.
 * Führt die Authentifizierung mit 'varlist' durch und sendet den Befehl.
 *
 * @param string $rcon_password Das RCON-Passwort des Servers.
 * @param string $command Der auszuführende Befehl (z. B. "gmx", "kick ID").
 * @return string Die Antwort des Servers auf den RCON-Befehl oder eine Statusmeldung.
 * 
 * @throws Invalid_Argument_Exception Wenn das RCON-Passwort oder der Befehl leer sind.
 * @throws Rcon_Exception Wenn die RCON-Authentifizierung fehlschlägt oder der Befehl keine Antwort erhält.
 * @throws Connection_Exception Bei einem Verbindungsfehler während des RCON-Vorgangs.
 */
public function Send_Rcon(string $rcon_password, string $command): string
```

Anwendungsbeispiel:

```php
<?php
// ... (Initialisierung der $server_query-Klasse) ...

$rcon_password = "ihr_geheimes_passwort_hier"; 
$command_to_send = "gmx"; // Beispiel: Neustart des Spielmodus

try {
    echo "Versuche RCON-Befehl zu senden: '{$command_to_send}'...\n";
    $response = $server_query->Send_Rcon($rcon_password, $command_to_send);
    echo "Antwort von RCON: {$response}\n";

    // Beispiel für einen Befehl, um etwas auf dem Server zu "sagen" (erfordert RCON)
    $response_say = $server_query->Send_Rcon($rcon_password, "say Hallo von meinem PHP-Skript!");
    echo "Antwort von RCON (say): {$response_say}\n";
}
catch (Invalid_Argument_Exception $e) {
    echo "RCON-Fehler (Ungültiges Argument): " . $e->getMessage() . "\n";
}
catch (Rcon_Exception $e) {
    echo "RCON-Fehler: Authentifizierung fehlgeschlagen oder Befehl nicht ausgeführt. " . $e->getMessage() . "\n";
}
catch (Connection_Exception $e) {
    echo "RCON-Fehler (Verbindung): " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Unerwarteter Fehler beim Senden von RCON: " . $e->getMessage() . "\n";
}
```

## Detaillierte Struktur der Bibliothek und Ausführungsablauf

Die Bibliothek **SA-MP Query - PHP** ist sorgfältig in mehrere Dateien organisiert, von denen jede eine klar definierte Verantwortung hat. Dieser Abschnitt untersucht jede Komponente im Detail und enthüllt die Designentscheidungen und die zugrunde liegende Logik.

### 1. `constants.php`: Das Herzstück der Konfiguration

Diese Datei zentralisiert alle "magischen" Konfigurationsparameter der Bibliothek und stellt sicher, dass Aspekte wie *Timeouts*, Anzahl der Versuche und Puffergrößen leicht anpassbar und im gesamten Projekt konsistent sind.

```php
// ...
final class Query {
    public const ATTEMPTS = 5; // Maximale Anzahl von Abfrageversuchen
    public const LARGE_PLAYER_THRESHOLD = 150; // Spielerlimit für detaillierte Abfrage
}

final class Protocol {
    public const UDP_READ_BUFFER_SIZE = 32768; // 32KB für den Lesepuffer
    public const KERNEL_RECEIVE_BUFFER_SIZE = 4194304; // 4MB für den Kernel-Puffer
}
// ...
```

- **Final Classes und Konstanten:** Die Klassen sind `final` und die Eigenschaften sind `public const`, was Unveränderlichkeit und globale Zugänglichkeit zur Kompilierzeit gewährleistet.
- **Granularität und Semantik:** Die Konstanten sind nach ihrem Bereich kategorisiert (`Query`, `Protocol`, `Performance`, `Logger`, `DNS`), was das Verständnis und die Wartung erleichtert. Zum Beispiel definiert `Query::LARGE_PLAYER_THRESHOLD` den Punkt, an dem die Suche nach detaillierten Spielerlisten zur Optimierung vermieden werden kann, aufgrund des Datenvolumens und des Potenzials für *Timeouts*.

### 2. `opcode.php`: Das Enum der Protokoll-Opcodes

Diese Datei definiert die Operationscodes (Opcodes), die für die verschiedenen Abfragen an den SA-MP/OMP-Server verwendet werden, und kapselt sie in einem typisierten `enum`.

```php
// ...
enum Opcode: string {
    case INFO = 'i';
    case RULES = 'r';
    case PLAYERS_DETAILED = 'd';
    // ...
}
```

- **Typisiertes `Enum` (PHP 8.1+):** Die Verwendung eines `enum` (`Opcode: string`) mit Werten vom Typ `string` stellt sicher, dass die Opcodes immer gültig sind und der Code semantisch klar ist. Dies ersetzt die Verwendung von "magischen" String-Literalen und macht den Code lesbarer und weniger anfällig für Tippfehler.

### 3. `exceptions.php`: Die Hierarchie der benutzerdefinierten Ausnahmen

Diese Datei etabliert eine Hierarchie von benutzerdefinierten Ausnahmen, die eine granularere und spezifischere Fehlerbehandlung für die verschiedenen Arten von Fehlern ermöglicht, die in der Bibliothek auftreten können.

```php
// ...
class Query_Exception extends \Exception {}
class Invalid_Argument_Exception extends Query_Exception {}
class Connection_Exception extends Query_Exception {}
// ...
```

- **Vererbung von `\Exception`:** Alle Ausnahmen erben von `Query_Exception` (das wiederum `\Exception` erweitert), was es ermöglicht, Gruppen von Fehlern zu fangen (`Connection_Exception` und `Timeout_Exception` sind spezifischer als `Query_Exception`) oder alle Ausnahmen der Bibliothek mit einem allgemeineren `catch` abzufangen.
- **Klarheit bei der Diagnose:** Die beschreibenden Namen der Ausnahmen erleichtern die Diagnose und die Fehlerbehebung in der Client-Anwendung.

### 4. `server_types.php`: Die unveränderlichen Datenmodelle

Diese Datei definiert die Klassen, die die Datenmodelle für die vom Server zurückgegebenen Informationen repräsentieren und die Datenintegrität durch Unveränderlichkeit gewährleisten.

```php
// ...
final class Server_Info {
    public function __construct(
        public readonly bool $password,
        public readonly int $players,
        // ... andere readonly Eigenschaften ...
    ) {}
}
// ...
```

- **`final class`:** Die Klassen sind `final`, was Erweiterungen verhindert und ihre Struktur und ihr Verhalten garantiert.
- **`public readonly` Properties (PHP 8.1+):** Alle Eigenschaften werden als `public readonly` deklariert. Das bedeutet, dass ihre Werte nach der Erstellung des Objekts nicht mehr geändert werden können, was die Integrität der vom Server empfangenen Daten sicherstellt.
- **Constructor Property Promotion (PHP 8.1+):** Die Eigenschaften werden direkt im Konstruktor deklariert, was den Code vereinfacht und den *Boilerplate* reduziert.

### 5. `autoloader.php`: Der automatische Klassenlader

Diese Datei ist dafür verantwortlich, die Klassen der Bibliothek dynamisch zu laden, wenn sie benötigt werden, und folgt dabei dem PSR-4-Standard.

```php
// ...
spl_autoload_register(function ($class) {
    $prefix = 'Samp_Query\\';
    $base_dir = __DIR__ . '/../modules/'; // Mappt den Namespace auf das Verzeichnis
    // ... Ladelogik ...
});

// Bindet wesentliche Dateien ein, die keine Klassen sind oder ein frühes Laden erfordern
require_once __DIR__ . '/../modules/constants.php';
require_once __DIR__ . '/../modules/exceptions.php';
// ...
```

- **`spl_autoload_register()`:** Registriert eine anonyme Funktion, die von PHP automatisch aufgerufen wird, wenn auf eine nicht definierte Klasse verwiesen wird, was die Entwicklung und Wartung beschleunigt.
- **Direkte Einbindung von Konfigurationen:** Dateien wie `constants.php` und `exceptions.php` werden direkt eingebunden. Dies stellt sicher, dass ihre Definitionen verfügbar sind, bevor eine Klasse, die sie verwendet, vom Autoloader instanziiert wird.

### 6. `logger.php`: Das Logging-Subsystem

Die `Logger`-Klasse bietet einen einfachen Mechanismus zum Aufzeichnen von Fehlermeldungen und wichtigen Ereignissen in einer Protokolldatei, was für das Debugging und die Überwachung nützlich ist.

```php
// ...
final class Logger {
    public function Log(string $message): void {
        if (is_file(Logger_Config::FILE) && filesize(Logger_Config::FILE) > Logger_Config::MAX_SIZE_BYTES)
            unlink(Logger_Config::FILE); // Entfernt das Protokoll, wenn es die Größe überschreitet

        $timestamp = date('Y-m-d H:i:s');
        $log_entry = "[{$timestamp}] {$message}" . PHP_EOL;
        
        file_put_contents(Logger_Config::FILE, $log_entry, FILE_APPEND | LOCK_EX); // Fügt mit Sperre hinzu
    }
}
```

- **Automatische Bereinigung:** Der Logger überprüft die Größe der Protokolldatei (`Logger_Config::FILE`). Wenn sie `Logger_Config::MAX_SIZE_BYTES` (standardmäßig 10 MB) überschreitet, wird die Datei gelöscht, um zu verhindern, dass sie unbegrenzt wächst.
- **Dateisperre (`LOCK_EX`):** `file_put_contents` verwendet `LOCK_EX`, um sicherzustellen, dass nur ein Prozess gleichzeitig in die Protokolldatei schreibt, was Korruption in Multi-Threaded/Multi-Process-Umgebungen verhindert.

### 7. `domain_resolver.php`: Die Domain-Auflösung mit persistentem Cache

Die `Domain_Resolver`-Klasse ist dafür verantwortlich, Hostnamen (wie `play.example.com`) in IP-Adressen (wie `192.0.2.1`) umzuwandeln. Sie implementiert ein Caching-System auf der Festplatte, um die Leistung zu optimieren.

```php
// ...
final class Domain_Resolver {
    public function Resolve(string $hostname): string {
        if (filter_var($hostname, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
            return $hostname; // Ist bereits eine IP

        $cache_file = DNS_Config::CACHE_DIR . md5($hostname) . '.cache';

        if (file_exists($cache_file) && (time() - filemtime($cache_file)) < DNS_Config::CACHE_TTL_SECONDS) {
            $ip = file_get_contents($cache_file);

            if ($ip && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
                return $ip;
        }
        
        $ip = gethostbyname($hostname); // Echte DNS-Auflösung
        // ... Logik zur Validierung und zum Speichern im Cache ...

        return $ip;
    }
    // ...
}
```

- **Persistenter Cache:** Bevor `gethostbyname()` aufgerufen wird, wird überprüft, ob die IP bereits in einer Cache-Datei (`dns/` + MD5-Hash des Hostnamens) gespeichert ist. Der Cache gilt als gültig, wenn er `DNS_Config::CACHE_TTL_SECONDS` (standardmäßig 3600 Sekunden oder 1 Stunde) nicht überschritten hat.
- **Robuste Validierung:** Die aufgelöste IP (oder die aus dem Cache gelesene) wird mit `filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)` validiert, um sicherzustellen, dass es sich um eine gültige IPv4-Adresse handelt. Wenn die Auflösung fehlschlägt, wird eine `Query_Exception` ausgelöst.

### 8. `socket_manager.php`: Der robuste UDP-Verbindungsmanager

Die `Socket_Manager`-Klasse kapselt die Komplexität der Erstellung, Konfiguration und Verwaltung eines UDP-Sockets für die Kommunikation mit dem Spieleserver.

```php
// ...
final class Socket_Manager {
    private $socket = null;
    // ...
    private function Connect(): void {
        if (is_resource($this->socket))
            return;

        $context = stream_context_create([
            'socket' => ['so_rcvbuf' => Protocol::KERNEL_RECEIVE_BUFFER_SIZE] // Erhöht den Puffer auf 4MB
        ]);

        $this->socket = stream_socket_client(
            "udp://{$this->host}:{$this->port}", 
            $err_no, $err_str, 
            (float)ini_get("default_socket_timeout"), 
            STREAM_CLIENT_CONNECT, // Verbindet den Socket mit der Remote-Adresse
            $context
        );

        if ($this->socket === false)
            throw new Connection_Exception("Could not create socket...");
    }
    // ...
}
```

- **`stream_socket_client` mit `STREAM_CLIENT_CONNECT`:** Dieses *Flag* weist das Betriebssystem an, den UDP-Socket mit der Remote-Adresse zu "verbinden". Obwohl UDP verbindungslos ist, ermöglicht das "Verbinden" des Sockets Leistungsoptimierungen auf Kernel-Ebene, wie z. B. das Weglassen der Remote-Adresse bei jedem `fwrite`- oder `stream_socket_recvfrom`-Aufruf, was zu geringerem Overhead führt.
- **Kernel-Empfangspuffer:** `stream_context_create` wird verwendet, um die Größe des Kernel-Empfangspuffers (`so_rcvbuf`) auf `Protocol::KERNEL_RECEIVE_BUFFER_SIZE` (4 MB) zu erhöhen. Dies ist entscheidend, um Paketverluste (Pufferüberlauf) beim Empfang großer Antworten, wie z. B. detaillierter Spielerlisten von stark frequentierten Servern, zu vermeiden.
- **RAII über `__destruct`:** Die `Disconnect()`-Methode wird automatisch im Destruktor (`__destruct`) aufgerufen, um sicherzustellen, dass der Socket geschlossen und die Ressourcen freigegeben werden, auch im Falle von Ausnahmen.
- **Dynamisches Timeout:** `Set_Timeout` passt die Lese-/Schreib-Timeouts des Sockets mit `stream_set_timeout` präzise an, was für die Logik von *Retries* und *Backoff* von grundlegender Bedeutung ist.

### 9. `packet_builder.php`: Der Ersteller von Binärpaketen

Die `Packet_Builder`-Klasse ist dafür verantwortlich, die Abfragedaten in ein spezifisches Binärformat zu serialisieren, das der SA-MP/OMP-Server verstehen kann.

```php
// ...
final class Packet_Builder {
    // ...
    private function Build_Header(): string {
        $packet = 'SAMP';
        $packet .= pack('c4', ...$this->ip_parts); // IP in 4 Bytes
        $packet .= pack('v', $this->port); // Port in 2 Bytes (little-endian)

        return $packet;
    }
    
    public function Build(string $opcode): string {
        $packet = $this->Build_Header();
        $packet .= $opcode;

        if ($opcode === Opcode::PING->value)
            $packet .= random_bytes(4); // Zufälliger Payload für PING
        
        return $packet;
    }
    // ...
}
```

- **`pack()` für Binärformat:** Verwendet die `pack()`-Funktion von PHP, um die Daten (IP, Port, String-Längen) in ihr korrektes Binärformat umzuwandeln, wie `c4` für 4 Bytes Zeichen (IP) und `v` für eine vorzeichenlose 16-Bit-Ganzzahl (Port und Längen), die *little-endian* ist.
- **Standard-Header:** `Build_Header()` erstellt den 10-Byte-'SAMP'-Header, der allen Paketen vorangestellt ist.
- **RCON-Struktur:** `Build_Rcon` formatiert das RCON-Paket mit dem Opcode 'x', gefolgt von der Passwortlänge, dem Passwort, der Befehlslänge und dem Befehl selbst.

### 10. `packet_parser.php`: Der Paket-Decoder mit Kodierungsbehandlung

Die `Packet_Parser`-Klasse ist das Gegenstück zum `Packet_Builder` und dafür verantwortlich, die vom Server empfangenen binären Antworten zu interpretieren und sie in strukturierte PHP-Daten umzuwandeln.

```php
// ...
final class Packet_Parser {
    private int $offset = Protocol::MIN_PACKET_LENGTH; // Beginnt nach dem Header (11 Bytes)
    // ...
    public function __construct(private readonly string $data) {
        // Initiale Validierung des 'SAMP'-Headers
        if (substr($this->data, 0, 4) !== 'SAMP' || strlen($this->data) < Protocol::MIN_PACKET_LENGTH)
            throw new Malformed_Packet_Exception("Invalid response header or packet too short.");
    }
    
    private function Extract_String(int $length_bytes): string {
        // ... Logik zum Lesen der Länge und des Strings ...
        $string = substr($this->data, $this->offset, $length);
        $this->offset += $length;
        
        // **KRITISCHE KODIERUNGSUMWANDLUNG:** SA-MP/OMP-Server verwenden Windows-1252
        return mb_convert_encoding($string, 'UTF-8', 'Windows-1252');
    }
    // ...
}
```

- **`offset` und `data_length`:** Der `offset` wird verwendet, um die aktuelle Position beim Lesen des Pakets zu verfolgen, während `data_length` das Lesen außerhalb der Puffergrenzen verhindert.
- **Header-Validierung:** Der Konstruktor validiert den "Magic String" `SAMP`, um fehlerhafte Pakete sofort abzulehnen.
- **`Extract_String()` - Entscheidende Kodierungsumwandlung:** Dies ist eine der wichtigsten Funktionalitäten. Das SA-MP-Protokoll überträgt Strings mit der **Windows-1252**-Kodierung. Um sicherzustellen, dass Sonderzeichen (wie Akzente oder kyrillische Buchstaben) in PHP-Anwendungen (die normalerweise in UTF-8 arbeiten) korrekt angezeigt werden, wird die Methode `mb_convert_encoding($string, 'UTF-8', 'Windows-1252')` angewendet.
- **Extraktion variabler Längen:** Die `Extract_String`-Methode unterstützt unterschiedliche Größen von Längenpräfixen für die Strings (1, 2 oder 4 Bytes), was sie flexibel für verschiedene Felder des Protokolls macht.

### 11. `samp-query.php`: Die Hauptklasse (Der vollständige Orchestrator)

Die `Samp_Query`-Klasse ist der Haupteinstiegspunkt und der Orchestrator aller Operationen. Sie verbindet alle Komponenten, verwaltet den Abfragestatus, die Logik für *Retries* und *Timeouts*.

#### Lebenszyklus der Abfrage: Die Reise eines Pakets

Der gesamte Prozess der Abfrage eines Servers folgt einer Reihe von sorgfältig orchestrierten Schritten, die auf maximale Widerstandsfähigkeit und Leistung abzielen.

##### 1. Initialisierung und Domain-Auflösung

Wenn eine Instanz von `Samp_Query` erstellt wird:
- **Schnelle Validierung:** Der Konstruktor validiert die Parameter `$hostname` und `$port`. Jede Inkonsistenz führt zu einer `Invalid_Argument_Exception`.
- **DNS-Cache-Bereinigung:** `Domain_Resolver::Clean_Expired_Cache()` wird aufgerufen, um sicherzustellen, dass nur gültige und nicht abgelaufene DNS-Einträge berücksichtigt werden.
- **IP-Auflösung:** Der `Domain_Resolver` wird verwendet, um den `$hostname` in eine IP-Adresse (`$this->ip`) umzuwandeln. Diese IP wird für zukünftige Anfragen auf der Festplatte zwischengespeichert, und eine `Query_Exception` wird ausgelöst, wenn die Auflösung fehlschlägt.
- **Ressourcenkonfiguration:** Der `Logger`, `Socket_Manager` und `Packet_Builder` werden instanziiert, um die Infrastruktur für die Kommunikation vorzubereiten.

##### 2. `Fetch_Server_State()`: Cache und kritische Abfrage von INFO/PING

Diese interne Methode ist ein Wächter für Leistung und Konsistenz und stellt sicher, dass die grundlegenden Serverinformationen (`Server_Info`) und der `ping` immer aktuell sind, bevor eine Hauptabfrage durchgeführt wird.

- **Primärer Cache (5 Sekunden):** Bevor eine Kommunikation gestartet wird, wird überprüft, ob `$this->cached_info` (das `Server_Info`-Objekt des Servers) Daten enthält, die jünger als 5 Sekunden sind (im Vergleich zu `$this->last_successful_query`). Wenn die Daten aktuell sind, kehrt die Funktion sofort zurück und vermeidet unnötigen Netzwerkverkehr.
- **Kritische INFO-Abfrage:** Wenn der Cache abgelaufen oder leer ist, wird die `Attempt_Query`-Methode aufgerufen, um die `INFO`-Daten abzurufen. Diese Abfrage ist als **kritisch** (`true`) markiert, was eine höhere Anzahl von Versuchen und großzügigere *Timeouts* auslöst. Eine `Connection_Exception` wird ausgelöst, wenn die INFO-Antwort nach allen Versuchen ungültig ist.
- **Ping-Berechnung:** Wenn `$this->cached_ping` noch null ist, wird eine schnelle `PING`-Abfrage (`Execute_Query_Phase` mit `Performance::FAST_PING_TIMEOUT`) durchgeführt. Der Ping wird als die verstrichene Zeit bis zur **ersten** empfangenen Antwort berechnet, um Genauigkeit zu gewährleisten.

##### 3. `Attempt_Query()`: Die optimierte Wiederholungsstrategie

Dies ist das Gehirn der Widerstandsfähigkeit der Bibliothek, das den Zyklus der übergeordneten Versuche für einen oder mehrere `$jobs` (Opcode-Abfragen) verwaltet.

- **Antwort-Cache (2 Sekunden):** Zuerst wird überprüft, ob die Antworten für einen der `$jobs` bereits im `$this->response_cache` vorhanden sind (weniger als 2,0 Sekunden alt). Dies verhindert unnötige *Retries* für volatile, aber nicht kritische Daten.
- **Phase der schnellen Wiederholungen:** Die Bibliothek versucht zuerst `Query::FAST_RETRY_ATTEMPTS` (standardmäßig 2) mit einem kürzeren *Timeout* (`$timeout * 0.6`). Das Ziel ist es, so schnell wie möglich eine Antwort zu erhalten, ohne signifikante Verzögerungen einzuführen.
- **Phase der Standard-Wiederholungen mit Backoff:** Wenn die schnelle Phase nicht ausreicht, wird der Zyklus mit den restlichen `Query::ATTEMPTS` fortgesetzt. In dieser Phase erhöht sich der `$adjusted_timeout` bei jedem Versuch progressiv, was dem Server mehr Zeit zum Antworten gibt. Wichtiger noch, `usleep()` führt eine zunehmende Verzögerung (basierend auf `Query::RETRY_DELAY_MS` und einem Erhöhungsfaktor) zwischen den Aufrufen von `Execute_Query_Phase` ein, damit sich das Netzwerk und der Server stabilisieren können.
- **Notfall-Wiederholungen (für kritische Abfragen):** Für `$jobs`, die als `critical` markiert sind, wird, wenn alle vorherigen Versuche fehlschlagen, ein letzter *Retry* für jeden Job einzeln mit einem noch größeren *Timeout* (`$timeout * 2`) durchgeführt. Dies ist eine letzte Chance, um lebenswichtige Informationen zu erhalten.

##### 4. `Execute_Query_Phase()`: Die Kommunikations-Engine mit Ping-Erkennung

In dieser Low-Level-Methode findet die eigentliche Interaktion mit dem UDP-Socket statt. Sie verwaltet das Senden und Empfangen von Paketen für eine Gruppe von `$jobs` gleichzeitig in einer einzigen Netzwerkphase.

```php
// ... (innerhalb von Execute_Query_Phase)
private function Execute_Query_Phase(array $jobs, ?int &$ping, float $timeout): array {
    $socket = $this->socket_manager->Get_Socket_Resource($timeout);
    stream_set_blocking($socket, false); // Nicht-blockierender Socket

    // Sendet Pakete sofort zweimal für eine höhere UDP-Zustellgarantie
    for ($i = 0; $i < 2; $i++) {
        foreach ($pending_jobs as $opcode)
            fwrite($socket, $this->packet_builder->Build($opcode->value));

        if ($i == 0)
            usleep(5000); // Kleine Verzögerung
    }

    $last_send_time = microtime(true);
    $current_retry_interval = Performance::INITIAL_RETRY_INTERVAL; // 0.08s
    
    while (!empty($pending_jobs) && (microtime(true) - $start_time) < $timeout) {
        $now = microtime(true);

        if (($now - $last_send_time) > $current_retry_interval) { // Logik zum erneuten Senden mit Backoff
            // ... sendet ausstehende Pakete erneut ...
            $current_retry_interval = min($current_retry_interval * Performance::BACKOFF_FACTOR, 0.2); // Erhöht das Wiederholungsintervall
        }

        $read = [$socket]; $write = $except = null

        if (stream_select($read, $write, $except, 0, 10000) > 0) { // Wartet auf Daten (max 10ms)
            $response = stream_socket_recvfrom($socket, Protocol::UDP_READ_BUFFER_SIZE);
            // ... Logik zum Parsen, zur Ping-Berechnung und zur Validierung ...
        }

        if (!empty($pending_jobs))
            usleep(1000); // Kleine Verzögerung, um CPU-Spin zu vermeiden
    }
    return $phase_results;
}
```

- **Nicht-blockierender Socket:** `stream_set_blocking($socket, false)` ist unerlässlich. Es ermöglicht PHP, Pakete zu senden und dann auf Antworten zu warten, ohne die Ausführung zu blockieren, indem `stream_select` verwendet wird.
- **Doppeltes Senden für Robustheit:** Die Pakete für alle `$pending_jobs` werden zu Beginn der Phase **zweimal** hintereinander (mit einer kleinen `usleep(5000)` dazwischen) gesendet. Diese Praxis ist bei UDP-Protokollen von grundlegender Bedeutung, um die Zustellwahrscheinlichkeit in instabilen Netzwerken oder bei Paketverlusten erheblich zu erhöhen und die unzuverlässige Natur von UDP zu mindern. Für `INFO` und `PING` wird während der *Retries* in der Hauptschleife ein drittes zusätzliches Senden durchgeführt.
- **Empfangsschleife mit adaptivem Backoff:**
   - Eine Haupt-`while`-Schleife wird fortgesetzt, bis alle `$jobs` abgeschlossen sind oder das *Timeout* der Phase abläuft.
   - **Dynamisches erneutes Senden:** Wenn die seit dem letzten Senden verstrichene Zeit (`$now - $last_send_time`) das `$current_retry_interval` überschreitet, werden die Pakete für die `$pending_jobs` erneut gesendet. Das `$current_retry_interval` wird dann erhöht (`min($current_retry_interval * Performance::BACKOFF_FACTOR, 0.2)`), wodurch ein exponentielles *Backoff* implementiert wird, das eine Überlastung des Servers vermeidet und die Chancen auf eine Antwort maximiert.
   - **Optimiertes `stream_select`:** `stream_select($read, $write, $except, 0, 10000)` wird verwendet, um maximal 10 Millisekunden auf Daten zu warten. Dies ermöglicht es der Bibliothek, reaktionsschnell zu sein und Pakete zu verarbeiten, sobald sie eintreffen.
   - **Präzise Ping-Messung:** Wenn das **erste** gültige Paket empfangen wird (`$packets_received === 0`), wird der `ping` mit hoher Präzision als Differenz zwischen `microtime(true)` zu Beginn des Sendens der ersten Paketwelle und dem genauen Zeitpunkt des Empfangs des **ersten** gültigen Pakets berechnet.
- **Verarbeitung und Validierung der Antwort:** Die empfangenen Antworten werden vom `Packet_Parser` dekodiert. Wenn eine `Malformed_Packet_Exception` erkannt wird, wird dies protokolliert und das Paket wird sofort erneut an den Server gesendet, um es erneut zu versuchen. Die dekodierte Antwort wird dann von `Validate_Response()` validiert. Wenn sie gültig ist, wird sie zu den `$phase_results` und zum `$this->response_cache` hinzugefügt.

##### 5. `Validate_Response()`: Die semantische Validierungsschicht

Diese entscheidende Methode, implementiert in der `Samp_Query`-Klasse, überprüft die Integrität und logische Konsistenz der dekodierten Daten, bevor sie dem Benutzer zur Verfügung gestellt werden.

```php
// ... (innerhalb von Validate_Response)
private function Validate_Response($data, Opcode $opcode): bool {
    switch ($opcode) {
        case Opcode::INFO:
            // Stellt sicher, dass der Hostname nicht leer ist und die Spielerzahlen logisch sind
            return $data instanceof Server_Info && $data->max_players > 0 && $data->players >= 0 && $data->players <= $data->max_players && !empty($data->hostname);
        
        case Opcode::RULES:
            if (!is_array($data))
                return false;

            // ...

            return true;
        // ... Validierungen für PLAYERS_DETAILED, PLAYERS_BASIC, PING ...
    }
}
```

- **Validierung nach Opcode:** Jeder `Opcode` hat seine eigene spezifische Validierungslogik. Zum Beispiel:
   - Für `Opcode::INFO`: Stellt sicher, dass `$data` eine Instanz von `Server_Info` ist, dass `$data->max_players > 0`, `$data->players` zwischen 0 und `max_players` liegt und dass `$data->hostname` nicht leer ist.
   - Für `Opcode::RULES` oder Spielerlisten: Überprüft, ob die Rückgabe ein `array` ist und, falls nicht leer, ob das erste Element die erwartete Modellinstanz ist (`Server_Rule`, `Players_Detailed` usw.).
- **Robustheit:** Wenn die Validierung fehlschlägt, wird die Antwort als ungültig betrachtet und verworfen. Dies zwingt das System, die Versuche fortzusetzen, als ob das Paket nie angekommen wäre, und schützt die Anwendung vor beschädigten oder inkonsistenten Serverdaten.

#### Berechnung und Verwaltung des adaptiven Timeouts

Die Bibliothek implementiert eine ausgeklügelte *Timeout*-Strategie, um Geschwindigkeit und Widerstandsfähigkeit auszugleichen:

- **`Performance::METADATA_TIMEOUT`:** (0,8 Sekunden) Ist das Basis-*Timeout* für schnelle Abfragen wie `INFO` und `RULES`.
- **`Performance::PLAYER_LIST_BASE_TIMEOUT`:** (1,0 Sekunden) Ist das Basis-*Timeout* für Abfragen von Spielerlisten.
- **`Performance::PING_MULTIPLIER`:** (2) Wird verwendet, um das *Timeout* basierend auf dem Ping anzupassen.
- **Anpassung nach Ping:** In der `Fetch_Player_Data`-Methode wird das *Timeout* für den Abruf der Spielerliste dynamisch angepasst:
   ```
   Spieler-Timeout = PLAYER_LIST_BASE_TIMEOUT + (Gecachter Ping * PING_MULTIPLIER / 1000)
   ```
   Dieser Ansatz ermöglicht es Servern mit hoher Latenz (hoher Ping), ein längeres *Timeout* zu haben, was die Erfolgsaussichten beim Abrufen der vollständigen Spielerliste erhöht, die groß sein und vom Server lange zur Verarbeitung benötigen kann.
- **Timeout-Limit:** `min($timeout, 2.0)` wird in mehreren Aufrufen verwendet, um eine maximale Grenze von 2,0 Sekunden für Metadatenabfragen durchzusetzen und übermäßige Wartezeiten zu vermeiden.

#### Öffentliche Abfragemethoden

| Methode | Detaillierte Beschreibung | Internes Caching-Verhalten |
| :--- | :--- | :--- |
| `Get_All()` | **Empfohlene Methode für den allgemeinen Gebrauch.** Orchestriert den Abruf von `INFO`, `RULES`, `PLAYERS_DETAILED` (oder `PLAYERS_BASIC` als Fallback) parallel. Dies minimiert die Gesamtabfragezeit, da die Pakete fast gleichzeitig gesendet werden und die Antworten verarbeitet werden, sobald sie eintreffen. Enthält eine Messung der gesamten `execution_time_ms`. | Verwendet den 2,0s-Cache (`$this->response_cache`) für jeden abgefragten Opcode innerhalb der parallelen Phase. |
| `Is_Online()` | Führt eine schnelle `INFO`-Abfrage durch und gibt `true` zurück, wenn der Server mit einem gültigen `Server_Info` innerhalb des *Timeouts* antwortet, andernfalls `false`. Es ist robust und verwendet kritische *Retries*. | Ruft intern `Fetch_Server_State()` auf, das den 5,0s-Cache für `INFO` verwendet. |
| `Get_Ping()` | Gibt den neuesten Ping des Servers in Millisekunden zurück. Wenn `cached_ping` null ist, erzwingt es eine dedizierte `PING`-Abfrage mit `Performance::FAST_PING_TIMEOUT` (0,3s), um eine schnelle Messung zu erhalten. | Der `ping` wird zwischengespeichert und aktualisiert, wann immer `Execute_Query_Phase` die erste Antwort erhält. |
| `Get_Info()` | Gibt ein `Server_Info`-Objekt mit Details wie Hostname, Spielmodus, Spieleranzahl usw. zurück. | Ruft `Fetch_Server_State()` auf, das den 5,0s-Cache verwendet. |
| `Get_Rules()` | Gibt ein `array` von `Server_Rule`-Objekten zurück, das alle auf dem Server konfigurierten Regeln enthält (z. B. `mapname`, `weburl`). Enthält zusätzliche *Retries* im Falle eines anfänglichen Fehlers. | Verwendet den 2,0s-Cache für `Opcode::RULES`. |
| `Get_Players_Detailed()` | Gibt ein `array` von `Players_Detailed`-Objekten zurück (ID, Name, Score, Ping für jeden Spieler). **Wichtig:** Diese Abfrage wird ignoriert, wenn die Anzahl der Spieler auf dem Server (`$this->cached_info->players`) größer oder gleich `Query::LARGE_PLAYER_THRESHOLD` (standardmäßig 150) ist, aufgrund des Risikos von langen *Timeouts* oder Paketfragmentierung. | Verwendet den 2,0s-Cache für `Opcode::PLAYERS_DETAILED`. |
| `Get_Players_Basic()` | Gibt ein `array` von `Players_Basic`-Objekten zurück (Name, Score für jeden Spieler). Es ist leichter als die detaillierte Abfrage. Wird normalerweise als *Fallback* aufgerufen, wenn `Get_Players_Detailed()` fehlschlägt oder ignoriert wird. | Verwendet den 2,0s-Cache für `Opcode::PLAYERS_BASIC`. |

#### RCON-Kommunikation (`Send_Rcon`)

Die Methode `Send_Rcon(string $rcon_password, string $command)` ermöglicht das Senden von Befehlen an die Remote-Konsole des Servers.

1.  **Argumentvalidierung:** Löst `Invalid_Argument_Exception` aus, wenn das Passwort oder der Befehl leer sind.
2.  **Isolierter Socket:** Erstellt eine neue Instanz von `Socket_Manager` (`$rcon_socket_manager`) für die RCON-Sitzung und isoliert sie vom Hauptabfrage-Socket, um Interferenzen zu vermeiden.
3.  **Authentifizierung (`varlist`):** Bevor der eigentliche Befehl gesendet wird, sendet die Bibliothek den Befehl "varlist" (in bis zu 3 Versuchen), um das RCON-Passwort zu authentifizieren. Wenn `Send_Single_Rcon_Request` `null` oder eine leere Antwort zurückgibt, wird eine `Rcon_Exception` ausgelöst, was auf eine fehlgeschlagene Authentifizierung oder darauf hinweist, dass RCON nicht aktiviert ist.
4.  **Senden des eigentlichen Befehls:** Nach erfolgreicher Authentifizierung wird der `$command` gesendet, ebenfalls mit bis zu 3 Versuchen.
5.  **Antwortbehandlung:** `Packet_Parser::Parse_Rcon()` dekodiert die Textantwort von RCON. Wenn der Server nach allen Versuchen keine textuelle Antwort zurückgibt, wird eine generische Erfolgsmeldung zurückgegeben.
6.  **Bereinigung:** Der Destruktor des `$rcon_socket_manager` stellt sicher, dass der RCON-Socket nach dem Vorgang geschlossen wird.

## Fehlerdiagnose und Ausnahmen

Die Bibliothek verwendet eine Hierarchie von benutzerdefinierten Ausnahmen für eine saubere und vorhersagbare Fehlerbehandlung. Im Falle eines Fehlers wird eine der folgenden Ausnahmen ausgelöst.

### `Invalid_Argument_Exception`

**Ursache:**
- **Leerer Hostname:** Der im Konstruktor von `Samp_Query` angegebene `hostname` ist ein leerer String.
- **Ungültiger Port:** Der im Konstruktor angegebene `port` liegt außerhalb des gültigen Bereichs von 1 bis 65535.
- **RCON:** Das RCON-Passwort oder der RCON-Befehl, die an `Send_Rcon` übergeben wurden, sind leer.

### `Connection_Exception`

**Ursache:** Netzwerkfehler oder fehlende wesentliche Antwort.
- **Domain-Auflösung fehlgeschlagen:** Der `Domain_Resolver` kann den Hostnamen nicht in eine gültige IPv4-Adresse umwandeln.
- **Fehler bei der Socket-Erstellung:** Der `Socket_Manager` kann den UDP-Socket nicht erstellen oder verbinden.
- **Server nicht erreichbar/offline:** Der Server antwortet nach allen Versuchen und *Timeouts* (einschließlich Notfall-*Retries*) nicht mit einem gültigen `INFO`-Paket, was normalerweise darauf hindeutet, dass der Server offline ist, die IP/der Port falsch ist oder eine Firewall die Kommunikation blockiert.

### `Malformed_Packet_Exception`

**Ursache:** Datenkorruption auf Protokollebene.
- **Ungültiger Header:** Der `Packet_Parser` erkennt ein Paket, das nicht mit dem "Magic String" `SAMP` beginnt oder eine unzureichende Gesamtlänge hat.
- **Ungültige Paketstruktur:** Der `Packet_Parser` findet Inkonsistenzen in der Binärstruktur, wie z. B. eine String-Länge, die außerhalb der Paketgrenzen zeigt.
- **Widerstandsfähigkeit:** Diese Ausnahme wird oft intern von `Execute_Query_Phase` behandelt, um einen sofortigen *Retry* auszulösen, kann aber weitergegeben werden, wenn das Problem weiterhin besteht.

### `Rcon_Exception`

**Ursache:** Fehler während der RCON-Kommunikation.
- **RCON-Authentifizierung fehlgeschlagen:** Der Server hat nach 3 Versuchen nicht auf die RCON-Authentifizierung (über den Befehl `varlist`) geantwortet, was auf ein falsches Passwort oder deaktiviertes RCON auf dem Server hindeutet.
- **Fehler beim Senden des RCON-Befehls:** Der eigentliche RCON-Befehl erhielt nach 3 Versuchen keine Antwort.

## Lizenz

Copyright © **SA-MP Programming Community**

Diese Software ist unter den Bedingungen der MIT-Lizenz ("Lizenz") lizenziert; Sie dürfen diese Software gemäß den Lizenzbedingungen nutzen. Eine Kopie der Lizenz finden Sie unter: [MIT License](https://opensource.org/licenses/MIT)

### Nutzungsbedingungen

#### 1. Gewährte Berechtigungen

Diese Lizenz gewährt jeder Person, die eine Kopie dieser Software und der zugehörigen Dokumentationsdateien erhält, kostenlos folgende Rechte:
* Die Software ohne Einschränkungen zu nutzen, zu kopieren, zu modifizieren, zusammenzuführen, zu veröffentlichen, zu verteilen, zu unterlizenzieren und/oder zu verkaufen
* Personen, denen die Software zur Verfügung gestellt wird, dies unter den folgenden Bedingungen zu gestatten

#### 2. Verpflichtende Bedingungen

Alle Kopien oder wesentliche Teile der Software müssen enthalten:
* Den obigen Urheberrechtshinweis
* Diesen Erlaubnishinweis
* Den nachstehenden Haftungsausschluss

#### 3. Urheberrecht

Die Software und alle zugehörige Dokumentation sind durch Urheberrechtsgesetze geschützt. Die **SA-MP Programming Community** behält die ursprünglichen Urheberrechte an der Software.

#### 4. Gewährleistungsausschluss und Haftungsbeschränkung

DIE SOFTWARE WIRD "WIE BESEHEN" ZUR VERFÜGUNG GESTELLT, OHNE JEGLICHE AUSDRÜCKLICHE ODER IMPLIZITE GEWÄHRLEISTUNG, EINSCHLIESSLICH, ABER NICHT BESCHRÄNKT AUF DIE GEWÄHRLEISTUNG DER MARKTGÄNGIGKEIT, DER EIGNUNG FÜR EINEN BESTIMMTEN ZWECK UND DER NICHTVERLETZUNG VON RECHTEN DRITTER.

DIE AUTOREN ODER URHEBERRECHTSINHABER SIND IN KEINEM FALL HAFTBAR FÜR ANSPRÜCHE, SCHÄDEN ODER ANDERE VERPFLICHTUNGEN, OB IN EINER VERTRAGS- ODER DELIKTKLAGE, DIE AUS ODER IN VERBINDUNG MIT DER SOFTWARE ODER DER NUTZUNG ODER ANDEREN GESCHÄFTEN MIT DER SOFTWARE ENTSTEHEN.

---

For detailed information about the MIT License, visit: https://opensource.org/licenses/MIT