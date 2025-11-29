<?php
/**
 * pcz "Poznati o PCZ-u" - Inicijalizacija
 * 
 * Ovaj snippet registrira:
 * - [pcz_poznati] shortcode za prikaz sekcije
 * - Automatsko učitavanje CSS i JS asseta
 * 
 * INSTALACIJA:
 * 1. Dodati ovaj kod u Code Snippets plugin
 * 2. Aktivirati snippet
 * 3. Koristiti [pcz_poznati] shortcode ili Oxygen Code Block
 * 
 * @package pcz_Redizajn
 * @since 1.0.0
 */

// Sprječava direktan pristup
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Definira putanje za pcz Poznati fajlove
 * 
 * Kompatibilno s WP Staging okruženjima
 * 
 * Redoslijed provjere:
 * 1. Uploads folder (wp-content/uploads/pcz-poznati/) - PREPORUČENO
 * 2. Child tema (wp-content/themes/tvoja-tema/pcz-poznati/)
 * 3. Parent tema
 */
function pcz_get_poznati_paths() {
    $uploads = wp_upload_dir();
    
    // Opcija 1: Uploads folder (PREPORUČENO)
    if ( ! empty( $uploads['basedir'] ) && ! empty( $uploads['baseurl'] ) ) {
        $uploads_path = trailingslashit( $uploads['basedir'] ) . 'pcz-poznati/';
        $uploads_url = trailingslashit( $uploads['baseurl'] ) . 'pcz-poznati/';
        
        $test_file = $uploads_path . 'poznati.php';
        if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
            return array(
                'path' => $uploads_path,
                'url'  => $uploads_url,
            );
        }
    }
    
    // Fallback: WP_CONTENT_DIR
    if ( defined( 'WP_CONTENT_DIR' ) && defined( 'WP_CONTENT_URL' ) ) {
        $wp_content_path = trailingslashit( WP_CONTENT_DIR ) . 'uploads/pcz-poznati/';
        $wp_content_url = trailingslashit( WP_CONTENT_URL ) . 'uploads/pcz-poznati/';
        
        $test_file = $wp_content_path . 'poznati.php';
        if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
            return array(
                'path' => $wp_content_path,
                'url'  => $wp_content_url,
            );
        }
    }
    
    // Opcija 2: Child tema
    $child_theme_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-poznati/';
    $child_theme_url = trailingslashit( get_stylesheet_directory_uri() ) . 'pcz-poznati/';
    
    $is_oxygen_folder = ( strpos( $child_theme_path, 'oxygen' ) !== false );
    
    if ( ! $is_oxygen_folder ) {
        $test_file = $child_theme_path . 'poznati.php';
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
            'path' => trailingslashit( $uploads['basedir'] ) . 'pcz-poznati/',
            'url'  => trailingslashit( $uploads['baseurl'] ) . 'pcz-poznati/',
        );
    }
    
    return null;
}

/**
 * Učitaj CSS za Poznati sekciju
 */
function pcz_poznati_enqueue_styles() {
    $paths = pcz_get_poznati_paths();
    
    if ( ! $paths ) {
        return;
    }
    
    $css_file = $paths['path'] . 'poznati.css';
    $css_url = $paths['url'] . 'poznati.css';
    
    if ( file_exists( $css_file ) ) {
        wp_enqueue_style(
            'pcz-poznati',
            $css_url,
            array(),
            filemtime( $css_file )
        );
    }
}

/**
 * Učitaj JavaScript za Poznati slider
 */
function pcz_poznati_enqueue_scripts() {
    $paths = pcz_get_poznati_paths();
    
    if ( ! $paths ) {
        return;
    }
    
    $js_file = $paths['path'] . 'poznati.js';
    $js_url = $paths['url'] . 'poznati.js';
    
    if ( file_exists( $js_file ) ) {
        wp_enqueue_script(
            'pcz-poznati',
            $js_url,
            array(),
            filemtime( $js_file ),
            true // U footer
        );
    }
}

/**
 * Registriraj [pcz_poznati] shortcode
 */
function pcz_poznati_shortcode( $atts ) {
    $paths = pcz_get_poznati_paths();
    
    if ( ! $paths ) {
        if ( current_user_can( 'manage_options' ) ) {
            return '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;">
                <strong>pcz Poznati Error:</strong> Fajlovi nisu pronađeni. 
                Uploadajte ih u <code>wp-content/uploads/pcz-poznati/</code>
            </div>';
        }
        return '';
    }
    
    $poznati_file = $paths['path'] . 'poznati.php';
    
    if ( ! file_exists( $poznati_file ) ) {
        if ( current_user_can( 'manage_options' ) ) {
            return '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;">
                <strong>pcz Poznati Error:</strong> poznati.php nije pronađen.<br>
                Lokacija: ' . esc_html( $poznati_file ) . '
            </div>';
        }
        return '';
    }
    
    // Učitaj assets
    pcz_poznati_enqueue_styles();
    pcz_poznati_enqueue_scripts();
    
    // Output buffer za include
    ob_start();
    include $poznati_file;
    return ob_get_clean();
}
add_shortcode( 'pcz_poznati', 'pcz_poznati_shortcode' );

/**
 * Automatski učitaj CSS na svim stranicama
 * (Za slučaj korištenja u Oxygen Code Block-u bez shortcode-a)
 */
function pcz_poznati_auto_enqueue() {
    // Provjeri je li poznati sekcija korištena na stranici
    // Za sada, učitaj CSS globalno (može se optimizirati kasnije)
    pcz_poznati_enqueue_styles();
}
// add_action( 'wp_enqueue_scripts', 'pcz_poznati_auto_enqueue' );
// Odkomentiraj gornju liniju ako želiš globalno učitavanje CSS-a

/**
 * Fallback podaci za "Poznati o PCZ-u" sekciju
 * 
 * Ovi podaci se koriste kada ACF nema podataka i fallback je omogućen.
 * NAPOMENA: Ovo je JEDINI IZVOR fallback podataka - NE dupliciramo u poznati.php!
 * 
 * Za deaktivaciju fallbacka u produkciji:
 * add_filter('pcz_poznati_use_fallback', '__return_false');
 * 
 * @param array $data Postojeći fallback podaci (obično prazni)
 * @return array Testimonijali za prikaz
 */
function pcz_poznati_default_fallback_data( $data ) {
    // Ako već ima podataka, vrati ih
    if ( ! empty( $data ) ) {
        return $data;
    }
    
    // Default fallback podaci za demo/development
    return array(
        array(
            'ime'   => 'DUBRAVKO MERLIĆ',
            'citat' => 'Ispalo je da se cijeli doživljaj pretvorio u iznimno iskustvo!',
            'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/D-merlic-min.png',
        ),
        array(
            'ime'   => 'DANIELA TRBOVIĆ',
            'citat' => 'Suradnja s Nicolasom zbog Plesa sa zvijezdama bila je jedna od ljepših u mom životu.',
            'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/trbovic-min.png',
        ),
        array(
            'ime'   => 'ZORAN VAKULA',
            'citat' => 'U Plesni centar Zagreb hodočastim gotovo svaki drugi dan.',
            'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/vakula-min.png',
        ),
        array(
            'ime'   => 'ZRINKA CVITEŠIĆ',
            'citat' => 'Uživala sam u svakoj sekundi ovdje u plesnom centru.',
            'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/cvitesic-min.png',
        ),
    );
}
add_filter( 'pcz_poznati_fallback_data', 'pcz_poznati_default_fallback_data', 10, 1 );

/**
 * Debug funkcija - prikazuje putanje u admin baru
 */
function pcz_poznati_debug_info() {
    if ( ! current_user_can( 'manage_options' ) || ! is_admin_bar_showing() ) {
        return;
    }
    
    $paths = pcz_get_poznati_paths();
    
    if ( $paths && isset( $_GET['pcz_debug'] ) ) {
        echo '<div style="background:#333;color:#fff;padding:10px;position:fixed;bottom:0;left:0;right:0;z-index:99999;font-size:12px;">';
        echo '<strong>pcz Poznati Debug:</strong><br>';
        echo 'Path: ' . esc_html( $paths['path'] ) . '<br>';
        echo 'URL: ' . esc_html( $paths['url'] ) . '<br>';
        echo 'File exists: ' . ( file_exists( $paths['path'] . 'poznati.php' ) ? '✅' : '❌' );
        echo '</div>';
    }
}
add_action( 'wp_footer', 'pcz_poznati_debug_info' );

