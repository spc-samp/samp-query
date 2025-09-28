# SA-MP Query - PHP

<div align="center">

![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Architecture](https://img.shields.io/badge/Protocol-UDP-blueviolet?style=for-the-badge)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**Uma biblioteca PHP robusta e resiliente, projetada para consultar o estado e as informações de servidores SA-MP (San Andreas Multiplayer) e OMP (Open Multiplayer).**

</div>

## Idiomas

- Deutsch: [README](translations/Deutsch/README.md)
- English: [README](translations/English/README.md)
- Español: [README](translations/Espanol/README.md)
- Français: [README](translations/Francais/README.md)
- Italiano: [README](translations/Italiano/README.md)
- Polski: [README](translations/Polski/README.md)
- Русский: [README](translations/Русский/README.md)
- Svenska: [README](translations/Svenska/README.md)
- Türkçe: [README](translations/Turkce/README.md)

## Índice

- [SA-MP Query - PHP](#sa-mp-query---php)
  - [Idiomas](#idiomas)
  - [Índice](#índice)
  - [Visão Geral](#visão-geral)
  - [Princípios de Design e Arquitetura](#princípios-de-design-e-arquitetura)
    - [Arquitetura Modular](#arquitetura-modular)
    - [Resiliência: Backoff, Retries e Caching](#resiliência-backoff-retries-e-caching)
    - [Otimização de Performance: Paralelismo e Adaptação de Timeout](#otimização-de-performance-paralelismo-e-adaptação-de-timeout)
    - [Programação Orientada a Objetos (OOP) Moderna (PHP 8.1+)](#programação-orientada-a-objetos-oop-moderna-php-81)
  - [Requisitos](#requisitos)
  - [Instalação e Uso Básico](#instalação-e-uso-básico)
    - [Inicialização da Classe `Samp_Query`](#inicialização-da-classe-samp_query)
    - [`Get_All()`: Consulta Completa e Otimizada](#get_all-consulta-completa-e-otimizada)
    - [`Is_Online()`: Verificação Rápida de Status](#is_online-verificação-rápida-de-status)
    - [`Get_Ping()`: Obtenção do Ping do Servidor](#get_ping-obtenção-do-ping-do-servidor)
    - [`Get_Info()`: Detalhes Essenciais do Servidor](#get_info-detalhes-essenciais-do-servidor)
    - [`Get_Rules()`: Regras Configuradas do Servidor](#get_rules-regras-configuradas-do-servidor)
    - [`Get_Players_Detailed()`: Lista de Jogadores com Detalhes](#get_players_detailed-lista-de-jogadores-com-detalhes)
    - [`Get_Players_Basic()`: Lista Básica de Jogadores](#get_players_basic-lista-básica-de-jogadores)
    - [`Send_Rcon()`: Envio de Comandos Remotos](#send_rcon-envio-de-comandos-remotos)
  - [Estrutura Detalhada da Biblioteca e Fluxo de Execução](#estrutura-detalhada-da-biblioteca-e-fluxo-de-execução)
    - [1. `constants.php`: O Coração da Configuração](#1-constantsphp-o-coração-da-configuração)
    - [2. `opcode.php`: O Enum de Opcodes do Protocolo](#2-opcodephp-o-enum-de-opcodes-do-protocolo)
    - [3. `exceptions.php`: A Hierarquia de Exceções Customizadas](#3-exceptionsphp-a-hierarquia-de-exceções-customizadas)
    - [4. `server_types.php`: Os Modelos de Dados Imutáveis](#4-server_typesphp-os-modelos-de-dados-imutáveis)
    - [5. `autoloader.php`: O Carregador Automático de Classes](#5-autoloaderphp-o-carregador-automático-de-classes)
    - [6. `logger.php`: O Subsistema de Log](#6-loggerphp-o-subsistema-de-log)
    - [7. `domain_resolver.php`: A Resolução de Domínio com Cache Persistente](#7-domain_resolverphp-a-resolução-de-domínio-com-cache-persistente)
    - [8. `socket_manager.php`: O Gerenciador de Conexão UDP Robusto](#8-socket_managerphp-o-gerenciador-de-conexão-udp-robusto)
    - [9. `packet_builder.php`: O Construtor de Pacotes Binários](#9-packet_builderphp-o-construtor-de-pacotes-binários)
    - [10. `packet_parser.php`: O Decodificador de Pacotes com Tratamento de Codificação](#10-packet_parserphp-o-decodificador-de-pacotes-com-tratamento-de-codificação)
    - [11. `samp-query.php`: A Classe Principal (O Orquestrador Completo)](#11-samp-queryphp-a-classe-principal-o-orquestrador-completo)
      - [Ciclo de Vida da Consulta: A Jornada de um Pacote](#ciclo-de-vida-da-consulta-a-jornada-de-um-pacote)
        - [1. Inicialização e Resolução de Domínio](#1-inicialização-e-resolução-de-domínio)
        - [2. `Fetch_Server_State()`: Cache e Consulta Crítica de INFO/PING](#2-fetch_server_state-cache-e-consulta-crítica-de-infoping)
        - [3. `Attempt_Query()`: A Estratégia de Retries Otimizada](#3-attempt_query-a-estratégia-de-retries-otimizada)
        - [4. `Execute_Query_Phase()`: O Motor de Comunicação com Detecção de Ping](#4-execute_query_phase-o-motor-de-comunicação-com-detecção-de-ping)
        - [5. `Validate_Response()`: A Camada de Validação Semântica](#5-validate_response-a-camada-de-validação-semântica)
      - [Cálculo e Gerenciamento de Timeout Adaptativo](#cálculo-e-gerenciamento-de-timeout-adaptativo)
      - [Métodos de Consulta Pública](#métodos-de-consulta-pública)
      - [Comunicação RCON (`Send_Rcon`)](#comunicação-rcon-send_rcon)
  - [Diagnóstico de Erros e Exceções](#diagnóstico-de-erros-e-exceções)
    - [`Invalid_Argument_Exception`](#invalid_argument_exception)
    - [`Connection_Exception`](#connection_exception)
    - [`Malformed_Packet_Exception`](#malformed_packet_exception)
    - [`Rcon_Exception`](#rcon_exception)
  - [Licença](#licença)
    - [Termos e Condições de Uso](#termos-e-condições-de-uso)
      - [1. Permissões Concedidas](#1-permissões-concedidas)
      - [2. Condições Obrigatórias](#2-condições-obrigatórias)
      - [3. Direitos Autorais](#3-direitos-autorais)
      - [4. Isenção de Garantias e Limitação de Responsabilidade](#4-isenção-de-garantias-e-limitação-de-responsabilidade)

## Visão Geral

A biblioteca **SA-MP Query - PHP** é uma solução de alto desempenho e tolerante a falhas para desenvolvedores PHP que necessitam interagir com servidores de jogos baseados no protocolo SA-MP/OMP (UDP). Seu propósito é encapsular a complexidade do protocolo binário de consulta em uma API PHP limpa e intuitiva, permitindo que aplicações web, launchers e utilitários obtenham informações detalhadas sobre o estado do servidor (jogadores, regras, ping, etc.) de forma rápida e confiável.

O design da biblioteca foca em três pilares principais: **Resiliência**, **Performance** e **Modularidade**. Ela é construída para lidar com a natureza não confiável do protocolo UDP, implementando um sistema avançado de tentativas e *backoff* para garantir que a informação seja obtida mesmo em condições de rede desfavoráveis ou servidores com alta latência.

## Princípios de Design e Arquitetura

### Arquitetura Modular

A biblioteca é dividida em componentes de responsabilidade única, cada um encapsulado em sua própria classe e arquivo.

- **Infraestrutura de Rede:** `Domain_Resolver`, `Socket_Manager`.
- **Protocolo:** `Packet_Builder`, `Packet_Parser`, `Opcode` (Enum).
- **Lógica de Negócios (Orquestração):** `Samp_Query`.
- **Modelos de Dados:** `Server_Info`, `Players_Detailed`, etc.

### Resiliência: Backoff, Retries e Caching

O protocolo UDP não garante a entrega de pacotes. A classe `Samp_Query` mitiga essa falha com um ciclo de consulta sofisticado.

- **Múltiplas Tentativas Adaptativas:** O método `Attempt_Query` implementa um ciclo com até `Query::ATTEMPTS` (5 por padrão) e o dobro disso para consultas críticas.
- **Estratégia de Backoff:** O *backoff* exponencial é implementado em `Execute_Query_Phase`. Após o primeiro envio, o intervalo de novas tentativas de escuta (loop `while`) aumenta de `Performance::INITIAL_RETRY_INTERVAL` (0.08s) pelo `Performance::BACKOFF_FACTOR` (1.3), até o limite de 0.2s. Isso evita a sobrecarga de pacotes e aumenta a chance de uma resposta no tempo.
- **Caching de Respostas:** Respostas recentes (válidas por 2.0 segundos) são armazenadas em `response_cache`, eliminando a necessidade de repetir consultas de metadados durante a execução de `Get_All()`.

### Otimização de Performance: Paralelismo e Adaptação de Timeout

- **Consultas Paralelas (Fan-out):** O método `Get_All()` envia solicitações para `INFO`, `RULES` e `PLAYERS` simultaneamente (em `$jobs`), permitindo que as respostas cheguem fora de ordem, minimizando o tempo de espera total.
- **Caching de DNS Persistente:** O `Domain_Resolver` armazena o endereço IP resolvido em um cache de arquivo local com um TTL de 3600 segundos, evitando atrasos na resolução de domínio em chamadas subsequentes.
- **Timeout Adaptativo:** O *timeout* de consultas de dados grandes (como a lista de jogadores) é ajustado dinamicamente com base no `cached_ping` do servidor:
   ```
   Timeout Ajustado = Base Timeout + (Cached Ping * Ping Multiplier / 1000)
   ```
   Essa lógica (implementada em `Fetch_Player_Data`) garante que servidores com latência alta tenham tempo suficiente para responder, sem comprometer a rapidez em servidores com baixa latência.

### Programação Orientada a Objetos (OOP) Moderna (PHP 8.1+)

A biblioteca utiliza recursos modernos do PHP para garantir segurança e clareza:

- **Tipagem Estrita** (`declare(strict_types=1)`).
- **Propriedades de Leitura Apenas** (`public readonly` em `Samp_Query` e nos modelos de dados) para garantir a imutabilidade dos dados.
- **Enums Tipados** (`enum Opcode: string`) para controle seguro do protocolo.
- **Constructor Property Promotion** (no `Samp_Query::__construct` e modelos).

## Requisitos

- **PHP:** Versão **8.1 ou superior**.
- **Extensões PHP:** `sockets` e `mbstring` (para manipulação de codificação UTF-8).

## Instalação e Uso Básico

Para começar a usar a biblioteca **SA-MP Query - PHP**, basta incluir o arquivo `samp-query.php` em seu projeto. Este arquivo se encarregará de carregar automaticamente todas as dependências através do seu autoloader interno.

```php
<?php
// Inclua a classe principal. Ela se encarregará de carregar as dependências via autoloader.
require_once 'path/to/samp-query/samp-query.php'; 

// Use o namespace da classe principal
use Samp_Query\Samp_Query;
// Inclua as exceções para um tratamento de erro mais específico
use Samp_Query\Exceptions\Invalid_Argument_Exception;
use Samp_Query\Exceptions\Connection_Exception;
use Samp_Query\Exceptions\Rcon_Exception;

// Instancie a classe Samp_Query, envolvendo-a em um bloco try-catch para lidar com erros de inicialização.
try {
    $server_query = new Samp_Query("play.example-samp-server.com", 7777);
    // Agora você pode usar os métodos públicos de $server_query
}
catch (Invalid_Argument_Exception $e) {
    echo "Erro de Argumento: " . $e->getMessage() . "\n";
}
catch (Connection_Exception $e) {
    echo "Erro de Conexão: " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Erro inesperado durante a inicialização: " . $e->getMessage() . "\n";
}
```

### Inicialização da Classe `Samp_Query`

A classe `Samp_Query` é a porta de entrada para todas as funcionalidades. Seu construtor requer o `hostname` (ou endereço IP) e a `port` do servidor que você deseja consultar.

```php
/**
 * Inicializa uma nova instância da biblioteca Samp_Query.
 *
 * @param string $hostname O hostname ou endereço IP do servidor SA-MP/OMP.
 * @param int $port A porta UDP do servidor (normalmente 7777).
 * 
 * @throws Invalid_Argument_Exception Se o hostname for vazio ou a porta for inválida.
 * @throws Connection_Exception Se a resolução de DNS falhar ou o socket não puder ser criado.
 */
public function __construct(public readonly string $hostname, public readonly int $port)
```

<br>

---

### `Get_All()`: Consulta Completa e Otimizada

Este é o método mais abrangente e recomendado. Ele executa diversas consultas (INFO, RULES, PLAYERS) em paralelo e de forma otimizada, minimizando o tempo total de resposta e retornando um array associativo completo com todas as informações disponíveis.

```php
/**
 * Retorna todas as informações disponíveis do servidor em uma única chamada otimizada.
 * Inclui: is_online, ping, info (Server_Info), rules (array de Server_Rule),
 * players_detailed (array de Players_Detailed), players_basic (array de Players_Basic),
 * e execution_time_ms.
 *
 * @return array Um array associativo contendo todas as informações do servidor.
 * 
 * @throws Connection_Exception Se a consulta INFO, essencial para o estado do servidor, falhar.
 */
public function Get_All(): array
```

Exemplo de Uso:

```php
<?php
// ... (inicialização da classe $server_query) ...

try {
    $data = $server_query->Get_All();
    
    if ($data['is_online']) {
        echo "## Servidor Online: {$data['info']->hostname} ({$server_query->ip}:{$server_query->port}) ##\n";
        echo "Ping: {$data['ping']}ms | Tempo Total da Consulta: {$data['execution_time_ms']}ms\n";
        echo "Jogadores: {$data['info']->players} / {$data['info']->max_players}\n";
        echo "Modo de Jogo: {$data['info']->gamemode}\n";
        echo "Idioma: {$data['info']->language}\n";
        echo "Protegido por Senha: " . ($data['info']->password ? "Sim" : "Não") . "\n\n";

        // Exemplo de lista de jogadores detalhada
        if (!empty($data['players_detailed'])) {
            echo "--- Jogadores Detalhados ({$data['info']->players} Ativos) ---\n";

            foreach ($data['players_detailed'] as $player)
                echo "ID: {$player->id}, Nome: {$player->name}, Ping: {$player->ping}ms, Score: {$player->score}\n";
        }
        elseif (!empty($data['players_basic'])) {
            echo "--- Jogadores Básicos ({$data['info']->players} Ativos) (Fallback) ---\n";

            foreach ($data['players_basic'] as $player)
                echo "Nome: {$player->name}, Score: {$player->score}\n";
        }
        else
            echo "Nenhum jogador online ou lista indisponível (talvez muitos jogadores).\n";
        
        // Exemplo de regras do servidor
        if (!empty($data['rules'])) {
            echo "\n--- Regras do Servidor ---\n";

            foreach ($data['rules'] as $rule)
                echo "{$rule->name}: {$rule->value}\n";
        }
    }
    else
        echo "O servidor está atualmente offline ou inacessível.\n";
}
catch (Connection_Exception $e) {
    echo "Falha de conexão ao tentar obter todas as informações: " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Erro inesperado ao consultar todas as informações: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Is_Online()`: Verificação Rápida de Status

Verifica se o servidor está online e responde a consultas, sem buscar detalhes adicionais. Ideal para um simples "liveness check".

```php
/**
 * Verifica se o servidor está online e acessível.
 *
 * @return bool Retorna true se o servidor estiver online e responder com INFO válido, false caso contrário.
 */
public function Is_Online(): bool
```

Exemplo de Uso:

```php
<?php
// ... (inicialização da classe $server_query) ...

if ($server_query->Is_Online())
    echo "O servidor SA-MP está online e respondendo!\n";
else
    echo "O servidor SA-MP está offline ou não respondeu a tempo.\n";
```

<br>

---

### `Get_Ping()`: Obtenção do Ping do Servidor

Retorna o tempo de latência (ping) do servidor em milissegundos. Este valor é cacheado internamente para otimização.

```php
/**
 * Retorna o ping atual do servidor em milissegundos.
 * Se o ping ainda não foi calculado, uma consulta PING rápida será executada.
 *
 * @return int|null O valor do ping em milissegundos, ou null se não for possível obtê-lo.
 */
public function Get_Ping(): ?int
```

Exemplo de Uso:

```php
<?php
// ... (inicialização da classe $server_query) ...

try {
    $ping = $server_query->Get_Ping();

    if ($ping !== null)
        echo "O ping do servidor é: {$ping}ms.\n";
    else
        echo "Não foi possível obter o ping do servidor.\n";
}
catch (Connection_Exception $e) {
    echo "Erro ao obter o ping: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Info()`: Detalhes Essenciais do Servidor

Obtém as informações básicas do servidor, como hostname, modo de jogo, número de jogadores, etc. Retorna um objeto `Server_Info`.

```php
/**
 * Retorna os detalhes básicos do servidor (hostname, jogadores, gamemode, etc.).
 * Os dados são cacheados por um curto período para otimização.
 *
 * @return Server_Info|null Um objeto Server_Info, ou null se as informações não puderem ser obtidas.
 */
public function Get_Info(): ?Server_Info
```

Exemplo de Uso:

```php
<?php
use Samp_Query\Models\Server_Info;
// ... (inicialização da classe $server_query) ...

try {
    /** @var Server_Info|null $info */
    $info = $server_query->Get_Info();

    if ($info) {
        echo "--- Informações do Servidor ---\n";
        echo "Hostname: {$info->hostname}\n";
        echo "Gamemode: {$info->gamemode}\n";
        echo "Jogadores: {$info->players} / {$info->max_players}\n";
        echo "Idioma: {$info->language}\n";
        echo "Protegido por Senha: " . ($info->password ? "Sim" : "Não") . "\n";
    }
    else
        echo "Não foi possível obter as informações do servidor.\n";
}
catch (Connection_Exception $e) {
    echo "Erro ao obter informações do servidor: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Rules()`: Regras Configuradas do Servidor

Recupera todas as regras configuradas no servidor, como `mapname`, `weburl`, `weather`, etc., retornando-as como um array de objetos `Server_Rule`.

```php
/**
 * Retorna um array de objetos Server_Rule, cada um contendo o nome e o valor de uma regra do servidor.
 * Os dados são cacheados para otimização.
 *
 * @return array Um array de Samp_Query\Models\Server_Rule. Pode ser vazio se não houver regras.
 */
public function Get_Rules(): array
```

Exemplo de Uso:

```php
<?php
use Samp_Query\Models\Server_Rule;
// ... (inicialização da classe $server_query) ...

try {
    $rules = $server_query->Get_Rules();

    if (!empty($rules)) {
        echo "--- Regras do Servidor ---\n";

        foreach ($rules as $rule)
            echo "{$rule->name}: {$rule->value}\n";

        // Exemplo de como acessar uma regra específica:
        $mapname_rule = array_filter($rules, fn(Server_Rule $r) => $r->name === 'mapname');

        if (!empty($mapname_rule))
            echo "\nMapa atual: " . reset($mapname_rule)->value . "\n";
    }
    else
        echo "Nenhuma regra encontrada para este servidor.\n";
}
catch (Connection_Exception $e) {
    echo "Erro ao obter as regras do servidor: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Players_Detailed()`: Lista de Jogadores com Detalhes

Obtém uma lista detalhada dos jogadores atualmente online, incluindo ID, nome, score e ping.

> [!CAUTION]
> Para otimizar o desempenho e evitar pacotes UDP excessivamente grandes que podem ser perdidos ou fragmentados, este método não buscará a lista detalhada de jogadores se o número total de jogadores for igual ou superior a `Query::LARGE_PLAYER_THRESHOLD` (150 por padrão). Nesses casos, um array vazio será retornado. Considere usar `Get_Players_Basic()` como um fallback.

```php
/**
 * Retorna um array de objetos Players_Detailed (ID, nome, score, ping) para cada jogador online.
 * Esta consulta pode ser ignorada se o número de jogadores for muito alto (ver Query::LARGE_PLAYER_THRESHOLD).
 *
 * @return array Um array de Samp_Query\Models\Players_Detailed. Pode ser vazio.
 */
public function Get_Players_Detailed(): array
```

Exemplo de Uso:

```php
<?php
use Samp_Query\Models\Players_Detailed;
// ... (inicialização da classe $server_query) ...

try {
    $players_detailed = $server_query->Get_Players_Detailed();

    if (!empty($players_detailed)) {
        echo "--- Jogadores Online (Detalhado) ---\n";

        foreach ($players_detailed as $player)
            echo "ID: {$player->id}, Nome: {$player->name}, Score: {$player->score}, Ping: {$player->ping}ms\n";
    }
    else
        echo "Nenhum jogador online ou lista detalhada indisponível.\n";
}
catch (Connection_Exception $e) {
    echo "Erro ao obter a lista detalhada de jogadores: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Players_Basic()`: Lista Básica de Jogadores

Fornece uma lista mais leve de jogadores, contendo apenas nome e score. Útil como alternativa quando a lista detalhada não está disponível ou para reduzir a carga de dados.

```php
/**
 * Retorna um array de objetos Players_Basic (nome, score) para cada jogador online.
 * Útil como uma alternativa mais leve ou fallback quando Get_Players_Detailed() não é viável.
 *
 * @return array Um array de Samp_Query\Models\Players_Basic. Pode ser vazio.
 */
public function Get_Players_Basic(): array
```

Exemplo de Uso:

```php
<?php
use Samp_Query\Models\Players_Basic;
// ... (inicialização da classe $server_query) ...

try {
    $players_basic = $server_query->Get_Players_Basic();

    if (!empty($players_basic)) {
        echo "--- Jogadores Online (Básico) ---\n";

        foreach ($players_basic as $player)
            echo "Nome: {$player->name}, Score: {$player->score}\n";
    }
    else
        echo "Nenhum jogador online ou lista básica indisponível.\n";
}
catch (Connection_Exception $e) {
    echo "Erro ao obter a lista básica de jogadores: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Send_Rcon()`: Envio de Comandos Remotos

Permite enviar comandos para o console RCON do servidor, como alterar regras, banir jogadores, etc. Requer a senha RCON do servidor.

> [!WARNING]
> A função RCON é sensível e pode causar alterações no servidor. Use com cautela e apenas com senhas de confiança.
> É crucial que a senha RCON seja **correta** e que o RCON esteja **habilitado** no servidor (configuração `rcon_password` no `server.cfg`).

```php
/**
 * Envia um comando RCON para o servidor.
 * Realiza autenticação com 'varlist' e envia o comando.
 *
 * @param string $rcon_password A senha RCON do servidor.
 * @param string $command O comando a ser executado (ex: "gmx", "kick ID").
 * @return string A resposta do servidor ao comando RCON, ou uma mensagem de status.
 * 
 * @throws Invalid_Argument_Exception Se a senha ou o comando RCON estiverem vazios.
 * @throws Rcon_Exception Se a autenticação RCON falhar ou o comando não obtiver resposta.
 * @throws Connection_Exception Em caso de falha de conexão durante a operação RCON.
 */
public function Send_Rcon(string $rcon_password, string $command): string
```

Exemplo de Uso:

```php
<?php
// ... (inicialização da classe $server_query) ...

$rcon_password = "sua_senha_secreta_aqui"; 
$command_to_send = "gmx"; // Exemplo: reiniciar o gamemode

try {
    echo "Tentando enviar comando RCON: '{$command_to_send}'...\n";
    $response = $server_query->Send_Rcon($rcon_password, $command_to_send);
    echo "Resposta do RCON: {$response}\n";

    // Exemplo de comando para "dizer" algo no servidor (requer RCON)
    $response_say = $server_query->Send_Rcon($rcon_password, "say Olá do meu script PHP!");
    echo "Resposta do RCON (say): {$response_say}\n";
}
catch (Invalid_Argument_Exception $e) {
    echo "Erro RCON (Argumento Inválido): " . $e->getMessage() . "\n";
}
catch (Rcon_Exception $e) {
    echo "Erro RCON: Falha de autenticação ou comando não executado. " . $e->getMessage() . "\n";
}
catch (Connection_Exception $e) {
    echo "Erro RCON (Conexão): " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Erro inesperado ao enviar RCON: " . $e->getMessage() . "\n";
}
```

## Estrutura Detalhada da Biblioteca e Fluxo de Execução

A biblioteca **SA-MP Query - PHP** é meticulosamente organizada em diversos arquivos, cada um com uma responsabilidade bem definida. Esta seção explora cada componente em detalhes, revelando as decisões de design e a lógica subjacente.

### 1. `constants.php`: O Coração da Configuração

Este arquivo centraliza todos os parâmetros de configuração "mágicos" da biblioteca, garantindo que aspectos como *timeouts*, número de tentativas e tamanhos de buffer sejam facilmente ajustáveis e consistentes em todo o projeto.

```php
// ...
final class Query {
    public const ATTEMPTS = 5; // Número máximo de tentativas de consulta
    public const LARGE_PLAYER_THRESHOLD = 150; // Limite de jogadores para consulta detalhada
}

final class Protocol {
    public const UDP_READ_BUFFER_SIZE = 32768; // 32KB para o buffer de leitura
    public const KERNEL_RECEIVE_BUFFER_SIZE = 4194304; // 4MB para o buffer do kernel
}
// ...
```

- **Final Classes e Constantes:** As classes são `final` e as propriedades são `public const`, garantindo imutabilidade e acessibilidade global em tempo de compilação.
- **Granularidade e Semântica:** As constantes são categorizadas por seu domínio (`Query`, `Protocol`, `Performance`, `Logger`, `DNS`), facilitando a compreensão e a manutenção. Por exemplo, `Query::LARGE_PLAYER_THRESHOLD` define o ponto em que a busca por listas detalhadas de jogadores pode ser evitada para otimização, devido ao volume de dados e potencial para *timeouts*.

### 2. `opcode.php`: O Enum de Opcodes do Protocolo

Este arquivo define os códigos de operação (opcodes) utilizados para as diferentes consultas ao servidor SA-MP/OMP, encapsulando-os em um `enum` tipado.

```php
// ...
enum Opcode: string {
    case INFO = 'i';
    case RULES = 'r';
    case PLAYERS_DETAILED = 'd';
    // ...
}
```

- **`Enum` Tipado (PHP 8.1+):** A utilização de um `enum` (`Opcode: string`) com valores de tipo `string` garante que os opcodes sejam sempre válidos e que o código seja semanticamente claro. Isso substitui o uso de strings literais "mágicas", tornando o código mais legível e menos propenso a erros de digitação.

### 3. `exceptions.php`: A Hierarquia de Exceções Customizadas

Este arquivo estabelece uma hierarquia de exceções customizadas, permitindo um tratamento de erros mais granular e específico para os diversos tipos de falhas que podem ocorrer na biblioteca.

```php
// ...
class Query_Exception extends \Exception {}
class Invalid_Argument_Exception extends Query_Exception {}
class Connection_Exception extends Query_Exception {}
// ...
```

- **Herança de `\Exception`:** Todas as exceções herdam de `Query_Exception` (que por sua vez estende `\Exception`), permitindo capturar grupos de erros (`Connection_Exception` e `Timeout_Exception` são mais específicas que `Query_Exception`) ou todas as exceções da biblioteca com um `catch` mais genérico.
- **Clareza no Diagnóstico:** Os nomes descritivos das exceções facilitam o diagnóstico e a recuperação de erros na aplicação cliente.

### 4. `server_types.php`: Os Modelos de Dados Imutáveis

Este arquivo define as classes que representam os modelos de dados para as informações retornadas pelo servidor, garantindo a integridade dos dados através de imutabilidade.

```php
// ...
final class Server_Info {
    public function __construct(
        public readonly bool $password,
        public readonly int $players,
        // ... outras propriedades readonly ...
    ) {}
}
// ...
```

- **`final class`:** As classes são `final`, prevenindo extensões e garantindo sua estrutura e comportamento.
- **`public readonly` Properties (PHP 8.1+):** Todas as propriedades são declaradas como `public readonly`. Isso significa que, uma vez que o objeto é construído, seus valores não podem ser alterados, garantindo a integridade dos dados recebidos do servidor.
- **Constructor Property Promotion (PHP 8.1+):** As propriedades são declaradas diretamente no construtor, simplificando o código e reduzindo o *boilerplate*.

### 5. `autoloader.php`: O Carregador Automático de Classes

Este arquivo é responsável por carregar dinamicamente as classes da biblioteca quando elas são necessárias, seguindo o padrão PSR-4.

```php
// ...
spl_autoload_register(function ($class) {
    $prefix = 'Samp_Query\\';
    $base_dir = __DIR__ . '/../modules/'; // Mapeia o namespace para o diretório
    // ... lógica de carregamento ...
});

// Inclui arquivos essenciais que não são classes, ou que precisam de carregamento antecipado
require_once __DIR__ . '/../modules/constants.php';
require_once __DIR__ . '/../modules/exceptions.php';
// ...
```

- **`spl_autoload_register()`:** Registra uma função anônima que é chamada automaticamente pelo PHP quando uma classe não definida é referenciada, agilizando o desenvolvimento e a manutenção.
- **Inclusão Direta de Configurações:** Arquivos como `constants.php` e `exceptions.php` são incluídos diretamente. Isso garante que suas definições estejam disponíveis antes que qualquer classe que os utilize seja instanciada pelo autoloader.

### 6. `logger.php`: O Subsistema de Log

A classe `Logger` fornece um mecanismo simples para registrar mensagens de erro e eventos importantes em um arquivo de log, útil para depuração e monitoramento.

```php
// ...
final class Logger {
    public function Log(string $message): void {
        if (is_file(Logger_Config::FILE) && filesize(Logger_Config::FILE) > Logger_Config::MAX_SIZE_BYTES)
            unlink(Logger_Config::FILE); // Remove o log se exceder o tamanho

        $timestamp = date('Y-m-d H:i:s');
        $log_entry = "[{$timestamp}] {$message}" . PHP_EOL;
        
        file_put_contents(Logger_Config::FILE, $log_entry, FILE_APPEND | LOCK_EX); // Adiciona com bloqueio
    }
}
```

- **Limpeza Automática:** O logger verifica o tamanho do arquivo de log (`Logger_Config::FILE`). Se ele exceder `Logger_Config::MAX_SIZE_BYTES` (10 MB por padrão), o arquivo é excluído para evitar que cresça indefinidamente.
- **Bloqueio de Arquivo (`LOCK_EX`):** `file_put_contents` utiliza `LOCK_EX` para garantir que apenas um processo escreva no arquivo de log por vez, prevenindo corrupção em ambientes multi-threaded/multi-processo.

### 7. `domain_resolver.php`: A Resolução de Domínio com Cache Persistente

A classe `Domain_Resolver` é responsável por converter nomes de host (como `play.example.com`) em endereços IP (como `192.0.2.1`). Ela implementa um sistema de cache em disco para otimizar o desempenho.

```php
// ...
final class Domain_Resolver {
    public function Resolve(string $hostname): string {
        if (filter_var($hostname, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
            return $hostname; // Já é um IP

        $cache_file = DNS_Config::CACHE_DIR . md5($hostname) . '.cache';

        if (file_exists($cache_file) && (time() - filemtime($cache_file)) < DNS_Config::CACHE_TTL_SECONDS) {
            $ip = file_get_contents($cache_file);

            if ($ip && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
                return $ip;
        }
        
        $ip = gethostbyname($hostname); // Resolução real do DNS
        // ... lógica de validação e salvamento em cache ...

        return $ip;
    }
    // ...
}
```

- **Cache Persistente:** Antes de chamar `gethostbyname()`, verifica se o IP já está armazenado em um arquivo de cache (`dns/` + hash MD5 do hostname). O cache é considerado válido se não tiver excedido `DNS_Config::CACHE_TTL_SECONDS` (3600 segundos ou 1 hora por padrão).
- **Validação Robusta:** O IP resolvido (ou lido do cache) é validado com `filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)` para garantir que seja um IPv4 válido. Se a resolução falhar, uma `Query_Exception` é lançada.

### 8. `socket_manager.php`: O Gerenciador de Conexão UDP Robusto

A classe `Socket_Manager` encapsula a complexidade da criação, configuração e gerenciamento de um socket UDP para comunicação com o servidor de jogo.

```php
// ...
final class Socket_Manager {
    private $socket = null;
    // ...
    private function Connect(): void {
        if (is_resource($this->socket))
            return;

        $context = stream_context_create([
            'socket' => ['so_rcvbuf' => Protocol::KERNEL_RECEIVE_BUFFER_SIZE] // Aumenta o buffer para 4MB
        ]);

        $this->socket = stream_socket_client(
            "udp://{$this->host}:{$this->port}", 
            $err_no, $err_str, 
            (float)ini_get("default_socket_timeout"), 
            STREAM_CLIENT_CONNECT, // Conecta o socket ao endereço remoto
            $context
        );

        if ($this->socket === false)
            throw new Connection_Exception("Could not create socket...");
    }
    // ...
}
```

- **`stream_socket_client` com `STREAM_CLIENT_CONNECT`:** Esta *flag* instrui o sistema operacional a "conectar" o socket UDP ao endereço remoto. Embora o UDP seja sem conexão, "conectar" o socket permite otimizações de desempenho no nível do kernel, como não precisar especificar o endereço remoto em cada chamada `fwrite` ou `stream_socket_recvfrom`, resultando em menor sobrecarga.
- **Buffer de Recebimento do Kernel:** O `stream_context_create` é utilizado para aumentar o tamanho do buffer de recebimento do kernel (`so_rcvbuf`) para `Protocol::KERNEL_RECEIVE_BUFFER_SIZE` (4MB). Isso é crucial para evitar perda de pacotes (overflow do buffer) ao receber respostas grandes, como listas de jogadores detalhadas de servidores movimentados.
- **RAII via `__destruct`:** O método `Disconnect()` é invocado automaticamente no destrutor (`__destruct`), garantindo que o socket seja fechado e os recursos liberados, mesmo em caso de exceções.
- **Timeout Dinâmico:** `Set_Timeout` ajusta com precisão os timeouts de leitura/escrita do socket usando `stream_set_timeout`, fundamental para a lógica de *retries* e *backoff*.

### 9. `packet_builder.php`: O Construtor de Pacotes Binários

A classe `Packet_Builder` é responsável por serializar os dados da consulta em um formato binário específico que o servidor SA-MP/OMP pode entender.

```php
// ...
final class Packet_Builder {
    // ...
    private function Build_Header(): string {
        $packet = 'SAMP';
        $packet .= pack('c4', ...$this->ip_parts); // IP em 4 bytes
        $packet .= pack('v', $this->port); // Porta em 2 bytes (little-endian)

        return $packet;
    }
    
    public function Build(string $opcode): string {
        $packet = $this->Build_Header();
        $packet .= $opcode;

        if ($opcode === Opcode::PING->value)
            $packet .= random_bytes(4); // Payload aleatório para PING
        
        return $packet;
    }
    // ...
}
```

- **`pack()` para Formato Binário:** Utiliza a função `pack()` do PHP para converter os dados (IP, porta, comprimentos de string) em seu formato binário correto, como `c4` para 4 bytes de caracteres (IP) e `v` para inteiro sem sinal de 16 bits (porta e comprimentos), que é *little-endian*.
- **Cabeçalho Padrão:** O `Build_Header()` cria o cabeçalho 'SAMP' de 10 bytes que precede todos os pacotes.
- **Estrutura RCON:** O `Build_Rcon` formata o pacote RCON com o opcode 'x' seguido pelo comprimento da senha, a senha, o comprimento do comando e o comando em si.

### 10. `packet_parser.php`: O Decodificador de Pacotes com Tratamento de Codificação

A classe `Packet_Parser` é a contraparte do `Packet_Builder`, responsável por interpretar as respostas binárias recebidas do servidor e convertê-las em dados PHP estruturados.

```php
// ...
final class Packet_Parser {
    private int $offset = Protocol::MIN_PACKET_LENGTH; // Inicia após o cabeçalho (11 bytes)
    // ...
    public function __construct(private readonly string $data) {
        // Validação inicial do cabeçalho 'SAMP'
        if (substr($this->data, 0, 4) !== 'SAMP' || strlen($this->data) < Protocol::MIN_PACKET_LENGTH)
            throw new Malformed_Packet_Exception("Invalid response header or packet too short.");
    }
    
    private function Extract_String(int $length_bytes): string {
        // ... lógica para ler o comprimento e a string ...
        $string = substr($this->data, $this->offset, $length);
        $this->offset += $length;
        
        // **CONVERSÃO DE CODIFICAÇÃO CRÍTICA:** Servidores SA-MP/OMP usam Windows-1252
        return mb_convert_encoding($string, 'UTF-8', 'Windows-1252');
    }
    // ...
}
```

- **`offset` e `data_length`:** O `offset` é usado para rastrear a posição atual na leitura do pacote, enquanto `data_length` previne leituras fora dos limites do buffer.
- **Validação do Cabeçalho:** O construtor valida a "magic string" `SAMP` para rejeitar pacotes malformados imediatamente.
- **`Extract_String()` - Conversão de Codificação Crucial:** Esta é uma das funcionalidades mais importantes. O protocolo SA-MP transmite strings usando a codificação **Windows-1252**. Para garantir que caracteres especiais (como acentos ou cirílicos) sejam exibidos corretamente em aplicações PHP (que geralmente operam em UTF-8), o método `mb_convert_encoding($string, 'UTF-8', 'Windows-1252')` é aplicado.
- **Extração de Comprimento Variável:** O método `Extract_String` suporta diferentes tamanhos de prefixo de comprimento para as strings (1, 2 ou 4 bytes), tornando-o flexível para diversos campos do protocolo.

### 11. `samp-query.php`: A Classe Principal (O Orquestrador Completo)

A classe `Samp_Query` é o ponto de entrada principal e o orquestrador de todas as operações. Ela amarra todos os componentes, gerencia o estado da consulta, a lógica de *retries* e os *timeouts*.

#### Ciclo de Vida da Consulta: A Jornada de um Pacote

Todo o processo de consulta a um servidor segue uma sequência de etapas cuidadosamente orquestradas, visando a máxima resiliência e performance.

##### 1. Inicialização e Resolução de Domínio

Quando uma instância de `Samp_Query` é criada:
- **Validação Rápida:** O construtor valida os parâmetros `$hostname` e `$port`. Qualquer inconsistência resulta em uma `Invalid_Argument_Exception`.
- **Limpeza de Cache DNS:** `Domain_Resolver::Clean_Expired_Cache()` é invocado para garantir que apenas entradas DNS válidas e não expiradas sejam consideradas.
- **Resolução de IP:** O `Domain_Resolver` é utilizado para converter o `$hostname` em um endereço IP (`$this->ip`). Esse IP é cacheado em disco para requisições futuras, e uma `Query_Exception` é lançada se a resolução falhar.
- **Configuração de Recursos:** O `Logger`, `Socket_Manager` e `Packet_Builder` são instanciados, preparando a infraestrutura para a comunicação.

##### 2. `Fetch_Server_State()`: Cache e Consulta Crítica de INFO/PING

Este método interno é um guardião de desempenho e consistência, garantindo que as informações básicas do servidor (`Server_Info`) e o `ping` estejam sempre atualizados antes de qualquer consulta principal.

- **Cache Primário (5 Segundos):** Antes de iniciar qualquer comunicação, verifica-se se `$this->cached_info` (o objeto `Server_Info` do servidor) possui dados com menos de 5 segundos de idade (comparado a `$this->last_successful_query`). Se os dados estiverem frescos, a função retorna imediatamente, evitando tráfego de rede desnecessário.
- **Consulta Crítica de INFO:** Se o cache estiver expirado ou vazio, o método `Attempt_Query` é invocado para obter os dados `INFO`. Esta consulta é marcada como **crítica** (`true`), o que desencadeia um número maior de tentativas e *timeouts* mais generosos. Uma `Connection_Exception` é lançada se a resposta INFO for inválida após todas as tentativas.
- **Cálculo de Ping:** Se `$this->cached_ping` ainda for nulo, uma consulta `PING` rápida (`Execute_Query_Phase` com `Performance::FAST_PING_TIMEOUT`) é realizada. O ping é calculado como o tempo decorrido até a **primeira** resposta recebida, garantindo precisão.

##### 3. `Attempt_Query()`: A Estratégia de Retries Otimizada

Este é o cérebro da resiliência da biblioteca, gerenciando o ciclo de tentativas de alto nível para um ou mais `$jobs` (consultas de opcodes).

- **Cache de Respostas (2 Segundos):** Primeiro, verifica se as respostas para qualquer um dos `$jobs` já estão no `$this->response_cache` (com menos de 2.0 segundos). Isso previne *retries* desnecessários para dados voláteis, mas não críticos.
- **Fase de Retries Rápidos:** A biblioteca primeiro tenta `Query::FAST_RETRY_ATTEMPTS` (2 por padrão) com um *timeout* menor (`$timeout * 0.6`). O objetivo é obter uma resposta o mais rápido possível, sem introduzir atrasos significativos.
- **Fase de Retries Padrão com Backoff:** Se a fase rápida não for suficiente, o ciclo continua com o restante das `Query::ATTEMPTS`. Nesta fase, o `$adjusted_timeout` aumenta progressivamente a cada tentativa, dando mais tempo ao servidor para responder. Mais importante, `usleep()` introduz um atraso crescente (baseado em `Query::RETRY_DELAY_MS` e um fator de aumento) entre as chamadas para `Execute_Query_Phase`, permitindo que a rede e o servidor se estabilizem.
- **Retries de Emergência (para Consultas Críticas):** Para `$jobs` marcados como `critical`, se todas as tentativas anteriores falharem, um *retry* final é feito para cada job individualmente, utilizando um *timeout* ainda maior (`$timeout * 2`). Esta é uma última chance para obter informações vitais.

##### 4. `Execute_Query_Phase()`: O Motor de Comunicação com Detecção de Ping

Este método de baixo nível é onde a interação real com o socket UDP acontece. Ele gerencia o envio e recebimento de pacotes para um grupo de `$jobs` simultaneamente em uma única fase de rede.

```php
// ... (dentro de Execute_Query_Phase)
private function Execute_Query_Phase(array $jobs, ?int &$ping, float $timeout): array {
    $socket = $this->socket_manager->Get_Socket_Resource($timeout);
    stream_set_blocking($socket, false); // Socket não-bloqueante

    // Envia pacotes duas vezes imediatamente para maior garantia de entrega UDP
    for ($i = 0; $i < 2; $i++) {
        foreach ($pending_jobs as $opcode)
            fwrite($socket, $this->packet_builder->Build($opcode->value));

        if ($i == 0)
            usleep(5000); // Pequeno delay
    }

    $last_send_time = microtime(true);
    $current_retry_interval = Performance::INITIAL_RETRY_INTERVAL; // 0.08s
    
    while (!empty($pending_jobs) && (microtime(true) - $start_time) < $timeout) {
        $now = microtime(true);

        if (($now - $last_send_time) > $current_retry_interval) { // Lógica de reenvio com backoff
            // ... reenvia pacotes pendentes ...
            $current_retry_interval = min($current_retry_interval * Performance::BACKOFF_FACTOR, 0.2); // Aumenta o intervalo de retry
        }

        $read = [$socket]; $write = $except = null

        if (stream_select($read, $write, $except, 0, 10000) > 0) { // Espera por dados (max 10ms)
            $response = stream_socket_recvfrom($socket, Protocol::UDP_READ_BUFFER_SIZE);
            // ... lógica de parsing, cálculo de ping e validação ...
        }

        if (!empty($pending_jobs))
            usleep(1000); // Pequeno delay para evitar CPU spin
    }
    return $phase_results;
}
```

- **Socket Não-Bloqueante:** `stream_set_blocking($socket, false)` é essencial. Ele permite que o PHP envie pacotes e, em seguida, espere por respostas sem bloquear a execução, usando `stream_select`.
- **Envio Duplo para Robustez:** Os pacotes para todos os `$pending_jobs` são enviados **duas vezes** consecutivas (com um pequeno `usleep(5000)` entre eles) no início da fase. Essa prática é fundamental em protocolos UDP para aumentar significativamente a probabilidade de entrega em redes instáveis ou com perda de pacotes, mitigando a natureza não confiável do UDP. Para `INFO` e `PING`, um terceiro envio adicional é feito durante os *retries* no loop principal.
- **Loop de Recebimento com Backoff Adaptativo:**
   - Um loop `while` principal continua até que todos os `$jobs` sejam concluídos ou o *timeout* da fase expire.
   - **Reenvio Dinâmico:** Se o tempo decorrido desde o último envio (`$now - $last_send_time`) exceder o `$current_retry_interval`, os pacotes para os `$pending_jobs` são reenviados. O `$current_retry_interval` é então aumentado (`min($current_retry_interval * Performance::BACKOFF_FACTOR, 0.2)`), implementando um *backoff* exponencial que evita a sobrecarga do servidor e maximiza as chances de uma resposta.
   - **`stream_select` Otimizado:** `stream_select($read, $write, $except, 0, 10000)` é usado para esperar por dados por no máximo 10 milissegundos. Isso permite que a biblioteca seja responsiva e processe pacotes assim que chegam.
   - **Medição Precisa de Ping:** Quando o **primeiro** pacote válido é recebido (`$packets_received === 0`), o `ping` é calculado com alta precisão como a diferença entre `microtime(true)` no início do envio da primeira leva de pacotes e o tempo exato de recebimento do **primeiro** pacote válido.
- **Processamento e Validação da Resposta:** As respostas recebidas são decodificadas pelo `Packet_Parser`. Se um pacote `Malformed_Packet_Exception` for detectado, ele é logado, e o pacote é imediatamente reenviado para o servidor para tentar novamente. A resposta decodificada é então validada por `Validate_Response()`. Se for válida, é adicionada aos `$phase_results` e ao `$this->response_cache`.

##### 5. `Validate_Response()`: A Camada de Validação Semântica

Este método crucial, implementado na classe `Samp_Query`, verifica a integridade e a consistência lógica dos dados decodificados antes de serem entregues ao usuário.

```php
// ... (dentro de Validate_Response)
private function Validate_Response($data, Opcode $opcode): bool {
    switch ($opcode) {
        case Opcode::INFO:
            // Garante que o hostname não seja vazio e que os números de jogadores sejam lógicos
            return $data instanceof Server_Info && $data->max_players > 0 && $data->players >= 0 && $data->players <= $data->max_players && !empty($data->hostname);
        
        case Opcode::RULES:
            if (!is_array($data))
                return false;

            // ...

            return true;
        // ... validações para PLAYERS_DETAILED, PLAYERS_BASIC, PING ...
    }
}
```

- **Validação por Opcode:** Cada `Opcode` tem sua própria lógica de validação específica. Por exemplo:
   - Para `Opcode::INFO`: Garante que o `$data` seja uma instância de `Server_Info`, que `$data->max_players > 0`, `$data->players` esteja entre 0 e `max_players`, e que `$data->hostname` não seja vazio.
   - Para `Opcode::RULES` ou listas de jogadores: Verifica se o retorno é um `array` e, se não estiver vazio, se o primeiro elemento é da instância de modelo esperada (`Server_Rule`, `Players_Detailed`, etc.).
- **Robustez:** Se a validação falhar, a resposta é considerada inválida e é descartada. Isso força o sistema a continuar as tentativas, como se o pacote nunca tivesse chegado, protegendo a aplicação contra dados corruptos ou inconsistentes do servidor.

#### Cálculo e Gerenciamento de Timeout Adaptativo

A biblioteca implementa uma estratégia de *timeout* sofisticada para equilibrar velocidade e resiliência:

- **`Performance::METADATA_TIMEOUT`:** (0.8 segundos) É o *timeout* base para consultas rápidas como `INFO` e `RULES`.
- **`Performance::PLAYER_LIST_BASE_TIMEOUT`:** (1.0 segundo) É o *timeout* base para consultas de lista de jogadores.
- **`Performance::PING_MULTIPLIER`:** (2) Utilizado para ajustar o *timeout* com base no ping.
- **Ajuste por Ping:** No método `Fetch_Player_Data`, o *timeout* para obter a lista de jogadores é ajustado dinamicamente:
   ```
   Timeout de Jogadores = PLAYER_LIST_BASE_TIMEOUT + (Cached Ping * PING_MULTIPLIER / 1000)
   ```
   Essa abordagem permite que servidores com alta latência (ping alto) tenham um *timeout* maior, aumentando a chance de sucesso na obtenção da lista completa de jogadores, que pode ser grande e demorada para ser processada pelo servidor.
- **Limite de Timeout:** O `min($timeout, 2.0)` é usado em várias chamadas para impor um limite máximo de 2.0 segundos para consultas de metadados, prevenindo esperas excessivas.

#### Métodos de Consulta Pública

| Método | Descrição Detalhada | Comportamento de Caching Interno |
| :--- | :--- | :--- |
| `Get_All()` | **Método recomendado para uso geral.** Orquestra a obtenção de `INFO`, `RULES`, `PLAYERS_DETAILED` (ou `PLAYERS_BASIC` como fallback) em paralelo. Isso minimiza o tempo total da consulta, pois os pacotes são enviados quase simultaneamente e as respostas são processadas conforme chegam. Inclui uma medição de `execution_time_ms` total. | Utiliza o cache de 2.0s (`$this->response_cache`) para cada opcode consultado dentro da fase paralela. |
| `Is_Online()` | Realiza uma consulta `INFO` rápida e retorna `true` se o servidor responder com um `Server_Info` válido dentro do *timeout*, `false` caso contrário. É robusto, utilizando *retries* críticos. | Internamente, invoca `Fetch_Server_State()`, que usa o cache de 5.0s para `INFO`. |
| `Get_Ping()` | Retorna o ping mais recente do servidor em milissegundos. Se `cached_ping` for nulo, força uma consulta `PING` dedicada com `Performance::FAST_PING_TIMEOUT` (0.3s) para obter uma medida rápida. | O `ping` é cacheado e atualizado sempre que `Execute_Query_Phase` recebe a primeira resposta. |
| `Get_Info()` | Retorna um objeto `Server_Info` com detalhes como hostname, gamemode, número de jogadores, etc. | Invoca `Fetch_Server_State()`, que utiliza o cache de 5.0s. |
| `Get_Rules()` | Retorna um `array` de objetos `Server_Rule` contendo todas as regras configuradas no servidor (ex: `mapname`, `weburl`). Inclui *retries* adicionais em caso de falha inicial. | Utiliza o cache de 2.0s para o `Opcode::RULES`. |
| `Get_Players_Detailed()` | Retorna um `array` de objetos `Players_Detailed` (id, nome, score, ping para cada jogador). **Importante:** Esta consulta é ignorada se o número de jogadores no servidor (`$this->cached_info->players`) for maior ou igual a `Query::LARGE_PLAYER_THRESHOLD` (150 por padrão), devido ao risco de *timeouts* prolongados ou fragmentação de pacotes. | Utiliza o cache de 2.0s para `Opcode::PLAYERS_DETAILED`. |
| `Get_Players_Basic()` | Retorna um `array` de objetos `Players_Basic` (nome, score para cada jogador). É mais leve que a consulta detalhada. Geralmente é chamada como *fallback* se `Get_Players_Detailed()` falhar ou for ignorada. | Utiliza o cache de 2.0s para `Opcode::PLAYERS_BASIC`. |

#### Comunicação RCON (`Send_Rcon`)

O método `Send_Rcon(string $rcon_password, string $command)` permite enviar comandos para o console remoto do servidor.

1. **Validação de Argumentos:** Lança `Invalid_Argument_Exception` se a senha ou o comando estiverem vazios.
2. **Socket Isolado:** Cria uma nova instância de `Socket_Manager` (`$rcon_socket_manager`) para a sessão RCON, isolando-a do socket principal de consulta para evitar interferências.
3. **Autenticação (`varlist`):** Antes de enviar o comando real, a biblioteca envia o comando "varlist" (em até 3 tentativas) para autenticar a senha RCON. Se `Send_Single_Rcon_Request` retornar `null` ou uma resposta vazia, uma `Rcon_Exception` é lançada, indicando falha de autenticação ou que o RCON não está habilitado.
4. **Envio do Comando Real:** Após a autenticação bem-sucedida, o `$command` é enviado, também com até 3 tentativas.
5. **Tratamento de Resposta:** O `Packet_Parser::Parse_Rcon()` decodifica a resposta de texto do RCON. Se o servidor não retornar uma resposta textual após todas as tentativas, uma mensagem genérica de sucesso é retornada.
6. **Limpeza:** O destrutor do `$rcon_socket_manager` garante que o socket RCON seja fechado após a operação.

## Diagnóstico de Erros e Exceções

A biblioteca utiliza uma hierarquia de exceções customizadas para um tratamento de erros limpo e previsível. Em caso de falha, uma das seguintes exceções será lançada.

### `Invalid_Argument_Exception`

**Causa:**
- **Hostname Vazio:** O `hostname` fornecido no construtor de `Samp_Query` é uma string vazia.
- **Porta Inválida:** A `port` fornecida no construtor está fora do intervalo válido de 1 a 65535.
- **RCON:** Senha RCON ou comando RCON fornecidos para `Send_Rcon` são vazios.

### `Connection_Exception`

**Causa:** Falha de rede ou falta de resposta essencial.
- **Resolução de Domínio Falha:** O `Domain_Resolver` não consegue converter o hostname em um IPv4 válido.
- **Falha na Criação do Socket:** O `Socket_Manager` não consegue criar ou conectar o socket UDP.
- **Servidor Inacessível/Offline:** O servidor falha em responder com um pacote `INFO` válido após todas as tentativas e *timeouts* (incluindo *retries* de emergência), geralmente indicando que o servidor está offline, o IP/porta está incorreto, ou um firewall está bloqueando a comunicação.

### `Malformed_Packet_Exception`

**Causa:** Corrupção de dados no nível do protocolo.
- **Cabeçalho Inválido:** O `Packet_Parser` detecta um pacote que não começa com a "magic string" `SAMP` ou tem um comprimento total insuficiente.
- **Estrutura de Pacote Inválida:** O `Packet_Parser` encontra inconsistências na estrutura binária, como um comprimento de string que aponta para fora dos limites do pacote.
- **Resiliência:** Esta exceção é frequentemente tratada internamente por `Execute_Query_Phase` para acionar um *retry* imediato, mas pode ser propagada se o problema persistir.

### `Rcon_Exception`

**Causa:** Erro durante a comunicação RCON.
- **Falha de Autenticação RCON:** O servidor não respondeu à autenticação RCON (via comando `varlist`) após 3 tentativas, sugerindo senha incorreta ou RCON desabilitado no servidor.
- **Falha no Envio do Comando RCON:** O comando RCON real não obteve resposta após 3 tentativas.

## Licença

Copyright © **SA-MP Programming Community**

Este software é licenciado sob os termos da Licença MIT ("Licença"); você pode utilizar este software de acordo com as condições da Licença. Uma cópia da Licença pode ser obtida em: [MIT License](https://opensource.org/licenses/MIT)

### Termos e Condições de Uso

#### 1. Permissões Concedidas

A presente licença concede, gratuitamente, a qualquer pessoa que obtenha uma cópia deste software e arquivos de documentação associados, os seguintes direitos:
* Utilizar, copiar, modificar, mesclar, publicar, distribuir, sublicenciar e/ou vender cópias do software sem restrições
* Permitir que pessoas para as quais o software é fornecido façam o mesmo, desde que sujeitas às condições a seguir

#### 2. Condições Obrigatórias

Todas as cópias ou partes substanciais do software devem incluir:
* O aviso de direitos autorais acima
* Este aviso de permissão
* O aviso de isenção de responsabilidade abaixo

#### 3. Direitos Autorais

O software e toda a documentação associada são protegidos por leis de direitos autorais. A **SA-MP Programming Community** mantém a titularidade dos direitos autorais originais do software.

#### 4. Isenção de Garantias e Limitação de Responsabilidade

O SOFTWARE É FORNECIDO "COMO ESTÁ", SEM GARANTIA DE QUALQUER TIPO, EXPRESSA OU IMPLÍCITA, INCLUINDO, MAS NÃO SE LIMITANDO ÀS GARANTIAS DE COMERCIALIZAÇÃO, ADEQUAÇÃO A UM DETERMINADO FIM E NÃO VIOLAÇÃO. 

EM NENHUMA CIRCUNSTÂNCIA OS AUTORES OU TITULARES DOS DIREITOS AUTORAIS SERÃO RESPONSÁVEIS POR QUALQUER REIVINDICAÇÃO, DANOS OU OUTRA RESPONSABILIDADE, SEJA EM AÇÃO DE CONTRATO, DELITO OU DE OUTRA FORMA, DECORRENTE DE, FORA DE OU EM CONEXÃO COM O SOFTWARE OU O USO OU OUTRAS NEGOCIAÇÕES NO SOFTWARE.

---

Para informações detalhadas sobre a Licença MIT, consulte: https://opensource.org/licenses/MIT