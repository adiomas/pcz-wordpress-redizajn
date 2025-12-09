<?php
/**
 * pcz "Izdvojeno iz ponude" - Highlights Sekcija
 * 
 * Prikazuje izdvojene kategorije tečajeva/disciplina
 * s atraktivnim slikama i CTA buttonom.
 * 
 * KORIŠTENJE:
 * 1. Direktno uključi u Oxygen Code Block
 * 2. Ili koristi [pcz_izdvojeno] shortcode (za Plesnu školu)
 * 3. Ili koristi [pcz_izdvojeno brand="sportski-klub"] shortcode (za Sportski klub)
 * 
 * ACF POLJA (u Site Settings):
 * 
 * Za Plesnu školu (prefix: izdvojeno_):
 * - izdvojeno_naslov (Text) - Naslov sekcije
 * - izdvojeno_kartice (Repeater)
 *   - naziv (Text) - Naziv kategorije
 *   - slika (Image) - Slika za karticu
 *   - link (URL) - Link na stranicu
 * - izdvojeno_cta_tekst (Text) - Tekst CTA buttona
 * - izdvojeno_cta_link (URL) - Link CTA buttona
 * 
 * Za Sportski klub (prefix: izdvojeno_sk_):
 * - izdvojeno_sk_naslov (Text) - Naslov sekcije
 * - izdvojeno_sk_kartice (Repeater)
 *   - naziv (Text) - Naziv discipline
 *   - slika (Image) - Slika za karticu
 *   - link (URL) - Link na stranicu
 * - izdvojeno_sk_cta_tekst (Text) - Tekst CTA buttona
 * - izdvojeno_sk_cta_link (URL) - Link CTA buttona
 * 
 * BRAND: Podržava oba branda (Plesna škola i Sportski klub)
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

$pcz_use_fallback = defined('pcz_TEST_ENVIRONMENT') && pcz_TEST_ENVIRONMENT === true;

if ( function_exists('apply_filters') ) {
    $pcz_use_fallback = apply_filters( 'pcz_izdvojeno_use_fallback', $pcz_use_fallback );
}

// =============================================================================
// BRAND DETEKCIJA
// =============================================================================

// Brand može biti proslijeđen kao varijabla (iz shortcode-a) ili auto-detektiran
if ( ! isset( $izdvojeno_brand ) || empty( $izdvojeno_brand ) ) {
    // Auto-detekcija branda
    if ( function_exists( 'pcz_get_current_brand_id' ) ) {
        $izdvojeno_brand = pcz_get_current_brand_id();
    } else {
        $izdvojeno_brand = 'plesna-skola'; // Default
    }
}

// Definiraj ACF field prefix ovisno o brandu
$acf_prefix = ( $izdvojeno_brand === 'sportski-klub' ) ? 'izdvojeno_sk_' : 'izdvojeno_';

// Default vrijednosti ovisno o brandu
$default_naslov = ( $izdvojeno_brand === 'sportski-klub' ) ? 'Sportske discipline' : 'Izdvojeno iz ponude';
$default_cta_tekst = ( $izdvojeno_brand === 'sportski-klub' ) ? 'VIDI VIŠE' : 'VIŠE O USLUGAMA';
$default_cta_link = ( $izdvojeno_brand === 'sportski-klub' ) ? '/sportski-klub/' : '/usluge/';

// =============================================================================
// DOHVAT PODATAKA IZ ACF
// =============================================================================

$naslov = $default_naslov;
$kartice = array();
$cta_tekst = $default_cta_tekst;
$cta_link = $default_cta_link;
$using_fallback = false;

// Dohvati podatke iz ACF
if ( function_exists( 'get_field' ) ) {
    // Naslov sekcije
    $acf_naslov = get_field( $acf_prefix . 'naslov', 'option' );
    if ( ! empty( $acf_naslov ) ) {
        $naslov = $acf_naslov;
    }
    
    // Kartice (Repeater)
    $acf_kartice = get_field( $acf_prefix . 'kartice', 'option' );
    if ( ! empty( $acf_kartice ) && is_array( $acf_kartice ) ) {
        foreach ( $acf_kartice as $item ) {
            // Dohvati sliku
            $slika_url = '';
            if ( ! empty( $item['slika'] ) ) {
                if ( is_array( $item['slika'] ) && isset( $item['slika']['url'] ) ) {
                    $slika_url = $item['slika']['url'];
                } elseif ( is_numeric( $item['slika'] ) ) {
                    $slika_url = wp_get_attachment_image_url( $item['slika'], 'large' );
                } else {
                    $slika_url = $item['slika'];
                }
            }
            
            $kartice[] = array(
                'naziv' => ! empty( $item['naziv'] ) ? $item['naziv'] : '',
                'slika' => $slika_url,
                'link'  => ! empty( $item['link'] ) ? $item['link'] : '#',
            );
        }
    }
    
    // CTA
    $acf_cta_tekst = get_field( $acf_prefix . 'cta_tekst', 'option' );
    if ( ! empty( $acf_cta_tekst ) ) {
        $cta_tekst = $acf_cta_tekst;
    }
    
    $acf_cta_link = get_field( $acf_prefix . 'cta_link', 'option' );
    if ( ! empty( $acf_cta_link ) ) {
        $cta_link = $acf_cta_link;
    }
}

// =============================================================================
// FALLBACK PODACI (za development/test)
// =============================================================================

if ( empty( $kartice ) && $pcz_use_fallback ) {
    $using_fallback = true;
    
    $fallback_data = array();
    
    // Koristi brand-specifični filter za fallback podatke
    $filter_name = ( $izdvojeno_brand === 'sportski-klub' ) 
        ? 'pcz_izdvojeno_sk_fallback_data' 
        : 'pcz_izdvojeno_fallback_data';
    
    if ( function_exists('apply_filters') ) {
        $fallback_data = apply_filters( $filter_name, $fallback_data );
    }
    
    $kartice = $fallback_data;
}

// =============================================================================
// PROVJERA - IZLAZ AKO NEMA SADRŽAJA
// =============================================================================

if ( empty( $kartice ) ) {
    if ( function_exists('current_user_can') && current_user_can( 'manage_options' ) ) {
        $admin_url = function_exists('admin_url') ? admin_url( 'admin.php?page=site-settings' ) : '#';
        $section_name = ( $izdvojeno_brand === 'sportski-klub' ) ? 'Sportske discipline' : 'Izdvojeno iz ponude';
        $tab_name = ( $izdvojeno_brand === 'sportski-klub' ) ? 'Izdvojeno SK' : 'Izdvojeno';
        $accent_color = ( $izdvojeno_brand === 'sportski-klub' ) ? '#FF6B00' : '#CB007C';
        
        echo '<div class="pcz-izdvojeno pcz-izdvojeno--empty pcz-izdvojeno--brand-' . esc_attr( $izdvojeno_brand ) . '" style="background: #4d4d4d; padding: 60px 20px; text-align: center;">';
        echo '<p style="color: white; font-size: 18px; margin: 0;">⚠️ <strong>' . esc_html( $section_name ) . '</strong> sekcija nema sadržaja.</p>';
        echo '<p style="color: rgba(255,255,255,0.9); font-size: 14px; margin-top: 10px;">';
        echo 'Dodajte kartice u <a href="' . esc_url( $admin_url ) . '" style="color: ' . esc_attr( $accent_color ) . '; text-decoration: underline;">Site Settings → ' . esc_html( $tab_name ) . '</a>';
        echo '</p></div>';
    }
    return;
}

// =============================================================================
// GENERIRANJE JEDINSTVENOG ID-a
// =============================================================================

$section_id = 'pcz-izdvojeno-' . ( function_exists( 'wp_rand' ) ? wp_rand( 1000, 9999 ) : mt_rand( 1000, 9999 ) );

// =============================================================================
// BRAND VISIBILITY CLASS
// =============================================================================

$section_visibility_class = isset( $visibility_class ) ? $visibility_class : 'pcz-izdvojeno--visible';

// Koristi brand koji je proslijeđen ili auto-detektiran
$brand_classes = ' pcz-izdvojeno--brand-' . $izdvojeno_brand;

?>

<!-- ==================== IZDVOJENO IZ PONUDE SEKCIJA ==================== -->
<?php 
$demo_tab_name = ( $izdvojeno_brand === 'sportski-klub' ) ? 'Izdvojeno SK' : 'Izdvojeno';
$demo_accent_color = ( $izdvojeno_brand === 'sportski-klub' ) ? '#FF6B00' : '#CB007C';
?>
<?php if ( $using_fallback && function_exists('current_user_can') && current_user_can( 'manage_options' ) ) : ?>
<div style="background: #fff3cd; border-left: 4px solid <?php echo esc_attr( $demo_accent_color ); ?>; padding: 12px 16px; margin: 0; font-size: 14px;">
    ⚠️ <strong>Demo mod:</strong> Prikazuju se primjeri podataka. 
    <a href="<?php echo esc_url( function_exists('admin_url') ? admin_url( 'admin.php?page=site-settings' ) : '#' ); ?>">Dodajte kartice u ACF → <?php echo esc_html( $demo_tab_name ); ?></a>
</div>
<?php endif; ?>

<section class="pcz-izdvojeno <?php echo esc_attr( $section_visibility_class . $brand_classes ); ?>" id="<?php echo esc_attr( $section_id ); ?>" data-brand="<?php echo esc_attr( $izdvojeno_brand ); ?>" data-fallback="<?php echo $using_fallback ? 'true' : 'false'; ?>">
    <div class="pcz-izdvojeno__container">
        
        <!-- Header -->
        <header class="pcz-izdvojeno__header">
            <h2 class="pcz-izdvojeno__title"><?php echo esc_html( $naslov ); ?></h2>
            <div class="pcz-izdvojeno__divider"></div>
        </header>
        
        <!-- Kartice Grid -->
        <div class="pcz-izdvojeno__grid">
            <?php foreach ( $kartice as $index => $kartica ) : ?>
            <article class="pcz-izdvojeno__card" data-index="<?php echo esc_attr( $index ); ?>">
                <?php if ( ! empty( $kartica['link'] ) && $kartica['link'] !== '#' ) : ?>
                <a href="<?php echo esc_url( $kartica['link'] ); ?>" class="pcz-izdvojeno__card-link">
                <?php endif; ?>
                    
                    <div class="pcz-izdvojeno__card-image-wrapper">
                        <?php if ( ! empty( $kartica['slika'] ) ) : ?>
                        <img 
                            src="<?php echo esc_url( $kartica['slika'] ); ?>" 
                            alt="<?php echo esc_attr( $kartica['naziv'] ); ?>"
                            class="pcz-izdvojeno__card-image"
                            loading="lazy"
                        >
                        <?php else : ?>
                        <div class="pcz-izdvojeno__card-placeholder">
                            <span>📷</span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <h3 class="pcz-izdvojeno__card-title">
                        <?php echo esc_html( $kartica['naziv'] ); ?>
                    </h3>
                    
                <?php if ( ! empty( $kartica['link'] ) && $kartica['link'] !== '#' ) : ?>
                </a>
                <?php endif; ?>
            </article>
            <?php endforeach; ?>
        </div>
        
        <!-- CTA Button -->
        <?php if ( ! empty( $cta_tekst ) ) : ?>
        <div class="pcz-izdvojeno__cta-wrapper">
            <a href="<?php echo esc_url( $cta_link ); ?>" class="pcz-izdvojeno__cta">
                <?php echo esc_html( $cta_tekst ); ?>
            </a>
        </div>
        <?php endif; ?>
        
    </div>
</section>
<!-- ==================== /IZDVOJENO IZ PONUDE SEKCIJA ==================== -->

