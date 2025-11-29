# Template za Novu Komponentu

## 📋 Upute za korištenje

### 1. Kopiraj template

```bash
cp -r test/templates/_template test/templates/ime-komponente
```

### 2. Konfiguriraj `test.php`

Otvori `test.php` i izmijeni varijable na vrhu:

```php
$component_name = 'Footer';                              // Naziv za prikaz
$component_path = pcz_PROJECT_ROOT . '/footer/footer.php'; // Putanja do PHP
$component_css  = '/footer/footer.css';                   // CSS (opcionalno)
$component_js   = '/footer/footer.js';                    // JS (opcionalno)
```

### 3. Definiraj mock podatke


Otvori `mock-data.php` i dodaj podatke koje tvoja komponenta očekuje:

```php
'acf_fields' => [
    'copyright_text' => '© 2024 Plesni Centar Zagreb',
    'social_links' => [
        ['icon' => 'facebook', 'url' => 'https://facebook.com/...'],
        ['icon' => 'instagram', 'url' => 'https://instagram.com/...'],
    ],
],
```

### 4. Kreiraj scenarije

Dodaj različite scenarije za testiranje edge case-ova:

```php
'scenarios' => [
    'default' => [],
    'no_social' => [
        'acf_fields' => [
            'social_links' => [],
        ],
    ],
    'long_copyright' => [
        'acf_fields' => [
            'copyright_text' => 'Vrlo dugačak copyright tekst koji testira prelom...',
        ],
    ],
],
```

### 5. Testiraj

```bash
php -S localhost:8080 -t test/
```

Otvori: `http://localhost:8080/?template=ime-komponente`

## 📁 Struktura fajlova

```
ime-komponente/
├── test.php       # Test wrapper - konfiguriraj putanje
├── mock-data.php  # Mock podaci - ACF fields, menus, itd.
└── README.md      # Ova dokumentacija (može se obrisati)
```

## 🔧 Dostupne funkcije

### WordPress Mock funkcije

```php
// ACF
get_field('ime_polja', 'option')
get_fields('option')
have_rows('repeater', 'option')
the_row()
get_sub_field('sub_polje')

// URLs
home_url('/')
site_url('/')
get_permalink($post_id)

// Site Info
get_bloginfo('name')
get_theme_mod('custom_logo')

// Escaping
esc_url($url)
esc_html($text)
esc_attr($value)
```

### Helper funkcije

```php
// Placeholder slike
pcz_placeholder_image(300, 200, 'Tekst')

// Lorem ipsum
pcz_lorem(50)

// Debug
debug_mock_data(true)
```

## ⌨️ Tipkovni prečaci

| Tipka | Akcija |
|-------|--------|
| `1` | Mobile prikaz |
| `2` | Tablet prikaz |
| `3` | Desktop prikaz |
| `4` | Full prikaz |

## 🎯 Best Practices

1. **Imenuj scenarije opisno** - `no_logo`, `empty_menu`, `long_title`
2. **Testiraj rubne slučajeve** - prazni podaci, predugački tekst, bez slike
3. **Koristi placeholder slike** - stavi ih u `test/assets/placeholder/`
4. **Ne mijenjaj originalne template-e** - mock sistem mora biti transparentan

