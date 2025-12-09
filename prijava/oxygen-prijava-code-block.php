<?php
/**
 * PCZ "Prijava" Sekcija - Oxygen PHP Code Block
 * 
 * UPUTE: Kopiraj CIJELI sadržaj ovog fajla u Oxygen → Code Block → PHP tab
 * 
 * BRAND AWARENESS:
 * - Sekcija se prikazuje za OBA branda (Plesna škola i Sportski klub)
 * - Automatski se prilagođava: naslov, boje, opcije u formi
 * - Hidden field "brand" automatski se popunjava u Gravity Forms
 * 
 * GRAVITY FORMS SETUP:
 * 1. Kreiraj formu u Gravity Forms s poljima:
 *    - Ime i Prezime (Text, required)
 *    - Telefon (Phone, required)
 *    - Email (Email, required)
 *    - Tečaj (Select/Dropdown)
 *    - Sam/a (Select/Dropdown)
 *    - Newsletter (Checkbox)
 *    - Suglasnost (Consent)
 *    - Brand (Hidden, default value: {brand} - automatski se popunjava)
 * 
 * 2. U ACF Site Settings upiši Form ID
 * 
 * @version 1.0.0
 * @package PCZ_Redizajn
 */

// =============================================================================
// BRAND-SPECIFIC VISIBILITY (ova sekcija je za oba branda)
// =============================================================================

// Sekcija se prikazuje za sve brandove
$allowed_brands = [ 'plesna-skola', 'sportski-klub' ];

// Dohvati trenutni brand
$current_brand = function_exists( 'pcz_get_current_brand_id' ) 
    ? pcz_get_current_brand_id() 
    : 'plesna-skola';

// Provjeri treba li prikazati
$is_visible = in_array( $current_brand, $allowed_brands, true );

// CSS klasa za visibility
$visibility_class = $is_visible ? 'pcz-prijava--visible' : 'pcz-prijava--hidden';

// =============================================================================
// DOHVATI PUTANJE
// =============================================================================

$uploads = wp_upload_dir();
$prijava_path = trailingslashit( $uploads['basedir'] ) . 'pcz-prijava/prijava.php';
$prijava_url = trailingslashit( $uploads['baseurl'] ) . 'pcz-prijava/';

// Fallback na child temu
if ( ! file_exists( $prijava_path ) ) {
    $prijava_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-prijava/prijava.php';
    $prijava_url = trailingslashit( get_stylesheet_directory_uri() ) . 'pcz-prijava/';
}

// Fallback na WP_CONTENT_DIR
if ( ! file_exists( $prijava_path ) && defined( 'WP_CONTENT_DIR' ) ) {
    $prijava_path = trailingslashit( WP_CONTENT_DIR ) . 'uploads/pcz-prijava/prijava.php';
    $prijava_url = trailingslashit( WP_CONTENT_URL ) . 'uploads/pcz-prijava/';
}

// =============================================================================
// UČITAJ CSS
// =============================================================================

$css_file = dirname( $prijava_path ) . '/prijava.css';
$css_url = $prijava_url . 'prijava.css';
if ( file_exists( $css_file ) ) {
    echo '<link rel="stylesheet" href="' . esc_url( $css_url ) . '?v=' . filemtime( $css_file ) . '">';
}

// =============================================================================
// UČITAJ PHP TEMPLATE
// =============================================================================

if ( file_exists( $prijava_path ) ) {
    include $prijava_path;
} else {
    if ( current_user_can( 'manage_options' ) ) {
        echo '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;margin:20px;">
            <strong>PCZ Prijava Error:</strong> Fajl nije pronađen.<br>
            Lokacija: ' . esc_html( $prijava_path ) . '
        </div>';
    }
}
?>

