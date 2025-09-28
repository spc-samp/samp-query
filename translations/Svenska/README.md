# SA-MP Query

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)

**SA-MP Query** är en samling av serverförfrågansbibliotek för flera programmeringsspråk, utformade för att interagera med SA-MP- och open.mp-servrar.

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

- [SA-MP Query](#sa-mp-query)
  - [Språk](#språk)
  - [Innehållsförteckning](#innehållsförteckning)
  - [Filosofi](#filosofi)
  - [Huvudfunktioner](#huvudfunktioner)
  - [Bibliotek](#bibliotek)
    - [Lista över bibliotek](#lista-över-bibliotek)
  - [Licens](#licens)
    - [Användarvillkor](#användarvillkor)
      - [1. Beviljade rättigheter](#1-beviljade-rättigheter)
      - [2. Obligatoriska villkor](#2-obligatoriska-villkor)
      - [3. Upphovsrätt](#3-upphovsrätt)
      - [4. Garantifriskrivning och ansvarsbegränsning](#4-garantifriskrivning-och-ansvarsbegränsning)

## Filosofi

Det primära målet med projektet **SA-MP Query** är att lösa inkonsekvensen och fragmenteringen bland befintliga verktyg för serverförfrågningar. Projektet etablerar ett standardiserat **Application Programming Interface (API)**, implementerat konsekvent över olika programmeringsspråk.

Detta tillvägagångssätt säkerställer en förutsägbar och enhetlig utvecklingsupplevelse, oavsett vilken teknologisk stack som används. Genom att tillhandahålla en pålitlig och väldokumenterad abstraktion över det officiella **query**-protokollet syftar projektet till att minska komplexiteten och påskynda utvecklingen av applikationer som:
- Övervakningspaneler för servrar.
- Automatiseringssystem.
- Verktyg för realtidsanalys.
- Integrationer med bottar och andra plattformar.

Fokus ligger på noggrannhet, prestanda och enkel underhåll, vilket skapar en stabil grund för alla applikationer som är beroende av att hämta serverdata.

## Huvudfunktioner

- **Standardiserat API:** Strukturen för metoder, parametrar och returvärden är konsekvent i alla bibliotek, vilket säkerställer att interaktionslogiken bara behöver läras en gång.
- **Stöd för flera språk:** Inhemska implementeringar för de viktigaste programmeringsspråken, vilket möjliggör flexibel integration i olika ekosystem.
- **Protokollöverensstämmelse:** Strikt implementering av det officiella **query UDP**-protokollet för SA-MP för att säkerställa maximal kompatibilitet och datanoggrannhet.
- **Prestandaorienterat:** Varje bibliotek är utformat för att vara lättviktigt, med minimal prestandaöverhead och beroenden.
- **Open Source:** Projektet underhålls under en tillåtande licens för att uppmuntra samarbete, användning och modifiering av gemenskapen.

## Bibliotek

Ekosystemet **SA-MP Query** omfattar en uppsättning officiella implementeringar, var och en utformad för ett specifikt programmeringsspråk. Utvecklingen och underhållet av varje bibliotek följer strikt projektets standarder för att säkerställa konsekvens i **API** och överensstämmelse med det ursprungliga protokollet.

### Lista över bibliotek

| Språk | Namn                | Länk till Bibliotek/Dokumentation            | Ladda Ner                                                                           |
| ----- | ------------------- | -------------------------------------------- | ----------------------------------------------------------------------------------- |
| PHP   | **SA-MP Query PHP** | [Kontrollera Bibliotek](../../libraries/php) | [Klicka Här](https://github.com/spc-samp/samp-query/releases/download/v1.0/php.zip) |

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