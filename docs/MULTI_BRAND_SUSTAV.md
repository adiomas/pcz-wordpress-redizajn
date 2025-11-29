# 🎨 Multi-Brand Sustav - Implementacijska Dokumentacija

## Pregled

Multi-Brand sustav omogućuje dinamičko prebacivanje između dva branda:
- **Plesna Škola** (default) - Magenta tema (`#CB007C`)
- **Sportski Klub** - Narančasta tema (`#FF6B00`)

## Arhitektura

```
brand/
├── brand.php                  # Core PHP logika (detection, data loading)
├── brand-switcher.php         # UI komponenta za brand switching
├── brand.css                  # CSS varijable i stilovi
├── brand.js                   # JavaScript za client-side switching
├── acf_brand_fields.json      # ACF field group definicije
├── brand-aware-header.php     # Wrapper za header komponentu
├── brand-aware-hero.php       # Wrapper za hero komponentu
└── brand-aware-footer.php     # Wrapper za footer komponentu
```

## URL Strategija

Brand se određuje putem **query parametra** `?brand=`:

```
https://pcz.hr/                       → Plesna Škola (default)
https://pcz.hr/?brand=plesna-skola    → Plesna Škola (explicit)
https://pcz.hr/?brand=sportski-klub   → Sportski Klub
```

### Prednosti ovog pristupa:
- Jednostavna implementacija
- Čuva SEO juice na jednom URL-u
- JavaScript enhancement za smooth transitions
- Kompatibilno s cachingom

## Instalacija

### 1. Upload brand fajlova na server

Uploadaj cijeli `brand/` folder na:
```
wp-content/uploads/pcz-brand/
```

**Struktura nakon uploada:**
```
wp-content/uploads/
├── pcz-brand/           ← NOVO (Brand sustav)
│   ├── brand.php
│   ├── brand-switcher.php
│   ├── brand.css
│   ├── brand.js
│   ├── brand-aware-header.php
│   ├── brand-aware-hero.php
│   └── brand-aware-footer.php
├── pcz-header/          ← Postojeći
│   └── mega-menu.php
├── pcz-hero/            ← Postojeći
│   └── hero.php
└── pcz-footer/          ← Postojeći
    └── footer.php
```

### 2. Dodati snippet u Code Snippets plugin

1. Idi na **Snippets → Add New**
2. Naziv: `pcz Multi-Brand System`
3. Zalijepi sadržaj iz `snippets/pcz-brand-init.php` (bez `<?php` na početku)
4. Aktiviraj snippet

### 3. Ažuriraj Oxygen Code Blockove

**Zamijeni sadržaj svakog Code Blocka:**

| Komponenta | Novi fajl za kopirati |
|------------|----------------------|
| Header | `header/oxygen-header-code-block.php` |
| Hero | `hero/oxygen-hero-code-block.php` |
| Footer | `footer/oxygen-footer-code-block.php` |

⚠️ **Važno:** Ovi Code Blockovi automatski:
- Prvo traže brand-aware verziju u `pcz-brand/`
- Ako ne postoji, koriste originalnu verziju
- Učitavaju brand.css i brand.js

### 4. Import ACF Field Group

1. Idi na **ACF → Tools → Import**
2. Upload: `brand/acf_brand_fields.json`
3. Klikni Import

### 5. Konfiguriraj brandove u ACF

Idi na **Site Settings → Brand Konfiguracija**:

| Postavka | Vrijednost |
|----------|------------|
| Plesna Škola Naziv | "Plesna škola" |
| Plesna Škola Logo | Upload logo |
| Plesna Škola Primarna Boja | #CB007C |
| Sportski Klub Naziv | "Sportski klub" |
| Sportski Klub Logo | Upload logo |
| Sportski Klub Primarna Boja | #FF6B00 |
| Brand-Aware Header | ☑️ (ako želiš da header mijenja logo) |

## Korištenje

### PHP - Dohvat trenutnog branda

```php
// Dohvati ID branda
$brand_id = pcz_get_current_brand_id();
// Returns: 'plesna-skola' ili 'sportski-klub'

// Dohvati sve brand podatke
$brand = pcz_get_brand_data($brand_id);
// Returns: array('id' => '...', 'name' => '...', 'primary_color' => '...', ...)

// Dohvati specifičnu vrijednost
$logo = pcz_get_brand_setting('logo');
$color = pcz_get_brand_setting('primary_color');
$name = pcz_get_brand_setting('name');
```

### CSS - Brand Varijable

Brand sustav automatski postavlja CSS varijable:

```css
/* Automatski se mijenja po brandu */
body[data-brand="plesna-skola"] {
    --brand-primary: #CB007C;
    --brand-primary-hover: #a30064;
    --brand-secondary: #6a0a46;
}

body[data-brand="sportski-klub"] {
    --brand-primary: #FF6B00;
    --brand-primary-hover: #e05f00;
    --brand-secondary: #cc5500;
}

/* Korištenje u CSS-u */
.my-element {
    background-color: var(--brand-primary);
    color: var(--brand-text);
}
```

### JavaScript - Brand Detection

```javascript
// Dohvati trenutni brand
const brand = pcz_Brand.getCurrentBrand();

// Promijeni brand programatski
pcz_Brand.setBrand('sportski-klub');

// Event listener za promjenu branda
document.addEventListener('pcz:brand-changed', (e) => {
    console.log('Novi brand:', e.detail.brand);
});
```

### Brand Switcher UI

Koristi shortcode ili PHP:

```php
// PHP
<?php include get_template_directory() . '/brand/brand-switcher.php'; ?>
<?php pcz_render_brand_switcher(['style' => 'tabs', 'size' => 'normal']); ?>

// Shortcode (ako je registriran)
[pcz_brand_switcher style="tabs" size="normal"]
```

**Stilovi:**
- `tabs` - Tabovi sa pozadinom
- `minimal` - Samo tekst linkovi
- `buttons` - Odvojeni gumbi

**Veličine:**
- `small` - Kompaktna verzija
- `normal` - Standardna veličina

## Brand-Aware Komponente

### Header

Za brand-aware header, koristi wrapper:

```php
// Umjesto:
include 'header/mega-menu.php';

// Koristi:
include 'brand/brand-aware-header.php';
```

Opcije u ACF:
- `brand_aware_header` (true/false) - Treba li header mijenjati logo po brandu

### Hero

```php
// Umjesto:
include 'hero/hero.php';

// Koristi:
include 'brand/brand-aware-hero.php';
```

Hero automatski dohvaća brand-specifične podatke ako su konfigurirani u ACF:
- `spk_hero_pozadinska_slika`
- `spk_hero_tagline`
- `spk_hero_naslov`
- `spk_hero_podnaslov`
- `spk_hero_cta_tekst`
- `spk_hero_cta_link`

### Footer

```php
// Umjesto:
include 'footer/footer.php';

// Koristi:
include 'brand/brand-aware-footer.php';
```

Footer automatski koristi brand-specifični logo.

## Filteri i Hookovi

### Filteri

```php
// Override brand podataka
add_filter('pcz_brand_data', function($data, $brand_id) {
    // Modificiraj $data
    return $data;
}, 10, 2);

// Override hero podataka za brand
add_filter('pcz_hero_data', function($hero_data) {
    // Modificiraj hero sadržaj
    return $hero_data;
});

// Override footer logo URL-a
add_filter('pcz_footer_logo_url', function($logo_url) {
    // Vrati custom logo
    return $logo_url;
});

// Override header klasa
add_filter('pcz_header_classes', function($classes) {
    return $classes . ' custom-class';
});
```

### Action Hookovi

```php
// Nakon hero intro sekcije (za brand switcher)
add_action('pcz_after_hero_intro', function() {
    // Renderaj custom content
});

// Nakon header navigacije
add_action('pcz_after_header_nav', function() {
    // Renderaj custom content
});
```

## ACF Struktura

### Brand Konfiguracija (Site Settings)

| Field Name | Tip | Opis |
|------------|-----|------|
| `ps_name` | Text | Naziv Plesne škole |
| `ps_logo` | Image | Logo Plesne škole |
| `ps_primary_color` | Color Picker | Primarna boja |
| `ps_secondary_color` | Color Picker | Sekundarna boja |
| `spk_name` | Text | Naziv Sportskog kluba |
| `spk_logo` | Image | Logo Sportskog kluba |
| `spk_primary_color` | Color Picker | Primarna boja |
| `spk_secondary_color` | Color Picker | Sekundarna boja |
| `brand_aware_header` | True/False | Header mijenja logo |
| `brand_switcher_position` | Select | Pozicija switchera |

### SPK Hero Polja

| Field Name | Tip | Opis |
|------------|-----|------|
| `spk_hero_pozadinska_slika` | Image | Hero pozadina za SPK |
| `spk_hero_tagline` | Text | Tagline za SPK |
| `spk_hero_naslov` | Textarea | Naslov za SPK |
| `spk_hero_podnaslov` | Textarea | Podnaslov za SPK |
| `spk_hero_cta_tekst` | Text | CTA tekst za SPK |
| `spk_hero_cta_link` | URL | CTA link za SPK |

## SEO Razmatranja

### Canonical Tags

```php
// U wp_head, automatski dodaj canonical bez query parametra
add_action('wp_head', function() {
    $canonical = home_url(add_query_arg([], $GLOBALS['wp']->request));
    echo '<link rel="canonical" href="' . esc_url($canonical) . '" />';
}, 1);
```

### Hreflang (opcionalno)

Ako tretirate brandove kao jezične varijante:

```html
<link rel="alternate" hreflang="x-plesna-skola" href="https://pcz.hr/?brand=plesna-skola" />
<link rel="alternate" hreflang="x-sportski-klub" href="https://pcz.hr/?brand=sportski-klub" />
```

## Testiranje

### Test Environment

Brand test scenariji su dostupni u test environmentu:

```
http://localhost:8080/brand/
http://localhost:8080/brand/?scenario=plesna_skola
http://localhost:8080/brand/?scenario=sportski_klub
```

### Testiranje u pregledniku

1. Otvori stranicu
2. Dodaj `?brand=sportski-klub` u URL
3. Provjeri promjenu boja i sadržaja
4. Klikni na brand switcher
5. Provjeri smooth transition

## Migracija na produkciju

### Koraci

1. **Backup** - Napravite backup baze i datoteka
2. **Upload datoteka** - Uploadajte `brand/` folder
3. **Aktivirajte snippet** - Dodajte init kod u functions.php
4. **Import ACF** - Importirajte field group JSON
5. **Konfigurirajte** - Popunite brand podatke u ACF
6. **Testirajte** - Provjerite sve stranice s oba branda

### Oxygen Builder

Ako koristite Oxygen, dodajte Code Block:

```php
<?php
// Brand-aware Hero
include ABSPATH . 'wp-content/themes/your-theme/brand/brand-aware-hero.php';
?>
```

### WP Staging

Prilikom migracije s WP Staging:
1. Exportirajte ACF field groups kao JSON
2. Kopirajte `brand/` folder
3. Testirajte na staging prije produkcije

## Troubleshooting

### Brand se ne mijenja

1. Provjerite je li `brand.js` učitan
2. Provjerite Console za JS greške
3. Provjerite ima li `body` tag `data-brand` atribut

### Logo se ne mijenja

1. Provjerite je li `brand_aware_header` uključen u ACF
2. Provjerite jesu li logo uploadani za oba branda
3. Provjerite filter prioritete

### Boje nisu ispravne

1. Provjerite CSS varijable u DevTools
2. Provjerite ima li hardkodiranih boja u CSS-u
3. Zamijenite hardkodirane boje s `var(--brand-primary)`

## Changelog

### v4.0.0
- Inicijalna implementacija Multi-Brand sustava
- Brand detection putem query parametra
- CSS varijable za dinamičke boje
- Brand-aware wrappers za header, hero, footer
- JavaScript brand switcher s smooth transitions
- ACF field group za brand konfiguraciju
- Kompletna dokumentacija

