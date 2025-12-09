<?php
/**
 * pcz Header - Oxygen PHP Code Block v4.0 (Brand-Aware)
 * 
 * UPUTE: Kopiraj CIJELI sadržaj ovog fajla u Oxygen → Code Block → PHP tab
 * 
 * LOKACIJA FAJLOVA:
 * - Brand sustav: wp-content/uploads/pcz-brand/
 * - Header: wp-content/uploads/pcz-header/
 * 
 * @version 4.0.0
 * @package pcz_Redizajn
 */

// ⚠️ CACHE BUST: Povećaj broj kad uploadaš nove fajlove!
$cache_bust = '20241129-v4';

$uploads = wp_upload_dir();

// =============================================================================
// BRAND-AWARE HEADER (PRIMARNO)
// =============================================================================

$brand_header_path = $uploads['basedir'] . '/pcz-brand/brand-aware-header.php';
$brand_url = $uploads['baseurl'] . '/pcz-brand/';

// Fallback lokacije za brand
if ( ! file_exists( $brand_header_path ) ) {
    $brand_header_path = get_stylesheet_directory() . '/pcz-brand/brand-aware-header.php';
    $brand_url = get_stylesheet_directory_uri() . '/pcz-brand/';
}

// =============================================================================
// ORIGINALNI HEADER (FALLBACK)
// =============================================================================

$header_path = $uploads['basedir'] . '/pcz-header/mega-menu.php';
$header_url = $uploads['baseurl'] . '/pcz-header/';

if ( ! file_exists( $header_path ) ) {
    $header_path = get_stylesheet_directory() . '/pcz-header/mega-menu.php';
    $header_url = get_stylesheet_directory_uri() . '/pcz-header/';
}

// =============================================================================
// UČITAJ CSS
// =============================================================================

// Brand CSS (ako postoji)
$brand_css = dirname( $brand_header_path ) . '/brand.css';
if ( file_exists( $brand_css ) ) {
    $brand_css_version = $cache_bust . '-' . filemtime( $brand_css );
    echo '<link rel="stylesheet" href="' . esc_url( $brand_url . 'brand.css?v=' . $brand_css_version ) . '">';
}

// Header CSS
$css = dirname( $header_path ) . '/mega-menu.css';
if ( file_exists( $css ) ) {
    $css_version = $cache_bust . '-' . filemtime( $css );
    echo '<link rel="stylesheet" href="' . esc_url( $header_url . 'mega-menu.css?v=' . $css_version ) . '">';
}

// Mobile Menu V2 CSS (override stilovi za mobile)
$mobile_css = dirname( $header_path ) . '/mega-menu-mobile-v2.css';
if ( file_exists( $mobile_css ) ) {
    $mobile_css_version = $cache_bust . '-' . filemtime( $mobile_css );
    echo '<link rel="stylesheet" href="' . esc_url( $header_url . 'mega-menu-mobile-v2.css?v=' . $mobile_css_version ) . '">';
}

// =============================================================================
// UČITAJ PHP TEMPLATE
// =============================================================================

if ( file_exists( $brand_header_path ) ) {
    // Brand-aware header (uključuje originalni header interno)
    include $brand_header_path;
} elseif ( file_exists( $header_path ) ) {
    // Fallback na originalni header
    include $header_path;
} elseif ( current_user_can( 'manage_options' ) ) {
    echo '<p style="color:red;padding:20px;">pcz Header: Fajl nije pronađen - ' . esc_html($header_path) . '</p>';
}

// =============================================================================
// UČITAJ JAVASCRIPT
// =============================================================================

// Brand JS (ako postoji)
$brand_js = dirname( $brand_header_path ) . '/brand.js';
if ( file_exists( $brand_js ) ) {
    $brand_js_version = $cache_bust . '-' . filemtime( $brand_js );
    echo '<script src="' . esc_url( $brand_url . 'brand.js?v=' . $brand_js_version ) . '"></script>';
}

// Header JS
$js = dirname( $header_path ) . '/mega-menu.js';
if ( file_exists( $js ) ) {
    $js_version = $cache_bust . '-' . filemtime( $js );
    echo '<script src="' . esc_url( $header_url . 'mega-menu.js?v=' . $js_version ) . '"></script>';
}
?>
