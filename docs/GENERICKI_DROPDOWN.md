# Generički Dropdown Menu - Dokumentacija

## 📋 Pregled

Header komponenta (`mega-menu.php`) sada podržava **generički pristup** za dropdown menue. Umjesto hardkodiranog pristupa samo za "Ponuda", sada možete dodati dropdown na **bilo koji menu item**.

## 🎯 Kako Funkcionira

### Automatsko Detektiranje

Header automatski detektira dropdown menue na temelju:

1. **WordPress Menu Meta** - Ako menu item ima custom meta `mega_menu_acf_field`
2. **Filter Hook** - Koristi `pcz_header_dropdown_config` filter za custom konfiguraciju
3. **Default Mapping** - Koristi `pcz_header_dropdown_mappings` filter za mapiranje naslova na ACF fieldove
4. **Auto-detekcija** - Ako je URL `#` ili prazan, pokušava pronaći ACF field po slug-u

### Struktura Podataka

Dropdown podaci se očekuju u ACF repeater strukturi:

```php
[
    [
        'naslov' => 'NASLOV KOLONE',
        'podsekcije' => [
            [
                'naslov_podsekcije' => 'Naslov podsekcije',
                'page_link_podsekcije' => 'http://example.com/link', // Opcionalno
                'stavke' => [
                    ['label' => 'Link 1', 'url' => 'http://example.com/1'],
                    ['label' => 'Link 2', 'url' => 'http://example.com/2'],
                ],
            ],
        ],
    ],
]
```

## 🔧 Konfiguracija

### Metoda 1: Filter Hook (Preporučeno)

Dodaj u `functions.php` ili Code Snippets:

```php
/**
 * Mapiranje menu itema na ACF fieldove
 */
add_filter('pcz_header_dropdown_mappings', function($mappings) {
    $mappings['ponuda'] = 'ponuda_blokovi';
    $mappings['usluge'] = 'usluge_blokovi';
    $mappings['proizvodi'] = 'proizvodi_blokovi';
    return $mappings;
});
```

### Metoda 2: Custom Konfiguracija po Itemu

```php
/**
 * Custom konfiguracija za specifični menu item
 */
add_filter('pcz_header_dropdown_config', function($config, $title, $url) {
    if ($title === 'Ponuda') {
        $config['acf_field'] = 'ponuda_blokovi';
    }
    return $config;
}, 10, 3);
```

### Metoda 3: Auto-detekcija (Default)

Ako menu item ima:
- URL = `#` ili prazan
- Naslov = "Ponuda"

Header će automatski tražiti ACF field `ponuda_blokovi` (ili `{slug}_blokovi`).

## 📝 Primjer: Dodavanje Novog Dropdown-a

### Korak 1: Kreiraj ACF Field Group

1. Idi u **Custom Fields → Field Groups**
2. Kreiraj novi field group (npr. "Usluge Menu")
3. Dodaj **Repeater** field:
   - Field Name: `usluge_blokovi`
   - Field Type: Repeater
   - Location: Options Page
4. Unutar repeatera dodaj:
   - `naslov` (Text)
   - `podsekcije` (Repeater)
     - `naslov_podsekcije` (Text)
     - `page_link_podsekcije` (URL)
     - `stavke` (Repeater)
       - `label` (Text)
       - `url` (URL)

### Korak 2: Dodaj u WordPress Menu

1. Idi u **Appearance → Menus**
2. Dodaj novi menu item (npr. "Usluge")
3. Postavi URL na `#` (indikator dropdown-a)
4. Spremi menu

### Korak 3: Konfiguriraj Mapiranje

Dodaj u `functions.php`:

```php
add_filter('pcz_header_dropdown_mappings', function($mappings) {
    $mappings['usluge'] = 'usluge_blokovi';
    return $mappings;
});
```

### Korak 4: Popuni Podatke

1. Idi u **Site Settings → Usluge Menu**
2. Popuni podatke u repeater polju
3. Spremi

**Gotovo!** Dropdown će se automatski pojaviti na menu itemu "Usluge".

## 🎨 CSS Klase

Header automatski dodaje CSS klase:

- `.has-dropdown` - Generička klasa za sve dropdown iteme
- `.pcz-nav__item--{slug}` - Specifična klasa po slug-u (npr. `.pcz-nav__item--ponuda`)

### Custom Stilovi

```css
/* Stiliziraj sve dropdown iteme */
.pcz-nav__item.has-dropdown .pcz-nav__link {
    /* custom stilovi */
}

/* Stiliziraj specifični dropdown */
.pcz-nav__item--ponuda .pcz-nav__link {
    /* custom stilovi samo za Ponuda */
}
```

## 🔍 Debugging

### Provjeri Detektirani Dropdown

Dodaj privremeno u `mega-menu.php`:

```php
// Nakon linije gdje se kreiraju $nav_items
foreach ($nav_items as $item) {
    if ($item['has_dropdown']) {
        error_log('Dropdown detected: ' . $item['title'] . ' -> ' . $item['dropdown_field']);
    }
}
```

### Provjeri ACF Podatke

```php
$test_data = get_field('usluge_blokovi', 'option');
var_dump($test_data); // Provjeri strukturu podataka
```

## ⚠️ Backward Compatibility

Stari kod koji koristi `.pcz-nav__item--ponuda` i dalje radi zbog backward compatibility CSS-a. Međutim, preporučeno je koristiti `.has-dropdown` za novi kod.

## 📚 Dodatni Resursi

- [ACF Repeater Documentation](https://www.advancedcustomfields.com/resources/repeater/)
- [WordPress Menu API](https://developer.wordpress.org/reference/functions/wp_nav_menu/)




