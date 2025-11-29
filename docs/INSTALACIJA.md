# pcz Custom Header - Vodič za Instalaciju

## 📋 Pregled

Ovaj vodič objašnjava kako instalirati i konfigurirati pcz Custom Header s mega menu dropdown funkcionalnošću u Oxygen Builder okruženju.

---

## 🎯 Gdje Ide Koji Snippet?

### **Code Snippets Plugin** (WordPress Admin → Code Snippets)

U **Code Snippets plugin** ide:

1. ✅ **`snippets/pcz-header-init.php`** 
   - **Što radi:** Registrira shortcode `[pcz_header]`, učitava CSS/JS, registrira ACF Options Page
   - **Status:** Aktiviraj ovaj snippet
   - **Prioritet:** 10 (default)

2. ✅ **`snippets/acf-options-page.php`** 
   - **Što radi:** Registrira ACF Options Page "Site Settings"
   - **Status:** Već imaš aktivan (vidi se na slici)
   - **Napomena:** Ako već postoji, ne dodavaj duplo!

### **Oxygen Builder** (Oxygen → Templates → Header)

U **Oxygen Header template** ide:

- **Shortcode element** s `[pcz_header]` ili
- **PHP Code Block** s direktnim uključivanjem `mega-menu.php`

---

## 📁 Struktura Fajlova

```
pcz-redizajn/
├── header/
│   ├── mega-menu.php          ← PHP template za header
│   ├── mega-menu.css          ← CSS stilovi
│   └── mega-menu.js           ← JavaScript funkcionalnost
├── snippets/
│   ├── pcz-header-init.php    ← IDE U CODE SNIPPETS PLUGIN
│   └── acf-options-page.php   ← IDE U CODE SNIPPETS PLUGIN (već imaš)
└── docs/
    └── INSTALACIJA.md          ← Ovaj fajl
```

---

## 🚀 Korak-po-Korak Instalacija

### **Korak 1: Kopiraj Fajlove u Child Temu**

1. Kreiraj folder `pcz-header` u svojoj child temi:
   ```
   wp-content/themes/tvoja-child-tema/pcz-header/
   ```

2. Kopiraj ove 3 fajla u taj folder:
   - `mega-menu.php`
   - `mega-menu.css`
   - `mega-menu.js`

   **Ili** ako koristiš custom putanju, prilagodi `pcz_get_header_paths()` funkciju u `pcz-header-init.php`.

---

### **Korak 2: Dodaj Snippet u Code Snippets Plugin**

1. Idi u **WordPress Admin → Code Snippets → Add New**
2. **Snippet Name:** `pcz Header Init`
3. **Snippet Code:** Otvori `snippets/pcz-header-init.php` i kopiraj SAV kod
4. **Snippet Type:** PHP
5. **Run snippet:** Everywhere (ili Wherever)
6. **Priority:** 10
7. **Klikni "Save Changes and Activate"**

**Provjeri:**
- ✅ Snippet je aktivan (toggle switch je ON)
- ✅ Nema PHP grešaka

---

### **Korak 3: Importiraj ACF Field Group**

1. Idi u **WordPress Admin → ACF → Tools → Import**
2. Otvori `header/acf_mega_menu.json`
3. Kopiraj cijeli JSON sadržaj
4. Zalijepi u "Import JSON" polje
5. Klikni **"Import"**

**Provjeri:**
- ✅ Idi u **ACF → Field Groups** → Trebao bi se pojaviti "Mega Menu Data"
- ✅ Idi u **Site Settings** → Trebao bi se pojaviti "Ponuda – Blokovi" repeater

---

### **Korak 4: Popuni ACF Podatke**

1. Idi u **WordPress Admin → Site Settings**
2. Pronađi sekciju **"Ponuda – Blokovi"**
3. Klikni **"Add Row"** za svaki blok:

   **Blok 1: PONUDA ZA ODRASLE**
   - Naslov: `PONUDA ZA ODRASLE`
   - Podsekcije:
     - **Plesni tečajevi** (s 2 stavke: Tečaj Društvenih plesova, Tečaj Latin Jam)
     - **Plesne rekreacije** (s 2 stavke)
     - **Brzi tečajevi** (samo page link, bez stavki)
     - **Tečajevi za mladence** (samo page link)
     - **Full Dance Workout** (samo page link)
     - **Hip Hop za Odrasle** (samo page link)
     - **Individualna poduka** (samo page link)

   **Blok 2: PONUDA ZA DJECU**
   - Naslov: `PONUDA ZA DJECU`
   - Podsekcije:
     - **Plesna Igraonica** (samo page link)
     - **Hip Hop za Djecu** (s 2 stavke: Rekreacijske grupe, Natjecateljske grupe)
     - **Plesne Rekreacije** (s 1 stavkom)

4. Klikni **"Spremi postavke"**

---

### **Korak 5: Konfiguriraj WordPress Menu**

1. Idi u **WordPress Admin → Appearance → Menus**
2. Odaberi ili kreiraj menu **"Main Menu"** (ili "Primary")
3. Dodaj stavke:
   - Naslovna (Front Page)
   - Ponuda (Custom Link: `#`)
   - Cjenik (Page)
   - O nama (Page)
   - Kontakt (Page)
4. Dodijeli menu lokaciji **"Main Menu"** (ili "Primary")
5. **Spremi menu**

---

### **Korak 6: Implementiraj u Oxygen Builder**

#### **Opcija A: Shortcode Pristup (Preporučeno)**

1. Idi u **Oxygen → Templates → Add New Template**
2. Odaberi **"Header"** kao template type
3. Dodaj **Shortcode** element
4. U shortcode polje upiši: `[pcz_header]`
5. Spremi template
6. Dodijeli template na **"All Pages"** ili specifične stranice

#### **Opcija B: PHP Code Block Pristup**

1. Idi u **Oxygen → Templates → Add New Template**
2. Odaberi **"Header"** kao template type
3. Dodaj **Code Block** element
4. U **PHP** tab zalijepi:
   ```php
   <?php
   $header_path = get_stylesheet_directory() . '/pcz-header/mega-menu.php';
   if ( file_exists( $header_path ) ) {
       include $header_path;
   }
   ?>
   ```
5. Spremi template

---

### **Korak 7: Deaktiviraj Max Mega Menu Plugin**

1. Idi u **WordPress Admin → Plugins**
2. Pronađi **Max Mega Menu**
3. Klikni **Deactivate**

**Zašto?**
- Max Mega Menu konfliktira s Oxygen Builder-om
- Tvoj custom kod je potpun i ne treba plugin
- Imaš potpunu kontrolu nad dizajnom

---

## ✅ Provjera da Sve Radi

### **Frontend Provjera:**

1. ✅ Header se prikazuje s logom lijevo i navigacijom desno
2. ✅ Navigacija ima 5 stavki (Naslovna, Ponuda, Cjenik, O nama, Kontakt)
3. ✅ Hover preko "Ponuda" otvara mega menu dropdown
4. ✅ Mega menu ima 2 kolone (PONUDA ZA ODRASLE, PONUDA ZA DJECU)
5. ✅ Podsekcije i stavke se prikazuju ispravno
6. ✅ Hover efekti rade (roza boja, animacije)
7. ✅ Mobile menu toggle radi na malim ekranima

### **Backend Provjera:**

1. ✅ Code Snippets → "pcz Header Init" je aktivan
2. ✅ ACF → Field Groups → "Mega Menu Data" postoji
3. ✅ Site Settings → "Ponuda – Blokovi" je popunjen
4. ✅ Appearance → Menus → "Main Menu" je konfiguriran

---

## 🐛 Troubleshooting

### **Problem: Header se ne prikazuje**

**Rješenje:**
- Provjeri da li je snippet aktivan u Code Snippets
- Provjeri da li su fajlovi na pravom mjestu (`pcz-header/` folder)
- Provjeri browser console za JavaScript greške
- Provjeri da li Oxygen template je dodijeljen stranicama

### **Problem: Mega menu se ne otvara**

**Rješenje:**
- Provjeri da li je `mega-menu.js` učitan (View Source → traži `mega-menu.js`)
- Provjeri da li je Max Mega Menu deaktiviran
- Provjeri browser console za JavaScript greške
- Provjeri da li ACF podaci postoje (`get_field('ponuda_blokovi', 'option')`)

### **Problem: CSS stilovi ne rade**

**Rješenje:**
- Provjeri da li je `mega-menu.css` učitan
- Provjeri da li Oxygen ne override-a stilove
- Dodaj `!important` ako treba (privremeno za debug)
- Provjeri da li su CSS selektori točni

### **Problem: ACF podaci se ne prikazuju**

**Rješenje:**
- Provjeri da li je ACF Options Page registrirana
- Provjeri da li je field group importiran
- Provjeri da li su podaci spremljeni u Site Settings
- Koristi `var_dump($mega_menu_data)` za debug

### **Problem: Menu stavke se ne prikazuju**

**Rješenje:**
- Provjeri da li je WordPress menu kreiran i dodijeljen lokaciji
- Provjeri menu location slug (`main-menu` ili `primary`)
- Provjeri da li fallback kod radi (hardkodirane stavke)

---

## 📝 Napomene

1. **Cache:** Obriši cache nakon promjena (LiteSpeed Cache, browser cache)
2. **Oxygen Cache:** Oxygen ima svoj cache - obriši ga ako treba
3. **PHP Version:** Zahtijeva PHP 7.4+ (preporučeno 8.0+)
4. **ACF PRO:** Potreban je ACF PRO za repeater fields
5. **Child Tema:** Preporuča se korištenje child teme za customizacije

---

## 🔄 Ažuriranje

Kada ažuriraš fajlove:

1. Kopiraj nove verzije u `pcz-header/` folder
2. Obriši cache
3. Provjeri frontend

---

## 📞 Podrška

Ako imaš problema:

1. Provjeri browser console (F12)
2. Provjeri WordPress debug log
3. Provjeri da li su svi koraci izvršeni
4. Provjeri da li nema konflikata s drugim pluginima

---

**Datum kreiranja:** 27. studenog 2025  
**Verzija:** 1.0.0

