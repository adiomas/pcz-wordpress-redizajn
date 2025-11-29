<?php
/**
 * pcz Brand-Aware Hero Wrapper
 * 
 * Ovaj fajl je wrapper oko originalne hero komponente koji
 * omogućuje brand-specifične podatke (logo, boje, socijalne mreže).
 * 
 * NAPOMENA: Hero SADRŽAJ (naslov, podnaslov, slika) je ZAJEDNIČKI za oba branda.
 * Samo se boje i socijalne mreže razlikuju po brandu.
 * 
 * @package pcz_Redizajn
 * @since 4.0.0
 */

// Sprječava direktan pristup
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Učitaj brand sustav ako nije već učitan
if ( ! function_exists( 'pcz_get_current_brand' ) ) {
    $brand_file = dirname( __FILE__ ) . '/brand.php';
    if ( file_exists( $brand_file ) ) {
        require_once $brand_file;
    }
}

// =============================================================================
// BRAND-SPECIFIC SOCIAL LINKS
// =============================================================================

/**
 * Override socijalne mreže za hero na temelju aktivnog branda
 * 
 * Svaki brand ima svoje socijalne mreže definirane u ACF:
 * - Plesna Škola: ps_socijalne_mreze
 * - Sportski Klub: spk_socijalne_mreze
 */
add_filter( 'pcz_hero_social_links', 'pcz_brand_hero_social_override', 10, 1 );
function pcz_brand_hero_social_override( $social_links ) {
    if ( ! function_exists( 'pcz_get_current_brand_id' ) || ! function_exists( 'get_field' ) ) {
        return $social_links;
    }
    
    $brand_id = pcz_get_current_brand_id();
    
    // Dohvati socijalne mreže za aktivni brand
    if ( $brand_id === 'sportski-klub' ) {
        $brand_social = get_field( 'spk_socijalne_mreze', 'option' );
    } else {
        $brand_social = get_field( 'ps_socijalne_mreze', 'option' );
    }
    
    // Ako ima brand-specific social links, koristi ih
    if ( ! empty( $brand_social ) && is_array( $brand_social ) ) {
        $social_links = [];
        foreach ( $brand_social as $item ) {
            if ( ! empty( $item['url'] ) ) {
                $social_links[] = [
                    'ikona' => $item['platforma'] ?? 'instagram',
                    'url'   => $item['url'],
                ];
            }
        }
    }
    
    return $social_links;
}

// =============================================================================
// BRAND SWITCHER INJECTION
// =============================================================================

/**
 * Dodaj brand switcher nakon intro sekcije (ako je konfiguriran)
 */
add_action( 'pcz_after_hero_intro', 'pcz_inject_brand_switcher_after_hero' );
function pcz_inject_brand_switcher_after_hero() {
    if ( ! function_exists( 'get_field' ) ) {
        return;
    }
    
    // Provjeri je li switcher uključen
    $enabled = get_field( 'brand_switcher_enabled', 'option' );
    if ( ! $enabled ) {
        return;
    }
    
    $position = get_field( 'brand_switcher_position', 'option' );
    
    if ( $position === 'hero' ) {
        // Učitaj brand-switcher.php
        $switcher_file = dirname( __FILE__ ) . '/brand-switcher.php';
        if ( file_exists( $switcher_file ) ) {
            require_once $switcher_file;
            
            if ( function_exists( 'pcz_render_brand_switcher' ) ) {
                echo '<div class="pcz-hero__brand-switcher">';
                pcz_render_brand_switcher(); // Koristi ACF postavke automatski
                echo '</div>';
            }
        }
    }
}

// =============================================================================
// HERO TEMPLATE INCLUDE
// =============================================================================

// Uključi originalnu hero komponentu
// Pokušaj više lokacija za kompatibilnost

$hero_file = null;
$possible_paths = [];

// 1. Relativna putanja (za development/test)
$possible_paths[] = dirname( dirname( __FILE__ ) ) . '/hero/hero.php';

// 2. WordPress uploads - pcz-hero
if ( function_exists( 'wp_upload_dir' ) ) {
    $uploads = wp_upload_dir();
    $possible_paths[] = trailingslashit( $uploads['basedir'] ) . 'pcz-hero/hero.php';
}

// 3. Child theme
if ( function_exists( 'get_stylesheet_directory' ) ) {
    $possible_paths[] = trailingslashit( get_stylesheet_directory() ) . 'pcz-hero/hero.php';
}

// Pronađi prvi postojeći fajl
foreach ( $possible_paths as $path ) {
    if ( file_exists( $path ) ) {
        $hero_file = $path;
        break;
    }
}

if ( $hero_file ) {
    include $hero_file;
} else {
    if ( function_exists( 'current_user_can' ) && current_user_can( 'manage_options' ) ) {
        echo '<div style="background:#ff6b6b;color:white;padding:20px;text-align:center;">';
        echo 'Hero komponenta nije pronađena. Provjerene lokacije:<br>';
        foreach ( $possible_paths as $p ) {
            echo esc_html( $p ) . '<br>';
        }
        echo '</div>';
    }
}
