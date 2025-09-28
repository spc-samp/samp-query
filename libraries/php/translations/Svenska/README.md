# SA-MP Query - PHP

<div align="center">

![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Architecture](https://img.shields.io/badge/Protocol-UDP-blueviolet?style=for-the-badge)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**Ett robust och motståndskraftigt PHP-bibliotek, utformat för att fråga status och information från SA-MP (San Andreas Multiplayer) och OMP (Open Multiplayer) servrar.**

</div>

## Språk

- Português: [README](../../)
- Deutsch: [README](../Deutsch/README.md)
- English: [README](../English/README.md)
- Español: [README](../Espanol/README.md)
- Français: [README](../Francais/README.md)
- Italiano: [README](../Italiano/README.md)
- Polski: [README](../Polski/README.md)
- Русский: [README](../Русский/README.md)
- Türkçe: [README](../Turkce/README.md)

## Innehållsförteckning

- [SA-MP Query - PHP](#sa-mp-query---php)
  - [Språk](#språk)
  - [Innehållsförteckning](#innehållsförteckning)
  - [Översikt](#översikt)
  - [Designprinciper och Arkitektur](#designprinciper-och-arkitektur)
    - [Modulär Arkitektur](#modulär-arkitektur)
    - [Motståndskraft: Backoff, Retries och Caching](#motståndskraft-backoff-retries-och-caching)
    - [Prestandaoptimering: Parallelism och Timeout-anpassning](#prestandaoptimering-parallelism-och-timeout-anpassning)
    - [Modern Objektorienterad Programmering (OOP) (PHP 8.1+)](#modern-objektorienterad-programmering-oop-php-81)
  - [Krav](#krav)
  - [Installation och Grundläggande Användning](#installation-och-grundläggande-användning)
    - [Initiering av klassen `Samp_Query`](#initiering-av-klassen-samp_query)
    - [`Get_All()`: Komplett och Optimerad Fråga](#get_all-komplett-och-optimerad-fråga)
    - [`Is_Online()`: Snabb Statuskontroll](#is_online-snabb-statuskontroll)
    - [`Get_Ping()`: Hämta Serverns Ping](#get_ping-hämta-serverns-ping)
    - [`Get_Info()`: Viktiga Serverdetaljer](#get_info-viktiga-serverdetaljer)
    - [`Get_Rules()`: Konfigurerade Serverregler](#get_rules-konfigurerade-serverregler)
    - [`Get_Players_Detailed()`: Lista över Spelare med Detaljer](#get_players_detailed-lista-över-spelare-med-detaljer)
    - [`Get_Players_Basic()`: Grundläggande Spelarlista](#get_players_basic-grundläggande-spelarlista)
    - [`Send_Rcon()`: Skicka Fjärrkommandon](#send_rcon-skicka-fjärrkommandon)
  - [Detaljerad Biblioteksstruktur och Exekveringsflöde](#detaljerad-biblioteksstruktur-och-exekveringsflöde)
    - [1. `constants.php`: Hjärtat av Konfigurationen](#1-constantsphp-hjärtat-av-konfigurationen)
    - [2. `opcode.php`: Protokollets Opcode Enum](#2-opcodephp-protokollets-opcode-enum)
    - [3. `exceptions.php`: Hierarkin av Anpassade Undantag](#3-exceptionsphp-hierarkin-av-anpassade-undantag)
    - [4. `server_types.php`: De oföränderliga Datamodellerna](#4-server_typesphp-de-oföränderliga-datamodellerna)
    - [5. `autoloader.php`: Den Automatiska Klassladdaren](#5-autoloaderphp-den-automatiska-klassladdaren)
    - [6. `logger.php`: Loggningsundersystemet](#6-loggerphp-loggningsundersystemet)
    - [7. `domain_resolver.php`: Domänupplösning med Persistent Cache](#7-domain_resolverphp-domänupplösning-med-persistent-cache)
    - [8. `socket_manager.php`: Den Robusta UDP-anslutningshanteraren](#8-socket_managerphp-den-robusta-udp-anslutningshanteraren)
    - [9. `packet_builder.php`: Byggaren av Binära Paket](#9-packet_builderphp-byggaren-av-binära-paket)
    - [10. `packet_parser.php`: Paketavkodaren med Kodningshantering](#10-packet_parserphp-paketavkodaren-med-kodningshantering)
    - [11. `samp-query.php`: Huvudklassen (Den Kompletta Orkestreraren)](#11-samp-queryphp-huvudklassen-den-kompletta-orkestreraren)
      - [Frågans Livscykel: Ett Pakets Resa](#frågans-livscykel-ett-pakets-resa)
        - [1. Initiering och Domänupplösning](#1-initiering-och-domänupplösning)
        - [2. `Fetch_Server_State()`: Cache och Kritisk Fråga om INFO/PING](#2-fetch_server_state-cache-och-kritisk-fråga-om-infoping)
        - [3. `Attempt_Query()`: Den Optimerade Retries-strategin](#3-attempt_query-den-optimerade-retries-strategin)
        - [4. `Execute_Query_Phase()`: Kommunikationsmotorn med Ping-detektering](#4-execute_query_phase-kommunikationsmotorn-med-ping-detektering)
        - [5. `Validate_Response()`: Det Semantiska Valideringslagret](#5-validate_response-det-semantiska-valideringslagret)
      - [Beräkning och Hantering av Adaptiv Timeout](#beräkning-och-hantering-av-adaptiv-timeout)
      - [Offentliga Frågemetoder](#offentliga-frågemetoder)
      - [RCON-kommunikation (`Send_Rcon`)](#rcon-kommunikation-send_rcon)
  - [Feldiagnostik och Undantag](#feldiagnostik-och-undantag)
    - [`Invalid_Argument_Exception`](#invalid_argument_exception)
    - [`Connection_Exception`](#connection_exception)
    - [`Malformed_Packet_Exception`](#malformed_packet_exception)
    - [`Rcon_Exception`](#rcon_exception)
  - [Licens](#licens)
    - [Användarvillkor](#användarvillkor)
      - [1. Beviljade rättigheter](#1-beviljade-rättigheter)
      - [2. Obligatoriska villkor](#2-obligatoriska-villkor)
      - [3. Upphovsrätt](#3-upphovsrätt)
      - [4. Garantifriskrivning och ansvarsbegränsning](#4-garantifriskrivning-och-ansvarsbegränsning)

## Översikt

Biblioteket **SA-MP Query - PHP** är en högpresterande och feltolerant lösning för PHP-utvecklare som behöver interagera med spel-servrar baserade på SA-MP/OMP-protokollet (UDP). Dess syfte är att kapsla in komplexiteten i det binära frågeprotokollet i ett rent och intuitivt PHP API, vilket gör det möjligt för webbapplikationer, launchers och verktyg att snabbt och tillförlitligt hämta detaljerad information om serverns tillstånd (spelare, regler, ping, etc.).

Bibliotekets design fokuserar på tre huvudpelare: **Motståndskraft**, **Prestanda** och **Modularitet**. Det är byggt för att hantera den opålitliga naturen hos UDP-protokollet, genom att implementera ett avancerat system med försök och *backoff* för att säkerställa att informationen hämtas även under ogynnsamma nätverksförhållanden eller servrar med hög latens.

## Designprinciper och Arkitektur

### Modulär Arkitektur

Biblioteket är uppdelat i komponenter med ett enda ansvar, var och en inkapslad i sin egen klass och fil.

- **Nätverksinfrastruktur:** `Domain_Resolver`, `Socket_Manager`.
- **Protokoll:** `Packet_Builder`, `Packet_Parser`, `Opcode` (Enum).
- **Affärslogik (Orkestrering):** `Samp_Query`.
- **Datamodeller:** `Server_Info`, `Players_Detailed`, etc.

### Motståndskraft: Backoff, Retries och Caching

UDP-protokollet garanterar inte leverans av paket. Klassen `Samp_Query` mildrar detta fel med en sofistikerad frågecykel.

- **Flera Adaptiva Försök:** Metoden `Attempt_Query` implementerar en cykel med upp till `Query::ATTEMPTS` (5 som standard) och dubbelt så många för kritiska frågor.
- **Backoff-strategi:** Exponentiell *backoff* implementeras i `Execute_Query_Phase`. Efter den första sändningen ökar intervallet för nya lyssningsförsök (`while`-loopen) från `Performance::INITIAL_RETRY_INTERVAL` (0.08s) med `Performance::BACKOFF_FACTOR` (1.3), upp till gränsen på 0.2s. Detta förhindrar överbelastning av paket och ökar chansen för ett svar i tid.
- **Caching av Svar:** Nya svar (giltiga i 2.0 sekunder) lagras i `response_cache`, vilket eliminerar behovet av att upprepa metadatafrågor under exekveringen av `Get_All()`.

### Prestandaoptimering: Parallelism och Timeout-anpassning

- **Parallella Frågor (Fan-out):** Metoden `Get_All()` skickar förfrågningar för `INFO`, `RULES` och `PLAYERS` samtidigt (i `$jobs`), vilket gör att svaren kan anlända i oordning och minimerar den totala väntetiden.
- **Persistent DNS-caching:** `Domain_Resolver` lagrar den upplösta IP-adressen i en lokal filcache med en TTL på 3600 sekunder, vilket undviker förseningar i domänupplösning vid efterföljande anrop.
- **Adaptiv Timeout:** Timeout för frågor om stora datamängder (som spelarlistan) justeras dynamiskt baserat på serverns `cached_ping`:
   ```
   Justerad Timeout = Bas Timeout + (Cached Ping * Ping Multiplier / 1000)
   ```
   Denna logik (implementerad i `Fetch_Player_Data`) säkerställer att servrar med hög latens har tillräckligt med tid att svara, utan att kompromissa med hastigheten på servrar med låg latens.

### Modern Objektorienterad Programmering (OOP) (PHP 8.1+)

Biblioteket använder moderna PHP-funktioner för att säkerställa säkerhet och tydlighet:

- **Strikt Typning** (`declare(strict_types=1)`).
- **Read-Only Egenskaper** (`public readonly` i `Samp_Query` och datamodellerna) för att säkerställa dataintegritet.
- **Typade Enums** (`enum Opcode: string`) för säker protokollkontroll.
- **Constructor Property Promotion** (i `Samp_Query::__construct` och modellerna).

## Krav

- **PHP:** Version **8.1 eller högre**.
- **PHP-tillägg:** `sockets` och `mbstring` (för hantering av UTF-8-kodning).

## Installation och Grundläggande Användning

För att börja använda biblioteket **SA-MP Query - PHP**, inkludera bara filen `samp-query.php` i ditt projekt. Denna fil kommer att hantera automatisk laddning av alla beroenden via sin interna autoloader.

```php
<?php
// Inkludera huvudklassen. Den kommer att hantera laddning av beroenden via autoloader.
require_once 'path/to/samp-query/samp-query.php'; 

// Använd namnrymden för huvudklassen
use Samp_Query\Samp_Query;
// Inkludera undantagen för mer specifik felhantering
use Samp_Query\Exceptions\Invalid_Argument_Exception;
use Samp_Query\Exceptions\Connection_Exception;
use Samp_Query\Exceptions\Rcon_Exception;

// Instansiera Samp_Query-klassen, och omslut den i ett try-catch-block för att hantera initieringsfel.
try {
    $server_query = new Samp_Query("play.example-samp-server.com", 7777);
    // Nu kan du använda de offentliga metoderna i $server_query
}
catch (Invalid_Argument_Exception $e) {
    echo "Argumentfel: " . $e->getMessage() . "\n";
}
catch (Connection_Exception $e) {
    echo "Anslutningsfel: " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Oväntat fel under initiering: " . $e->getMessage() . "\n";
}
```

### Initiering av klassen `Samp_Query`

Klassen `Samp_Query` är ingången till alla funktioner. Dess konstruktor kräver `hostname` (eller IP-adress) och `port` för servern du vill fråga.

```php
/**
 * Initierar en ny instans av Samp_Query-biblioteket.
 *
 * @param string $hostname Hostnamnet eller IP-adressen för SA-MP/OMP-servern.
 * @param int $port Serverns UDP-port (vanligtvis 7777).
 * 
 * @throws Invalid_Argument_Exception Om hostnamnet är tomt eller porten är ogiltig.
 * @throws Connection_Exception Om DNS-upplösning misslyckas eller socketen inte kan skapas.
 */
public function __construct(public readonly string $hostname, public readonly int $port)
```

<br>

---

### `Get_All()`: Komplett och Optimerad Fråga

Detta är den mest omfattande och rekommenderade metoden. Den utför flera frågor (INFO, RULES, PLAYERS) parallellt och på ett optimerat sätt, vilket minimerar den totala svarstiden och returnerar en komplett associativ array med all tillgänglig information.

```php
/**
 * Returnerar all tillgänglig serverinformation i ett enda optimerat anrop.
 * Inkluderar: is_online, ping, info (Server_Info), rules (array av Server_Rule),
 * players_detailed (array av Players_Detailed), players_basic (array av Players_Basic),
 * och execution_time_ms.
 *
 * @return array En associativ array som innehåller all serverinformation.
 * 
 * @throws Connection_Exception Om INFO-frågan, som är nödvändig för serverstatus, misslyckas.
 */
public function Get_All(): array
```

Användningsexempel:

```php
<?php
// ... (initiering av klassen $server_query) ...

try {
    $data = $server_query->Get_All();
    
    if ($data['is_online']) {
        echo "## Server Online: {$data['info']->hostname} ({$server_query->ip}:{$server_query->port}) ##\n";
        echo "Ping: {$data['ping']}ms | Total Frågetid: {$data['execution_time_ms']}ms\n";
        echo "Spelare: {$data['info']->players} / {$data['info']->max_players}\n";
        echo "Spelläge: {$data['info']->gamemode}\n";
        echo "Språk: {$data['info']->language}\n";
        echo "Lösenordsskyddad: " . ($data['info']->password ? "Ja" : "Nej") . "\n\n";

        // Exempel på detaljerad spelarlista
        if (!empty($data['players_detailed'])) {
            echo "--- Detaljerade Spelare ({$data['info']->players} Aktiva) ---\n";

            foreach ($data['players_detailed'] as $player)
                echo "ID: {$player->id}, Namn: {$player->name}, Ping: {$player->ping}ms, Poäng: {$player->score}\n";
        }
        elseif (!empty($data['players_basic'])) {
            echo "--- Grundläggande Spelare ({$data['info']->players} Aktiva) (Fallback) ---\n";

            foreach ($data['players_basic'] as $player)
                echo "Namn: {$player->name}, Poäng: {$player->score}\n";
        }
        else
            echo "Inga spelare online eller listan är otillgänglig (kanske för många spelare).\n";
        
        // Exempel på serverregler
        if (!empty($data['rules'])) {
            echo "\n--- Serverregler ---\n";

            foreach ($data['rules'] as $rule)
                echo "{$rule->name}: {$rule->value}\n";
        }
    }
    else
        echo "Servern är för närvarande offline eller oåtkomlig.\n";
}
catch (Connection_Exception $e) {
    echo "Anslutningsfel vid försök att hämta all information: " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Oväntat fel vid förfrågan om all information: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Is_Online()`: Snabb Statuskontroll

Kontrollerar om servern är online och svarar på frågor, utan att hämta ytterligare detaljer. Perfekt för en enkel "liveness check".

```php
/**
 * Kontrollerar om servern är online och tillgänglig.
 *
 * @return bool Returnerar true om servern är online och svarar med giltig INFO, annars false.
 */
public function Is_Online(): bool
```

Användningsexempel:

```php
<?php
// ... (initiering av klassen $server_query) ...

if ($server_query->Is_Online())
    echo "SA-MP-servern är online och svarar!\n";
else
    echo "SA-MP-servern är offline eller svarade inte i tid.\n";
```

<br>

---

### `Get_Ping()`: Hämta Serverns Ping

Returnerar serverns latens (ping) i millisekunder. Detta värde cachas internt för optimering.

```php
/**
 * Returnerar serverns aktuella ping i millisekunder.
 * Om pingen ännu inte har beräknats, kommer en snabb PING-fråga att utföras.
 *
 * @return int|null Pingvärdet i millisekunder, eller null om det inte kan hämtas.
 */
public function Get_Ping(): ?int
```

Användningsexempel:

```php
<?php
// ... (initiering av klassen $server_query) ...

try {
    $ping = $server_query->Get_Ping();

    if ($ping !== null)
        echo "Serverns ping är: {$ping}ms.\n";
    else
        echo "Kunde inte hämta serverns ping.\n";
}
catch (Connection_Exception $e) {
    echo "Fel vid hämtning av ping: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Info()`: Viktiga Serverdetaljer

Hämtar grundläggande information om servern, som hostname, spelläge, antal spelare, etc. Returnerar ett `Server_Info`-objekt.

```php
/**
 * Returnerar de grundläggande serverdetaljerna (hostname, spelare, gamemode, etc.).
 * Datan cachas under en kort period för optimering.
 *
 * @return Server_Info|null Ett Server_Info-objekt, eller null om informationen inte kan hämtas.
 */
public function Get_Info(): ?Server_Info
```

Användningsexempel:

```php
<?php
use Samp_Query\Models\Server_Info;
// ... (initiering av klassen $server_query) ...

try {
    /** @var Server_Info|null $info */
    $info = $server_query->Get_Info();

    if ($info) {
        echo "--- Serverinformation ---\n";
        echo "Hostname: {$info->hostname}\n";
        echo "Gamemode: {$info->gamemode}\n";
        echo "Spelare: {$info->players} / {$info->max_players}\n";
        echo "Språk: {$info->language}\n";
        echo "Lösenordsskyddad: " . ($info->password ? "Ja" : "Nej") . "\n";
    }
    else
        echo "Kunde inte hämta serverinformationen.\n";
}
catch (Connection_Exception $e) {
    echo "Fel vid hämtning av serverinformation: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Rules()`: Konfigurerade Serverregler

Hämtar alla konfigurerade regler på servern, som `mapname`, `weburl`, `weather`, etc., och returnerar dem som en array av `Server_Rule`-objekt.

```php
/**
 * Returnerar en array av Server_Rule-objekt, var och en innehållande namnet och värdet på en serverregel.
 * Datan cachas för optimering.
 *
 * @return array En array av Samp_Query\Models\Server_Rule. Kan vara tom om inga regler finns.
 */
public function Get_Rules(): array
```

Användningsexempel:

```php
<?php
use Samp_Query\Models\Server_Rule;
// ... (initiering av klassen $server_query) ...

try {
    $rules = $server_query->Get_Rules();

    if (!empty($rules)) {
        echo "--- Serverregler ---\n";

        foreach ($rules as $rule)
            echo "{$rule->name}: {$rule->value}\n";

        // Exempel på hur man kommer åt en specifik regel:
        $mapname_rule = array_filter($rules, fn(Server_Rule $r) => $r->name === 'mapname');

        if (!empty($mapname_rule))
            echo "\nNuvarande karta: " . reset($mapname_rule)->value . "\n";
    }
    else
        echo "Inga regler hittades för denna server.\n";
}
catch (Connection_Exception $e) {
    echo "Fel vid hämtning av serverregler: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Players_Detailed()`: Lista över Spelare med Detaljer

Hämtar en detaljerad lista över spelare som för närvarande är online, inklusive ID, namn, poäng och ping.

> [!CAUTION]
> För att optimera prestanda och undvika överdrivet stora UDP-paket som kan gå förlorade eller fragmenteras, kommer denna metod inte att hämta den detaljerade spelarlistan om det totala antalet spelare är lika med eller större än `Query::LARGE_PLAYER_THRESHOLD` (150 som standard). I dessa fall kommer en tom array att returneras. Överväg att använda `Get_Players_Basic()` som en fallback.

```php
/**
 * Returnerar en array av Players_Detailed-objekt (ID, namn, poäng, ping) för varje onlinespelare.
 * Denna fråga kan ignoreras om antalet spelare är mycket högt (se Query::LARGE_PLAYER_THRESHOLD).
 *
 * @return array En array av Samp_Query\Models\Players_Detailed. Kan vara tom.
 */
public function Get_Players_Detailed(): array
```

Användningsexempel:

```php
<?php
use Samp_Query\Models\Players_Detailed;
// ... (initiering av klassen $server_query) ...

try {
    $players_detailed = $server_query->Get_Players_Detailed();

    if (!empty($players_detailed)) {
        echo "--- Onlinespelare (Detaljerad) ---\n";

        foreach ($players_detailed as $player)
            echo "ID: {$player->id}, Namn: {$player->name}, Poäng: {$player->score}, Ping: {$player->ping}ms\n";
    }
    else
        echo "Inga spelare online eller detaljerad lista otillgänglig.\n";
}
catch (Connection_Exception $e) {
    echo "Fel vid hämtning av detaljerad spelarlista: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Players_Basic()`: Grundläggande Spelarlista

Ger en lättare lista över spelare, som endast innehåller namn och poäng. Användbar som ett alternativ när den detaljerade listan inte är tillgänglig eller för att minska databelastningen.

```php
/**
 * Returnerar en array av Players_Basic-objekt (namn, poäng) för varje onlinespelare.
 * Användbar som ett lättare alternativ eller fallback när Get_Players_Detailed() inte är genomförbart.
 *
 * @return array En array av Samp_Query\Models\Players_Basic. Kan vara tom.
 */
public function Get_Players_Basic(): array
```

Användningsexempel:

```php
<?php
use Samp_Query\Models\Players_Basic;
// ... (initiering av klassen $server_query) ...

try {
    $players_basic = $server_query->Get_Players_Basic();

    if (!empty($players_basic)) {
        echo "--- Onlinespelare (Grundläggande) ---\n";

        foreach ($players_basic as $player)
            echo "Namn: {$player->name}, Poäng: {$player->score}\n";
    }
    else
        echo "Inga spelare online eller grundläggande lista otillgänglig.\n";
}
catch (Connection_Exception $e) {
    echo "Fel vid hämtning av grundläggande spelarlista: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Send_Rcon()`: Skicka Fjärrkommandon

Tillåter att skicka kommandon till serverns RCON-konsol, som att ändra regler, banna spelare, etc. Kräver serverns RCON-lösenord.

> [!WARNING]
> RCON-funktionen är känslig och kan orsaka ändringar på servern. Använd med försiktighet och endast med betrodda lösenord.
> Det är avgörande att RCON-lösenordet är **korrekt** och att RCON är **aktiverat** på servern (inställningen `rcon_password` i `server.cfg`).

```php
/**
 * Skickar ett RCON-kommando till servern.
 * Utför autentisering med 'varlist' och skickar kommandot.
 *
 * @param string $rcon_password Serverns RCON-lösenord.
 * @param string $command Kommandot som ska köras (t.ex. "gmx", "kick ID").
 * @return string Serverns svar på RCON-kommandot, eller ett statusmeddelande.
 * 
 * @throws Invalid_Argument_Exception Om RCON-lösenordet eller kommandot är tomt.
 * @throws Rcon_Exception Om RCON-autentisering misslyckas eller kommandot inte får något svar.
 * @throws Connection_Exception Vid anslutningsfel under RCON-operationen.
 */
public function Send_Rcon(string $rcon_password, string $command): string
```

Användningsexempel:

```php
<?php
// ... (initiering av klassen $server_query) ...

$rcon_password = "ditt_hemliga_lösenord_här"; 
$command_to_send = "gmx"; // Exempel: starta om spelläget

try {
    echo "Försöker skicka RCON-kommando: '{$command_to_send}'...\n";
    $response = $server_query->Send_Rcon($rcon_password, $command_to_send);
    echo "Svar från RCON: {$response}\n";

    // Exempel på kommando för att "säga" något på servern (kräver RCON)
    $response_say = $server_query->Send_Rcon($rcon_password, "say Hej från mitt PHP-skript!");
    echo "Svar från RCON (say): {$response_say}\n";
}
catch (Invalid_Argument_Exception $e) {
    echo "RCON-fel (Ogiltigt Argument): " . $e->getMessage() . "\n";
}
catch (Rcon_Exception $e) {
    echo "RCON-fel: Autentisering misslyckades eller kommandot kördes inte. " . $e->getMessage() . "\n";
}
catch (Connection_Exception $e) {
    echo "RCON-fel (Anslutning): " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Oväntat fel vid sändning av RCON: " . $e->getMessage() . "\n";
}
```

## Detaljerad Biblioteksstruktur och Exekveringsflöde

Biblioteket **SA-MP Query - PHP** är noggrant organiserat i flera filer, var och en med ett väldefinierat ansvar. Denna sektion utforskar varje komponent i detalj och avslöjar designbeslut och den underliggande logiken.

### 1. `constants.php`: Hjärtat av Konfigurationen

Denna fil centraliserar alla "magiska" konfigurationsparametrar för biblioteket, vilket säkerställer att aspekter som *timeouts*, antal försök och buffertstorlekar är lätta att justera och konsekventa i hela projektet.

```php
// ...
final class Query {
    public const ATTEMPTS = 5; // Maximalt antal frågeförsök
    public const LARGE_PLAYER_THRESHOLD = 150; // Spelargräns för detaljerad fråga
}

final class Protocol {
    public const UDP_READ_BUFFER_SIZE = 32768; // 32KB för läsbufferten
    public const KERNEL_RECEIVE_BUFFER_SIZE = 4194304; // 4MB för kärnans buffert
}
// ...
```

- **Final Classes och Constanter:** Klasserna är `final` och egenskaperna är `public const`, vilket garanterar oföränderlighet och global åtkomst vid kompileringstid.
- **Granularitet och Semantik:** Konstanterna är kategoriserade efter deras domän (`Query`, `Protocol`, `Performance`, `Logger`, `DNS`), vilket underlättar förståelse och underhåll. Till exempel definierar `Query::LARGE_PLAYER_THRESHOLD` den punkt där sökning efter detaljerade spelarlistor kan undvikas för optimering, på grund av datamängden och potentialen för *timeouts*.

### 2. `opcode.php`: Protokollets Opcode Enum

Denna fil definierar operationskoderna (opcodes) som används för de olika frågorna till SA-MP/OMP-servern, och kapslar in dem i en typad `enum`.

```php
// ...
enum Opcode: string {
    case INFO = 'i';
    case RULES = 'r';
    case PLAYERS_DETAILED = 'd';
    // ...
}
```

- **Typad `Enum` (PHP 8.1+):** Användningen av en `enum` (`Opcode: string`) med `string`-värden säkerställer att opcoderna alltid är giltiga och att koden är semantiskt tydlig. Detta ersätter användningen av "magiska" strängliteraler, vilket gör koden mer läsbar och mindre benägen för stavfel.

### 3. `exceptions.php`: Hierarkin av Anpassade Undantag

Denna fil etablerar en hierarki av anpassade undantag, vilket möjliggör en mer granulär och specifik felhantering för de olika typer av fel som kan uppstå i biblioteket.

```php
// ...
class Query_Exception extends \Exception {}
class Invalid_Argument_Exception extends Query_Exception {}
class Connection_Exception extends Query_Exception {}
// ...
```

- **Arv från `\Exception`:** Alla undantag ärver från `Query_Exception` (som i sin tur utökar `\Exception`), vilket gör det möjligt att fånga grupper av fel (`Connection_Exception` och `Timeout_Exception` är mer specifika än `Query_Exception`) eller alla undantag från biblioteket med en mer generisk `catch`.
- **Tydlighet i Diagnostik:** De beskrivande namnen på undantagen underlättar diagnostik och felåterhämtning i klientapplikationen.

### 4. `server_types.php`: De oföränderliga Datamodellerna

Denna fil definierar klasserna som representerar datamodellerna för informationen som returneras av servern, vilket säkerställer dataintegritet genom oföränderlighet.

```php
// ...
final class Server_Info {
    public function __construct(
        public readonly bool $password,
        public readonly int $players,
        // ... andra readonly-egenskaper ...
    ) {}
}
// ...
```

- **`final class`:** Klasserna är `final`, vilket förhindrar utökning och garanterar deras struktur och beteende.
- **`public readonly` Properties (PHP 8.1+):** Alla egenskaper deklareras som `public readonly`. Detta innebär att när ett objekt väl har skapats kan dess värden inte ändras, vilket garanterar integriteten hos data som tas emot från servern.
- **Constructor Property Promotion (PHP 8.1+):** Egenskaperna deklareras direkt i konstruktorn, vilket förenklar koden och minskar *boilerplate*.

### 5. `autoloader.php`: Den Automatiska Klassladdaren

Denna fil ansvarar för att dynamiskt ladda bibliotekets klasser när de behövs, enligt PSR-4-standarden.

```php
// ...
spl_autoload_register(function ($class) {
    $prefix = 'Samp_Query\\';
    $base_dir = __DIR__ . '/../modules/'; // Mappar namnrymden till katalogen
    // ... laddningslogik ...
});

// Inkluderar nödvändiga filer som inte är klasser, eller som behöver laddas i förväg
require_once __DIR__ . '/../modules/constants.php';
require_once __DIR__ . '/../modules/exceptions.php';
// ...
```

- **`spl_autoload_register()`:** Registrerar en anonym funktion som anropas automatiskt av PHP när en odefinierad klass refereras, vilket effektiviserar utveckling och underhåll.
- **Direkt Inkludering av Konfigurationer:** Filer som `constants.php` och `exceptions.php` inkluderas direkt. Detta säkerställer att deras definitioner är tillgängliga innan någon klass som använder dem instansieras av autoloadern.

### 6. `logger.php`: Loggningsundersystemet

Klassen `Logger` tillhandahåller en enkel mekanism för att registrera felmeddelanden och viktiga händelser i en loggfil, vilket är användbart för felsökning och övervakning.

```php
// ...
final class Logger {
    public function Log(string $message): void {
        if (is_file(Logger_Config::FILE) && filesize(Logger_Config::FILE) > Logger_Config::MAX_SIZE_BYTES)
            unlink(Logger_Config::FILE); // Ta bort loggen om den överskrider storleken

        $timestamp = date('Y-m-d H:i:s');
        $log_entry = "[{$timestamp}] {$message}" . PHP_EOL;
        
        file_put_contents(Logger_Config::FILE, $log_entry, FILE_APPEND | LOCK_EX); // Lägg till med lås
    }
}
```

- **Automatisk Rensning:** Loggern kontrollerar storleken på loggfilen (`Logger_Config::FILE`). Om den överskrider `Logger_Config::MAX_SIZE_BYTES` (10 MB som standard), raderas filen för att förhindra att den växer oändligt.
- **Fillåsning (`LOCK_EX`):** `file_put_contents` använder `LOCK_EX` för att säkerställa att endast en process skriver till loggfilen åt gången, vilket förhindrar korruption i flertrådade/flerprocessmiljöer.

### 7. `domain_resolver.php`: Domänupplösning med Persistent Cache

Klassen `Domain_Resolver` ansvarar för att omvandla värdnamn (som `play.example.com`) till IP-adresser (som `192.0.2.1`). Den implementerar ett cache-system på disk för att optimera prestanda.

```php
// ...
final class Domain_Resolver {
    public function Resolve(string $hostname): string {
        if (filter_var($hostname, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
            return $hostname; // Redan en IP

        $cache_file = DNS_Config::CACHE_DIR . md5($hostname) . '.cache';

        if (file_exists($cache_file) && (time() - filemtime($cache_file)) < DNS_Config::CACHE_TTL_SECONDS) {
            $ip = file_get_contents($cache_file);

            if ($ip && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
                return $ip;
        }
        
        $ip = gethostbyname($hostname); // Faktisk DNS-upplösning
        // ... validerings- och cachningslogik ...

        return $ip;
    }
    // ...
}
```

- **Persistent Cache:** Innan `gethostbyname()` anropas, kontrollerar den om IP-adressen redan är lagrad i en cache-fil (`dns/` + MD5-hash av värdnamnet). Cachen anses giltig om den inte har överskridit `DNS_Config::CACHE_TTL_SECONDS` (3600 sekunder eller 1 timme som standard).
- **Robust Validering:** Den upplösta IP-adressen (eller den som lästs från cachen) valideras med `filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)` för att säkerställa att det är en giltig IPv4. Om upplösningen misslyckas, kastas en `Query_Exception`.

### 8. `socket_manager.php`: Den Robusta UDP-anslutningshanteraren

Klassen `Socket_Manager` kapslar in komplexiteten i att skapa, konfigurera och hantera en UDP-socket för kommunikation med spel-servern.

```php
// ...
final class Socket_Manager {
    private $socket = null;
    // ...
    private function Connect(): void {
        if (is_resource($this->socket))
            return;

        $context = stream_context_create([
            'socket' => ['so_rcvbuf' => Protocol::KERNEL_RECEIVE_BUFFER_SIZE] // Ökar bufferten till 4MB
        ]);

        $this->socket = stream_socket_client(
            "udp://{$this->host}:{$this->port}", 
            $err_no, $err_str, 
            (float)ini_get("default_socket_timeout"), 
            STREAM_CLIENT_CONNECT, // Ansluter socketen till fjärradressen
            $context
        );

        if ($this->socket === false)
            throw new Connection_Exception("Could not create socket...");
    }
    // ...
}
```

- **`stream_socket_client` med `STREAM_CLIENT_CONNECT`:** Denna flagga instruerar operativsystemet att "ansluta" UDP-socketen till fjärradressen. Även om UDP är anslutningslöst, möjliggör "anslutning" av socketen prestandaoptimeringar på kärnnivå, som att inte behöva specificera fjärradressen i varje `fwrite`- eller `stream_socket_recvfrom`-anrop, vilket resulterar i mindre overhead.
- **Kärnans Mottagningsbuffert:** `stream_context_create` används för att öka storleken på kärnans mottagningsbuffert (`so_rcvbuf`) till `Protocol::KERNEL_RECEIVE_BUFFER_SIZE` (4MB). Detta är avgörande för att undvika paketförlust (buffertöverflöde) när stora svar tas emot, som detaljerade spelarlistor från upptagna servrar.
- **RAII via `__destruct`:** Metoden `Disconnect()` anropas automatiskt i destruktorn (`__destruct`), vilket säkerställer att socketen stängs och resurserna frigörs, även vid undantag.
- **Dynamisk Timeout:** `Set_Timeout` justerar noggrant socketens läs-/skriv-timeouts med `stream_set_timeout`, vilket är grundläggande för logiken med *retries* och *backoff*.

### 9. `packet_builder.php`: Byggaren av Binära Paket

Klassen `Packet_Builder` ansvarar för att serialisera frågedata till ett specifikt binärt format som SA-MP/OMP-servern kan förstå.

```php
// ...
final class Packet_Builder {
    // ...
    private function Build_Header(): string {
        $packet = 'SAMP';
        $packet .= pack('c4', ...$this->ip_parts); // IP i 4 byte
        $packet .= pack('v', $this->port); // Port i 2 byte (little-endian)

        return $packet;
    }
    
    public function Build(string $opcode): string {
        $packet = $this->Build_Header();
        $packet .= $opcode;

        if ($opcode === Opcode::PING->value)
            $packet .= random_bytes(4); // Slumpmässig payload för PING
        
        return $packet;
    }
    // ...
}
```

- **`pack()` för Binärt Format:** Använder PHP:s `pack()`-funktion för att konvertera data (IP, port, stränglängder) till deras korrekta binära format, som `c4` för 4 byte tecken (IP) och `v` för 16-bitars osignerat heltal (port och längder), vilket är *little-endian*.
- **Standard Header:** `Build_Header()` skapar den 10-byte 'SAMP'-headern som föregår alla paket.
- **RCON-struktur:** `Build_Rcon` formaterar RCON-paketet med opcoden 'x' följt av lösenordets längd, lösenordet, kommandots längd och själva kommandot.

### 10. `packet_parser.php`: Paketavkodaren med Kodningshantering

Klassen `Packet_Parser` är motparten till `Packet_Builder`, ansvarig för att tolka de binära svaren som tas emot från servern och omvandla dem till strukturerad PHP-data.

```php
// ...
final class Packet_Parser {
    private int $offset = Protocol::MIN_PACKET_LENGTH; // Börjar efter headern (11 byte)
    // ...
    public function __construct(private readonly string $data) {
        // Initial validering av 'SAMP'-headern
        if (substr($this->data, 0, 4) !== 'SAMP' || strlen($this->data) < Protocol::MIN_PACKET_LENGTH)
            throw new Malformed_Packet_Exception("Invalid response header or packet too short.");
    }
    
    private function Extract_String(int $length_bytes): string {
        // ... logik för att läsa längd och sträng ...
        $string = substr($this->data, $this->offset, $length);
        $this->offset += $length;
        
        // **KRITISK KODNINGSKONVERTERING:** SA-MP/OMP-servrar använder Windows-1252
        return mb_convert_encoding($string, 'UTF-8', 'Windows-1252');
    }
    // ...
}
```

- **`offset` och `data_length`:** `offset` används för att spåra den aktuella positionen vid läsning av paketet, medan `data_length` förhindrar läsningar utanför buffertens gränser.
- **Validering av Header:** Konstruktorn validerar den "magiska strängen" `SAMP` för att omedelbart avvisa felaktigt formaterade paket.
- **`Extract_String()` - Avgörande Kodningskonvertering:** Detta är en av de viktigaste funktionerna. SA-MP-protokollet överför strängar med kodningen **Windows-1252**. För att säkerställa att specialtecken (som accenter eller kyrilliska bokstäver) visas korrekt i PHP-applikationer (som vanligtvis arbetar i UTF-8), tillämpas metoden `mb_convert_encoding($string, 'UTF-8', 'Windows-1252')`.
- **Extrahering av Variabel Längd:** Metoden `Extract_String` stöder olika storlekar på längdprefix för strängar (1, 2 eller 4 byte), vilket gör den flexibel för olika fält i protokollet.

### 11. `samp-query.php`: Huvudklassen (Den Kompletta Orkestreraren)

Klassen `Samp_Query` är den huvudsakliga ingångspunkten och orkestreraren för alla operationer. Den binder samman alla komponenter, hanterar frågestatus, *retries*-logik och *timeouts*.

#### Frågans Livscykel: Ett Pakets Resa

Hela processen att fråga en server följer en sekvens av noggrant orkestrerade steg, med sikte på maximal motståndskraft och prestanda.

##### 1. Initiering och Domänupplösning

När en instans av `Samp_Query` skapas:
- **Snabb Validering:** Konstruktorn validerar parametrarna `$hostname` och `$port`. Eventuella inkonsekvenser resulterar i en `Invalid_Argument_Exception`.
- **Rensning av DNS-cache:** `Domain_Resolver::Clean_Expired_Cache()` anropas för att säkerställa att endast giltiga och inte utgångna DNS-poster beaktas.
- **IP-upplösning:** `Domain_Resolver` används för att omvandla `$hostname` till en IP-adress (`$this->ip`). Denna IP cachas på disk för framtida förfrågningar, och en `Query_Exception` kastas om upplösningen misslyckas.
- **Resurskonfiguration:** `Logger`, `Socket_Manager` och `Packet_Builder` instansieras, vilket förbereder infrastrukturen för kommunikation.

##### 2. `Fetch_Server_State()`: Cache och Kritisk Fråga om INFO/PING

Denna interna metod är en väktare av prestanda och konsistens, som säkerställer att grundläggande serverinformation (`Server_Info`) och `ping` alltid är uppdaterade före någon huvudfråga.

- **Primär Cache (5 Sekunder):** Innan någon kommunikation påbörjas, kontrolleras det om `$this->cached_info` (serverns `Server_Info`-objekt) har data som är mindre än 5 sekunder gammal (jämfört med `$this->last_successful_query`). Om datan är färsk, återvänder funktionen omedelbart, vilket undviker onödig nätverkstrafik.
- **Kritisk INFO-fråga:** Om cachen är utgången eller tom, anropas metoden `Attempt_Query` för att hämta `INFO`-data. Denna fråga markeras som **kritisk** (`true`), vilket utlöser ett större antal försök och mer generösa *timeouts*. En `Connection_Exception` kastas om INFO-svaret är ogiltigt efter alla försök.
- **Ping-beräkning:** Om `$this->cached_ping` fortfarande är null, utförs en snabb `PING`-fråga (`Execute_Query_Phase` med `Performance::FAST_PING_TIMEOUT`). Pingen beräknas som den tid som förflutit tills det **första** mottagna svaret, vilket garanterar noggrannhet.

##### 3. `Attempt_Query()`: Den Optimerade Retries-strategin

Detta är hjärnan i bibliotekets motståndskraft, som hanterar den högnivåcykeln av försök för ett eller flera `$jobs` (opcode-frågor).

- **Svarscache (2 Sekunder):** Först kontrollerar den om svaren för något av `$jobs` redan finns i `$this->response_cache` (med mindre än 2.0 sekunder). Detta förhindrar onödiga *retries* för flyktiga men inte kritiska data.
- **Fas med Snabba Retries:** Biblioteket försöker först `Query::FAST_RETRY_ATTEMPTS` (2 som standard) med en kortare *timeout* (`$timeout * 0.6`). Målet är att få ett svar så snabbt som möjligt, utan att införa betydande förseningar.
- **Fas med Standard Retries med Backoff:** Om den snabba fasen inte räcker, fortsätter cykeln med resten av `Query::ATTEMPTS`. I denna fas ökar `$adjusted_timeout` progressivt med varje försök, vilket ger servern mer tid att svara. Viktigast är att `usleep()` inför en ökande fördröjning (baserad på `Query::RETRY_DELAY_MS` och en ökningfaktor) mellan anropen till `Execute_Query_Phase`, vilket låter nätverket och servern stabiliseras.
- **Nödförsök (för Kritiska Frågor):** För `$jobs` markerade som `critical`, om alla tidigare försök misslyckas, görs ett sista försök för varje jobb individuellt, med en ännu längre *timeout* (`$timeout * 2`). Detta är en sista chans att få vital information.

##### 4. `Execute_Query_Phase()`: Kommunikationsmotorn med Ping-detektering

Denna lågnivåmetod är där den faktiska interaktionen med UDP-socketen sker. Den hanterar sändning och mottagning av paket för en grupp av `$jobs` samtidigt i en enda nätverksfas.

```php
// ... (inuti Execute_Query_Phase)
private function Execute_Query_Phase(array $jobs, ?int &$ping, float $timeout): array {
    $socket = $this->socket_manager->Get_Socket_Resource($timeout);
    stream_set_blocking($socket, false); // Icke-blockerande socket

    // Skickar paket två gånger omedelbart för större garanti för UDP-leverans
    for ($i = 0; $i < 2; $i++) {
        foreach ($pending_jobs as $opcode)
            fwrite($socket, $this->packet_builder->Build($opcode->value));

        if ($i == 0)
            usleep(5000); // Liten fördröjning
    }

    $last_send_time = microtime(true);
    $current_retry_interval = Performance::INITIAL_RETRY_INTERVAL; // 0.08s
    
    while (!empty($pending_jobs) && (microtime(true) - $start_time) < $timeout) {
        $now = microtime(true);

        if (($now - $last_send_time) > $current_retry_interval) { // Logik för återsändning med backoff
            // ... skicka om väntande paket ...
            $current_retry_interval = min($current_retry_interval * Performance::BACKOFF_FACTOR, 0.2); // Öka retry-intervallet
        }

        $read = [$socket]; $write = $except = null

        if (stream_select($read, $write, $except, 0, 10000) > 0) { // Vänta på data (max 10ms)
            $response = stream_socket_recvfrom($socket, Protocol::UDP_READ_BUFFER_SIZE);
            // ... logik för parsning, ping-beräkning och validering ...
        }

        if (!empty($pending_jobs))
            usleep(1000); // Liten fördröjning för att undvika CPU-spin
    }
    return $phase_results;
}
```

- **Icke-blockerande Socket:** `stream_set_blocking($socket, false)` är avgörande. Det gör att PHP kan skicka paket och sedan vänta på svar utan att blockera exekveringen, med hjälp av `stream_select`.
- **Dubbel Sändning för Robusthet:** Paketen för alla `$pending_jobs` skickas **två gånger** i rad (med en liten `usleep(5000)` mellan dem) i början av fasen. Denna praxis är grundläggande i UDP-protokoll för att avsevärt öka sannolikheten för leverans i instabila nätverk eller med paketförlust, vilket mildrar den opålitliga naturen hos UDP. För `INFO` och `PING` görs en tredje extra sändning under *retries* i huvudloopen.
- **Mottagningsloop med Adaptiv Backoff:**
   - En huvud-`while`-loop fortsätter tills alla `$jobs` är slutförda eller fasens *timeout* löper ut.
   - **Dynamisk Återsändning:** Om tiden som förflutit sedan den senaste sändningen (`$now - $last_send_time`) överstiger `$current_retry_interval`, skickas paketen för `$pending_jobs` om. `$current_retry_interval` ökas sedan (`min($current_retry_interval * Performance::BACKOFF_FACTOR, 0.2)`), vilket implementerar en exponentiell *backoff* som undviker överbelastning av servern och maximerar chanserna för ett svar.
   - **Optimerad `stream_select`:** `stream_select($read, $write, $except, 0, 10000)` används för att vänta på data i högst 10 millisekunder. Detta gör att biblioteket är responsivt och kan bearbeta paket så fort de anländer.
   - **Noggrann Ping-mätning:** När det **första** giltiga paketet tas emot (`$packets_received === 0`), beräknas `ping` med hög precision som skillnaden mellan `microtime(true)` i början av sändningen av den första omgången paket och den exakta tiden för mottagandet av det **första** giltiga paketet.
- **Bearbetning och Validering av Svar:** De mottagna svaren avkodas av `Packet_Parser`. Om ett `Malformed_Packet_Exception` upptäcks, loggas det, och paketet skickas omedelbart om till servern för att försöka igen. Det avkodade svaret valideras sedan av `Validate_Response()`. Om det är giltigt, läggs det till i `$phase_results` och `$this->response_cache`.

##### 5. `Validate_Response()`: Det Semantiska Valideringslagret

Denna avgörande metod, implementerad i klassen `Samp_Query`, verifierar integriteten och den logiska konsistensen hos de avkodade data innan de levereras till användaren.

```php
// ... (inuti Validate_Response)
private function Validate_Response($data, Opcode $opcode): bool {
    switch ($opcode) {
        case Opcode::INFO:
            // Säkerställer att hostnamnet inte är tomt och att spelarantalen är logiska
            return $data instanceof Server_Info && $data->max_players > 0 && $data->players >= 0 && $data->players <= $data->max_players && !empty($data->hostname);
        
        case Opcode::RULES:
            if (!is_array($data))
                return false;

            // ...

            return true;
        // ... valideringar för PLAYERS_DETAILED, PLAYERS_BASIC, PING ...
    }
}
```

- **Validering per Opcode:** Varje `Opcode` har sin egen specifika valideringslogik. Till exempel:
   - För `Opcode::INFO`: Säkerställer att `$data` är en instans av `Server_Info`, att `$data->max_players > 0`, `$data->players` är mellan 0 och `max_players`, och att `$data->hostname` inte är tomt.
   - För `Opcode::RULES` eller spelarlistor: Kontrollerar om returen är en `array` och, om den inte är tom, om det första elementet är av den förväntade modellinstansen (`Server_Rule`, `Players_Detailed`, etc.).
- **Robusthet:** Om valideringen misslyckas, anses svaret vara ogiltigt och kasseras. Detta tvingar systemet att fortsätta försöken, som om paketet aldrig hade anlänt, vilket skyddar applikationen mot korrupta eller inkonsekventa data från servern.

#### Beräkning och Hantering av Adaptiv Timeout

Biblioteket implementerar en sofistikerad *timeout*-strategi för att balansera hastighet och motståndskraft:

- **`Performance::METADATA_TIMEOUT`:** (0.8 sekunder) Är bas-*timeout* för snabba frågor som `INFO` och `RULES`.
- **`Performance::PLAYER_LIST_BASE_TIMEOUT`:** (1.0 sekund) Är bas-*timeout* för frågor om spelarlistor.
- **`Performance::PING_MULTIPLIER`:** (2) Används för att justera *timeout* baserat på ping.
- **Justering efter Ping:** I metoden `Fetch_Player_Data` justeras *timeout* för att hämta spelarlistan dynamiskt:
   ```
   Spelar-timeout = PLAYER_LIST_BASE_TIMEOUT + (Cached Ping * PING_MULTIPLIER / 1000)
   ```
   Detta tillvägagångssätt gör att servrar med hög latens (hög ping) får en längre *timeout*, vilket ökar chansen att lyckas hämta den kompletta spelarlistan, som kan vara stor och tidskrävande för servern att bearbeta.
- **Timeout-gräns:** `min($timeout, 2.0)` används i flera anrop för att införa en maximal gräns på 2.0 sekunder för metadatafrågor, vilket förhindrar överdrivna väntetider.

#### Offentliga Frågemetoder

| Metod | Detaljerad Beskrivning | Internt Cache-beteende |
| :--- | :--- | :--- |
| `Get_All()` | **Rekommenderad metod för allmänt bruk.** Orkestrerar hämtning av `INFO`, `RULES`, `PLAYERS_DETAILED` (eller `PLAYERS_BASIC` som fallback) parallellt. Detta minimerar den totala frågetiden, eftersom paketen skickas nästan samtidigt och svaren bearbetas när de anländer. Inkluderar en mätning av total `execution_time_ms`. | Använder 2.0s-cachen (`$this->response_cache`) för varje opcode som frågas inom den parallella fasen. |
| `Is_Online()` | Utför en snabb `INFO`-fråga och returnerar `true` om servern svarar med en giltig `Server_Info` inom *timeout*, annars `false`. Den är robust och använder kritiska *retries*. | Internt anropas `Fetch_Server_State()`, som använder 5.0s-cachen för `INFO`. |
| `Get_Ping()` | Returnerar serverns senaste ping i millisekunder. Om `cached_ping` är null, tvingas en dedikerad `PING`-fråga med `Performance::FAST_PING_TIMEOUT` (0.3s) för att få ett snabbt mått. | `ping` cachas och uppdateras varje gång `Execute_Query_Phase` tar emot det första svaret. |
| `Get_Info()` | Returnerar ett `Server_Info`-objekt med detaljer som hostname, gamemode, antal spelare, etc. | Anropar `Fetch_Server_State()`, som använder 5.0s-cachen. |
| `Get_Rules()` | Returnerar en `array` av `Server_Rule`-objekt som innehåller alla konfigurerade regler på servern (t.ex. `mapname`, `weburl`). Inkluderar extra *retries* vid initialt fel. | Använder 2.0s-cachen för `Opcode::RULES`. |
| `Get_Players_Detailed()` | Returnerar en `array` av `Players_Detailed`-objekt (id, namn, poäng, ping för varje spelare). **Viktigt:** Denna fråga ignoreras om antalet spelare på servern (`$this->cached_info->players`) är större än eller lika med `Query::LARGE_PLAYER_THRESHOLD` (150 som standard), på grund av risken för förlängda *timeouts* eller paketfragmentering. | Använder 2.0s-cachen för `Opcode::PLAYERS_DETAILED`. |
| `Get_Players_Basic()` | Returnerar en `array` av `Players_Basic`-objekt (namn, poäng för varje spelare). Är lättare än den detaljerade frågan. Anropas vanligtvis som en *fallback* om `Get_Players_Detailed()` misslyckas eller ignoreras. | Använder 2.0s-cachen för `Opcode::PLAYERS_BASIC`. |

#### RCON-kommunikation (`Send_Rcon`)

Metoden `Send_Rcon(string $rcon_password, string $command)` tillåter att skicka kommandon till serverns fjärrkonsol.

1.  **Validering av Argument:** Kastar `Invalid_Argument_Exception` om lösenordet eller kommandot är tomt.
2.  **Isolerad Socket:** Skapar en ny instans av `Socket_Manager` (`$rcon_socket_manager`) för RCON-sessionen, vilket isolerar den från den huvudsakliga frågesocketen för att undvika störningar.
3.  **Autentisering (`varlist`):** Innan det faktiska kommandot skickas, skickar biblioteket kommandot "varlist" (i upp till 3 försök) för att autentisera RCON-lösenordet. Om `Send_Single_Rcon_Request` returnerar `null` eller ett tomt svar, kastas en `Rcon_Exception`, vilket indikerar autentiseringsfel eller att RCON inte är aktiverat.
4.  **Sändning av Faktiskt Kommando:** Efter lyckad autentisering skickas `$command`, också med upp till 3 försök.
5.  **Svarshantering:** `Packet_Parser::Parse_Rcon()` avkodar textsvaret från RCON. Om servern inte returnerar ett textuellt svar efter alla försök, returneras ett generiskt framgångsmeddelande.
6.  **Rensning:** Destruktorn för `$rcon_socket_manager` säkerställer att RCON-socketen stängs efter operationen.

## Feldiagnostik och Undantag

Biblioteket använder en hierarki av anpassade undantag för en ren och förutsägbar felhantering. Vid fel kommer ett av följande undantag att kastas.

### `Invalid_Argument_Exception`

**Orsak:**
- **Tomt Hostname:** Det `hostname` som anges i `Samp_Query`-konstruktorn är en tom sträng.
- **Ogiltig Port:** Den `port` som anges i konstruktorn ligger utanför det giltiga intervallet 1 till 65535.
- **RCON:** RCON-lösenord eller RCON-kommando som anges för `Send_Rcon` är tomma.

### `Connection_Exception`

**Orsak:** Nätverksfel eller brist på nödvändigt svar.
- **Domänupplösning Misslyckades:** `Domain_Resolver` kan inte omvandla hostnamnet till en giltig IPv4.
- **Fel vid Skapande av Socket:** `Socket_Manager` kan inte skapa eller ansluta UDP-socketen.
- **Server Oåtkomlig/Offline:** Servern misslyckas med att svara med ett giltigt `INFO`-paket efter alla försök och *timeouts* (inklusive nödförsök), vilket vanligtvis indikerar att servern är offline, IP/port är felaktig, eller en brandvägg blockerar kommunikationen.

### `Malformed_Packet_Exception`

**Orsak:** Datakorruption på protokollnivå.
- **Ogiltig Header:** `Packet_Parser` upptäcker ett paket som inte börjar med den "magiska strängen" `SAMP` eller har en otillräcklig total längd.
- **Ogiltig Paketstruktur:** `Packet_Parser` hittar inkonsekvenser i den binära strukturen, som en stränglängd som pekar utanför paketets gränser.
- **Motståndskraft:** Detta undantag hanteras ofta internt av `Execute_Query_Phase` för att utlösa ett omedelbart *retry*, men kan propageras om problemet kvarstår.

### `Rcon_Exception`

**Orsak:** Fel under RCON-kommunikation.
- **RCON-autentisering Misslyckades:** Servern svarade inte på RCON-autentiseringen (via `varlist`-kommandot) efter 3 försök, vilket tyder på felaktigt lösenord eller att RCON är inaktiverat på servern.
- **Misslyckad Sändning av RCON-kommando:** Det faktiska RCON-kommandot fick inget svar efter 3 försök.

## Licens

Copyright © **SA-MP Programming Community**

Denna programvara är licensierad under villkoren i MIT-licensen ("Licensen"); du får använda denna programvara i enlighet med Licensens villkor. En kopia av Licensen kan erhållas på: [MIT License](https://opensource.org/licenses/MIT)

### Användarvillkor

#### 1. Beviljade rättigheter

Denna licens ger kostnadsfritt följande rättigheter till alla som erhåller en kopia av denna programvara och tillhörande dokumentationsfiler:
* Att använda, kopiera, modifiera, slå samman, publicera, distribuera, underlicensiera och/eller sälja kopior av programvaran utan begränsningar
* Att tillåta personer som programvaran tillhandahålls till att göra detsamma, under förutsättning att de följer nedanstående villkor

#### 2. Obligatoriska villkor

Alla kopior eller väsentliga delar av programvaran måste innehålla:
* Ovanstående upphovsrättsmeddelande
* Detta tillståndsmeddelande
* Nedanstående ansvarsfriskrivning

#### 3. Upphovsrätt

Programvaran och all tillhörande dokumentation skyddas av upphovsrättslagar. **SA-MP Programming Community** behåller den ursprungliga upphovsrätten till programvaran.

#### 4. Garantifriskrivning och ansvarsbegränsning

PROGRAMVARAN TILLHANDAHÅLLS "I BEFINTLIGT SKICK", UTAN NÅGON GARANTI AV NÅGOT SLAG, UTTRYCKLIG ELLER UNDERFÖRSTÅDD, INKLUSIVE MEN INTE BEGRÄNSAT TILL GARANTIER FÖR SÄLJBARHET, LÄMPLIGHET FÖR ETT SÄRSKILT SYFTE OCH ICKE-INTRÅNG.

UNDER INGA OMSTÄNDIGHETER SKA FÖRFATTARNA ELLER UPPHOVSRÄTTSINNEHAVARNA VARA ANSVARIGA FÖR NÅGRA ANSPRÅK, SKADOR ELLER ANNAT ANSVAR, VARE SIG I EN AVTALSHANDLING, SKADESTÅNDSANSPRÅK ELLER PÅ ANNAT SÄTT, SOM UPPSTÅR FRÅN, UT ELLER I SAMBAND MED PROGRAMVARAN ELLER ANVÄNDNINGEN ELLER ANNAT HANDHAVANDE AV PROGRAMVARAN.

---

För detaljerad information om MIT-licensen, besök: https://opensource.org/licenses/MIT