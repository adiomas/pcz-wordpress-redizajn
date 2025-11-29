<?php
/**
 * pcz Brand-Aware Header Wrapper
 * 
 * Ovaj fajl je wrapper oko originalne header komponente koji
 * omogućuje brand-specifični logo i boje.
 * 
 * NAPOMENA:
 * Koristi opciju 'brand_aware_header' iz ACF-a da određuje
 * treba li header promijeniti logo po brandu.
 * 
 * @package pcz_Redizajn
 * @since 4.0.0
 */

// Sprječava direktan pristup
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Učitaj brand sustav ako nije već učitan
if ( ! function_exists( 'pcz_get_current_brand' ) ) {
    $brand_file = dirname( __FILE__ ) . '/brand.php';
    if ( file_exists( $brand_file ) ) {
        require_once $brand_file;
    }
}

// =============================================================================
// BRAND-SPECIFIC HEADER DATA OVERRIDE
// =============================================================================

/**
 * Override header logo na temelju aktivnog branda
 * Samo ako je brand_aware_header uključen
 */
add_filter( 'pcz_header_logo_url', 'pcz_brand_header_logo_override', 10, 1 );
function pcz_brand_header_logo_override( $logo_url ) {
    // Provjeri je li brand-aware header uključen
    $brand_aware_header = false;
    if ( function_exists( 'get_field' ) ) {
        $brand_aware_header = get_field( 'brand_aware_header', 'option' );
    }
    
    if ( ! $brand_aware_header ) {
        return $logo_url;
    }
    
    if ( ! function_exists( 'pcz_get_current_brand_id' ) || ! function_exists( 'get_field' ) ) {
        return $logo_url;
    }
    
    $brand_id = pcz_get_current_brand_id();
    
    // Dohvati brand-specifični logo
    if ( $brand_id === 'plesna-skola' ) {
        $brand_logo = get_field( 'ps_logo', 'option' );
    } elseif ( $brand_id === 'sportski-klub' ) {
        $brand_logo = get_field( 'spk_logo', 'option' );
    } else {
        return $logo_url;
    }
    
    if ( ! empty( $brand_logo ) ) {
        if ( is_array( $brand_logo ) && isset( $brand_logo['url'] ) ) {
            return $brand_logo['url'];
        } elseif ( is_numeric( $brand_logo ) ) {
            $url = wp_get_attachment_image_url( $brand_logo, 'full' );
            return $url ? $url : $logo_url;
        } elseif ( is_string( $brand_logo ) ) {
            return $brand_logo;
        }
    }
    
    return $logo_url;
}

/**
 * Dodaj brand data attribute na header
 */
add_filter( 'pcz_header_classes', 'pcz_brand_header_classes', 10, 1 );
function pcz_brand_header_classes( $classes ) {
    if ( function_exists( 'pcz_get_current_brand_id' ) ) {
        $classes .= ' brand-' . pcz_get_current_brand_id();
    }
    return $classes;
}

/**
 * Dodaj brand switcher u header (opcionalno)
 */
add_action( 'pcz_after_header_nav', 'pcz_inject_brand_switcher_in_header' );
function pcz_inject_brand_switcher_in_header() {
    if ( ! function_exists( 'get_field' ) ) {
        return;
    }
    
    // Provjeri je li switcher uključen
    $enabled = get_field( 'brand_switcher_enabled', 'option' );
    if ( ! $enabled ) {
        return;
    }
    
    $position = get_field( 'brand_switcher_position', 'option' );
    
    if ( $position === 'header' ) {
        $switcher_file = dirname( __FILE__ ) . '/brand-switcher.php';
        if ( file_exists( $switcher_file ) ) {
            require_once $switcher_file;
            
            if ( function_exists( 'pcz_render_brand_switcher' ) ) {
                echo '<div class="pcz-header__brand-switcher">';
                // U headeru koristi manju verziju bez obzira na ACF postavke
                pcz_render_brand_switcher([
                    'size' => 'small',
                ]);
                echo '</div>';
            }
        }
    }
}

// =============================================================================
// HEADER TEMPLATE INCLUDE
// =============================================================================

// Uključi originalnu header komponentu
// Pokušaj više lokacija za kompatibilnost

$header_file = null;
$possible_paths = [];

// 1. Relativna putanja (za development/test)
$possible_paths[] = dirname( dirname( __FILE__ ) ) . '/header/mega-menu.php';

// 2. WordPress uploads - pcz-header
if ( function_exists( 'wp_upload_dir' ) ) {
    $uploads = wp_upload_dir();
    $possible_paths[] = trailingslashit( $uploads['basedir'] ) . 'pcz-header/mega-menu.php';
}

// 3. Child theme
if ( function_exists( 'get_stylesheet_directory' ) ) {
    $possible_paths[] = trailingslashit( get_stylesheet_directory() ) . 'pcz-header/mega-menu.php';
}

// Pronađi prvi postojeći fajl
foreach ( $possible_paths as $path ) {
    if ( file_exists( $path ) ) {
        $header_file = $path;
        break;
    }
}

if ( $header_file ) {
    include $header_file;
} else {
    if ( function_exists( 'current_user_can' ) && current_user_can( 'manage_options' ) ) {
        echo '<div style="background:#ff6b6b;color:white;padding:20px;text-align:center;">';
        echo 'Header komponenta nije pronađena. Provjerene lokacije:<br>';
        foreach ( $possible_paths as $p ) {
            echo esc_html( $p ) . '<br>';
        }
        echo '</div>';
    }
}

