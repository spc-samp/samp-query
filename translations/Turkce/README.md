# SA-MP Query

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)

**SA-MP Query**, SA-MP ve open.mp sunucularıyla etkileşim kurmak için tasarlanmış birden fazla programlama dili için sunucu sorgulama kütüphaneleri setidir.

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

- [SA-MP Query](#sa-mp-query)
  - [Diller](#diller)
  - [İçindekiler](#i̇çindekiler)
  - [Felsefe](#felsefe)
  - [Ana Özellikler](#ana-özellikler)
  - [Kütüphaneler](#kütüphaneler)
    - [Kütüphane Listesi](#kütüphane-listesi)
  - [Lisans](#lisans)
    - [Kullanım Şartları ve Koşulları](#kullanım-şartları-ve-koşulları)
      - [1. Verilen İzinler](#1-verilen-i̇zinler)
      - [2. Zorunlu Koşullar](#2-zorunlu-koşullar)
      - [3. Telif Hakları](#3-telif-hakları)
      - [4. Garanti Reddi ve Sorumluluk Sınırlaması](#4-garanti-reddi-ve-sorumluluk-sınırlaması)

## Felsefe

**SA-MP Query** projesinin ana hedefi, mevcut sunucu sorgulama araçları arasındaki tutarsızlığı ve parçalanmayı çözmektir. Proje, farklı programlama dillerinde tutarlı bir şekilde uygulanan standart bir **Application Programming Interface (API)** oluşturur.

Bu yaklaşım, kullanılan teknolojik yığına bakılmaksızın öngörülebilir ve tekdüze bir geliştirme deneyimi sağlar. Resmi **query** protokolü üzerinde güvenilir ve iyi belgelenmiş bir soyutlama sağlayarak, proje karmaşıklığı azaltmayı ve aşağıdaki gibi uygulamaların geliştirilmesini hızlandırmayı amaçlar:
- Sunucu izleme panelleri.
- Otomasyon sistemleri.
- Gerçek zamanlı analiz araçları.
- Botlar ve diğer platformlarla entegrasyonlar.

Odak noktası doğruluk, performans ve bakım kolaylığıdır, böylece sunucu verilerini almaya dayanan herhangi bir uygulama için sağlam bir temel oluşturulur.

## Ana Özellikler

- **Standart API:** Yöntemlerin, parametrelerin ve dönüş değerlerinin yapısı tüm kütüphanelerde tutarlıdır, böylece etkileşim mantığı yalnızca bir kez öğrenilir.
- **Çoklu Dil Desteği:** Başlıca programlama dilleri için yerel uygulamalar, farklı ekosistemlere esnek entegrasyon sağlar.
- **Protokol Uyumluluğu:** Maksimum uyumluluk ve veri doğruluğunu sağlamak için SA-MP’nin resmi **query UDP** protokolünün sıkı bir şekilde uygulanması.
- **Performans Odaklı:** Her kütüphane, minimum performans yükü ve bağımlılıklarla hafif olacak şekilde tasarlanmıştır.
- **Açık Kaynak:** Proje, topluluğun işbirliği, kullanımı ve değiştirilmesi için teşvik edici bir izinli lisans altında sürdürülür.

## Kütüphaneler

**SA-MP Query** ekosistemi, her biri belirli bir programlama dili için tasarlanmış bir dizi resmi uygulamayı içerir. Her kütüphanenin geliştirilmesi ve bakımı, **API** tutarlılığını ve orijinal protokole uyumu sağlamak için projenin standartlarına sıkı sıkıya bağlıdır.

### Kütüphane Listesi

| Dil | Adı                 | Kütüphane/Dökümantasyon Linki                 | İndir                                                                                 |
| --- | ------------------- | --------------------------------------------- | ------------------------------------------------------------------------------------- |
| PHP | **SA-MP Query PHP** | [Kütüphaneyi Kontrol Et](../../libraries/php) | [Buraya Tıkla](https://github.com/spc-samp/samp-query/releases/download/v1.0/php.zip) |

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