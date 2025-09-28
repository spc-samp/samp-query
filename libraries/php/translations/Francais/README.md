# SA-MP Query - PHP

<div align="center">

![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Architecture](https://img.shields.io/badge/Protocol-UDP-blueviolet?style=for-the-badge)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**Une bibliothèque PHP robuste et résiliente, conçue pour interroger l'état et les informations des serveurs SA-MP (San Andreas Multiplayer) et OMP (Open Multiplayer).**

</div>

## Langues

- Português: [README](../../)
- Deutsch: [README](../Deutsch/README.md)
- English: [README](../English/README.md)
- Español: [README](../Espanol/README.md)
- Italiano: [README](../Italiano/README.md)
- Polski: [README](../Polski/README.md)
- Русский: [README](../Русский/README.md)
- Svenska: [README](../Svenska/README.md)
- Türkçe: [README](../Turkce/README.md)

## Table des matières

- [SA-MP Query - PHP](#sa-mp-query---php)
  - [Langues](#langues)
  - [Table des matières](#table-des-matières)
  - [Aperçu Général](#aperçu-général)
  - [Principes de Conception et d'Architecture](#principes-de-conception-et-darchitecture)
    - [Architecture Modulaire](#architecture-modulaire)
    - [Résilience : Backoff, Retries et Caching](#résilience--backoff-retries-et-caching)
    - [Optimisation des Performances : Parallélisme et Adaptation du Timeout](#optimisation-des-performances--parallélisme-et-adaptation-du-timeout)
    - [Programmation Orientée Objet (POO) Moderne (PHP 8.1+)](#programmation-orientée-objet-poo-moderne-php-81)
  - [Prérequis](#prérequis)
  - [Installation et Utilisation de Base](#installation-et-utilisation-de-base)
    - [Initialisation de la Classe `Samp_Query`](#initialisation-de-la-classe-samp_query)
    - [`Get_All()` : Requête Complète et Optimisée](#get_all--requête-complète-et-optimisée)
    - [`Is_Online()` : Vérification Rapide du Statut](#is_online--vérification-rapide-du-statut)
    - [`Get_Ping()` : Obtention du Ping du Serveur](#get_ping--obtention-du-ping-du-serveur)
    - [`Get_Info()` : Détails Essentiels du Serveur](#get_info--détails-essentiels-du-serveur)
    - [`Get_Rules()` : Règles Configurées du Serveur](#get_rules--règles-configurées-du-serveur)
    - [`Get_Players_Detailed()` : Liste des Joueurs avec Détails](#get_players_detailed--liste-des-joueurs-avec-détails)
    - [`Get_Players_Basic()` : Liste Basique des Joueurs](#get_players_basic--liste-basique-des-joueurs)
    - [`Send_Rcon()` : Envoi de Commandes à Distance](#send_rcon--envoi-de-commandes-à-distance)
  - [Structure Détaillée de la Bibliothèque et Flux d'Exécution](#structure-détaillée-de-la-bibliothèque-et-flux-dexécution)
    - [1. `constants.php` : Le Cœur de la Configuration](#1-constantsphp--le-cœur-de-la-configuration)
    - [2. `opcode.php` : L'Enum des Opcodes du Protocole](#2-opcodephp--lenum-des-opcodes-du-protocole)
    - [3. `exceptions.php` : La Hiérarchie des Exceptions Personnalisées](#3-exceptionsphp--la-hiérarchie-des-exceptions-personnalisées)
    - [4. `server_types.php` : Les Modèles de Données Immuables](#4-server_typesphp--les-modèles-de-données-immuables)
    - [5. `autoloader.php` : Le Chargeur Automatique de Classes](#5-autoloaderphp--le-chargeur-automatique-de-classes)
    - [6. `logger.php` : Le Sous-système de Journalisation](#6-loggerphp--le-sous-système-de-journalisation)
    - [7. `domain_resolver.php` : La Résolution de Domaine avec Cache Persistant](#7-domain_resolverphp--la-résolution-de-domaine-avec-cache-persistant)
    - [8. `socket_manager.php` : Le Gestionnaire de Connexion UDP Robuste](#8-socket_managerphp--le-gestionnaire-de-connexion-udp-robuste)
    - [9. `packet_builder.php` : Le Constructeur de Paquets Binaires](#9-packet_builderphp--le-constructeur-de-paquets-binaires)
    - [10. `packet_parser.php` : Le Décodeur de Paquets avec Traitement de l'Encodage](#10-packet_parserphp--le-décodeur-de-paquets-avec-traitement-de-lencodage)
    - [11. `samp-query.php` : La Classe Principale (L'Orchestrateur Complet)](#11-samp-queryphp--la-classe-principale-lorchestrateur-complet)
      - [Cycle de Vie de la Requête : Le Parcours d'un Paquet](#cycle-de-vie-de-la-requête--le-parcours-dun-paquet)
        - [1. Initialisation et Résolution de Domaine](#1-initialisation-et-résolution-de-domaine)
        - [2. `Fetch_Server_State()` : Cache et Requête Critique d'INFO/PING](#2-fetch_server_state--cache-et-requête-critique-dinfoping)
        - [3. `Attempt_Query()` : La Stratégie de Retries Optimisée](#3-attempt_query--la-stratégie-de-retries-optimisée)
        - [4. `Execute_Query_Phase()` : Le Moteur de Communication avec Détection de Ping](#4-execute_query_phase--le-moteur-de-communication-avec-détection-de-ping)
        - [5. `Validate_Response()` : La Couche de Validation Sémantique](#5-validate_response--la-couche-de-validation-sémantique)
      - [Calcul et Gestion du Timeout Adaptatif](#calcul-et-gestion-du-timeout-adaptatif)
      - [Méthodes de Requête Publiques](#méthodes-de-requête-publiques)
      - [Communication RCON (`Send_Rcon`)](#communication-rcon-send_rcon)
  - [Diagnostic des Erreurs et Exceptions](#diagnostic-des-erreurs-et-exceptions)
    - [`Invalid_Argument_Exception`](#invalid_argument_exception)
    - [`Connection_Exception`](#connection_exception)
    - [`Malformed_Packet_Exception`](#malformed_packet_exception)
    - [`Rcon_Exception`](#rcon_exception)
  - [Licence](#licence)
    - [Conditions Générales d'Utilisation](#conditions-générales-dutilisation)
      - [1. Autorisations Accordées](#1-autorisations-accordées)
      - [2. Conditions Obligatoires](#2-conditions-obligatoires)
      - [3. Droits d'Auteur](#3-droits-dauteur)
      - [4. Exclusion de Garantie et Limitation de Responsabilité](#4-exclusion-de-garantie-et-limitation-de-responsabilité)

## Aperçu Général

La bibliothèque **SA-MP Query - PHP** est une solution haute performance et tolérante aux pannes pour les développeurs PHP qui ont besoin d'interagir avec des serveurs de jeu basés sur le protocole SA-MP/OMP (UDP). Son objectif est d'encapsuler la complexité du protocole de requête binaire dans une API PHP propre et intuitive, permettant aux applications web, aux lanceurs et aux utilitaires d'obtenir des informations détaillées sur l'état du serveur (joueurs, règles, ping, etc.) de manière rapide et fiable.

La conception de la bibliothèque se concentre sur trois piliers principaux : **Résilience**, **Performance** et **Modularité**. Elle est conçue pour gérer la nature non fiable du protocole UDP, en mettant en œuvre un système avancé de tentatives et de *backoff* pour garantir que les informations soient obtenues même dans des conditions de réseau défavorables ou sur des serveurs à forte latence.

## Principes de Conception et d'Architecture

### Architecture Modulaire

La bibliothèque est divisée en composants à responsabilité unique, chacun encapsulé dans sa propre classe et son propre fichier.

- **Infrastructure Réseau :** `Domain_Resolver`, `Socket_Manager`.
- **Protocole :** `Packet_Builder`, `Packet_Parser`, `Opcode` (Enum).
- **Logique Métier (Orchestration) :** `Samp_Query`.
- **Modèles de Données :** `Server_Info`, `Players_Detailed`, etc.

### Résilience : Backoff, Retries et Caching

Le protocole UDP ne garantit pas la livraison des paquets. La classe `Samp_Query` atténue cette défaillance avec un cycle de requête sophistiqué.

- **Multiples Tentatives Adaptatives :** La méthode `Attempt_Query` met en œuvre un cycle avec jusqu'à `Query::ATTEMPTS` (5 par défaut) et le double pour les requêtes critiques.
- **Stratégie de Backoff :** Le *backoff* exponentiel est implémenté dans `Execute_Query_Phase`. Après le premier envoi, l'intervalle des nouvelles tentatives d'écoute (boucle `while`) augmente de `Performance::INITIAL_RETRY_INTERVAL` (0.08s) par le `Performance::BACKOFF_FACTOR` (1.3), jusqu'à la limite de 0.2s. Cela évite la surcharge de paquets et augmente les chances d'une réponse à temps.
- **Caching des Réponses :** Les réponses récentes (valides pendant 2.0 secondes) sont stockées dans `response_cache`, éliminant la nécessité de répéter les requêtes de métadonnées lors de l'exécution de `Get_All()`.

### Optimisation des Performances : Parallélisme et Adaptation du Timeout

- **Requêtes Parallèles (Fan-out) :** La méthode `Get_All()` envoie des requêtes pour `INFO`, `RULES` et `PLAYERS` simultanément (dans `$jobs`), permettant aux réponses d'arriver dans le désordre, minimisant ainsi le temps d'attente total.
- **Caching DNS Persistant :** Le `Domain_Resolver` stocke l'adresse IP résolue dans un cache de fichier local avec un TTL de 3600 secondes, évitant les retards de résolution de domaine lors des appels ultérieurs.
- **Timeout Adaptatif :** Le *timeout* des requêtes de données volumineuses (comme la liste des joueurs) est ajusté dynamiquement en fonction du `cached_ping` du serveur :
   ```
   Timeout Ajusté = Timeout de Base + (Ping Caché * Multiplicateur de Ping / 1000)
   ```
   Cette logique (implémentée dans `Fetch_Player_Data`) garantit que les serveurs à haute latence disposent de suffisamment de temps pour répondre, sans compromettre la rapidité sur les serveurs à faible latence.

### Programmation Orientée Objet (POO) Moderne (PHP 8.1+)

La bibliothèque utilise des fonctionnalités modernes de PHP pour garantir la sécurité et la clarté :

- **Typage Strict** (`declare(strict_types=1)`).
- **Propriétés en Lecture Seule** (`public readonly` dans `Samp_Query` et les modèles de données) pour garantir l'immuabilité des données.
- **Enums Typés** (`enum Opcode: string`) pour un contrôle sécurisé du protocole.
- **Promotion des Propriétés du Constructeur** (dans `Samp_Query::__construct` et les modèles).

## Prérequis

- **PHP :** Version **8.1 ou supérieure**.
- **Extensions PHP :** `sockets` et `mbstring` (pour la manipulation de l'encodage UTF-8).

## Installation et Utilisation de Base

Pour commencer à utiliser la bibliothèque **SA-MP Query - PHP**, il suffit d'inclure le fichier `samp-query.php` dans votre projet. Ce fichier se chargera de charger automatiquement toutes les dépendances via son autoloader interne.

```php
<?php
// Incluez la classe principale. Elle se chargera de charger les dépendances via l'autoloader.
require_once 'path/to/samp-query/samp-query.php'; 

// Utilisez le namespace de la classe principale
use Samp_Query\Samp_Query;
// Incluez les exceptions pour un traitement d'erreur plus spécifique
use Samp_Query\Exceptions\Invalid_Argument_Exception;
use Samp_Query\Exceptions\Connection_Exception;
use Samp_Query\Exceptions\Rcon_Exception;

// Instanciez la classe Samp_Query, en l'enveloppant dans un bloc try-catch pour gérer les erreurs d'initialisation.
try {
    $server_query = new Samp_Query("play.example-samp-server.com", 7777);
    // Vous pouvez maintenant utiliser les méthodes publiques de $server_query
}
catch (Invalid_Argument_Exception $e) {
    echo "Erreur d'Argument : " . $e->getMessage() . "\n";
}
catch (Connection_Exception $e) {
    echo "Erreur de Connexion : " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Erreur inattendue lors de l'initialisation : " . $e->getMessage() . "\n";
}
```

### Initialisation de la Classe `Samp_Query`

La classe `Samp_Query` est la porte d'entrée de toutes les fonctionnalités. Son constructeur nécessite le `hostname` (ou l'adresse IP) et le `port` du serveur que vous souhaitez interroger.

```php
/**
 * Initialise une nouvelle instance de la bibliothèque Samp_Query.
 *
 * @param string $hostname Le nom d'hôte ou l'adresse IP du serveur SA-MP/OMP.
 * @param int $port Le port UDP du serveur (généralement 7777).
 * 
 * @throws Invalid_Argument_Exception Si le nom d'hôte est vide ou si le port est invalide.
 * @throws Connection_Exception Si la résolution DNS échoue ou si le socket ne peut pas être créé.
 */
public function __construct(public readonly string $hostname, public readonly int $port)
```

<br>

---

### `Get_All()` : Requête Complète et Optimisée

C'est la méthode la plus complète et recommandée. Elle exécute plusieurs requêtes (INFO, RULES, PLAYERS) en parallèle et de manière optimisée, minimisant le temps de réponse total et retournant un tableau associatif complet avec toutes les informations disponibles.

```php
/**
 * Retourne toutes les informations disponibles du serveur en un seul appel optimisé.
 * Inclut : is_online, ping, info (Server_Info), rules (tableau de Server_Rule),
 * players_detailed (tableau de Players_Detailed), players_basic (tableau de Players_Basic),
 * et execution_time_ms.
 *
 * @return array Un tableau associatif contenant toutes les informations du serveur.
 * 
 * @throws Connection_Exception Si la requête INFO, essentielle pour l'état du serveur, échoue.
 */
public function Get_All(): array
```

Exemple d'Utilisation :

```php
<?php
// ... (initialisation de la classe $server_query) ...

try {
    $data = $server_query->Get_All();
    
    if ($data['is_online']) {
        echo "## Serveur en Ligne : {$data['info']->hostname} ({$server_query->ip}:{$server_query->port}) ##\n";
        echo "Ping : {$data['ping']}ms | Temps Total de la Requête : {$data['execution_time_ms']}ms\n";
        echo "Joueurs : {$data['info']->players} / {$data['info']->max_players}\n";
        echo "Mode de Jeu : {$data['info']->gamemode}\n";
        echo "Langue : {$data['info']->language}\n";
        echo "Protégé par Mot de Passe : " . ($data['info']->password ? "Oui" : "Non") . "\n\n";

        // Exemple de liste de joueurs détaillée
        if (!empty($data['players_detailed'])) {
            echo "--- Joueurs Détaillés ({$data['info']->players} Actifs) ---\n";

            foreach ($data['players_detailed'] as $player)
                echo "ID : {$player->id}, Nom : {$player->name}, Ping : {$player->ping}ms, Score : {$player->score}\n";
        }
        elseif (!empty($data['players_basic'])) {
            echo "--- Joueurs Basiques ({$data['info']->players} Actifs) (Fallback) ---\n";

            foreach ($data['players_basic'] as $player)
                echo "Nom : {$player->name}, Score : {$player->score}\n";
        }
        else
            echo "Aucun joueur en ligne ou liste indisponible (peut-être trop de joueurs).\n";
        
        // Exemple de règles du serveur
        if (!empty($data['rules'])) {
            echo "\n--- Règles du Serveur ---\n";

            foreach ($data['rules'] as $rule)
                echo "{$rule->name} : {$rule->value}\n";
        }
    }
    else
        echo "Le serveur est actuellement hors ligne ou inaccessible.\n";
}
catch (Connection_Exception $e) {
    echo "Échec de connexion lors de la tentative d'obtention de toutes les informations : " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Erreur inattendue lors de la consultation de toutes les informations : " . $e->getMessage() . "\n";
}
```

<br>

---

### `Is_Online()` : Vérification Rapide du Statut

Vérifie si le serveur est en ligne et répond aux requêtes, sans chercher de détails supplémentaires. Idéal pour un simple "liveness check".

```php
/**
 * Vérifie si le serveur est en ligne et accessible.
 *
 * @return bool Retourne true si le serveur est en ligne et répond avec des INFO valides, false sinon.
 */
public function Is_Online(): bool
```

Exemple d'Utilisation :

```php
<?php
// ... (initialisation de la classe $server_query) ...

if ($server_query->Is_Online())
    echo "Le serveur SA-MP est en ligne et répond !\n";
else
    echo "Le serveur SA-MP est hors ligne ou n'a pas répondu à temps.\n";
```

<br>

---

### `Get_Ping()` : Obtention du Ping du Serveur

Retourne le temps de latence (ping) du serveur en millisecondes. Cette valeur est mise en cache en interne pour l'optimisation.

```php
/**
 * Retourne le ping actuel du serveur en millisecondes.
 * Si le ping n'a pas encore été calculé, une requête PING rapide sera exécutée.
 *
 * @return int|null La valeur du ping en millisecondes, ou null s'il n'est pas possible de l'obtenir.
 */
public function Get_Ping(): ?int
```

Exemple d'Utilisation :

```php
<?php
// ... (initialisation de la classe $server_query) ...

try {
    $ping = $server_query->Get_Ping();

    if ($ping !== null)
        echo "Le ping du serveur est de : {$ping}ms.\n";
    else
        echo "Impossible d'obtenir le ping du serveur.\n";
}
catch (Connection_Exception $e) {
    echo "Erreur lors de l'obtention du ping : " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Info()` : Détails Essentiels du Serveur

Obtient les informations de base du serveur, telles que le nom d'hôte, le mode de jeu, le nombre de joueurs, etc. Retourne un objet `Server_Info`.

```php
/**
 * Retourne les détails de base du serveur (nom d'hôte, joueurs, gamemode, etc.).
 * Les données sont mises en cache pour une courte période pour l'optimisation.
 *
 * @return Server_Info|null Un objet Server_Info, ou null si les informations ne peuvent pas être obtenues.
 */
public function Get_Info(): ?Server_Info
```

Exemple d'Utilisation :

```php
<?php
use Samp_Query\Models\Server_Info;
// ... (initialisation de la classe $server_query) ...

try {
    /** @var Server_Info|null $info */
    $info = $server_query->Get_Info();

    if ($info) {
        echo "--- Informations du Serveur ---\n";
        echo "Nom d'hôte : {$info->hostname}\n";
        echo "Gamemode : {$info->gamemode}\n";
        echo "Joueurs : {$info->players} / {$info->max_players}\n";
        echo "Langue : {$info->language}\n";
        echo "Protégé par Mot de Passe : " . ($info->password ? "Oui" : "Non") . "\n";
    }
    else
        echo "Impossible d'obtenir les informations du serveur.\n";
}
catch (Connection_Exception $e) {
    echo "Erreur lors de l'obtention des informations du serveur : " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Rules()` : Règles Configurées du Serveur

Récupère toutes les règles configurées sur le serveur, telles que `mapname`, `weburl`, `weather`, etc., en les retournant sous forme de tableau d'objets `Server_Rule`.

```php
/**
 * Retourne un tableau d'objets Server_Rule, chacun contenant le nom et la valeur d'une règle du serveur.
 * Les données sont mises en cache pour l'optimisation.
 *
 * @return array Un tableau de Samp_Query\Models\Server_Rule. Peut être vide s'il n'y a pas de règles.
 */
public function Get_Rules(): array
```

Exemple d'Utilisation :

```php
<?php
use Samp_Query\Models\Server_Rule;
// ... (initialisation de la classe $server_query) ...

try {
    $rules = $server_query->Get_Rules();

    if (!empty($rules)) {
        echo "--- Règles du Serveur ---\n";

        foreach ($rules as $rule)
            echo "{$rule->name} : {$rule->value}\n";

        // Exemple pour accéder à une règle spécifique :
        $mapname_rule = array_filter($rules, fn(Server_Rule $r) => $r->name === 'mapname');

        if (!empty($mapname_rule))
            echo "\nCarte actuelle : " . reset($mapname_rule)->value . "\n";
    }
    else
        echo "Aucune règle trouvée pour ce serveur.\n";
}
catch (Connection_Exception $e) {
    echo "Erreur lors de l'obtention des règles du serveur : " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Players_Detailed()` : Liste des Joueurs avec Détails

Obtient une liste détaillée des joueurs actuellement en ligne, incluant l'ID, le nom, le score et le ping.

> [!CAUTION]
> Pour optimiser les performances et éviter des paquets UDP excessivement volumineux qui peuvent être perdus ou fragmentés, cette méthode ne cherchera pas la liste détaillée des joueurs si le nombre total de joueurs est égal ou supérieur à `Query::LARGE_PLAYER_THRESHOLD` (150 par défaut). Dans ces cas, un tableau vide sera retourné. Envisagez d'utiliser `Get_Players_Basic()` comme solution de repli.

```php
/**
 * Retourne un tableau d'objets Players_Detailed (ID, nom, score, ping) pour chaque joueur en ligne.
 * Cette requête peut être ignorée si le nombre de joueurs est très élevé (voir Query::LARGE_PLAYER_THRESHOLD).
 *
 * @return array Un tableau de Samp_Query\Models\Players_Detailed. Peut être vide.
 */
public function Get_Players_Detailed(): array
```

Exemple d'Utilisation :

```php
<?php
use Samp_Query\Models\Players_Detailed;
// ... (initialisation de la classe $server_query) ...

try {
    $players_detailed = $server_query->Get_Players_Detailed();

    if (!empty($players_detailed)) {
        echo "--- Joueurs en Ligne (Détaillé) ---\n";

        foreach ($players_detailed as $player)
            echo "ID : {$player->id}, Nom : {$player->name}, Score : {$player->score}, Ping : {$player->ping}ms\n";
    }
    else
        echo "Aucun joueur en ligne ou liste détaillée indisponible.\n";
}
catch (Connection_Exception $e) {
    echo "Erreur lors de l'obtention de la liste détaillée des joueurs : " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Players_Basic()` : Liste Basique des Joueurs

Fournit une liste plus légère de joueurs, contenant uniquement le nom et le score. Utile comme alternative lorsque la liste détaillée n'est pas disponible ou pour réduire la charge de données.

```php
/**
 * Retourne un tableau d'objets Players_Basic (nom, score) pour chaque joueur en ligne.
 * Utile comme alternative plus légère ou solution de repli lorsque Get_Players_Detailed() n'est pas viable.
 *
 * @return array Un tableau de Samp_Query\Models\Players_Basic. Peut être vide.
 */
public function Get_Players_Basic(): array
```

Exemple d'Utilisation :

```php
<?php
use Samp_Query\Models\Players_Basic;
// ... (initialisation de la classe $server_query) ...

try {
    $players_basic = $server_query->Get_Players_Basic();

    if (!empty($players_basic)) {
        echo "--- Joueurs en Ligne (Basique) ---\n";

        foreach ($players_basic as $player)
            echo "Nom : {$player->name}, Score : {$player->score}\n";
    }
    else
        echo "Aucun joueur en ligne ou liste basique indisponible.\n";
}
catch (Connection_Exception $e) {
    echo "Erreur lors de l'obtention de la liste basique des joueurs : " . $e->getMessage() . "\n";
}
```

<br>

---

### `Send_Rcon()` : Envoi de Commandes à Distance

Permet d'envoyer des commandes à la console RCON du serveur, comme changer les règles, bannir des joueurs, etc. Requiert le mot de passe RCON du serveur.

> [!WARNING]
> La fonction RCON est sensible et peut entraîner des modifications sur le serveur. Utilisez-la avec prudence et uniquement avec des mots de passe de confiance.
> Il est crucial que le mot de passe RCON soit **correct** et que le RCON soit **activé** sur le serveur (configuration `rcon_password` dans `server.cfg`).

```php
/**
 * Envoie une commande RCON au serveur.
 * Effectue l'authentification avec 'varlist' et envoie la commande.
 *
 * @param string $rcon_password Le mot de passe RCON du serveur.
 * @param string $command La commande à exécuter (ex: "gmx", "kick ID").
 * @return string La réponse du serveur à la commande RCON, ou un message de statut.
 * 
 * @throws Invalid_Argument_Exception Si le mot de passe ou la commande RCON est vide.
 * @throws Rcon_Exception Si l'authentification RCON échoue ou si la commande n'obtient pas de réponse.
 * @throws Connection_Exception En cas d'échec de connexion pendant l'opération RCON.
 */
public function Send_Rcon(string $rcon_password, string $command): string
```

Exemple d'Utilisation :

```php
<?php
// ... (initialisation de la classe $server_query) ...

$rcon_password = "votre_mot_de_passe_secret_ici"; 
$command_to_send = "gmx"; // Exemple : redémarrer le gamemode

try {
    echo "Tentative d'envoi de la commande RCON : '{$command_to_send}'...\n";
    $response = $server_query->Send_Rcon($rcon_password, $command_to_send);
    echo "Réponse du RCON : {$response}\n";

    // Exemple de commande pour "dire" quelque chose sur le serveur (requiert RCON)
    $response_say = $server_query->Send_Rcon($rcon_password, "say Bonjour depuis mon script PHP !");
    echo "Réponse du RCON (say) : {$response_say}\n";
}
catch (Invalid_Argument_Exception $e) {
    echo "Erreur RCON (Argument Invalide) : " . $e->getMessage() . "\n";
}
catch (Rcon_Exception $e) {
    echo "Erreur RCON : Échec d'authentification ou commande non exécutée. " . $e->getMessage() . "\n";
}
catch (Connection_Exception $e) {
    echo "Erreur RCON (Connexion) : " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Erreur inattendue lors de l'envoi RCON : " . $e->getMessage() . "\n";
}
```

## Structure Détaillée de la Bibliothèque et Flux d'Exécution

La bibliothèque **SA-MP Query - PHP** est méticuleusement organisée en plusieurs fichiers, chacun avec une responsabilité bien définie. Cette section explore chaque composant en détail, révélant les décisions de conception et la logique sous-jacente.

### 1. `constants.php` : Le Cœur de la Configuration

Ce fichier centralise tous les paramètres de configuration "magiques" de la bibliothèque, garantissant que des aspects tels que les *timeouts*, le nombre de tentatives et les tailles de buffer soient facilement ajustables et cohérents dans tout le projet.

```php
// ...
final class Query {
    public const ATTEMPTS = 5; // Nombre maximum de tentatives de requête
    public const LARGE_PLAYER_THRESHOLD = 150; // Limite de joueurs pour la requête détaillée
}

final class Protocol {
    public const UDP_READ_BUFFER_SIZE = 32768; // 32KB pour le buffer de lecture
    public const KERNEL_RECEIVE_BUFFER_SIZE = 4194304; // 4MB pour le buffer du noyau
}
// ...
```

- **Classes Finales et Constantes :** Les classes sont `final` et les propriétés sont `public const`, garantissant l'immuabilité et l'accessibilité globale au moment de la compilation.
- **Granularité et Sémantique :** Les constantes sont catégorisées par leur domaine (`Query`, `Protocol`, `Performance`, `Logger`, `DNS`), facilitant la compréhension et la maintenance. Par exemple, `Query::LARGE_PLAYER_THRESHOLD` définit le point à partir duquel la recherche de listes détaillées de joueurs peut être évitée pour l'optimisation, en raison du volume de données et du potentiel de *timeouts*.

### 2. `opcode.php` : L'Enum des Opcodes du Protocole

Ce fichier définit les codes d'opération (opcodes) utilisés pour les différentes requêtes au serveur SA-MP/OMP, en les encapsulant dans un `enum` typé.

```php
// ...
enum Opcode: string {
    case INFO = 'i';
    case RULES = 'r';
    case PLAYERS_DETAILED = 'd';
    // ...
}
```

- **`Enum` Typé (PHP 8.1+) :** L'utilisation d'un `enum` (`Opcode: string`) avec des valeurs de type `string` garantit que les opcodes sont toujours valides et que le code est sémantiquement clair. Cela remplace l'utilisation de chaînes littérales "magiques", rendant le code plus lisible et moins sujet aux erreurs de frappe.

### 3. `exceptions.php` : La Hiérarchie des Exceptions Personnalisées

Ce fichier établit une hiérarchie d'exceptions personnalisées, permettant un traitement d'erreurs plus granulaire et spécifique pour les divers types de défaillances pouvant survenir dans la bibliothèque.

```php
// ...
class Query_Exception extends \Exception {}
class Invalid_Argument_Exception extends Query_Exception {}
class Connection_Exception extends Query_Exception {}
// ...
```

- **Héritage de `\Exception` :** Toutes les exceptions héritent de `Query_Exception` (qui à son tour étend `\Exception`), permettant de capturer des groupes d'erreurs (`Connection_Exception` et `Timeout_Exception` sont plus spécifiques que `Query_Exception`) ou toutes les exceptions de la bibliothèque avec un `catch` plus générique.
- **Clarté dans le Diagnostic :** Les noms descriptifs des exceptions facilitent le diagnostic et la récupération des erreurs dans l'application cliente.

### 4. `server_types.php` : Les Modèles de Données Immuables

Ce fichier définit les classes qui représentent les modèles de données pour les informations retournées par le serveur, garantissant l'intégrité des données grâce à l'immuabilité.

```php
// ...
final class Server_Info {
    public function __construct(
        public readonly bool $password,
        public readonly int $players,
        // ... autres propriétés readonly ...
    ) {}
}
// ...
```

- **`final class` :** Les classes sont `final`, empêchant les extensions et garantissant leur structure et leur comportement.
- **Propriétés `public readonly` (PHP 8.1+) :** Toutes les propriétés sont déclarées comme `public readonly`. Cela signifie qu'une fois l'objet construit, ses valeurs ne peuvent pas être modifiées, garantissant l'intégrité des données reçues du serveur.
- **Promotion des Propriétés du Constructeur (PHP 8.1+) :** Les propriétés sont déclarées directement dans le constructeur, simplifiant le code et réduisant le *boilerplate*.

### 5. `autoloader.php` : Le Chargeur Automatique de Classes

Ce fichier est responsable du chargement dynamique des classes de la bibliothèque lorsqu'elles sont nécessaires, en suivant la norme PSR-4.

```php
// ...
spl_autoload_register(function ($class) {
    $prefix = 'Samp_Query\\';
    $base_dir = __DIR__ . '/../modules/'; // Mappe le namespace au répertoire
    // ... logique de chargement ...
});

// Inclut les fichiers essentiels qui ne sont pas des classes, ou qui nécessitent un chargement anticipé
require_once __DIR__ . '/../modules/constants.php';
require_once __DIR__ . '/../modules/exceptions.php';
// ...
```

- **`spl_autoload_register()` :** Enregistre une fonction anonyme qui est appelée automatiquement par PHP lorsqu'une classe non définie est référencée, accélérant le développement et la maintenance.
- **Inclusion Directe des Configurations :** Des fichiers comme `constants.php` et `exceptions.php` sont inclus directement. Cela garantit que leurs définitions sont disponibles avant que toute classe qui les utilise ne soit instanciée par l'autoloader.

### 6. `logger.php` : Le Sous-système de Journalisation

La classe `Logger` fournit un mécanisme simple pour enregistrer les messages d'erreur et les événements importants dans un fichier journal, utile pour le débogage et la surveillance.

```php
// ...
final class Logger {
    public function Log(string $message): void {
        if (is_file(Logger_Config::FILE) && filesize(Logger_Config::FILE) > Logger_Config::MAX_SIZE_BYTES)
            unlink(Logger_Config::FILE); // Supprime le journal s'il dépasse la taille

        $timestamp = date('Y-m-d H:i:s');
        $log_entry = "[{$timestamp}] {$message}" . PHP_EOL;
        
        file_put_contents(Logger_Config::FILE, $log_entry, FILE_APPEND | LOCK_EX); // Ajoute avec verrouillage
    }
}
```

- **Nettoyage Automatique :** Le logger vérifie la taille du fichier journal (`Logger_Config::FILE`). S'il dépasse `Logger_Config::MAX_SIZE_BYTES` (10 Mo par défaut), le fichier est supprimé pour éviter qu'il ne grossisse indéfiniment.
- **Verrouillage de Fichier (`LOCK_EX`) :** `file_put_contents` utilise `LOCK_EX` pour garantir qu'un seul processus écrit dans le fichier journal à la fois, prévenant la corruption dans les environnements multi-thread/multi-processus.

### 7. `domain_resolver.php` : La Résolution de Domaine avec Cache Persistant

La classe `Domain_Resolver` est responsable de la conversion des noms d'hôte (comme `play.example.com`) en adresses IP (comme `192.0.2.1`). Elle implémente un système de cache sur disque pour optimiser les performances.

```php
// ...
final class Domain_Resolver {
    public function Resolve(string $hostname): string {
        if (filter_var($hostname, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
            return $hostname; // C'est déjà une IP

        $cache_file = DNS_Config::CACHE_DIR . md5($hostname) . '.cache';

        if (file_exists($cache_file) && (time() - filemtime($cache_file)) < DNS_Config::CACHE_TTL_SECONDS) {
            $ip = file_get_contents($cache_file);

            if ($ip && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
                return $ip;
        }
        
        $ip = gethostbyname($hostname); // Résolution DNS réelle
        // ... logique de validation et de sauvegarde en cache ...

        return $ip;
    }
    // ...
}
```

- **Cache Persistant :** Avant d'appeler `gethostbyname()`, il vérifie si l'IP est déjà stockée dans un fichier cache (`dns/` + hash MD5 du nom d'hôte). Le cache est considéré comme valide s'il n'a pas dépassé `DNS_Config::CACHE_TTL_SECONDS` (3600 secondes ou 1 heure par défaut).
- **Validation Robuste :** L'IP résolue (ou lue depuis le cache) est validée avec `filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)` pour garantir qu'il s'agit d'une IPv4 valide. Si la résolution échoue, une `Query_Exception` est levée.

### 8. `socket_manager.php` : Le Gestionnaire de Connexion UDP Robuste

La classe `Socket_Manager` encapsule la complexité de la création, de la configuration et de la gestion d'un socket UDP pour la communication avec le serveur de jeu.

```php
// ...
final class Socket_Manager {
    private $socket = null;
    // ...
    private function Connect(): void {
        if (is_resource($this->socket))
            return;

        $context = stream_context_create([
            'socket' => ['so_rcvbuf' => Protocol::KERNEL_RECEIVE_BUFFER_SIZE] // Augmente le buffer à 4MB
        ]);

        $this->socket = stream_socket_client(
            "udp://{$this->host}:{$this->port}", 
            $err_no, $err_str, 
            (float)ini_get("default_socket_timeout"), 
            STREAM_CLIENT_CONNECT, // Connecte le socket à l'adresse distante
            $context
        );

        if ($this->socket === false)
            throw new Connection_Exception("Could not create socket...");
    }
    // ...
}
```

- **`stream_socket_client` avec `STREAM_CLIENT_CONNECT` :** Ce *flag* indique au système d'exploitation de "connecter" le socket UDP à l'adresse distante. Bien que l'UDP soit sans connexion, "connecter" le socket permet des optimisations de performances au niveau du noyau, comme ne pas avoir besoin de spécifier l'adresse distante à chaque appel `fwrite` ou `stream_socket_recvfrom`, ce qui entraîne une surcharge moindre.
- **Buffer de Réception du Noyau :** `stream_context_create` est utilisé pour augmenter la taille du buffer de réception du noyau (`so_rcvbuf`) à `Protocol::KERNEL_RECEIVE_BUFFER_SIZE` (4 Mo). Ceci est crucial pour éviter la perte de paquets (débordement du buffer) lors de la réception de réponses volumineuses, comme les listes de joueurs détaillées de serveurs très fréquentés.
- **RAII via `__destruct` :** La méthode `Disconnect()` est invoquée automatiquement dans le destructeur (`__destruct`), garantissant que le socket soit fermé et les ressources libérées, même en cas d'exceptions.
- **Timeout Dynamique :** `Set_Timeout` ajuste avec précision les timeouts de lecture/écriture du socket en utilisant `stream_set_timeout`, ce qui est fondamental pour la logique de *retries* et de *backoff*.

### 9. `packet_builder.php` : Le Constructeur de Paquets Binaires

La classe `Packet_Builder` est responsable de la sérialisation des données de la requête dans un format binaire spécifique que le serveur SA-MP/OMP peut comprendre.

```php
// ...
final class Packet_Builder {
    // ...
    private function Build_Header(): string {
        $packet = 'SAMP';
        $packet .= pack('c4', ...$this->ip_parts); // IP en 4 octets
        $packet .= pack('v', $this->port); // Port en 2 octets (little-endian)

        return $packet;
    }
    
    public function Build(string $opcode): string {
        $packet = $this->Build_Header();
        $packet .= $opcode;

        if ($opcode === Opcode::PING->value)
            $packet .= random_bytes(4); // Charge utile aléatoire pour PING
        
        return $packet;
    }
    // ...
}
```

- **`pack()` pour le Format Binaire :** Utilise la fonction `pack()` de PHP pour convertir les données (IP, port, longueurs de chaîne) dans leur format binaire correct, comme `c4` pour 4 octets de caractères (IP) et `v` pour un entier non signé de 16 bits (port et longueurs), qui est en *little-endian*.
- **En-tête Standard :** Le `Build_Header()` crée l'en-tête 'SAMP' de 10 octets qui précède tous les paquets.
- **Structure RCON :** Le `Build_Rcon` formate le paquet RCON avec l'opcode 'x' suivi de la longueur du mot de passe, du mot de passe, de la longueur de la commande et de la commande elle-même.

### 10. `packet_parser.php` : Le Décodeur de Paquets avec Traitement de l'Encodage

La classe `Packet_Parser` est la contrepartie du `Packet_Builder`, responsable de l'interprétation des réponses binaires reçues du serveur et de leur conversion en données PHP structurées.

```php
// ...
final class Packet_Parser {
    private int $offset = Protocol::MIN_PACKET_LENGTH; // Commence après l'en-tête (11 octets)
    // ...
    public function __construct(private readonly string $data) {
        // Validation initiale de l'en-tête 'SAMP'
        if (substr($this->data, 0, 4) !== 'SAMP' || strlen($this->data) < Protocol::MIN_PACKET_LENGTH)
            throw new Malformed_Packet_Exception("Invalid response header or packet too short.");
    }
    
    private function Extract_String(int $length_bytes): string {
        // ... logique pour lire la longueur et la chaîne ...
        $string = substr($this->data, $this->offset, $length);
        $this->offset += $length;
        
        // **CONVERSION D'ENCODAGE CRITIQUE :** Les serveurs SA-MP/OMP utilisent Windows-1252
        return mb_convert_encoding($string, 'UTF-8', 'Windows-1252');
    }
    // ...
}
```

- **`offset` et `data_length` :** `offset` est utilisé pour suivre la position actuelle dans la lecture du paquet, tandis que `data_length` prévient les lectures hors des limites du buffer.
- **Validation de l'En-tête :** Le constructeur valide la "chaîne magique" `SAMP` pour rejeter immédiatement les paquets malformés.
- **`Extract_String()` - Conversion d'Encodage Cruciale :** C'est l'une des fonctionnalités les plus importantes. Le protocole SA-MP transmet les chaînes en utilisant l'encodage **Windows-1252**. Pour garantir que les caractères spéciaux (comme les accents ou les caractères cyrilliques) s'affichent correctement dans les applications PHP (qui fonctionnent généralement en UTF-8), la méthode `mb_convert_encoding($string, 'UTF-8', 'Windows-1252')` est appliquée.
- **Extraction de Longueur Variable :** La méthode `Extract_String` prend en charge différentes tailles de préfixe de longueur pour les chaînes (1, 2 ou 4 octets), ce qui la rend flexible pour divers champs du protocole.

### 11. `samp-query.php` : La Classe Principale (L'Orchestrateur Complet)

La classe `Samp_Query` est le point d'entrée principal et l'orchestrateur de toutes les opérations. Elle lie tous les composants, gère l'état de la requête, la logique de *retries* et les *timeouts*.

#### Cycle de Vie de la Requête : Le Parcours d'un Paquet

L'ensemble du processus de requête à un serveur suit une séquence d'étapes soigneusement orchestrées, visant une résilience et des performances maximales.

##### 1. Initialisation et Résolution de Domaine

Lorsqu'une instance de `Samp_Query` est créée :
- **Validation Rapide :** Le constructeur valide les paramètres `$hostname` et `$port`. Toute incohérence entraîne une `Invalid_Argument_Exception`.
- **Nettoyage du Cache DNS :** `Domain_Resolver::Clean_Expired_Cache()` est invoqué pour garantir que seules les entrées DNS valides et non expirées sont prises en compte.
- **Résolution d'IP :** Le `Domain_Resolver` est utilisé pour convertir le `$hostname` en une adresse IP (`$this->ip`). Cette IP est mise en cache sur disque pour les requêtes futures, et une `Query_Exception` est levée si la résolution échoue.
- **Configuration des Ressources :** Le `Logger`, le `Socket_Manager` et le `Packet_Builder` sont instanciés, préparant l'infrastructure pour la communication.

##### 2. `Fetch_Server_State()` : Cache et Requête Critique d'INFO/PING

Cette méthode interne est un gardien des performances et de la cohérence, garantissant que les informations de base du serveur (`Server_Info`) et le `ping` sont toujours à jour avant toute requête principale.

- **Cache Primaire (5 Secondes) :** Avant de commencer toute communication, il est vérifié si `$this->cached_info` (l'objet `Server_Info` du serveur) contient des données de moins de 5 secondes (par rapport à `$this->last_successful_query`). Si les données sont fraîches, la fonction retourne immédiatement, évitant un trafic réseau inutile.
- **Requête Critique d'INFO :** Si le cache est expiré ou vide, la méthode `Attempt_Query` est invoquée pour obtenir les données `INFO`. Cette requête est marquée comme **critique** (`true`), ce qui déclenche un plus grand nombre de tentatives et des *timeouts* plus généreux. Une `Connection_Exception` est levée si la réponse INFO est invalide après toutes les tentatives.
- **Calcul du Ping :** Si `$this->cached_ping` est encore nul, une requête `PING` rapide (`Execute_Query_Phase` avec `Performance::FAST_PING_TIMEOUT`) est effectuée. Le ping est calculé comme le temps écoulé jusqu'à la **première** réponse reçue, garantissant la précision.

##### 3. `Attempt_Query()` : La Stratégie de Retries Optimisée

C'est le cerveau de la résilience de la bibliothèque, gérant le cycle de tentatives de haut niveau pour un ou plusieurs `$jobs` (requêtes d'opcodes).

- **Cache de Réponses (2 Secondes) :** D'abord, il vérifie si les réponses pour l'un des `$jobs` sont déjà dans `$this->response_cache` (avec moins de 2.0 secondes). Cela prévient les *retries* inutiles pour des données volatiles, mais non critiques.
- **Phase de Retries Rapides :** La bibliothèque tente d'abord `Query::FAST_RETRY_ATTEMPTS` (2 par défaut) avec un *timeout* plus court (`$timeout * 0.6`). L'objectif est d'obtenir une réponse le plus rapidement possible, sans introduire de retards significatifs.
- **Phase de Retries Standard avec Backoff :** Si la phase rapide n'est pas suffisante, le cycle continue avec le reste des `Query::ATTEMPTS`. Dans cette phase, le `$adjusted_timeout` augmente progressivement à chaque tentative, donnant plus de temps au serveur pour répondre. Plus important encore, `usleep()` introduit un délai croissant (basé sur `Query::RETRY_DELAY_MS` et un facteur d'augmentation) entre les appels à `Execute_Query_Phase`, permettant au réseau et au serveur de se stabiliser.
- **Retries d'Urgence (pour les Requêtes Critiques) :** Pour les `$jobs` marqués comme `critical`, si toutes les tentatives précédentes échouent, une dernière tentative est faite pour chaque job individuellement, en utilisant un *timeout* encore plus long (`$timeout * 2`). C'est une dernière chance d'obtenir des informations vitales.

##### 4. `Execute_Query_Phase()` : Le Moteur de Communication avec Détection de Ping

Cette méthode de bas niveau est l'endroit où l'interaction réelle avec le socket UDP a lieu. Elle gère l'envoi et la réception de paquets pour un groupe de `$jobs` simultanément en une seule phase réseau.

```php
// ... (à l'intérieur de Execute_Query_Phase)
private function Execute_Query_Phase(array $jobs, ?int &$ping, float $timeout): array {
    $socket = $this->socket_manager->Get_Socket_Resource($timeout);
    stream_set_blocking($socket, false); // Socket non bloquant

    // Envoie les paquets deux fois immédiatement pour une meilleure garantie de livraison UDP
    for ($i = 0; $i < 2; $i++) {
        foreach ($pending_jobs as $opcode)
            fwrite($socket, $this->packet_builder->Build($opcode->value));

        if ($i == 0)
            usleep(5000); // Petit délai
    }

    $last_send_time = microtime(true);
    $current_retry_interval = Performance::INITIAL_RETRY_INTERVAL; // 0.08s
    
    while (!empty($pending_jobs) && (microtime(true) - $start_time) < $timeout) {
        $now = microtime(true);

        if (($now - $last_send_time) > $current_retry_interval) { // Logique de renvoi avec backoff
            // ... renvoie les paquets en attente ...
            $current_retry_interval = min($current_retry_interval * Performance::BACKOFF_FACTOR, 0.2); // Augmente l'intervalle de retry
        }

        $read = [$socket]; $write = $except = null

        if (stream_select($read, $write, $except, 0, 10000) > 0) { // Attend les données (max 10ms)
            $response = stream_socket_recvfrom($socket, Protocol::UDP_READ_BUFFER_SIZE);
            // ... logique de parsing, calcul de ping et validation ...
        }

        if (!empty($pending_jobs))
            usleep(1000); // Petit délai pour éviter le spin CPU
    }
    return $phase_results;
}
```

- **Socket Non-Bloquant :** `stream_set_blocking($socket, false)` est essentiel. Il permet à PHP d'envoyer des paquets puis d'attendre des réponses sans bloquer l'exécution, en utilisant `stream_select`.
- **Envoi Double pour la Robustesse :** Les paquets pour tous les `$pending_jobs` sont envoyés **deux fois** consécutivement (avec un petit `usleep(5000)` entre eux) au début de la phase. Cette pratique est fondamentale dans les protocoles UDP pour augmenter significativement la probabilité de livraison sur des réseaux instables ou avec perte de paquets, atténuant la nature non fiable de l'UDP. Pour `INFO` et `PING`, un troisième envoi supplémentaire est effectué pendant les *retries* dans la boucle principale.
- **Boucle de Réception avec Backoff Adaptatif :**
   - Une boucle `while` principale continue jusqu'à ce que tous les `$jobs` soient terminés ou que le *timeout* de la phase expire.
   - **Renvoi Dynamique :** Si le temps écoulé depuis le dernier envoi (`$now - $last_send_time`) dépasse le `$current_retry_interval`, les paquets pour les `$pending_jobs` sont renvoyés. Le `$current_retry_interval` est ensuite augmenté (`min($current_retry_interval * Performance::BACKOFF_FACTOR, 0.2)`), implémentant un *backoff* exponentiel qui évite la surcharge du serveur et maximise les chances d'une réponse.
   - **`stream_select` Optimisé :** `stream_select($read, $write, $except, 0, 10000)` est utilisé pour attendre des données pendant au maximum 10 millisecondes. Cela permet à la bibliothèque d'être réactive et de traiter les paquets dès leur arrivée.
   - **Mesure Précise du Ping :** Lorsque le **premier** paquet valide est reçu (`$packets_received === 0`), le `ping` est calculé avec une grande précision comme la différence entre `microtime(true)` au début de l'envoi de la première vague de paquets et l'heure exacte de réception du **premier** paquet valide.
- **Traitement et Validation de la Réponse :** Les réponses reçues sont décodées par le `Packet_Parser`. Si un paquet `Malformed_Packet_Exception` est détecté, il est journalisé, et le paquet est immédiatement renvoyé au serveur pour une nouvelle tentative. La réponse décodée est ensuite validée par `Validate_Response()`. Si elle est valide, elle est ajoutée aux `$phase_results` et au `$this->response_cache`.

##### 5. `Validate_Response()` : La Couche de Validation Sémantique

Cette méthode cruciale, implémentée dans la classe `Samp_Query`, vérifie l'intégrité et la cohérence logique des données décodées avant d'être livrées à l'utilisateur.

```php
// ... (à l'intérieur de Validate_Response)
private function Validate_Response($data, Opcode $opcode): bool {
    switch ($opcode) {
        case Opcode::INFO:
            // S'assure que le nom d'hôte n'est pas vide et que les nombres de joueurs sont logiques
            return $data instanceof Server_Info && $data->max_players > 0 && $data->players >= 0 && $data->players <= $data->max_players && !empty($data->hostname);
        
        case Opcode::RULES:
            if (!is_array($data))
                return false;

            // ...

            return true;
        // ... validations pour PLAYERS_DETAILED, PLAYERS_BASIC, PING ...
    }
}
```

- **Validation par Opcode :** Chaque `Opcode` a sa propre logique de validation spécifique. Par exemple :
   - Pour `Opcode::INFO` : S'assure que `$data` est une instance de `Server_Info`, que `$data->max_players > 0`, que `$data->players` est entre 0 et `max_players`, et que `$data->hostname` n'est pas vide.
   - Pour `Opcode::RULES` ou les listes de joueurs : Vérifie si le retour est un `array` et, s'il n'est pas vide, si le premier élément est de l'instance de modèle attendue (`Server_Rule`, `Players_Detailed`, etc.).
- **Robustesse :** Si la validation échoue, la réponse est considérée comme invalide et est rejetée. Cela force le système à continuer les tentatives, comme si le paquet n'était jamais arrivé, protégeant l'application contre des données corrompues ou incohérentes du serveur.

#### Calcul et Gestion du Timeout Adaptatif

La bibliothèque met en œuvre une stratégie de *timeout* sophistiquée pour équilibrer vitesse et résilience :

- **`Performance::METADATA_TIMEOUT` :** (0.8 secondes) C'est le *timeout* de base pour les requêtes rapides comme `INFO` et `RULES`.
- **`Performance::PLAYER_LIST_BASE_TIMEOUT` :** (1.0 seconde) C'est le *timeout* de base pour les requêtes de liste de joueurs.
- **`Performance::PING_MULTIPLIER` :** (2) Utilisé pour ajuster le *timeout* en fonction du ping.
- **Ajustement par Ping :** Dans la méthode `Fetch_Player_Data`, le *timeout* pour obtenir la liste des joueurs est ajusté dynamiquement :
   ```
   Timeout des Joueurs = PLAYER_LIST_BASE_TIMEOUT + (Ping Caché * PING_MULTIPLIER / 1000)
   ```
   Cette approche permet aux serveurs à haute latence (ping élevé) d'avoir un *timeout* plus long, augmentant les chances de succès pour obtenir la liste complète des joueurs, qui peut être volumineuse et longue à traiter par le serveur.
- **Limite de Timeout :** `min($timeout, 2.0)` est utilisé dans plusieurs appels pour imposer une limite maximale de 2.0 secondes pour les requêtes de métadonnées, prévenant les attentes excessives.

#### Méthodes de Requête Publiques

| Méthode | Description Détaillée | Comportement de Caching Interne |
| :--- | :--- | :--- |
| `Get_All()` | **Méthode recommandée pour un usage général.** Orchestre l'obtention de `INFO`, `RULES`, `PLAYERS_DETAILED` (ou `PLAYERS_BASIC` en fallback) en parallèle. Cela minimise le temps total de la requête, car les paquets sont envoyés quasi simultanément et les réponses sont traitées à leur arrivée. Inclut une mesure de `execution_time_ms` total. | Utilise le cache de 2.0s (`$this->response_cache`) pour chaque opcode interrogé dans la phase parallèle. |
| `Is_Online()` | Effectue une requête `INFO` rapide et retourne `true` si le serveur répond avec un `Server_Info` valide dans le *timeout*, `false` sinon. C'est robuste, utilisant des *retries* critiques. | En interne, invoque `Fetch_Server_State()`, qui utilise le cache de 5.0s pour `INFO`. |
| `Get_Ping()` | Retourne le ping le plus récent du serveur en millisecondes. Si `cached_ping` est nul, force une requête `PING` dédiée avec `Performance::FAST_PING_TIMEOUT` (0.3s) pour obtenir une mesure rapide. | Le `ping` est mis en cache et mis à jour chaque fois que `Execute_Query_Phase` reçoit la première réponse. |
| `Get_Info()` | Retourne un objet `Server_Info` avec des détails comme le nom d'hôte, le gamemode, le nombre de joueurs, etc. | Invoque `Fetch_Server_State()`, qui utilise le cache de 5.0s. |
| `Get_Rules()` | Retourne un `array` d'objets `Server_Rule` contenant toutes les règles configurées sur le serveur (ex: `mapname`, `weburl`). Inclut des *retries* supplémentaires en cas d'échec initial. | Utilise le cache de 2.0s pour `Opcode::RULES`. |
| `Get_Players_Detailed()` | Retourne un `array` d'objets `Players_Detailed` (id, nom, score, ping pour chaque joueur). **Important :** Cette requête est ignorée si le nombre de joueurs sur le serveur (`$this->cached_info->players`) est supérieur ou égal à `Query::LARGE_PLAYER_THRESHOLD` (150 par défaut), en raison du risque de *timeouts* prolongés ou de fragmentation des paquets. | Utilise le cache de 2.0s pour `Opcode::PLAYERS_DETAILED`. |
| `Get_Players_Basic()` | Retourne un `array` d'objets `Players_Basic` (nom, score pour chaque joueur). C'est plus léger que la requête détaillée. Généralement appelé en *fallback* si `Get_Players_Detailed()` échoue ou est ignoré. | Utilise le cache de 2.0s pour `Opcode::PLAYERS_BASIC`. |

#### Communication RCON (`Send_Rcon`)

La méthode `Send_Rcon(string $rcon_password, string $command)` permet d'envoyer des commandes à la console à distance du serveur.

1.  **Validation des Arguments :** Lance `Invalid_Argument_Exception` si le mot de passe ou la commande est vide.
2.  **Socket Isolé :** Crée une nouvelle instance de `Socket_Manager` (`$rcon_socket_manager`) pour la session RCON, l'isolant du socket de requête principal pour éviter les interférences.
3.  **Authentification (`varlist`) :** Avant d'envoyer la commande réelle, la bibliothèque envoie la commande "varlist" (jusqu'à 3 tentatives) pour authentifier le mot de passe RCON. Si `Send_Single_Rcon_Request` retourne `null` ou une réponse vide, une `Rcon_Exception` est levée, indiquant un échec d'authentification ou que le RCON n'est pas activé.
4.  **Envoi de la Commande Réelle :** Après une authentification réussie, la `$command` est envoyée, également avec jusqu'à 3 tentatives.
5.  **Traitement de la Réponse :** Le `Packet_Parser::Parse_Rcon()` décode la réponse textuelle du RCON. Si le serveur ne retourne pas de réponse textuelle après toutes les tentatives, un message générique de succès est retourné.
6.  **Nettoyage :** Le destructeur du `$rcon_socket_manager` garantit que le socket RCON est fermé après l'opération.

## Diagnostic des Erreurs et Exceptions

La bibliothèque utilise une hiérarchie d'exceptions personnalisées pour un traitement des erreurs propre et prévisible. En cas d'échec, l'une des exceptions suivantes sera levée.

### `Invalid_Argument_Exception`

**Cause :**
- **Nom d'hôte Vide :** Le `hostname` fourni dans le constructeur de `Samp_Query` est une chaîne vide.
- **Port Invalide :** Le `port` fourni dans le constructeur est en dehors de la plage valide de 1 à 65535.
- **RCON :** Le mot de passe RCON ou la commande RCON fournis à `Send_Rcon` sont vides.

### `Connection_Exception`

**Cause :** Échec réseau ou absence de réponse essentielle.
- **Échec de la Résolution de Domaine :** Le `Domain_Resolver` ne parvient pas à convertir le nom d'hôte en une IPv4 valide.
- **Échec de la Création du Socket :** Le `Socket_Manager` ne parvient pas à créer ou à connecter le socket UDP.
- **Serveur Inaccessible/Hors Ligne :** Le serveur ne répond pas avec un paquet `INFO` valide après toutes les tentatives et *timeouts* (y compris les *retries* d'urgence), indiquant généralement que le serveur est hors ligne, que l'IP/port est incorrect, ou qu'un pare-feu bloque la communication.

### `Malformed_Packet_Exception`

**Cause :** Corruption de données au niveau du protocole.
- **En-tête Invalide :** Le `Packet_Parser` détecte un paquet qui ne commence pas par la "chaîne magique" `SAMP` ou qui a une longueur totale insuffisante.
- **Structure de Paquet Invalide :** Le `Packet_Parser` rencontre des incohérences dans la structure binaire, comme une longueur de chaîne qui pointe en dehors des limites du paquet.
- **Résilience :** Cette exception est souvent traitée en interne par `Execute_Query_Phase` pour déclencher une nouvelle tentative immédiate, mais peut être propagée si le problème persiste.

### `Rcon_Exception`

**Cause :** Erreur lors de la communication RCON.
- **Échec d'Authentification RCON :** Le serveur n'a pas répondu à l'authentification RCON (via la commande `varlist`) après 3 tentatives, suggérant un mot de passe incorrect ou que le RCON est désactivé sur le serveur.
- **Échec de l'Envoi de la Commande RCON :** La commande RCON réelle n'a pas obtenu de réponse après 3 tentatives.

## Licence

Copyright © **SA-MP Programming Community**

Ce logiciel est sous licence selon les termes de la Licence MIT ("Licence"); vous pouvez utiliser ce logiciel conformément aux conditions de la Licence. Une copie de la Licence peut être obtenue à: [MIT License](https://opensource.org/licenses/MIT)

### Conditions Générales d'Utilisation

#### 1. Autorisations Accordées

La présente licence accorde gratuitement à toute personne obtenant une copie de ce logiciel et des fichiers de documentation associés les droits suivants:
* Utiliser, copier, modifier, fusionner, publier, distribuer, sous-licencier et/ou vendre des copies du logiciel sans restriction
* Permettre aux personnes à qui le logiciel est fourni de faire de même, sous réserve des conditions suivantes

#### 2. Conditions Obligatoires

Toutes les copies ou parties substantielles du logiciel doivent inclure:
* L'avis de droit d'auteur ci-dessus
* Cet avis d'autorisation
* L'avis de non-responsabilité ci-dessous

#### 3. Droits d'Auteur

Le logiciel et toute la documentation associée sont protégés par les lois sur le droit d'auteur. La **SA-MP Programming Community** conserve la propriété des droits d'auteur originaux du logiciel.

#### 4. Exclusion de Garantie et Limitation de Responsabilité

LE LOGICIEL EST FOURNI "TEL QUEL", SANS GARANTIE D'AUCUNE SORTE, EXPRESSE OU IMPLICITE, Y COMPRIS MAIS NON LIMITÉ AUX GARANTIES DE COMMERCIALISATION, D'ADÉQUATION À UN USAGE PARTICULIER ET DE NON-VIOLATION.

EN AUCUN CAS LES AUTEURS OU LES DÉTENTEURS DES DROITS D'AUTEUR NE SERONT RESPONSABLES DE TOUTE RÉCLAMATION, DOMMAGE OU AUTRE RESPONSABILITÉ, QUE CE SOIT DANS UNE ACTION DE CONTRAT, UN DÉLIT OU AUTRE, DÉCOULANT DE, HORS DE OU EN RELATION AVEC LE LOGICIEL OU L'UTILISATION OU D'AUTRES TRANSACTIONS DANS LE LOGICIEL.

---

Pour des informations détaillées sur la Licence MIT, consultez: https://opensource.org/licenses/MIT