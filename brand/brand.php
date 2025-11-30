<?php
/**
 * pcz Multi-Brand System - Core Logic
 * 
 * Omogućuje dinamičko prebacivanje između brandova:
 * - Plesna Škola (default) - magenta tema
 * - Sportski Klub - narančasta tema
 * 
 * KORIŠTENJE:
 * 1. PHP: $brand = pcz_get_current_brand();
 * 2. URL: ?brand=sportski-klub ili ?brand=plesna-skola
 * 3. Template: [pcz_brand_switcher] shortcode za tab UI
 * 
 * @package pcz_Redizajn
 * @since 4.0.0
 */

// Sprječava direktan pristup
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// =============================================================================
// BRAND KONFIGURACIJA
// =============================================================================

/**
 * Definirane boje i postavke za svaki brand
 * 
 * @return array
 */
function pcz_get_brand_defaults() {
    return [
        'plesna-skola' => [
            'id'               => 'plesna-skola',
            'name'             => 'Plesna Škola',
            'short_name'       => 'PCZ',
            'tagline'          => 'Plesni Centar Zagreb by Nicolas',
            'primary_color'    => '#C71585',      // Magenta
            'primary_hover'    => '#a01269',
            'secondary_color'  => '#ff6b9d',
            'accent_color'     => '#ffc107',
            'gradient'         => 'linear-gradient(135deg, #C71585 0%, #ff6b9d 100%)',
            'logo_field'       => 'ps_logo',      // ACF field za logo
            'social_field'     => 'ps_socijalne_mreze',
            'is_default'       => true,
        ],
        'sportski-klub' => [
            'id'               => 'sportski-klub',
            'name'             => 'Sportski Klub',
            'short_name'       => 'SPK',
            'tagline'          => 'Sportski Plesni Klub Zagreb',
            'primary_color'    => '#FF6B00',      // Narančasta
            'primary_hover'    => '#CC5500',
            'secondary_color'  => '#FFA500',
            'accent_color'     => '#FFD700',
            'gradient'         => 'linear-gradient(135deg, #FF6B00 0%, #FFA500 100%)',
            'logo_field'       => 'spk_logo',     // ACF field za logo
            'social_field'     => 'spk_socijalne_mreze',
            'is_default'       => false,
        ],
    ];
}

// =============================================================================
// BRAND DETECTION
// =============================================================================

/**
 * Dohvati trenutni brand na temelju URL parametra, sesije ili defaulta
 * 
 * Prioritet:
 * 1. URL query parameter (?brand=sportski-klub)
 * 2. POST data (za AJAX)
 * 3. Session/Cookie
 * 4. Default (plesna-skola)
 * 
 * @return string Brand ID
 */
function pcz_get_current_brand_id() {
    $brands = pcz_get_brand_defaults();
    $valid_brands = array_keys( $brands );
    
    // 1. URL Query Parameter (najviši prioritet)
    if ( isset( $_GET['brand'] ) ) {
        $brand = sanitize_key( $_GET['brand'] );
        if ( in_array( $brand, $valid_brands, true ) ) {
            // Spremi u cookie za persistenciju
            pcz_set_brand_cookie( $brand );
            return $brand;
        }
    }
    
    // 2. POST data (za AJAX pozive)
    if ( isset( $_POST['brand'] ) ) {
        $brand = sanitize_key( $_POST['brand'] );
        if ( in_array( $brand, $valid_brands, true ) ) {
            return $brand;
        }
    }
    
    // 3. Cookie (ako postoji)
    if ( isset( $_COOKIE['pcz_brand'] ) ) {
        $brand = sanitize_key( $_COOKIE['pcz_brand'] );
        if ( in_array( $brand, $valid_brands, true ) ) {
            return $brand;
        }
    }
    
    // 4. ACF default brand (ako je konfiguriran)
    if ( function_exists( 'get_field' ) ) {
        $acf_default = get_field( 'default_brand', 'option' );
        if ( $acf_default && in_array( $acf_default, $valid_brands, true ) ) {
            return $acf_default;
        }
    }
    
    // 5. Hardcoded default
    return 'plesna-skola';
}

/**
 * Dohvati kompletne podatke trenutnog branda
 * 
 * @return array Brand konfiguracija + ACF override
 */
function pcz_get_current_brand() {
    $brand_id = pcz_get_current_brand_id();
    return pcz_get_brand_data( $brand_id );
}

/**
 * Dohvati podatke za specifični brand
 * 
 * @param string $brand_id Brand ID
 * @return array
 */
function pcz_get_brand_data( $brand_id ) {
    $defaults = pcz_get_brand_defaults();
    
    if ( ! isset( $defaults[ $brand_id ] ) ) {
        $brand_id = 'plesna-skola';
    }
    
    $brand = $defaults[ $brand_id ];
    $acf_prefix = ( $brand_id === 'sportski-klub' ) ? 'spk_' : 'ps_';
    
    // Merge s ACF podacima ako postoje
    if ( function_exists( 'get_field' ) ) {
        // Logo
        $acf_logo = get_field( $brand['logo_field'], 'option' );
        if ( $acf_logo ) {
            $brand['logo'] = is_array( $acf_logo ) ? $acf_logo['url'] : $acf_logo;
        }
        
        // Primarna boja iz ACF-a
        $acf_color = get_field( $acf_prefix . 'primary_color', 'option' );
        if ( ! empty( $acf_color ) ) {
            $brand['primary_color'] = $acf_color;
        }
        
        // Socijalne mreže
        $acf_social = get_field( $brand['social_field'], 'option' );
        if ( ! empty( $acf_social ) ) {
            $brand['social_links'] = $acf_social;
        }
    }
    
    // Filter za programatsko overridanje
    if ( function_exists( 'apply_filters' ) ) {
        $brand = apply_filters( 'pcz_brand_data', $brand, $brand_id );
    }
    
    return $brand;
}

/**
 * Dohvati socijalne mreže za trenutni brand
 * 
 * @return array
 */
function pcz_get_brand_social_links() {
    $brand = pcz_get_current_brand();
    
    if ( ! empty( $brand['social_links'] ) ) {
        return $brand['social_links'];
    }
    
    return [];
}

// =============================================================================
// COOKIE MANAGEMENT
// =============================================================================

/**
 * Postavi brand cookie
 * 
 * @param string $brand_id Brand ID
 * @return void
 */
function pcz_set_brand_cookie( $brand_id ) {
    if ( ! headers_sent() && ! defined( 'pcz_TEST_ENVIRONMENT' ) ) {
        setcookie( 
            'pcz_brand', 
            $brand_id, 
            time() + ( 30 * DAY_IN_SECONDS ), // 30 dana
            COOKIEPATH, 
            COOKIE_DOMAIN,
            is_ssl(),
            true // httponly
        );
    }
}

// =============================================================================
// CSS VARIABLES OUTPUT
// =============================================================================

/**
 * Generira CSS varijable za trenutni brand
 * 
 * @return string CSS
 */
function pcz_get_brand_css_variables() {
    $brand = pcz_get_current_brand();
    
    $css = ":root {\n";
    $css .= "    /* pcz Brand: {$brand['name']} */\n";
    $css .= "    --pcz-brand-id: '{$brand['id']}';\n";
    $css .= "    --pcz-primary: {$brand['primary_color']};\n";
    $css .= "    --pcz-primary-hover: {$brand['primary_hover']};\n";
    $css .= "    --pcz-secondary: {$brand['secondary_color']};\n";
    $css .= "    --pcz-accent: {$brand['accent_color']};\n";
    $css .= "    --pcz-gradient: {$brand['gradient']};\n";
    $css .= "}\n\n";
    
    // Body data attribute za CSS selektore
    $css .= "/* Brand-specific selectors */\n";
    $css .= "[data-brand='{$brand['id']}'] {\n";
    $css .= "    --pcz-primary: {$brand['primary_color']};\n";
    $css .= "    --pcz-primary-hover: {$brand['primary_hover']};\n";
    $css .= "}\n";
    
    return $css;
}

/**
 * Ispiši CSS varijable u <head>
 */
function pcz_output_brand_css() {
    $brand = pcz_get_current_brand();
    echo '<style id="pcz-brand-css">' . "\n";
    echo pcz_get_brand_css_variables();
    echo '</style>' . "\n";
}

// =============================================================================
// BODY CLASS & DATA ATTRIBUTES
// =============================================================================

/**
 * Dodaj brand klasu na body tag
 * 
 * @param array $classes
 * @return array
 */
function pcz_body_brand_class( $classes ) {
    $brand = pcz_get_current_brand();
    $classes[] = 'pcz-brand-' . $brand['id'];
    return $classes;
}

/**
 * Dodaj data-brand attribute na body tag (za JS/CSS)
 */
function pcz_body_brand_attribute() {
    $brand = pcz_get_current_brand();
    echo ' data-brand="' . esc_attr( $brand['id'] ) . '"';
}

// =============================================================================
// HELPERS
// =============================================================================

/**
 * Provjeri je li trenutni brand specifičan brand
 * 
 * @param string $brand_id Brand ID za provjeru
 * @return bool
 */
function pcz_is_brand( $brand_id ) {
    return pcz_get_current_brand_id() === $brand_id;
}

/**
 * Generiraj URL za specifični brand
 * 
 * NAPOMENA: URL UVIJEK uključuje brand parametar, čak i za default brand.
 * Ovo osigurava da se cookie ispravno postavi kad korisnik klikne na brand.
 * Bez toga, cookie ostaje na prethodnom brandu i stranica ne radi ispravno.
 * 
 * @param string $brand_id Brand ID
 * @param string|null $url Base URL (default: current URL)
 * @return string
 */
function pcz_get_brand_url( $brand_id, $url = null ) {
    if ( $url === null ) {
        // Koristi trenutni URL sa svim query parametrima
        $current_url = function_exists( 'home_url' ) ? home_url( '/' ) : '/';
        
        // Provjeri da li smo u test okruženju (ima template parametar)
        if ( isset( $_GET['template'] ) ) {
            $current_url = add_query_arg( 'template', sanitize_key( $_GET['template'] ), $current_url );
            
            // Zadrži i scenario ako postoji
            if ( isset( $_GET['scenario'] ) ) {
                $current_url = add_query_arg( 'scenario', sanitize_key( $_GET['scenario'] ), $current_url );
            }
        }
        
        $url = $current_url;
    }
    
    // Ukloni postojeći brand parametar
    $url = remove_query_arg( 'brand', $url );
    
    // UVIJEK dodaj brand parametar - to osigurava da se cookie postavi ispravno
    // Prije je bilo: samo za non-default brandove, što je uzrokovalo bug
    // gdje je cookie ostajao na sportski-klub kad se prebacivalo na plesna-skola
    $url = add_query_arg( 'brand', $brand_id, $url );
    
    return $url;
}

/**
 * Dohvati logo URL za trenutni brand
 * 
 * @return string
 */
function pcz_get_brand_logo() {
    $brand = pcz_get_current_brand();
    
    if ( ! empty( $brand['logo'] ) ) {
        return $brand['logo'];
    }
    
    // Fallback na site_logo
    if ( function_exists( 'get_field' ) ) {
        $site_logo = get_field( 'site_logo', 'option' );
        if ( $site_logo ) {
            return is_array( $site_logo ) ? $site_logo['url'] : $site_logo;
        }
    }
    
    // WordPress Customizer fallback
    if ( function_exists( 'get_theme_mod' ) ) {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        if ( $custom_logo_id ) {
            return wp_get_attachment_image_url( $custom_logo_id, 'full' );
        }
    }
    
    return '';
}

/**
 * Provjeri je li header brand-aware (mijenja li se po brandu)
 * 
 * @return bool
 */
function pcz_is_header_brand_aware() {
    if ( function_exists( 'get_field' ) ) {
        return (bool) get_field( 'brand_aware_header', 'option' );
    }
    return false;
}

/**
 * Dohvati specifičnu postavku trenutnog branda
 * 
 * @param string $key Ključ postavke (npr. 'logo', 'primary_color', 'name')
 * @param mixed $default Defaultna vrijednost ako ključ ne postoji
 * @return mixed
 */
function pcz_get_brand_setting( $key, $default = null ) {
    $brand = pcz_get_current_brand();
    
    if ( isset( $brand[ $key ] ) ) {
        return $brand[ $key ];
    }
    
    return $default;
}

// =============================================================================
// JSON DATA ZA JAVASCRIPT
// =============================================================================

/**
 * Dohvati brand podatke za JavaScript
 * 
 * @return array
 */
function pcz_get_brands_for_js() {
    $brands = pcz_get_brand_defaults();
    $current_brand_id = pcz_get_current_brand_id();
    
    $js_data = [
        'current' => $current_brand_id,
        'brands'  => [],
    ];
    
    foreach ( $brands as $id => $brand ) {
        $brand_data = pcz_get_brand_data( $id );
        $js_data['brands'][ $id ] = [
            'id'           => $id,
            'name'         => $brand_data['name'],
            'shortName'    => $brand_data['short_name'],
            'primaryColor' => $brand_data['primary_color'],
            'hoverColor'   => $brand_data['primary_hover'],
            'gradient'     => $brand_data['gradient'],
            'url'          => pcz_get_brand_url( $id ),
            'logo'         => pcz_get_brand_logo(),
            'isDefault'    => $brand_data['is_default'],
        ];
    }
    
    return $js_data;
}

/**
 * Ispis brand JSON-a u footer (za JavaScript)
 */
function pcz_output_brand_json() {
    $data = pcz_get_brands_for_js();
    echo '<script id="pcz-brand-data" type="application/json">';
    echo json_encode( $data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    echo '</script>' . "\n";
}

