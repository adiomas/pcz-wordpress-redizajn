# Generički Prompt Template za pcz Redizajn

## Kako Koristiti Ovaj Template

Kada radiš bilo kakve promjene ili implementacije na projektu, koristi ovaj template za konzistentnost i kvalitetu.

---

## PROMPT TEMPLATE

```
## Kontekst Projekta
- **Projekt:** pcz (Plesni Centar Zagreb by Nicolas) WordPress Redizajn
- **Page Builder:** Oxygen Builder
- **Custom Fields:** ACF PRO
- **Trenutna faza:** [opiši fazu - npr. "Implementacija headera"]

## Što Sam Napravio (Context)
[Detaljno opiši što si do sada napravio, uključujući:]
- Koje fajlove si kreirao/modificirao
- Koje ACF field grupe si dodao
- Koje WordPress postavke si promijenio
- Screenshots ako je potrebno

## Što Želim Napraviti
[Jasno definiraj cilj:]
- Konkretan zadatak
- Željeni vizualni rezultat (priloži screenshot/mockup ako postoji)
- Funkcionalni zahtjevi

## Tehnički Zahtjevi
- [ ] Kod mora biti u zasebnim fajlovima (ne inline)
- [ ] Dokumentacija mora biti ažurirana
- [ ] Slijediti postojeću strukturu projekta
- [ ] Kompatibilno s Oxygen Builder
- [ ] Responsivni dizajn (desktop/tablet/mobile)

## Pitanja (ako ih ima)
[Postavi specifična pitanja za pojašnjenje]

## Koristi Context7
use context7
```

---

## PRAVILA ZA AI ASISTENTA

Kada radiš na ovom projektu, UVIJEK:

### 1. Struktura Koda
- Sav PHP kod ide u `/snippets/` ili odgovarajući direktorij
- Sav CSS ide u zasebne `.css` fajlove
- JavaScript ide u zasebne `.js` fajlove
- Nikad inline stilovi osim ako nije apsolutno nužno

### 2. Dokumentacija
- Ažuriraj `DOKUMENTACIJA.md` za svaku značajnu promjenu
- Dodaj komentare u kod
- Održavaj changelog

### 3. Oxygen Builder Specifično
- Koristi Reusable Parts za komponente koje se ponavljaju
- Koristi PHP Code Block za dinamičke podatke
- Koristi CSS Code Block za custom stilove
- Prati Oxygen naming konvencije

### 4. ACF Specifično
- Koristi Local JSON za sync (`/acf-json/` folder)
- Dokumentiraj field grupe u JSON formatu
- Koristi opisne field keys (npr. `field_mega_menu_title`)

### 5. WordPress Standardi
- Prati WordPress Coding Standards
- Koristi `esc_html()`, `esc_url()` za output
- Koristi `wp_enqueue_style/script` za assets
- Nikad direktno editati core fajlove

### 6. Responsive Design
- Mobile-first pristup
- Breakpoints: 
  - Mobile: < 768px
  - Tablet: 768px - 1024px
  - Desktop: > 1024px

### 7. Performanse
- Optimiziraj slike
- Minimiziraj HTTP requests
- Koristi lazy loading gdje je moguće

### 8. Multi-Brand Awareness

**VAŽNO:** Ovaj projekt koristi multi-brand sustav s dva branda:
- **Plesna Škola** (default) - magenta tema (#CB007C)
- **Sportski Klub** - narančasta tema (#FF6B00)

#### Brand Detection
Brand se određuje putem `?brand=` query parametra:
- `?brand=plesna-skola` → Plesna Škola
- `?brand=sportski-klub` → Sportski Klub
- Bez parametra → Plesna Škola (default)

#### Kada Koristiti Brand Conditions

Koristi brand conditions kada:
- Sekcija se prikazuje SAMO za jedan brand
- Sadržaj se razlikuje između brandova
- Logo/boje/slike ovise o brandu

NE koristi brand conditions kada:
- Sadržaj je isti za oba branda
- Samo boje se mijenjaju (to rješava CSS)

#### Oxygen Builder - Custom PHP Conditions

Za brand-specifične sekcije koristi Oxygen Conditions:

1. Selektiraj element → Settings → Conditions → Edit Conditions
2. +Add Condition → Custom PHP → Open PHP Editor
3. Upiši odgovarajuću funkciju:

```php
// SAMO za Plesnu Školu
return pcz_is_plesna_skola();

// SAMO za Sportski Klub  
return pcz_is_sportski_klub();

// Za specifični brand (generički)
return pcz_brand_is( 'sportski-klub' );

// Za sve OSIM određenog branda
return pcz_brand_is_not( 'sportski-klub' );
```

#### CSS Brand Varijable

Za stilove koji se mijenjaju po brandu, koristi CSS varijable:

```css
/* ✅ ISPRAVNO - koristi brand varijable */
.my-element {
    background-color: var(--brand-primary);
    color: var(--brand-text);
}

/* ❌ POGREŠNO - hardkodirane boje */
.my-element {
    background-color: #CB007C;
}
```

#### PHP Brand Helpers

```php
// Provjeri aktivni brand
if ( pcz_is_plesna_skola() ) {
    // Kod za Plesnu Školu
}

// Dohvati brand-aware ACF polje
$naslov = pcz_get_brand_field( 'hero_naslov' );
// → vraća 'hero_naslov' za PS, 'spk_hero_naslov' za SPK

// Dohvati ID trenutnog branda
$brand_id = pcz_current_brand_id();
```

#### Shortcode Brand Conditions

```
[pcz_if_brand brand="plesna-skola"]
    Sadržaj samo za Plesnu Školu
[/pcz_if_brand]

[pcz_if_brand brand="sportski-klub"]
    Sadržaj samo za Sportski Klub
[/pcz_if_brand]

[pcz_unless_brand brand="sportski-klub"]
    Sadržaj za sve OSIM Sportski Klub
[/pcz_unless_brand]
```

#### Brand Switcher

Za prikaz brand switchera koristi shortcode:

```
[pcz_brand_switcher style="tabs" size="normal"]
```

Ili u Oxygen Code Block:

```php
<?php echo do_shortcode('[pcz_brand_switcher style="tabs"]'); ?>
```

### 10. Development Workflow - Test Environment

**VAŽNO:** Ovaj projekt koristi lokalni test environment za razvoj komponenti BEZ WordPressa.

#### Struktura Projekta - Komponente

Svaka komponenta ima **dva dijela**: produkcijski kod i test wrapper.

```
pcz-redizajn/
├── [sekcija]/                    # PRODUKCIJSKI KOD (ide na WordPress)
│   ├── [sekcija].php             # Glavna PHP komponenta
│   ├── [sekcija].css             # CSS stilovi
│   ├── [sekcija].js              # JavaScript (ako treba)
│   ├── acf_[sekcija]_fields.json # ACF field group export
│   └── oxygen-[sekcija]-code-block.php  # Oxygen wrapper
│
├── snippets/
│   └── pcz-[sekcija]-init.php    # WordPress snippet (shortcode + enqueue)
│
└── test/
    ├── templates/
    │   └── [sekcija]/            # TEST ENVIRONMENT (samo za lokalni razvoj)
    │       ├── test.php          # Test wrapper - UKLJUČUJE komponentu iz /[sekcija]/
    │       └── mock-data.php     # Mock ACF podaci za testiranje
    └── core/
        ├── wp-mock.php           # Mock WordPress/ACF funkcija
        └── helpers.php           # Helper funkcije
```

#### Ključna Razlika

| Folder | Namjena | Ide na WordPress? |
|--------|---------|-------------------|
| `/[sekcija]/` | Produkcijski kod komponente | ✅ DA |
| `/test/templates/[sekcija]/` | Test wrapper + mock podaci | ❌ NE |
| `/snippets/` | WordPress snippeti | ✅ DA |

#### ⚠️ ZLATNO PRAVILO: NIKAD DUPLICIRAJ KOD

Test environment **UVIJEK UKLJUČUJE** produkcijski kod - **NIKAD ga ne duplicira!**

```
✅ ISPRAVNO:
test/templates/hero/test.php --> include('/hero/hero.php')
test/templates/hero/test.php --> <link href="/hero/hero.css">

❌ POGREŠNO:
test/templates/hero/test.php --> copy-paste hero.php koda
test/templates/hero/test.php --> duplicirani CSS stilovi
```

**Zašto?**
- Jedna istina (single source of truth)
- Promjena u `/hero/hero.css` automatski se vidi u testu
- Nema sync problema između produkcije i testa
- Test environment služi SAMO za: mock podatke, scenarije, i privremene `!important` override-e

#### Workflow za Novu Komponentu

**Korak 1: Kreiraj produkcijski kod**
```
[sekcija]/
├── [sekcija].php      # PHP markup + logika
├── [sekcija].css      # Stilovi
└── acf_[sekcija]_fields.json
```

**Korak 2: Kreiraj test environment**
```
test/templates/[sekcija]/
├── test.php           # Wrapper koji UKLJUČUJE /[sekcija]/[sekcija].php
└── mock-data.php      # Simulira ACF get_field() pozive
```

**Korak 3: Testiraj i iteriraj**
- Pokreni: `php -S localhost:8000 router.php`
- Otvori: `http://localhost:8000/[sekcija]`
- Koristi `!important` override-e u test.php za brzo eksperimentiranje
- Kad si zadovoljan, primijeni stilove u `/[sekcija]/[sekcija].css`

**Korak 4: WordPress implementacija**
- Upload `/[sekcija]/` fajlove u `wp-content/uploads/pcz-[sekcija]/`
- Koristi Oxygen Code Block ili `[pcz_sekcija]` shortcode

#### Primjer test.php Structure

```php
<?php
// 1. Bootstrap test environment
require_once __DIR__ . '/../../core/bootstrap.php';

// 2. Učitaj MOCK podatke (simulira ACF)
$mock_data = require __DIR__ . '/mock-data.php';
load_mock_data($mock_data);

// 3. Apply scenario ako postoji
$scenario = $_GET['scenario'] ?? 'default';
if ($scenario !== 'default') {
    apply_scenario($scenario, $mock_data);
}

// 4. Definiraj putanje do PRODUKCIJSKOG koda
$component_path = pcz_PROJECT_ROOT . '/[sekcija]/[sekcija].php';
?>
<!DOCTYPE html>
<html>
<head>
    <!-- ⚠️ CSS DOLAZI IZ PRODUKCIJSKOG FOLDERA - ne dupliciraj! -->
    <link rel="stylesheet" href="/[sekcija]/[sekcija].css">
    
    <style>
    /* =======================================================
       PRIVREMENI OVERRIDE STILOVI - samo za testiranje!
       Kad si zadovoljan, PRIMIJENI ove stilove u:
       /[sekcija]/[sekcija].css (bez !important)
       pa OBRIŠI ovaj style blok.
       ======================================================= */
    </style>
</head>
<body>
    <?php 
    // ⚠️ UKLJUČI produkcijsku komponentu - NIKAD ne dupliciraj kod!
    include $component_path; 
    ?>
</body>
</html>
```

#### Workflow: Od Testa do Produkcije

```
1. TESTIRAJ: Dodaj !important override u test.php <style> blok
   ↓
2. ITERIRAJ: Osvježavaj browser dok nije savršeno
   ↓
3. PRIMIJENI: Kopiraj finalne stilove u /[sekcija]/[sekcija].css (BEZ !important)
   ↓
4. OČISTI: Obriši override stilove iz test.php
   ↓
5. PROVJERI: Test i produkcija sada izgledaju identično
```

#### Primjer mock-data.php

```php
<?php
return [
    'acf_fields' => [
        // Simulira get_field('naslov', 'option')
        '[sekcija]_naslov' => 'Naslov Sekcije',
        '[sekcija]_tekst'  => 'Lorem ipsum dolor sit amet.',
        
        // Za textarea s newlines - koristi \n, NE <br>
        '[sekcija]_opis' => "Prva linija\nDruga linija",
    ],
    
    'scenarios' => [
        'default' => [],
        'no_title' => [
            'acf_fields' => ['[sekcija]_naslov' => '']
        ],
        'long_text' => [
            'acf_fields' => ['[sekcija]_tekst' => 'Vrlo dugačak tekst...']
        ],
    ],
];
```

#### ACF Textarea Napomena

Za polja koja trebaju podržavati nove redove:

| ACF Postavka | PHP Output | Rezultat |
|--------------|------------|----------|
| `new_lines: ""` (prazno) | `nl2br(esc_html($tekst))` | ✅ Pravilno |
| `new_lines: "br"` | `wp_kses_post($tekst)` | ⚠️ ACF dodaje `<br>` |

**Preporuka:** Koristi `new_lines: ""` i `nl2br(esc_html())` u PHP-u.

#### Pokretanje Test Servera
```bash
cd /Users/qguest/Developer/pcz-redizajn/test
php -S localhost:8000 router.php
```
Otvori: `http://localhost:8000/[sekcija]` ili `http://localhost:8000/[sekcija]?scenario=no_title`

---

## PRIMJERI PROMPTA ZA RAZLIČITE ZADATKE

### Primjer 1: Dodavanje Nove Sekcije

```
## Kontekst Projekta
- **Projekt:** pcz WordPress Redizajn
- **Page Builder:** Oxygen Builder
- **Trenutna faza:** Implementacija homepage sekcija

## Što Sam Napravio
- Kreiran header s mega menu-om
- Postavljene ACF field grupe za mega menu
- Dokumentacija u /docs/DOKUMENTACIJA.md

## Što Želim Napraviti
Dodati Hero sekciju na homepage sa:
- Pozadinskom slikom/videom
- Naslovom i podnaslovom
- CTA buttonom
- Animacijom na scroll

## Tehnički Zahtjevi
- ACF polja za sve tekstove i slike
- Responsivno za sve uređaje
- Smooth scroll animacije

use context7
```

### Primjer 2: Stiliziranje Komponente

```
## Kontekst Projekta
- **Projekt:** pcz WordPress Redizajn
- **Page Builder:** Oxygen Builder
- **Trenutna faza:** Stiliziranje menija

## Što Sam Napravio
- Mega menu struktura je gotova
- ACF podaci se ispravno prikazuju

## Što Želim Napraviti
Stilizirati mega menu dropdown:
- Tamna pozadina (#3d3d3d)
- Magenta akcenti (#C71585)
- Smooth fade animacija na hover
- Pravilna tipografija

## Priloženi Material
[screenshot ili mockup]

use context7
```

### Primjer 3: Bug Fix

```
## Kontekst Projekta
- **Projekt:** pcz WordPress Redizajn
- **Trenutna faza:** Debugging

## Problem
Mega menu se ne prikazuje ispravno na mobile uređajima:
- Dropdown se otvara izvan viewporta
- Tekst je presitan

## Očekivano Ponašanje
- Menu se otvara fullscreen na mobile
- Font size čitljiv (min 16px)

## Koraci za Reprodukciju
1. Otvoriti stranicu na mobile (< 768px)
2. Kliknuti na "Ponuda"
3. Menu se otvara ali je cut-off

use context7
```

### Primjer 4: Brand-Aware Sekcija

```
## Kontekst Projekta
- **Projekt:** pcz WordPress Redizajn
- **Page Builder:** Oxygen Builder
- **Trenutna faza:** Dodavanje brand-specific sekcije

## Što Želim Napraviti
Kreirati sekciju koja se prikazuje SAMO za Sportski Klub:
- Sekcija "Naši Uspjesi" s medaljama i natjecanjima
- Prikazuje se samo kad je aktivan brand Sportski Klub
- Koristi narančastu temu (SPK boje)

## Brand Condition
U Oxygen Builderu za ovu sekciju:
- Settings → Conditions → Custom PHP
- Upisati: return pcz_is_sportski_klub();

## Sadržaj Sekcije
- Naslov iz ACF: spk_uspjesi_naslov
- Repeater s medaljama: spk_uspjesi_medalje
- CTA button za prijavu

## Napomene
- Koristiti CSS varijable za boje (var(--brand-primary))
- NE duplicirati sekciju - koristiti Condition
- Fallback na prazan prostor ako nije SPK

use context7
```

### Primjer 5: Nova Komponenta (Test Environment Workflow)

```
## Kontekst Projekta
- **Projekt:** pcz WordPress Redizajn
- **Page Builder:** Oxygen Builder
- **Trenutna faza:** Kreiranje nove sekcije
- **Brand:** Zajednička sekcija (oba branda) ILI specifični brand

## Što Želim Napraviti
Kreirati novu "[Ime Sekcije]" komponentu za homepage.

## Koraci za Implementaciju

### 1. Kreiraj PRODUKCIJSKI kod (ide na WordPress):
[sekcija]/
├── [sekcija].php                    # PHP komponenta
├── [sekcija].css                    # CSS stilovi  
├── acf_[sekcija]_fields.json        # ACF polja
└── oxygen-[sekcija]-code-block.php  # Oxygen wrapper

### 2. Kreiraj TEST environment (samo lokalno):
test/templates/[sekcija]/
├── test.php       # Wrapper - uključuje /[sekcija]/[sekcija].php
└── mock-data.php  # Mock ACF podaci

### 3. Registriraj u test/config.php

### 4. Kreiraj WordPress snippet:
snippets/pcz-[sekcija]-init.php  # Shortcode + enqueue

### 5. Ažuriraj dokumentaciju:
docs/DOKUMENTACIJA.md

## Development Workflow

1. **Razvijaj komponentu** u /[sekcija]/ folderu
2. **Testiraj** preko http://localhost:8000/[sekcija]
3. **Iteriraj stilove** s !important u test.php
4. **Primijeni finalne stilove** na /[sekcija]/[sekcija].css
5. **Upload na WordPress** u wp-content/uploads/pcz-[sekcija]/

## Napomene
- test.php UKLJUČUJE komponentu, ne duplicira kod
- mock-data.php simulira ACF - koristi \n za newlines, ne <br>
- ACF textarea: new_lines="" + nl2br(esc_html()) u PHP

use context7
```

---

## CHECKLIST PRIJE ZAVRŠETKA ZADATKA

- [ ] Kod je testiran lokalno
- [ ] Responsive design provjeren na svim breakpointima
- [ ] Dokumentacija ažurirana
- [ ] Nema console errors
- [ ] Accessibility provjeren (ARIA labels, keyboard navigation)
- [ ] Cross-browser testiranje (Chrome, Firefox, Safari)
- [ ] Performanse provjerene (PageSpeed Insights)

---

*Template verzija: 1.3 | Zadnje ažuriranje: 29.11.2025*

