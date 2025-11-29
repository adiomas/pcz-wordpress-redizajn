# pcz Redizajn - Projektna Dokumentacija

## Pregled Projekta

**Naziv projekta:** Plesni Centar Zagreb by Nicolas - Redizajn WordPress Stranice  
**Datum početka:** Studeni 2025  
**Verzija dokumentacije:** 1.3

---

## 1. Glavni Navigacijski Menu

### 1.1 Struktura Menija

Menu se sastoji od 5 glavnih stavki:
1. **Naslovna** - Front Page (s Mega Menu oznakom)
2. **Ponuda** - Custom Link (#) - Sadrži mega menu dropdown
3. **Cjenik** - Page (Cjenik usluga)
4. **O nama** - Page (Stručni tim)
5. **Kontakt** - Page (Kontakt i lokacija)

### 1.2 Vizualni Dizajn

- **Logo:** Lijevo poravnat
- **Navigacija:** Desno poravnata, 5 buttona
- **Aktivna stavka:** Roza/magenta boja (#C71585 ili slična)
- **Header pozadina:** Tamno siva (#3d3d3d)
- **Hover efekt:** Roza obrub/pozadina

### 1.3 Mega Menu - Ponuda

Kada korisnik hovera preko "Ponuda", otvara se mega menu s dva glavna bloka:

#### PONUDA ZA ODRASLE
| Podsekcija | Stavke |
|------------|--------|
| Plesni tečajevi | Tečaj Društvenih plesova, Tečaj Latin Jam |
| Plesne rekreacije | Rekreacija Društvenih plesova, Rekreacija Latin Jam |
| Brzi tečajevi | (linkano direktno na page) |
| Tečajevi za mladence | (linkano direktno na page) |
| Full Dance Workout | (linkano direktno na page) |
| Hip Hop za Odrasle | (linkano direktno na page) |
| Individualna poduka | (linkano direktno na page) |

#### PONUDA ZA DJECU
| Podsekcija | Stavke |
|------------|--------|
| Plesna Igraonica | (linkano direktno na page) |
| Hip Hop za Djecu | Rekreacijske grupe, Natjecateljske grupe |
| Plesne Rekreacije | Rekreacijske grupe Društvenih plesova |

---

## 2. ACF Konfiguracija

### 2.1 Options Page

**Snippet naziv:** ACF Options Page  
**Lokacija:** WordPress Admin → Site Settings

```php
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(array(
        'page_title' => 'Site Settings',
        'menu_title' => 'Site Settings',
        'menu_slug'  => 'site-settings',
        'capability' => 'edit_posts',
        'redirect'   => false
    ));
}
```

### 2.2 ACF Field Group - Mega Menu Data

**Naziv grupe:** Mega Menu Data  
**Key:** group_mega_menu  
**Lokacija:** Options Page == site-settings

#### Struktura polja:

```
ponuda_blokovi (Repeater)
├── naslov (Text) - npr. "PONUDA ZA ODRASLE"
└── podsekcije (Repeater)
    ├── naslov_podsekcije (Text) - npr. "Plesni tečajevi"
    ├── page_link_podsekcije (Page Link) - opcionalano, ako nema stavki
    └── stavke (Repeater)
        ├── label (Text) - naziv linka
        └── url (Page Link) - link na stranicu
```

### 2.3 Lokacija ACF JSON Fajla

**Put:** `/header/acf_mega_menu.json`

---

## 3. WordPress Menu Postavke

### 3.1 Lokacija u Adminu

`Appearance → Menus`

### 3.2 Menu Postavke

- **Menu Name:** Main Menu - NEW
- **Menu Location:** Main Menu (Primary)
- **Max Mega Menu:** Enabled za "Main Menu"
  - Event: Hover Intent
  - Effect: Fade / Fast
  - Mobile Menu: Slide Down / Fast
  - Theme: Default

### 3.3 Pojedinačne stavke

| Redoslijed | Naziv | Tip | Parent | Napomena |
|------------|-------|-----|--------|----------|
| 1 | Naslovna | Front Page | - | Mega Menu oznaka |
| 2 | Ponuda | Custom Link (#) | - | Trigger za mega menu |
| 3 | Cjenik | Page | - | - |
| 4 | O nama | Page | - | - |
| 5 | Kontakt | Page | - | - |

---

## 4. Oxygen Builder Strategija

### 4.1 Preporučeni Pristup

Za implementaciju mega menija u Oxygen Builder, preporučuje se:

1. **Header Template** - Kreirati u `Oxygen → Headers`
2. **Reusable Part za Mega Menu** - Kreirati zasebnu komponentu
3. **PHP Code Block** - Za dinamičko dohvaćanje ACF podataka

### 4.2 Zašto Reusable Part?

- Lakše održavanje - jedna točka uređivanja
- Konzistentnost kroz cijeli site
- Čistija struktura koda
- Može se koristiti kao "Single" (ažuriranja se propagiraju)

### 4.3 Alternativni Pristup

Umjesto Max Mega Menu plugina, možete koristiti:
- Pure CSS/JS mega menu unutar Oxygen
- Custom dropdown s hover efektima
- ACF + PHP Code Block za dinamički sadržaj

---

## 5. Tehnička Specifikacija

### 5.1 Korištene Tehnologije

| Tehnologija | Verzija | Namjena |
|-------------|---------|---------|
| WordPress | 6.x | CMS |
| Oxygen Builder | 4.x | Page Builder |
| ACF PRO | 6.x | Custom Fields |
| Max Mega Menu | - | Menu Enhancement (opcionalno) |

### 5.2 Putanje Fajlova

```
pcz-redizajn/
├── docs/
│   ├── DOKUMENTACIJA.md            # Ovaj fajl
│   ├── PROMPT_TEMPLATE.md          # Template za buduće promjene
│   └── FOOTER_IMPLEMENTACIJA.md    # Footer dokumentacija
├── header/
│   ├── acf_mega_menu.json          # ACF export
│   ├── mega-menu.php               # PHP kod za mega menu
│   ├── mega-menu.css               # CSS stilovi
│   ├── mega-menu.js                # JavaScript
│   └── oxygen-header-code-block.php
├── footer/
│   ├── acf_footer_fields.json      # ACF export
│   ├── footer.php                  # PHP kod za footer
│   ├── footer.css                  # CSS stilovi
│   └── oxygen-footer-code-block.php
├── poznati/
│   ├── acf_poznati_fields.json     # ACF export
│   ├── poznati.php                 # PHP kod za sekciju
│   ├── poznati.css                 # CSS stilovi
│   ├── poznati.js                  # Mobile slider JS
│   └── oxygen-poznati-code-block.php
├── hero/
│   ├── acf_hero_fields.json        # ACF export
│   ├── hero.php                    # PHP kod za hero sekciju
│   ├── hero.css                    # CSS stilovi
│   └── oxygen-hero-code-block.php
├── snippets/
│   ├── acf-options-page.php        # ACF Options Page
│   ├── pcz-header-init.php         # Header inicijalizacija
│   ├── pcz-footer-init.php         # Footer inicijalizacija
│   ├── pcz-poznati-init.php        # Poznati inicijalizacija
│   └── pcz-hero-init.php           # Hero inicijalizacija
└── test/
    ├── index.php                   # Test environment
    └── templates/                  # Test template-i
        ├── header/
        ├── footer/
        ├── poznati/
        └── hero/
```

---

## 6. Poznati o PCZ-u Sekcija

### 6.1 Opis

Sekcija s testimonialima poznatih osoba koje su plesale u Plesnom Centru Zagreb.

**Karakteristike:**
- Roza gradient pozadina koja ide u prozirno
- Kružne slike s bijelim borderom
- Italic citati + uppercase imena
- Mobile: Touch-friendly swipe slider
- Desktop: 4-kolonski grid

### 6.2 ACF Polja

**Lokacija:** Options Page → Site Settings → Poznati o PCZ-u

| Polje | Tip | Opis |
|-------|-----|------|
| poznati_naslov | Text | Naslov sekcije (default: "POZNATI O PCZ-u") |
| poznati_testimonijali | Repeater | Lista testimonijala |
| ↳ ime | Text | Ime i prezime osobe |
| ↳ citat | Textarea | Kratki citat/testimonijal |
| ↳ slika | Image | Portretna fotografija (kvadratna) |

### 6.3 Korištenje

**Opcija 1: Shortcode**
```php
[pcz_poznati]
```

**Opcija 2: Oxygen Code Block**
```php
// Kopiraj sadržaj iz: poznati/oxygen-poznati-code-block.php
```

### 6.4 CSS Varijable

Koristi iste varijable kao header/footer za konzistentnost:
- `--pcz-primary: #C71585`
- `--pcz-primary-hover: #a01269`
- `--pcz-dark: #3d3d3d`
- `--pcz-light: #ffffff`

---

## 7. Hero Sekcija

### 7.1 Opis

Fullwidth hero sekcija za naslovnicu s pozadinskom slikom, naslovom, CTA gumbom i intro sekcijom.

**Karakteristike:**
- Fullwidth pozadinska slika (1920x1080+)
- Gradient overlay za čitljivost teksta
- Handwritten tagline (Dancing Script font)
- Bold naslov i podnaslov
- CTA gumb s hover efektom
- Intro sekcija ispod s tekstom i social ikonama
- Responzivni dizajn za sve uređaje

### 7.2 ACF Polja

**Lokacija:** Options Page → Site Settings → Hero Sekcija

| Polje | Tip | Opis |
|-------|-----|------|
| hero_pozadinska_slika | Image | Fullwidth pozadinska slika |
| hero_tagline | Text | Mala poruka iznad naslova (npr. "plesom do zdravlja!") |
| hero_naslov | Textarea | Glavni naslov hero sekcije |
| hero_podnaslov | Textarea | Tekst ispod naslova |
| hero_cta_tekst | Text | Tekst CTA gumba |
| hero_cta_link | URL | Link za CTA gumb |
| hero_intro_naslov | Text | Naslov intro sekcije |
| hero_intro_tekst | Textarea | Tekst intro sekcije |
| hero_intro_tekst_2 | Textarea | Dodatni tekst (opcionalno) |
| hero_socijalne_mreze | Repeater | Lista social ikona |
| ↳ ikona | Select | Instagram/Facebook/YouTube/TikTok |
| ↳ url | URL | Link na profil |

### 7.3 Korištenje

**Opcija 1: Shortcode**
```php
[pcz_hero]
```

**Opcija 2: Oxygen Code Block**
```php
// Kopiraj sadržaj iz: hero/oxygen-hero-code-block.php
```

### 7.4 CSS Varijable

Koristi iste varijable kao ostale komponente:
- `--pcz-primary: #C71585`
- `--pcz-primary-hover: #a01269`
- `--pcz-dark: #3d3d3d`
- `--pcz-light: #ffffff`

### 7.5 Responzivni Breakpoints

| Uređaj | Min-height | Padding-left |
|--------|------------|--------------|
| Desktop (>1024px) | 90vh | 400px |
| Tablet (768-1024px) | 80vh | 200px |
| Mobile (<768px) | 70vh | 20px |

---

## 8. Changelog

| Datum | Verzija | Promjene |
|-------|---------|----------|
| 29.11.2025 | 1.3 | Dodana Hero sekcija s fullwidth slikom i intro sekcijom |
| 29.11.2025 | 1.2 | Dodana "Poznati o PCZ-u" sekcija s mobile sliderom |
| 28.11.2025 | 1.1 | Dodan Footer s kontakt sekcijom |
| 27.11.2025 | 1.0 | Inicijalna dokumentacija, ACF konfiguracija, Menu struktura |

---

## 8. Napomene

1. **ACF JSON Sync:** Preporučuje se korištenje ACF Local JSON za verzioniranje field grupa
2. **Backup:** Uvijek napraviti backup prije većih promjena
3. **Staging:** Testirati promjene na staging okruženju prije produkcije
4. **Cache:** Obrisati cache nakon promjena na menu-u

---

*Dokumentacija generirana: 29. studenog 2025*
