<?php
/**
 * PCZ Prijava Sekcija - Inicijalizacija
 * 
 * Ovaj snippet registrira:
 * - [pcz_prijava] shortcode za prikaz sekcije
 * - Automatsko učitavanje CSS asseta
 * 
 * INSTALACIJA:
 * 1. Dodati ovaj kod u Code Snippets plugin
 * 2. Aktivirati snippet
 * 3. Koristiti [pcz_prijava] shortcode ili Oxygen Code Block
 * 
 * GRAVITY FORMS SETUP:
 * 1. Kreiraj formu s potrebnim poljima
 * 2. Dodaj Hidden field "brand" s default value {brand}
 * 3. U ACF postavkama upiši Form ID
 * 
 * @package PCZ_Redizajn
 * @since 1.0.0
 */

// Sprječava direktan pristup
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Definira putanje za PCZ Prijava fajlove
 * 
 * Kompatibilno s WP Staging okruženjima
 */
function pcz_get_prijava_paths() {
    $uploads = wp_upload_dir();
    
    // Opcija 1: Uploads folder (PREPORUČENO)
    if ( ! empty( $uploads['basedir'] ) && ! empty( $uploads['baseurl'] ) ) {
        $uploads_path = trailingslashit( $uploads['basedir'] ) . 'pcz-prijava/';
        $uploads_url = trailingslashit( $uploads['baseurl'] ) . 'pcz-prijava/';
        
        $test_file = $uploads_path . 'prijava.php';
        if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
            return array(
                'path' => $uploads_path,
                'url'  => $uploads_url,
            );
        }
    }
    
    // Fallback: WP_CONTENT_DIR
    if ( defined( 'WP_CONTENT_DIR' ) && defined( 'WP_CONTENT_URL' ) ) {
        $wp_content_path = trailingslashit( WP_CONTENT_DIR ) . 'uploads/pcz-prijava/';
        $wp_content_url = trailingslashit( WP_CONTENT_URL ) . 'uploads/pcz-prijava/';
        
        $test_file = $wp_content_path . 'prijava.php';
        if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
            return array(
                'path' => $wp_content_path,
                'url'  => $wp_content_url,
            );
        }
    }
    
    // Opcija 2: Child tema
    $child_theme_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-prijava/';
    $child_theme_url = trailingslashit( get_stylesheet_directory_uri() ) . 'pcz-prijava/';
    
    $is_oxygen_folder = ( strpos( $child_theme_path, 'oxygen' ) !== false );
    
    if ( ! $is_oxygen_folder ) {
        $test_file = $child_theme_path . 'prijava.php';
        if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
            return array(
                'path' => $child_theme_path,
                'url'  => $child_theme_url,
            );
        }
    }
    
    // Fallback - vrati uploads putanju
    if ( ! empty( $uploads['basedir'] ) && ! empty( $uploads['baseurl'] ) ) {
        return array(
            'path' => trailingslashit( $uploads['basedir'] ) . 'pcz-prijava/',
            'url'  => trailingslashit( $uploads['baseurl'] ) . 'pcz-prijava/',
        );
    }
    
    return null;
}

/**
 * Učitaj CSS za Prijava sekciju
 */
function pcz_prijava_enqueue_styles() {
    $paths = pcz_get_prijava_paths();
    
    if ( ! $paths ) {
        return;
    }
    
    $css_file = $paths['path'] . 'prijava.css';
    $css_url = $paths['url'] . 'prijava.css';
    
    if ( file_exists( $css_file ) ) {
        wp_enqueue_style(
            'pcz-prijava',
            $css_url,
            array(),
            filemtime( $css_file )
        );
    }
}

/**
 * Registriraj [pcz_prijava] shortcode
 */
function pcz_prijava_shortcode( $atts ) {
    $paths = pcz_get_prijava_paths();
    
    if ( ! $paths ) {
        if ( current_user_can( 'manage_options' ) ) {
            return '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;">
                <strong>PCZ Prijava Error:</strong> Fajlovi nisu pronađeni. 
                Uploadajte ih u <code>wp-content/uploads/pcz-prijava/</code>
            </div>';
        }
        return '';
    }
    
    $prijava_file = $paths['path'] . 'prijava.php';
    
    if ( ! file_exists( $prijava_file ) ) {
        if ( current_user_can( 'manage_options' ) ) {
            return '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;">
                <strong>PCZ Prijava Error:</strong> prijava.php nije pronađen.<br>
                Lokacija: ' . esc_html( $prijava_file ) . '
            </div>';
        }
        return '';
    }
    
    // Učitaj assets
    pcz_prijava_enqueue_styles();
    
    // Output buffer za include
    ob_start();
    include $prijava_file;
    return ob_get_clean();
}
add_shortcode( 'pcz_prijava', 'pcz_prijava_shortcode' );

/**
 * Automatski učitaj CSS na stranicama s formom
 */
function pcz_prijava_auto_enqueue() {
    // Učitaj na svim stranicama jer se forma može koristiti bilo gdje
    // Optimizacija: može se ograničiti na specifične stranice
    if ( is_front_page() || is_home() || is_page() ) {
        pcz_prijava_enqueue_styles();
    }
}
add_action( 'wp_enqueue_scripts', 'pcz_prijava_auto_enqueue' );

/**
 * Gravity Forms: Pre-populate brand field
 * 
 * Automatski popunjava hidden "brand" polje s trenutnim brandom
 */
function pcz_gform_populate_brand_field( $value, $field, $name ) {
    if ( $name === 'brand' || strtolower( $field->label ) === 'brand' ) {
        if ( function_exists( 'pcz_get_current_brand_id' ) ) {
            return pcz_get_current_brand_id();
        }
        return 'plesna-skola';
    }
    return $value;
}
add_filter( 'gform_field_value_brand', 'pcz_gform_populate_brand_field', 10, 3 );

/**
 * Gravity Forms: Add brand info to entry meta
 */
function pcz_gform_entry_created( $entry, $form ) {
    // Automatski dodaj brand u entry notes
    if ( function_exists( 'GFAPI' ) && function_exists( 'pcz_get_current_brand_id' ) ) {
        $brand = pcz_get_current_brand_id();
        $brand_label = $brand === 'sportski-klub' ? 'Sportski Klub' : 'Plesna Škola';
        
        GFAPI::add_note( 
            $entry['id'], 
            0, 
            'PCZ Brand System', 
            'Prijava putem: ' . $brand_label . ' (' . $brand . ')'
        );
    }
}
add_action( 'gform_entry_created', 'pcz_gform_entry_created', 10, 2 );

/**
 * Admin: Gravity Forms entries - add brand column
 */
function pcz_gform_add_brand_column( $columns ) {
    $columns['brand'] = 'Brand';
    return $columns;
}
// add_filter( 'gform_entry_list_columns', 'pcz_gform_add_brand_column', 10, 1 );

/**
 * Debug funkcija - prikazuje putanje
 */
function pcz_prijava_debug_info() {
    if ( ! current_user_can( 'manage_options' ) || ! is_admin_bar_showing() ) {
        return;
    }
    
    $paths = pcz_get_prijava_paths();
    
    if ( $paths && isset( $_GET['pcz_debug'] ) ) {
        echo '<div style="background:#333;color:#fff;padding:10px;position:fixed;bottom:0;left:0;right:0;z-index:99999;font-size:12px;">';
        echo '<strong>PCZ Prijava Debug:</strong><br>';
        echo 'Path: ' . esc_html( $paths['path'] ) . '<br>';
        echo 'URL: ' . esc_html( $paths['url'] ) . '<br>';
        echo 'File exists: ' . ( file_exists( $paths['path'] . 'prijava.php' ) ? '✅' : '❌' );
        echo '</div>';
    }
}
add_action( 'wp_footer', 'pcz_prijava_debug_info' );

