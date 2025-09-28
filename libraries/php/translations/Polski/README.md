# SA-MP Query - PHP

<div align="center">

![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Architecture](https://img.shields.io/badge/Protocol-UDP-blueviolet?style=for-the-badge)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**Solidna i odporna biblioteka PHP, zaprojektowana do odpytywania o stan i informacje serwerów SA-MP (San Andreas Multiplayer) i OMP (Open Multiplayer).**

</div>

## Języki

- Português: [README](../../)
- Deutsch: [README](../Deutsch/README.md)
- English: [README](../English/README.md)
- Español: [README](../Espanol/README.md)
- Français: [README](../Francais/README.md)
- Italiano: [README](../Italiano/README.md)
- Русский: [README](../Русский/README.md)
- Svenska: [README](../Svenska/README.md)
- Türkçe: [README](../Turkce/README.md)

## Spis treści

- [SA-MP Query - PHP](#sa-mp-query---php)
  - [Języki](#języki)
  - [Spis treści](#spis-treści)
  - [Przegląd](#przegląd)
  - [Zasady Projektowania i Architektura](#zasady-projektowania-i-architektura)
    - [Architektura Modułowa](#architektura-modułowa)
    - [Odporność: Backoff, Ponowne Próby i Buforowanie (Caching)](#odporność-backoff-ponowne-próby-i-buforowanie-caching)
    - [Optymalizacja Wydajności: Równoległość i Adaptacyjne Ustawianie Czasu Oczekiwania (Timeout)](#optymalizacja-wydajności-równoległość-i-adaptacyjne-ustawianie-czasu-oczekiwania-timeout)
    - [Nowoczesne Programowanie Obiektowe (OOP) (PHP 8.1+)](#nowoczesne-programowanie-obiektowe-oop-php-81)
  - [Wymagania](#wymagania)
  - [Instalacja i Podstawowe Użycie](#instalacja-i-podstawowe-użycie)
    - [Inicjalizacja Klasy `Samp_Query`](#inicjalizacja-klasy-samp_query)
    - [`Get_All()`: Pełne i Zoptymalizowane Zapytanie](#get_all-pełne-i-zoptymalizowane-zapytanie)
    - [`Is_Online()`: Szybkie Sprawdzanie Stanu](#is_online-szybkie-sprawdzanie-stanu)
    - [`Get_Ping()`: Pobieranie Pingu Serwera](#get_ping-pobieranie-pingu-serwera)
    - [`Get_Info()`: Podstawowe Szczegóły Serwera](#get_info-podstawowe-szczegóły-serwera)
    - [`Get_Rules()`: Skonfigurowane Reguły Serwera](#get_rules-skonfigurowane-reguły-serwera)
    - [`Get_Players_Detailed()`: Lista Graczy ze Szczegółami](#get_players_detailed-lista-graczy-ze-szczegółami)
    - [`Get_Players_Basic()`: Podstawowa Lista Graczy](#get_players_basic-podstawowa-lista-graczy)
    - [`Send_Rcon()`: Wysyłanie Zdalnych Poleceń](#send_rcon-wysyłanie-zdalnych-poleceń)
  - [Szczegółowa Struktura Biblioteki i Przebieg Wykonania](#szczegółowa-struktura-biblioteki-i-przebieg-wykonania)
    - [1. `constants.php`: Serce Konfiguracji](#1-constantsphp-serce-konfiguracji)
    - [2. `opcode.php`: Enum Opcode'ów Protokołu](#2-opcodephp-enum-opcodeów-protokołu)
    - [3. `exceptions.php`: Hierarchia Niestandardowych Wyjątków](#3-exceptionsphp-hierarchia-niestandardowych-wyjątków)
    - [4. `server_types.php`: Niezmienne Modele Danych](#4-server_typesphp-niezmienne-modele-danych)
    - [5. `autoloader.php`: Automatyczny Ładowacz Klas](#5-autoloaderphp-automatyczny-ładowacz-klas)
    - [6. `logger.php`: Podsystem Logowania](#6-loggerphp-podsystem-logowania)
    - [7. `domain_resolver.php`: Rozwiązywanie Nazw Domen z Trwałym Buforem](#7-domain_resolverphp-rozwiązywanie-nazw-domen-z-trwałym-buforem)
    - [8. `socket_manager.php`: Solidny Menedżer Połączeń UDP](#8-socket_managerphp-solidny-menedżer-połączeń-udp)
    - [9. `packet_builder.php`: Konstruktor Pakietów Binarnych](#9-packet_builderphp-konstruktor-pakietów-binarnych)
    - [10. `packet_parser.php`: Dekoder Pakietów z Obsługą Kodowania](#10-packet_parserphp-dekoder-pakietów-z-obsługą-kodowania)
    - [11. `samp-query.php`: Klasa Główna (Kompletny Orkiestrator)](#11-samp-queryphp-klasa-główna-kompletny-orkiestrator)
      - [Cykl Życia Zapytania: Podróż Pakietu](#cykl-życia-zapytania-podróż-pakietu)
        - [1. Inicjalizacja i Rozwiązywanie Nazw Domen](#1-inicjalizacja-i-rozwiązywanie-nazw-domen)
        - [2. `Fetch_Server_State()`: Bufor i Krytyczne Zapytanie o INFO/PING](#2-fetch_server_state-bufor-i-krytyczne-zapytanie-o-infoping)
        - [3. `Attempt_Query()`: Zoptymalizowana Strategia Ponownych Prób](#3-attempt_query-zoptymalizowana-strategia-ponownych-prób)
        - [4. `Execute_Query_Phase()`: Silnik Komunikacji z Wykrywaniem Pingu](#4-execute_query_phase-silnik-komunikacji-z-wykrywaniem-pingu)
        - [5. `Validate_Response()`: Warstwa Walidacji Semantycznej](#5-validate_response-warstwa-walidacji-semantycznej)
      - [Obliczanie i Zarządzanie Adaptacyjnym Timeoutem](#obliczanie-i-zarządzanie-adaptacyjnym-timeoutem)
      - [Publiczne Metody Zapytań](#publiczne-metody-zapytań)
      - [Komunikacja RCON (`Send_Rcon`)](#komunikacja-rcon-send_rcon)
  - [Diagnostyka Błędów i Wyjątków](#diagnostyka-błędów-i-wyjątków)
    - [`Invalid_Argument_Exception`](#invalid_argument_exception)
    - [`Connection_Exception`](#connection_exception)
    - [`Malformed_Packet_Exception`](#malformed_packet_exception)
    - [`Rcon_Exception`](#rcon_exception)
  - [Licencja](#licencja)
    - [Warunki użytkowania](#warunki-użytkowania)
      - [1. Przyznane uprawnienia](#1-przyznane-uprawnienia)
      - [2. Obowiązkowe warunki](#2-obowiązkowe-warunki)
      - [3. Prawa autorskie](#3-prawa-autorskie)
      - [4. Wyłączenie gwarancji i ograniczenie odpowiedzialności](#4-wyłączenie-gwarancji-i-ograniczenie-odpowiedzialności)

## Przegląd

Biblioteka **SA-MP Query - PHP** to wysokowydajne i odporne na błędy rozwiązanie dla deweloperów PHP, którzy potrzebują interagować z serwerami gier opartymi na protokole SA-MP/OMP (UDP). Jej celem jest zamknięcie złożoności binarnego protokołu zapytań w czystym i intuicyjnym API PHP, umożliwiając aplikacjom internetowym, launcherom i narzędziom szybkie i niezawodne uzyskiwanie szczegółowych informacji o stanie serwera (gracze, reguły, ping itp.).

Projekt biblioteki skupia się na trzech głównych filarach: **Odporności**, **Wydajności** i **Modularności**. Jest zbudowana, aby radzić sobie z zawodną naturą protokołu UDP, implementując zaawansowany system prób i *backoff*, aby zapewnić uzyskanie informacji nawet w niekorzystnych warunkach sieciowych lub na serwerach o wysokiej latencji.

## Zasady Projektowania i Architektura

### Architektura Modułowa

Biblioteka jest podzielona na komponenty o pojedynczej odpowiedzialności, każdy zamknięty we własnej klasie i pliku.

- **Infrastruktura Sieciowa:** `Domain_Resolver`, `Socket_Manager`.
- **Protokół:** `Packet_Builder`, `Packet_Parser`, `Opcode` (Enum).
- **Logika Biznesowa (Orkiestracja):** `Samp_Query`.
- **Modele Danych:** `Server_Info`, `Players_Detailed`, itp.

### Odporność: Backoff, Ponowne Próby i Buforowanie (Caching)

Protokół UDP nie gwarantuje dostarczenia pakietów. Klasa `Samp_Query` łagodzi tę wadę za pomocą zaawansowanego cyklu zapytań.

- **Wielokrotne Próby Adaptacyjne:** Metoda `Attempt_Query` implementuje pętlę z maksymalnie `Query::ATTEMPTS` (domyślnie 5) próbami i podwójną ich liczbą dla zapytań krytycznych.
- **Strategia Backoff:** Wykładniczy *backoff* jest zaimplementowany w `Execute_Query_Phase`. Po pierwszym wysłaniu, interwał nowych prób nasłuchiwania (pętla `while`) rośnie od `Performance::INITIAL_RETRY_INTERVAL` (0.08s) o `Performance::BACKOFF_FACTOR` (1.3), aż do limitu 0.2s. Zapobiega to przeciążeniu pakietami i zwiększa szansę na odpowiedź w odpowiednim czasie.
- **Buforowanie Odpowiedzi:** Najnowsze odpowiedzi (ważne przez 2.0 sekundy) są przechowywane w `response_cache`, eliminując potrzebę powtarzania zapytań o metadane podczas wykonywania `Get_All()`.

### Optymalizacja Wydajności: Równoległość i Adaptacyjne Ustawianie Czasu Oczekiwania (Timeout)

- **Zapytania Równoległe (Fan-out):** Metoda `Get_All()` wysyła żądania `INFO`, `RULES` i `PLAYERS` jednocześnie (w `$jobs`), pozwalając na otrzymywanie odpowiedzi w dowolnej kolejności, co minimalizuje całkowity czas oczekiwania.
- **Trwałe Buforowanie DNS:** `Domain_Resolver` przechowuje rozwiązany adres IP w lokalnym pliku bufora z czasem życia (TTL) 3600 sekund, unikając opóźnień w rozwiązywaniu nazw domen w kolejnych wywołaniach.
- **Adaptacyjny Timeout:** Czas oczekiwania na odpowiedzi na duże zapytania (takie jak lista graczy) jest dynamicznie dostosowywany na podstawie `cached_ping` serwera:
   ```
   Dostosowany Timeout = Bazowy Timeout + (Zbuforowany Ping * Mnożnik Pingu / 1000)
   ```
   Ta logika (zaimplementowana w `Fetch_Player_Data`) zapewnia, że serwery o wysokiej latencji mają wystarczająco dużo czasu na odpowiedź, nie pogarszając szybkości działania na serwerach o niskiej latencji.

### Nowoczesne Programowanie Obiektowe (OOP) (PHP 8.1+)

Biblioteka wykorzystuje nowoczesne funkcje PHP, aby zapewnić bezpieczeństwo i przejrzystość:

- **Ścisłe Typowanie** (`declare(strict_types=1)`).
- **Właściwości Tylko do Odczytu** (`public readonly` w `Samp_Query` i w modelach danych) w celu zapewnienia niezmienności danych.
- **Typowane Enumy** (`enum Opcode: string`) dla bezpiecznej kontroli protokołu.
- **Promocja Właściwości w Konstruktorze** (w `Samp_Query::__construct` i modelach).

## Wymagania

- **PHP:** Wersja **8.1 lub wyższa**.
- **Rozszerzenia PHP:** `sockets` i `mbstring` (do obsługi kodowania UTF-8).

## Instalacja i Podstawowe Użycie

Aby zacząć używać biblioteki **SA-MP Query - PHP**, wystarczy dołączyć plik `samp-query.php` do swojego projektu. Ten plik zajmie się automatycznym ładowaniem wszystkich zależności za pomocą swojego wewnętrznego autoloadera.

```php
<?php
// Dołącz główną klasę. Zajmie się ona ładowaniem zależności poprzez autoloader.
require_once 'path/to/samp-query/samp-query.php'; 

// Użyj przestrzeni nazw głównej klasy
use Samp_Query\Samp_Query;
// Dołącz wyjątki dla bardziej szczegółowej obsługi błędów
use Samp_Query\Exceptions\Invalid_Argument_Exception;
use Samp_Query\Exceptions\Connection_Exception;
use Samp_Query\Exceptions\Rcon_Exception;

// Utwórz instancję klasy Samp_Query, opakowując ją w blok try-catch, aby obsłużyć błędy inicjalizacji.
try {
    $server_query = new Samp_Query("play.example-samp-server.com", 7777);
    // Teraz możesz używać publicznych metod $server_query
}
catch (Invalid_Argument_Exception $e) {
    echo "Błąd Argumentu: " . $e->getMessage() . "\n";
}
catch (Connection_Exception $e) {
    echo "Błąd Połączenia: " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Nieoczekiwany błąd podczas inicjalizacji: " . $e->getMessage() . "\n";
}
```

### Inicjalizacja Klasy `Samp_Query`

Klasa `Samp_Query` jest punktem wejścia do wszystkich funkcjonalności. Jej konstruktor wymaga `hostname` (lub adresu IP) i `port` serwera, który chcesz odpytać.

```php
/**
 * Inicjalizuje nową instancję biblioteki Samp_Query.
 *
 * @param string $hostname Nazwa hosta lub adres IP serwera SA-MP/OMP.
 * @param int $port Port UDP serwera (zazwyczaj 7777).
 * 
 * @throws Invalid_Argument_Exception Jeśli nazwa hosta jest pusta lub port jest nieprawidłowy.
 * @throws Connection_Exception Jeśli rozwiązywanie DNS nie powiodło się lub nie można utworzyć gniazda.
 */
public function __construct(public readonly string $hostname, public readonly int $port)
```

<br>

---

### `Get_All()`: Pełne i Zoptymalizowane Zapytanie

Jest to najbardziej kompleksowa i zalecana metoda. Wykonuje ona różne zapytania (INFO, RULES, PLAYERS) równolegle i w zoptymalizowany sposób, minimalizując całkowity czas odpowiedzi i zwracając pełną tablicę asocjacyjną ze wszystkimi dostępnymi informacjami.

```php
/**
 * Zwraca wszystkie dostępne informacje o serwerze w jednym zoptymalizowanym wywołaniu.
 * Zawiera: is_online, ping, info (Server_Info), rules (tablica Server_Rule),
 * players_detailed (tablica Players_Detailed), players_basic (tablica Players_Basic),
 * i execution_time_ms.
 *
 * @return array Tablica asocjacyjna zawierająca wszystkie informacje o serwerze.
 * 
 * @throws Connection_Exception Jeśli zapytanie INFO, kluczowe dla stanu serwera, nie powiedzie się.
 */
public function Get_All(): array
```

Przykład Użycia:

```php
<?php
// ... (inicjalizacja klasy $server_query) ...

try {
    $data = $server_query->Get_All();
    
    if ($data['is_online']) {
        echo "## Serwer Online: {$data['info']->hostname} ({$server_query->ip}:{$server_query->port}) ##\n";
        echo "Ping: {$data['ping']}ms | Całkowity Czas Zapytania: {$data['execution_time_ms']}ms\n";
        echo "Gracze: {$data['info']->players} / {$data['info']->max_players}\n";
        echo "Tryb Gry: {$data['info']->gamemode}\n";
        echo "Język: {$data['info']->language}\n";
        echo "Chroniony Hasłem: " . ($data['info']->password ? "Tak" : "Nie") . "\n\n";

        // Przykład szczegółowej listy graczy
        if (!empty($data['players_detailed'])) {
            echo "--- Szczegółowi Gracze ({$data['info']->players} Aktywnych) ---\n";

            foreach ($data['players_detailed'] as $player)
                echo "ID: {$player->id}, Nazwa: {$player->name}, Ping: {$player->ping}ms, Wynik: {$player->score}\n";
        }
        elseif (!empty($data['players_basic'])) {
            echo "--- Podstawowi Gracze ({$data['info']->players} Aktywnych) (Fallback) ---\n";

            foreach ($data['players_basic'] as $player)
                echo "Nazwa: {$player->name}, Wynik: {$player->score}\n";
        }
        else
            echo "Brak graczy online lub lista niedostępna (może za dużo graczy).\n";
        
        // Przykład reguł serwera
        if (!empty($data['rules'])) {
            echo "\n--- Reguły Serwera ---\n";

            foreach ($data['rules'] as $rule)
                echo "{$rule->name}: {$rule->value}\n";
        }
    }
    else
        echo "Serwer jest obecnie offline lub niedostępny.\n";
}
catch (Connection_Exception $e) {
    echo "Błąd połączenia podczas próby uzyskania wszystkich informacji: " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Nieoczekiwany błąd podczas odpytywania o wszystkie informacje: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Is_Online()`: Szybkie Sprawdzanie Stanu

Sprawdza, czy serwer jest online i odpowiada na zapytania, nie pobierając dodatkowych szczegółów. Idealne do prostego "liveness check".

```php
/**
 * Sprawdza, czy serwer jest online i dostępny.
 *
 * @return bool Zwraca true, jeśli serwer jest online i odpowiada z prawidłowymi informacjami INFO, w przeciwnym razie false.
 */
public function Is_Online(): bool
```

Przykład Użycia:

```php
<?php
// ... (inicjalizacja klasy $server_query) ...

if ($server_query->Is_Online())
    echo "Serwer SA-MP jest online i odpowiada!\n";
else
    echo "Serwer SA-MP jest offline lub nie odpowiedział na czas.\n";
```

<br>

---

### `Get_Ping()`: Pobieranie Pingu Serwera

Zwraca czas opóźnienia (ping) serwera w milisekundach. Ta wartość jest buforowana wewnętrznie w celu optymalizacji.

```php
/**
 * Zwraca aktualny ping serwera w milisekundach.
 * Jeśli ping nie został jeszcze obliczony, zostanie wykonane szybkie zapytanie PING.
 *
 * @return int|null Wartość pingu w milisekundach lub null, jeśli nie można go uzyskać.
 */
public function Get_Ping(): ?int
```

Przykład Użycia:

```php
<?php
// ... (inicjalizacja klasy $server_query) ...

try {
    $ping = $server_query->Get_Ping();

    if ($ping !== null)
        echo "Ping serwera wynosi: {$ping}ms.\n";
    else
        echo "Nie można uzyskać pingu serwera.\n";
}
catch (Connection_Exception $e) {
    echo "Błąd podczas uzyskiwania pingu: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Info()`: Podstawowe Szczegóły Serwera

Pobiera podstawowe informacje o serwerze, takie jak nazwa hosta, tryb gry, liczba graczy itp. Zwraca obiekt `Server_Info`.

```php
/**
 * Zwraca podstawowe szczegóły serwera (nazwa hosta, gracze, gamemode itp.).
 * Dane są buforowane przez krótki okres w celu optymalizacji.
 *
 * @return Server_Info|null Obiekt Server_Info lub null, jeśli nie można uzyskać informacji.
 */
public function Get_Info(): ?Server_Info
```

Przykład Użycia:

```php
<?php
use Samp_Query\Models\Server_Info;
// ... (inicjalizacja klasy $server_query) ...

try {
    /** @var Server_Info|null $info */
    $info = $server_query->Get_Info();

    if ($info) {
        echo "--- Informacje o Serwerze ---\n";
        echo "Nazwa Hosta: {$info->hostname}\n";
        echo "Tryb Gry: {$info->gamemode}\n";
        echo "Gracze: {$info->players} / {$info->max_players}\n";
        echo "Język: {$info->language}\n";
        echo "Chroniony Hasłem: " . ($info->password ? "Tak" : "Nie") . "\n";
    }
    else
        echo "Nie można uzyskać informacji o serwerze.\n";
}
catch (Connection_Exception $e) {
    echo "Błąd podczas uzyskiwania informacji o serwerze: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Rules()`: Skonfigurowane Reguły Serwera

Pobiera wszystkie reguły skonfigurowane na serwerze, takie jak `mapname`, `weburl`, `weather` itp., zwracając je jako tablicę obiektów `Server_Rule`.

```php
/**
 * Zwraca tablicę obiektów Server_Rule, z których każdy zawiera nazwę i wartość reguły serwera.
 * Dane są buforowane w celu optymalizacji.
 *
 * @return array Tablica Samp_Query\Models\Server_Rule. Może być pusta, jeśli nie ma reguł.
 */
public function Get_Rules(): array
```

Przykład Użycia:

```php
<?php
use Samp_Query\Models\Server_Rule;
// ... (inicjalizacja klasy $server_query) ...

try {
    $rules = $server_query->Get_Rules();

    if (!empty($rules)) {
        echo "--- Reguły Serwera ---\n";

        foreach ($rules as $rule)
            echo "{$rule->name}: {$rule->value}\n";

        // Przykład dostępu do konkretnej reguły:
        $mapname_rule = array_filter($rules, fn(Server_Rule $r) => $r->name === 'mapname');

        if (!empty($mapname_rule))
            echo "\nAktualna mapa: " . reset($mapname_rule)->value . "\n";
    }
    else
        echo "Nie znaleziono żadnych reguł dla tego serwera.\n";
}
catch (Connection_Exception $e) {
    echo "Błąd podczas uzyskiwania reguł serwera: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Players_Detailed()`: Lista Graczy ze Szczegółami

Pobiera szczegółową listę graczy aktualnie online, w tym ID, nazwę, wynik i ping.

> [!CAUTION]
> Aby zoptymalizować wydajność i uniknąć nadmiernie dużych pakietów UDP, które mogą zostać utracone lub pofragmentowane, ta metoda nie pobierze szczegółowej listy graczy, jeśli całkowita liczba graczy jest równa lub większa niż `Query::LARGE_PLAYER_THRESHOLD` (domyślnie 150). W takich przypadkach zostanie zwrócona pusta tablica. Rozważ użycie `Get_Players_Basic()` jako alternatywy.

```php
/**
 * Zwraca tablicę obiektów Players_Detailed (ID, nazwa, wynik, ping) dla każdego gracza online.
 * To zapytanie może zostać zignorowane, jeśli liczba graczy jest zbyt duża (zobacz Query::LARGE_PLAYER_THRESHOLD).
 *
 * @return array Tablica Samp_Query\Models\Players_Detailed. Może być pusta.
 */
public function Get_Players_Detailed(): array
```

Przykład Użycia:

```php
<?php
use Samp_Query\Models\Players_Detailed;
// ... (inicjalizacja klasy $server_query) ...

try {
    $players_detailed = $server_query->Get_Players_Detailed();

    if (!empty($players_detailed)) {
        echo "--- Gracze Online (Szczegółowo) ---\n";

        foreach ($players_detailed as $player)
            echo "ID: {$player->id}, Nazwa: {$player->name}, Wynik: {$player->score}, Ping: {$player->ping}ms\n";
    }
    else
        echo "Brak graczy online lub szczegółowa lista niedostępna.\n";
}
catch (Connection_Exception $e) {
    echo "Błąd podczas uzyskiwania szczegółowej listy graczy: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Players_Basic()`: Podstawowa Lista Graczy

Dostarcza lżejszą listę graczy, zawierającą tylko nazwę i wynik. Przydatne jako alternatywa, gdy szczegółowa lista nie jest dostępna, lub w celu zmniejszenia ilości przesyłanych danych.

```php
/**
 * Zwraca tablicę obiektów Players_Basic (nazwa, wynik) dla każdego gracza online.
 * Przydatne jako lżejsza alternatywa lub fallback, gdy Get_Players_Detailed() nie jest wykonalne.
 *
 * @return array Tablica Samp_Query\Models\Players_Basic. Może być pusta.
 */
public function Get_Players_Basic(): array
```

Przykład Użycia:

```php
<?php
use Samp_Query\Models\Players_Basic;
// ... (inicjalizacja klasy $server_query) ...

try {
    $players_basic = $server_query->Get_Players_Basic();

    if (!empty($players_basic)) {
        echo "--- Gracze Online (Podstawowo) ---\n";

        foreach ($players_basic as $player)
            echo "Nazwa: {$player->name}, Wynik: {$player->score}\n";
    }
    else
        echo "Brak graczy online lub podstawowa lista niedostępna.\n";
}
catch (Connection_Exception $e) {
    echo "Błąd podczas uzyskiwania podstawowej listy graczy: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Send_Rcon()`: Wysyłanie Zdalnych Poleceń

Umożliwia wysyłanie poleceń do konsoli RCON serwera, takich jak zmiana reguł, banowanie graczy itp. Wymaga hasła RCON serwera.

> [!WARNING]
> Funkcja RCON jest wrażliwa i może powodować zmiany na serwerze. Używaj z ostrożnością i tylko z zaufanymi hasłami.
> Kluczowe jest, aby hasło RCON było **prawidłowe** i aby RCON był **włączony** na serwerze (konfiguracja `rcon_password` w `server.cfg`).

```php
/**
 * Wysyła polecenie RCON na serwer.
 * Przeprowadza uwierzytelnianie za pomocą 'varlist' i wysyła polecenie.
 *
 * @param string $rcon_password Hasło RCON serwera.
 * @param string $command Polecenie do wykonania (np. "gmx", "kick ID").
 * @return string Odpowiedź serwera na polecenie RCON lub komunikat o stanie.
 * 
 * @throws Invalid_Argument_Exception Jeśli hasło lub polecenie RCON są puste.
 * @throws Rcon_Exception Jeśli uwierzytelnianie RCON nie powiedzie się lub polecenie nie otrzyma odpowiedzi.
 * @throws Connection_Exception W przypadku błędu połączenia podczas operacji RCON.
 */
public function Send_Rcon(string $rcon_password, string $command): string
```

Przykład Użycia:

```php
<?php
// ... (inicjalizacja klasy $server_query) ...

$rcon_password = "twoje_sekretne_haslo_tutaj"; 
$command_to_send = "gmx"; // Przykład: restart trybu gry

try {
    echo "Próba wysłania polecenia RCON: '{$command_to_send}'...\n";
    $response = $server_query->Send_Rcon($rcon_password, $command_to_send);
    echo "Odpowiedź RCON: {$response}\n";

    // Przykład polecenia "say" na serwerze (wymaga RCON)
    $response_say = $server_query->Send_Rcon($rcon_password, "say Cześć z mojego skryptu PHP!");
    echo "Odpowiedź RCON (say): {$response_say}\n";
}
catch (Invalid_Argument_Exception $e) {
    echo "Błąd RCON (Nieprawidłowy Argument): " . $e->getMessage() . "\n";
}
catch (Rcon_Exception $e) {
    echo "Błąd RCON: Błąd uwierzytelniania lub polecenie nie zostało wykonane. " . $e->getMessage() . "\n";
}
catch (Connection_Exception $e) {
    echo "Błąd RCON (Połączenie): " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Nieoczekiwany błąd podczas wysyłania RCON: " . $e->getMessage() . "\n";
}
```

## Szczegółowa Struktura Biblioteki i Przebieg Wykonania

Biblioteka **SA-MP Query - PHP** jest starannie zorganizowana w kilka plików, z których każdy ma dobrze zdefiniowaną odpowiedzialność. Ta sekcja szczegółowo omawia każdy komponent, ujawniając decyzje projektowe i leżącą u ich podstaw logikę.

### 1. `constants.php`: Serce Konfiguracji

Ten plik centralizuje wszystkie "magiczne" parametry konfiguracyjne biblioteki, zapewniając, że aspekty takie jak *timeouts*, liczba prób i rozmiary buforów są łatwo regulowane i spójne w całym projekcie.

```php
// ...
final class Query {
    public const ATTEMPTS = 5; // Maksymalna liczba prób zapytania
    public const LARGE_PLAYER_THRESHOLD = 150; // Limit graczy dla szczegółowego zapytania
}

final class Protocol {
    public const UDP_READ_BUFFER_SIZE = 32768; // 32KB dla bufora odczytu
    public const KERNEL_RECEIVE_BUFFER_SIZE = 4194304; // 4MB dla bufora jądra
}
// ...
```

- **Finalne Klasy i Stałe:** Klasy są `final`, a właściwości `public const`, co zapewnia niezmienność i globalną dostępność w czasie kompilacji.
- **Granularność i Semantyka:** Stałe są podzielone na kategorie według ich domeny (`Query`, `Protocol`, `Performance`, `Logger`, `DNS`), co ułatwia zrozumienie i konserwację. Na przykład, `Query::LARGE_PLAYER_THRESHOLD` definiuje punkt, w którym można uniknąć wyszukiwania szczegółowych list graczy w celu optymalizacji, ze względu na dużą ilość danych i potencjalne *timeouts*.

### 2. `opcode.php`: Enum Opcode'ów Protokołu

Ten plik definiuje kody operacji (opcodes) używane do różnych zapytań do serwera SA-MP/OMP, zamykając je w typowanym `enum`.

```php
// ...
enum Opcode: string {
    case INFO = 'i';
    case RULES = 'r';
    case PLAYERS_DETAILED = 'd';
    // ...
}
```

- **Typowany `Enum` (PHP 8.1+):** Użycie `enum` (`Opcode: string`) z wartościami typu `string` gwarantuje, że opcody są zawsze prawidłowe, a kod jest semantycznie jasny. Zastępuje to użycie "magicznych" literałów stringowych, czyniąc kod bardziej czytelnym i mniej podatnym na błędy literowe.

### 3. `exceptions.php`: Hierarchia Niestandardowych Wyjątków

Ten plik ustanawia hierarchię niestandardowych wyjątków, umożliwiając bardziej granularne i specyficzne traktowanie błędów dla różnych typów awarii, które mogą wystąpić w bibliotece.

```php
// ...
class Query_Exception extends \Exception {}
class Invalid_Argument_Exception extends Query_Exception {}
class Connection_Exception extends Query_Exception {}
// ...
```

- **Dziedziczenie po `\Exception`:** Wszystkie wyjątki dziedziczą po `Query_Exception` (który z kolei rozszerza `\Exception`), co pozwala na przechwytywanie grup błędów (`Connection_Exception` i `Timeout_Exception` są bardziej specyficzne niż `Query_Exception`) lub wszystkich wyjątków biblioteki za pomocą bardziej ogólnego bloku `catch`.
- **Jasność w Diagnostyce:** Opisowe nazwy wyjątków ułatwiają diagnozowanie i odzyskiwanie po błędach w aplikacji klienckiej.

### 4. `server_types.php`: Niezmienne Modele Danych

Ten plik definiuje klasy, które reprezentują modele danych dla informacji zwracanych przez serwer, zapewniając integralność danych poprzez niezmienność.

```php
// ...
final class Server_Info {
    public function __construct(
        public readonly bool $password,
        public readonly int $players,
        // ... inne właściwości readonly ...
    ) {}
}
// ...
```

- **`final class`:** Klasy są `final`, co zapobiega ich rozszerzaniu i gwarantuje ich strukturę i zachowanie.
- **`public readonly` Properties (PHP 8.1+):** Wszystkie właściwości są zadeklarowane jako `public readonly`. Oznacza to, że po utworzeniu obiektu jego wartości nie mogą być zmienione, co gwarantuje integralność danych otrzymanych z serwera.
- **Constructor Property Promotion (PHP 8.1+):** Właściwości są deklarowane bezpośrednio w konstruktorze, co upraszcza kod i redukuje *boilerplate*.

### 5. `autoloader.php`: Automatyczny Ładowacz Klas

Ten plik jest odpowiedzialny za dynamiczne ładowanie klas biblioteki, gdy są one potrzebne, zgodnie ze standardem PSR-4.

```php
// ...
spl_autoload_register(function ($class) {
    $prefix = 'Samp_Query\\';
    $base_dir = __DIR__ . '/../modules/'; // Mapuje przestrzeń nazw na katalog
    // ... logika ładowania ...
});

// Dołącza kluczowe pliki, które nie są klasami lub wymagają wcześniejszego załadowania
require_once __DIR__ . '/../modules/constants.php';
require_once __DIR__ . '/../modules/exceptions.php';
// ...
```

- **`spl_autoload_register()`:** Rejestruje anonimową funkcję, która jest automatycznie wywoływana przez PHP, gdy odwołujemy się do niezdefiniowanej klasy, przyspieszając rozwój i konserwację.
- **Bezpośrednie Dołączanie Konfiguracji:** Pliki takie jak `constants.php` i `exceptions.php` są dołączane bezpośrednio. Gwarantuje to, że ich definicje są dostępne, zanim jakakolwiek klasa, która ich używa, zostanie zainicjowana przez autoloader.

### 6. `logger.php`: Podsystem Logowania

Klasa `Logger` zapewnia prosty mechanizm do rejestrowania komunikatów o błędach i ważnych zdarzeń w pliku logu, co jest przydatne do debugowania i monitorowania.

```php
// ...
final class Logger {
    public function Log(string $message): void {
        if (is_file(Logger_Config::FILE) && filesize(Logger_Config::FILE) > Logger_Config::MAX_SIZE_BYTES)
            unlink(Logger_Config::FILE); // Usuwa log, jeśli przekroczy rozmiar

        $timestamp = date('Y-m-d H:i:s');
        $log_entry = "[{$timestamp}] {$message}" . PHP_EOL;
        
        file_put_contents(Logger_Config::FILE, $log_entry, FILE_APPEND | LOCK_EX); // Dodaje z blokadą
    }
}
```

- **Automatyczne Czyszczenie:** Logger sprawdza rozmiar pliku logu (`Logger_Config::FILE`). Jeśli przekroczy on `Logger_Config::MAX_SIZE_BYTES` (domyślnie 10 MB), plik jest usuwany, aby zapobiec jego niekontrolowanemu wzrostowi.
- **Blokada Pliku (`LOCK_EX`):** `file_put_contents` używa `LOCK_EX`, aby zapewnić, że tylko jeden proces zapisuje do pliku logu w danym momencie, zapobiegając uszkodzeniu w środowiskach wielowątkowych/wieloprocesowych.

### 7. `domain_resolver.php`: Rozwiązywanie Nazw Domen z Trwałym Buforem

Klasa `Domain_Resolver` jest odpowiedzialna za konwersję nazw hostów (takich jak `play.example.com`) na adresy IP (takie jak `192.0.2.1`). Implementuje system buforowania na dysku w celu optymalizacji wydajności.

```php
// ...
final class Domain_Resolver {
    public function Resolve(string $hostname): string {
        if (filter_var($hostname, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
            return $hostname; // Już jest adresem IP

        $cache_file = DNS_Config::CACHE_DIR . md5($hostname) . '.cache';

        if (file_exists($cache_file) && (time() - filemtime($cache_file)) < DNS_Config::CACHE_TTL_SECONDS) {
            $ip = file_get_contents($cache_file);

            if ($ip && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
                return $ip;
        }
        
        $ip = gethostbyname($hostname); // Rzeczywiste rozwiązywanie DNS
        // ... logika walidacji i zapisu w buforze ...

        return $ip;
    }
    // ...
}
```

- **Trwały Bufor:** Przed wywołaniem `gethostbyname()`, sprawdza, czy IP jest już przechowywane w pliku bufora (`dns/` + hash MD5 nazwy hosta). Bufor jest uważany za ważny, jeśli nie przekroczył `DNS_Config::CACHE_TTL_SECONDS` (domyślnie 3600 sekund, czyli 1 godzina).
- **Solidna Walidacja:** Rozwiązany (lub odczytany z bufora) adres IP jest walidowany za pomocą `filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)`, aby upewnić się, że jest to prawidłowy adres IPv4. Jeśli rozwiązywanie nie powiedzie się, rzucany jest wyjątek `Query_Exception`.

### 8. `socket_manager.php`: Solidny Menedżer Połączeń UDP

Klasa `Socket_Manager` zamyka w sobie złożoność tworzenia, konfigurowania i zarządzania gniazdem UDP do komunikacji z serwerem gry.

```php
// ...
final class Socket_Manager {
    private $socket = null;
    // ...
    private function Connect(): void {
        if (is_resource($this->socket))
            return;

        $context = stream_context_create([
            'socket' => ['so_rcvbuf' => Protocol::KERNEL_RECEIVE_BUFFER_SIZE] // Zwiększa bufor do 4MB
        ]);

        $this->socket = stream_socket_client(
            "udp://{$this->host}:{$this->port}", 
            $err_no, $err_str, 
            (float)ini_get("default_socket_timeout"), 
            STREAM_CLIENT_CONNECT, // Łączy gniazdo ze zdalnym adresem
            $context
        );

        if ($this->socket === false)
            throw new Connection_Exception("Could not create socket...");
    }
    // ...
}
```

- **`stream_socket_client` z `STREAM_CLIENT_CONNECT`:** Ta flaga instruuje system operacyjny, aby "połączył" gniazdo UDP ze zdalnym adresem. Chociaż UDP jest protokołem bezpołączeniowym, "połączenie" gniazda pozwala na optymalizacje wydajności na poziomie jądra, takie jak brak konieczności określania zdalnego adresu w każdym wywołaniu `fwrite` lub `stream_socket_recvfrom`, co skutkuje mniejszym narzutem.
- **Bufor Odbioru Jądra:** `stream_context_create` jest używany do zwiększenia rozmiaru bufora odbioru jądra (`so_rcvbuf`) do `Protocol::KERNEL_RECEIVE_BUFFER_SIZE` (4MB). Jest to kluczowe, aby uniknąć utraty pakietów (przepełnienie bufora) podczas odbierania dużych odpowiedzi, takich jak szczegółowe listy graczy z obciążonych serwerów.
- **RAII przez `__destruct`:** Metoda `Disconnect()` jest wywoływana automatycznie w destruktorze (`__destruct`), zapewniając, że gniazdo jest zamykane, a zasoby zwalniane, nawet w przypadku wyjątków.
- **Dynamiczny Timeout:** `Set_Timeout` precyzyjnie dostosowuje timeouty odczytu/zapisu gniazda za pomocą `stream_set_timeout`, co jest fundamentalne dla logiki ponownych prób i *backoff*.

### 9. `packet_builder.php`: Konstruktor Pakietów Binarnych

Klasa `Packet_Builder` jest odpowiedzialna za serializację danych zapytania do specyficznego formatu binarnego, który serwer SA-MP/OMP może zrozumieć.

```php
// ...
final class Packet_Builder {
    // ...
    private function Build_Header(): string {
        $packet = 'SAMP';
        $packet .= pack('c4', ...$this->ip_parts); // IP w 4 bajtach
        $packet .= pack('v', $this->port); // Port w 2 bajtach (little-endian)

        return $packet;
    }
    
    public function Build(string $opcode): string {
        $packet = $this->Build_Header();
        $packet .= $opcode;

        if ($opcode === Opcode::PING->value)
            $packet .= random_bytes(4); // Losowy ładunek dla PING
        
        return $packet;
    }
    // ...
}
```

- **`pack()` do Formatu Binarnego:** Używa funkcji `pack()` PHP do konwersji danych (IP, port, długości stringów) do ich prawidłowego formatu binarnego, takiego jak `c4` dla 4 bajtów znaków (IP) i `v` dla 16-bitowej liczby całkowitej bez znaku (port i długości), która jest w formacie *little-endian*.
- **Standardowy Nagłówek:** `Build_Header()` tworzy 10-bajtowy nagłówek 'SAMP', który poprzedza wszystkie pakiety.
- **Struktura RCON:** `Build_Rcon` formatuje pakiet RCON z opcode'em 'x', po którym następuje długość hasła, hasło, długość polecenia i samo polecenie.

### 10. `packet_parser.php`: Dekoder Pakietów z Obsługą Kodowania

Klasa `Packet_Parser` jest odpowiednikiem `Packet_Builder`, odpowiedzialnym za interpretację binarnych odpowiedzi otrzymanych z serwera i konwersję ich na ustrukturyzowane dane PHP.

```php
// ...
final class Packet_Parser {
    private int $offset = Protocol::MIN_PACKET_LENGTH; // Zaczyna po nagłówku (11 bajtów)
    // ...
    public function __construct(private readonly string $data) {
        // Wstępna walidacja nagłówka 'SAMP'
        if (substr($this->data, 0, 4) !== 'SAMP' || strlen($this->data) < Protocol::MIN_PACKET_LENGTH)
            throw new Malformed_Packet_Exception("Invalid response header or packet too short.");
    }
    
    private function Extract_String(int $length_bytes): string {
        // ... logika do odczytu długości i stringa ...
        $string = substr($this->data, $this->offset, $length);
        $this->offset += $length;
        
        // **KRYTYCZNA KONWERSJA KODOWANIA:** Serwery SA-MP/OMP używają Windows-1252
        return mb_convert_encoding($string, 'UTF-8', 'Windows-1252');
    }
    // ...
}
```

- **`offset` i `data_length`:** `offset` jest używany do śledzenia bieżącej pozycji podczas odczytu pakietu, podczas gdy `data_length` zapobiega odczytom poza granicami bufora.
- **Walidacja Nagłówka:** Konstruktor waliduje "magic string" `SAMP`, aby natychmiast odrzucić źle sformułowane pakiety.
- **`Extract_String()` - Kluczowa Konwersja Kodowania:** Jest to jedna z najważniejszych funkcjonalności. Protokół SA-MP przesyła stringi używając kodowania **Windows-1252**. Aby zapewnić, że znaki specjalne (takie jak akcenty czy cyrylica) są poprawnie wyświetlane w aplikacjach PHP (które zazwyczaj działają w UTF-8), stosowana jest metoda `mb_convert_encoding($string, 'UTF-8', 'Windows-1252')`.
- **Ekstrakcja Zmiennej Długości:** Metoda `Extract_String` obsługuje różne rozmiary prefiksów długości dla stringów (1, 2 lub 4 bajty), co czyni ją elastyczną dla różnych pól protokołu.

### 11. `samp-query.php`: Klasa Główna (Kompletny Orkiestrator)

Klasa `Samp_Query` jest głównym punktem wejścia i orkiestratorem wszystkich operacji. Łączy ona wszystkie komponenty, zarządza stanem zapytania, logiką ponownych prób i *timeoutami*.

#### Cykl Życia Zapytania: Podróż Pakietu

Cały proces odpytywania serwera przebiega według starannie zorkiestrowanej sekwencji kroków, mających na celu maksymalną odporność i wydajność.

##### 1. Inicjalizacja i Rozwiązywanie Nazw Domen

Gdy tworzona jest instancja `Samp_Query`:
- **Szybka Walidacja:** Konstruktor waliduje parametry `$hostname` i `$port`. Jakakolwiek niespójność skutkuje rzuceniem `Invalid_Argument_Exception`.
- **Czyszczenie Bufora DNS:** Wywoływane jest `Domain_Resolver::Clean_Expired_Cache()`, aby zapewnić, że brane są pod uwagę tylko ważne i niewygasłe wpisy DNS.
- **Rozwiązywanie IP:** `Domain_Resolver` jest używany do konwersji `$hostname` na adres IP (`$this->ip`). Ten IP jest buforowany na dysku dla przyszłych żądań, a w przypadku niepowodzenia rzucany jest wyjątek `Query_Exception`.
- **Konfiguracja Zasobów:** Inicjowane są `Logger`, `Socket_Manager` i `Packet_Builder`, przygotowując infrastrukturę do komunikacji.

##### 2. `Fetch_Server_State()`: Bufor i Krytyczne Zapytanie o INFO/PING

Ta wewnętrzna metoda jest strażnikiem wydajności i spójności, zapewniając, że podstawowe informacje o serwerze (`Server_Info`) i `ping` są zawsze aktualne przed każdym głównym zapytaniem.

- **Główny Bufor (5 Sekund):** Przed rozpoczęciem jakiejkolwiek komunikacji, sprawdza się, czy `$this->cached_info` (obiekt `Server_Info` serwera) zawiera dane młodsze niż 5 sekund (w porównaniu do `$this->last_successful_query`). Jeśli dane są świeże, funkcja natychmiast kończy działanie, unikając niepotrzebnego ruchu sieciowego.
- **Krytyczne Zapytanie o INFO:** Jeśli bufor jest przestarzały lub pusty, wywoływana jest metoda `Attempt_Query` w celu uzyskania danych `INFO`. To zapytanie jest oznaczone jako **krytyczne** (`true`), co uruchamia większą liczbę prób i bardziej hojne *timeouty*. W przypadku nieprawidłowej odpowiedzi INFO po wszystkich próbach rzucany jest wyjątek `Connection_Exception`.
- **Obliczanie Pingu:** Jeśli `$this->cached_ping` jest wciąż `null`, wykonywane jest szybkie zapytanie `PING` (`Execute_Query_Phase` z `Performance::FAST_PING_TIMEOUT`). Ping jest obliczany jako czas, który upłynął do otrzymania **pierwszej** odpowiedzi, co zapewnia precyzję.

##### 3. `Attempt_Query()`: Zoptymalizowana Strategia Ponownych Prób

To jest mózg odporności biblioteki, zarządzający cyklem prób na wysokim poziomie dla jednego lub więcej `$jobs` (zapytań o opcody).

- **Bufor Odpowiedzi (2 Sekundy):** Najpierw sprawdza, czy odpowiedzi na którekolwiek z `$jobs` znajdują się już w `$this->response_cache` (młodsze niż 2.0 sekundy). Zapobiega to niepotrzebnym ponownym próbom dla danych ulotnych, ale nie krytycznych.
- **Faza Szybkich Ponownych Prób:** Biblioteka najpierw próbuje `Query::FAST_RETRY_ATTEMPTS` (domyślnie 2) z krótszym *timeoutem* (`$timeout * 0.6`). Celem jest uzyskanie odpowiedzi tak szybko, jak to możliwe, bez wprowadzania znaczących opóźnień.
- **Faza Standardowych Ponownych Prób z Backoff:** Jeśli faza szybka nie wystarczy, cykl kontynuuje z pozostałymi `Query::ATTEMPTS`. W tej fazie `$adjusted_timeout` stopniowo wzrasta z każdą próbą, dając serwerowi więcej czasu na odpowiedź. Co ważniejsze, `usleep()` wprowadza rosnące opóźnienie (oparte na `Query::RETRY_DELAY_MS` i współczynniku wzrostu) między wywołaniami `Execute_Query_Phase`, pozwalając sieci i serwerowi na ustabilizowanie się.
- **Ponowne Próby Awaryjne (dla Zapytań Krytycznych):** Dla `$jobs` oznaczonych jako `critical`, jeśli wszystkie poprzednie próby zawiodą, wykonywana jest ostatnia próba dla każdego zadania indywidualnie, z jeszcze większym *timeoutem* (`$timeout * 2`). Jest to ostatnia szansa na uzyskanie kluczowych informacji.

##### 4. `Execute_Query_Phase()`: Silnik Komunikacji z Wykrywaniem Pingu

Ta niskopoziomowa metoda jest miejscem, gdzie odbywa się rzeczywista interakcja z gniazdem UDP. Zarządza wysyłaniem i odbieraniem pakietów dla grupy `$jobs` jednocześnie w jednej fazie sieciowej.

```php
// ... (wewnątrz Execute_Query_Phase)
private function Execute_Query_Phase(array $jobs, ?int &$ping, float $timeout): array {
    $socket = $this->socket_manager->Get_Socket_Resource($timeout);
    stream_set_blocking($socket, false); // Gniazdo nieblokujące

    // Wysyła pakiety dwukrotnie natychmiast dla większej gwarancji dostarczenia UDP
    for ($i = 0; $i < 2; $i++) {
        foreach ($pending_jobs as $opcode)
            fwrite($socket, $this->packet_builder->Build($opcode->value));

        if ($i == 0)
            usleep(5000); // Małe opóźnienie
    }

    $last_send_time = microtime(true);
    $current_retry_interval = Performance::INITIAL_RETRY_INTERVAL; // 0.08s
    
    while (!empty($pending_jobs) && (microtime(true) - $start_time) < $timeout) {
        $now = microtime(true);

        if (($now - $last_send_time) > $current_retry_interval) { // Logika ponownego wysyłania z backoff
            // ... ponowne wysyłanie oczekujących pakietów ...
            $current_retry_interval = min($current_retry_interval * Performance::BACKOFF_FACTOR, 0.2); // Zwiększa interwał ponownych prób
        }

        $read = [$socket]; $write = $except = null

        if (stream_select($read, $write, $except, 0, 10000) > 0) { // Czeka na dane (maks. 10ms)
            $response = stream_socket_recvfrom($socket, Protocol::UDP_READ_BUFFER_SIZE);
            // ... logika parsowania, obliczania pingu i walidacji ...
        }

        if (!empty($pending_jobs))
            usleep(1000); // Małe opóźnienie, aby uniknąć obciążania CPU
    }
    return $phase_results;
}
```

- **Gniazdo Nieblokujące:** `stream_set_blocking($socket, false)` jest kluczowe. Pozwala PHP wysyłać pakiety, a następnie czekać na odpowiedzi bez blokowania wykonania, używając `stream_select`.
- **Podwójne Wysłanie dla Solidności:** Pakiety dla wszystkich `$pending_jobs` są wysyłane **dwukrotnie** z rzędu (z małym `usleep(5000)` pomiędzy) na początku fazy. Ta praktyka jest fundamentalna w protokołach UDP, aby znacznie zwiększyć prawdopodobieństwo dostarczenia w niestabilnych sieciach lub z utratą pakietów, łagodząc zawodną naturę UDP. Dla `INFO` i `PING`, trzecie dodatkowe wysłanie jest wykonywane podczas ponownych prób w głównej pętli.
- **Pętla Odbioru z Adaptacyjnym Backoff:**
   - Główna pętla `while` trwa, dopóki wszystkie `$jobs` nie zostaną zakończone lub nie upłynie *timeout* fazy.
   - **Dynamiczne Ponowne Wysyłanie:** Jeśli czas, który upłynął od ostatniego wysłania (`$now - $last_send_time`), przekroczy `$current_retry_interval`, pakiety dla `$pending_jobs` są wysyłane ponownie. Następnie `$current_retry_interval` jest zwiększany (`min($current_retry_interval * Performance::BACKOFF_FACTOR, 0.2)`), implementując wykładniczy *backoff*, który unika przeciążania serwera i maksymalizuje szanse na odpowiedź.
   - **Zoptymalizowany `stream_select`:** `stream_select($read, $write, $except, 0, 10000)` jest używany do oczekiwania na dane przez maksymalnie 10 milisekund. Pozwala to bibliotece być responsywną i przetwarzać pakiety, gdy tylko nadejdą.
   - **Precyzyjny Pomiar Pingu:** Gdy otrzymany jest **pierwszy** prawidłowy pakiet (`$packets_received === 0`), `ping` jest obliczany z dużą precyzją jako różnica między `microtime(true)` na początku wysyłania pierwszej partii pakietów a dokładnym czasem otrzymania **pierwszego** prawidłowego pakietu.
- **Przetwarzanie i Walidacja Odpowiedzi:** Otrzymane odpowiedzi są dekodowane przez `Packet_Parser`. Jeśli wykryty zostanie pakiet z `Malformed_Packet_Exception`, jest on logowany, a pakiet jest natychmiast wysyłany ponownie do serwera w celu ponownej próby. Zdekodowana odpowiedź jest następnie walidowana przez `Validate_Response()`. Jeśli jest prawidłowa, jest dodawana do `$phase_results` i do `$this->response_cache`.

##### 5. `Validate_Response()`: Warstwa Walidacji Semantycznej

Ta kluczowa metoda, zaimplementowana w klasie `Samp_Query`, weryfikuje integralność i logiczną spójność zdekodowanych danych, zanim zostaną one dostarczone użytkownikowi.

```php
// ... (wewnątrz Validate_Response)
private function Validate_Response($data, Opcode $opcode): bool {
    switch ($opcode) {
        case Opcode::INFO:
            // Gwarantuje, że nazwa hosta nie jest pusta i że liczby graczy są logiczne
            return $data instanceof Server_Info && $data->max_players > 0 && $data->players >= 0 && $data->players <= $data->max_players && !empty($data->hostname);
        
        case Opcode::RULES:
            if (!is_array($data))
                return false;

            // ...

            return true;
        // ... walidacje dla PLAYERS_DETAILED, PLAYERS_BASIC, PING ...
    }
}
```

- **Walidacja według Opcode:** Każdy `Opcode` ma swoją specyficzną logikę walidacji. Na przykład:
   - Dla `Opcode::INFO`: Gwarantuje, że `$data` jest instancją `Server_Info`, że `$data->max_players > 0`, `$data->players` jest między 0 a `max_players`, oraz że `$data->hostname` nie jest pusty.
   - Dla `Opcode::RULES` lub list graczy: Sprawdza, czy zwrot jest tablicą `array`, a jeśli nie jest pusta, czy pierwszy element jest oczekiwaną instancją modelu (`Server_Rule`, `Players_Detailed` itp.).
- **Odporność:** Jeśli walidacja nie powiedzie się, odpowiedź jest uważana za nieprawidłową i jest odrzucana. To zmusza system do kontynuowania prób, tak jakby pakiet nigdy nie dotarł, chroniąc aplikację przed uszkodzonymi lub niespójnymi danymi z serwera.

#### Obliczanie i Zarządzanie Adaptacyjnym Timeoutem

Biblioteka implementuje zaawansowaną strategię *timeoutu*, aby zrównoważyć szybkość i odporność:

- **`Performance::METADATA_TIMEOUT`:** (0.8 sekundy) Jest to bazowy *timeout* dla szybkich zapytań, takich jak `INFO` i `RULES`.
- **`Performance::PLAYER_LIST_BASE_TIMEOUT`:** (1.0 sekundy) Jest to bazowy *timeout* dla zapytań o listę graczy.
- **`Performance::PING_MULTIPLIER`:** (2) Używany do dostosowywania *timeoutu* na podstawie pingu.
- **Dostosowanie przez Ping:** W metodzie `Fetch_Player_Data`, *timeout* na uzyskanie listy graczy jest dynamicznie dostosowywany:
   ```
   Timeout Graczy = PLAYER_LIST_BASE_TIMEOUT + (Zbuforowany Ping * PING_MULTIPLIER / 1000)
   ```
   Takie podejście pozwala serwerom o wysokiej latencji (wysoki ping) mieć dłuższy *timeout*, zwiększając szansę na pomyślne uzyskanie pełnej listy graczy, która może być duża i czasochłonna do przetworzenia przez serwer.
- **Limit Timeoutu:** `min($timeout, 2.0)` jest używany w różnych wywołaniach, aby narzucić maksymalny limit 2.0 sekund dla zapytań o metadane, zapobiegając nadmiernemu oczekiwaniu.

#### Publiczne Metody Zapytań

| Metoda | Szczegółowy Opis | Wewnętrzne Zachowanie Buforowania |
| :--- | :--- | :--- |
| `Get_All()` | **Zalecana metoda do ogólnego użytku.** Orkiestruje uzyskiwanie `INFO`, `RULES`, `PLAYERS_DETAILED` (lub `PLAYERS_BASIC` jako fallback) równolegle. Minimalizuje to całkowity czas zapytania, ponieważ pakiety są wysyłane niemal jednocześnie, a odpowiedzi są przetwarzane w miarę ich napływania. Zawiera pomiar całkowitego `execution_time_ms`. | Używa bufora 2.0s (`$this->response_cache`) dla każdego odpytywanego opcode'u w fazie równoległej. |
| `Is_Online()` | Wykonuje szybkie zapytanie `INFO` i zwraca `true`, jeśli serwer odpowie prawidłowym `Server_Info` w ramach *timeoutu*, w przeciwnym razie `false`. Jest odporna, używając krytycznych ponownych prób. | Wewnętrznie wywołuje `Fetch_Server_State()`, które używa bufora 5.0s dla `INFO`. |
| `Get_Ping()` | Zwraca najnowszy ping serwera w milisekundach. Jeśli `cached_ping` jest `null`, wymusza dedykowane zapytanie `PING` z `Performance::FAST_PING_TIMEOUT` (0.3s) w celu uzyskania szybkiego pomiaru. | `ping` jest buforowany i aktualizowany za każdym razem, gdy `Execute_Query_Phase` otrzyma pierwszą odpowiedź. |
| `Get_Info()` | Zwraca obiekt `Server_Info` ze szczegółami takimi jak nazwa hosta, tryb gry, liczba graczy itp. | Wywołuje `Fetch_Server_State()`, które używa bufora 5.0s. |
| `Get_Rules()` | Zwraca tablicę `array` obiektów `Server_Rule` zawierającą wszystkie skonfigurowane reguły serwera (np. `mapname`, `weburl`). Zawiera dodatkowe ponowne próby w przypadku początkowej awarii. | Używa bufora 2.0s dla `Opcode::RULES`. |
| `Get_Players_Detailed()` | Zwraca tablicę `array` obiektów `Players_Detailed` (id, nazwa, wynik, ping dla każdego gracza). **Ważne:** To zapytanie jest ignorowane, jeśli liczba graczy na serwerze (`$this->cached_info->players`) jest większa lub równa `Query::LARGE_PLAYER_THRESHOLD` (domyślnie 150), ze względu na ryzyko długich *timeoutów* lub fragmentacji pakietów. | Używa bufora 2.0s dla `Opcode::PLAYERS_DETAILED`. |
| `Get_Players_Basic()` | Zwraca tablicę `array` obiektów `Players_Basic` (nazwa, wynik dla każdego gracza). Jest lżejsza niż zapytanie szczegółowe. Zazwyczaj jest wywoływana jako *fallback*, jeśli `Get_Players_Detailed()` zawiedzie lub zostanie zignorowane. | Używa bufora 2.0s dla `Opcode::PLAYERS_BASIC`. |

#### Komunikacja RCON (`Send_Rcon`)

Metoda `Send_Rcon(string $rcon_password, string $command)` pozwala na wysyłanie poleceń do zdalnej konsoli serwera.

1.  **Walidacja Argumentów:** Rzuca `Invalid_Argument_Exception`, jeśli hasło lub polecenie są puste.
2.  **Izolowane Gniazdo:** Tworzy nową instancję `Socket_Manager` (`$rcon_socket_manager`) dla sesji RCON, izolując ją od głównego gniazda zapytań, aby uniknąć zakłóceń.
3.  **Uwierzytelnianie (`varlist`):** Przed wysłaniem właściwego polecenia, biblioteka wysyła polecenie "varlist" (w maksymalnie 3 próbach) w celu uwierzytelnienia hasła RCON. Jeśli `Send_Single_Rcon_Request` zwróci `null` lub pustą odpowiedź, rzucany jest wyjątek `Rcon_Exception`, wskazujący na błąd uwierzytelniania lub brak włączonego RCON.
4.  **Wysłanie Właściwego Polecenia:** Po pomyślnym uwierzytelnieniu, wysyłane jest `$command`, również w maksymalnie 3 próbach.
5.  **Obsługa Odpowiedzi:** `Packet_Parser::Parse_Rcon()` dekoduje tekstową odpowiedź RCON. Jeśli serwer nie zwróci odpowiedzi tekstowej po wszystkich próbach, zwracany jest ogólny komunikat o powodzeniu.
6.  **Czyszczenie:** Destruktor `$rcon_socket_manager` zapewnia, że gniazdo RCON jest zamykane po operacji.

## Diagnostyka Błędów i Wyjątków

Biblioteka używa hierarchii niestandardowych wyjątków do czystego i przewidywalnego traktowania błędów. W przypadku awarii zostanie rzucony jeden z następujących wyjątków.

### `Invalid_Argument_Exception`

**Przyczyna:**
- **Pusta Nazwa Hosta:** `hostname` podany w konstruktorze `Samp_Query` jest pustym stringiem.
- **Nieprawidłowy Port:** `port` podany w konstruktorze jest poza prawidłowym zakresem od 1 do 65535.
- **RCON:** Hasło RCON lub polecenie RCON podane do `Send_Rcon` są puste.

### `Connection_Exception`

**Przyczyna:** Awaria sieciowa lub brak istotnej odpowiedzi.
- **Błąd Rozwiązywania Nazwy Domeny:** `Domain_Resolver` nie może przekonwertować nazwy hosta na prawidłowy adres IPv4.
- **Błąd Tworzenia Gniazda:** `Socket_Manager` nie może utworzyć lub połączyć gniazda UDP.
- **Serwer Niedostępny/Offline:** Serwer nie odpowiada prawidłowym pakietem `INFO` po wszystkich próbach i *timeoutach* (w tym awaryjnych ponownych próbach), co zazwyczaj wskazuje, że serwer jest offline, IP/port jest nieprawidłowy lub zapora sieciowa blokuje komunikację.

### `Malformed_Packet_Exception`

**Przyczyna:** Uszkodzenie danych na poziomie protokołu.
- **Nieprawidłowy Nagłówek:** `Packet_Parser` wykrywa pakiet, który nie zaczyna się od "magic string" `SAMP` lub ma niewystarczającą całkowitą długość.
- **Nieprawidłowa Struktura Pakietu:** `Packet_Parser` napotyka niespójności w strukturze binarnej, takie jak długość stringu, która wskazuje poza granice pakietu.
- **Odporność:** Ten wyjątek jest często obsługiwany wewnętrznie przez `Execute_Query_Phase` w celu wywołania natychmiastowej ponownej próby, ale może być propagowany, jeśli problem się powtarza.

### `Rcon_Exception`

**Przyczyna:** Błąd podczas komunikacji RCON.
- **Błąd Uwierzytelniania RCON:** Serwer nie odpowiedział na uwierzytelnianie RCON (za pomocą polecenia `varlist`) po 3 próbach, co sugeruje nieprawidłowe hasło lub wyłączony RCON na serwerze.
- **Błąd Wysyłania Polecenia RCON:** Właściwe polecenie RCON nie otrzymało odpowiedzi po 3 próbach.

## Licencja

Copyright © **SA-MP Programming Community**

To oprogramowanie jest licencjonowane na warunkach licencji MIT ("Licencja"); możesz korzystać z tego oprogramowania zgodnie z warunkami Licencji. Kopię Licencji można uzyskać pod adresem: [MIT License](https://opensource.org/licenses/MIT)

### Warunki użytkowania

#### 1. Przyznane uprawnienia

Niniejsza licencja bezpłatnie przyznaje każdej osobie otrzymującej kopię tego oprogramowania i powiązanych plików dokumentacji następujące prawa:
* Używanie, kopiowanie, modyfikowanie, łączenie, publikowanie, rozpowszechnianie, sublicencjonowanie i/lub sprzedaż kopii oprogramowania bez ograniczeń
* Zezwalanie osobom, którym oprogramowanie jest dostarczane, na to samo, pod warunkiem przestrzegania poniższych warunków

#### 2. Obowiązkowe warunki

Wszystkie kopie lub istotne części oprogramowania muszą zawierać:
* Powyższą informację o prawach autorskich
* Niniejsze zezwolenie
* Poniższe wyłączenie odpowiedzialności

#### 3. Prawa autorskie

Oprogramowanie i cała powiązana dokumentacja są chronione prawami autorskimi. **SA-MP Programming Community** zachowuje oryginalne prawa autorskie do oprogramowania.

#### 4. Wyłączenie gwarancji i ograniczenie odpowiedzialności

OPROGRAMOWANIE JEST DOSTARCZANE "TAK JAK JEST", BEZ JAKIEJKOLWIEK GWARANCJI, WYRAŹNEJ LUB DOROZUMIANEJ, W TYM MIĘDZY INNYMI GWARANCJI PRZYDATNOŚCI HANDLOWEJ, PRZYDATNOŚCI DO OKREŚLONEGO CELU I NIENARUSZANIA PRAW.

W ŻADNYM WYPADKU AUTORZY LUB POSIADACZE PRAW AUTORSKICH NIE PONOSZĄ ODPOWIEDZIALNOŚCI ZA JAKIEKOLWIEK ROSZCZENIA, SZKODY LUB INNE ZOBOWIĄZANIA, CZY TO W RAMACH DZIAŁAŃ UMOWNYCH, DELIKTOWYCH CZY INNYCH, WYNIKAJĄCYCH Z OPROGRAMOWANIA LUB ZWIĄZANYCH Z NIM LUB Z JEGO UŻYTKOWANIEM LUB INNYMI CZYNNOŚCIAMI W OPROGRAMOWANIU.

---

Szczegółowe informacje o licencji MIT można znaleźć pod adresem: https://opensource.org/licenses/MIT