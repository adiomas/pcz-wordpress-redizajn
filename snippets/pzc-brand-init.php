<?php
/**
 * pcz Multi-Brand System - WordPress Initialization
 * 
 * Ovaj snippet registrira:
 * - Brand detection i CSS varijable
 * - [pcz_brand_switcher] shortcode
 * - Body klase i data attribute za brand
 * - Automatsko učitavanje CSS i JS asseta
 * 
 * INSTALACIJA:
 * 1. Dodati ovaj kod u Code Snippets plugin
 * 2. Aktivirati snippet
 * 3. Uploadati brand/ folder u wp-content/uploads/pcz-brand/
 * 4. Koristiti [pcz_brand_switcher] shortcode gdje je potrebno
 * 
 * @package pcz_Redizajn
 * @since 4.0.0
 */

// Sprječava direktan pristup
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// =============================================================================
// PATH DETECTION (kompatibilno s WP Staging)
// =============================================================================

/**
 * Dohvati putanje za brand fajlove
 * 
 * @return array ['path' => string, 'url' => string]
 */
function pcz_get_brand_paths() {
    $uploads = wp_upload_dir();
    
    // Primarno: wp-content/uploads/pcz-brand/
    if ( ! empty( $uploads['basedir'] ) && ! empty( $uploads['baseurl'] ) ) {
        $path = trailingslashit( $uploads['basedir'] ) . 'pcz-brand/';
        $url = trailingslashit( $uploads['baseurl'] ) . 'pcz-brand/';
        
        if ( file_exists( $path . 'brand.php' ) ) {
            return [
                'path' => $path,
                'url'  => $url,
            ];
        }
    }
    
    // Fallback: theme directory
    $theme_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-brand/';
    $theme_url = trailingslashit( get_stylesheet_directory_uri() ) . 'pcz-brand/';
    
    if ( file_exists( $theme_path . 'brand.php' ) ) {
        return [
            'path' => $theme_path,
            'url'  => $theme_url,
        ];
    }
    
    // Posljednji fallback
    return [
        'path' => trailingslashit( $uploads['basedir'] ?? ABSPATH . 'wp-content/uploads' ) . 'pcz-brand/',
        'url'  => trailingslashit( $uploads['baseurl'] ?? content_url( '/uploads' ) ) . 'pcz-brand/',
    ];
}

// =============================================================================
// LOAD BRAND CORE
// =============================================================================

add_action( 'after_setup_theme', 'pcz_load_brand_core' );
function pcz_load_brand_core() {
    $paths = pcz_get_brand_paths();
    $brand_file = $paths['path'] . 'brand.php';
    
    if ( file_exists( $brand_file ) ) {
        require_once $brand_file;
    }
}

// =============================================================================
// LOAD BRAND SWITCHER EARLY (za header toggle support)
// =============================================================================

add_action( 'after_setup_theme', 'pcz_load_brand_switcher_early', 11 );
function pcz_load_brand_switcher_early() {
    $paths = pcz_get_brand_paths();
    $switcher_file = $paths['path'] . 'brand-switcher.php';
    
    if ( file_exists( $switcher_file ) ) {
        require_once $switcher_file;
    }
}

// =============================================================================
// BRAND-AWARE HEADER FILTERS
// Ovi filteri omogućuju promjenu loga i klasa u headeru po brandu
// =============================================================================

/**
 * Override header logo na temelju aktivnog branda
 * Samo ako je brand_aware_header uključen u ACF postavkama
 */
add_filter( 'pcz_header_logo_url', 'pcz_brand_header_logo_filter', 10, 1 );
function pcz_brand_header_logo_filter( $logo_url ) {
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
    $brand_logo = null;
    if ( $brand_id === 'plesna-skola' ) {
        $brand_logo = get_field( 'ps_logo', 'option' );
    } elseif ( $brand_id === 'sportski-klub' ) {
        $brand_logo = get_field( 'spk_logo', 'option' );
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
 * Dodaj brand klasu na header element
 */
add_filter( 'pcz_header_classes', 'pcz_brand_header_classes_filter', 10, 1 );
function pcz_brand_header_classes_filter( $classes ) {
    if ( function_exists( 'pcz_get_current_brand_id' ) ) {
        $classes .= ' brand-' . pcz_get_current_brand_id();
    }
    return $classes;
}

// =============================================================================
// HOOKS - Body Class & Attribute
// =============================================================================

// Dodaj brand klasu na body
add_filter( 'body_class', 'pcz_add_brand_body_class' );
function pcz_add_brand_body_class( $classes ) {
    if ( function_exists( 'pcz_get_current_brand_id' ) ) {
        $brand_id = pcz_get_current_brand_id();
        $classes[] = 'pcz-brand-' . $brand_id;
    }
    return $classes;
}

// Dodaj data-brand attribute na body (za JavaScript/CSS selektore)
add_filter( 'language_attributes', 'pcz_add_brand_html_attribute' );
function pcz_add_brand_html_attribute( $output ) {
    // Ovaj filter se koristi za <html> tag, ne <body>
    // Za body koristimo wp_body_open hook
    return $output;
}

// Body open hook za data-brand
add_action( 'wp_body_open', 'pcz_output_brand_body_attribute' );
function pcz_output_brand_body_attribute() {
    if ( function_exists( 'pcz_get_current_brand_id' ) ) {
        $brand_id = pcz_get_current_brand_id();
        // Ovaj script odmah postavlja data-brand
        echo '<script>document.body.setAttribute("data-brand", "' . esc_js( $brand_id ) . '");</script>' . "\n";
    }
}

// =============================================================================
// HOOKS - CSS Variables u <head>
// =============================================================================

add_action( 'wp_head', 'pcz_output_brand_css_variables', 5 );
function pcz_output_brand_css_variables() {
    if ( function_exists( 'pcz_output_brand_css' ) ) {
        pcz_output_brand_css();
    }
}

// =============================================================================
// HOOKS - JSON Data za JavaScript
// =============================================================================

add_action( 'wp_footer', 'pcz_output_brand_js_data', 5 );
function pcz_output_brand_js_data() {
    if ( function_exists( 'pcz_output_brand_json' ) ) {
        pcz_output_brand_json();
    }
}

// =============================================================================
// ENQUEUE ASSETS
// =============================================================================

add_action( 'wp_enqueue_scripts', 'pcz_enqueue_brand_assets' );
function pcz_enqueue_brand_assets() {
    $paths = pcz_get_brand_paths();
    
    // CSS
    if ( file_exists( $paths['path'] . 'brand.css' ) ) {
        wp_enqueue_style(
            'pcz-brand',
            $paths['url'] . 'brand.css',
            [],
            filemtime( $paths['path'] . 'brand.css' )
        );
    }
    
    // JavaScript
    if ( file_exists( $paths['path'] . 'brand.js' ) ) {
        wp_enqueue_script(
            'pcz-brand',
            $paths['url'] . 'brand.js',
            [],
            filemtime( $paths['path'] . 'brand.js' ),
            true // In footer
        );
        
        // Localize script s AJAX podacima
        wp_localize_script( 'pcz-brand', 'pczAjax', [
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'pcz_brand_nonce' ),
        ]);
    }
}

// =============================================================================
// SHORTCODES
// =============================================================================

// Brand Switcher shortcode
add_shortcode( 'pcz_brand_switcher', 'pcz_brand_switcher_shortcode_handler' );
function pcz_brand_switcher_shortcode_handler( $atts ) {
    // Dohvati atribute
    $atts = shortcode_atts( [
        'force' => false,  // Ako je true, ignoriraj duplicate protection
    ], $atts, 'pcz_brand_switcher' );
    
    // Duplicate protection (osim ako nije force=true)
    global $pcz_brand_switcher_rendered;
    if ( ! $atts['force'] && ! empty( $pcz_brand_switcher_rendered ) ) {
        return current_user_can( 'manage_options' ) 
            ? '<!-- pcz Brand Switcher: Shortcode preskočen, switcher već renderiran -->' 
            : '';
    }
    
    $paths = pcz_get_brand_paths();
    $switcher_file = $paths['path'] . 'brand-switcher.php';
    
    if ( ! file_exists( $switcher_file ) ) {
        if ( current_user_can( 'manage_options' ) ) {
            return '<p style="color:red;">pcz Brand: brand-switcher.php nije pronađen u ' . esc_html( $paths['path'] ) . '</p>';
        }
        return '';
    }
    
    require_once $switcher_file;
    
    if ( function_exists( 'pcz_brand_switcher_shortcode' ) ) {
        $pcz_brand_switcher_rendered = true;  // Označi kao renderiran
        return pcz_brand_switcher_shortcode( $atts );
    }
    
    return '';
}

// Brand Toggle shortcode (kompaktna verzija za header)
add_shortcode( 'pcz_brand_toggle', 'pcz_brand_toggle_shortcode_handler' );
function pcz_brand_toggle_shortcode_handler() {
    $paths = pcz_get_brand_paths();
    $switcher_file = $paths['path'] . 'brand-switcher.php';
    
    if ( file_exists( $switcher_file ) ) {
        require_once $switcher_file;
        
        if ( function_exists( 'pcz_render_brand_toggle' ) ) {
            ob_start();
            pcz_render_brand_toggle();
            return ob_get_clean();
        }
    }
    
    return '';
}

// Brand Badge shortcode
add_shortcode( 'pcz_brand_badge', 'pcz_brand_badge_shortcode_handler' );
function pcz_brand_badge_shortcode_handler() {
    $paths = pcz_get_brand_paths();
    $switcher_file = $paths['path'] . 'brand-switcher.php';
    
    if ( file_exists( $switcher_file ) ) {
        require_once $switcher_file;
        
        if ( function_exists( 'pcz_render_brand_badge' ) ) {
            ob_start();
            pcz_render_brand_badge();
            return ob_get_clean();
        }
    }
    
    return '';
}

// =============================================================================
// CONDITIONAL CONTENT SHORTCODES
// =============================================================================

/**
 * [pcz_if_brand brand="plesna-skola"]Sadržaj samo za Plesnu Školu[/pcz_if_brand]
 */
add_shortcode( 'pcz_if_brand', 'pcz_if_brand_shortcode' );
function pcz_if_brand_shortcode( $atts, $content = null ) {
    $atts = shortcode_atts( [
        'brand' => 'plesna-skola',
    ], $atts, 'pcz_if_brand' );
    
    if ( ! function_exists( 'pcz_is_brand' ) ) {
        return '';
    }
    
    if ( pcz_is_brand( $atts['brand'] ) ) {
        return do_shortcode( $content );
    }
    
    return '';
}

/**
 * [pcz_unless_brand brand="sportski-klub"]Sadržaj za sve OSIM Sportski Klub[/pcz_unless_brand]
 */
add_shortcode( 'pcz_unless_brand', 'pcz_unless_brand_shortcode' );
function pcz_unless_brand_shortcode( $atts, $content = null ) {
    $atts = shortcode_atts( [
        'brand' => '',
    ], $atts, 'pcz_unless_brand' );
    
    if ( ! function_exists( 'pcz_is_brand' ) || empty( $atts['brand'] ) ) {
        return do_shortcode( $content );
    }
    
    if ( ! pcz_is_brand( $atts['brand'] ) ) {
        return do_shortcode( $content );
    }
    
    return '';
}

// =============================================================================
// ADMIN NOTICE - Provjera instalacije
// =============================================================================

add_action( 'admin_notices', 'pcz_brand_admin_notice' );
function pcz_brand_admin_notice() {
    $screen = get_current_screen();
    if ( ! $screen || ! in_array( $screen->id, [ 'dashboard', 'toplevel_page_site-settings' ] ) ) {
        return;
    }
    
    $paths = pcz_get_brand_paths();
    $missing = [];
    
    $required_files = [ 'brand.php', 'brand.css', 'brand.js', 'brand-switcher.php' ];
    foreach ( $required_files as $file ) {
        if ( ! file_exists( $paths['path'] . $file ) ) {
            $missing[] = $file;
        }
    }
    
    if ( ! empty( $missing ) ) {
        ?>
        <div class="notice notice-warning">
            <p><strong>pcz Brand System:</strong> Nedostaju fajlovi u <code><?php echo esc_html( $paths['path'] ); ?></code></p>
            <ul style="margin-left:20px;list-style:disc;">
                <?php foreach ( $missing as $file ) : ?>
                    <li><code><?php echo esc_html( $file ); ?></code></li>
                <?php endforeach; ?>
            </ul>
            <p>Kopirajte fajlove iz <code>pcz-redizajn/brand/</code> direktorija.</p>
        </div>
        <?php
    }
}

// =============================================================================
// OXYGEN BUILDER INTEGRATION
// =============================================================================

/**
 * Registriraj brand sustav kao Oxygen element (ako Oxygen postoji)
 */
add_action( 'init', 'pcz_register_oxygen_brand_elements' );
function pcz_register_oxygen_brand_elements() {
    // Provjeri postoji li Oxygen
    if ( ! function_exists( 'oxygen_add_plus_sections' ) ) {
        return;
    }
    
    // Registriraj shortcode kao Oxygen element
    add_filter( 'oxygen_builder_shortcodes', function( $shortcodes ) {
        $shortcodes['pcz_brand_switcher'] = [
            'title'        => 'pcz Brand Switcher',
            'category'     => 'pcz Components',
            'params'       => [
                'style'    => [
                    'type'    => 'select',
                    'label'   => 'Stil',
                    'options' => [
                        'tabs'    => 'Tabs',
                        'pills'   => 'Pills',
                        'buttons' => 'Buttons',
                    ],
                    'default' => 'tabs',
                ],
                'size' => [
                    'type'    => 'select',
                    'label'   => 'Veličina',
                    'options' => [
                        'small'  => 'Mala',
                        'normal' => 'Normalna',
                        'large'  => 'Velika',
                    ],
                    'default' => 'normal',
                ],
            ],
        ];
        
        return $shortcodes;
    });
}

// =============================================================================
// HELPER: Brand-aware ACF get_field wrapper
// =============================================================================

/**
 * Dohvati ACF field s brand prefixom
 * 
 * Korištenje:
 * pcz_get_brand_field('hero_naslov') 
 * → vraća 'hero_naslov' za Plesnu Školu
 * → vraća 'spk_hero_naslov' za Sportski Klub
 * 
 * @param string $field_name Ime polja bez prefixa
 * @param mixed $post_id Post ID ili 'option'
 * @return mixed
 */
function pcz_get_brand_field( $field_name, $post_id = 'option' ) {
    if ( ! function_exists( 'get_field' ) || ! function_exists( 'pcz_get_current_brand_id' ) ) {
        return null;
    }
    
    $brand_id = pcz_get_current_brand_id();
    
    // Za Sportski Klub, koristi prefixed polja
    if ( $brand_id === 'sportski-klub' ) {
        $prefixed_value = get_field( 'spk_' . $field_name, $post_id );
        if ( ! empty( $prefixed_value ) ) {
            return $prefixed_value;
        }
    }
    
    // Fallback na default polje
    return get_field( $field_name, $post_id );
}

// =============================================================================
// OXYGEN BUILDER CONDITION HELPERS
// =============================================================================
// 
// Ove funkcije koristi u Oxygen → Element → Conditions → Custom PHP
// 
// KORIŠTENJE:
// 1. Selektiraj element/sekciju u Oxygen Builderu
// 2. Idi na Settings → Conditions → Edit Conditions
// 3. Klikni +Add Condition
// 4. Odaberi "Custom PHP"
// 5. Klikni "Open PHP Editor"
// 6. Upiši jednu od funkcija ispod
// 7. Klikni Apply Conditions
//
// =============================================================================

/**
 * Provjeri je li aktivni brand Plesna Škola
 * 
 * OXYGEN CONDITION USAGE:
 * return pcz_is_plesna_skola();
 * 
 * @return bool True ako je aktivni brand Plesna Škola
 */
function pcz_is_plesna_skola() {
    if ( function_exists( 'pcz_is_brand' ) ) {
        return pcz_is_brand( 'plesna-skola' );
    }
    // Default: Plesna škola je default brand
    return true;
}

/**
 * Provjeri je li aktivni brand Sportski Klub
 * 
 * OXYGEN CONDITION USAGE:
 * return pcz_is_sportski_klub();
 * 
 * @return bool True ako je aktivni brand Sportski Klub
 */
function pcz_is_sportski_klub() {
    if ( function_exists( 'pcz_is_brand' ) ) {
        return pcz_is_brand( 'sportski-klub' );
    }
    return false;
}

/**
 * Provjeri je li aktivni brand jednak zadanom
 * 
 * OXYGEN CONDITION USAGE:
 * return pcz_brand_is( 'sportski-klub' );
 * return pcz_brand_is( 'plesna-skola' );
 * 
 * @param string $brand_id Brand ID za provjeru
 * @return bool
 */
function pcz_brand_is( $brand_id ) {
    if ( function_exists( 'pcz_is_brand' ) ) {
        return pcz_is_brand( $brand_id );
    }
    // Default
    return $brand_id === 'plesna-skola';
}

/**
 * Provjeri NIJE li aktivni brand jednak zadanom
 * 
 * OXYGEN CONDITION USAGE:
 * return pcz_brand_is_not( 'sportski-klub' );
 * 
 * @param string $brand_id Brand ID koji NE želimo
 * @return bool
 */
function pcz_brand_is_not( $brand_id ) {
    return ! pcz_brand_is( $brand_id );
}

/**
 * Provjeri je li aktivni brand bilo koji od zadanih
 * 
 * OXYGEN CONDITION USAGE:
 * return pcz_brand_in( ['plesna-skola', 'sportski-klub'] );
 * 
 * @param array $brand_ids Array brand ID-eva
 * @return bool
 */
function pcz_brand_in( $brand_ids ) {
    if ( ! is_array( $brand_ids ) ) {
        return false;
    }
    
    if ( function_exists( 'pcz_get_current_brand_id' ) ) {
        return in_array( pcz_get_current_brand_id(), $brand_ids, true );
    }
    
    return in_array( 'plesna-skola', $brand_ids, true );
}

/**
 * Dohvati ID trenutnog branda
 * 
 * OXYGEN DYNAMIC DATA USAGE:
 * [oxygen data="pcz_current_brand_id"]
 * 
 * @return string Brand ID
 */
function pcz_current_brand_id() {
    if ( function_exists( 'pcz_get_current_brand_id' ) ) {
        return pcz_get_current_brand_id();
    }
    return 'plesna-skola';
}

