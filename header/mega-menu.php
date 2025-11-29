<?php
/**
 * pcz Custom Header - Kompaktna Verzija
 * 
 * Sadrži: Logo + Navigacija + Mega Menu Dropdown
 * Generički pristup - radi na staging i produkciji
 * 
 * KORIŠTENJE:
 * 1. Direktno uključi u Oxygen Code Block
 * 2. Ili koristi [pcz_header] shortcode
 * 
 * KONFIGURACIJA LOGA:
 * =====================
 * Logo se može podesiti na 3 načina (po prioritetu):
 * 
 * 1. PHP konstanta (najlakše - dodaj u functions.php):
 *    define('pcz_LOGO_URL', '/path/to/your/logo.svg');
 * 
 * 2. ACF Options polje 'site_logo':
 *    - Idi u ACF > Field Groups
 *    - Kreiraj Image polje s imenom 'site_logo'
 *    - Postavi Location: Options Page
 * 
 * 3. WordPress Customizer:
 *    - Appearance > Customize > Site Identity > Logo
 * 
 * KONFIGURACIJA DROPDOWN MENUA:
 * - Koristi filter 'pcz_header_dropdown_config' za custom konfiguraciju
 * - Ili ACF polje 'ponuda_blokovi' u Options
 * 
 * @package pcz_Redizajn
 * @since 3.0.0
 */

// Sprječava direktan pristup ako se koristi standalone
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// =============================================================================
// HELPER FUNKCIJE
// =============================================================================

/**
 * Dohvati ACF field name za dropdown menu itema
 * 
 * @param string $menu_item_title Naslov menu itema
 * @param string $menu_item_url URL menu itema
 * @return string|null ACF field name ili null
 */
if ( ! function_exists( 'pcz_get_dropdown_field_name' ) ) {
function pcz_get_dropdown_field_name( $menu_item_title, $menu_item_url ) {
    // Provjeri WordPress menu meta (ako postoji)
    if ( function_exists( 'get_post_meta' ) ) {
        // Ovo bi trebalo raditi ako imamo menu item ID, ali za sada koristimo fallback
    }
    
    // Provjeri filter za custom konfiguraciju
    $config = apply_filters( 'pcz_header_dropdown_config', array(), $menu_item_title, $menu_item_url );
    if ( ! empty( $config['acf_field'] ) ) {
        return $config['acf_field'];
    }
    
    // Default mapping - za backward compatibility
    // Može se proširiti s više mapiranja
    $default_mappings = apply_filters( 'pcz_header_dropdown_mappings', array(
        'ponuda' => 'ponuda_blokovi',
        // Dodaj više mapiranja ovdje po potrebi
    ) );
    
    $title_lower = strtolower( trim( $menu_item_title ) );
    if ( isset( $default_mappings[ $title_lower ] ) ) {
        return $default_mappings[ $title_lower ];
    }
    
    // Provjeri je li URL hash ili prazan (indikator dropdown-a)
    if ( $menu_item_url === '#' || empty( $menu_item_url ) || strpos( $menu_item_url, '#' ) === 0 ) {
        // Pokušaj pronaći ACF field po slug-u menu itema
        $slug = sanitize_title( $menu_item_title );
        $field_name = $slug . '_blokovi'; // Default pattern
        
        // Provjeri postoji li field
        if ( function_exists( 'get_field' ) ) {
            $test_data = get_field( $field_name, 'option' );
            if ( ! empty( $test_data ) ) {
                return $field_name;
            }
        }
    }
    
    return null;
}
}

/**
 * Provjeri ima li menu item dropdown
 * 
 * @param string $menu_item_title Naslov menu itema
 * @param string $menu_item_url URL menu itema
 * @return bool
 */
if ( ! function_exists( 'pcz_has_dropdown' ) ) {
function pcz_has_dropdown( $menu_item_title, $menu_item_url ) {
    $field_name = pcz_get_dropdown_field_name( $menu_item_title, $menu_item_url );
    if ( ! $field_name ) {
        return false;
    }
    
    if ( function_exists( 'get_field' ) ) {
        $data = get_field( $field_name, 'option' );
        return ! empty( $data );
    }
    
    return false;
}
}

// =============================================================================
// KONFIGURACIJA
// =============================================================================

// Site URL za logo link
$site_url = home_url( '/' );

// =============================================================================
// LOGO KONFIGURACIJA - 3 načina postavljanja (po prioritetu)
// =============================================================================

$logo_url = '';

// 1. PHP Konstanta (NAJLAKŠE - dodaj u functions.php ili wp-config.php)
//    Primjer: define('pcz_LOGO_URL', '/wp-content/uploads/2025/logo.svg');
if ( defined( 'pcz_LOGO_URL' ) && pcz_LOGO_URL ) {
    $logo_url = pcz_LOGO_URL;
}

// 2. ACF Options polje 'site_logo'
if ( empty( $logo_url ) && function_exists( 'get_field' ) ) {
    $acf_logo = get_field( 'site_logo', 'option' );
    if ( $acf_logo ) {
        // Podržava i URL string i array (ACF Image field)
        $logo_url = is_array( $acf_logo ) ? $acf_logo['url'] : $acf_logo;
    }
}

// 3. WordPress Customizer (Appearance > Customize > Site Identity)
if ( empty( $logo_url ) ) {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    if ( $custom_logo_id ) {
        $logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
    }
}

// 4. Fallback - generički path (promijeni po potrebi)
if ( empty( $logo_url ) ) {
    $logo_url = content_url( '/uploads/2025/06/pcz-logotip-v2.svg' );
}

// Filter za programatsko overridanje
$logo_url = apply_filters( 'pcz_header_logo_url', $logo_url );

// =============================================================================
// NAVIGACIJA - Dohvat iz WordPress Menu-a
// =============================================================================

$nav_items = array();

// Pokušaj dohvatiti iz WordPress menu-a
$menu_locations = get_nav_menu_locations();
$menu_name = isset( $menu_locations['main-menu'] ) ? 'main-menu' : 'primary';

if ( isset( $menu_locations[ $menu_name ] ) ) {
    $menu = wp_get_nav_menu_object( $menu_locations[ $menu_name ] );
    if ( $menu ) {
        $menu_items = wp_get_nav_menu_items( $menu->term_id );
        if ( $menu_items ) {
            foreach ( $menu_items as $item ) {
                if ( $item->menu_item_parent == 0 ) { // Samo top-level
                    $has_dropdown = pcz_has_dropdown( $item->title, $item->url );
                    $dropdown_field = $has_dropdown ? pcz_get_dropdown_field_name( $item->title, $item->url ) : null;
                    
                    $nav_items[] = array(
                        'title'          => $item->title,
                        'url'            => $item->url,
                        'has_dropdown'   => $has_dropdown,
                        'dropdown_field' => $dropdown_field,
                        'item_id'        => $item->ID ?? null,
                    );
                }
            }
        }
    }
}

// Fallback - hardkodirane stavke ako menu ne postoji
if ( empty( $nav_items ) ) {
    $nav_items = array(
        array( 
            'title' => 'Naslovna', 
            'url' => $site_url, 
            'has_dropdown' => false,
            'dropdown_field' => null,
        ),
        array( 
            'title' => 'Ponuda', 
            'url' => '#', 
            'has_dropdown' => true,
            'dropdown_field' => 'ponuda_blokovi',
        ),
        array( 
            'title' => 'Cjenik', 
            'url' => $site_url . 'cjenik-usluga/', 
            'has_dropdown' => false,
            'dropdown_field' => null,
        ),
        array( 
            'title' => 'O nama', 
            'url' => $site_url . 'strucni-tim/', 
            'has_dropdown' => false,
            'dropdown_field' => null,
        ),
        array( 
            'title' => 'Kontakt', 
            'url' => $site_url . 'kontakt-i-lokacija/', 
            'has_dropdown' => false,
            'dropdown_field' => null,
        ),
    );
}

// =============================================================================
// MEGA MENU DATA - Dohvat iz ACF (generički za svaki dropdown)
// =============================================================================

// Pripremi dropdown podatke za svaki nav item
foreach ( $nav_items as &$item ) {
    $item['dropdown_data'] = array();
    
    if ( $item['has_dropdown'] && ! empty( $item['dropdown_field'] ) ) {
        if ( function_exists( 'get_field' ) ) {
            $item['dropdown_data'] = get_field( $item['dropdown_field'], 'option' );
        }
    }
}
unset( $item ); // Oslobodi referencu

// Fallback podaci za backward compatibility (ako nema ACF podataka)
// Ovo se koristi samo ako je dropdown_field = 'ponuda_blokovi' i nema podataka
foreach ( $nav_items as &$item ) {
    if ( $item['has_dropdown'] && empty( $item['dropdown_data'] ) && $item['dropdown_field'] === 'ponuda_blokovi' ) {
        $item['dropdown_data'] = array(
            // PONUDA ZA ODRASLE
            array(
                'naslov' => 'PONUDA ZA ODRASLE',
                'podsekcije' => array(
                    array(
                        'naslov_podsekcije' => 'PLESNI TEČAJEVI',
                        'page_link_podsekcije' => '',
                        'stavke' => array(
                            array( 'label' => 'Tečaj Društvenih plesova', 'url' => $site_url . 'tecaj-drustvenih-plesova/' ),
                            array( 'label' => 'Tečaj Latin Jam', 'url' => $site_url . 'tecaj-latin-jam/' ),
                        ),
                    ),
                    array(
                        'naslov_podsekcije' => 'PLESNE REKREACIJE',
                        'page_link_podsekcije' => '',
                        'stavke' => array(
                            array( 'label' => 'Rekreacija Društvenih plesova', 'url' => $site_url . 'rekreacija-drustvenih-plesova/' ),
                            array( 'label' => 'Rekreacija Latin Jam', 'url' => $site_url . 'rekreacija-latin-jam/' ),
                        ),
                    ),
                    array(
                        'naslov_podsekcije' => 'BRZI TEČAJEVI',
                        'page_link_podsekcije' => $site_url . 'brzi-tecajevi/',
                        'stavke' => array(),
                    ),
                    array(
                        'naslov_podsekcije' => 'TEČAJEVI ZA MLADENCE',
                        'page_link_podsekcije' => $site_url . 'tecajevi-za-mladence/',
                        'stavke' => array(),
                    ),
                    array(
                        'naslov_podsekcije' => 'FULL DANCE WORKOUT',
                        'page_link_podsekcije' => $site_url . 'full-dance-workout/',
                        'stavke' => array(),
                    ),
                    array(
                        'naslov_podsekcije' => 'HIP HOP ZA ODRASLE',
                        'page_link_podsekcije' => $site_url . 'hip-hop-za-odrasle/',
                        'stavke' => array(),
                    ),
                    array(
                        'naslov_podsekcije' => 'INDIVIDUALNA PODUKA',
                        'page_link_podsekcije' => $site_url . 'individualna-poduka/',
                        'stavke' => array(),
                    ),
                ),
            ),
            // PONUDA ZA DJECU
            array(
                'naslov' => 'PONUDA ZA DJECU',
                'podsekcije' => array(
                    array(
                        'naslov_podsekcije' => 'PLESNA IGRAONICA',
                        'page_link_podsekcije' => $site_url . 'plesna-igraonica/',
                        'stavke' => array(),
                    ),
                    array(
                        'naslov_podsekcije' => 'HIP HOP ZA DJECU',
                        'page_link_podsekcije' => '',
                        'stavke' => array(
                            array( 'label' => 'Rekreacijske grupe', 'url' => $site_url . 'hip-hop-rekreacijske-grupe/' ),
                            array( 'label' => 'Natjecateljske grupe', 'url' => $site_url . 'hip-hop-natjecateljske-grupe/' ),
                        ),
                    ),
                    array(
                        'naslov_podsekcije' => 'PLESNE REKREACIJE',
                        'page_link_podsekcije' => '',
                        'stavke' => array(
                            array( 'label' => 'Rekreacijske grupe Društvenih plesova', 'url' => $site_url . 'djeca-rekreacija-drustvenih-plesova/' ),
                        ),
                    ),
                ),
            ),
        );
    }
}
unset( $item ); // Oslobodi referencu

// Trenutna stranica za active state
$current_url = '';
if ( isset( $GLOBALS['wp'] ) ) {
    $current_url = home_url( add_query_arg( array(), $GLOBALS['wp']->request ) );
}

// =============================================================================
// LOGO VELIČINA - Dohvat iz ACF-a
// =============================================================================
$logo_height_desktop = 48; // Default
$logo_height_mobile = 40;  // Default

if ( function_exists( 'get_field' ) ) {
    $acf_desktop = get_field( 'logo_height_desktop', 'option' );
    $acf_mobile = get_field( 'logo_height_mobile', 'option' );
    
    if ( $acf_desktop && is_numeric( $acf_desktop ) ) {
        $logo_height_desktop = intval( $acf_desktop );
    }
    if ( $acf_mobile && is_numeric( $acf_mobile ) ) {
        $logo_height_mobile = intval( $acf_mobile );
    }
}

// Filter za programatsko overridanje
$logo_height_desktop = apply_filters( 'pcz_logo_height_desktop', $logo_height_desktop );
$logo_height_mobile = apply_filters( 'pcz_logo_height_mobile', $logo_height_mobile );
?>

<!-- pcz Custom Header - Dynamic Logo Size -->
<style>
.pcz-header__logo-img {
    height: <?php echo esc_attr( $logo_height_desktop ); ?>px !important;
}
@media screen and (max-width: 768px) {
    .pcz-header__logo-img {
        height: <?php echo esc_attr( $logo_height_mobile ); ?>px !important;
    }
}
</style>

<!-- pcz Custom Header -->
<?php
// Klase za header element - omogućuje brand-awareness
$header_classes = 'pcz-header';
if ( function_exists( 'apply_filters' ) ) {
    $header_classes = apply_filters( 'pcz_header_classes', $header_classes );
}

// Atributi za header
$header_attrs = array(
    'class' => $header_classes,
    'id'    => 'pcz-header',
);

// Omogući brand sustavu da dodaje atribute
if ( function_exists( 'apply_filters' ) ) {
    $header_attrs = apply_filters( 'pcz_header_attributes', $header_attrs );
}

// Konvertiraj atribute u HTML string
$header_attrs_html = '';
foreach ( $header_attrs as $attr => $value ) {
    $header_attrs_html .= ' ' . esc_attr( $attr ) . '="' . esc_attr( $value ) . '"';
}
?>
<header<?php echo $header_attrs_html; ?>>
    <div class="pcz-header__container">
        
        <!-- Logo -->
        <a href="<?php echo esc_url( $site_url ); ?>" class="pcz-header__logo">
            <?php if ( $logo_url ) : ?>
                <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="pcz-header__logo-img">
            <?php else : ?>
                <span class="pcz-header__logo-text"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
            <?php endif; ?>
        </a>
        
        <!-- Navigation -->
        <nav class="pcz-nav" id="pcz-nav" role="navigation" aria-label="Glavna navigacija">
            <ul class="pcz-nav__list">
                <?php foreach ( $nav_items as $index => $item ) : ?>
                    <?php 
                    $is_active = ( trailingslashit( $item['url'] ) === trailingslashit( $current_url ) ) || 
                                 ( $item['url'] === $site_url && is_front_page() );
                    $item_classes = 'pcz-nav__item';
                    if ( $item['has_dropdown'] ) {
                        $item_classes .= ' has-dropdown';
                        // Dodaj i specifičnu klasu za backward compatibility (ako je potrebno)
                        $item_slug = sanitize_title( $item['title'] );
                        $item_classes .= ' pcz-nav__item--' . esc_attr( $item_slug );
                    }
                    if ( $is_active ) {
                        $item_classes .= ' is-active';
                    }
                    
                    // Generiraj unique ID za dropdown
                    $dropdown_id = 'pcz-mega-dropdown-' . $index;
                    ?>
                    <li class="<?php echo esc_attr( $item_classes ); ?>" data-dropdown-id="<?php echo esc_attr( $dropdown_id ); ?>">
                        <a href="<?php echo esc_url( $item['url'] ); ?>" 
                           id="pcz-nav-link-<?php echo esc_attr( $index ); ?>"
                           class="pcz-nav__link"
                           <?php if ( $item['has_dropdown'] ) : ?>
                               aria-haspopup="true" 
                               aria-expanded="false"
                               aria-controls="<?php echo esc_attr( $dropdown_id ); ?>"
                           <?php endif; ?>
                           <?php if ( $is_active ) : ?>
                               aria-current="page"
                           <?php endif; ?>>
                            <?php echo esc_html( $item['title'] ); ?>
                        </a>
                        
                        <?php if ( $item['has_dropdown'] && ! empty( $item['dropdown_data'] ) ) : ?>
                        <!-- Mega Menu Dropdown -->
                        <div class="pcz-mega-dropdown" 
                             id="<?php echo esc_attr( $dropdown_id ); ?>" 
                             role="menu"
                             aria-labelledby="pcz-nav-link-<?php echo esc_attr( $index ); ?>">
                            <div class="pcz-mega-dropdown__container">
                                <?php foreach ( $item['dropdown_data'] as $blok ) : ?>
                                <div class="pcz-mega-dropdown__column">
                                    <?php if ( ! empty( $blok['naslov'] ) ) : ?>
                                    <h3 class="pcz-mega-dropdown__title"><?php echo esc_html( $blok['naslov'] ); ?></h3>
                                    <?php endif; ?>
                                    
                                    <?php if ( ! empty( $blok['podsekcije'] ) ) : ?>
                                    <ul class="pcz-mega-dropdown__list">
                                        <?php foreach ( $blok['podsekcije'] as $pod ) : ?>
                                        <?php 
                                        $naziv = isset( $pod['naslov_podsekcije'] ) ? $pod['naslov_podsekcije'] : '';
                                        $link = isset( $pod['page_link_podsekcije'] ) ? $pod['page_link_podsekcije'] : '';
                                        $stavke = isset( $pod['stavke'] ) ? $pod['stavke'] : array();
                                        ?>
                                        <li class="pcz-mega-dropdown__item" role="none">
                                            <?php if ( $naziv ) : ?>
                                                <?php if ( $link && empty( $stavke ) ) : ?>
                                                <a href="<?php echo esc_url( $link ); ?>" class="pcz-mega-dropdown__section-link" role="menuitem">
                                                    <?php echo esc_html( $naziv ); ?>
                                                </a>
                                                <?php else : ?>
                                                <span class="pcz-mega-dropdown__section-title">
                                                    <?php echo esc_html( $naziv ); ?>
                                                </span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            
                                            <?php if ( ! empty( $stavke ) ) : ?>
                                            <ul class="pcz-mega-dropdown__sublist">
                                                <?php foreach ( $stavke as $s ) : ?>
                                                <?php if ( ! empty( $s['label'] ) ) : ?>
                                                <li class="pcz-mega-dropdown__subitem" role="none">
                                                    <a href="<?php echo esc_url( isset( $s['url'] ) ? $s['url'] : '#' ); ?>" class="pcz-mega-dropdown__link" role="menuitem">
                                                        <?php echo esc_html( $s['label'] ); ?>
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
                        
                    </li>
                <?php endforeach; ?>
            </ul>
            
            <!-- Mobile Toggle -->
            <button class="pcz-nav__toggle" id="pcz-nav-toggle" aria-label="Otvori menu" aria-expanded="false" aria-controls="pcz-nav">
                <span></span>
                <span></span>
                <span></span>
            </button>
            
            <?php 
            // Hook za brand switcher ili druge elemente nakon navigacije
            if ( function_exists('do_action') ) {
                do_action( 'pcz_after_header_nav' );
            }
            ?>
        </nav>
        
    </div>
    
    <!-- Accent Line -->
    <div class="pcz-header__accent"></div>
</header>
<!-- /pcz Custom Header -->
