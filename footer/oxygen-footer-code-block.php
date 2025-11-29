<?php
/**
 * pcz Custom Footer - Oxygen PHP Code Block v4.0 (Brand-Aware)
 * 
 * UPUTE: Kopiraj CIJELI sadržaj ovog fajla u Oxygen → Code Block → PHP tab
 * 
 * LOKACIJA FAJLOVA:
 * - Brand sustav: wp-content/uploads/pcz-brand/
 * - Footer: wp-content/uploads/pcz-footer/
 * 
 * @version 4.0.0
 * @package pcz_Redizajn
 */

// ⚠️ CACHE BUST: Povećaj broj kad uploadaš nove fajlove!
$cache_bust = '20241129-v4';

$uploads = wp_upload_dir();

// =============================================================================
// BRAND-AWARE FOOTER (PRIMARNO)
// =============================================================================

$brand_footer_path = trailingslashit( $uploads['basedir'] ) . 'pcz-brand/brand-aware-footer.php';
$brand_url = trailingslashit( $uploads['baseurl'] ) . 'pcz-brand/';

// Fallback lokacije za brand
if ( ! file_exists( $brand_footer_path ) ) {
    $brand_footer_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-brand/brand-aware-footer.php';
    $brand_url = trailingslashit( get_stylesheet_directory_uri() ) . 'pcz-brand/';
}

// =============================================================================
// ORIGINALNI FOOTER (FALLBACK)
// =============================================================================

$footer_path = trailingslashit( $uploads['basedir'] ) . 'pcz-footer/footer.php';
$footer_url = trailingslashit( $uploads['baseurl'] ) . 'pcz-footer/';

// Fallback na child temu
if ( ! file_exists( $footer_path ) ) {
    $footer_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-footer/footer.php';
    $footer_url = trailingslashit( get_stylesheet_directory_uri() ) . 'pcz-footer/';
}

// Fallback na WP_CONTENT_DIR
if ( ! file_exists( $footer_path ) && defined( 'WP_CONTENT_DIR' ) ) {
    $footer_path = trailingslashit( WP_CONTENT_DIR ) . 'uploads/pcz-footer/footer.php';
    $footer_url = trailingslashit( WP_CONTENT_URL ) . 'uploads/pcz-footer/';
}

// =============================================================================
// UČITAJ CSS
// =============================================================================

// Brand CSS (ako postoji i nije već učitan)
$brand_css = dirname( $brand_footer_path ) . '/brand.css';
if ( file_exists( $brand_css ) ) {
    $brand_css_version = $cache_bust . '-' . filemtime( $brand_css );
    echo '<link rel="stylesheet" href="' . esc_url( $brand_url . 'brand.css?v=' . $brand_css_version ) . '">';
}

// Footer CSS
$css_file = dirname( $footer_path ) . '/footer.css';
if ( file_exists( $css_file ) ) {
    $css_version = $cache_bust . '-' . filemtime( $css_file );
    echo '<link rel="stylesheet" href="' . esc_url( $footer_url . 'footer.css?v=' . $css_version ) . '">';
}

// =============================================================================
// UČITAJ PHP TEMPLATE
// =============================================================================

if ( file_exists( $brand_footer_path ) ) {
    // Brand-aware footer (uključuje originalni footer interno)
    include $brand_footer_path;
} elseif ( file_exists( $footer_path ) ) {
    // Fallback na originalni footer
    include $footer_path;
} elseif ( current_user_can( 'manage_options' ) ) {
    echo '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;margin:20px;">
        <strong>pcz Footer Error:</strong> Fajl nije pronađen.<br>
        Lokacija: ' . esc_html( $footer_path ) . '
    </div>';
}

// =============================================================================
// UČITAJ JAVASCRIPT (ako postoji)
// =============================================================================

// Brand JS (ako nije već učitan u headeru)
$brand_js = dirname( $brand_footer_path ) . '/brand.js';
if ( file_exists( $brand_js ) ) {
    // Provjeri nije li već uključen
    static $brand_js_loaded = false;
    if ( ! $brand_js_loaded ) {
        $brand_js_version = $cache_bust . '-' . filemtime( $brand_js );
        echo '<script src="' . esc_url( $brand_url . 'brand.js?v=' . $brand_js_version ) . '"></script>';
        $brand_js_loaded = true;
    }
}
?>
