<?php
/**
 * pcz "Izdvojeno iz ponude" - Oxygen PHP Code Block
 * 
 * UPUTE: Kopiraj CIJELI sadržaj ovog fajla u Oxygen → Code Block → PHP tab
 * 
 * BRAND SUPPORT:
 * - Ova sekcija podržava oba branda: "Plesna Škola" i "Sportski Klub"
 * - Brand se automatski detektira, ali možete ga i eksplicitno postaviti
 * 
 * KONFIGURACIJA:
 * - Za Plesnu školu: $izdvojeno_brand = 'plesna-skola';
 * - Za Sportski klub: $izdvojeno_brand = 'sportski-klub';
 * - Auto-detekcija: $izdvojeno_brand = ''; (default)
 * 
 * @version 2.0.0
 * @package pcz_Redizajn
 */

// =============================================================================
// BRAND KONFIGURACIJA
// =============================================================================

// Postavi brand eksplicitno ili ostavi prazno za auto-detekciju
// Opcije: 'plesna-skola', 'sportski-klub', '' (auto)
$izdvojeno_brand = ''; // <-- PROMIJENI OVO ZA DRUGI BRAND

// Auto-detekcija ako nije postavljen
if ( empty( $izdvojeno_brand ) ) {
    $izdvojeno_brand = function_exists( 'pcz_get_current_brand_id' ) 
        ? pcz_get_current_brand_id() 
        : 'plesna-skola';
}

// Validiraj brand
$allowed_brands = array( 'plesna-skola', 'sportski-klub' );
if ( ! in_array( $izdvojeno_brand, $allowed_brands, true ) ) {
    $izdvojeno_brand = 'plesna-skola';
}

// =============================================================================
// VISIBILITY
// =============================================================================

// CSS klasa za visibility
$visibility_class = 'pcz-izdvojeno--visible';

// =============================================================================
// DOHVATI PUTANJE
// =============================================================================

$uploads = wp_upload_dir();
$izdvojeno_path = trailingslashit( $uploads['basedir'] ) . 'pcz-izdvojeno/izdvojeno.php';
$izdvojeno_url = trailingslashit( $uploads['baseurl'] ) . 'pcz-izdvojeno/';

// Fallback na child temu
if ( ! file_exists( $izdvojeno_path ) ) {
    $izdvojeno_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-izdvojeno/izdvojeno.php';
    $izdvojeno_url = trailingslashit( get_stylesheet_directory_uri() ) . 'pcz-izdvojeno/';
}

// Fallback na WP_CONTENT_DIR
if ( ! file_exists( $izdvojeno_path ) && defined( 'WP_CONTENT_DIR' ) ) {
    $izdvojeno_path = trailingslashit( WP_CONTENT_DIR ) . 'uploads/pcz-izdvojeno/izdvojeno.php';
    $izdvojeno_url = trailingslashit( WP_CONTENT_URL ) . 'uploads/pcz-izdvojeno/';
}

// =============================================================================
// UČITAJ CSS
// =============================================================================

$css_file = dirname( $izdvojeno_path ) . '/izdvojeno.css';
$css_url = $izdvojeno_url . 'izdvojeno.css';
if ( file_exists( $css_file ) ) {
    echo '<link rel="stylesheet" href="' . esc_url( $css_url ) . '?v=' . filemtime( $css_file ) . '">';
}

// =============================================================================
// UČITAJ PHP TEMPLATE
// =============================================================================

if ( file_exists( $izdvojeno_path ) ) {
    include $izdvojeno_path;
} else {
    if ( current_user_can( 'manage_options' ) ) {
        echo '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;margin:20px;">
            <strong>pcz Izdvojeno Error:</strong> Fajl nije pronađen.<br>
            Lokacija: ' . esc_html( $izdvojeno_path ) . '
        </div>';
    }
}
?>
