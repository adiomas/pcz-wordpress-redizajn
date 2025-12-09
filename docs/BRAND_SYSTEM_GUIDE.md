# 🎨 pcz Multi-Brand System - Vodič

## Sadržaj
1. [Struktura ACF polja](#struktura-acf-polja)
2. [Gdje se što nalazi u WP Adminu](#gdje-se-sto-nalazi)
3. [Oxygen - Kako ograničiti sekcije po brandu](#oxygen-conditions)
4. [Brand Switcher postavke](#brand-switcher)
5. [Socijalne mreže po brandu](#socijalne-mreze)

---

## 1. Struktura ACF polja {#struktura-acf-polja}

Sve brand postavke su u **Site Settings** → **Multi-Brand Postavke**.

### Tabovi u Site Settings:

```
┌─────────────────────────────────────────────────────────────┐
│  ⚙️ Opće  │  🔀 Switcher  │  💃 Plesna Škola  │  🏆 Sportski Klub  │
└─────────────────────────────────────────────────────────────┘
```

### ⚙️ Opće
- **Defaultni Brand** - koji brand se prikazuje defaultno
- **Header mijenja boje** - da li header mijenja boje po brandu

### 🔀 Switcher
- Stil, veličina, poravnanje
- Labele za svaki brand
- Tekst iznad switchera

### 💃 Plesna Škola
- Logo
- Primarna boja
- **Socijalne mreže** (zasebne za Plesnu Školu!)

### 🏆 Sportski Klub
- Logo
- Primarna boja
- **Socijalne mreže** (zasebne za Sportski Klub!)

---

## 2. Gdje se što nalazi u WP Adminu {#gdje-se-sto-nalazi}

```
WordPress Admin
│
├── 📄 Stranice → Homepage
│   └── (Oxygen Builder - ne sadrži brand postavke)
│
├── ⚙️ Site Settings (ACF Options)
│   │
│   ├── Hero Postavke          ← Originalne hero postavke (zajedničke)
│   ├── Footer Postavke        ← Footer podaci (zajedničke)
│   ├── Poznati o PCZ-u        ← Testimonials
│   │
│   └── Multi-Brand Postavke   ← SAMO BRAND STVARI
│       ├── Opće postavke
│       ├── Switcher postavke
│       ├── Plesna Škola (logo, boja, socijalne)
│       └── Sportski Klub (logo, boja, socijalne)
│
└── 🔧 Code Snippets
    └── pcz-brand-init         ← Inicijalizacija brand sustava
```

### ⚠️ VAŽNO - Nema duplikata!

Brand Settings **NE** duplicira Hero polja. Umjesto toga:
- Hero sadržaj (naslov, podnaslov, slika) ostaje u **Hero Postavkama** (originalne)
- Brand Settings sadrži samo: logo, boje, socijalne mreže

---

## 3. Oxygen - Kako ograničiti sekcije po brandu {#oxygen-conditions}

### Korak 1: Otvori Oxygen Builder

```
WordPress Admin → Pages → Homepage → Edit with Oxygen
```

### Korak 2: Odaberi element koji želiš ograničiti

Klikni na sekciju/div/element koji želiš prikazati samo za određeni brand.

### Korak 3: Dodaj PHP Condition

U desnom panelu:

```
┌─────────────────────────────────────┐
│ Advanced                            │
├─────────────────────────────────────┤
│ ▼ Conditions                        │
│   ┌─────────────────────────────┐   │
│   │ + Add Condition             │   │
│   └─────────────────────────────┘   │
│                                     │
│   Condition Type: [PHP Function]   │ ← Odaberi ovo!
│                                     │
│   Function: pcz_is_plesna_skola    │ ← Upiši ime funkcije
│                                     │
│   ☑ Show/Hide when TRUE             │
└─────────────────────────────────────┘
```

### Dostupne funkcije za Conditions:

| Funkcija | Što radi |
|----------|----------|
| `pcz_is_plesna_skola` | Vraća TRUE ako je aktivan brand "Plesna Škola" |
| `pcz_is_sportski_klub` | Vraća TRUE ako je aktivan brand "Sportski Klub" |
| `pcz_is_brand` | Generička funkcija (zahtijeva argument) |

### Primjer u praksi:

**Sekcija "Naši treneri" samo za Sportski Klub:**

1. Odaberi sekciju "Naši treneri"
2. Advanced → Conditions → Add Condition
3. Type: PHP Function Return Value
4. Function: `pcz_is_sportski_klub`
5. Value: `true`
6. Action: Show

**Sekcija "Plesni programi" samo za Plesnu Školu:**

1. Odaberi sekciju "Plesni programi"
2. Advanced → Conditions → Add Condition
3. Type: PHP Function Return Value
4. Function: `pcz_is_plesna_skola`
5. Value: `true`
6. Action: Show

---

## 4. Brand Switcher {#brand-switcher}

### Kako dodati switcher na stranicu:

**Opcija A: Oxygen Code Block**

1. U Oxygen dodaj **Code Block** element
2. Kopiraj sadržaj iz: `brand/oxygen-brand-switcher-code-block.php`

**Opcija B: Shortcode**

U bilo kojem Oxygen tekst elementu ili Code Block-u:

```
[pcz_brand_switcher]
```

### Konfiguracija u Site Settings:

```
Site Settings → Multi-Brand Postavke → 🔀 Switcher tab
```

Sve postavke se automatski primjenjuju - ne treba ništa hardcodirat.

---

## 5. Socijalne mreže po brandu {#socijalne-mreze}

Svaki brand ima **svoje** socijalne mreže:

### U Site Settings:

**💃 Plesna Škola tab:**
```
Socijalne mreže:
┌──────────────┬─────────────────────────────────────┐
│ Platforma    │ URL                                 │
├──────────────┼─────────────────────────────────────┤
│ Instagram    │ https://instagram.com/plesna_skola  │
│ Facebook     │ https://facebook.com/plesnaskola    │
│ TikTok       │ https://tiktok.com/@plesna_skola    │
└──────────────┴─────────────────────────────────────┘
```

**🏆 Sportski Klub tab:**
```
Socijalne mreže:
┌──────────────┬─────────────────────────────────────┐
│ Platforma    │ URL                                 │
├──────────────┼─────────────────────────────────────┤
│ Instagram    │ https://instagram.com/spk_zagreb    │
│ Facebook     │ https://facebook.com/spkzagreb      │
│ YouTube      │ https://youtube.com/spkzagreb       │
└──────────────┴─────────────────────────────────────┘
```

### Dohvaćanje u kodu:

```php
// Automatski vraća socijalne za aktivni brand
$social = pcz_get_brand_social_links();

foreach ( $social as $link ) {
    echo '<a href="' . $link['url'] . '">' . $link['platforma'] . '</a>';
}
```

---

## 📋 Checklist za postavljanje

- [ ] Importaj `acf_brand_fields.json` u ACF
- [ ] Uploadaj `pcz-brand/` folder u `wp-content/uploads/`
- [ ] Aktiviraj snippet `pcz-brand-init`
- [ ] U Site Settings popuni Multi-Brand Postavke
- [ ] U Oxygen dodaj Code Block za switcher
- [ ] Postavi Conditions na sekcije specifične za brand

---

## 🆘 Troubleshooting

### Switcher nije centriran
- Provjeri da wrapper ima `display: flex`
- U Site Settings provjeri postavku "Poravnanje"

### Tekst se ne vidi (bijeli na bijelom)
- CSS je ažuriran da koristi tamnosive boje (#666)
- Ako i dalje ne radi, očisti cache (browser + WP cache)

### Conditions ne rade
- Provjeri da je snippet `pcz-brand-init` aktivan
- Provjeri da funkcija postoji: `pcz_is_plesna_skola()`
- U Oxygen koristi "PHP Function Return Value", ne "PHP"

---

## 📁 Struktura fajlova

```
wp-content/uploads/pcz-brand/
├── brand.php                    # Core logika
├── brand.css                    # Stilovi
├── brand.js                     # JavaScript
├── brand-switcher.php           # Switcher komponenta
├── brand-aware-header.php       # Header wrapper
├── brand-aware-hero.php         # Hero wrapper
├── brand-aware-footer.php       # Footer wrapper
└── oxygen-brand-switcher-code-block.php  # Za kopiranje u Oxygen
```

---

*Dokumentacija generirana: Studeni 2025*


