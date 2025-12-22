# Oxygen PHP Code Blocks - Header & Footer

Svi PHP kodovi za Oxygen Code Block-ove na jednom mjestu.

---

## 📍 Header Code Block

Koristi u Oxygen **Header** template-u:

```php
<?php
/**
 * pcz Custom Header - Oxygen PHP Code Block
 * 
 * @version 2.0.1
 * @package pcz_Redizajn
 */

// Koristi WP_CONTENT_DIR za sigurnu putanju (Oxygen-safe)
$header_path = WP_CONTENT_DIR . '/uploads/pcz-header/mega-menu.php';
$header_url = content_url( '/uploads/pcz-header/' );

// Fallback na child temu
if ( ! file_exists( $header_path ) ) {
    $header_path = get_stylesheet_directory() . '/pcz-header/mega-menu.php';
    $header_url = get_stylesheet_directory_uri() . '/pcz-header/';
}

// Provjeri postoji li fajl
if ( ! file_exists( $header_path ) ) {
    if ( current_user_can( 'manage_options' ) ) {
        echo '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;margin:20px;">
            <strong>pcz Header Error:</strong> Fajl nije pronađen.<br>
            Tražena lokacija: ' . esc_html( $header_path ) . '
        </div>';
    }
    return;
}

// CSS
$css_file = dirname( $header_path ) . '/mega-menu.css';
$css_url = $header_url . 'mega-menu.css';
if ( file_exists( $css_file ) ) {
    echo '<link rel="stylesheet" href="' . esc_url( $css_url ) . '?v=' . filemtime( $css_file ) . '">';
}

// Uključi PHP template
include $header_path;

// JavaScript - defer
$js_file = dirname( $header_path ) . '/mega-menu.js';
$js_url = $header_url . 'mega-menu.js';
if ( file_exists( $js_file ) ) {
    echo '<script src="' . esc_url( $js_url ) . '?v=' . filemtime( $js_file ) . '" defer></script>';
}
?>
```

### Header Fajlovi na Serveru:
```
wp-content/uploads/pcz-header/
├── mega-menu.php
├── mega-menu.css
└── mega-menu.js
```

---

## 📍 Footer Code Block

Koristi u Oxygen **Footer** template-u:

```php
<?php
/**
 * pcz Custom Footer - Oxygen PHP Code Block
 * 
 * @version 1.0.0
 * @package pcz_Redizajn
 */

// Koristi WP_CONTENT_DIR za sigurnu putanju (Oxygen-safe)
$footer_path = WP_CONTENT_DIR . '/uploads/pcz-footer/footer.php';
$footer_url = content_url( '/uploads/pcz-footer/' );

// Fallback na child temu
if ( ! file_exists( $footer_path ) ) {
    $footer_path = get_stylesheet_directory() . '/pcz-footer/footer.php';
    $footer_url = get_stylesheet_directory_uri() . '/pcz-footer/';
}

// Provjeri postoji li fajl
if ( ! file_exists( $footer_path ) ) {
    if ( current_user_can( 'manage_options' ) ) {
        echo '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;margin:20px;">
            <strong>pcz Footer Error:</strong> Fajl nije pronađen.<br>
            Tražena lokacija: ' . esc_html( $footer_path ) . '
        </div>';
    }
    return;
}

// CSS
$css_file = dirname( $footer_path ) . '/footer.css';
$css_url = $footer_url . 'footer.css';
if ( file_exists( $css_file ) ) {
    echo '<link rel="stylesheet" href="' . esc_url( $css_url ) . '?v=' . filemtime( $css_file ) . '">';
}

// Uključi PHP template
include $footer_path;
?>
```

### Footer Fajlovi na Serveru:
```
wp-content/uploads/pcz-footer/
├── footer.php
└── footer.css
```

---

## 🗂️ Kompletna Struktura na Plesku

```
httpdocs/
└── defiant/
    └── wp-content/
        └── uploads/
            ├── pcz-header/
            │   ├── mega-menu.php
            │   ├── mega-menu.css
            │   └── mega-menu.js
            │
            └── pcz-footer/
                ├── footer.php
                └── footer.css
```

---

## ⚙️ Code Snippets (WordPress)

### Header Snippet
Fajl: `snippets/pcz-header-init.php`
- Registrira `[pcz_header]` shortcode
- Automatski učitava CSS/JS
- Admin notice ako fajlovi nedostaju

### Footer Snippet
Fajl: `snippets/pcz-footer-init.php`
- Registrira `[pcz_footer]` shortcode
- Automatski učitava CSS
- Registrira "Footer Menu" lokaciju
- Admin notice ako fajlovi nedostaju

### ACF Options Page
Fajl: `snippets/acf-options-page.php`
- Kreira "Site Settings" stranicu u adminu
- Koristi se za header i footer ACF polja

---

## 📋 Checklist za Deploy

### Header
- [ ] Upload `mega-menu.php`, `mega-menu.css`, `mega-menu.js` u `pcz-header/`
- [ ] Dodaj `pcz-header-init.php` u Code Snippets
- [ ] Import `header/acf_mega_menu.json` u ACF
- [ ] Dodaj PHP Code Block u Oxygen Header template

### Footer
- [ ] Upload `footer.php`, `footer.css` u `pcz-footer/`
- [ ] Dodaj `pcz-footer-init.php` u Code Snippets
- [ ] Import `footer/acf_footer_fields.json` u ACF
- [ ] Popuni Footer Settings u Site Settings
- [ ] Dodaj PHP Code Block u Oxygen Footer template

### Zajedničko
- [ ] Dodaj `acf-options-page.php` u Code Snippets (ako već nije)
- [ ] Provjeri da ACF PRO plugin je aktivan

---

## 🧪 Testiranje

Lokalno test okruženje:
```bash
cd pcz-redizajn/test
php -S localhost:8080 router.php
```

- Header test: `http://localhost:8080/?template=header`
- Footer test: `http://localhost:8080/?template=footer`

---

*Dokumentacija verzija: 1.0 | Zadnje ažuriranje: 29.11.2025*




