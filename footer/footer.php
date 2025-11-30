<?php
/**
 * pcz Custom Footer - Kompletno Rješenje
 * 
 * Sadrži: Kontakt sekcija + Footer + Copyright
 * Generički pristup - radi na staging i produkciji
 * 
 * KORIŠTENJE:
 * 1. Direktno uključi u Oxygen Code Block
 * 2. Ili koristi [pcz_footer] shortcode
 * 
 * ACF POLJA (u Site Settings):
 * - footer_radno_vrijeme (Group)
 * - footer_kontakt (Group)
 * - footer_social_links (Repeater)
 * - footer_nav_links (Repeater)
 * - footer_legal_links (Repeater)
 * - footer_copyright (Text)
 * - footer_google_maps_embed (URL)
 * 
 * @package pcz_Redizajn
 * @since 1.0.0
 */

// Sprječava direktan pristup
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// =============================================================================
// KONFIGURACIJA - Dohvat podataka iz ACF
// =============================================================================

$site_url = home_url( '/' );
$site_name = get_bloginfo( 'name' );

// Logo - iz ACF ili fallback
$logo_url = '';
if ( function_exists( 'get_field' ) ) {
    $acf_logo = get_field( 'site_logo', 'option' );
    if ( ! empty( $acf_logo ) ) {
        if ( is_array( $acf_logo ) && isset( $acf_logo['url'] ) ) {
            $logo_url = $acf_logo['url'];
        } elseif ( is_numeric( $acf_logo ) ) {
            $logo_url = wp_get_attachment_image_url( $acf_logo, 'full' );
        } else {
            $logo_url = $acf_logo;
        }
    }
}
if ( empty( $logo_url ) ) {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    if ( $custom_logo_id ) {
        $logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
    }
}
if ( empty( $logo_url ) ) {
    $logo_url = content_url( '/uploads/2025/06/pcz-logotip-v2.svg' );
}

// Omogući brand sustavu da override-a logo URL
if ( function_exists( 'apply_filters' ) ) {
    $logo_url = apply_filters( 'pcz_footer_logo_url', $logo_url );
}

// =============================================================================
// RADNO VRIJEME - iz ACF
// =============================================================================

$radno_vrijeme = array(
    'pon_pet' => 'od 14:00 do 22:00 sata',
    'sub'     => 'od 10:00 do 14:00 sati',
    'ned'     => 'ne radimo',
);

if ( function_exists( 'get_field' ) ) {
    $acf_radno = get_field( 'footer_radno_vrijeme', 'option' );
    if ( ! empty( $acf_radno ) ) {
        $radno_vrijeme = array(
            'pon_pet' => ! empty( $acf_radno['pon_pet'] ) ? $acf_radno['pon_pet'] : $radno_vrijeme['pon_pet'],
            'sub'     => ! empty( $acf_radno['sub'] ) ? $acf_radno['sub'] : $radno_vrijeme['sub'],
            'ned'     => ! empty( $acf_radno['ned'] ) ? $acf_radno['ned'] : $radno_vrijeme['ned'],
        );
    }
}

// =============================================================================
// KONTAKT PODACI - iz ACF
// =============================================================================

$kontakt = array(
    'telefon' => '+385 98 9157 443',
    'email'   => 'info@pcz.hr',
    'adresa'  => 'Ozaljska 93, Zagreb',
);

if ( function_exists( 'get_field' ) ) {
    $acf_kontakt = get_field( 'footer_kontakt', 'option' );
    if ( ! empty( $acf_kontakt ) ) {
        $kontakt = array(
            'telefon' => ! empty( $acf_kontakt['telefon'] ) ? $acf_kontakt['telefon'] : $kontakt['telefon'],
            'email'   => ! empty( $acf_kontakt['email'] ) ? $acf_kontakt['email'] : $kontakt['email'],
            'adresa'  => ! empty( $acf_kontakt['adresa'] ) ? $acf_kontakt['adresa'] : $kontakt['adresa'],
        );
    }
}

// =============================================================================
// GOOGLE MAPS - iz ACF
// =============================================================================

$google_maps_embed = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2781.5!2d15.9441!3d45.7989!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4765d6e8c8c8c8c8%3A0x0!2sOzaljska%2093%2C%2010000%20Zagreb!5e0!3m2!1shr!2shr!4v1';

if ( function_exists( 'get_field' ) ) {
    $acf_maps = get_field( 'footer_google_maps_embed', 'option' );
    if ( ! empty( $acf_maps ) ) {
        $google_maps_embed = $acf_maps;
    }
}

// =============================================================================
// SOCIAL LINKOVI - iz ACF
// =============================================================================

// =============================================================================
// SOCIJALNE MREŽE - Brand ima prioritet!
// =============================================================================
// Logika:
// 1. PRIORITET: Brand-specific socijalne (ps_socijalne_mreze / spk_socijalne_mreze)
// 2. FALLBACK: Hardkodirane vrijednosti (ako brand socials nisu definirane)

$social_links = array();

// Omogući brand sustavu da postavi socijalne mreže (PRIORITET)
if ( function_exists( 'apply_filters' ) ) {
    $social_links = apply_filters( 'pcz_footer_social_links', $social_links );
}

// FALLBACK - samo ako brand sustav nije vratio ništa
if ( empty( $social_links ) ) {
    $social_links = array(
        array( 'platforma' => 'instagram', 'url' => 'https://instagram.com/plesnicentarzagreb' ),
        array( 'platforma' => 'facebook', 'url' => 'https://facebook.com/plesnicentarzagreb' ),
    );
}

// =============================================================================
// FOOTER NAVIGACIJA
// Prioritet: 1. WordPress Menu (lokacija) → 2. WordPress Menu (po imenu) → 3. ACF → 4. Hardcoded
// =============================================================================

$footer_nav = array();
$menu = false;

// 1. PRIMARNO: WordPress Menu - po lokaciji "footer-menu"
$menu_locations = get_nav_menu_locations();
if ( isset( $menu_locations['footer-menu'] ) ) {
    $menu = wp_get_nav_menu_object( $menu_locations['footer-menu'] );
}

// 2. BACKUP: WordPress Menu - po imenu "Footer Menu"
if ( ! $menu ) {
    $menu = wp_get_nav_menu_object( 'Footer Menu' );
}

// Dohvati stavke iz WordPress menija
if ( $menu ) {
    $menu_items = wp_get_nav_menu_items( $menu->term_id );
    if ( $menu_items ) {
        foreach ( $menu_items as $item ) {
            if ( $item->menu_item_parent == 0 ) { // Samo top-level stavke
                $footer_nav[] = array(
                    'label' => $item->title,
                    'url'   => $item->url,
                );
            }
        }
    }
}

// 3. FALLBACK: ACF footer_nav_links (ako nema WordPress menija)
if ( empty( $footer_nav ) && function_exists( 'get_field' ) ) {
    $acf_nav = get_field( 'footer_nav_links', 'option' );
    if ( ! empty( $acf_nav ) && is_array( $acf_nav ) ) {
        $footer_nav = $acf_nav;
    }
}

// 4. ZADNJI FALLBACK: Hardkodirane stavke
if ( empty( $footer_nav ) ) {
    $footer_nav = array(
        array( 'label' => 'Naslovna', 'url' => $site_url ),
        array( 'label' => 'Cjenik', 'url' => $site_url . 'cjenik-usluga/' ),
        array( 'label' => 'O nama', 'url' => $site_url . 'strucni-tim/' ),
        array( 'label' => 'Kontakt', 'url' => $site_url . 'kontakt-i-lokacija/' ),
    );
}

// =============================================================================
// PRAVNI LINKOVI - iz ACF
// =============================================================================

$legal_links = array(
    array( 'label' => 'Pravila privatnosti', 'url' => $site_url . 'pravila-privatnosti/' ),
    array( 'label' => 'Uvjeti i odredbe', 'url' => $site_url . 'uvjeti-i-odredbe/' ),
);

if ( function_exists( 'get_field' ) ) {
    $acf_legal = get_field( 'footer_legal_links', 'option' );
    if ( ! empty( $acf_legal ) && is_array( $acf_legal ) ) {
        $legal_links = $acf_legal;
    }
}

// =============================================================================
// COPYRIGHT - iz ACF
// =============================================================================

$copyright = '© ' . date( 'Y' ) . '. Plesni Centar Zagreb by Nicolas. Sva prava pridržana.';

if ( function_exists( 'get_field' ) ) {
    $acf_copyright = get_field( 'footer_copyright', 'option' );
    if ( ! empty( $acf_copyright ) ) {
        // Zamijeni {year} s trenutnom godinom
        $copyright = str_replace( '{year}', date( 'Y' ), $acf_copyright );
    }
}

// =============================================================================
// LOGO SIZE - Desktop i Mobile (kao u headeru)
// =============================================================================

$footer_logo_height_desktop = 50; // Default za footer
$footer_logo_height_mobile = 40;  // Default za footer

if ( function_exists( 'get_field' ) ) {
    $acf_footer_desktop = get_field( 'footer_logo_height_desktop', 'option' );
    $acf_footer_mobile = get_field( 'footer_logo_height_mobile', 'option' );
    
    if ( $acf_footer_desktop && is_numeric( $acf_footer_desktop ) ) {
        $footer_logo_height_desktop = intval( $acf_footer_desktop );
    }
    if ( $acf_footer_mobile && is_numeric( $acf_footer_mobile ) ) {
        $footer_logo_height_mobile = intval( $acf_footer_mobile );
    }
}

// Filteri za programatsko overridanje
if ( function_exists( 'apply_filters' ) ) {
    $footer_logo_height_desktop = apply_filters( 'pcz_footer_logo_height_desktop', $footer_logo_height_desktop );
    $footer_logo_height_mobile = apply_filters( 'pcz_footer_logo_height_mobile', $footer_logo_height_mobile );
}

// =============================================================================
// HELPER: SVG Ikone
// =============================================================================

function pcz_get_social_icon( $platform ) {
    $icons = array(
        'instagram' => '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
        'facebook'  => '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
        'youtube'   => '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
        'twitter'   => '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
        'linkedin'  => '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
    );
    
    return isset( $icons[ $platform ] ) ? $icons[ $platform ] : '';
}

?>

<!-- pcz Footer - Dynamic Logo Size -->
<style>
.pcz-footer__logo img {
    height: <?php echo esc_attr( $footer_logo_height_desktop ); ?>px !important;
    width: auto;
}
@media screen and (max-width: 768px) {
    .pcz-footer__logo img {
        height: <?php echo esc_attr( $footer_logo_height_mobile ); ?>px !important;
    }
}
</style>

<!-- pcz Contact Section -->
<section class="pcz-contact-section">
    <div class="pcz-contact-section__container">
        
        <!-- Info Column -->
        <div class="pcz-contact-section__info">
            
            <!-- Radno vrijeme -->
            <div class="pcz-contact-section__block">
                <h3>Radno vrijeme recepcije:</h3>
                <p>
                    <strong>PON - PET:</strong> <?php echo esc_html( $radno_vrijeme['pon_pet'] ); ?><br>
                    <strong>SUB:</strong> <?php echo esc_html( $radno_vrijeme['sub'] ); ?><br>
                    <strong>NED:</strong> <?php echo esc_html( $radno_vrijeme['ned'] ); ?>
                </p>
            </div>
            
            <!-- Kontakt -->
            <div class="pcz-contact-section__block">
                <h3>Naša adresa i kontakti:</h3>
                <p>
                    <strong>TEL:</strong> <a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $kontakt['telefon'] ) ); ?>"><?php echo esc_html( $kontakt['telefon'] ); ?></a><br>
                    <strong>MAIL:</strong> <a href="mailto:<?php echo esc_attr( $kontakt['email'] ); ?>"><?php echo esc_html( $kontakt['email'] ); ?></a><br>
                    <strong>ADRESA:</strong> <?php echo esc_html( $kontakt['adresa'] ); ?>
                </p>
            </div>
            
        </div>
        
        <!-- Map Column -->
        <div class="pcz-contact-section__map">
            <iframe 
                src="<?php echo esc_url( $google_maps_embed ); ?>"
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade"
                title="Lokacija Plesni Centar Zagreb">
            </iframe>
        </div>
        
    </div>
</section>

<!-- pcz Main Footer -->
<?php
// Atributi za footer element - omogućuje brand-awareness
$footer_attributes = array(
    'class' => 'pcz-footer',
    'id'    => 'pcz-footer',
);
if ( function_exists( 'apply_filters' ) ) {
    $footer_attributes = apply_filters( 'pcz_footer_attributes', $footer_attributes );
}

// Konvertiraj atribute u HTML string
$footer_attrs_html = '';
foreach ( $footer_attributes as $attr => $value ) {
    $footer_attrs_html .= ' ' . esc_attr( $attr ) . '="' . esc_attr( $value ) . '"';
}
?>
<footer<?php echo $footer_attrs_html; ?>>
    <div class="pcz-footer__container">
        
        <!-- Main Row: Logo + Nav + Social -->
        <div class="pcz-footer__main">
            
            <!-- Logo -->
            <a href="<?php echo esc_url( $site_url ); ?>" class="pcz-footer__logo">
                <?php if ( $logo_url ) : ?>
                    <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $site_name ); ?>">
                <?php else : ?>
                    <span class="pcz-footer__logo-text"><?php echo esc_html( $site_name ); ?></span>
                <?php endif; ?>
            </a>
            
            <!-- Navigation -->
            <?php if ( ! empty( $footer_nav ) ) : ?>
            <nav class="pcz-footer__nav" aria-label="Footer navigacija">
                <?php foreach ( $footer_nav as $item ) : ?>
                    <a href="<?php echo esc_url( $item['url'] ); ?>" class="pcz-footer__nav-link">
                        <?php echo esc_html( $item['label'] ); ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <?php endif; ?>
            
            <!-- Social Icons -->
            <?php if ( ! empty( $social_links ) ) : ?>
            <div class="pcz-footer__social">
                <?php foreach ( $social_links as $social ) : ?>
                    <?php 
                    $platform = isset( $social['platforma'] ) ? $social['platforma'] : '';
                    $url = isset( $social['url'] ) ? $social['url'] : '';
                    $icon = pcz_get_social_icon( $platform );
                    if ( $url && $icon ) :
                    ?>
                    <a href="<?php echo esc_url( $url ); ?>" 
                       class="pcz-footer__social-link" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       aria-label="<?php echo esc_attr( ucfirst( $platform ) ); ?>">
                        <?php echo $icon; // SVG je safe ?>
                    </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
        </div>
        
        <!-- Divider -->
        <div class="pcz-footer__divider"></div>
        
        <!-- Bottom Bar: Copyright + Legal Links -->
        <div class="pcz-footer__bottom">
            
            <p class="pcz-footer__copyright">
                <?php echo esc_html( $copyright ); ?>
            </p>
            
            <?php if ( ! empty( $legal_links ) ) : ?>
            <div class="pcz-footer__legal">
                <?php foreach ( $legal_links as $link ) : ?>
                    <a href="<?php echo esc_url( $link['url'] ); ?>" class="pcz-footer__legal-link">
                        <?php echo esc_html( $link['label'] ); ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
        </div>
        
    </div>
</footer>
<!-- /pcz Footer -->

