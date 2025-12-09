<?php
/**
 * pcz "Upoznajte nas" - Oxygen PHP Code Block
 * 
 * UPUTE: Kopiraj CIJELI sadržaj ovog fajla u Oxygen → Code Block → PHP tab
 * 
 * BRAND VISIBILITY:
 * - Ova sekcija se prikazuje SAMO za brand "Plesna Škola"
 * - Za Sportski Klub sekcija je skrivena CSS-om (omogućuje smooth transition)
 * 
 * @version 1.0.0
 * @package pcz_Redizajn
 */

// =============================================================================
// BRAND-SPECIFIC VISIBILITY
// =============================================================================

// Definiraj za koje brandove se sekcija prikazuje
$allowed_brands = [ 'plesna-skola' ];  // SAMO Plesna Škola

// Dohvati trenutni brand
$current_brand = function_exists( 'pcz_get_current_brand_id' ) 
    ? pcz_get_current_brand_id() 
    : 'plesna-skola';

// Provjeri treba li prikazati
$is_visible = in_array( $current_brand, $allowed_brands, true );

// CSS klasa za visibility (omogućuje smooth transition)
$visibility_class = $is_visible ? 'pcz-upoznajte--visible' : 'pcz-upoznajte--hidden';

// Opcija: Potpuno preskoči render za nevidljive brandove (štedi bandwidth)
// Odkomentiraj sljedeće ako NE želiš smooth transition:
// if ( ! $is_visible ) { return; }

// Dohvati putanje
$uploads = wp_upload_dir();
$upoznajte_path = trailingslashit( $uploads['basedir'] ) . 'pcz-upoznajte/upoznajte.php';
$upoznajte_url = trailingslashit( $uploads['baseurl'] ) . 'pcz-upoznajte/';

// Fallback na child temu
if ( ! file_exists( $upoznajte_path ) ) {
    $upoznajte_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-upoznajte/upoznajte.php';
    $upoznajte_url = trailingslashit( get_stylesheet_directory_uri() ) . 'pcz-upoznajte/';
}

// Fallback na WP_CONTENT_DIR
if ( ! file_exists( $upoznajte_path ) && defined( 'WP_CONTENT_DIR' ) ) {
    $upoznajte_path = trailingslashit( WP_CONTENT_DIR ) . 'uploads/pcz-upoznajte/upoznajte.php';
    $upoznajte_url = trailingslashit( WP_CONTENT_URL ) . 'uploads/pcz-upoznajte/';
}

// CSS
$css_file = dirname( $upoznajte_path ) . '/upoznajte.css';
$css_url = $upoznajte_url . 'upoznajte.css';
if ( file_exists( $css_file ) ) {
    echo '<link rel="stylesheet" href="' . esc_url( $css_url ) . '?v=' . filemtime( $css_file ) . '">';
}

// PHP Template
if ( file_exists( $upoznajte_path ) ) {
    include $upoznajte_path;
} else {
    if ( current_user_can( 'manage_options' ) ) {
        echo '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;margin:20px;">
            <strong>pcz Upoznajte Error:</strong> Fajl nije pronađen.<br>
            Lokacija: ' . esc_html( $upoznajte_path ) . '
        </div>';
    }
}
?>

