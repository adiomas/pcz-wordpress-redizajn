<?php
/**
 * pcz "Sportski Klub" - Oxygen PHP Code Block
 * 
 * UPUTE: Kopiraj CIJELI sadržaj ovog fajla u Oxygen → Code Block → PHP tab
 * 
 * BRAND VISIBILITY:
 * - Ova sekcija se prikazuje SAMO za brand "Sportski Klub"
 * - Za Plesnu Školu sekcija je skrivena CSS-om (omogućuje smooth transition)
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
$visibility_class = $is_visible ? 'pcz-sportski-klub--visible' : 'pcz-sportski-klub--hidden';

// Opcija: Potpuno preskoči render za nevidljive brandove (štedi bandwidth)
// Odkomentiraj sljedeće ako NE želiš smooth transition:
// if ( ! $is_visible ) { return; }

// Dohvati putanje
$uploads = wp_upload_dir();
$component_path = trailingslashit( $uploads['basedir'] ) . 'pcz-sportski-klub/sportski-klub.php';
$component_url = trailingslashit( $uploads['baseurl'] ) . 'pcz-sportski-klub/';

// Fallback na child temu
if ( ! file_exists( $component_path ) ) {
    $component_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-sportski-klub/sportski-klub.php';
    $component_url = trailingslashit( get_stylesheet_directory_uri() ) . 'pcz-sportski-klub/';
}

// Fallback na WP_CONTENT_DIR
if ( ! file_exists( $component_path ) && defined( 'WP_CONTENT_DIR' ) ) {
    $component_path = trailingslashit( WP_CONTENT_DIR ) . 'uploads/pcz-sportski-klub/sportski-klub.php';
    $component_url = trailingslashit( WP_CONTENT_URL ) . 'uploads/pcz-sportski-klub/';
}

// CSS
$css_file = dirname( $component_path ) . '/sportski-klub.css';
$css_url = $component_url . 'sportski-klub.css';
if ( file_exists( $css_file ) ) {
    echo '<link rel="stylesheet" href="' . esc_url( $css_url ) . '?v=' . filemtime( $css_file ) . '">';
}

// PHP Template
if ( file_exists( $component_path ) ) {
    include $component_path;
} else {
    if ( current_user_can( 'manage_options' ) ) {
        echo '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;margin:20px;">
            <strong>pcz Sportski Klub Error:</strong> Fajl nije pronađen.<br>
            Lokacija: ' . esc_html( $component_path ) . '
        </div>';
    }
}
?>


