<?php
/**
 * pcz "O Nama - Sportski Klub" - Oxygen PHP Code Block
 * 
 * Premium Immersive Design - točna replika preview.html
 * 
 * UPUTE: Kopiraj CIJELI sadržaj ovog fajla u Oxygen → Code Block → PHP tab
 * 
 * BRAND VISIBILITY:
 * - Ova sekcija se prikazuje SAMO za brand "Sportski Klub"
 * - Za Plesna Škola sekcija je skrivena CSS-om (omogućuje smooth transition)
 * 
 * @version 2.0.0
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
$visibility_class = $is_visible ? 'o-nama--visible' : 'o-nama--hidden';

// Opcija: Potpuno preskoči render za nevidljive brandove (štedi bandwidth)
// Odkomentiraj sljedeće ako NE želiš smooth transition:
// if ( ! $is_visible ) { return; }

// =============================================================================
// PUTANJE
// =============================================================================

$uploads = wp_upload_dir();
$component_path = trailingslashit( $uploads['basedir'] ) . 'pcz-o-nama-spk/o-nama-spk.php';
$component_url = trailingslashit( $uploads['baseurl'] ) . 'pcz-o-nama-spk/';

// Fallback na child temu
if ( ! file_exists( $component_path ) ) {
    $component_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-o-nama-spk/o-nama-spk.php';
    $component_url = trailingslashit( get_stylesheet_directory_uri() ) . 'pcz-o-nama-spk/';
}

// Fallback na WP_CONTENT_DIR
if ( ! file_exists( $component_path ) && defined( 'WP_CONTENT_DIR' ) ) {
    $component_path = trailingslashit( WP_CONTENT_DIR ) . 'uploads/pcz-o-nama-spk/o-nama-spk.php';
    $component_url = trailingslashit( WP_CONTENT_URL ) . 'uploads/pcz-o-nama-spk/';
}

// =============================================================================
// UČITAJ ASSETS
// =============================================================================

// Google Fonts - Playfair Display + DM Sans
echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600;700&display=swap">';

// CSS
$css_file = dirname( $component_path ) . '/o-nama-spk.css';
$css_url = $component_url . 'o-nama-spk.css';
if ( file_exists( $css_file ) ) {
    echo '<link rel="stylesheet" href="' . esc_url( $css_url ) . '?v=' . filemtime( $css_file ) . '">';
}

// =============================================================================
// RENDER PHP TEMPLATE
// =============================================================================

if ( file_exists( $component_path ) ) {
    include $component_path;
} else {
    if ( current_user_can( 'manage_options' ) ) {
        echo '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;margin:20px;">
            <strong>pcz O Nama SPK Error:</strong> Fajl nije pronađen.<br>
            Lokacija: ' . esc_html( $component_path ) . '
        </div>';
    }
}
?>
