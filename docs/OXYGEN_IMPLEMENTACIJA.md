# Oxygen Builder - Mega Menu Implementacija

## Pregled

Ovaj dokument opisuje korak-po-korak implementaciju mega menija u Oxygen Builder koristeći ACF podatke.

---

## Pristup: Zašto NE koristiti Max Mega Menu

Iako si instalirao Max Mega Menu plugin, preporučujem **custom pristup** iz sljedećih razloga:

1. **Potpuna kontrola dizajna** - Možeš dizajnirati točno kako želiš
2. **Performanse** - Manje plugin overhead-a
3. **Integracija s ACF** - Direktno čitaš ACF podatke
4. **Oxygen kompatibilnost** - Nativno radi s Oxygen elementima
5. **Lakše održavanje** - Sve na jednom mjestu

---

## Korak 1: Kreiranje Header Template-a

### 1.1 Idi na Oxygen > Headers

1. U WordPress adminu idi na `Oxygen → Headers`
2. Klikni `Add Header`
3. Postavke:
   - **Title:** Main Header
   - **Location:** Everywhere
   - **Priority:** 10

### 1.2 Otvori Header u Oxygen Editoru

Klikni "Edit with Oxygen" da uđeš u builder.

---

## Korak 2: Struktura Header Elementa

Kreiraj sljedeću strukturu:

```
Section (pcz-header)
├── Container (pcz-header__container)
│   ├── Link Wrapper (pcz-logo)
│   │   └── Image (pcz-logo__image)
│   │
│   └── Nav (pcz-nav)
│       ├── Div (pcz-nav__list)
│       │   ├── Link (Naslovna)
│       │   ├── Div (pcz-nav-item--ponuda) ← OVDJE IDE MEGA MENU
│       │   │   ├── Link (Ponuda)
│       │   │   └── PHP Code Block (Mega Menu)
│       │   ├── Link (Cjenik)
│       │   ├── Link (O nama)
│       │   └── Link (Kontakt)
│       │
│       └── Div (pcz-mobile-toggle) [opcionalno za mobile]
│
└── Div (pcz-header__accent) ← Žuta linija
```

---

## Korak 3: Dodavanje Elemenata u Oxygen

### 3.1 Dodaj Section

1. Dodaj `Section` element
2. ID: `pcz-header`
3. Layout: `Flexbox`
4. Background: `#3d3d3d`

### 3.2 Dodaj Container

1. Dodaj `Container` unutar Section-a
2. Class: `pcz-header__container`
3. Layout: `Flexbox` | `Row` | `Space Between` | `Center`
4. Max Width: `1400px`
5. Padding: `16px 24px`

### 3.3 Dodaj Logo

1. Dodaj `Link Wrapper`
2. Class: `pcz-logo`
3. URL: `{{ site_url }}`
4. Unutra dodaj `Image` element
5. Postavi logo sliku

### 3.4 Dodaj Navigation Container

1. Dodaj `Div` element
2. Class: `pcz-nav__list`
3. Layout: `Flexbox` | `Row` | `Gap: 8px`

### 3.5 Dodaj Menu Items

Za svaku stavku (Naslovna, Cjenik, O nama, Kontakt):

1. Dodaj `Text Link` element
2. Class: `pcz-nav__link`
3. Postavi odgovarajući URL

### 3.6 Dodaj Ponuda Item s Mega Menu

**Ovo je ključni dio!**

1. Dodaj `Div` element (wrapper)
2. Class: `pcz-nav-item--ponuda`
3. Position: `Relative`

Unutar tog Div-a:
1. Dodaj `Text Link` za "Ponuda"
   - Class: `pcz-nav__link`
   - URL: `#`
   - Aria-haspopup: `true`
   - Aria-expanded: `false`

2. Dodaj `PHP Code Block` element za Mega Menu sadržaj

---

## Korak 4: PHP Code Block - Mega Menu

U PHP Code Block, dodaj sljedeći kod:

```php
<?php
$blokovi = get_field('ponuda_blokovi', 'option');

if ($blokovi && is_array($blokovi)) :
?>
<div class="pcz-mega-menu">
    <div class="pcz-mega-menu__container">
        <?php foreach ($blokovi as $blok) : ?>
            <div class="pcz-mega-menu__column">
                <?php if (!empty($blok['naslov'])) : ?>
                    <h3 class="pcz-mega-menu__title"><?php echo esc_html($blok['naslov']); ?></h3>
                <?php endif; ?>
                
                <?php if (!empty($blok['podsekcije'])) : ?>
                    <ul class="pcz-mega-menu__list">
                        <?php foreach ($blok['podsekcije'] as $podsekcija) : ?>
                            <li class="pcz-mega-menu__item">
                                <?php 
                                $naslov = $podsekcija['naslov_podsekcije'] ?? '';
                                $link = $podsekcija['page_link_podsekcije'] ?? '';
                                $stavke = $podsekcija['stavke'] ?? [];
                                ?>
                                
                                <?php if ($naslov) : ?>
                                    <?php if ($link && empty($stavke)) : ?>
                                        <a href="<?php echo esc_url($link); ?>" class="pcz-mega-menu__section-title pcz-mega-menu__section-title--link">
                                            <?php echo esc_html($naslov); ?>
                                        </a>
                                    <?php else : ?>
                                        <span class="pcz-mega-menu__section-title"><?php echo esc_html($naslov); ?></span>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                <?php if (!empty($stavke)) : ?>
                                    <ul class="pcz-mega-menu__sublist">
                                        <?php foreach ($stavke as $stavka) : ?>
                                            <?php if (!empty($stavka['label'])) : ?>
                                                <li class="pcz-mega-menu__subitem">
                                                    <a href="<?php echo esc_url($stavka['url'] ?? '#'); ?>" class="pcz-mega-menu__link">
                                                        <?php echo esc_html($stavka['label']); ?>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
```

---

## Korak 5: Dodavanje CSS-a

### Opcija A: CSS Code Block u Oxygen

1. Dodaj `CSS Code Block` element (može biti u header ili globalno)
2. Kopiraj sadržaj iz `/header/mega-menu.css`

### Opcija B: Oxygen Stylesheet

1. Idi na `Oxygen → Stylesheets`
2. Kreiraj novi stylesheet "pcz Mega Menu"
3. Kopiraj CSS

### Opcija C: Enqueue CSS u functions.php

```php
function pcz_enqueue_mega_menu_styles() {
    wp_enqueue_style(
        'pcz-mega-menu',
        get_template_directory_uri() . '/assets/css/mega-menu.css',
        array(),
        '1.0.0'
    );
}
add_action('wp_enqueue_scripts', 'pcz_enqueue_mega_menu_styles');
```

---

## Korak 6: Dodavanje JavaScript-a

### Opcija A: JavaScript Code Block u Oxygen

1. Dodaj `JavaScript Code Block` element
2. Kopiraj sadržaj iz `/header/mega-menu.js`
3. Postavi da se učitava u footer

### Opcija B: Enqueue JS

```php
function pcz_enqueue_mega_menu_scripts() {
    wp_enqueue_script(
        'pcz-mega-menu',
        get_template_directory_uri() . '/assets/js/mega-menu.js',
        array(),
        '1.0.0',
        true // U footer
    );
}
add_action('wp_enqueue_scripts', 'pcz_enqueue_mega_menu_scripts');
```

---

## Korak 7: Stiliziranje u Oxygen

### Header Background

1. Selektiraj Section
2. Background Color: `#3d3d3d`

### Logo

1. Selektiraj Image
2. Height: `50px`
3. Width: `auto`

### Navigation Links

1. Selektiraj Text Link
2. Typography:
   - Font Size: `16px`
   - Color: `#ffffff`
3. Spacing:
   - Padding: `8px 16px`
4. Border:
   - All sides: `2px solid transparent`
   - Radius: `4px`
5. **Hover State:**
   - Border Color: `#C71585`
6. **Active State (add class `is-active`):**
   - Background: `#C71585`
   - Border Color: `#C71585`

### Mega Menu Container

1. Position: `Absolute`
2. Top: `100%`
3. Left: `0`
4. Width: `100%`
5. Background: `#ffffff`
6. Box Shadow: `0 10px 40px rgba(0,0,0,0.25)`
7. Opacity: `0` (default)
8. Visibility: `hidden` (default)

---

## Korak 8: Hover Efekt

CSS će se pobrinuti za hover efekt, ali ako trebaš Oxygen interactions:

1. Selektiraj parent Div (`pcz-nav-item--ponuda`)
2. Idi na Advanced → Interactions
3. Dodaj Hover trigger
4. Target: `.pcz-mega-menu`
5. Animation: Fade In

---

## Korak 9: Mobile Menu (Opcionalno)

Za mobile verziju:

1. Dodaj hamburger icon/button
2. Class: `pcz-mobile-toggle`
3. Sakrij navigation na mobile
4. Prikaži mobile toggle
5. JavaScript će handlati toggle

---

## Korak 10: Testiranje

### Desktop

- [ ] Hover preko "Ponuda" otvara mega menu
- [ ] Svi linkovi rade ispravno
- [ ] Styling je ispravan
- [ ] Smooth animacija

### Tablet

- [ ] Menu se prikazuje ispravno
- [ ] Touch events rade
- [ ] Responsive layout

### Mobile

- [ ] Hamburger menu radi
- [ ] Mega menu je fullscreen
- [ ] Touch-friendly linkovi
- [ ] Close button radi

### Accessibility

- [ ] Keyboard navigacija (Tab, Enter, Escape)
- [ ] Screen reader friendly (ARIA attributes)
- [ ] Focus states vidljivi

---

## Alternativni Pristup: Reusable Part

Ako želiš Mega Menu kao Reusable Part:

1. Idi na `Oxygen → Templates`
2. Kreiraj novu stranicu "Mega Menu Component"
3. Izgradi mega menu strukturu
4. Selektiraj parent element
5. Right-click → Make Reusable
6. Daj ime: "pcz Mega Menu"
7. U headeru: Add → Reusable → pcz Mega Menu → Single

**Prednost:** Lako uređivanje na jednom mjestu
**Mana:** Malo kompleksniji setup

---

## Troubleshooting

### Mega menu se ne prikazuje

1. Provjeri da ACF podaci postoje u Site Settings
2. Provjeri PHP errors u WP_DEBUG
3. Provjeri CSS z-index

### Hover ne radi

1. Provjeri da parent ima `position: relative`
2. Provjeri CSS selektore
3. Provjeri JavaScript console za greške

### Styling nije ispravan

1. Provjeri da je CSS učitan
2. Koristi browser DevTools
3. Provjeri specifičnost selektora

---

## Sljedeći Koraci

1. ✅ Kreirati header strukturu u Oxygen
2. ✅ Dodati PHP Code Block za mega menu
3. ✅ Dodati CSS stilove
4. ✅ Testirati na svim uređajima
5. ⬜ Optimizirati za performanse
6. ⬜ Dodati mobile menu toggle
7. ⬜ Finalizirati dizajn

---

*Dokumentacija: Verzija 1.0 | 27.11.2025*

