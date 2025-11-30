<?php
/**
 * pcz "Poznati o PCZ-u" - Oxygen PHP Code Block
 * 
 * UPUTE: Kopiraj CIJELI sadržaj ovog fajla u Oxygen → Code Block → PHP tab
 * 
 * BRAND VISIBILITY:
 * - Ova sekcija se prikazuje SAMO za brand "Plesna Škola"
 * - Za Sportski Klub sekcija je skrivena CSS-om (omogućuje smooth transition)
 * 
 * @version 1.1.0
 * @package pcz_Redizajn
 */

// =============================================================================
// BRAND-SPECIFIC VISIBILITY
// =============================================================================

// Definiraj za koje brandove se sekcija prikazuje
$allowed_brands = [ 'plesna-skola' ];  // Dodaj 'sportski-klub' ako želiš prikazati za oba

// Dohvati trenutni brand
$current_brand = function_exists( 'pcz_get_current_brand_id' ) 
    ? pcz_get_current_brand_id() 
    : 'plesna-skola';

// Provjeri treba li prikazati
$is_visible = in_array( $current_brand, $allowed_brands, true );

// CSS klasa za visibility (omogućuje smooth transition)
$visibility_class = $is_visible ? 'pcz-poznati--visible' : 'pcz-poznati--hidden';

// Opcija: Potpuno preskoči render za nevidljive brandove (štedi bandwidth)
// Odkomentiraj sljedeće ako NE želiš smooth transition:
// if ( ! $is_visible ) { return; }

// Dohvati putanje
$uploads = wp_upload_dir();
$poznati_path = trailingslashit( $uploads['basedir'] ) . 'pcz-poznati/poznati.php';
$poznati_url = trailingslashit( $uploads['baseurl'] ) . 'pcz-poznati/';

// Fallback na child temu
if ( ! file_exists( $poznati_path ) ) {
    $poznati_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-poznati/poznati.php';
    $poznati_url = trailingslashit( get_stylesheet_directory_uri() ) . 'pcz-poznati/';
}

// Fallback na WP_CONTENT_DIR
if ( ! file_exists( $poznati_path ) && defined( 'WP_CONTENT_DIR' ) ) {
    $poznati_path = trailingslashit( WP_CONTENT_DIR ) . 'uploads/pcz-poznati/poznati.php';
    $poznati_url = trailingslashit( WP_CONTENT_URL ) . 'uploads/pcz-poznati/';
}

// CSS
$css_file = dirname( $poznati_path ) . '/poznati.css';
$css_url = $poznati_url . 'poznati.css';
if ( file_exists( $css_file ) ) {
    echo '<link rel="stylesheet" href="' . esc_url( $css_url ) . '?v=' . filemtime( $css_file ) . '">';
}

// PHP Template
if ( file_exists( $poznati_path ) ) {
    include $poznati_path;
} else {
    if ( current_user_can( 'manage_options' ) ) {
        echo '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;margin:20px;">
            <strong>pcz Poznati Error:</strong> Fajl nije pronađen.<br>
            Lokacija: ' . esc_html( $poznati_path ) . '
        </div>';
    }
}

// JavaScript (za slider)
$js_file = dirname( $poznati_path ) . '/poznati.js';
$js_url = $poznati_url . 'poznati.js';
if ( file_exists( $js_file ) ) {
    echo '<script src="' . esc_url( $js_url ) . '?v=' . filemtime( $js_file ) . '" defer></script>';
}
?>

