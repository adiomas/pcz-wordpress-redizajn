<?php
/**
 * pcz "Dodatne Informacije" - Kompletno Rješenje
 * 
 * Sadrži: Grid ikona s linkovima na dodatne informacije
 * SAMO za brand "Sportski Klub"
 * 
 * KORIŠTENJE:
 * 1. Direktno uključi u Oxygen Code Block
 * 2. Ili koristi [pcz_dodatne_info] shortcode
 * 
 * ACF POLJA (u Site Settings):
 * - spk_dodatne_naslov (Text)
 * - spk_dodatne_stavke (Repeater)
 *   - ikona (Text) - klasa ikone ili emoji
 *   - naziv (Text) - label ispod ikone
 *   - stranica (Page Link) - odabir stranice
 * 
 * BRAND VISIBILITY:
 * - Ova sekcija se prikazuje SAMO za brand "Sportski Klub"
 * 
 * @package pcz_Redizajn
 * @since 1.0.0
 */

// Sprječava direktan pristup
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// =============================================================================
// KONFIGURACIJA
// =============================================================================

/**
 * Kontrolira da li se koriste fallback podaci
 */
$pcz_use_fallback = defined('pcz_TEST_ENVIRONMENT') && pcz_TEST_ENVIRONMENT === true;

if ( function_exists('apply_filters') ) {
    $pcz_use_fallback = apply_filters( 'pcz_dodatne_info_use_fallback', $pcz_use_fallback );
}

// =============================================================================
// DOHVAT PODATAKA IZ ACF
// =============================================================================

$naslov = 'DODATNE INFORMACIJE';
$stavke = array();
$using_fallback = false;

// Dohvati podatke iz ACF
if ( function_exists( 'get_field' ) ) {
    // Naslov sekcije
    $acf_naslov = get_field( 'spk_dodatne_naslov', 'option' );
    if ( ! empty( $acf_naslov ) ) {
        $naslov = $acf_naslov;
    }
    
    // Stavke (Repeater)
    $acf_stavke = get_field( 'spk_dodatne_stavke', 'option' );
    if ( ! empty( $acf_stavke ) && is_array( $acf_stavke ) ) {
        foreach ( $acf_stavke as $item ) {
            // Page link može vratiti URL ili post object
            $url = '';
            if ( ! empty( $item['stranica'] ) ) {
                if ( is_numeric( $item['stranica'] ) ) {
                    $url = get_permalink( $item['stranica'] );
                } else {
                    $url = $item['stranica'];
                }
            }
            
            // Dohvati ikonu - može biti SVG, emoji ili Font Awesome klasa
            $ikona = ! empty( $item['ikona'] ) ? $item['ikona'] : '📄';
            
            // Dohvati custom SVG ako postoji
            $custom_svg = ! empty( $item['custom_svg'] ) ? $item['custom_svg'] : '';
            
            if ( ! empty( $item['naziv'] ) ) {
                $stavke[] = array(
                    'ikona'      => $ikona,
                    'custom_svg' => $custom_svg,
                    'naziv'      => $item['naziv'],
                    'url'        => $url,
                );
            }
        }
    }
}

// =============================================================================
// FALLBACK PODACI (za development/test)
// =============================================================================

if ( empty( $stavke ) && $pcz_use_fallback ) {
    $using_fallback = true;
    
    $fallback_data = array();
    
    if ( function_exists('apply_filters') ) {
        $fallback_data = apply_filters( 'pcz_dodatne_info_fallback_data', $fallback_data );
    }
    
    if ( ! empty( $fallback_data ) && isset( $fallback_data['stavke'] ) ) {
        $stavke = $fallback_data['stavke'];
    }
}

// =============================================================================
// PROVJERA - IZLAZ AKO NEMA SADRŽAJA
// =============================================================================

if ( empty( $stavke ) ) {
    // Samo admin vidi poruku
    if ( function_exists('current_user_can') && current_user_can( 'manage_options' ) ) {
        $admin_url = function_exists('admin_url') ? admin_url( 'admin.php?page=site-settings' ) : '#';
        echo '<div class="pcz-dodatne-info pcz-dodatne-info--empty" style="background: linear-gradient(180deg, #FF6B00 0%, #ffa366 50%, #f5f5f5 100%); padding: 60px 20px; text-align: center;">';
        echo '<p style="color: white; font-size: 18px; margin: 0;">⚠️ <strong>Dodatne Informacije</strong> sekcija nema sadržaja.</p>';
        echo '<p style="color: rgba(255,255,255,0.9); font-size: 14px; margin-top: 10px;">';
        echo 'Dodajte stavke u <a href="' . esc_url( $admin_url ) . '" style="color: white; text-decoration: underline;">Site Settings → Dodatne Informacije</a>';
        echo '</p></div>';
    }
    return;
}

// =============================================================================
// GENERIRANJE JEDINSTVENOG ID-a
// =============================================================================

$section_id = 'pcz-dodatne-info-' . ( function_exists( 'wp_rand' ) ? wp_rand( 1000, 9999 ) : mt_rand( 1000, 9999 ) );

// =============================================================================
// BRAND VISIBILITY CLASS (postavlja oxygen-dodatne-info-code-block.php)
// =============================================================================

$section_visibility_class = isset( $visibility_class ) ? $visibility_class : 'pcz-dodatne-info--visible';

// Dodatna provjera: Ako je brand awareness uključen, dodaj klase
$brand_classes = '';
if ( function_exists( 'pcz_get_current_brand_id' ) ) {
    $brand_classes = ' pcz-dodatne-info--brand-' . pcz_get_current_brand_id();
}

// =============================================================================
// IKONE - SVG definicije
// =============================================================================

$svg_icons = array(
    'cjenik' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v12"/><path d="M8 10h8"/><path d="M8 14h8"/></svg>',
    'kontakt' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',
    'lokacija' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
    'trofej' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>',
    'kamp' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 22h20L12 2z"/><path d="M12 22V12"/><path d="M8 18l4-6 4 6"/></svg>',
    'faq' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
    'podrska' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
    'euro' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M7 12h7"/><path d="M7 9h7"/><path d="M15 6a5 5 0 1 0 0 12"/></svg>',
);

/**
 * Helper funkcija za dohvat SVG ikone
 */
function pcz_get_dodatne_icon( $icon_key, $svg_icons ) {
    // Ako je SVG kod
    if ( strpos( $icon_key, '<svg' ) !== false ) {
        return $icon_key;
    }
    
    // Ako je definirana ikona
    $key = strtolower( trim( $icon_key ) );
    if ( isset( $svg_icons[ $key ] ) ) {
        return $svg_icons[ $key ];
    }
    
    // Ako je emoji ili tekst
    return '<span class="pcz-dodatne-info__icon-text">' . esc_html( $icon_key ) . '</span>';
}

?>

<!-- ==================== DODATNE INFORMACIJE SEKCIJA ==================== -->
<?php if ( $using_fallback && function_exists('current_user_can') && current_user_can( 'manage_options' ) ) : ?>
<div style="background: #fff3cd; border-left: 4px solid #FF6B00; padding: 12px 16px; margin: 0; font-size: 14px;">
    ⚠️ <strong>Demo mod:</strong> Prikazuju se primjeri podataka. 
    <a href="<?php echo esc_url( function_exists('admin_url') ? admin_url( 'admin.php?page=site-settings' ) : '#' ); ?>">Dodajte sadržaj u ACF</a>.
</div>
<?php endif; ?>

<section class="pcz-dodatne-info <?php echo esc_attr( $section_visibility_class . $brand_classes ); ?>" id="<?php echo esc_attr( $section_id ); ?>" data-fallback="<?php echo $using_fallback ? 'true' : 'false'; ?>">
    <div class="pcz-dodatne-info__container">
        
        <!-- Header -->
        <header class="pcz-dodatne-info__header">
            <h2 class="pcz-dodatne-info__title"><?php echo esc_html( $naslov ); ?></h2>
            <div class="pcz-dodatne-info__divider"></div>
        </header>
        
        <!-- Grid -->
        <nav class="pcz-dodatne-info__grid" aria-label="Dodatne informacije navigacija">
            <?php foreach ( $stavke as $index => $item ) : ?>
            <a href="<?php echo esc_url( $item['url'] ); ?>" class="pcz-dodatne-info__item" data-index="<?php echo esc_attr( $index ); ?>">
                <div class="pcz-dodatne-info__icon-wrapper">
                    <?php if ( ! empty( $item['custom_svg'] ) ) : ?>
                        <?php echo $item['custom_svg']; // Custom SVG already escaped or trusted ?>
                    <?php else : ?>
                        <?php echo pcz_get_dodatne_icon( $item['ikona'], $svg_icons ); ?>
                    <?php endif; ?>
                </div>
                <span class="pcz-dodatne-info__label"><?php echo esc_html( $item['naziv'] ); ?></span>
            </a>
            <?php endforeach; ?>
        </nav>
        
    </div>
</section>
<!-- ==================== /DODATNE INFORMACIJE SEKCIJA ==================== -->



