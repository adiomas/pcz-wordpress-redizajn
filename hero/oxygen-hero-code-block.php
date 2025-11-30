<?php
/**
 * pcz Hero Sekcija - Oxygen PHP Code Block v4.1 (Brand-Aware FIXED)
 * 
 * UPUTE: Kopiraj CIJELI sadržaj ovog fajla u Oxygen → Code Block → PHP tab
 * 
 * LOKACIJA FAJLOVA:
 * - Brand sustav: wp-content/uploads/pcz-brand/
 * - Hero: wp-content/uploads/pcz-hero/
 * 
 * NAPOMENA: Ovaj code block je SAMOSTALAN i ne ovisi o pzc-brand-init.php snippet-u.
 * Sve potrebno za brand switching je uključeno.
 * 
 * @version 4.1.0
 * @package pcz_Redizajn
 */

// ⚠️ CACHE BUST: Povećaj broj kad uploadaš nove fajlove!
$cache_bust = '20241129-v6-brand-fix';

$uploads = wp_upload_dir();

// =============================================================================
// 1. UČITAJ BRAND SUSTAV PRVO! (potrebno za CSS varijable)
// =============================================================================

$brand_path = trailingslashit( $uploads['basedir'] ) . 'pcz-brand/brand.php';
$brand_url = trailingslashit( $uploads['baseurl'] ) . 'pcz-brand/';

// Fallback lokacije za brand
if ( ! file_exists( $brand_path ) ) {
    $brand_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-brand/brand.php';
    $brand_url = trailingslashit( get_stylesheet_directory_uri() ) . 'pcz-brand/';
}

// Učitaj brand funkcije AKO postoje
$brand_loaded = false;
if ( file_exists( $brand_path ) && ! function_exists( 'pcz_get_current_brand' ) ) {
    require_once $brand_path;
    $brand_loaded = true;
}

// Dohvati trenutni brand
$current_brand_id = 'plesna-skola';
$current_brand = null;

if ( function_exists( 'pcz_get_current_brand' ) ) {
    $current_brand = pcz_get_current_brand();
    $current_brand_id = $current_brand['id'] ?? 'plesna-skola';
}

// =============================================================================
// 2. PUTANJE DO HERO FAJLOVA
// =============================================================================

$brand_hero_path = trailingslashit( $uploads['basedir'] ) . 'pcz-brand/brand-aware-hero.php';

// Fallback za brand-aware-hero
if ( ! file_exists( $brand_hero_path ) ) {
    $brand_hero_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-brand/brand-aware-hero.php';
}

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
// 3. UČITAJ FONTOVE
// =============================================================================

echo '<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600&display=swap" rel="stylesheet">';

// =============================================================================
// 4. BRAND CSS VARIJABLE (KRITIČNO za brand switching!)
// =============================================================================

// Output CSS varijable za trenutni brand INLINE
// Ovo osigurava da su boje ispravne čak i ako pzc-brand-init.php nije aktivan
if ( $current_brand ) {
    echo '<style id="pcz-brand-css-hero">';
    echo ':root {';
    echo '  /* pcz Brand: ' . esc_html( $current_brand['name'] ) . ' */';
    echo '  --pcz-primary: ' . esc_html( $current_brand['primary_color'] ) . ';';
    echo '  --pcz-primary-hover: ' . esc_html( $current_brand['primary_hover'] ) . ';';
    echo '  --pcz-secondary: ' . esc_html( $current_brand['secondary_color'] ) . ';';
    echo '  --pcz-accent: ' . esc_html( $current_brand['accent_color'] ) . ';';
    echo '  --pcz-gradient: ' . esc_html( $current_brand['gradient'] ) . ';';
    echo '}';
    echo '</style>';
    
    // Postavi data-brand na body putem JavaScript-a (za CSS selektore)
    echo '<script>';
    echo 'document.body.setAttribute("data-brand", "' . esc_js( $current_brand_id ) . '");';
    echo 'document.body.classList.add("pcz-brand-' . esc_js( $current_brand_id ) . '");';
    echo '</script>';
}

// =============================================================================
// 5. UČITAJ CSS (REDOSLIJED JE BITAN!)
// =============================================================================

// 1. PRVO: Hero CSS (base stilovi)
$css_file = dirname( $hero_path ) . '/hero.css';
if ( file_exists( $css_file ) ) {
    $css_version = $cache_bust . '-' . filemtime( $css_file );
    echo '<link rel="stylesheet" href="' . esc_url( $hero_url . 'hero.css?v=' . $css_version ) . '">';
}

// 2. NAKON: Brand CSS (brand-specific override stilovi)
// Mora biti POSLIJE hero.css da bi override radio!
$brand_css = dirname( $brand_path ) . '/brand.css';
if ( file_exists( $brand_css ) ) {
    $brand_css_version = $cache_bust . '-' . filemtime( $brand_css );
    echo '<link rel="stylesheet" href="' . esc_url( $brand_url . 'brand.css?v=' . $brand_css_version ) . '">';
}

// =============================================================================
// 6. UČITAJ PHP TEMPLATE
// =============================================================================

if ( file_exists( $brand_hero_path ) ) {
    // Brand-aware hero (uključuje originalni hero interno + filter za social)
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
// 7. BRAND JSON DATA (potrebno za brand.js)
// =============================================================================

// Output JSON data za JavaScript
if ( function_exists( 'pcz_get_brands_for_js' ) ) {
    $brand_js_data = pcz_get_brands_for_js();
    echo '<script id="pcz-brand-data" type="application/json">';
    echo json_encode( $brand_js_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    echo '</script>';
}

// =============================================================================
// 8. UČITAJ JAVASCRIPT
// =============================================================================

// Brand JS
$brand_js = dirname( $brand_path ) . '/brand.js';
if ( file_exists( $brand_js ) ) {
    $brand_js_version = $cache_bust . '-' . filemtime( $brand_js );
    echo '<script src="' . esc_url( $brand_url . 'brand.js?v=' . $brand_js_version ) . '"></script>';
}
?>
