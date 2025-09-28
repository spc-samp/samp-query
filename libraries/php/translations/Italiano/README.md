# SA-MP Query - PHP

<div align="center">

![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Architecture](https://img.shields.io/badge/Protocol-UDP-blueviolet?style=for-the-badge)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**Una libreria PHP robusta e resiliente, progettata per interrogare lo stato e le informazioni dei server SA-MP (San Andreas Multiplayer) e OMP (Open Multiplayer).**

</div>

## Lingue

- Português: [README](../../)
- Deutsch: [README](../Deutsch/README.md)
- English: [README](../English/README.md)
- Español: [README](../Espanol/README.md)
- Français: [README](../Francais/README.md)
- Polski: [README](../Polski/README.md)
- Русский: [README](../Русский/README.md)
- Svenska: [README](../Svenska/README.md)
- Türkçe: [README](../Turkce/README.md)

## Indice

- [SA-MP Query - PHP](#sa-mp-query---php)
  - [Lingue](#lingue)
  - [Indice](#indice)
  - [Panoramica](#panoramica)
  - [Principi di Progettazione e Architettura](#principi-di-progettazione-e-architettura)
    - [Architettura Modulare](#architettura-modulare)
    - [Resilienza: Backoff, Retries e Caching](#resilienza-backoff-retries-e-caching)
    - [Ottimizzazione delle Prestazioni: Parallelismo e Adattamento del Timeout](#ottimizzazione-delle-prestazioni-parallelismo-e-adattamento-del-timeout)
    - [Programmazione Orientata agli Oggetti (OOP) Moderna (PHP 8.1+)](#programmazione-orientata-agli-oggetti-oop-moderna-php-81)
  - [Requisiti](#requisiti)
  - [Installazione e Uso di Base](#installazione-e-uso-di-base)
    - [Inizializzazione della Classe `Samp_Query`](#inizializzazione-della-classe-samp_query)
    - [`Get_All()`: Interrogazione Completa e Ottimizzata](#get_all-interrogazione-completa-e-ottimizzata)
    - [`Is_Online()`: Verifica Rapida dello Stato](#is_online-verifica-rapida-dello-stato)
    - [`Get_Ping()`: Ottenimento del Ping del Server](#get_ping-ottenimento-del-ping-del-server)
    - [`Get_Info()`: Dettagli Essenziali del Server](#get_info-dettagli-essenziali-del-server)
    - [`Get_Rules()`: Regole Configurate del Server](#get_rules-regole-configurate-del-server)
    - [`Get_Players_Detailed()`: Elenco dei Giocatori con Dettagli](#get_players_detailed-elenco-dei-giocatori-con-dettagli)
    - [`Get_Players_Basic()`: Elenco Base dei Giocatori](#get_players_basic-elenco-base-dei-giocatori)
    - [`Send_Rcon()`: Invio di Comandi Remoti](#send_rcon-invio-di-comandi-remoti)
  - [Struttura Dettagliata della Libreria e Flusso di Esecuzione](#struttura-dettagliata-della-libreria-e-flusso-di-esecuzione)
    - [1. `constants.php`: Il Cuore della Configurazione](#1-constantsphp-il-cuore-della-configurazione)
    - [2. `opcode.php`: L'Enum degli Opcode del Protocollo](#2-opcodephp-lenum-degli-opcode-del-protocollo)
    - [3. `exceptions.php`: La Gerarchia delle Eccezioni Personalizzate](#3-exceptionsphp-la-gerarchia-delle-eccezioni-personalizzate)
    - [4. `server_types.php`: I Modelli di Dati Immutabili](#4-server_typesphp-i-modelli-di-dati-immutabili)
    - [5. `autoloader.php`: Il Caricatore Automatico di Classi](#5-autoloaderphp-il-caricatore-automatico-di-classi)
    - [6. `logger.php`: Il Sottosistema di Log](#6-loggerphp-il-sottosistema-di-log)
    - [7. `domain_resolver.php`: La Risoluzione del Dominio con Cache Persistente](#7-domain_resolverphp-la-risoluzione-del-dominio-con-cache-persistente)
    - [8. `socket_manager.php`: Il Gestore di Connessione UDP Robusto](#8-socket_managerphp-il-gestore-di-connessione-udp-robusto)
    - [9. `packet_builder.php`: Il Costruttore di Pacchetti Binari](#9-packet_builderphp-il-costruttore-di-pacchetti-binari)
    - [10. `packet_parser.php`: Il Decodificatore di Pacchetti con Gestione della Codifica](#10-packet_parserphp-il-decodificatore-di-pacchetti-con-gestione-della-codifica)
    - [11. `samp-query.php`: La Classe Principale (L'Orchestratore Completo)](#11-samp-queryphp-la-classe-principale-lorchestratore-completo)
      - [Ciclo di Vita dell'Interrogazione: Il Viaggio di un Pacchetto](#ciclo-di-vita-dellinterrogazione-il-viaggio-di-un-pacchetto)
        - [1. Inizializzazione e Risoluzione del Dominio](#1-inizializzazione-e-risoluzione-del-dominio)
        - [2. `Fetch_Server_State()`: Cache e Interrogazione Critica di INFO/PING](#2-fetch_server_state-cache-e-interrogazione-critica-di-infoping)
        - [3. `Attempt_Query()`: La Strategia di Retries Ottimizzata](#3-attempt_query-la-strategia-di-retries-ottimizzata)
        - [4. `Execute_Query_Phase()`: Il Motore di Comunicazione con Rilevamento del Ping](#4-execute_query_phase-il-motore-di-comunicazione-con-rilevamento-del-ping)
        - [5. `Validate_Response()`: Lo Strato di Validazione Semantica](#5-validate_response-lo-strato-di-validazione-semantica)
      - [Calcolo e Gestione del Timeout Adattivo](#calcolo-e-gestione-del-timeout-adattivo)
      - [Metodi di Interrogazione Pubblica](#metodi-di-interrogazione-pubblica)
      - [Comunicazione RCON (`Send_Rcon`)](#comunicazione-rcon-send_rcon)
  - [Diagnostica degli Errori ed Eccezioni](#diagnostica-degli-errori-ed-eccezioni)
    - [`Invalid_Argument_Exception`](#invalid_argument_exception)
    - [`Connection_Exception`](#connection_exception)
    - [`Malformed_Packet_Exception`](#malformed_packet_exception)
    - [`Rcon_Exception`](#rcon_exception)
  - [Licenza](#licenza)
    - [Termini e Condizioni d'Uso](#termini-e-condizioni-duso)
      - [1. Permessi Concessi](#1-permessi-concessi)
      - [2. Condizioni Obbligatorie](#2-condizioni-obbligatorie)
      - [3. Diritti d'Autore](#3-diritti-dautore)
      - [4. Esclusione di Garanzia e Limitazione di Responsabilità](#4-esclusione-di-garanzia-e-limitazione-di-responsabilità)

## Panoramica

La libreria **SA-MP Query - PHP** è una soluzione ad alte prestazioni e tollerante ai guasti per gli sviluppatori PHP che necessitano di interagire con server di gioco basati sul protocollo SA-MP/OMP (UDP). Il suo scopo è incapsulare la complessità del protocollo di interrogazione binario in un'API PHP pulita e intuitiva, consentendo ad applicazioni web, launcher e utility di ottenere informazioni dettagliate sullo stato del server (giocatori, regole, ping, ecc.) in modo rapido e affidabile.

Il design della libreria si concentra su tre pilastri principali: **Resilienza**, **Prestazioni** e **Modularità**. È costruita per gestire la natura inaffidabile del protocollo UDP, implementando un sistema avanzato di tentativi e *backoff* per garantire che le informazioni vengano ottenute anche in condizioni di rete sfavorevoli o server con alta latenza.

## Principi di Progettazione e Architettura

### Architettura Modulare

La libreria è suddivisa in componenti a responsabilità unica, ciascuno incapsulato nella propria classe e file.

- **Infrastruttura di Rete:** `Domain_Resolver`, `Socket_Manager`.
- **Protocollo:** `Packet_Builder`, `Packet_Parser`, `Opcode` (Enum).
- **Logica di Business (Orchestrazione):** `Samp_Query`.
- **Modelli di Dati:** `Server_Info`, `Players_Detailed`, ecc.

### Resilienza: Backoff, Retries e Caching

Il protocollo UDP non garantisce la consegna dei pacchetti. La classe `Samp_Query` mitiga questo problema con un sofisticato ciclo di interrogazione.

- **Tentativi Multipli Adattivi:** Il metodo `Attempt_Query` implementa un ciclo con fino a `Query::ATTEMPTS` (5 di default) e il doppio per le interrogazioni critiche.
- **Strategia di Backoff:** Il *backoff* esponenziale è implementato in `Execute_Query_Phase`. Dopo il primo invio, l'intervallo tra i nuovi tentativi di ascolto (ciclo `while`) aumenta da `Performance::INITIAL_RETRY_INTERVAL` (0.08s) del `Performance::BACKOFF_FACTOR` (1.3), fino al limite di 0.2s. Ciò evita il sovraccarico di pacchetti e aumenta le possibilità di una risposta tempestiva.
- **Caching delle Risposte:** Le risposte recenti (valide per 2.0 secondi) vengono memorizzate in `response_cache`, eliminando la necessità di ripetere le interrogazioni sui metadati durante l'esecuzione di `Get_All()`.

### Ottimizzazione delle Prestazioni: Parallelismo e Adattamento del Timeout

- **Interrogazioni Parallele (Fan-out):** Il metodo `Get_All()` invia richieste per `INFO`, `RULES` e `PLAYERS` simultaneamente (in `$jobs`), consentendo alle risposte di arrivare fuori ordine, minimizzando il tempo di attesa totale.
- **Caching DNS Persistente:** Il `Domain_Resolver` memorizza l'indirizzo IP risolto in una cache su file locale con un TTL di 3600 secondi, evitando ritardi nella risoluzione del dominio nelle chiamate successive.
- **Timeout Adattivo:** Il *timeout* delle interrogazioni di grandi quantità di dati (come l'elenco dei giocatori) viene regolato dinamicamente in base al `cached_ping` del server:
   ```
   Timeout Regolato = Timeout di Base + (Ping in Cache * Moltiplicatore Ping / 1000)
   ```
   Questa logica (implementata in `Fetch_Player_Data`) garantisce che i server con alta latenza abbiano tempo sufficiente per rispondere, senza compromettere la velocità sui server a bassa latenza.

### Programmazione Orientata agli Oggetti (OOP) Moderna (PHP 8.1+)

La libreria utilizza funzionalità moderne di PHP per garantire sicurezza e chiarezza:

- **Tipizzazione Stretta** (`declare(strict_types=1)`).
- **Proprietà di Sola Lettura** (`public readonly` in `Samp_Query` e nei modelli di dati) per garantire l'immutabilità dei dati.
- **Enum Tipizzati** (`enum Opcode: string`) per un controllo sicuro del protocollo.
- **Constructor Property Promotion** (in `Samp_Query::__construct` e nei modelli).

## Requisiti

- **PHP:** Versione **8.1 o superiore**.
- **Estensioni PHP:** `sockets` e `mbstring` (per la manipolazione della codifica UTF-8).

## Installazione e Uso di Base

Per iniziare a usare la libreria **SA-MP Query - PHP**, basta includere il file `samp-query.php` nel tuo progetto. Questo file si occuperà di caricare automaticamente tutte le dipendenze tramite il suo autoloader interno.

```php
<?php
// Includi la classe principale. Si occuperà di caricare le dipendenze tramite l'autoloader.
require_once 'path/to/samp-query/samp-query.php'; 

// Usa il namespace della classe principale
use Samp_Query\Samp_Query;
// Includi le eccezioni per una gestione degli errori più specifica
use Samp_Query\Exceptions\Invalid_Argument_Exception;
use Samp_Query\Exceptions\Connection_Exception;
use Samp_Query\Exceptions\Rcon_Exception;

// Istanzia la classe Samp_Query, avvolgendola in un blocco try-catch per gestire gli errori di inizializzazione.
try {
    $server_query = new Samp_Query("play.example-samp-server.com", 7777);
    // Ora puoi usare i metodi pubblici di $server_query
}
catch (Invalid_Argument_Exception $e) {
    echo "Errore di Argomento: " . $e->getMessage() . "\n";
}
catch (Connection_Exception $e) {
    echo "Errore di Connessione: " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Errore inaspettato durante l'inizializzazione: " . $e->getMessage() . "\n";
}
```

### Inizializzazione della Classe `Samp_Query`

La classe `Samp_Query` è il punto di accesso a tutte le funzionalità. Il suo costruttore richiede l'`hostname` (o indirizzo IP) e la `port` del server che si desidera interrogare.

```php
/**
 * Inizializza una nuova istanza della libreria Samp_Query.
 *
 * @param string $hostname L'hostname o l'indirizzo IP del server SA-MP/OMP.
 * @param int $port La porta UDP del server (di solito 7777).
 * 
 * @throws Invalid_Argument_Exception Se l'hostname è vuoto o la porta non è valida.
 * @throws Connection_Exception Se la risoluzione DNS fallisce o il socket non può essere creato.
 */
public function __construct(public readonly string $hostname, public readonly int $port)
```

<br>

---

### `Get_All()`: Interrogazione Completa e Ottimizzata

Questo è il metodo più completo e consigliato. Esegue diverse interrogazioni (INFO, RULES, PLAYERS) in parallelo e in modo ottimizzato, minimizzando il tempo di risposta totale e restituendo un array associativo completo con tutte le informazioni disponibili.

```php
/**
 * Restituisce tutte le informazioni disponibili del server in una singola chiamata ottimizzata.
 * Include: is_online, ping, info (Server_Info), rules (array di Server_Rule),
 * players_detailed (array di Players_Detailed), players_basic (array di Players_Basic),
 * e execution_time_ms.
 *
 * @return array Un array associativo contenente tutte le informazioni del server.
 * 
 * @throws Connection_Exception Se l'interrogazione INFO, essenziale per lo stato del server, fallisce.
 */
public function Get_All(): array
```

Esempio di Utilizzo:

```php
<?php
// ... (inizializzazione della classe $server_query) ...

try {
    $data = $server_query->Get_All();
    
    if ($data['is_online']) {
        echo "## Server Online: {$data['info']->hostname} ({$server_query->ip}:{$server_query->port}) ##\n";
        echo "Ping: {$data['ping']}ms | Tempo Totale dell'Interrogazione: {$data['execution_time_ms']}ms\n";
        echo "Giocatori: {$data['info']->players} / {$data['info']->max_players}\n";
        echo "Modalità di Gioco: {$data['info']->gamemode}\n";
        echo "Lingua: {$data['info']->language}\n";
        echo "Protetto da Password: " . ($data['info']->password ? "Sì" : "No") . "\n\n";

        // Esempio di elenco dettagliato dei giocatori
        if (!empty($data['players_detailed'])) {
            echo "--- Giocatori Dettagliati ({$data['info']->players} Attivi) ---\n";

            foreach ($data['players_detailed'] as $player)
                echo "ID: {$player->id}, Nome: {$player->name}, Ping: {$player->ping}ms, Punteggio: {$player->score}\n";
        }
        elseif (!empty($data['players_basic'])) {
            echo "--- Giocatori Base ({$data['info']->players} Attivi) (Fallback) ---\n";

            foreach ($data['players_basic'] as $player)
                echo "Nome: {$player->name}, Punteggio: {$player->score}\n";
        }
        else
            echo "Nessun giocatore online o elenco non disponibile (forse troppi giocatori).\n";
        
        // Esempio di regole del server
        if (!empty($data['rules'])) {
            echo "\n--- Regole del Server ---\n";

            foreach ($data['rules'] as $rule)
                echo "{$rule->name}: {$rule->value}\n";
        }
    }
    else
        echo "Il server è attualmente offline o inaccessibile.\n";
}
catch (Connection_Exception $e) {
    echo "Fallimento della connessione durante il tentativo di ottenere tutte le informazioni: " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Errore inaspettato durante l'interrogazione di tutte le informazioni: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Is_Online()`: Verifica Rapida dello Stato

Verifica se il server è online e risponde alle interrogazioni, senza cercare dettagli aggiuntivi. Ideale per un semplice "liveness check".

```php
/**
 * Verifica se il server è online e accessibile.
 *
 * @return bool Restituisce true se il server è online e risponde con INFO valido, altrimenti false.
 */
public function Is_Online(): bool
```

Esempio di Utilizzo:

```php
<?php
// ... (inizializzazione della classe $server_query) ...

if ($server_query->Is_Online())
    echo "Il server SA-MP è online e sta rispondendo!\n";
else
    echo "Il server SA-MP è offline o non ha risposto in tempo.\n";
```

<br>

---

### `Get_Ping()`: Ottenimento del Ping del Server

Restituisce il tempo di latenza (ping) del server in millisecondi. Questo valore viene memorizzato internamente in cache per ottimizzazione.

```php
/**
 * Restituisce il ping attuale del server in millisecondi.
 * Se il ping non è ancora stato calcolato, verrà eseguita una rapida interrogazione PING.
 *
 * @return int|null Il valore del ping in millisecondi, o null se non è possibile ottenerlo.
 */
public function Get_Ping(): ?int
```

Esempio di Utilizzo:

```php
<?php
// ... (inizializzazione della classe $server_query) ...

try {
    $ping = $server_query->Get_Ping();

    if ($ping !== null)
        echo "Il ping del server è: {$ping}ms.\n";
    else
        echo "Non è stato possibile ottenere il ping del server.\n";
}
catch (Connection_Exception $e) {
    echo "Errore durante l'ottenimento del ping: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Info()`: Dettagli Essenziali del Server

Ottiene le informazioni di base del server, come hostname, modalità di gioco, numero di giocatori, ecc. Restituisce un oggetto `Server_Info`.

```php
/**
 * Restituisce i dettagli di base del server (hostname, giocatori, gamemode, ecc.).
 * I dati vengono memorizzati in cache per un breve periodo per ottimizzazione.
 *
 * @return Server_Info|null Un oggetto Server_Info, o null se le informazioni non possono essere ottenute.
 */
public function Get_Info(): ?Server_Info
```

Esempio di Utilizzo:

```php
<?php
use Samp_Query\Models\Server_Info;
// ... (inizializzazione della classe $server_query) ...

try {
    /** @var Server_Info|null $info */
    $info = $server_query->Get_Info();

    if ($info) {
        echo "--- Informazioni del Server ---\n";
        echo "Hostname: {$info->hostname}\n";
        echo "Gamemode: {$info->gamemode}\n";
        echo "Giocatori: {$info->players} / {$info->max_players}\n";
        echo "Lingua: {$info->language}\n";
        echo "Protetto da Password: " . ($info->password ? "Sì" : "No") . "\n";
    }
    else
        echo "Non è stato possibile ottenere le informazioni del server.\n";
}
catch (Connection_Exception $e) {
    echo "Errore durante l'ottenimento delle informazioni del server: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Rules()`: Regole Configurate del Server

Recupera tutte le regole configurate sul server, come `mapname`, `weburl`, `weather`, ecc., restituendole come un array di oggetti `Server_Rule`.

```php
/**
 * Restituisce un array di oggetti Server_Rule, ognuno contenente il nome e il valore di una regola del server.
 * I dati vengono memorizzati in cache per ottimizzazione.
 *
 * @return array Un array di Samp_Query\Models\Server_Rule. Può essere vuoto se non ci sono regole.
 */
public function Get_Rules(): array
```

Esempio di Utilizzo:

```php
<?php
use Samp_Query\Models\Server_Rule;
// ... (inizializzazione della classe $server_query) ...

try {
    $rules = $server_query->Get_Rules();

    if (!empty($rules)) {
        echo "--- Regole del Server ---\n";

        foreach ($rules as $rule)
            echo "{$rule->name}: {$rule->value}\n";

        // Esempio di come accedere a una regola specifica:
        $mapname_rule = array_filter($rules, fn(Server_Rule $r) => $r->name === 'mapname');

        if (!empty($mapname_rule))
            echo "\nMappa attuale: " . reset($mapname_rule)->value . "\n";
    }
    else
        echo "Nessuna regola trovata per questo server.\n";
}
catch (Connection_Exception $e) {
    echo "Errore durante l'ottenimento delle regole del server: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Players_Detailed()`: Elenco dei Giocatori con Dettagli

Ottiene un elenco dettagliato dei giocatori attualmente online, inclusi ID, nome, punteggio e ping.

> [!CAUTION]
> Per ottimizzare le prestazioni ed evitare pacchetti UDP eccessivamente grandi che possono essere persi o frammentati, questo metodo non recupererà l'elenco dettagliato dei giocatori se il numero totale di giocatori è uguale o superiore a `Query::LARGE_PLAYER_THRESHOLD` (150 di default). In questi casi, verrà restituito un array vuoto. Considera di usare `Get_Players_Basic()` come fallback.

```php
/**
 * Restituisce un array di oggetti Players_Detailed (ID, nome, punteggio, ping) for each online player.
 * Questa interrogazione può essere ignorata se il numero di giocatori è molto alto (vedi Query::LARGE_PLAYER_THRESHOLD).
 *
 * @return array Un array di Samp_Query\Models\Players_Detailed. Può essere vuoto.
 */
public function Get_Players_Detailed(): array
```

Esempio di Utilizzo:

```php
<?php
use Samp_Query\Models\Players_Detailed;
// ... (inizializzazione della classe $server_query) ...

try {
    $players_detailed = $server_query->Get_Players_Detailed();

    if (!empty($players_detailed)) {
        echo "--- Giocatori Online (Dettagliato) ---\n";

        foreach ($players_detailed as $player)
            echo "ID: {$player->id}, Nome: {$player->name}, Punteggio: {$player->score}, Ping: {$player->ping}ms\n";
    }
    else
        echo "Nessun giocatore online o elenco dettagliato non disponibile.\n";
}
catch (Connection_Exception $e) {
    echo "Errore durante l'ottenimento dell'elenco dettagliato dei giocatori: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Players_Basic()`: Elenco Base dei Giocatori

Fornisce un elenco più leggero di giocatori, contenente solo nome e punteggio. Utile come alternativa quando l'elenco dettagliato non è disponibile o per ridurre il carico di dati.

```php
/**
 * Restituisce un array di oggetti Players_Basic (nome, punteggio) per ogni giocatore online.
 * Utile come alternativa più leggera o fallback quando Get_Players_Detailed() non è fattibile.
 *
 * @return array Un array di Samp_Query\Models\Players_Basic. Può essere vuoto.
 */
public function Get_Players_Basic(): array
```

Esempio di Utilizzo:

```php
<?php
use Samp_Query\Models\Players_Basic;
// ... (inizializzazione della classe $server_query) ...

try {
    $players_basic = $server_query->Get_Players_Basic();

    if (!empty($players_basic)) {
        echo "--- Giocatori Online (Base) ---\n";

        foreach ($players_basic as $player)
            echo "Nome: {$player->name}, Punteggio: {$player->score}\n";
    }
    else
        echo "Nessun giocatore online o elenco base non disponibile.\n";
}
catch (Connection_Exception $e) {
    echo "Errore durante l'ottenimento dell'elenco base dei giocatori: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Send_Rcon()`: Invio di Comandi Remoti

Permette di inviare comandi alla console RCON del server, come cambiare regole, bannare giocatori, ecc. Richiede la password RCON del server.

> [!WARNING]
> La funzione RCON è sensibile e può causare modifiche al server. Usare con cautela e solo con password di fiducia.
> È cruciale che la password RCON sia **corretta** e che RCON sia **abilitato** sul server (configurazione `rcon_password` in `server.cfg`).

```php
/**
 * Invia un comando RCON al server.
 * Esegue l'autenticazione con 'varlist' e invia il comando.
 *
 * @param string $rcon_password La password RCON del server.
 * @param string $command Il comando da eseguire (es: "gmx", "kick ID").
 * @return string La risposta del server al comando RCON, o un messaggio di stato.
 * 
 * @throws Invalid_Argument_Exception Se la password o il comando RCON sono vuoti.
 * @throws Rcon_Exception Se l'autenticazione RCON fallisce o il comando non riceve risposta.
 * @throws Connection_Exception In caso di fallimento della connessione durante l'operazione RCON.
 */
public function Send_Rcon(string $rcon_password, string $command): string
```

Esempio di Utilizzo:

```php
<?php
// ... (inizializzazione della classe $server_query) ...

$rcon_password = "tua_password_segreta_qui"; 
$command_to_send = "gmx"; // Esempio: riavviare il gamemode

try {
    echo "Tentativo di inviare il comando RCON: '{$command_to_send}'...\n";
    $response = $server_query->Send_Rcon($rcon_password, $command_to_send);
    echo "Risposta RCON: {$response}\n";

    // Esempio di comando per "dire" qualcosa sul server (richiede RCON)
    $response_say = $server_query->Send_Rcon($rcon_password, "say Ciao dal mio script PHP!");
    echo "Risposta RCON (say): {$response_say}\n";
}
catch (Invalid_Argument_Exception $e) {
    echo "Errore RCON (Argomento Non Valido): " . $e->getMessage() . "\n";
}
catch (Rcon_Exception $e) {
    echo "Errore RCON: Fallimento dell'autenticazione o comando non eseguito. " . $e->getMessage() . "\n";
}
catch (Connection_Exception $e) {
    echo "Errore RCON (Connessione): " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Errore inaspettato durante l'invio RCON: " . $e->getMessage() . "\n";
}
```

## Struttura Dettagliata della Libreria e Flusso di Esecuzione

La libreria **SA-MP Query - PHP** è meticolosamente organizzata in diversi file, ciascuno con una responsabilità ben definita. Questa sezione esplora ogni componente in dettaglio, rivelando le decisioni di progettazione e la logica sottostante.

### 1. `constants.php`: Il Cuore della Configurazione

Questo file centralizza tutti i parametri di configurazione "magici" della libreria, garantendo che aspetti come *timeouts*, numero di tentativi e dimensioni dei buffer siano facilmente regolabili e coerenti in tutto il progetto.

```php
// ...
final class Query {
    public const ATTEMPTS = 5; // Numero massimo di tentativi di interrogazione
    public const LARGE_PLAYER_THRESHOLD = 150; // Limite di giocatori per l'interrogazione dettagliata
}

final class Protocol {
    public const UDP_READ_BUFFER_SIZE = 32768; // 32KB per il buffer di lettura
    public const KERNEL_RECEIVE_BUFFER_SIZE = 4194304; // 4MB per il buffer del kernel
}
// ...
```

- **Classi Final e Costanti:** Le classi sono `final` e le proprietà sono `public const`, garantendo immutabilità e accessibilità globale in fase di compilazione.
- **Granularità e Semantica:** Le costanti sono categorizzate per il loro dominio (`Query`, `Protocol`, `Performance`, `Logger`, `DNS`), facilitando la comprensione e la manutenzione. Ad esempio, `Query::LARGE_PLAYER_THRESHOLD` definisce il punto in cui la ricerca di elenchi dettagliati di giocatori può essere evitata per ottimizzazione, a causa del volume di dati e del potenziale di *timeout*.

### 2. `opcode.php`: L'Enum degli Opcode del Protocollo

Questo file definisce i codici di operazione (opcodes) utilizzati per le diverse interrogazioni al server SA-MP/OMP, incapsulandoli in un `enum` tipizzato.

```php
// ...
enum Opcode: string {
    case INFO = 'i';
    case RULES = 'r';
    case PLAYERS_DETAILED = 'd';
    // ...
}
```

- **`Enum` Tipizzato (PHP 8.1+):** L'utilizzo di un `enum` (`Opcode: string`) con valori di tipo `string` garantisce che gli opcode siano sempre validi e che il codice sia semanticamente chiaro. Questo sostituisce l'uso di stringhe letterali "magiche", rendendo il codice più leggibile e meno soggetto a errori di battitura.

### 3. `exceptions.php`: La Gerarchia delle Eccezioni Personalizzate

Questo file stabilisce una gerarchia di eccezioni personalizzate, consentendo una gestione degli errori più granulare e specifica per i diversi tipi di fallimenti che possono verificarsi nella libreria.

```php
// ...
class Query_Exception extends \Exception {}
class Invalid_Argument_Exception extends Query_Exception {}
class Connection_Exception extends Query_Exception {}
// ...
```

- **Ereditarietà da `\Exception`:** Tutte le eccezioni ereditano da `Query_Exception` (che a sua volta estende `\Exception`), consentendo di catturare gruppi di errori (`Connection_Exception` e `Timeout_Exception` sono più specifiche di `Query_Exception`) o tutte le eccezioni della libreria con un `catch` più generico.
- **Chiarezza nella Diagnostica:** I nomi descrittivi delle eccezioni facilitano la diagnosi e il recupero degli errori nell'applicazione client.

### 4. `server_types.php`: I Modelli di Dati Immutabili

Questo file definisce le classi che rappresentano i modelli di dati per le informazioni restituite dal server, garantendo l'integrità dei dati attraverso l'immutabilità.

```php
// ...
final class Server_Info {
    public function __construct(
        public readonly bool $password,
        public readonly int $players,
        // ... altre proprietà readonly ...
    ) {}
}
// ...
```

- **`final class`:** Le classi sono `final`, prevenendo estensioni e garantendone la struttura e il comportamento.
- **`public readonly` Properties (PHP 8.1+):** Tutte le proprietà sono dichiarate come `public readonly`. Ciò significa che, una volta costruito l'oggetto, i suoi valori non possono essere modificati, garantendo l'integrità dei dati ricevuti dal server.
- **Constructor Property Promotion (PHP 8.1+):** Le proprietà sono dichiarate direttamente nel costruttore, semplificando il codice e riducendo il *boilerplate*.

### 5. `autoloader.php`: Il Caricatore Automatico di Classi

Questo file è responsabile del caricamento dinamico delle classi della libreria quando sono necessarie, seguendo lo standard PSR-4.

```php
// ...
spl_autoload_register(function ($class) {
    $prefix = 'Samp_Query\\';
    $base_dir = __DIR__ . '/../modules/'; // Mappa il namespace alla directory
    // ... logica di caricamento ...
});

// Include file essenziali che non sono classi, o che necessitano di caricamento anticipato
require_once __DIR__ . '/../modules/constants.php';
require_once __DIR__ . '/../modules/exceptions.php';
// ...
```

- **`spl_autoload_register()`:** Registra una funzione anonima che viene chiamata automaticamente da PHP quando si fa riferimento a una classe non definita, velocizzando lo sviluppo e la manutenzione.
- **Inclusione Diretta delle Configurazioni:** File come `constants.php` e `exceptions.php` sono inclusi direttamente. Ciò garantisce che le loro definizioni siano disponibili prima che qualsiasi classe che li utilizzi venga istanziata dall'autoloader.

### 6. `logger.php`: Il Sottosistema di Log

La classe `Logger` fornisce un meccanismo semplice per registrare messaggi di errore ed eventi importanti in un file di log, utile per il debug e il monitoraggio.

```php
// ...
final class Logger {
    public function Log(string $message): void {
        if (is_file(Logger_Config::FILE) && filesize(Logger_Config::FILE) > Logger_Config::MAX_SIZE_BYTES)
            unlink(Logger_Config::FILE); // Rimuove il log se supera le dimensioni

        $timestamp = date('Y-m-d H:i:s');
        $log_entry = "[{$timestamp}] {$message}" . PHP_EOL;
        
        file_put_contents(Logger_Config::FILE, $log_entry, FILE_APPEND | LOCK_EX); // Aggiunge con blocco
    }
}
```

- **Pulizia Automatica:** Il logger controlla le dimensioni del file di log (`Logger_Config::FILE`). Se supera `Logger_Config::MAX_SIZE_BYTES` (10 MB di default), il file viene eliminato per evitare che cresca indefinitamente.
- **Blocco del File (`LOCK_EX`):** `file_put_contents` utilizza `LOCK_EX` per garantire che solo un processo alla volta scriva nel file di log, prevenendo la corruzione in ambienti multi-threaded/multi-processo.

### 7. `domain_resolver.php`: La Risoluzione del Dominio con Cache Persistente

La classe `Domain_Resolver` è responsabile della conversione dei nomi host (come `play.example.com`) in indirizzi IP (come `192.0.2.1`). Implementa un sistema di cache su disco per ottimizzare le prestazioni.

```php
// ...
final class Domain_Resolver {
    public function Resolve(string $hostname): string {
        if (filter_var($hostname, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
            return $hostname; // È già un IP

        $cache_file = DNS_Config::CACHE_DIR . md5($hostname) . '.cache';

        if (file_exists($cache_file) && (time() - filemtime($cache_file)) < DNS_Config::CACHE_TTL_SECONDS) {
            $ip = file_get_contents($cache_file);

            if ($ip && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
                return $ip;
        }
        
        $ip = gethostbyname($hostname); // Risoluzione DNS reale
        // ... logica di validazione e salvataggio in cache ...

        return $ip;
    }
    // ...
}
```

- **Cache Persistente:** Prima di chiamare `gethostbyname()`, verifica se l'IP è già memorizzato in un file di cache (`dns/` + hash MD5 dell'hostname). La cache è considerata valida se non ha superato `DNS_Config::CACHE_TTL_SECONDS` (3600 secondi o 1 ora di default).
- **Validazione Robusta:** L'IP risolto (o letto dalla cache) viene validato con `filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)` per garantire che sia un IPv4 valido. Se la risoluzione fallisce, viene lanciata una `Query_Exception`.

### 8. `socket_manager.php`: Il Gestore di Connessione UDP Robusto

La classe `Socket_Manager` incapsula la complessità della creazione, configurazione e gestione di un socket UDP per la comunicazione con il server di gioco.

```php
// ...
final class Socket_Manager {
    private $socket = null;
    // ...
    private function Connect(): void {
        if (is_resource($this->socket))
            return;

        $context = stream_context_create([
            'socket' => ['so_rcvbuf' => Protocol::KERNEL_RECEIVE_BUFFER_SIZE] // Aumenta il buffer a 4MB
        ]);

        $this->socket = stream_socket_client(
            "udp://{$this->host}:{$this->port}", 
            $err_no, $err_str, 
            (float)ini_get("default_socket_timeout"), 
            STREAM_CLIENT_CONNECT, // Connette il socket all'indirizzo remoto
            $context
        );

        if ($this->socket === false)
            throw new Connection_Exception("Could not create socket...");
    }
    // ...
}
```

- **`stream_socket_client` con `STREAM_CLIENT_CONNECT`:** Questo *flag* istruisce il sistema operativo a "connettere" il socket UDP all'indirizzo remoto. Sebbene l'UDP sia senza connessione, "connettere" il socket consente ottimizzazioni delle prestazioni a livello di kernel, come non dover specificare l'indirizzo remoto in ogni chiamata `fwrite` o `stream_socket_recvfrom`, risultando in un minor sovraccarico.
- **Buffer di Ricezione del Kernel:** `stream_context_create` viene utilizzato per aumentare le dimensioni del buffer di ricezione del kernel (`so_rcvbuf`) a `Protocol::KERNEL_RECEIVE_BUFFER_SIZE` (4MB). Questo è cruciale per evitare la perdita di pacchetti (overflow del buffer) quando si ricevono risposte di grandi dimensioni, come elenchi dettagliati di giocatori da server affollati.
- **RAII tramite `__destruct`:** Il metodo `Disconnect()` viene invocato automaticamente nel distruttore (`__destruct`), garantendo che il socket venga chiuso e le risorse liberate, anche in caso di eccezioni.
- **Timeout Dinamico:** `Set_Timeout` regola con precisione i timeout di lettura/scrittura del socket utilizzando `stream_set_timeout`, fondamentale per la logica di *retries* e *backoff*.

### 9. `packet_builder.php`: Il Costruttore di Pacchetti Binari

La classe `Packet_Builder` è responsabile della serializzazione dei dati della richiesta in un formato binario specifico che il server SA-MP/OMP può comprendere.

```php
// ...
final class Packet_Builder {
    // ...
    private function Build_Header(): string {
        $packet = 'SAMP';
        $packet .= pack('c4', ...$this->ip_parts); // IP in 4 byte
        $packet .= pack('v', $this->port); // Porta in 2 byte (little-endian)

        return $packet;
    }
    
    public function Build(string $opcode): string {
        $packet = $this->Build_Header();
        $packet .= $opcode;

        if ($opcode === Opcode::PING->value)
            $packet .= random_bytes(4); // Payload casuale per PING
        
        return $packet;
    }
    // ...
}
```

- **`pack()` per Formato Binario:** Utilizza la funzione `pack()` di PHP per convertire i dati (IP, porta, lunghezze delle stringhe) nel loro formato binario corretto, come `c4` per 4 byte di caratteri (IP) e `v` per intero senza segno a 16 bit (porta e lunghezze), che è *little-endian*.
- **Intestazione Standard:** `Build_Header()` crea l'intestazione 'SAMP' di 10 byte che precede tutti i pacchetti.
- **Struttura RCON:** `Build_Rcon` formatta il pacchetto RCON con l'opcode 'x' seguito dalla lunghezza della password, la password, la lunghezza del comando e il comando stesso.

### 10. `packet_parser.php`: Il Decodificatore di Pacchetti con Gestione della Codifica

La classe `Packet_Parser` è la controparte del `Packet_Builder`, responsabile dell'interpretazione delle risposte binarie ricevute dal server e della loro conversione in dati PHP strutturati.

```php
// ...
final class Packet_Parser {
    private int $offset = Protocol::MIN_PACKET_LENGTH; // Inizia dopo l'intestazione (11 byte)
    // ...
    public function __construct(private readonly string $data) {
        // Validazione iniziale dell'intestazione 'SAMP'
        if (substr($this->data, 0, 4) !== 'SAMP' || strlen($this->data) < Protocol::MIN_PACKET_LENGTH)
            throw new Malformed_Packet_Exception("Invalid response header or packet too short.");
    }
    
    private function Extract_String(int $length_bytes): string {
        // ... logica per leggere la lunghezza e la stringa ...
        $string = substr($this->data, $this->offset, $length);
        $this->offset += $length;
        
        // **CONVERSIONE DELLA CODIFICA CRITICA:** I server SA-MP/OMP usano Windows-1252
        return mb_convert_encoding($string, 'UTF-8', 'Windows-1252');
    }
    // ...
}
```

- **`offset` e `data_length`:** L'`offset` viene utilizzato per tracciare la posizione corrente durante la lettura del pacchetto, mentre `data_length` previene letture oltre i limiti del buffer.
- **Validazione dell'Intestazione:** Il costruttore convalida la "magic string" `SAMP` per rifiutare immediatamente i pacchetti malformati.
- **`Extract_String()` - Conversione della Codifica Cruciale:** Questa è una delle funzionalità più importanti. Il protocollo SA-MP trasmette le stringhe utilizzando la codifica **Windows-1252**. Per garantire che i caratteri speciali (come accenti o cirillici) vengano visualizzati correttamente nelle applicazioni PHP (che generalmente operano in UTF-8), viene applicato il metodo `mb_convert_encoding($string, 'UTF-8', 'Windows-1252')`.
- **Estrazione della Lunghezza Variabile:** Il metodo `Extract_String` supporta diverse dimensioni del prefisso di lunghezza per le stringhe (1, 2 o 4 byte), rendendolo flessibile per i vari campi del protocollo.

### 11. `samp-query.php`: La Classe Principale (L'Orchestratore Completo)

La classe `Samp_Query` è il punto di ingresso principale e l'orchestratore di tutte le operazioni. Lega insieme tutti i componenti, gestisce lo stato dell'interrogazione, la logica di *retries* e i *timeouts*.

#### Ciclo di Vita dell'Interrogazione: Il Viaggio di un Pacchetto

L'intero processo di interrogazione di un server segue una sequenza di passaggi attentamente orchestrati, mirando alla massima resilienza e prestazioni.

##### 1. Inizializzazione e Risoluzione del Dominio

Quando viene creata un'istanza di `Samp_Query`:
- **Validazione Rapida:** Il costruttore convalida i parametri `$hostname` e `$port`. Qualsiasi incoerenza risulta in una `Invalid_Argument_Exception`.
- **Pulizia della Cache DNS:** Viene invocato `Domain_Resolver::Clean_Expired_Cache()` per garantire che vengano considerate solo le voci DNS valide e non scadute.
- **Risoluzione IP:** Il `Domain_Resolver` viene utilizzato per convertire l'`$hostname` in un indirizzo IP (`$this->ip`). Questo IP viene memorizzato in cache su disco per richieste future, e viene lanciata una `Query_Exception` se la risoluzione fallisce.
- **Configurazione delle Risorse:** Vengono istanziati `Logger`, `Socket_Manager` e `Packet_Builder`, preparando l'infrastruttura per la comunicazione.

##### 2. `Fetch_Server_State()`: Cache e Interrogazione Critica di INFO/PING

Questo metodo interno è un guardiano delle prestazioni e della coerenza, garantendo che le informazioni di base del server (`Server_Info`) e il `ping` siano sempre aggiornati prima di qualsiasi interrogazione principale.

- **Cache Primaria (5 Secondi):** Prima di avviare qualsiasi comunicazione, si verifica se `$this->cached_info` (l'oggetto `Server_Info` del server) contiene dati con meno di 5 secondi di età (rispetto a `$this->last_successful_query`). Se i dati sono freschi, la funzione ritorna immediatamente, evitando traffico di rete non necessario.
- **Interrogazione Critica di INFO:** Se la cache è scaduta o vuota, viene invocato il metodo `Attempt_Query` per ottenere i dati `INFO`. Questa interrogazione è contrassegnata come **critica** (`true`), il che innesca un numero maggiore di tentativi e *timeouts* più generosi. Viene lanciata una `Connection_Exception` se la risposta INFO non è valida dopo tutti i tentativi.
- **Calcolo del Ping:** Se `$this->cached_ping` è ancora nullo, viene eseguita una rapida interrogazione `PING` (`Execute_Query_Phase` con `Performance::FAST_PING_TIMEOUT`). Il ping viene calcolato come il tempo trascorso fino alla **prima** risposta ricevuta, garantendo precisione.

##### 3. `Attempt_Query()`: La Strategia di Retries Ottimizzata

Questo è il cervello della resilienza della libreria, che gestisce il ciclo di tentativi di alto livello per uno o più `$jobs` (interrogazioni di opcode).

- **Cache delle Risposte (2 Secondi):** Innanzitutto, verifica se le risposte per uno qualsiasi dei `$jobs` sono già in `$this->response_cache` (con meno di 2.0 secondi). Ciò previene *retries* non necessari per dati volatili, ma non critici.
- **Fase di Retries Rapidi:** La libreria tenta prima `Query::FAST_RETRY_ATTEMPTS` (2 di default) con un *timeout* inferiore (`$timeout * 0.6`). L'obiettivo è ottenere una risposta il più rapidamente possibile, senza introdurre ritardi significativi.
- **Fase di Retries Standard con Backoff:** Se la fase rapida non è sufficiente, il ciclo continua con i restanti `Query::ATTEMPTS`. In questa fase, `$adjusted_timeout` aumenta progressivamente ad ogni tentativo, dando più tempo al server per rispondere. Ancora più importante, `usleep()` introduce un ritardo crescente (basato su `Query::RETRY_DELAY_MS` e un fattore di aumento) tra le chiamate a `Execute_Query_Phase`, consentendo alla rete e al server di stabilizzarsi.
- **Retries di Emergenza (per Interrogazioni Critiche):** Per i `$jobs` contrassegnati come `critical`, se tutti i tentativi precedenti falliscono, viene effettuato un *retry* finale per ogni job individualmente, utilizzando un *timeout* ancora maggiore (`$timeout * 2`). Questa è un'ultima possibilità per ottenere informazioni vitali.

##### 4. `Execute_Query_Phase()`: Il Motore di Comunicazione con Rilevamento del Ping

Questo metodo di basso livello è dove avviene l'interazione reale con il socket UDP. Gestisce l'invio e la ricezione di pacchetti for un gruppo di `$jobs` simultaneamente in una singola fase di rete.

```php
// ... (all'interno di Execute_Query_Phase)
private function Execute_Query_Phase(array $jobs, ?int &$ping, float $timeout): array {
    $socket = $this->socket_manager->Get_Socket_Resource($timeout);
    stream_set_blocking($socket, false); // Socket non bloccante

    // Invia i pacchetti due volte immediatamente per una maggiore garanzia di consegna UDP
    for ($i = 0; $i < 2; $i++) {
        foreach ($pending_jobs as $opcode)
            fwrite($socket, $this->packet_builder->Build($opcode->value));

        if ($i == 0)
            usleep(5000); // Piccolo ritardo
    }

    $last_send_time = microtime(true);
    $current_retry_interval = Performance::INITIAL_RETRY_INTERVAL; // 0.08s
    
    while (!empty($pending_jobs) && (microtime(true) - $start_time) < $timeout) {
        $now = microtime(true);

        if (($now - $last_send_time) > $current_retry_interval) { // Logica di reinvio con backoff
            // ... reinvia i pacchetti in sospeso ...
            $current_retry_interval = min($current_retry_interval * Performance::BACKOFF_FACTOR, 0.2); // Aumenta l'intervallo di retry
        }

        $read = [$socket]; $write = $except = null

        if (stream_select($read, $write, $except, 0, 10000) > 0) { // Attende i dati (max 10ms)
            $response = stream_socket_recvfrom($socket, Protocol::UDP_READ_BUFFER_SIZE);
            // ... logica di parsing, calcolo del ping e validazione ...
        }

        if (!empty($pending_jobs))
            usleep(1000); // Piccolo ritardo per evitare lo spin della CPU
    }
    return $phase_results;
}
```

- **Socket Non Bloccante:** `stream_set_blocking($socket, false)` è essenziale. Permette a PHP di inviare pacchetti e poi attendere le risposte senza bloccare l'esecuzione, usando `stream_select`.
- **Invio Doppio per Robustezza:** I pacchetti per tutti i `$pending_jobs` vengono inviati **due volte** consecutivamente (con un piccolo `usleep(5000)` tra di loro) all'inizio della fase. Questa pratica è fondamentale nei protocolli UDP per aumentare significativamente la probabilità di consegna in reti instabili o con perdita di pacchetti, mitigando la natura inaffidabile dell'UDP. Per `INFO` e `PING`, viene effettuato un terzo invio aggiuntivo durante i *retries* nel ciclo principale.
- **Ciclo di Ricezione con Backoff Adattivo:**
   - Un ciclo `while` principale continua fino a quando tutti i `$jobs` sono completati o il *timeout* della fase scade.
   - **Reinvio Dinamico:** Se il tempo trascorso dall'ultimo invio (`$now - $last_send_time`) supera `$current_retry_interval`, i pacchetti per i `$pending_jobs` vengono reinviati. Il `$current_retry_interval` viene quindi aumentato (`min($current_retry_interval * Performance::BACKOFF_FACTOR, 0.2)`), implementando un *backoff* esponenziale che evita il sovraccarico del server e massimizza le possibilità di una risposta.
   - **`stream_select` Ottimizzato:** `stream_select($read, $write, $except, 0, 10000)` viene utilizzato per attendere i dati per un massimo di 10 millisecondi. Ciò consente alla libreria di essere reattiva e di elaborare i pacchetti non appena arrivano.
   - **Misurazione Precisa del Ping:** Quando viene ricevuto il **primo** pacchetto valido (`$packets_received === 0`), il `ping` viene calcolato con alta precisione come la differenza tra `microtime(true)` all'inizio dell'invio della prima serie di pacchetti e il tempo esatto di ricezione del **primo** pacchetto valido.
- **Elaborazione e Validazione della Risposta:** Le risposte ricevute vengono decodificate dal `Packet_Parser`. Se viene rilevato un pacchetto con `Malformed_Packet_Exception`, viene registrato nel log e il pacchetto viene immediatamente reinviato al server per un nuovo tentativo. La risposta decodificata viene quindi convalidata da `Validate_Response()`. Se è valida, viene aggiunta a `$phase_results` e a `$this->response_cache`.

##### 5. `Validate_Response()`: Lo Strato di Validazione Semantica

Questo metodo cruciale, implementato nella classe `Samp_Query`, verifica l'integrità e la coerenza logica dei dati decodificati prima che vengano consegnati all'utente.

```php
// ... (all'interno di Validate_Response)
private function Validate_Response($data, Opcode $opcode): bool {
    switch ($opcode) {
        case Opcode::INFO:
            // Assicura che l'hostname non sia vuoto e che i numeri dei giocatori siano logici
            return $data instanceof Server_Info && $data->max_players > 0 && $data->players >= 0 && $data->players <= $data->max_players && !empty($data->hostname);
        
        case Opcode::RULES:
            if (!is_array($data))
                return false;

            // ...

            return true;
        // ... validazioni per PLAYERS_DETAILED, PLAYERS_BASIC, PING ...
    }
}
```

- **Validazione per Opcode:** Ogni `Opcode` ha la sua logica di validazione specifica. Per esempio:
   - Per `Opcode::INFO`: Assicura che `$data` sia un'istanza di `Server_Info`, che `$data->max_players > 0`, che `$data->players` sia compreso tra 0 e `max_players`, e che `$data->hostname` non sia vuoto.
   - Per `Opcode::RULES` o elenchi di giocatori: Verifica che il ritorno sia un `array` e, se non è vuoto, che il primo elemento sia dell'istanza del modello atteso (`Server_Rule`, `Players_Detailed`, ecc.).
- **Robustezza:** Se la validazione fallisce, la risposta è considerata non valida e viene scartata. Ciò costringe il sistema a continuare i tentativi, come se il pacchetto non fosse mai arrivato, proteggendo l'applicazione da dati corrotti o incoerenti del server.

#### Calcolo e Gestione del Timeout Adattivo

La libreria implementa una sofisticata strategia di *timeout* per bilanciare velocità e resilienza:

- **`Performance::METADATA_TIMEOUT`:** (0.8 secondi) È il *timeout* di base per interrogazioni rapide come `INFO` e `RULES`.
- **`Performance::PLAYER_LIST_BASE_TIMEOUT`:** (1.0 secondo) È il *timeout* di base per le interrogazioni dell'elenco dei giocatori.
- **`Performance::PING_MULTIPLIER`:** (2) Utilizzato per regolare il *timeout* in base al ping.
- **Regolazione per Ping:** Nel metodo `Fetch_Player_Data`, il *timeout* per ottenere l'elenco dei giocatori viene regolato dinamicamente:
   ```
   Timeout Giocatori = PLAYER_LIST_BASE_TIMEOUT + (Ping in Cache * PING_MULTIPLIER / 1000)
   ```
   Questo approccio consente ai server con alta latenza (ping elevato) di avere un *timeout* maggiore, aumentando le possibilità di successo nell'ottenere l'elenco completo dei giocatori, che può essere grande e richiedere tempo per essere elaborato dal server.
- **Limite di Timeout:** `min($timeout, 2.0)` viene utilizzato in diverse chiamate per imporre un limite massimo di 2.0 secondi per le interrogazioni sui metadati, prevenendo attese eccessive.

#### Metodi di Interrogazione Pubblica

| Metodo | Descrizione Dettagliata | Comportamento di Caching Interno |
| :--- | :--- | :--- |
| `Get_All()` | **Metodo consigliato per l'uso generale.** Orchestra l'ottenimento di `INFO`, `RULES`, `PLAYERS_DETAILED` (o `PLAYERS_BASIC` come fallback) in parallelo. Ciò minimizza il tempo totale dell'interrogazione, poiché i pacchetti vengono inviati quasi simultaneamente e le risposte vengono elaborate man mano che arrivano. Include una misurazione del tempo totale `execution_time_ms`. | Utilizza la cache di 2.0s (`$this->response_cache`) per ogni opcode interrogato all'interno della fase parallela. |
| `Is_Online()` | Esegue una rapida interrogazione `INFO` e restituisce `true` se il server risponde con un `Server_Info` valido entro il *timeout*, altrimenti `false`. È robusto, utilizzando *retries* critici. | Internamente, invoca `Fetch_Server_State()`, che usa la cache di 5.0s per `INFO`. |
| `Get_Ping()` | Restituisce il ping più recente del server in millisecondi. Se `cached_ping` è nullo, forza un'interrogazione `PING` dedicata con `Performance::FAST_PING_TIMEOUT` (0.3s) per ottenere una misurazione rapida. | Il `ping` viene memorizzato in cache e aggiornato ogni volta che `Execute_Query_Phase` riceve la prima risposta. |
| `Get_Info()` | Restituisce un oggetto `Server_Info` con dettagli come hostname, gamemode, numero di giocatori, ecc. | Invoca `Fetch_Server_State()`, che utilizza la cache di 5.0s. |
| `Get_Rules()` | Restituisce un `array` di oggetti `Server_Rule` contenente tutte le regole configurate sul server (es: `mapname`, `weburl`). Include *retries* aggiuntivi in caso di fallimento iniziale. | Utilizza la cache di 2.0s per l'`Opcode::RULES`. |
| `Get_Players_Detailed()` | Restituisce un `array` di oggetti `Players_Detailed` (id, nome, punteggio, ping per ogni giocatore). **Importante:** Questa interrogazione viene ignorata se il numero di giocatori sul server (`$this->cached_info->players`) è maggiore o uguale a `Query::LARGE_PLAYER_THRESHOLD` (150 di default), a causa del rischio di *timeout* prolungati o frammentazione dei pacchetti. | Utilizza la cache di 2.0s per `Opcode::PLAYERS_DETAILED`. |
| `Get_Players_Basic()` | Restituisce un `array` di oggetti `Players_Basic` (nome, punteggio per ogni giocatore). È più leggero dell'interrogazione dettagliata. Generalmente viene chiamata come *fallback* se `Get_Players_Detailed()` fallisce o viene ignorata. | Utilizza la cache di 2.0s per `Opcode::PLAYERS_BASIC`. |

#### Comunicazione RCON (`Send_Rcon`)

Il metodo `Send_Rcon(string $rcon_password, string $command)` permette di inviare comandi alla console remota del server.

1.  **Validazione degli Argomenti:** Lancia `Invalid_Argument_Exception` se la password o il comando sono vuoti.
2.  **Socket Isolato:** Crea una nuova istanza di `Socket_Manager` (`$rcon_socket_manager`) per la sessione RCON, isolandola dal socket di interrogazione principale per evitare interferenze.
3.  **Autenticazione (`varlist`):** Prima di inviare il comando effettivo, la libreria invia il comando "varlist" (fino a 3 tentativi) per autenticare la password RCON. Se `Send_Single_Rcon_Request` restituisce `null` o una risposta vuota, viene lanciata una `Rcon_Exception`, indicando un fallimento dell'autenticazione o che RCON non è abilitato.
4.  **Invio del Comando Effettivo:** Dopo l'autenticazione riuscita, viene inviato `$command`, anche questo con un massimo di 3 tentativi.
5.  **Gestione della Risposta:** `Packet_Parser::Parse_Rcon()` decodifica la risposta testuale da RCON. Se il server non restituisce una risposta testuale dopo tutti i tentativi, viene restituito un messaggio generico di successo.
6.  **Pulizia:** Il distruttore di `$rcon_socket_manager` garantisce che il socket RCON venga chiuso dopo l'operazione.

## Diagnostica degli Errori ed Eccezioni

La libreria utilizza una gerarchia di eccezioni personalizzate per una gestione degli errori pulita e prevedibile. In caso di fallimento, verrà lanciata una delle seguenti eccezioni.

### `Invalid_Argument_Exception`

**Causa:**
- **Hostname Vuoto:** L'`hostname` fornito nel costruttore di `Samp_Query` è una stringa vuota.
- **Porta Non Valida:** La `port` fornita nel costruttore è al di fuori dell'intervallo valido da 1 a 65535.
- **RCON:** La password RCON o il comando RCON forniti a `Send_Rcon` sono vuoti.

### `Connection_Exception`

**Causa:** Fallimento di rete o mancanza di una risposta essenziale.
- **Fallimento della Risoluzione del Dominio:** Il `Domain_Resolver` non riesce a convertire l'hostname in un IPv4 valido.
- **Fallimento nella Creazione del Socket:** Il `Socket_Manager` non riesce a creare o connettere il socket UDP.
- **Server Inaccessibile/Offline:** Il server non risponde con un pacchetto `INFO` valido dopo tutti i tentativi e i *timeout* (inclusi i *retries* di emergenza), indicando generalmente che il server è offline, l'IP/porta è errato, o un firewall sta bloccando la comunicazione.

### `Malformed_Packet_Exception`

**Causa:** Corruzione dei dati a livello di protocollo.
- **Intestazione Non Valida:** Il `Packet_Parser` rileva un pacchetto che non inizia con la "magic string" `SAMP` o ha una lunghezza totale insufficiente.
- **Struttura del Pacchetto Non Valida:** Il `Packet_Parser` riscontra incoerenze nella struttura binaria, come una lunghezza di stringa che punta al di fuori dei limiti del pacchetto.
- **Resilienza:** Questa eccezione viene spesso gestita internamente da `Execute_Query_Phase` per attivare un *retry* immediato, ma può essere propagata se il problema persiste.

### `Rcon_Exception`

**Causa:** Errore durante la comunicazione RCON.
- **Fallimento dell'Autenticazione RCON:** Il server non ha risposto all'autenticazione RCON (tramite il comando `varlist`) dopo 3 tentativi, suggerendo una password errata o che RCON è disabilitato sul server.
- **Fallimento nell'Invio del Comando RCON:** Il comando RCON effettivo non ha ottenuto risposta dopo 3 tentativi.

## Licenza

Copyright © **SA-MP Programming Community**

Questo software è concesso in licenza secondo i termini della Licenza MIT ("Licenza"); è possibile utilizzare questo software in conformità con le condizioni della Licenza. Una copia della Licenza può essere ottenuta su: [MIT License](https://opensource.org/licenses/MIT)

### Termini e Condizioni d'Uso

#### 1. Permessi Concessi

La presente licenza concede gratuitamente a chiunque ottenga una copia di questo software e dei file di documentazione associati i seguenti diritti:
* Utilizzare, copiare, modificare, unire, pubblicare, distribuire, sublicenziare e/o vendere copie del software senza restrizioni
* Permettere alle persone a cui il software viene fornito di fare lo stesso, soggetto alle seguenti condizioni

#### 2. Condizioni Obbligatorie

Tutte le copie o parti sostanziali del software devono includere:
* L'avviso di copyright sopra riportato
* Questo avviso di permesso
* L'avviso di esclusione di responsabilità sotto riportato

#### 3. Diritti d'Autore

Il software e tutta la documentazione associata sono protetti dalle leggi sul diritto d'autore. La **SA-MP Programming Community** mantiene la titolarità dei diritti d'autore originali del software.

#### 4. Esclusione di Garanzia e Limitazione di Responsabilità

IL SOFTWARE VIENE FORNITO "COSÌ COM'È", SENZA GARANZIE DI ALCUN TIPO, ESPLICITE O IMPLICITE, INCLUSE, MA NON LIMITATE A, LE GARANZIE DI COMMERCIABILITÀ, IDONEITÀ PER UN PARTICOLARE SCOPO E NON VIOLAZIONE.

IN NESSUN CASO GLI AUTORI O I TITOLARI DEL COPYRIGHT SARANNO RESPONSABILI PER QUALSIASI RECLAMO, DANNO O ALTRA RESPONSABILITÀ, SIA IN UN'AZIONE DI CONTRATTO, ILLECITO O ALTRO, DERIVANTE DA, FUORI O IN CONNESSIONE CON IL SOFTWARE O L'USO O ALTRE OPERAZIONI NEL SOFTWARE.

---

Per informazioni dettagliate sulla Licenza MIT, consultare: https://opensource.org/licenses/MIT