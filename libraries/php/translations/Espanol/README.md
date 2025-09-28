# SA-MP Query - PHP

<div align="center">

![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Architecture](https://img.shields.io/badge/Protocol-UDP-blueviolet?style=for-the-badge)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**Una biblioteca PHP robusta y resiliente, diseñada para consultar el estado y la información de servidores SA-MP (San Andreas Multiplayer) y OMP (Open Multiplayer).**

</div>

## Idiomas

- Português: [README](../../)
- Deutsch: [README](../Deutsch/README.md)
- English: [README](../English/README.md)
- Français: [README](../Francais/README.md)
- Italiano: [README](../Italiano/README.md)
- Polski: [README](../Polski/README.md)
- Русский: [README](../Русский/README.md)
- Svenska: [README](../Svenska/README.md)
- Türkçe: [README](../Turkce/README.md)

## Índice

- [SA-MP Query - PHP](#sa-mp-query---php)
  - [Idiomas](#idiomas)
  - [Índice](#índice)
  - [Visión General](#visión-general)
  - [Principios de Diseño y Arquitectura](#principios-de-diseño-y-arquitectura)
    - [Arquitectura Modular](#arquitectura-modular)
    - [Resiliencia: Backoff, Retries y Caching](#resiliencia-backoff-retries-y-caching)
    - [Optimización de Rendimiento: Paralelismo y Adaptación de Timeout](#optimización-de-rendimiento-paralelismo-y-adaptación-de-timeout)
    - [Programación Orientada a Objetos (OOP) Moderna (PHP 8.1+)](#programación-orientada-a-objetos-oop-moderna-php-81)
  - [Requisitos](#requisitos)
  - [Instalación y Uso Básico](#instalación-y-uso-básico)
    - [Inicialización de la Clase `Samp_Query`](#inicialización-de-la-clase-samp_query)
    - [`Get_All()`: Consulta Completa y Optimizada](#get_all-consulta-completa-y-optimizada)
    - [`Is_Online()`: Verificación Rápida de Estado](#is_online-verificación-rápida-de-estado)
    - [`Get_Ping()`: Obtención del Ping del Servidor](#get_ping-obtención-del-ping-del-servidor)
    - [`Get_Info()`: Detalles Esenciales del Servidor](#get_info-detalles-esenciales-del-servidor)
    - [`Get_Rules()`: Reglas Configuradas del Servidor](#get_rules-reglas-configuradas-del-servidor)
    - [`Get_Players_Detailed()`: Lista de Jugadores con Detalles](#get_players_detailed-lista-de-jugadores-con-detalles)
    - [`Get_Players_Basic()`: Lista Básica de Jugadores](#get_players_basic-lista-básica-de-jugadores)
    - [`Send_Rcon()`: Envío de Comandos Remotos](#send_rcon-envío-de-comandos-remotos)
  - [Estructura Detallada de la Biblioteca y Flujo de Ejecución](#estructura-detallada-de-la-biblioteca-y-flujo-de-ejecución)
    - [1. `constants.php`: El Corazón de la Configuración](#1-constantsphp-el-corazón-de-la-configuración)
    - [2. `opcode.php`: El Enum de Opcodes del Protocolo](#2-opcodephp-el-enum-de-opcodes-del-protocolo)
    - [3. `exceptions.php`: La Jerarquía de Excepciones Personalizadas](#3-exceptionsphp-la-jerarquía-de-excepciones-personalizadas)
    - [4. `server_types.php`: Los Modelos de Datos Inmutables](#4-server_typesphp-los-modelos-de-datos-inmutables)
    - [5. `autoloader.php`: El Cargador Automático de Clases](#5-autoloaderphp-el-cargador-automático-de-clases)
    - [6. `logger.php`: El Subsistema de Log](#6-loggerphp-el-subsistema-de-log)
    - [7. `domain_resolver.php`: La Resolución de Dominio con Caché Persistente](#7-domain_resolverphp-la-resolución-de-dominio-con-caché-persistente)
    - [8. `socket_manager.php`: El Gestor de Conexión UDP Robusto](#8-socket_managerphp-el-gestor-de-conexión-udp-robusto)
    - [9. `packet_builder.php`: El Constructor de Paquetes Binarios](#9-packet_builderphp-el-constructor-de-paquetes-binarios)
    - [10. `packet_parser.php`: El Decodificador de Paquetes con Tratamiento de Codificación](#10-packet_parserphp-el-decodificador-de-paquetes-con-tratamiento-de-codificación)
    - [11. `samp-query.php`: La Clase Principal (El Orquestador Completo)](#11-samp-queryphp-la-clase-principal-el-orquestador-completo)
      - [Ciclo de Vida de la Consulta: El Viaje de un Paquete](#ciclo-de-vida-de-la-consulta-el-viaje-de-un-paquete)
        - [1. Inicialización y Resolución de Dominio](#1-inicialización-y-resolución-de-dominio)
        - [2. `Fetch_Server_State()`: Caché y Consulta Crítica de INFO/PING](#2-fetch_server_state-caché-y-consulta-crítica-de-infoping)
        - [3. `Attempt_Query()`: La Estrategia de Reintentos Optimizada](#3-attempt_query-la-estrategia-de-reintentos-optimizada)
        - [4. `Execute_Query_Phase()`: El Motor de Comunicación con Detección de Ping](#4-execute_query_phase-el-motor-de-comunicación-con-detección-de-ping)
        - [5. `Validate_Response()`: La Capa de Validación Semántica](#5-validate_response-la-capa-de-validación-semántica)
      - [Cálculo y Gestión de Timeout Adaptativo](#cálculo-y-gestión-de-timeout-adaptativo)
      - [Métodos de Consulta Pública](#métodos-de-consulta-pública)
      - [Comunicación RCON (`Send_Rcon`)](#comunicación-rcon-send_rcon)
  - [Diagnóstico de Errores y Excepciones](#diagnóstico-de-errores-y-excepciones)
    - [`Invalid_Argument_Exception`](#invalid_argument_exception)
    - [`Connection_Exception`](#connection_exception)
    - [`Malformed_Packet_Exception`](#malformed_packet_exception)
    - [`Rcon_Exception`](#rcon_exception)
  - [Licencia](#licencia)
    - [Términos y Condiciones de Uso](#términos-y-condiciones-de-uso)
      - [1. Permisos Otorgados](#1-permisos-otorgados)
      - [2. Condiciones Obligatorias](#2-condiciones-obligatorias)
      - [3. Derechos de Autor](#3-derechos-de-autor)
      - [4. Exención de Garantías y Limitación de Responsabilidad](#4-exención-de-garantías-y-limitación-de-responsabilidad)

## Visión General

La biblioteca **SA-MP Query - PHP** es una solución de alto rendimiento y tolerante a fallos para desarrolladores PHP que necesitan interactuar con servidores de juegos basados en el protocolo SA-MP/OMP (UDP). Su propósito es encapsular la complejidad del protocolo binario de consulta en una API PHP limpia e intuitiva, permitiendo que aplicaciones web, launchers y utilidades obtengan información detallada sobre el estado del servidor (jugadores, reglas, ping, etc.) de forma rápida y confiable.

El diseño de la biblioteca se enfoca en tres pilares principales: **Resiliencia**, **Rendimiento** y **Modularidad**. Está construida para manejar la naturaleza no confiable del protocolo UDP, implementando un sistema avanzado de reintentos y *backoff* para garantizar que la información se obtenga incluso en condiciones de red desfavorables o servidores con alta latencia.

## Principios de Diseño y Arquitectura

### Arquitectura Modular

La biblioteca está dividida en componentes de responsabilidad única, cada uno encapsulado en su propia clase y archivo.

- **Infraestructura de Red:** `Domain_Resolver`, `Socket_Manager`.
- **Protocolo:** `Packet_Builder`, `Packet_Parser`, `Opcode` (Enum).
- **Lógica de Negocios (Orquestación):** `Samp_Query`.
- **Modelos de Datos:** `Server_Info`, `Players_Detailed`, etc.

### Resiliencia: Backoff, Retries y Caching

El protocolo UDP no garantiza la entrega de paquetes. La clase `Samp_Query` mitiga este fallo con un ciclo de consulta sofisticado.

- **Múltiples Intentos Adaptativos:** El método `Attempt_Query` implementa un ciclo con hasta `Query::ATTEMPTS` (5 por defecto) y el doble de eso para consultas críticas.
- **Estrategia de Backoff:** El *backoff* exponencial se implementa en `Execute_Query_Phase`. Después del primer envío, el intervalo de nuevos intentos de escucha (bucle `while`) aumenta de `Performance::INITIAL_RETRY_INTERVAL` (0.08s) por el `Performance::BACKOFF_FACTOR` (1.3), hasta el límite de 0.2s. Esto evita la sobrecarga de paquetes y aumenta la probabilidad de una respuesta a tiempo.
- **Caching de Respuestas:** Las respuestas recientes (válidas por 2.0 segundos) se almacenan en `response_cache`, eliminando la necesidad de repetir consultas de metadatos durante la ejecución de `Get_All()`.

### Optimización de Rendimiento: Paralelismo y Adaptación de Timeout

- **Consultas Paralelas (Fan-out):** El método `Get_All()` envía solicitudes para `INFO`, `RULES` y `PLAYERS` simultáneamente (en `$jobs`), permitiendo que las respuestas lleguen fuera de orden, minimizando el tiempo de espera total.
- **Caching de DNS Persistente:** El `Domain_Resolver` almacena la dirección IP resuelta en una caché de archivo local con un TTL de 3600 segundos, evitando retrasos en la resolución de dominio en llamadas posteriores.
- **Timeout Adaptativo:** El *timeout* de consultas de datos grandes (como la lista de jugadores) se ajusta dinámicamente en función del `cached_ping` del servidor:
   ```
   Timeout Ajustado = Timeout Base + (Ping Cacheado * Multiplicador de Ping / 1000)
   ```
   Esta lógica (implementada en `Fetch_Player_Data`) garantiza que los servidores con alta latencia tengan tiempo suficiente para responder, sin comprometer la rapidez en servidores con baja latencia.

### Programación Orientada a Objetos (OOP) Moderna (PHP 8.1+)

La biblioteca utiliza características modernas de PHP para garantizar seguridad y claridad:

- **Tipado Estricto** (`declare(strict_types=1)`).
- **Propiedades de Solo Lectura** (`public readonly` en `Samp_Query` y en los modelos de datos) para garantizar la inmutabilidad de los datos.
- **Enums Tipados** (`enum Opcode: string`) para un control seguro del protocolo.
- **Constructor Property Promotion** (en `Samp_Query::__construct` y modelos).

## Requisitos

- **PHP:** Versión **8.1 o superior**.
- **Extensiones PHP:** `sockets` y `mbstring` (para manipulación de codificación UTF-8).

## Instalación y Uso Básico

Para comenzar a usar la biblioteca **SA-MP Query - PHP**, simplemente incluye el archivo `samp-query.php` en tu proyecto. Este archivo se encargará de cargar automáticamente todas las dependencias a través de su autoloader interno.

```php
<?php
// Incluye la clase principal. Se encargará de cargar las dependencias vía autoloader.
require_once 'path/to/samp-query/samp-query.php'; 

// Usa el namespace de la clase principal
use Samp_Query\Samp_Query;
// Incluye las excepciones para un manejo de errores más específico
use Samp_Query\Exceptions\Invalid_Argument_Exception;
use Samp_Query\Exceptions\Connection_Exception;
use Samp_Query\Exceptions\Rcon_Exception;

// Instancia la clase Samp_Query, envolviéndola en un bloque try-catch para manejar errores de inicialización.
try {
    $server_query = new Samp_Query("play.example-samp-server.com", 7777);
    // Ahora puedes usar los métodos públicos de $server_query
}
catch (Invalid_Argument_Exception $e) {
    echo "Error de Argumento: " . $e->getMessage() . "\n";
}
catch (Connection_Exception $e) {
    echo "Error de Conexión: " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Error inesperado durante la inicialización: " . $e->getMessage() . "\n";
}
```

### Inicialización de la Clase `Samp_Query`

La clase `Samp_Query` es la puerta de entrada a todas las funcionalidades. Su constructor requiere el `hostname` (o dirección IP) y el `port` del servidor que deseas consultar.

```php
/**
 * Inicializa una nueva instancia de la biblioteca Samp_Query.
 *
 * @param string $hostname El hostname o dirección IP del servidor SA-MP/OMP.
 * @param int $port El puerto UDP del servidor (normalmente 7777).
 * 
 * @throws Invalid_Argument_Exception Si el hostname está vacío o el puerto es inválido.
 * @throws Connection_Exception Si la resolución de DNS falla o el socket no puede ser creado.
 */
public function __construct(public readonly string $hostname, public readonly int $port)
```

<br>

---

### `Get_All()`: Consulta Completa y Optimizada

Este es el método más completo y recomendado. Ejecuta diversas consultas (INFO, RULES, PLAYERS) en paralelo y de forma optimizada, minimizando el tiempo total de respuesta y devolviendo un array asociativo completo con toda la información disponible.

```php
/**
 * Devuelve toda la información disponible del servidor en una única llamada optimizada.
 * Incluye: is_online, ping, info (Server_Info), rules (array de Server_Rule),
 * players_detailed (array de Players_Detailed), players_basic (array de Players_Basic),
 * y execution_time_ms.
 *
 * @return array Un array asociativo que contiene toda la información del servidor.
 * 
 * @throws Connection_Exception Si la consulta INFO, esencial para el estado del servidor, falla.
 */
public function Get_All(): array
```

Ejemplo de Uso:

```php
<?php
// ... (inicialización de la clase $server_query) ...

try {
    $data = $server_query->Get_All();
    
    if ($data['is_online']) {
        echo "## Servidor en Línea: {$data['info']->hostname} ({$server_query->ip}:{$server_query->port}) ##\n";
        echo "Ping: {$data['ping']}ms | Tiempo Total de la Consulta: {$data['execution_time_ms']}ms\n";
        echo "Jugadores: {$data['info']->players} / {$data['info']->max_players}\n";
        echo "Modo de Juego: {$data['info']->gamemode}\n";
        echo "Idioma: {$data['info']->language}\n";
        echo "Protegido con Contraseña: " . ($data['info']->password ? "Sí" : "No") . "\n\n";

        // Ejemplo de lista de jugadores detallada
        if (!empty($data['players_detailed'])) {
            echo "--- Jugadores Detallados ({$data['info']->players} Activos) ---\n";

            foreach ($data['players_detailed'] as $player)
                echo "ID: {$player->id}, Nombre: {$player->name}, Ping: {$player->ping}ms, Score: {$player->score}\n";
        }
        elseif (!empty($data['players_basic'])) {
            echo "--- Jugadores Básicos ({$data['info']->players} Activos) (Fallback) ---\n";

            foreach ($data['players_basic'] as $player)
                echo "Nombre: {$player->name}, Score: {$player->score}\n";
        }
        else
            echo "Ningún jugador en línea o lista no disponible (quizás demasiados jugadores).\n";
        
        // Ejemplo de reglas del servidor
        if (!empty($data['rules'])) {
            echo "\n--- Reglas del Servidor ---\n";

            foreach ($data['rules'] as $rule)
                echo "{$rule->name}: {$rule->value}\n";
        }
    }
    else
        echo "El servidor está actualmente fuera de línea o inaccesible.\n";
}
catch (Connection_Exception $e) {
    echo "Fallo de conexión al intentar obtener toda la información: " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Error inesperado al consultar toda la información: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Is_Online()`: Verificación Rápida de Estado

Verifica si el servidor está en línea y responde a consultas, sin buscar detalles adicionales. Ideal para un simple "liveness check".

```php
/**
 * Verifica si el servidor está en línea y accesible.
 *
 * @return bool Devuelve true si el servidor está en línea y responde con INFO válido, false en caso contrario.
 */
public function Is_Online(): bool
```

Ejemplo de Uso:

```php
<?php
// ... (inicialización de la clase $server_query) ...

if ($server_query->Is_Online())
    echo "¡El servidor SA-MP está en línea y respondiendo!\n";
else
    echo "El servidor SA-MP está fuera de línea o no respondió a tiempo.\n";
```

<br>

---

### `Get_Ping()`: Obtención del Ping del Servidor

Devuelve el tiempo de latencia (ping) del servidor en milisegundos. Este valor se cachea internamente para optimización.

```php
/**
 * Devuelve el ping actual del servidor en milisegundos.
 * Si el ping aún no ha sido calculado, se ejecutará una consulta PING rápida.
 *
 * @return int|null El valor del ping en milisegundos, o null si no es posible obtenerlo.
 */
public function Get_Ping(): ?int
```

Ejemplo de Uso:

```php
<?php
// ... (inicialización de la clase $server_query) ...

try {
    $ping = $server_query->Get_Ping();

    if ($ping !== null)
        echo "El ping del servidor es: {$ping}ms.\n";
    else
        echo "No fue posible obtener el ping del servidor.\n";
}
catch (Connection_Exception $e) {
    echo "Error al obtener el ping: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Info()`: Detalles Esenciales del Servidor

Obtiene la información básica del servidor, como hostname, modo de juego, número de jugadores, etc. Devuelve un objeto `Server_Info`.

```php
/**
 * Devuelve los detalles básicos del servidor (hostname, jugadores, gamemode, etc.).
 * Los datos se cachean por un corto período para optimización.
 *
 * @return Server_Info|null Un objeto Server_Info, o null si la información no puede ser obtenida.
 */
public function Get_Info(): ?Server_Info
```

Ejemplo de Uso:

```php
<?php
use Samp_Query\Models\Server_Info;
// ... (inicialización de la clase $server_query) ...

try {
    /** @var Server_Info|null $info */
    $info = $server_query->Get_Info();

    if ($info) {
        echo "--- Información del Servidor ---\n";
        echo "Hostname: {$info->hostname}\n";
        echo "Gamemode: {$info->gamemode}\n";
        echo "Jugadores: {$info->players} / {$info->max_players}\n";
        echo "Idioma: {$info->language}\n";
        echo "Protegido con Contraseña: " . ($info->password ? "Sí" : "No") . "\n";
    }
    else
        echo "No fue posible obtener la información del servidor.\n";
}
catch (Connection_Exception $e) {
    echo "Error al obtener la información del servidor: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Rules()`: Reglas Configuradas del Servidor

Recupera todas las reglas configuradas en el servidor, como `mapname`, `weburl`, `weather`, etc., devolviéndolas como un array de objetos `Server_Rule`.

```php
/**
 * Devuelve un array de objetos Server_Rule, cada uno conteniendo el nombre y el valor de una regla del servidor.
 * Los datos se cachean para optimización.
 *
 * @return array Un array de Samp_Query\Models\Server_Rule. Puede estar vacío si no hay reglas.
 */
public function Get_Rules(): array
```

Ejemplo de Uso:

```php
<?php
use Samp_Query\Models\Server_Rule;
// ... (inicialización de la clase $server_query) ...

try {
    $rules = $server_query->Get_Rules();

    if (!empty($rules)) {
        echo "--- Reglas del Servidor ---\n";

        foreach ($rules as $rule)
            echo "{$rule->name}: {$rule->value}\n";

        // Ejemplo de cómo acceder a una regla específica:
        $mapname_rule = array_filter($rules, fn(Server_Rule $r) => $r->name === 'mapname');

        if (!empty($mapname_rule))
            echo "\nMapa actual: " . reset($mapname_rule)->value . "\n";
    }
    else
        echo "No se encontraron reglas para este servidor.\n";
}
catch (Connection_Exception $e) {
    echo "Error al obtener las reglas del servidor: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Players_Detailed()`: Lista de Jugadores con Detalles

Obtiene una lista detallada de los jugadores actualmente en línea, incluyendo ID, nombre, score y ping.

> [!CAUTION]
> Para optimizar el rendimiento y evitar paquetes UDP excesivamente grandes que pueden perderse o fragmentarse, este método no buscará la lista detallada de jugadores si el número total de jugadores es igual o superior a `Query::LARGE_PLAYER_THRESHOLD` (150 por defecto). En tales casos, se devolverá un array vacío. Considere usar `Get_Players_Basic()` como un fallback.

```php
/**
 * Devuelve un array de objetos Players_Detailed (ID, nombre, score, ping) para cada jugador en línea.
 * Esta consulta puede ser omitida si el número de jugadores es muy alto (ver Query::LARGE_PLAYER_THRESHOLD).
 *
 * @return array Un array de Samp_Query\Models\Players_Detailed. Puede estar vacío.
 */
public function Get_Players_Detailed(): array
```

Ejemplo de Uso:

```php
<?php
use Samp_Query\Models\Players_Detailed;
// ... (inicialización de la clase $server_query) ...

try {
    $players_detailed = $server_query->Get_Players_Detailed();

    if (!empty($players_detailed)) {
        echo "--- Jugadores en Línea (Detallado) ---\n";

        foreach ($players_detailed as $player)
            echo "ID: {$player->id}, Nombre: {$player->name}, Score: {$player->score}, Ping: {$player->ping}ms\n";
    }
    else
        echo "Ningún jugador en línea o lista detallada no disponible.\n";
}
catch (Connection_Exception $e) {
    echo "Error al obtener la lista detallada de jugadores: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Players_Basic()`: Lista Básica de Jugadores

Proporciona una lista más ligera de jugadores, conteniendo solo nombre y score. Útil como alternativa cuando la lista detallada no está disponible o para reducir la carga de datos.

```php
/**
 * Devuelve un array de objetos Players_Basic (nombre, score) para cada jugador en línea.
 * Útil como una alternativa más ligera o fallback cuando Get_Players_Detailed() no es viable.
 *
 * @return array Un array de Samp_Query\Models\Players_Basic. Puede estar vacío.
 */
public function Get_Players_Basic(): array
```

Ejemplo de Uso:

```php
<?php
use Samp_Query\Models\Players_Basic;
// ... (inicialización de la clase $server_query) ...

try {
    $players_basic = $server_query->Get_Players_Basic();

    if (!empty($players_basic)) {
        echo "--- Jugadores en Línea (Básico) ---\n";

        foreach ($players_basic as $player)
            echo "Nombre: {$player->name}, Score: {$player->score}\n";
    }
    else
        echo "Ningún jugador en línea o lista básica no disponible.\n";
}
catch (Connection_Exception $e) {
    echo "Error al obtener la lista básica de jugadores: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Send_Rcon()`: Envío de Comandos Remotos

Permite enviar comandos a la consola RCON del servidor, como cambiar reglas, banear jugadores, etc. Requiere la contraseña RCON del servidor.

> [!WARNING]
> La función RCON es sensible y puede causar cambios en el servidor. Úsela con precaución y solo con contraseñas de confianza.
> Es crucial que la contraseña RCON sea **correcta** y que el RCON esté **habilitado** en el servidor (configuración `rcon_password` en `server.cfg`).

```php
/**
 * Envía un comando RCON al servidor.
 * Realiza autenticación con 'varlist' y envía el comando.
 *
 * @param string $rcon_password La contraseña RCON del servidor.
 * @param string $command El comando a ejecutar (ej: "gmx", "kick ID").
 * @return string La respuesta del servidor al comando RCON, o un mensaje de estado.
 * 
 * @throws Invalid_Argument_Exception Si la contraseña o el comando RCON están vacíos.
 * @throws Rcon_Exception Si la autenticación RCON falla o el comando no obtiene respuesta.
 * @throws Connection_Exception En caso de fallo de conexión durante la operación RCON.
 */
public function Send_Rcon(string $rcon_password, string $command): string
```

Ejemplo de Uso:

```php
<?php
// ... (inicialización de la clase $server_query) ...

$rcon_password = "tu_contraseña_secreta_aqui"; 
$command_to_send = "gmx"; // Ejemplo: reiniciar el gamemode

try {
    echo "Intentando enviar comando RCON: '{$command_to_send}'...\n";
    $response = $server_query->Send_Rcon($rcon_password, $command_to_send);
    echo "Respuesta de RCON: {$response}\n";

    // Ejemplo de comando para "decir" algo en el servidor (requiere RCON)
    $response_say = $server_query->Send_Rcon($rcon_password, "say ¡Hola desde mi script PHP!");
    echo "Respuesta de RCON (say): {$response_say}\n";
}
catch (Invalid_Argument_Exception $e) {
    echo "Error RCON (Argumento Inválido): " . $e->getMessage() . "\n";
}
catch (Rcon_Exception $e) {
    echo "Error RCON: Fallo de autenticación o comando no ejecutado. " . $e->getMessage() . "\n";
}
catch (Connection_Exception $e) {
    echo "Error RCON (Conexión): " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Error inesperado al enviar RCON: " . $e->getMessage() . "\n";
}
```

## Estructura Detallada de la Biblioteca y Flujo de Ejecución

La biblioteca **SA-MP Query - PHP** está meticulosamente organizada en varios archivos, cada uno con una responsabilidad bien definida. Esta sección explora cada componente en detalle, revelando las decisiones de diseño y la lógica subyacente.

### 1. `constants.php`: El Corazón de la Configuración

Este archivo centraliza todos los parámetros de configuración "mágicos" de la biblioteca, garantizando que aspectos como *timeouts*, número de reintentos y tamaños de buffer sean fácilmente ajustables y consistentes en todo el proyecto.

```php
// ...
final class Query {
    public const ATTEMPTS = 5; // Número máximo de intentos de consulta
    public const LARGE_PLAYER_THRESHOLD = 150; // Límite de jugadores para consulta detallada
}

final class Protocol {
    public const UDP_READ_BUFFER_SIZE = 32768; // 32KB para el buffer de lectura
    public const KERNEL_RECEIVE_BUFFER_SIZE = 4194304; // 4MB para el buffer del kernel
}
// ...
```

- **Clases Final y Constantes:** Las clases son `final` y las propiedades son `public const`, garantizando inmutabilidad y accesibilidad global en tiempo de compilación.
- **Granularidad y Semántica:** Las constantes se categorizan por su dominio (`Query`, `Protocol`, `Performance`, `Logger`, `DNS`), facilitando la comprensión y el mantenimiento. Por ejemplo, `Query::LARGE_PLAYER_THRESHOLD` define el punto en que la búsqueda de listas detalladas de jugadores puede evitarse para optimización, debido al volumen de datos y el potencial de *timeouts*.

### 2. `opcode.php`: El Enum de Opcodes del Protocolo

Este archivo define los códigos de operación (opcodes) utilizados para las diferentes consultas al servidor SA-MP/OMP, encapsulándolos en un `enum` tipado.

```php
// ...
enum Opcode: string {
    case INFO = 'i';
    case RULES = 'r';
    case PLAYERS_DETAILED = 'd';
    // ...
}
```

- **`Enum` Tipado (PHP 8.1+):** El uso de un `enum` (`Opcode: string`) con valores de tipo `string` garantiza que los opcodes sean siempre válidos y que el código sea semánticamente claro. Esto reemplaza el uso de cadenas literales "mágicas", haciendo el código más legible y menos propenso a errores de tipeo.

### 3. `exceptions.php`: La Jerarquía de Excepciones Personalizadas

Este archivo establece una jerarquía de excepciones personalizadas, permitiendo un manejo de errores más granular y específico para los diversos tipos de fallos que pueden ocurrir en la biblioteca.

```php
// ...
class Query_Exception extends \Exception {}
class Invalid_Argument_Exception extends Query_Exception {}
class Connection_Exception extends Query_Exception {}
// ...
```

- **Herencia de `\Exception`:** Todas las excepciones heredan de `Query_Exception` (que a su vez extiende `\Exception`), permitiendo capturar grupos de errores (`Connection_Exception` y `Timeout_Exception` son más específicas que `Query_Exception`) o todas las excepciones de la biblioteca con un `catch` más genérico.
- **Claridad en el Diagnóstico:** Los nombres descriptivos de las excepciones facilitan el diagnóstico y la recuperación de errores en la aplicación cliente.

### 4. `server_types.php`: Los Modelos de Datos Inmutables

Este archivo define las clases que representan los modelos de datos para la información devuelta por el servidor, garantizando la integridad de los datos a través de la inmutabilidad.

```php
// ...
final class Server_Info {
    public function __construct(
        public readonly bool $password,
        public readonly int $players,
        // ... otras propiedades readonly ...
    ) {}
}
// ...
```

- **`final class`:** Las clases son `final`, previniendo extensiones y garantizando su estructura y comportamiento.
- **`public readonly` Properties (PHP 8.1+):** Todas las propiedades se declaran como `public readonly`. Esto significa que, una vez que el objeto es construido, sus valores no pueden ser alterados, garantizando la integridad de los datos recibidos del servidor.
- **Constructor Property Promotion (PHP 8.1+):** Las propiedades se declaran directamente en el constructor, simplificando el código y reduciendo el *boilerplate*.

### 5. `autoloader.php`: El Cargador Automático de Clases

Este archivo es responsable de cargar dinámicamente las clases de la biblioteca cuando son necesarias, siguiendo el estándar PSR-4.

```php
// ...
spl_autoload_register(function ($class) {
    $prefix = 'Samp_Query\\';
    $base_dir = __DIR__ . '/../modules/'; // Mapea el namespace al directorio
    // ... lógica de carga ...
});

// Incluye archivos esenciales que no son clases, o que necesitan carga anticipada
require_once __DIR__ . '/../modules/constants.php';
require_once __DIR__ . '/../modules/exceptions.php';
// ...
```

- **`spl_autoload_register()`:** Registra una función anónima que es llamada automáticamente por PHP cuando se hace referencia a una clase no definida, agilizando el desarrollo y el mantenimiento.
- **Inclusión Directa de Configuraciones:** Archivos como `constants.php` y `exceptions.php` se incluyen directamente. Esto garantiza que sus definiciones estén disponibles antes de que cualquier clase que los utilice sea instanciada por el autoloader.

### 6. `logger.php`: El Subsistema de Log

La clase `Logger` proporciona un mecanismo simple para registrar mensajes de error y eventos importantes en un archivo de log, útil para depuración y monitoreo.

```php
// ...
final class Logger {
    public function Log(string $message): void {
        if (is_file(Logger_Config::FILE) && filesize(Logger_Config::FILE) > Logger_Config::MAX_SIZE_BYTES)
            unlink(Logger_Config::FILE); // Elimina el log si excede el tamaño

        $timestamp = date('Y-m-d H:i:s');
        $log_entry = "[{$timestamp}] {$message}" . PHP_EOL;
        
        file_put_contents(Logger_Config::FILE, $log_entry, FILE_APPEND | LOCK_EX); // Añade con bloqueo
    }
}
```

- **Limpieza Automática:** El logger verifica el tamaño del archivo de log (`Logger_Config::FILE`). Si excede `Logger_Config::MAX_SIZE_BYTES` (10 MB por defecto), el archivo se elimina para evitar que crezca indefinidamente.
- **Bloqueo de Archivo (`LOCK_EX`):** `file_put_contents` utiliza `LOCK_EX` para garantizar que solo un proceso escriba en el archivo de log a la vez, previniendo corrupción en entornos multi-threaded/multi-proceso.

### 7. `domain_resolver.php`: La Resolución de Dominio con Caché Persistente

La clase `Domain_Resolver` es responsable de convertir nombres de host (como `play.example.com`) en direcciones IP (como `192.0.2.1`). Implementa un sistema de caché en disco para optimizar el rendimiento.

```php
// ...
final class Domain_Resolver {
    public function Resolve(string $hostname): string {
        if (filter_var($hostname, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
            return $hostname; // Ya es una IP

        $cache_file = DNS_Config::CACHE_DIR . md5($hostname) . '.cache';

        if (file_exists($cache_file) && (time() - filemtime($cache_file)) < DNS_Config::CACHE_TTL_SECONDS) {
            $ip = file_get_contents($cache_file);

            if ($ip && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
                return $ip;
        }
        
        $ip = gethostbyname($hostname); // Resolución real del DNS
        // ... lógica de validación y guardado en caché ...

        return $ip;
    }
    // ...
}
```

- **Caché Persistente:** Antes de llamar a `gethostbyname()`, verifica si la IP ya está almacenada en un archivo de caché (`dns/` + hash MD5 del hostname). La caché se considera válida si no ha excedido `DNS_Config::CACHE_TTL_SECONDS` (3600 segundos o 1 hora por defecto).
- **Validación Robusta:** La IP resuelta (o leída de la caché) se valida con `filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)` para garantizar que sea una IPv4 válida. Si la resolución falla, se lanza una `Query_Exception`.

### 8. `socket_manager.php`: El Gestor de Conexión UDP Robusto

La clase `Socket_Manager` encapsula la complejidad de la creación, configuración y gestión de un socket UDP para la comunicación con el servidor de juego.

```php
// ...
final class Socket_Manager {
    private $socket = null;
    // ...
    private function Connect(): void {
        if (is_resource($this->socket))
            return;

        $context = stream_context_create([
            'socket' => ['so_rcvbuf' => Protocol::KERNEL_RECEIVE_BUFFER_SIZE] // Aumenta el buffer a 4MB
        ]);

        $this->socket = stream_socket_client(
            "udp://{$this->host}:{$this->port}", 
            $err_no, $err_str, 
            (float)ini_get("default_socket_timeout"), 
            STREAM_CLIENT_CONNECT, // Conecta el socket a la dirección remota
            $context
        );

        if ($this->socket === false)
            throw new Connection_Exception("Could not create socket...");
    }
    // ...
}
```

- **`stream_socket_client` con `STREAM_CLIENT_CONNECT`:** Esta *flag* instruye al sistema operativo a "conectar" el socket UDP a la dirección remota. Aunque UDP no tiene conexión, "conectar" el socket permite optimizaciones de rendimiento a nivel del kernel, como no necesitar especificar la dirección remota en cada llamada `fwrite` o `stream_socket_recvfrom`, resultando en una menor sobrecarga.
- **Buffer de Recepción del Kernel:** `stream_context_create` se utiliza para aumentar el tamaño del buffer de recepción del kernel (`so_rcvbuf`) a `Protocol::KERNEL_RECEIVE_BUFFER_SIZE` (4MB). Esto es crucial para evitar la pérdida de paquetes (desbordamiento del buffer) al recibir respuestas grandes, como listas de jugadores detalladas de servidores concurridos.
- **RAII vía `__destruct`:** El método `Disconnect()` es invocado automáticamente en el destructor (`__destruct`), garantizando que el socket se cierre y los recursos se liberen, incluso en caso de excepciones.
- **Timeout Dinámico:** `Set_Timeout` ajusta con precisión los timeouts de lectura/escritura del socket usando `stream_set_timeout`, fundamental para la lógica de *retries* y *backoff*.

### 9. `packet_builder.php`: El Constructor de Paquetes Binarios

La clase `Packet_Builder` es responsable de serializar los datos de la consulta en un formato binario específico que el servidor SA-MP/OMP puede entender.

```php
// ...
final class Packet_Builder {
    // ...
    private function Build_Header(): string {
        $packet = 'SAMP';
        $packet .= pack('c4', ...$this->ip_parts); // IP en 4 bytes
        $packet .= pack('v', $this->port); // Puerto en 2 bytes (little-endian)

        return $packet;
    }
    
    public function Build(string $opcode): string {
        $packet = $this->Build_Header();
        $packet .= $opcode;

        if ($opcode === Opcode::PING->value)
            $packet .= random_bytes(4); // Payload aleatorio para PING
        
        return $packet;
    }
    // ...
}
```

- **`pack()` para Formato Binario:** Utiliza la función `pack()` de PHP para convertir los datos (IP, puerto, longitudes de cadena) en su formato binario correcto, como `c4` para 4 bytes de caracteres (IP) y `v` para entero sin signo de 16 bits (puerto y longitudes), que es *little-endian*.
- **Encabezado Estándar:** `Build_Header()` crea el encabezado 'SAMP' de 10 bytes que precede a todos los paquetes.
- **Estructura RCON:** `Build_Rcon` formatea el paquete RCON con el opcode 'x' seguido por la longitud de la contraseña, la contraseña, la longitud del comando y el comando en sí.

### 10. `packet_parser.php`: El Decodificador de Paquetes con Tratamiento de Codificación

La clase `Packet_Parser` es la contraparte del `Packet_Builder`, responsable de interpretar las respuestas binarias recibidas del servidor y convertirlas en datos PHP estructurados.

```php
// ...
final class Packet_Parser {
    private int $offset = Protocol::MIN_PACKET_LENGTH; // Inicia después del encabezado (11 bytes)
    // ...
    public function __construct(private readonly string $data) {
        // Validación inicial del encabezado 'SAMP'
        if (substr($this->data, 0, 4) !== 'SAMP' || strlen($this->data) < Protocol::MIN_PACKET_LENGTH)
            throw new Malformed_Packet_Exception("Invalid response header or packet too short.");
    }
    
    private function Extract_String(int $length_bytes): string {
        // ... lógica para leer la longitud y la cadena ...
        $string = substr($this->data, $this->offset, $length);
        $this->offset += $length;
        
        // **CONVERSIÓN DE CODIFICACIÓN CRÍTICA:** Servidores SA-MP/OMP usan Windows-1252
        return mb_convert_encoding($string, 'UTF-8', 'Windows-1252');
    }
    // ...
}
```

- **`offset` y `data_length`:** El `offset` se usa para rastrear la posición actual en la lectura del paquete, mientras que `data_length` previene lecturas fuera de los límites del buffer.
- **Validación del Encabezado:** El constructor valida la "magic string" `SAMP` para rechazar paquetes malformados inmediatamente.
- **`Extract_String()` - Conversión de Codificación Crucial:** Esta es una de las funcionalidades más importantes. El protocolo SA-MP transmite cadenas usando la codificación **Windows-1252**. Para garantizar que los caracteres especiales (como acentos o cirílicos) se muestren correctamente en aplicaciones PHP (que generalmente operan en UTF-8), se aplica el método `mb_convert_encoding($string, 'UTF-8', 'Windows-1252')`.
- **Extracción de Longitud Variable:** El método `Extract_String` soporta diferentes tamaños de prefijo de longitud para las cadenas (1, 2 o 4 bytes), haciéndolo flexible para diversos campos del protocolo.

### 11. `samp-query.php`: La Clase Principal (El Orquestador Completo)

La clase `Samp_Query` es el punto de entrada principal y el orquestador de todas las operaciones. Une todos los componentes, gestiona el estado de la consulta, la lógica de *retries* y los *timeouts*.

#### Ciclo de Vida de la Consulta: El Viaje de un Paquete

Todo el proceso de consulta a un servidor sigue una secuencia de pasos cuidadosamente orquestados, buscando la máxima resiliencia y rendimiento.

##### 1. Inicialización y Resolución de Dominio

Cuando se crea una instancia de `Samp_Query`:
- **Validación Rápida:** El constructor valida los parámetros `$hostname` y `$port`. Cualquier inconsistencia resulta en una `Invalid_Argument_Exception`.
- **Limpieza de Caché DNS:** `Domain_Resolver::Clean_Expired_Cache()` se invoca para garantizar que solo se consideren entradas DNS válidas y no expiradas.
- **Resolución de IP:** `Domain_Resolver` se utiliza para convertir el `$hostname` en una dirección IP (`$this->ip`). Esta IP se cachea en disco para futuras solicitudes, y se lanza una `Query_Exception` si la resolución falla.
- **Configuración de Recursos:** `Logger`, `Socket_Manager` y `Packet_Builder` son instanciados, preparando la infraestructura para la comunicación.

##### 2. `Fetch_Server_State()`: Caché y Consulta Crítica de INFO/PING

Este método interno es un guardián de rendimiento y consistencia, garantizando que la información básica del servidor (`Server_Info`) y el `ping` estén siempre actualizados antes de cualquier consulta principal.

- **Caché Primaria (5 Segundos):** Antes de iniciar cualquier comunicación, se verifica si `$this->cached_info` (el objeto `Server_Info` del servidor) tiene datos con menos de 5 segundos de antigüedad (comparado con `$this->last_successful_query`). Si los datos están frescos, la función retorna inmediatamente, evitando tráfico de red innecesario.
- **Consulta Crítica de INFO:** Si la caché está expirada o vacía, se invoca el método `Attempt_Query` para obtener los datos `INFO`. Esta consulta se marca como **crítica** (`true`), lo que desencadena un mayor número de reintentos y *timeouts* más generosos. Se lanza una `Connection_Exception` si la respuesta INFO es inválida después de todos los intentos.
- **Cálculo de Ping:** Si `$this->cached_ping` todavía es nulo, se realiza una consulta `PING` rápida (`Execute_Query_Phase` con `Performance::FAST_PING_TIMEOUT`). El ping se calcula como el tiempo transcurrido hasta la **primera** respuesta recibida, garantizando precisión.

##### 3. `Attempt_Query()`: La Estrategia de Reintentos Optimizada

Este es el cerebro de la resiliencia de la biblioteca, gestionando el ciclo de reintentos de alto nivel para uno o más `$jobs` (consultas de opcodes).

- **Caché de Respuestas (2 Segundos):** Primero, verifica si las respuestas para cualquiera de los `$jobs` ya están en `$this->response_cache` (con menos de 2.0 segundos). Esto previene *retries* innecesarios para datos volátiles, pero no críticos.
- **Fase de Reintentos Rápidos:** La biblioteca primero intenta `Query::FAST_RETRY_ATTEMPTS` (2 por defecto) con un *timeout* menor (`$timeout * 0.6`). El objetivo es obtener una respuesta lo más rápido posible, sin introducir retrasos significativos.
- **Fase de Reintentos Estándar con Backoff:** Si la fase rápida no es suficiente, el ciclo continúa con el resto de los `Query::ATTEMPTS`. En esta fase, el `$adjusted_timeout` aumenta progresivamente con cada intento, dando más tiempo al servidor para responder. Más importante aún, `usleep()` introduce un retraso creciente (basado en `Query::RETRY_DELAY_MS` y un factor de aumento) entre las llamadas a `Execute_Query_Phase`, permitiendo que la red y el servidor se estabilicen.
- **Reintentos de Emergencia (para Consultas Críticas):** Para `$jobs` marcados como `critical`, si todos los intentos anteriores fallan, se realiza un *retry* final para cada job individualmente, utilizando un *timeout* aún mayor (`$timeout * 2`). Esta es una última oportunidad para obtener información vital.

##### 4. `Execute_Query_Phase()`: El Motor de Comunicación con Detección de Ping

Este método de bajo nivel es donde ocurre la interacción real con el socket UDP. Gestiona el envío y la recepción de paquetes para un grupo de `$jobs` simultáneamente en una única fase de red.

```php
// ... (dentro de Execute_Query_Phase)
private function Execute_Query_Phase(array $jobs, ?int &$ping, float $timeout): array {
    $socket = $this->socket_manager->Get_Socket_Resource($timeout);
    stream_set_blocking($socket, false); // Socket no bloqueante

    // Envía paquetes dos veces inmediatamente para mayor garantía de entrega UDP
    for ($i = 0; $i < 2; $i++) {
        foreach ($pending_jobs as $opcode)
            fwrite($socket, $this->packet_builder->Build($opcode->value));

        if ($i == 0)
            usleep(5000); // Pequeño delay
    }

    $last_send_time = microtime(true);
    $current_retry_interval = Performance::INITIAL_RETRY_INTERVAL; // 0.08s
    
    while (!empty($pending_jobs) && (microtime(true) - $start_time) < $timeout) {
        $now = microtime(true);

        if (($now - $last_send_time) > $current_retry_interval) { // Lógica de reenvío con backoff
            // ... reenvía paquetes pendientes ...
            $current_retry_interval = min($current_retry_interval * Performance::BACKOFF_FACTOR, 0.2); // Aumenta el intervalo de retry
        }

        $read = [$socket]; $write = $except = null

        if (stream_select($read, $write, $except, 0, 10000) > 0) { // Espera por datos (max 10ms)
            $response = stream_socket_recvfrom($socket, Protocol::UDP_READ_BUFFER_SIZE);
            // ... lógica de parsing, cálculo de ping y validación ...
        }

        if (!empty($pending_jobs))
            usleep(1000); // Pequeño delay para evitar CPU spin
    }
    return $phase_results;
}
```

- **Socket No Bloqueante:** `stream_set_blocking($socket, false)` es esencial. Permite que PHP envíe paquetes y luego espere respuestas sin bloquear la ejecución, usando `stream_select`.
- **Envío Doble para Robustez:** Los paquetes para todos los `$pending_jobs` se envían **dos veces** consecutivas (con un pequeño `usleep(5000)` entre ellos) al inicio de la fase. Esta práctica es fundamental en protocolos UDP para aumentar significativamente la probabilidad de entrega en redes inestables o con pérdida de paquetes, mitigando la naturaleza no confiable de UDP. Para `INFO` y `PING`, se realiza un tercer envío adicional durante los *retries* en el bucle principal.
- **Bucle de Recepción con Backoff Adaptativo:**
   - Un bucle `while` principal continúa hasta que todos los `$jobs` se completen o expire el *timeout* de la fase.
   - **Reenvío Dinámico:** Si el tiempo transcurrido desde el último envío (`$now - $last_send_time`) excede el `$current_retry_interval`, los paquetes para los `$pending_jobs` se reenvían. El `$current_retry_interval` se aumenta entonces (`min($current_retry_interval * Performance::BACKOFF_FACTOR, 0.2)`), implementando un *backoff* exponencial que evita la sobrecarga del servidor y maximiza las posibilidades de una respuesta.
   - **`stream_select` Optimizado:** `stream_select($read, $write, $except, 0, 10000)` se usa para esperar datos por un máximo de 10 milisegundos. Esto permite que la biblioteca sea responsiva y procese paquetes tan pronto como llegan.
   - **Medición Precisa de Ping:** Cuando se recibe el **primer** paquete válido (`$packets_received === 0`), el `ping` se calcula con alta precisión como la diferencia entre `microtime(true)` al inicio del envío de la primera tanda de paquetes y el tiempo exacto de recepción del **primer** paquete válido.
- **Procesamiento y Validación de la Respuesta:** Las respuestas recibidas son decodificadas por `Packet_Parser`. Si se detecta un paquete `Malformed_Packet_Exception`, se registra en el log, y el paquete se reenvía inmediatamente al servidor para intentarlo de nuevo. La respuesta decodificada es luego validada por `Validate_Response()`. Si es válida, se añade a `$phase_results` y a `$this->response_cache`.

##### 5. `Validate_Response()`: La Capa de Validación Semántica

Este método crucial, implementado en la clase `Samp_Query`, verifica la integridad y la consistencia lógica de los datos decodificados antes de ser entregados al usuario.

```php
// ... (dentro de Validate_Response)
private function Validate_Response($data, Opcode $opcode): bool {
    switch ($opcode) {
        case Opcode::INFO:
            // Garantiza que el hostname no esté vacío y que los números de jugadores sean lógicos
            return $data instanceof Server_Info && $data->max_players > 0 && $data->players >= 0 && $data->players <= $data->max_players && !empty($data->hostname);
        
        case Opcode::RULES:
            if (!is_array($data))
                return false;

            // ...

            return true;
        // ... validaciones para PLAYERS_DETAILED, PLAYERS_BASIC, PING ...
    }
}
```

- **Validación por Opcode:** Cada `Opcode` tiene su propia lógica de validación específica. Por ejemplo:
   - Para `Opcode::INFO`: Garantiza que `$data` sea una instancia de `Server_Info`, que `$data->max_players > 0`, `$data->players` esté entre 0 y `max_players`, y que `$data->hostname` no esté vacío.
   - Para `Opcode::RULES` o listas de jugadores: Verifica si el retorno es un `array` y, si no está vacío, si el primer elemento es de la instancia de modelo esperada (`Server_Rule`, `Players_Detailed`, etc.).
- **Robustez:** Si la validación falla, la respuesta se considera inválida y se descarta. Esto fuerza al sistema a continuar los reintentos, como si el paquete nunca hubiera llegado, protegiendo la aplicación contra datos corruptos o inconsistentes del servidor.

#### Cálculo y Gestión de Timeout Adaptativo

La biblioteca implementa una estrategia de *timeout* sofisticada para equilibrar velocidad y resiliencia:

- **`Performance::METADATA_TIMEOUT`:** (0.8 segundos) Es el *timeout* base para consultas rápidas como `INFO` y `RULES`.
- **`Performance::PLAYER_LIST_BASE_TIMEOUT`:** (1.0 segundo) Es el *timeout* base para consultas de lista de jugadores.
- **`Performance::PING_MULTIPLIER`:** (2) Utilizado para ajustar el *timeout* en función del ping.
- **Ajuste por Ping:** En el método `Fetch_Player_Data`, el *timeout* para obtener la lista de jugadores se ajusta dinámicamente:
   ```
   Timeout de Jugadores = PLAYER_LIST_BASE_TIMEOUT + (Ping Cacheado * PING_MULTIPLIER / 1000)
   ```
   Este enfoque permite que los servidores con alta latencia (ping alto) tengan un *timeout* mayor, aumentando la probabilidad de éxito en la obtención de la lista completa de jugadores, que puede ser grande y tardar en ser procesada por el servidor.
- **Límite de Timeout:** `min($timeout, 2.0)` se usa en varias llamadas para imponer un límite máximo de 2.0 segundos para consultas de metadatos, previniendo esperas excesivas.

#### Métodos de Consulta Pública

| Método | Descripción Detallada | Comportamiento de Caching Interno |
| :--- | :--- | :--- |
| `Get_All()` | **Método recomendado para uso general.** Orquesta la obtención de `INFO`, `RULES`, `PLAYERS_DETAILED` (o `PLAYERS_BASIC` como fallback) en paralelo. Esto minimiza el tiempo total de la consulta, ya que los paquetes se envían casi simultáneamente y las respuestas se procesan a medida que llegan. Incluye una medición de `execution_time_ms` total. | Utiliza la caché de 2.0s (`$this->response_cache`) para cada opcode consultado dentro de la fase paralela. |
| `Is_Online()` | Realiza una consulta `INFO` rápida y devuelve `true` si el servidor responde con un `Server_Info` válido dentro del *timeout*, `false` en caso contrario. Es robusto, utilizando *retries* críticos. | Internamente, invoca `Fetch_Server_State()`, que usa la caché de 5.0s para `INFO`. |
| `Get_Ping()` | Devuelve el ping más reciente del servidor en milisegundos. Si `cached_ping` es nulo, fuerza una consulta `PING` dedicada con `Performance::FAST_PING_TIMEOUT` (0.3s) para obtener una medida rápida. | El `ping` se cachea y se actualiza siempre que `Execute_Query_Phase` recibe la primera respuesta. |
| `Get_Info()` | Devuelve un objeto `Server_Info` con detalles como hostname, gamemode, número de jugadores, etc. | Invoca `Fetch_Server_State()`, que utiliza la caché de 5.0s. |
| `Get_Rules()` | Devuelve un `array` de objetos `Server_Rule` que contiene todas las reglas configuradas en el servidor (ej: `mapname`, `weburl`). Incluye *retries* adicionales en caso de fallo inicial. | Utiliza la caché de 2.0s para el `Opcode::RULES`. |
| `Get_Players_Detailed()` | Devuelve un `array` de objetos `Players_Detailed` (id, nombre, score, ping para cada jugador). **Importante:** Esta consulta se omite si el número de jugadores en el servidor (`$this->cached_info->players`) es mayor o igual a `Query::LARGE_PLAYER_THRESHOLD` (150 por defecto), debido al riesgo de *timeouts* prolongados o fragmentación de paquetes. | Utiliza la caché de 2.0s para `Opcode::PLAYERS_DETAILED`. |
| `Get_Players_Basic()` | Devuelve un `array` de objetos `Players_Basic` (nombre, score para cada jugador). Es más ligera que la consulta detallada. Generalmente se llama como *fallback* si `Get_Players_Detailed()` falla o es omitida. | Utiliza la caché de 2.0s para `Opcode::PLAYERS_BASIC`. |

#### Comunicación RCON (`Send_Rcon`)

El método `Send_Rcon(string $rcon_password, string $command)` permite enviar comandos a la consola remota del servidor.

1.  **Validación de Argumentos:** Lanza `Invalid_Argument_Exception` si la contraseña o el comando están vacíos.
2.  **Socket Aislado:** Crea una nueva instancia de `Socket_Manager` (`$rcon_socket_manager`) para la sesión RCON, aislándola del socket principal de consulta para evitar interferencias.
3.  **Autenticación (`varlist`):** Antes de enviar el comando real, la biblioteca envía el comando "varlist" (hasta 3 intentos) para autenticar la contraseña RCON. Si `Send_Single_Rcon_Request` devuelve `null` o una respuesta vacía, se lanza una `Rcon_Exception`, indicando fallo de autenticación o que el RCON no está habilitado.
4.  **Envío del Comando Real:** Tras la autenticación exitosa, se envía el `$command`, también con hasta 3 intentos.
5.  **Manejo de Respuesta:** `Packet_Parser::Parse_Rcon()` decodifica la respuesta de texto del RCON. Si el servidor no devuelve una respuesta textual después de todos los intentos, se devuelve un mensaje genérico de éxito.
6.  **Limpieza:** El destructor de `$rcon_socket_manager` garantiza que el socket RCON se cierre después de la operación.

## Diagnóstico de Errores y Excepciones

La biblioteca utiliza una jerarquía de excepciones personalizadas para un manejo de errores limpio y predecible. En caso de fallo, se lanzará una de las siguientes excepciones.

### `Invalid_Argument_Exception`

**Causa:**
- **Hostname Vacío:** El `hostname` proporcionado en el constructor de `Samp_Query` es una cadena vacía.
- **Puerto Inválido:** El `port` proporcionado en el constructor está fuera del rango válido de 1 a 65535.
- **RCON:** La contraseña RCON o el comando RCON proporcionados para `Send_Rcon` están vacíos.

### `Connection_Exception`

**Causa:** Fallo de red o falta de respuesta esencial.
- **Resolución de Dominio Fallida:** `Domain_Resolver` no puede convertir el hostname en una IPv4 válida.
- **Fallo en la Creación del Socket:** `Socket_Manager` no puede crear o conectar el socket UDP.
- **Servidor Inaccesible/Offline:** El servidor no responde con un paquete `INFO` válido después de todos los intentos y *timeouts* (incluyendo *retries* de emergencia), generalmente indicando que el servidor está offline, la IP/puerto es incorrecto, o un firewall está bloqueando la comunicación.

### `Malformed_Packet_Exception`

**Causa:** Corrupción de datos a nivel de protocolo.
- **Encabezado Inválido:** `Packet_Parser` detecta un paquete que no comienza con la "magic string" `SAMP` o tiene una longitud total insuficiente.
- **Estructura de Paquete Inválida:** `Packet_Parser` encuentra inconsistencias en la estructura binaria, como una longitud de cadena que apunta fuera de los límites del paquete.
- **Resiliencia:** Esta excepción es frecuentemente manejada internamente por `Execute_Query_Phase` para activar un *retry* inmediato, pero puede propagarse si el problema persiste.

### `Rcon_Exception`

**Causa:** Error durante la comunicación RCON.
- **Fallo de Autenticación RCON:** El servidor no respondió a la autenticación RCON (vía comando `varlist`) después de 3 intentos, sugiriendo una contraseña incorrecta o RCON deshabilitado en el servidor.
- **Fallo en el Envío del Comando RCON:** El comando RCON real no obtuvo respuesta después de 3 intentos.

## Licencia

Copyright © **SA-MP Programming Community**

Este software está licenciado bajo los términos de la Licencia MIT ("Licencia"); puede utilizar este software de acuerdo con las condiciones de la Licencia. Puede obtener una copia de la Licencia en: [MIT License](https://opensource.org/licenses/MIT)

### Términos y Condiciones de Uso

#### 1. Permisos Otorgados

La presente licencia otorga, gratuitamente, a cualquier persona que obtenga una copia de este software y archivos de documentación asociados, los siguientes derechos:
* Usar, copiar, modificar, fusionar, publicar, distribuir, sublicenciar y/o vender copias del software sin restricciones
* Permitir que las personas a las que se les proporciona el software hagan lo mismo, sujeto a las siguientes condiciones

#### 2. Condiciones Obligatorias

Todas las copias o partes sustanciales del software deben incluir:
* El aviso de derechos de autor anterior
* Este aviso de permiso
* El aviso de exención de responsabilidad a continuación

#### 3. Derechos de Autor

El software y toda la documentación asociada están protegidos por leyes de derechos de autor. La **SA-MP Programming Community** mantiene la titularidad de los derechos de autor originales del software.

#### 4. Exención de Garantías y Limitación de Responsabilidad

EL SOFTWARE SE PROPORCIONA "TAL CUAL", SIN GARANTÍA DE NINGÚN TIPO, EXPRESA O IMPLÍCITA, INCLUYENDO PERO NO LIMITADO A LAS GARANTÍAS DE COMERCIABILIDAD, IDONEIDAD PARA UN PROPÓSITO PARTICULAR Y NO INFRACCIÓN.

EN NINGÚN CASO LOS AUTORES O TITULARES DE LOS DERECHOS DE AUTOR SERÁN RESPONSABLES DE CUALQUIER RECLAMACIÓN, DAÑOS U OTRA RESPONSABILIDAD, YA SEA EN UNA ACCIÓN DE CONTRATO, AGRAVIO O DE OTRO MODO, QUE SURJA DE, FUERA DE O EN CONEXIÓN CON EL SOFTWARE O EL USO U OTROS TRATOS EN EL SOFTWARE.

---

Para información detallada sobre la Licencia MIT, consulte: https://opensource.org/licenses/MIT