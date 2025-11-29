<?php
/**
 * pcz Hero Sekcija - Oxygen PHP Code Block v4.0 (Brand-Aware)
 * 
 * UPUTE: Kopiraj CIJELI sadržaj ovog fajla u Oxygen → Code Block → PHP tab
 * 
 * LOKACIJA FAJLOVA:
 * - Brand sustav: wp-content/uploads/pcz-brand/
 * - Hero: wp-content/uploads/pcz-hero/
 * 
 * @version 4.0.0
 * @package pcz_Redizajn
 */

// ⚠️ CACHE BUST: Povećaj broj kad uploadaš nove fajlove!
$cache_bust = '20241129-v4';

$uploads = wp_upload_dir();

// =============================================================================
// BRAND-AWARE HERO (PRIMARNO)
// =============================================================================

$brand_hero_path = trailingslashit( $uploads['basedir'] ) . 'pcz-brand/brand-aware-hero.php';
$brand_url = trailingslashit( $uploads['baseurl'] ) . 'pcz-brand/';

// Fallback lokacije za brand
if ( ! file_exists( $brand_hero_path ) ) {
    $brand_hero_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-brand/brand-aware-hero.php';
    $brand_url = trailingslashit( get_stylesheet_directory_uri() ) . 'pcz-brand/';
}

// =============================================================================
// ORIGINALNI HERO (FALLBACK)
// =============================================================================

$hero_path = trailingslashit( $uploads['basedir'] ) . 'pcz-hero/hero.php';
$hero_url = trailingslashit( $uploads['baseurl'] ) . 'pcz-hero/';

// Fallback na child temu
if ( ! file_exists( $hero_path ) ) {
    $hero_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-hero/hero.php';
    $hero_url = trailingslashit( get_stylesheet_directory_uri() ) . 'pcz-hero/';
}

// Fallback na WP_CONTENT_DIR
if ( ! file_exists( $hero_path ) && defined( 'WP_CONTENT_DIR' ) ) {
    $hero_path = trailingslashit( WP_CONTENT_DIR ) . 'uploads/pcz-hero/hero.php';
    $hero_url = trailingslashit( WP_CONTENT_URL ) . 'uploads/pcz-hero/';
}

// =============================================================================
// UČITAJ FONTOVE
// =============================================================================

// Dancing Script font za tagline
echo '<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600&display=swap" rel="stylesheet">';

// =============================================================================
// UČITAJ CSS
// =============================================================================

// Brand CSS (ako postoji i nije već učitan)
$brand_css = dirname( $brand_hero_path ) . '/brand.css';
if ( file_exists( $brand_css ) ) {
    $brand_css_version = $cache_bust . '-' . filemtime( $brand_css );
    echo '<link rel="stylesheet" href="' . esc_url( $brand_url . 'brand.css?v=' . $brand_css_version ) . '">';
}

// Hero CSS
$css_file = dirname( $hero_path ) . '/hero.css';
if ( file_exists( $css_file ) ) {
    $css_version = $cache_bust . '-' . filemtime( $css_file );
    echo '<link rel="stylesheet" href="' . esc_url( $hero_url . 'hero.css?v=' . $css_version ) . '">';
}

// =============================================================================
// UČITAJ PHP TEMPLATE
// =============================================================================

if ( file_exists( $brand_hero_path ) ) {
    // Brand-aware hero (uključuje originalni hero interno)
    include $brand_hero_path;
} elseif ( file_exists( $hero_path ) ) {
    // Fallback na originalni hero
    include $hero_path;
} elseif ( current_user_can( 'manage_options' ) ) {
    echo '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;margin:20px;">
        <strong>pcz Hero Error:</strong> Fajl nije pronađen.<br>
        Lokacija: ' . esc_html( $hero_path ) . '<br><br>
        <small>Uploadaj fajlove u: <code>wp-content/uploads/pcz-hero/</code></small>
    </div>';
}

// =============================================================================
// UČITAJ JAVASCRIPT (ako postoji)
// =============================================================================

// Brand JS
$brand_js = dirname( $brand_hero_path ) . '/brand.js';
if ( file_exists( $brand_js ) ) {
    $brand_js_version = $cache_bust . '-' . filemtime( $brand_js );
    echo '<script src="' . esc_url( $brand_url . 'brand.js?v=' . $brand_js_version ) . '"></script>';
}
?>
