<?php
/**
 * pcz "Dodatne Informacije" - Oxygen PHP Code Block
 * 
 * UPUTE: Kopiraj CIJELI sadržaj ovog fajla u Oxygen → Code Block → PHP tab
 * 
 * BRAND VISIBILITY:
 * - Ova sekcija se prikazuje SAMO za brand "Sportski Klub"
 * - Za Plesna Škola sekcija je skrivena CSS-om (omogućuje smooth transition)
 * 
 * @version 1.0.0
 * @package pcz_Redizajn
 */

// =============================================================================
// BRAND-SPECIFIC VISIBILITY
// =============================================================================

// Definiraj za koje brandove se sekcija prikazuje
$allowed_brands = [ 'sportski-klub' ];  // SAMO za Sportski Klub

// Dohvati trenutni brand
$current_brand = function_exists( 'pcz_get_current_brand_id' ) 
    ? pcz_get_current_brand_id() 
    : 'plesna-skola';

// Provjeri treba li prikazati
$is_visible = in_array( $current_brand, $allowed_brands, true );

// CSS klasa za visibility (omogućuje smooth transition)
$visibility_class = $is_visible ? 'pcz-dodatne-info--visible' : 'pcz-dodatne-info--hidden';

// Opcija: Potpuno preskoči render za nevidljive brandove (štedi bandwidth)
// Odkomentiraj sljedeće ako NE želiš smooth transition:
// if ( ! $is_visible ) { return; }

// Dohvati putanje
$uploads = wp_upload_dir();
$dodatne_path = trailingslashit( $uploads['basedir'] ) . 'pcz-dodatne-info/dodatne-info.php';
$dodatne_url = trailingslashit( $uploads['baseurl'] ) . 'pcz-dodatne-info/';

// Fallback na child temu
if ( ! file_exists( $dodatne_path ) ) {
    $dodatne_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-dodatne-info/dodatne-info.php';
    $dodatne_url = trailingslashit( get_stylesheet_directory_uri() ) . 'pcz-dodatne-info/';
}

// Fallback na WP_CONTENT_DIR
if ( ! file_exists( $dodatne_path ) && defined( 'WP_CONTENT_DIR' ) ) {
    $dodatne_path = trailingslashit( WP_CONTENT_DIR ) . 'uploads/pcz-dodatne-info/dodatne-info.php';
    $dodatne_url = trailingslashit( WP_CONTENT_URL ) . 'uploads/pcz-dodatne-info/';
}

// CSS
$css_file = dirname( $dodatne_path ) . '/dodatne-info.css';
$css_url = $dodatne_url . 'dodatne-info.css';
if ( file_exists( $css_file ) ) {
    echo '<link rel="stylesheet" href="' . esc_url( $css_url ) . '?v=' . filemtime( $css_file ) . '">';
}

// PHP Template
if ( file_exists( $dodatne_path ) ) {
    include $dodatne_path;
} else {
    if ( current_user_can( 'manage_options' ) ) {
        echo '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;margin:20px;">
            <strong>pcz Dodatne Info Error:</strong> Fajl nije pronađen.<br>
            Lokacija: ' . esc_html( $dodatne_path ) . '
        </div>';
    }
}
?>

