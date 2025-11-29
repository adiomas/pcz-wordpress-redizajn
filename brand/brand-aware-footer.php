<?php
/**
 * pcz Brand-Aware Footer Wrapper
 * 
 * Ovaj fajl je wrapper oko originalne footer komponente koji
 * omogućuje brand-specifične logo i boje.
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
// BRAND-SPECIFIC FOOTER DATA OVERRIDE
// =============================================================================

/**
 * Override footer logo na temelju aktivnog branda
 */
add_filter( 'pcz_footer_logo_url', 'pcz_brand_footer_logo_override', 10, 1 );
function pcz_brand_footer_logo_override( $logo_url ) {
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
 * Override footer social links na temelju aktivnog branda
 * 
 * Svaki brand ima svoje socijalne mreže definirane u ACF:
 * - Plesna Škola: ps_socijalne_mreze
 * - Sportski Klub: spk_socijalne_mreze
 */
add_filter( 'pcz_footer_social_links', 'pcz_brand_footer_social_override', 10, 1 );
function pcz_brand_footer_social_override( $social_links ) {
    if ( ! function_exists( 'pcz_get_current_brand_id' ) || ! function_exists( 'get_field' ) ) {
        return $social_links;
    }
    
    $brand_id = pcz_get_current_brand_id();
    
    // Dohvati socijalne mreže za aktivni brand
    if ( $brand_id === 'sportski-klub' ) {
        $brand_social = get_field( 'spk_socijalne_mreze', 'option' );
    } else {
        $brand_social = get_field( 'ps_socijalne_mreze', 'option' );
    }
    
    // Ako ima brand-specific social links, koristi ih
    if ( ! empty( $brand_social ) && is_array( $brand_social ) ) {
        return $brand_social;
    }
    
    return $social_links;
}

/**
 * Override footer contact info na temelju aktivnog branda (opcionalno)
 */
add_filter( 'pcz_footer_contact_data', 'pcz_brand_footer_contact_override', 10, 1 );
function pcz_brand_footer_contact_override( $contact_data ) {
    // Trenutno koristimo iste kontaktne podatke za oba branda
    // Ovaj filter je pripremljen za buduće proširenje
    return $contact_data;
}

/**
 * Dodaj brand data attribute na footer
 */
add_filter( 'pcz_footer_attributes', 'pcz_brand_footer_attributes', 10, 1 );
function pcz_brand_footer_attributes( $attributes ) {
    if ( function_exists( 'pcz_get_current_brand_id' ) ) {
        $attributes['data-brand'] = pcz_get_current_brand_id();
    }
    return $attributes;
}

// =============================================================================
// FOOTER TEMPLATE INCLUDE
// =============================================================================

// Uključi originalnu footer komponentu
// Pokušaj više lokacija za kompatibilnost

$footer_file = null;
$possible_paths = [];

// 1. Relativna putanja (za development/test)
$possible_paths[] = dirname( dirname( __FILE__ ) ) . '/footer/footer.php';

// 2. WordPress uploads - pcz-footer
if ( function_exists( 'wp_upload_dir' ) ) {
    $uploads = wp_upload_dir();
    $possible_paths[] = trailingslashit( $uploads['basedir'] ) . 'pcz-footer/footer.php';
}

// 3. Child theme
if ( function_exists( 'get_stylesheet_directory' ) ) {
    $possible_paths[] = trailingslashit( get_stylesheet_directory() ) . 'pcz-footer/footer.php';
}

// Pronađi prvi postojeći fajl
foreach ( $possible_paths as $path ) {
    if ( file_exists( $path ) ) {
        $footer_file = $path;
        break;
    }
}

if ( $footer_file ) {
    include $footer_file;
} else {
    if ( function_exists( 'current_user_can' ) && current_user_can( 'manage_options' ) ) {
        echo '<div style="background:#ff6b6b;color:white;padding:20px;text-align:center;">';
        echo 'Footer komponenta nije pronađena. Provjerene lokacije:<br>';
        foreach ( $possible_paths as $p ) {
            echo esc_html( $p ) . '<br>';
        }
        echo '</div>';
    }
}

