# SA-MP Query - PHP

<div align="center">

![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Architecture](https://img.shields.io/badge/Protocol-UDP-blueviolet?style=for-the-badge)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**SA-MP (San Andreas Multiplayer) ve OMP (Open Multiplayer) sunucularının durumunu ve bilgilerini sorgulamak için tasarlanmış, sağlam ve dayanıklı bir PHP kütüphanesi.**

</div>

## Diller

- Português: [README](../../)
- Deutsch: [README](../Deutsch/README.md)
- English: [README](../English/README.md)
- Español: [README](../Espanol/README.md)
- Français: [README](../Francais/README.md)
- Italiano: [README](../Italiano/README.md)
- Polski: [README](../Polski/README.md)
- Русский: [README](../Русский/README.md)
- Svenska: [README](../Svenska/README.md)

## İçindekiler

- [SA-MP Query - PHP](#sa-mp-query---php)
  - [Diller](#diller)
  - [İçindekiler](#i̇çindekiler)
  - [Genel Bakış](#genel-bakış)
  - [Tasarım ve Mimari İlkeleri](#tasarım-ve-mimari-i̇lkeleri)
    - [Modüler Mimari](#modüler-mimari)
    - [Dayanıklılık: Backoff, Yeniden Denemeler ve Önbellekleme](#dayanıklılık-backoff-yeniden-denemeler-ve-önbellekleme)
    - [Performans Optimizasyonu: Paralellik ve Zaman Aşımı Uyarlaması](#performans-optimizasyonu-paralellik-ve-zaman-aşımı-uyarlaması)
    - [Modern Nesne Yönelimli Programlama (OOP) (PHP 8.1+)](#modern-nesne-yönelimli-programlama-oop-php-81)
  - [Gereksinimler](#gereksinimler)
  - [Kurulum ve Temel Kullanım](#kurulum-ve-temel-kullanım)
    - [`Samp_Query` Sınıfının Başlatılması](#samp_query-sınıfının-başlatılması)
    - [`Get_All()`: Tam ve Optimize Edilmiş Sorgu](#get_all-tam-ve-optimize-edilmiş-sorgu)
    - [`Is_Online()`: Hızlı Durum Kontrolü](#is_online-hızlı-durum-kontrolü)
    - [`Get_Ping()`: Sunucu Ping'ini Alma](#get_ping-sunucu-pingini-alma)
    - [`Get_Info()`: Temel Sunucu Detayları](#get_info-temel-sunucu-detayları)
    - [`Get_Rules()`: Yapılandırılmış Sunucu Kuralları](#get_rules-yapılandırılmış-sunucu-kuralları)
    - [`Get_Players_Detailed()`: Detaylı Oyuncu Listesi](#get_players_detailed-detaylı-oyuncu-listesi)
    - [`Get_Players_Basic()`: Temel Oyuncu Listesi](#get_players_basic-temel-oyuncu-listesi)
    - [`Send_Rcon()`: Uzaktan Komut Gönderme](#send_rcon-uzaktan-komut-gönderme)
  - [Kütüphanenin Detaylı Yapısı ve Yürütme Akışı](#kütüphanenin-detaylı-yapısı-ve-yürütme-akışı)
    - [1. `constants.php`: Yapılandırmanın Kalbi](#1-constantsphp-yapılandırmanın-kalbi)
    - [2. `opcode.php`: Protokol Opcodes Enum'ı](#2-opcodephp-protokol-opcodes-enumı)
    - [3. `exceptions.php`: Özel İstisna Hiyerarşisi](#3-exceptionsphp-özel-i̇stisna-hiyerarşisi)
    - [4. `server_types.php`: Değişmez Veri Modelleri](#4-server_typesphp-değişmez-veri-modelleri)
    - [5. `autoloader.php`: Otomatik Sınıf Yükleyici](#5-autoloaderphp-otomatik-sınıf-yükleyici)
    - [6. `logger.php`: Loglama Alt Sistemi](#6-loggerphp-loglama-alt-sistemi)
    - [7. `domain_resolver.php`: Kalıcı Önbellekli Alan Adı Çözümlemesi](#7-domain_resolverphp-kalıcı-önbellekli-alan-adı-çözümlemesi)
    - [8. `socket_manager.php`: Sağlam UDP Bağlantı Yöneticisi](#8-socket_managerphp-sağlam-udp-bağlantı-yöneticisi)
    - [9. `packet_builder.php`: İkili Paket Oluşturucu](#9-packet_builderphp-i̇kili-paket-oluşturucu)
    - [10. `packet_parser.php`: Kodlama İşlemli Paket Çözücü](#10-packet_parserphp-kodlama-i̇şlemli-paket-çözücü)
    - [11. `samp-query.php`: Ana Sınıf (Tam Orkestratör)](#11-samp-queryphp-ana-sınıf-tam-orkestratör)
      - [Sorgu Yaşam Döngüsü: Bir Paketin Yolculuğu](#sorgu-yaşam-döngüsü-bir-paketin-yolculuğu)
        - [1. Başlatma ve Alan Adı Çözümlemesi](#1-başlatma-ve-alan-adı-çözümlemesi)
        - [2. `Fetch_Server_State()`: Önbellek ve Kritik INFO/PING Sorgusu](#2-fetch_server_state-önbellek-ve-kritik-infoping-sorgusu)
        - [3. `Attempt_Query()`: Optimize Edilmiş Yeniden Deneme Stratejisi](#3-attempt_query-optimize-edilmiş-yeniden-deneme-stratejisi)
        - [4. `Execute_Query_Phase()`: Ping Tespiti ile İletişim Motoru](#4-execute_query_phase-ping-tespiti-ile-i̇letişim-motoru)
        - [5. `Validate_Response()`: Anlamsal Doğrulama Katmanı](#5-validate_response-anlamsal-doğrulama-katmanı)
      - [Uyarlanabilir Zaman Aşımı Hesaplaması ve Yönetimi](#uyarlanabilir-zaman-aşımı-hesaplaması-ve-yönetimi)
      - [Genel Sorgu Metotları](#genel-sorgu-metotları)
      - [RCON İletişimi (`Send_Rcon`)](#rcon-i̇letişimi-send_rcon)
  - [Hata Teşhisi ve İstisnalar](#hata-teşhisi-ve-i̇stisnalar)
    - [`Invalid_Argument_Exception`](#invalid_argument_exception)
    - [`Connection_Exception`](#connection_exception)
    - [`Malformed_Packet_Exception`](#malformed_packet_exception)
    - [`Rcon_Exception`](#rcon_exception)
  - [Lisans](#lisans)
    - [Kullanım Şartları ve Koşulları](#kullanım-şartları-ve-koşulları)
      - [1. Verilen İzinler](#1-verilen-i̇zinler)
      - [2. Zorunlu Koşullar](#2-zorunlu-koşullar)
      - [3. Telif Hakları](#3-telif-hakları)
      - [4. Garanti Reddi ve Sorumluluk Sınırlaması](#4-garanti-reddi-ve-sorumluluk-sınırlaması)

## Genel Bakış

**SA-MP Query - PHP** kütüphanesi, SA-MP/OMP (UDP) protokolüne dayalı oyun sunucularıyla etkileşime geçmesi gereken PHP geliştiricileri için yüksek performanslı ve hataya dayanıklı bir çözümdür. Amacı, sorgu ikili protokolünün karmaşıklığını temiz ve sezgisel bir PHP API'sine sarmak, web uygulamalarının, başlatıcıların ve yardımcı programların sunucu durumu hakkında (oyuncular, kurallar, ping vb.) detaylı bilgileri hızlı ve güvenilir bir şekilde almasını sağlamaktır.

Kütüphanenin tasarımı üç ana sütuna odaklanmıştır: **Dayanıklılık**, **Performans** ve **Modülerlik**. UDP protokolünün güvenilmez doğasıyla başa çıkmak için inşa edilmiştir ve olumsuz ağ koşullarında veya yüksek gecikmeli sunucularda bile bilginin elde edilmesini sağlamak için gelişmiş bir deneme ve *backoff* sistemi uygular.

## Tasarım ve Mimari İlkeleri

### Modüler Mimari

Kütüphane, her biri kendi sınıfına ve dosyasına sarmalanmış tek sorumluluklu bileşenlere ayrılmıştır.

- **Ağ Altyapısı:** `Domain_Resolver`, `Socket_Manager`.
- **Protokol:** `Packet_Builder`, `Packet_Parser`, `Opcode` (Enum).
- **İş Mantığı (Orkestrasyon):** `Samp_Query`.
- **Veri Modelleri:** `Server_Info`, `Players_Detailed`, vb.

### Dayanıklılık: Backoff, Yeniden Denemeler ve Önbellekleme

UDP protokolü paketlerin teslimini garanti etmez. `Samp_Query` sınıfı bu eksikliği sofistike bir sorgu döngüsüyle giderir.

- **Çoklu Uyarlanabilir Denemeler:** `Attempt_Query` metodu, `Query::ATTEMPTS`'e (varsayılan 5) kadar ve kritik sorgular için bunun iki katı bir döngü uygular.
- **Backoff Stratejisi:** Üstel *backoff*, `Execute_Query_Phase`'de uygulanır. İlk gönderimden sonra, dinleme denemeleri arasındaki aralık (`while` döngüsü) `Performance::INITIAL_RETRY_INTERVAL` (0.08s) değerinden `Performance::BACKOFF_FACTOR` (1.3) ile 0.2s sınırına kadar artar. Bu, paket aşırı yüklenmesini önler ve zamanında bir yanıt alma şansını artırır.
- **Yanıtların Önbellenmesi:** Son yanıtlar (2.0 saniye geçerli) `response_cache`'de saklanır, bu da `Get_All()`'ın yürütülmesi sırasında meta veri sorgularını tekrarlama ihtiyacını ortadan kaldırır.

### Performans Optimizasyonu: Paralellik ve Zaman Aşımı Uyarlaması

- **Paralel Sorgular (Fan-out):** `Get_All()` metodu, `INFO`, `RULES` ve `PLAYERS` isteklerini aynı anda (`$jobs` içinde) gönderir, bu da yanıtların sırasız gelmesine izin vererek toplam bekleme süresini en aza indirir.
- **Kalıcı DNS Önbellekleme:** `Domain_Resolver`, çözümlenmiş IP adresini 3600 saniyelik bir TTL ile yerel bir dosya önbelleğinde saklar, bu da sonraki çağrılarda alan adı çözümlemesindeki gecikmeleri önler.
- **Uyarlanabilir Zaman Aşımı:** Oyuncu listesi gibi büyük veri sorgularının *zaman aşımı*, sunucunun `cached_ping` değerine göre dinamik olarak ayarlanır:
   ```
   Ayarlanmış Zaman Aşımı = Temel Zaman Aşımı + (Önbellenmiş Ping * Ping Çarpanı / 1000)
   ```
   Bu mantık (`Fetch_Player_Data`'da uygulanmıştır), yüksek gecikmeli sunucuların yanıt vermek için yeterli zamana sahip olmasını sağlarken, düşük gecikmeli sunucularda hızı tehlikeye atmaz.

### Modern Nesne Yönelimli Programlama (OOP) (PHP 8.1+)

Kütüphane, güvenlik ve netliği sağlamak için modern PHP özelliklerini kullanır:

- **Katı Tipleme** (`declare(strict_types=1)`).
- **Sadece Okunur Özellikler** (`public readonly`, `Samp_Query`'de ve veri modellerinde) verilerin değişmezliğini garanti etmek için.
- **Tiplendirilmiş Enumlar** (`enum Opcode: string`) güvenli protokol kontrolü için.
- **Constructor Property Promotion** (`Samp_Query::__construct` ve modellerde).

## Gereksinimler

- **PHP:** Sürüm **8.1 veya üstü**.
- **PHP Eklentileri:** `sockets` ve `mbstring` (UTF-8 kodlama manipülasyonu için).

## Kurulum ve Temel Kullanım

**SA-MP Query - PHP** kütüphanesini kullanmaya başlamak için `samp-query.php` dosyasını projenize dahil etmeniz yeterlidir. Bu dosya, dahili autoloader'ı aracılığıyla tüm bağımlılıkları otomatik olarak yükleyecektir.

```php
<?php
// Ana sınıfı dahil edin. Bağımlılıkları autoloader aracılığıyla yükleyecektir.
require_once 'path/to/samp-query/samp-query.php'; 

// Ana sınıfın namespace'ini kullanın
use Samp_Query\Samp_Query;
// Daha spesifik hata işleme için istisnaları dahil edin
use Samp_Query\Exceptions\Invalid_Argument_Exception;
use Samp_Query\Exceptions\Connection_Exception;
use Samp_Query\Exceptions\Rcon_Exception;

// Samp_Query sınıfını başlatırken, başlangıç hatalarını işlemek için bir try-catch bloğu içinde sarmalayın.
try {
    $server_query = new Samp_Query("play.example-samp-server.com", 7777);
    // Artık $server_query'nin genel metotlarını kullanabilirsiniz
}
catch (Invalid_Argument_Exception $e) {
    echo "Argüman Hatası: " . $e->getMessage() . "\n";
}
catch (Connection_Exception $e) {
    echo "Bağlantı Hatası: " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Başlatma sırasında beklenmedik hata: " . $e->getMessage() . "\n";
}
```

### `Samp_Query` Sınıfının Başlatılması

`Samp_Query` sınıfı, tüm işlevlere giriş kapısıdır. Yapılandırıcısı, sorgulamak istediğiniz sunucunun `hostname`'ini (veya IP adresini) ve `port`'unu gerektirir.

```php
/**
 * Yeni bir Samp_Query kütüphanesi örneğini başlatır.
 *
 * @param string $hostname SA-MP/OMP sunucusunun hostname'i veya IP adresi.
 * @param int $port Sunucunun UDP portu (genellikle 7777).
 * 
 * @throws Invalid_Argument_Exception Hostname boşsa veya port geçersizse.
 * @throws Connection_Exception DNS çözümlemesi başarısız olursa veya socket oluşturulamazsa.
 */
public function __construct(public readonly string $hostname, public readonly int $port)
```

<br>

---

### `Get_All()`: Tam ve Optimize Edilmiş Sorgu

Bu en kapsamlı ve önerilen metottur. Çeşitli sorguları (INFO, RULES, PLAYERS) paralel ve optimize bir şekilde yürüterek toplam yanıt süresini en aza indirir ve mevcut tüm bilgileri içeren tam bir ilişkisel dizi döndürür.

```php
/**
 * Sunucunun mevcut tüm bilgilerini tek bir optimize edilmiş çağrıda döndürür.
 * İçerir: is_online, ping, info (Server_Info), rules (Server_Rule dizisi),
 * players_detailed (Players_Detailed dizisi), players_basic (Players_Basic dizisi),
 * ve execution_time_ms.
 *
 * @return array Sunucunun tüm bilgilerini içeren bir ilişkisel dizi.
 * 
 * @throws Connection_Exception Sunucu durumu için gerekli olan INFO sorgusu başarısız olursa.
 */
public function Get_All(): array
```

Kullanım Örneği:

```php
<?php
// ... ($server_query sınıfının başlatılması) ...

try {
    $data = $server_query->Get_All();
    
    if ($data['is_online']) {
        echo "## Sunucu Çevrimiçi: {$data['info']->hostname} ({$server_query->ip}:{$server_query->port}) ##\n";
        echo "Ping: {$data['ping']}ms | Toplam Sorgu Süresi: {$data['execution_time_ms']}ms\n";
        echo "Oyuncular: {$data['info']->players} / {$data['info']->max_players}\n";
        echo "Oyun Modu: {$data['info']->gamemode}\n";
        echo "Dil: {$data['info']->language}\n";
        echo "Şifre Korumalı: " . ($data['info']->password ? "Evet" : "Hayır") . "\n\n";

        // Detaylı oyuncu listesi örneği
        if (!empty($data['players_detailed'])) {
            echo "--- Detaylı Oyuncular ({$data['info']->players} Aktif) ---\n";

            foreach ($data['players_detailed'] as $player)
                echo "ID: {$player->id}, İsim: {$player->name}, Ping: {$player->ping}ms, Skor: {$player->score}\n";
        }
        elseif (!empty($data['players_basic'])) {
            echo "--- Temel Oyuncular ({$data['info']->players} Aktif) (Yedek) ---\n";

            foreach ($data['players_basic'] as $player)
                echo "İsim: {$player->name}, Skor: {$player->score}\n";
        }
        else
            echo "Çevrimiçi oyuncu yok veya liste mevcut değil (belki çok fazla oyuncu var).\n";
        
        // Sunucu kuralları örneği
        if (!empty($data['rules'])) {
            echo "\n--- Sunucu Kuralları ---\n";

            foreach ($data['rules'] as $rule)
                echo "{$rule->name}: {$rule->value}\n";
        }
    }
    else
        echo "Sunucu şu anda çevrimdışı veya erişilemez.\n";
}
catch (Connection_Exception $e) {
    echo "Tüm bilgileri alırken bağlantı hatası: " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "Tüm bilgileri sorgularken beklenmedik hata: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Is_Online()`: Hızlı Durum Kontrolü

Ek ayrıntılar almadan sunucunun çevrimiçi olup olmadığını ve sorgulara yanıt verip vermediğini kontrol eder. Basit bir "canlılık kontrolü" için idealdir.

```php
/**
 * Sunucunun çevrimiçi ve erişilebilir olup olmadığını kontrol eder.
 *
 * @return bool Sunucu çevrimiçiyse ve geçerli INFO ile yanıt veriyorsa true, aksi takdirde false döndürür.
 */
public function Is_Online(): bool
```

Kullanım Örneği:

```php
<?php
// ... ($server_query sınıfının başlatılması) ...

if ($server_query->Is_Online())
    echo "SA-MP sunucusu çevrimiçi ve yanıt veriyor!\n";
else
    echo "SA-MP sunucusu çevrimdışı veya zamanında yanıt vermedi.\n";
```

<br>

---

### `Get_Ping()`: Sunucu Ping'ini Alma

Sunucunun gecikme süresini (ping) milisaniye cinsinden döndürür. Bu değer optimizasyon için dahili olarak önbelleğe alınır.

```php
/**
 * Sunucunun mevcut ping'ini milisaniye cinsinden döndürür.
 * Eğer ping henüz hesaplanmadıysa, hızlı bir PING sorgusu çalıştırılır.
 *
 * @return int|null Ping değeri milisaniye cinsinden veya alınamazsa null.
 */
public function Get_Ping(): ?int
```

Kullanım Örneği:

```php
<?php
// ... ($server_query sınıfının başlatılması) ...

try {
    $ping = $server_query->Get_Ping();

    if ($ping !== null)
        echo "Sunucunun ping'i: {$ping}ms.\n";
    else
        echo "Sunucunun ping'i alınamadı.\n";
}
catch (Connection_Exception $e) {
    echo "Ping alınırken hata: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Info()`: Temel Sunucu Detayları

Sunucunun hostname, oyun modu, oyuncu sayısı gibi temel bilgilerini alır. Bir `Server_Info` nesnesi döndürür.

```php
/**
 * Sunucunun temel ayrıntılarını (hostname, oyuncular, gamemode vb.) döndürür.
 * Veriler optimizasyon için kısa bir süre önbelleğe alınır.
 *
 * @return Server_Info|null Bir Server_Info nesnesi veya bilgiler alınamazsa null.
 */
public function Get_Info(): ?Server_Info
```

Kullanım Örneği:

```php
<?php
use Samp_Query\Models\Server_Info;
// ... ($server_query sınıfının başlatılması) ...

try {
    /** @var Server_Info|null $info */
    $info = $server_query->Get_Info();

    if ($info) {
        echo "--- Sunucu Bilgileri ---\n";
        echo "Hostname: {$info->hostname}\n";
        echo "Gamemode: {$info->gamemode}\n";
        echo "Oyuncular: {$info->players} / {$info->max_players}\n";
        echo "Dil: {$info->language}\n";
        echo "Şifre Korumalı: " . ($info->password ? "Evet" : "Hayır") . "\n";
    }
    else
        echo "Sunucu bilgileri alınamadı.\n";
}
catch (Connection_Exception $e) {
    echo "Sunucu bilgileri alınırken hata: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Rules()`: Yapılandırılmış Sunucu Kuralları

Sunucuda yapılandırılmış `mapname`, `weburl`, `weather` gibi tüm kuralları alır ve bunları `Server_Rule` nesnelerinden oluşan bir dizi olarak döndürür.

```php
/**
 * Her biri sunucu kuralının adını ve değerini içeren Server_Rule nesnelerinden oluşan bir dizi döndürür.
 * Veriler optimizasyon için önbelleğe alınır.
 *
 * @return array Bir Samp_Query\Models\Server_Rule dizisi. Kural yoksa boş olabilir.
 */
public function Get_Rules(): array
```

Kullanım Örneği:

```php
<?php
use Samp_Query\Models\Server_Rule;
// ... ($server_query sınıfının başlatılması) ...

try {
    $rules = $server_query->Get_Rules();

    if (!empty($rules)) {
        echo "--- Sunucu Kuralları ---\n";

        foreach ($rules as $rule)
            echo "{$rule->name}: {$rule->value}\n";

        // Belirli bir kurala erişim örneği:
        $mapname_rule = array_filter($rules, fn(Server_Rule $r) => $r->name === 'mapname');

        if (!empty($mapname_rule))
            echo "\nMevcut harita: " . reset($mapname_rule)->value . "\n";
    }
    else
        echo "Bu sunucu için kural bulunamadı.\n";
}
catch (Connection_Exception $e) {
    echo "Sunucu kuralları alınırken hata: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Players_Detailed()`: Detaylı Oyuncu Listesi

Şu anda çevrimiçi olan oyuncuların ID, isim, skor ve ping dahil olmak üzere detaylı bir listesini alır.

> [!CAUTION]
> Performansı optimize etmek ve kaybolabilecek veya parçalanabilecek aşırı büyük UDP paketlerinden kaçınmak için, bu metot toplam oyuncu sayısı `Query::LARGE_PLAYER_THRESHOLD` (varsayılan 150) değerine eşit veya daha fazlaysa detaylı oyuncu listesini almayacaktır. Bu durumlarda boş bir dizi döndürülecektir. Bir yedek olarak `Get_Players_Basic()` kullanmayı düşünün.

```php
/**
 * Her çevrimiçi oyuncu için Players_Detailed nesnelerinden (ID, isim, skor, ping) oluşan bir dizi döndürür.
 * Bu sorgu, oyuncu sayısı çok yüksekse (bkz. Query::LARGE_PLAYER_THRESHOLD) atlanabilir.
 *
 * @return array Bir Samp_Query\Models\Players_Detailed dizisi. Boş olabilir.
 */
public function Get_Players_Detailed(): array
```

Kullanım Örneği:

```php
<?php
use Samp_Query\Models\Players_Detailed;
// ... ($server_query sınıfının başlatılması) ...

try {
    $players_detailed = $server_query->Get_Players_Detailed();

    if (!empty($players_detailed)) {
        echo "--- Çevrimiçi Oyuncular (Detaylı) ---\n";

        foreach ($players_detailed as $player)
            echo "ID: {$player->id}, İsim: {$player->name}, Skor: {$player->score}, Ping: {$player->ping}ms\n";
    }
    else
        echo "Çevrimiçi oyuncu yok veya detaylı liste mevcut değil.\n";
}
catch (Connection_Exception $e) {
    echo "Detaylı oyuncu listesi alınırken hata: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Get_Players_Basic()`: Temel Oyuncu Listesi

Sadece isim ve skor içeren daha hafif bir oyuncu listesi sağlar. Detaylı liste mevcut olmadığında veya veri yükünü azaltmak için bir alternatif olarak kullanışlıdır.

```php
/**
 * Her çevrimiçi oyuncu için Players_Basic nesnelerinden (isim, skor) oluşan bir dizi döndürür.
 * Get_Players_Detailed() mümkün olmadığında daha hafif bir alternatif veya yedek olarak kullanışlıdır.
 *
 * @return array Bir Samp_Query\Models\Players_Basic dizisi. Boş olabilir.
 */
public function Get_Players_Basic(): array
```

Kullanım Örneği:

```php
<?php
use Samp_Query\Models\Players_Basic;
// ... ($server_query sınıfının başlatılması) ...

try {
    $players_basic = $server_query->Get_Players_Basic();

    if (!empty($players_basic)) {
        echo "--- Çevrimiçi Oyuncular (Temel) ---\n";

        foreach ($players_basic as $player)
            echo "İsim: {$player->name}, Skor: {$player->score}\n";
    }
    else
        echo "Çevrimiçi oyuncu yok veya temel liste mevcut değil.\n";
}
catch (Connection_Exception $e) {
    echo "Temel oyuncu listesi alınırken hata: " . $e->getMessage() . "\n";
}
```

<br>

---

### `Send_Rcon()`: Uzaktan Komut Gönderme

Sunucunun RCON konsoluna kuralları değiştirme, oyuncuları yasaklama gibi komutlar göndermenizi sağlar. Sunucunun RCON şifresini gerektirir.

> [!WARNING]
> RCON işlevi hassastır ve sunucuda değişikliklere neden olabilir. Dikkatli ve sadece güvenilir şifrelerle kullanın.
> RCON şifresinin **doğru** olması ve RCON'un sunucuda **etkinleştirilmiş** olması (`server.cfg`'deki `rcon_password` ayarı) çok önemlidir.

```php
/**
 * Sunucuya bir RCON komutu gönderir.
 * 'varlist' ile kimlik doğrulaması yapar ve komutu gönderir.
 *
 * @param string $rcon_password Sunucunun RCON şifresi.
 * @param string $command Yürütülecek komut (ör: "gmx", "kick ID").
 * @return string Sunucunun RCON komutuna verdiği yanıt veya bir durum mesajı.
 * 
 * @throws Invalid_Argument_Exception RCON şifresi veya komutu boşsa.
 * @throws Rcon_Exception RCON kimlik doğrulaması başarısız olursa veya komut yanıt almazsa.
 * @throws Connection_Exception RCON işlemi sırasında bağlantı hatası durumunda.
 */
public function Send_Rcon(string $rcon_password, string $command): string
```

Kullanım Örneği:

```php
<?php
// ... ($server_query sınıfının başlatılması) ...

$rcon_password = "gizli_sifreniz_burada"; 
$command_to_send = "gmx"; // Örnek: oyun modunu yeniden başlat

try {
    echo "RCON komutu göndermeye çalışılıyor: '{$command_to_send}'...\n";
    $response = $server_query->Send_Rcon($rcon_password, $command_to_send);
    echo "RCON yanıtı: {$response}\n";

    // Sunucuda bir şeyler "söylemek" için komut örneği (RCON gerektirir)
    $response_say = $server_query->Send_Rcon($rcon_password, "say PHP scriptimden merhaba!");
    echo "RCON yanıtı (say): {$response_say}\n";
}
catch (Invalid_Argument_Exception $e) {
    echo "RCON Hatası (Geçersiz Argüman): " . $e->getMessage() . "\n";
}
catch (Rcon_Exception $e) {
    echo "RCON Hatası: Kimlik doğrulama başarısız veya komut yürütülmedi. " . $e->getMessage() . "\n";
}
catch (Connection_Exception $e) {
    echo "RCON Hatası (Bağlantı): " . $e->getMessage() . "\n";
}
catch (\Exception $e) {
    echo "RCON gönderilirken beklenmedik hata: " . $e->getMessage() . "\n";
}
```

## Kütüphanenin Detaylı Yapısı ve Yürütme Akışı

**SA-MP Query - PHP** kütüphanesi, her biri iyi tanımlanmış bir sorumluluğa sahip olan birkaç dosyaya titizlikle organize edilmiştir. Bu bölüm, her bileşeni ayrıntılı olarak inceler, tasarım kararlarını ve altta yatan mantığı ortaya koyar.

### 1. `constants.php`: Yapılandırmanın Kalbi

Bu dosya, *zaman aşımları*, deneme sayısı ve arabellek boyutları gibi tüm "sihirli" yapılandırma parametrelerini merkezileştirerek, bu yönlerin proje genelinde kolayca ayarlanabilir ve tutarlı olmasını sağlar.

```php
// ...
final class Query {
    public const ATTEMPTS = 5; // Maksimum sorgu deneme sayısı
    public const LARGE_PLAYER_THRESHOLD = 150; // Detaylı sorgu için oyuncu sınırı
}

final class Protocol {
    public const UDP_READ_BUFFER_SIZE = 32768; // Okuma arabelleği için 32KB
    public const KERNEL_RECEIVE_BUFFER_SIZE = 4194304; // Kernel arabelleği için 4MB
}
// ...
```

- **Final Sınıflar ve Sabitler:** Sınıflar `final` ve özellikler `public const`'tur, bu da derleme zamanında değişmezlik ve genel erişilebilirlik sağlar.
- **Granülerlik ve Anlambilim:** Sabitler, alanlarına göre (`Query`, `Protocol`, `Performance`, `Logger`, `DNS`) kategorize edilmiştir, bu da anlaşılmasını ve bakımını kolaylaştırır. Örneğin, `Query::LARGE_PLAYER_THRESHOLD`, veri hacmi ve *zaman aşımı* potansiyeli nedeniyle optimizasyon için detaylı oyuncu listesi aramasının önlenebileceği noktayı tanımlar.

### 2. `opcode.php`: Protokol Opcodes Enum'ı

Bu dosya, SA-MP/OMP sunucusuna yapılan farklı sorgular için kullanılan işlem kodlarını (opcode'ları) tanımlar ve bunları tiplendirilmiş bir `enum` içinde sarmalar.

```php
// ...
enum Opcode: string {
    case INFO = 'i';
    case RULES = 'r';
    case PLAYERS_DETAILED = 'd';
    // ...
}
```

- **Tiplendirilmiş `Enum` (PHP 8.1+):** `string` türü değerlere sahip bir `enum` (`Opcode: string`) kullanımı, opcode'ların her zaman geçerli olmasını ve kodun anlamsal olarak net olmasını sağlar. Bu, "sihirli" harf dizgilerinin kullanımının yerini alarak kodu daha okunaklı ve yazım hatalarına daha az eğilimli hale getirir.

### 3. `exceptions.php`: Özel İstisna Hiyerarşisi

Bu dosya, kütüphanede meydana gelebilecek çeşitli hata türleri için daha granüler ve spesifik bir hata işlemeye olanak tanıyan özel bir istisna hiyerarşisi oluşturur.

```php
// ...
class Query_Exception extends \Exception {}
class Invalid_Argument_Exception extends Query_Exception {}
class Connection_Exception extends Query_Exception {}
// ...
```

- **`\Exception`'dan Kalıtım:** Tüm istisnalar `Query_Exception`'dan (`\Exception`'ı genişleten) kalıtım alır, bu da hata gruplarını (`Connection_Exception` ve `Timeout_Exception`, `Query_Exception`'dan daha spesifiktir) veya kütüphanenin tüm istisnalarını daha genel bir `catch` ile yakalamaya olanak tanır.
- **Teşhiste Netlik:** İstisnaların açıklayıcı adları, istemci uygulamasında hata teşhisini ve kurtarmayı kolaylaştırır.

### 4. `server_types.php`: Değişmez Veri Modelleri

Bu dosya, sunucudan döndürülen bilgiler için veri modellerini temsil eden sınıfları tanımlar ve değişmezlik yoluyla veri bütünlüğünü sağlar.

```php
// ...
final class Server_Info {
    public function __construct(
        public readonly bool $password,
        public readonly int $players,
        // ... diğer readonly özellikler ...
    ) {}
}
// ...
```

- **`final class`:** Sınıflar `final`'dır, bu da genişletilmelerini önler ve yapılarını ve davranışlarını garanti eder.
- **`public readonly` Özellikler (PHP 8.1+):** Tüm özellikler `public readonly` olarak bildirilmiştir. Bu, nesne oluşturulduktan sonra değerlerinin değiştirilemeyeceği anlamına gelir ve sunucudan alınan verilerin bütünlüğünü garanti eder.
- **Constructor Property Promotion (PHP 8.1+):** Özellikler doğrudan yapıcıda bildirilir, bu da kodu basitleştirir ve *boilerplate*'i azaltır.

### 5. `autoloader.php`: Otomatik Sınıf Yükleyici

Bu dosya, PSR-4 standardını takip ederek, kütüphanenin sınıflarını ihtiyaç duyulduğunda dinamik olarak yüklemekten sorumludur.

```php
// ...
spl_autoload_register(function ($class) {
    $prefix = 'Samp_Query\\';
    $base_dir = __DIR__ . '/../modules/'; // Namespace'i dizine eşler
    // ... yükleme mantığı ...
});

// Sınıf olmayan veya önceden yüklenmesi gereken temel dosyaları dahil eder
require_once __DIR__ . '/../modules/constants.php';
require_once __DIR__ . '/../modules/exceptions.php';
// ...
```

- **`spl_autoload_register()`:** Tanımsız bir sınıfa başvurulduğunda PHP tarafından otomatik olarak çağrılan anonim bir fonksiyon kaydeder, bu da geliştirmeyi ve bakımı hızlandırır.
- **Yapılandırmaların Doğrudan Dahil Edilmesi:** `constants.php` ve `exceptions.php` gibi dosyalar doğrudan dahil edilir. Bu, tanımlarının, onları kullanan herhangi bir sınıf autoloader tarafından örneklendirilmeden önce mevcut olmasını sağlar.

### 6. `logger.php`: Loglama Alt Sistemi

`Logger` sınıfı, hata mesajlarını ve önemli olayları bir log dosyasına kaydetmek için basit bir mekanizma sağlar, bu da hata ayıklama ve izleme için kullanışlıdır.

```php
// ...
final class Logger {
    public function Log(string $message): void {
        if (is_file(Logger_Config::FILE) && filesize(Logger_Config::FILE) > Logger_Config::MAX_SIZE_BYTES)
            unlink(Logger_Config::FILE); // Boyutu aşarsa logu kaldırır

        $timestamp = date('Y-m-d H:i:s');
        $log_entry = "[{$timestamp}] {$message}" . PHP_EOL;
        
        file_put_contents(Logger_Config::FILE, $log_entry, FILE_APPEND | LOCK_EX); // Kilitlemeyle ekler
    }
}
```

- **Otomatik Temizleme:** Logger, log dosyasının boyutunu (`Logger_Config::FILE`) kontrol eder. `Logger_Config::MAX_SIZE_BYTES` (varsayılan 10 MB) değerini aşarsa, süresiz olarak büyümesini önlemek için dosya silinir.
- **Dosya Kilitleme (`LOCK_EX`):** `file_put_contents`, çok iş parçacıklı/çok işlemli ortamlarda bozulmayı önleyerek, aynı anda yalnızca bir işlemin log dosyasına yazmasını sağlamak için `LOCK_EX` kullanır.

### 7. `domain_resolver.php`: Kalıcı Önbellekli Alan Adı Çözümlemesi

`Domain_Resolver` sınıfı, ana bilgisayar adlarını (`play.example.com` gibi) IP adreslerine (`192.0.2.1` gibi) dönüştürmekten sorumludur. Performansı optimize etmek için bir disk önbellekleme sistemi uygular.

```php
// ...
final class Domain_Resolver {
    public function Resolve(string $hostname): string {
        if (filter_var($hostname, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
            return $hostname; // Zaten bir IP

        $cache_file = DNS_Config::CACHE_DIR . md5($hostname) . '.cache';

        if (file_exists($cache_file) && (time() - filemtime($cache_file)) < DNS_Config::CACHE_TTL_SECONDS) {
            $ip = file_get_contents($cache_file);

            if ($ip && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
                return $ip;
        }
        
        $ip = gethostbyname($hostname); // Gerçek DNS çözümlemesi
        // ... doğrulama ve önbelleğe kaydetme mantığı ...

        return $ip;
    }
    // ...
}
```

- **Kalıcı Önbellek:** `gethostbyname()`'i çağırmadan önce, IP'nin zaten bir önbellek dosyasında (`dns/` + hostname'in MD5 karması) saklanıp saklanmadığını kontrol eder. Önbellek, `DNS_Config::CACHE_TTL_SECONDS` (varsayılan 3600 saniye veya 1 saat) süresini aşmadıysa geçerli kabul edilir.
- **Sağlam Doğrulama:** Çözümlenen (veya önbellekten okunan) IP, geçerli bir IPv4 olduğundan emin olmak için `filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)` ile doğrulanır. Çözümleme başarısız olursa, bir `Query_Exception` fırlatılır.

### 8. `socket_manager.php`: Sağlam UDP Bağlantı Yöneticisi

`Socket_Manager` sınıfı, oyun sunucusuyla iletişim için bir UDP soketi oluşturma, yapılandırma ve yönetme karmaşıklığını sarmalar.

```php
// ...
final class Socket_Manager {
    private $socket = null;
    // ...
    private function Connect(): void {
        if (is_resource($this->socket))
            return;

        $context = stream_context_create([
            'socket' => ['so_rcvbuf' => Protocol::KERNEL_RECEIVE_BUFFER_SIZE] // Arabelleği 4MB'ye çıkarır
        ]);

        $this->socket = stream_socket_client(
            "udp://{$this->host}:{$this->port}", 
            $err_no, $err_str, 
            (float)ini_get("default_socket_timeout"), 
            STREAM_CLIENT_CONNECT, // Soketi uzak adrese bağlar
            $context
        );

        if ($this->socket === false)
            throw new Connection_Exception("Could not create socket...");
    }
    // ...
}
```

- **`stream_socket_client` ile `STREAM_CLIENT_CONNECT`:** Bu *bayrak*, işletim sistemine UDP soketini uzak adrese "bağlamasını" söyler. UDP bağlantısız olsa da, soketi "bağlamak", her `fwrite` veya `stream_socket_recvfrom` çağrısında uzak adresi belirtme gereksinimini ortadan kaldırarak ve daha az ek yükle sonuçlanarak çekirdek düzeyinde performans optimizasyonlarına olanak tanır.
- **Çekirdek Alım Arabelleği:** `stream_context_create`, çekirdek alım arabelleğinin (`so_rcvbuf`) boyutunu `Protocol::KERNEL_RECEIVE_BUFFER_SIZE` (4MB) değerine yükseltmek için kullanılır. Bu, yoğun sunuculardan detaylı oyuncu listeleri gibi büyük yanıtları alırken paket kaybını (arabellek taşması) önlemek için çok önemlidir.
- **`__destruct` aracılığıyla RAII:** `Disconnect()` metodu, yıkıcıda (`__destruct`) otomatik olarak çağrılır, bu da istisnalar durumunda bile soketin kapatılmasını ve kaynakların serbest bırakılmasını sağlar.
- **Dinamik Zaman Aşımı:** `Set_Timeout`, soketin okuma/yazma zaman aşımlarını `stream_set_timeout` kullanarak hassas bir şekilde ayarlar, bu da *yeniden deneme* ve *backoff* mantığı için temeldir.

### 9. `packet_builder.php`: İkili Paket Oluşturucu

`Packet_Builder` sınıfı, sorgu verilerini SA-MP/OMP sunucusunun anlayabileceği belirli bir ikili biçime serileştirmekten sorumludur.

```php
// ...
final class Packet_Builder {
    // ...
    private function Build_Header(): string {
        $packet = 'SAMP';
        $packet .= pack('c4', ...$this->ip_parts); // IP 4 bayt olarak
        $packet .= pack('v', $this->port); // Port 2 bayt olarak (little-endian)

        return $packet;
    }
    
    public function Build(string $opcode): string {
        $packet = $this->Build_Header();
        $packet .= $opcode;

        if ($opcode === Opcode::PING->value)
            $packet .= random_bytes(4); // PING için rastgele yük
        
        return $packet;
    }
    // ...
}
```

- **İkili Biçim için `pack()`:** Verileri (IP, port, dize uzunlukları) doğru ikili biçimlerine dönüştürmek için PHP'nin `pack()` fonksiyonunu kullanır, örneğin 4 baytlık karakterler (IP) için `c4` ve 16 bitlik işaretsiz tamsayı (port ve uzunluklar) için *little-endian* olan `v`.
- **Standart Başlık:** `Build_Header()`, tüm paketlerden önce gelen 10 baytlık 'SAMP' başlığını oluşturur.
- **RCON Yapısı:** `Build_Rcon`, RCON paketini 'x' opcode'u, ardından şifre uzunluğu, şifre, komut uzunluğu ve komutun kendisi ile biçimlendirir.

### 10. `packet_parser.php`: Kodlama İşlemli Paket Çözücü

`Packet_Parser` sınıfı, `Packet_Builder`'ın karşıtıdır ve sunucudan alınan ikili yanıtları yorumlayıp bunları yapılandırılmış PHP verilerine dönüştürmekten sorumludur.

```php
// ...
final class Packet_Parser {
    private int $offset = Protocol::MIN_PACKET_LENGTH; // Başlıktan sonra başlar (11 bayt)
    // ...
    public function __construct(private readonly string $data) {
        // 'SAMP' başlığının ilk doğrulaması
        if (substr($this->data, 0, 4) !== 'SAMP' || strlen($this->data) < Protocol::MIN_PACKET_LENGTH)
            throw new Malformed_Packet_Exception("Invalid response header or packet too short.");
    }
    
    private function Extract_String(int $length_bytes): string {
        // ... uzunluğu ve dizeyi okuma mantığı ...
        $string = substr($this->data, $this->offset, $length);
        $this->offset += $length;
        
        // **KRİTİK KODLAMA DÖNÜŞÜMÜ:** SA-MP/OMP sunucuları Windows-1252 kullanır
        return mb_convert_encoding($string, 'UTF-8', 'Windows-1252');
    }
    // ...
}
```

- **`offset` ve `data_length`:** `offset`, paket okumasında mevcut konumu izlemek için kullanılırken, `data_length` arabellek sınırlarının dışına okumaları önler.
- **Başlık Doğrulaması:** Yapıcı, hatalı biçimlendirilmiş paketleri hemen reddetmek için "sihirli dize" `SAMP`'ı doğrular.
- **`Extract_String()` - Kritik Kodlama Dönüşümü:** Bu, en önemli işlevlerden biridir. SA-MP protokolü, dizeleri **Windows-1252** kodlamasını kullanarak iletir. Özel karakterlerin (aksanlar veya Kiril harfleri gibi) PHP uygulamalarında (genellikle UTF-8'de çalışan) doğru görüntülenmesini sağlamak için `mb_convert_encoding($string, 'UTF-8', 'Windows-1252')` metodu uygulanır.
- **Değişken Uzunluk Çıkarma:** `Extract_String` metodu, dizeler için farklı uzunluk önek boyutlarını (1, 2 veya 4 bayt) destekler, bu da onu protokolün çeşitli alanları için esnek hale getirir.

### 11. `samp-query.php`: Ana Sınıf (Tam Orkestratör)

`Samp_Query` sınıfı, ana giriş noktası ve tüm işlemlerin orkestratörüdür. Tüm bileşenleri bir araya getirir, sorgu durumunu, *yeniden deneme* mantığını ve *zaman aşımlarını* yönetir.

#### Sorgu Yaşam Döngüsü: Bir Paketin Yolculuğu

Bir sunucuyu sorgulama süreci, maksimum dayanıklılık ve performansı hedefleyen, dikkatle düzenlenmiş bir dizi adımı izler.

##### 1. Başlatma ve Alan Adı Çözümlemesi

Bir `Samp_Query` örneği oluşturulduğunda:
- **Hızlı Doğrulama:** Yapıcı, `$hostname` ve `$port` parametrelerini doğrular. Herhangi bir tutarsızlık bir `Invalid_Argument_Exception` ile sonuçlanır.
- **DNS Önbellek Temizliği:** Yalnızca geçerli ve süresi dolmamış DNS girişlerinin dikkate alınmasını sağlamak için `Domain_Resolver::Clean_Expired_Cache()` çağrılır.
- **IP Çözümlemesi:** `$hostname`'i bir IP adresine (`$this->ip`) dönüştürmek için `Domain_Resolver` kullanılır. Bu IP, gelecekteki istekler için diske önbelleğe alınır ve çözümleme başarısız olursa bir `Query_Exception` fırlatılır.
- **Kaynakların Yapılandırılması:** `Logger`, `Socket_Manager` ve `Packet_Builder` örneklenir, bu da iletişim için altyapıyı hazırlar.

##### 2. `Fetch_Server_State()`: Önbellek ve Kritik INFO/PING Sorgusu

Bu dahili metot, bir performans ve tutarlılık koruyucusudur ve herhangi bir ana sorgudan önce sunucunun temel bilgilerinin (`Server_Info`) ve `ping`'in her zaman güncel olmasını sağlar.

- **Birincil Önbellek (5 Saniye):** Herhangi bir iletişim başlatmadan önce, `$this->cached_info`'nun (sunucunun `Server_Info` nesnesi) 5 saniyeden daha yeni verilere sahip olup olmadığı kontrol edilir (`$this->last_successful_query` ile karşılaştırılır). Veriler taze ise, fonksiyon gereksiz ağ trafiğini önleyerek hemen döner.
- **Kritik INFO Sorgusu:** Önbellek süresi dolmuş veya boşsa, `INFO` verilerini almak için `Attempt_Query` metodu çağrılır. Bu sorgu **kritik** (`true`) olarak işaretlenir, bu da daha fazla deneme ve daha cömert *zaman aşımlarını* tetikler. Tüm denemelerden sonra INFO yanıtı geçersizse bir `Connection_Exception` fırlatılır.
- **Ping Hesaplaması:** Eğer `$this->cached_ping` hala null ise, hızlı bir `PING` sorgusu (`Execute_Query_Phase` ile `Performance::FAST_PING_TIMEOUT`) gerçekleştirilir. Ping, alınan **ilk** yanıta kadar geçen süre olarak hesaplanır, bu da doğruluğu sağlar.

##### 3. `Attempt_Query()`: Optimize Edilmiş Yeniden Deneme Stratejisi

Bu, bir veya daha fazla `$jobs` (opcode sorguları) için üst düzey deneme döngüsünü yöneten, kütüphanenin dayanıklılığının beynidir.

- **Yanıt Önbelleği (2 Saniye):** İlk olarak, herhangi bir `$jobs` için yanıtların zaten `$this->response_cache`'de (2.0 saniyeden daha az) olup olmadığını kontrol eder. Bu, geçici ancak kritik olmayan veriler için gereksiz *yeniden denemeleri* önler.
- **Hızlı Yeniden Deneme Aşaması:** Kütüphane önce `Query::FAST_RETRY_ATTEMPTS` (varsayılan 2) denemeyi daha kısa bir *zaman aşımı* ile (`$timeout * 0.6`) dener. Amaç, önemli gecikmeler yaratmadan mümkün olan en kısa sürede bir yanıt almaktır.
- **Backoff ile Standart Yeniden Deneme Aşaması:** Hızlı aşama yeterli olmazsa, döngü kalan `Query::ATTEMPTS` ile devam eder. Bu aşamada, `$adjusted_timeout` her denemede aşamalı olarak artar ve sunucuya yanıt vermesi için daha fazla zaman tanır. Daha da önemlisi, `usleep()` `Execute_Query_Phase` çağrıları arasında artan bir gecikme ( `Query::RETRY_DELAY_MS` ve bir artış faktörüne dayalı) ekler, bu da ağın ve sunucunun dengelenmesine olanak tanır.
- **Acil Durum Yeniden Denemeleri (Kritik Sorgular için):** `critical` olarak işaretlenmiş `$jobs` için, önceki tüm denemeler başarısız olursa, her bir iş için ayrı ayrı, daha da büyük bir *zaman aşımı* (`$timeout * 2`) kullanılarak son bir *yeniden deneme* yapılır. Bu, hayati bilgileri elde etmek için son bir şanstır.

##### 4. `Execute_Query_Phase()`: Ping Tespiti ile İletişim Motoru

Bu alt düzey metot, gerçek UDP soketi etkileşiminin gerçekleştiği yerdir. Bir grup `$jobs` için paketlerin gönderimini ve alımını tek bir ağ aşamasında aynı anda yönetir.

```php
// ... (Execute_Query_Phase içinde)
private function Execute_Query_Phase(array $jobs, ?int &$ping, float $timeout): array {
    $socket = $this->socket_manager->Get_Socket_Resource($timeout);
    stream_set_blocking($socket, false); // Bloklamayan soket

    // UDP teslimat garantisini artırmak için paketleri hemen iki kez gönderir
    for ($i = 0; $i < 2; $i++) {
        foreach ($pending_jobs as $opcode)
            fwrite($socket, $this->packet_builder->Build($opcode->value));

        if ($i == 0)
            usleep(5000); // Küçük bir gecikme
    }

    $last_send_time = microtime(true);
    $current_retry_interval = Performance::INITIAL_RETRY_INTERVAL; // 0.08s
    
    while (!empty($pending_jobs) && (microtime(true) - $start_time) < $timeout) {
        $now = microtime(true);

        if (($now - $last_send_time) > $current_retry_interval) { // backoff ile yeniden gönderme mantığı
            // ... bekleyen paketleri yeniden gönderir ...
            $current_retry_interval = min($current_retry_interval * Performance::BACKOFF_FACTOR, 0.2); // yeniden deneme aralığını artırır
        }

        $read = [$socket]; $write = $except = null

        if (stream_select($read, $write, $except, 0, 10000) > 0) { // Veri bekler (en fazla 10ms)
            $response = stream_socket_recvfrom($socket, Protocol::UDP_READ_BUFFER_SIZE);
            // ... ayrıştırma, ping hesaplama ve doğrulama mantığı ...
        }

        if (!empty($pending_jobs))
            usleep(1000); // CPU dönüşünü önlemek için küçük gecikme
    }
    return $phase_results;
}
```

- **Bloklamayan Soket:** `stream_set_blocking($socket, false)` esastır. PHP'nin paket göndermesine ve ardından `stream_select` kullanarak yürütmeyi engellemeden yanıtları beklemesine olanak tanır.
- **Sağlamlık için Çift Gönderim:** Tüm `$pending_jobs` için paketler, aşamanın başında art arda **iki kez** (aralarında küçük bir `usleep(5000)` ile) gönderilir. Bu uygulama, istikrarsız veya paket kayıplı ağlarda teslimat olasılığını önemli ölçüde artırmak ve UDP'nin güvenilmez doğasını azaltmak için UDP protokollerinde temeldir. `INFO` ve `PING` için, ana döngüdeki *yeniden denemeler* sırasında ek bir üçüncü gönderim yapılır.
- **Uyarlanabilir Backoff ile Alım Döngüsü:**
   - Bir ana `while` döngüsü, tüm `$jobs` tamamlanana veya aşamanın *zaman aşımı* sona erene kadar devam eder.
   - **Dinamik Yeniden Gönderim:** Son gönderimden bu yana geçen süre (`$now - $last_send_time`) `$current_retry_interval`'ı aşarsa, `$pending_jobs` için paketler yeniden gönderilir. `$current_retry_interval` daha sonra artırılır (`min($current_retry_interval * Performance::BACKOFF_FACTOR, 0.2)`), bu da sunucunun aşırı yüklenmesini önleyen ve bir yanıt şansını en üst düzeye çıkaran üstel bir *backoff* uygular.
   - **Optimize Edilmiş `stream_select`:** `stream_select($read, $write, $except, 0, 10000)`, en fazla 10 milisaniye boyunca veri beklemek için kullanılır. Bu, kütüphanenin duyarlı olmasını ve paketleri geldikleri anda işlemesini sağlar.
   - **Hassas Ping Ölçümü:** **İlk** geçerli paket alındığında (`$packets_received === 0`), `ping` ilk paket grubunun gönderiminin başlangıcındaki `microtime(true)` ile **ilk** geçerli paketin tam alınma zamanı arasındaki fark olarak yüksek hassasiyetle hesaplanır.
- **Yanıtın İşlenmesi ve Doğrulanması:** Alınan yanıtlar `Packet_Parser` tarafından çözülür. Bir `Malformed_Packet_Exception` paketi tespit edilirse, bu loglanır ve paket tekrar denemek için hemen sunucuya yeniden gönderilir. Çözülen yanıt daha sonra `Validate_Response()` tarafından doğrulanır. Geçerliyse, `$phase_results`'e ve `$this->response_cache`'e eklenir.

##### 5. `Validate_Response()`: Anlamsal Doğrulama Katmanı

`Samp_Query` sınıfında uygulanan bu kritik metot, kullanıcıya teslim edilmeden önce çözülen verilerin bütünlüğünü ve mantıksal tutarlılığını kontrol eder.

```php
// ... (Validate_Response içinde)
private function Validate_Response($data, Opcode $opcode): bool {
    switch ($opcode) {
        case Opcode::INFO:
            // hostname'in boş olmadığını ve oyuncu sayılarının mantıklı olduğunu garanti eder
            return $data instanceof Server_Info && $data->max_players > 0 && $data->players >= 0 && $data->players <= $data->max_players && !empty($data->hostname);
        
        case Opcode::RULES:
            if (!is_array($data))
                return false;

            // ...

            return true;
        // ... PLAYERS_DETAILED, PLAYERS_BASIC, PING için doğrulamalar ...
    }
}
```

- **Opcode'a Göre Doğrulama:** Her `Opcode`'un kendi özel doğrulama mantığı vardır. Örneğin:
   - `Opcode::INFO` için: `$data`'nın bir `Server_Info` örneği olduğunu, `$data->max_players > 0` olduğunu, `$data->players`'ın 0 ile `max_players` arasında olduğunu ve `$data->hostname`'in boş olmadığını garanti eder.
   - `Opcode::RULES` veya oyuncu listeleri için: Dönüşün bir `array` olup olmadığını ve boş değilse, ilk elemanın beklenen model örneği (`Server_Rule`, `Players_Detailed`, vb.) olup olmadığını kontrol eder.
- **Sağlamlık:** Doğrulama başarısız olursa, yanıt geçersiz kabul edilir ve atılır. Bu, sistemi, paket hiç gelmemiş gibi denemelere devam etmeye zorlar ve uygulamayı bozuk veya tutarsız sunucu verilerine karşı korur.

#### Uyarlanabilir Zaman Aşımı Hesaplaması ve Yönetimi

Kütüphane, hız ve dayanıklılığı dengelemek için sofistike bir *zaman aşımı* stratejisi uygular:

- **`Performance::METADATA_TIMEOUT`:** (0.8 saniye) `INFO` ve `RULES` gibi hızlı sorgular için temel *zaman aşımıdır*.
- **`Performance::PLAYER_LIST_BASE_TIMEOUT`:** (1.0 saniye) Oyuncu listesi sorguları için temel *zaman aşımıdır*.
- **`Performance::PING_MULTIPLIER`:** (2) Ping'e dayalı olarak *zaman aşımını* ayarlamak için kullanılır.
- **Ping'e Göre Ayarlama:** `Fetch_Player_Data` metodunda, oyuncu listesini alma *zaman aşımı* dinamik olarak ayarlanır:
   ```
   Oyuncu Zaman Aşımı = PLAYER_LIST_BASE_TIMEOUT + (Önbellenmiş Ping * PING_MULTIPLIER / 1000)
   ```
   Bu yaklaşım, yüksek gecikmeli (yüksek ping) sunucuların daha uzun bir *zaman aşımına* sahip olmasını sağlar, bu da büyük olabilen ve sunucu tarafından işlenmesi zaman alabilen tam oyuncu listesini alma şansını artırır.
- **Zaman Aşımı Sınırı:** `min($timeout, 2.0)`, aşırı beklemeleri önlemek için meta veri sorguları için maksimum 2.0 saniyelik bir sınır uygulamak amacıyla çeşitli çağrılarda kullanılır.

#### Genel Sorgu Metotları

| Metot | Detaylı Açıklama | Dahili Önbellekleme Davranışı |
| :--- | :--- | :--- |
| `Get_All()` | **Genel kullanım için önerilen metot.** `INFO`, `RULES`, `PLAYERS_DETAILED` (veya yedek olarak `PLAYERS_BASIC`) bilgilerinin paralel olarak alınmasını düzenler. Paketler neredeyse aynı anda gönderildiği ve yanıtlar geldikçe işlendiği için bu, toplam sorgu süresini en aza indirir. Toplam `execution_time_ms` ölçümünü içerir. | Paralel aşama içinde sorgulanan her opcode için 2.0s önbelleği (`$this->response_cache`) kullanır. |
| `Is_Online()` | Hızlı bir `INFO` sorgusu yapar ve sunucu *zaman aşımı* içinde geçerli bir `Server_Info` ile yanıt verirse `true`, aksi takdirde `false` döndürür. Kritik *yeniden denemeler* kullanarak sağlamdır. | Dahili olarak, `INFO` için 5.0s önbelleği kullanan `Fetch_Server_State()`'i çağırır. |
| `Get_Ping()` | Sunucunun en son ping'ini milisaniye cinsinden döndürür. Eğer `cached_ping` null ise, hızlı bir ölçüm elde etmek için `Performance::FAST_PING_TIMEOUT` (0.3s) ile özel bir `PING` sorgusu zorlar. | `ping`, `Execute_Query_Phase` ilk yanıtı aldığında önbelleğe alınır ve güncellenir. |
| `Get_Info()` | Hostname, gamemode, oyuncu sayısı gibi ayrıntıları içeren bir `Server_Info` nesnesi döndürür. | 5.0s önbelleği kullanan `Fetch_Server_State()`'i çağırır. |
| `Get_Rules()` | Sunucuda yapılandırılmış tüm kuralları (`mapname`, `weburl` gibi) içeren `Server_Rule` nesnelerinden oluşan bir `array` döndürür. İlk başarısızlık durumunda ek *yeniden denemeler* içerir. | `Opcode::RULES` için 2.0s önbelleği kullanır. |
| `Get_Players_Detailed()` | Her oyuncu için `Players_Detailed` nesnelerinden (id, isim, skor, ping) oluşan bir `array` döndürür. **Önemli:** Sunucudaki oyuncu sayısı (`$this->cached_info->players`) `Query::LARGE_PLAYER_THRESHOLD` (varsayılan 150) değerinden büyük veya eşitse, uzun süreli *zaman aşımları* veya paket parçalanması riski nedeniyle bu sorgu atlanır. | `Opcode::PLAYERS_DETAILED` için 2.0s önbelleği kullanır. |
| `Get_Players_Basic()` | Her oyuncu için `Players_Basic` nesnelerinden (isim, skor) oluşan bir `array` döndürür. Detaylı sorgudan daha hafiftir. Genellikle `Get_Players_Detailed()` başarısız olursa veya atlanırsa *yedek* olarak çağrılır. | `Opcode::PLAYERS_BASIC` için 2.0s önbelleği kullanır. |

#### RCON İletişimi (`Send_Rcon`)

`Send_Rcon(string $rcon_password, string $command)` metodu, sunucunun uzaktan konsoluna komut göndermeyi sağlar.

1.  **Argümanların Doğrulanması:** Şifre veya komut boşsa `Invalid_Argument_Exception` fırlatır.
2.  **İzole Soket:** RCON oturumu için yeni bir `Socket_Manager` örneği (`$rcon_socket_manager`) oluşturur, bunu ana sorgu soketinden izole ederek etkileşimi önler.
3.  **Kimlik Doğrulama (`varlist`):** Gerçek komutu göndermeden önce, kütüphane RCON şifresini doğrulamak için "varlist" komutunu gönderir (en fazla 3 deneme). `Send_Single_Rcon_Request` `null` veya boş bir yanıt döndürürse, kimlik doğrulama hatası veya RCON'un etkin olmadığını belirten bir `Rcon_Exception` fırlatılır.
4.  **Gerçek Komutun Gönderilmesi:** Başarılı kimlik doğrulamasından sonra, `$command` de en fazla 3 deneme ile gönderilir.
5.  **Yanıtın İşlenmesi:** `Packet_Parser::Parse_Rcon()`, RCON'dan gelen metin yanıtını çözer. Sunucu tüm denemelerden sonra metinsel bir yanıt döndürmezse, genel bir başarı mesajı döndürülür.
6.  **Temizlik:** `$rcon_socket_manager`'ın yıkıcısı, işlemden sonra RCON soketinin kapatılmasını sağlar.

## Hata Teşhisi ve İstisnalar

Kütüphane, temiz ve öngörülebilir bir hata işleme için özel bir istisna hiyerarşisi kullanır. Başarısızlık durumunda, aşağıdaki istisnalardan biri fırlatılacaktır.

### `Invalid_Argument_Exception`

**Neden:**
- **Boş Hostname:** `Samp_Query` yapılandırıcısında sağlanan `hostname` boş bir dizedir.
- **Geçersiz Port:** Yapılandırıcıda sağlanan `port`, 1 ila 65535 geçerli aralığının dışındadır.
- **RCON:** `Send_Rcon`'a sağlanan RCON şifresi veya RCON komutu boştur.

### `Connection_Exception`

**Neden:** Ağ hatası veya temel bir yanıtın olmaması.
- **Alan Adı Çözümlemesi Başarısız:** `Domain_Resolver`, hostname'i geçerli bir IPv4'e dönüştüremez.
- **Soket Oluşturma Hatası:** `Socket_Manager`, UDP soketini oluşturamaz veya bağlayamaz.
- **Sunucu Erişilemez/Çevrimdışı:** Sunucu, tüm denemeler ve *zaman aşımlarından* (acil durum *yeniden denemeleri* dahil) sonra geçerli bir `INFO` paketiyle yanıt vermez, bu genellikle sunucunun çevrimdışı olduğunu, IP/portun yanlış olduğunu veya bir güvenlik duvarının iletişimi engellediğini gösterir.

### `Malformed_Packet_Exception`

**Neden:** Protokol düzeyinde veri bozulması.
- **Geçersiz Başlık:** `Packet_Parser`, `SAMP` "sihirli dizesi" ile başlamayan veya yetersiz toplam uzunluğa sahip bir paket tespit eder.
- **Geçersiz Paket Yapısı:** `Packet_Parser`, dize uzunluğunun paketin sınırlarının dışını göstermesi gibi ikili yapıda tutarsızlıklar bulur.
- **Dayanıklılık:** Bu istisna, anında bir *yeniden denemeyi* tetiklemek için genellikle `Execute_Query_Phase` tarafından dahili olarak işlenir, ancak sorun devam ederse yayılabilir.

### `Rcon_Exception`

**Neden:** RCON iletişimi sırasında hata.
- **RCON Kimlik Doğrulama Hatası:** Sunucu, 3 denemeden sonra RCON kimlik doğrulamasına (`varlist` komutu aracılığıyla) yanıt vermedi, bu da yanlış şifre veya sunucuda RCON'un devre dışı bırakıldığını düşündürür.
- **RCON Komut Gönderme Hatası:** Gerçek RCON komutu, 3 denemeden sonra yanıt alamadı.

## Lisans

Copyright © **SA-MP Programming Community**

Bu yazılım MIT Lisansı ("Lisans") şartları altında lisanslanmıştır; bu yazılımı Lisans şartlarına uygun olarak kullanabilirsiniz. Lisansın bir kopyasını şu adresten edinebilirsiniz: [MIT License](https://opensource.org/licenses/MIT)

### Kullanım Şartları ve Koşulları

#### 1. Verilen İzinler

Bu lisans, bu yazılımın ve ilgili dokümantasyon dosyalarının bir kopyasını edinen herhangi bir kişiye ücretsiz olarak aşağıdaki hakları vermektedir:
* Yazılımın kopyalarını kullanma, kopyalama, değiştirme, birleştirme, yayınlama, dağıtma, alt lisans verme ve/veya satma hakkı
* Yazılımın sağlandığı kişilerin de aynısını yapmasına izin verme hakkı (aşağıdaki koşullara tabi olmak kaydıyla)

#### 2. Zorunlu Koşullar

Yazılımın tüm kopyaları veya önemli parçaları şunları içermelidir:
* Yukarıdaki telif hakkı bildirimi
* Bu izin bildirimi
* Aşağıdaki sorumluluk reddi

#### 3. Telif Hakları

Yazılım ve ilgili tüm dokümantasyon telif hakkı yasaları ile korunmaktadır. **SA-MP Programming Community** yazılımın orijinal telif haklarını elinde tutmaktadır.

#### 4. Garanti Reddi ve Sorumluluk Sınırlaması

YAZILIM "OLDUĞU GİBİ" SAĞLANMAKTADIR, HİÇBİR TÜRDE GARANTİ VERİLMEMEKTEDİR, AÇIK VEYA ZIMNİ, TİCARİ ELVERİŞLİLİK, BELİRLİ BİR AMACA UYGUNLUK VE İHLAL ETMEME GARANTİLERİ DAHİL ANCAK BUNLARLA SINIRLI OLMAMAK ÜZERE.

HİÇBİR KOŞULDA YAZARLAR VEYA TELİF HAKKI SAHİPLERİ HERHANGİ BİR İDDİA, HASAR VEYA DİĞER YÜKÜMLÜLÜKLERDEN SORUMLU TUTULAMAZ, İSTER SÖZLEŞME KAPSAMINDA, HAKSIZ FİİL VEYA BAŞKA BİR ŞEKİLDE OLSUN, YAZILIMDAN VEYA YAZILIMIN KULLANIMINDAN VEYA DİĞER İŞLEMLERDEN KAYNAKLANAN DURUMLAR İÇİN.

---

MIT Lisansı hakkında detaylı bilgi için: https://opensource.org/licenses/MIT