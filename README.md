# pcz Custom Header - Quick Start

## 🎯 Gdje Ide Što?

### ✅ **Code Snippets Plugin** (WordPress Admin → Code Snippets)

Dodaj ovaj snippet u **Code Snippets plugin**:

- 📄 **`snippets/pcz-header-init.php`** 
  - Kopiraj SAV kod iz ovog fajla
  - Dodaj kao novi snippet u Code Snippets
  - Aktiviraj ga
  - **Što radi:** Registrira `[pcz_header]` shortcode, učitava CSS/JS, registrira ACF Options Page

### ✅ **ACF Options Page** (Već Imaš!)

- 📄 **`snippets/acf-options-page.php`**
  - **Već imaš aktivan** (vidi se na slici - "ACF Options Page" je aktivan)
  - **Ne dodavaj duplo!** Ako već radi, ne treba ništa mijenjati

### ✅ **Oxygen Builder** (Oxygen → Templates → Header)

U Oxygen Header template dodaj:

- **Shortcode element** s `[pcz_header]`

ILI

- **PHP Code Block** s direktnim uključivanjem `mega-menu.php`

---

## 📁 Struktura Fajlova

```
pcz-redizajn/
├── header/
│   ├── mega-menu.php          ← PHP template (kopiraj u child-tema/pcz-header/)
│   ├── mega-menu.css          ← CSS stilovi (kopiraj u child-tema/pcz-header/)
│   └── mega-menu.js           ← JavaScript (kopiraj u child-tema/pcz-header/)
├── snippets/
│   ├── pcz-header-init.php   ← IDE U CODE SNIPPETS PLUGIN ⭐
│   └── acf-options-page.php   ← Već imaš aktivan ✅
└── docs/
    └── INSTALACIJA.md         ← Detaljni vodič
```

---

## 🚀 Brza Instalacija (5 Koraka)

1. **Kopiraj fajlove** u `wp-content/themes/tvoja-child-tema/pcz-header/`
   - `mega-menu.php`
   - `mega-menu.css`
   - `mega-menu.js`

2. **Dodaj snippet** `pcz-header-init.php` u Code Snippets plugin i aktiviraj

3. **Importiraj ACF** field group (`header/acf_mega_menu.json`)

4. **Popuni podatke** u Site Settings → Ponuda – Blokovi

5. **Dodaj u Oxygen** Header template: Shortcode `[pcz_header]`

---

## 📖 Detaljne Upute

Za detaljne korak-po-korak upute, pogledaj: **[docs/INSTALACIJA.md](docs/INSTALACIJA.md)**

---

## ⚠️ Važno

- **Deaktiviraj Max Mega Menu plugin** - konfliktira s Oxygen-om
- **Koristi child temu** za customizacije
- **Obriši cache** nakon promjena

---

## 🆕 Generički Dropdown Menu

Header sada podržava **generički pristup** za dropdown menue! Možete dodati dropdown na bilo koji menu item, ne samo "Ponuda".

Za detalje, pogledaj: **[docs/GENERICKI_DROPDOWN.md](docs/GENERICKI_DROPDOWN.md)**

---

**Verzija:** 2.0.0 | **Datum:** 27.11.2025

