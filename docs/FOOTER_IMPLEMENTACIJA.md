# pcz Footer - Implementacija

## Pregled

Footer komponenta za Plesni Centar Zagreb koja uključuje:
- Kontakt sekciju s radnim vremenom i Google mapom
- Glavni footer s logom, navigacijom i social ikonama
- Donju traku s copyright tekstom i pravnim linkovima

---

## Struktura Fajlova

```
footer/
├── footer.php              # Glavni PHP template
├── footer.css              # Stilovi
└── acf_footer_fields.json  # ACF field group za import

snippets/
└── pcz-footer-init.php     # Shortcode + asset loader
```

---

## Instalacija

### Korak 1: Upload Fajlova na Server

Kopiraj `footer.php` i `footer.css` u:
```
wp-content/uploads/pcz-footer/
```

Na Plesk-u:
```
httpdocs/defiant/wp-content/uploads/pcz-footer/
```

### Korak 2: Dodaj Snippet

1. Idi na **Code Snippets** plugin u WordPress adminu
2. Kreiraj novi snippet
3. Kopiraj sadržaj iz `snippets/pcz-footer-init.php`
4. Aktiviraj snippet

### Korak 3: Import ACF Polja

1. Idi na **Custom Fields > Tools**
2. Import `footer/acf_footer_fields.json`
3. Ili ručno kreiraj polja prema JSON strukturu

### Korak 4: Oxygen Code Block

U Oxygen Footer template-u dodaj **Code Block** s PHP kodom:

```php
<?php
/**
 * pcz Custom Footer - Oxygen PHP Code Block
 * 
 * @version 1.0.0
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

---

## ACF Polja

Footer koristi sljedeća ACF polja (u Site Settings):

### Radno Vrijeme (`footer_radno_vrijeme`)
| Polje | Tip | Opis |
|-------|-----|------|
| `pon_pet` | Text | Radno vrijeme pon-pet |
| `sub` | Text | Radno vrijeme subota |
| `ned` | Text | Radno vrijeme nedjelja |

### Kontakt (`footer_kontakt`)
| Polje | Tip | Opis |
|-------|-----|------|
| `telefon` | Text | Broj telefona |
| `email` | Email | Email adresa |
| `adresa` | Text | Fizička adresa |

### Google Maps
| Polje | Tip | Opis |
|-------|-----|------|
| `footer_google_maps_embed` | URL | Embed URL za Google mapu |

### Social Linkovi (`footer_social_links`) - Repeater
| Polje | Tip | Opis |
|-------|-----|------|
| `platforma` | Select | instagram/facebook/youtube/twitter/linkedin |
| `url` | URL | Link na profil |

### Navigacija (`footer_nav_links`) - Repeater
| Polje | Tip | Opis |
|-------|-----|------|
| `label` | Text | Tekst linka |
| `url` | URL | URL linka |

### Copyright & Legal
| Polje | Tip | Opis |
|-------|-----|------|
| `footer_copyright` | Text | Copyright tekst ({year} = trenutna godina) |
| `footer_legal_links` | Repeater | Pravni linkovi (label + url) |

---

## Alternativa: WordPress Menu

Umjesto ACF repeatera za navigaciju, možeš koristiti WordPress Menu:

1. Idi na **Appearance > Menus**
2. Kreiraj menu i dodijeli ga lokaciji "Footer Menu"
3. Footer će automatski koristiti taj menu

---

## Responsive Breakpoints

- **Desktop:** > 1024px
- **Tablet:** 768px - 1024px
- **Mobile:** < 768px
- **Small Mobile:** < 480px

---

## CSS Varijable

Footer koristi iste CSS varijable kao header:

```css
:root {
    --pcz-primary: #C71585;
    --pcz-primary-hover: #a01269;
    --pcz-dark: #3d3d3d;
    --pcz-darker: #333333;
    --pcz-gray: #666666;
    --pcz-light: #ffffff;
    --pcz-footer-bg: var(--pcz-dark);
    --pcz-contact-bg: #f5f5f5;
}
```

---

## Testiranje

Test okruženje: `http://localhost:8080/?template=footer`

Dostupni scenariji:
- `default` - Puni footer
- `no_social` - Bez social ikona
- `minimal` - Minimalni footer
- `no_logo` - Tekstualni logo
- `footer_only` - Bez kontakt sekcije

---

## Changelog

### 1.0.0 (2025-11-29)
- Inicijalna verzija
- Kontakt sekcija s Google mapom
- Footer s logom, navigacijom, social ikonama
- Donja traka s copyright i pravnim linkovima
- Responsive dizajn
- ACF integracija

---

*Dokumentacija verzija: 1.0 | Zadnje ažuriranje: 29.11.2025*

