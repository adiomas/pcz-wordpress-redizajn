<?php
/**
 * pcz Hero Sekcija - Inicijalizacija
 * 
 * Ovaj snippet registrira:
 * - [pcz_hero] shortcode za prikaz sekcije
 * - Automatsko učitavanje CSS asseta
 * 
 * INSTALACIJA:
 * 1. Dodati ovaj kod u Code Snippets plugin
 * 2. Aktivirati snippet
 * 3. Koristiti [pcz_hero] shortcode ili Oxygen Code Block
 * 
 * @package pcz_Redizajn
 * @since 1.0.0
 */

// Sprječava direktan pristup
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Definira putanje za pcz Hero fajlove
 * 
 * Kompatibilno s WP Staging okruženjima
 * 
 * Redoslijed provjere:
 * 1. Uploads folder (wp-content/uploads/pcz-hero/) - PREPORUČENO
 * 2. Child tema (wp-content/themes/tvoja-tema/pcz-hero/)
 * 3. Parent tema
 */
function pcz_get_hero_paths() {
    $uploads = wp_upload_dir();
    
    // Opcija 1: Uploads folder (PREPORUČENO)
    if ( ! empty( $uploads['basedir'] ) && ! empty( $uploads['baseurl'] ) ) {
        $uploads_path = trailingslashit( $uploads['basedir'] ) . 'pcz-hero/';
        $uploads_url = trailingslashit( $uploads['baseurl'] ) . 'pcz-hero/';
        
        $test_file = $uploads_path . 'hero.php';
        if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
            return array(
                'path' => $uploads_path,
                'url'  => $uploads_url,
            );
        }
    }
    
    // Fallback: WP_CONTENT_DIR
    if ( defined( 'WP_CONTENT_DIR' ) && defined( 'WP_CONTENT_URL' ) ) {
        $wp_content_path = trailingslashit( WP_CONTENT_DIR ) . 'uploads/pcz-hero/';
        $wp_content_url = trailingslashit( WP_CONTENT_URL ) . 'uploads/pcz-hero/';
        
        $test_file = $wp_content_path . 'hero.php';
        if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
            return array(
                'path' => $wp_content_path,
                'url'  => $wp_content_url,
            );
        }
    }
    
    // Opcija 2: Child tema
    $child_theme_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-hero/';
    $child_theme_url = trailingslashit( get_stylesheet_directory_uri() ) . 'pcz-hero/';
    
    $is_oxygen_folder = ( strpos( $child_theme_path, 'oxygen' ) !== false );
    
    if ( ! $is_oxygen_folder ) {
        $test_file = $child_theme_path . 'hero.php';
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
            'path' => trailingslashit( $uploads['basedir'] ) . 'pcz-hero/',
            'url'  => trailingslashit( $uploads['baseurl'] ) . 'pcz-hero/',
        );
    }
    
    return null;
}

/**
 * Učitaj CSS za Hero sekciju
 */
function pcz_hero_enqueue_styles() {
    $paths = pcz_get_hero_paths();
    
    if ( ! $paths ) {
        return;
    }
    
    $css_file = $paths['path'] . 'hero.css';
    $css_url = $paths['url'] . 'hero.css';
    
    if ( file_exists( $css_file ) ) {
        wp_enqueue_style(
            'pcz-hero',
            $css_url,
            array(),
            filemtime( $css_file )
        );
    }
    
    // Dancing Script font za tagline
    wp_enqueue_style(
        'pcz-hero-font',
        'https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600&display=swap',
        array(),
        null
    );
}

/**
 * Registriraj [pcz_hero] shortcode
 */
function pcz_hero_shortcode( $atts ) {
    $paths = pcz_get_hero_paths();
    
    if ( ! $paths ) {
        if ( current_user_can( 'manage_options' ) ) {
            return '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;">
                <strong>pcz Hero Error:</strong> Fajlovi nisu pronađeni. 
                Uploadajte ih u <code>wp-content/uploads/pcz-hero/</code>
            </div>';
        }
        return '';
    }
    
    $hero_file = $paths['path'] . 'hero.php';
    
    if ( ! file_exists( $hero_file ) ) {
        if ( current_user_can( 'manage_options' ) ) {
            return '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;">
                <strong>pcz Hero Error:</strong> hero.php nije pronađen.<br>
                Lokacija: ' . esc_html( $hero_file ) . '
            </div>';
        }
        return '';
    }
    
    // Učitaj assets
    pcz_hero_enqueue_styles();
    
    // Output buffer za include
    ob_start();
    include $hero_file;
    return ob_get_clean();
}
add_shortcode( 'pcz_hero', 'pcz_hero_shortcode' );

/**
 * Automatski učitaj CSS na naslovnici
 * (Za slučaj korištenja u Oxygen Code Block-u bez shortcode-a)
 */
function pcz_hero_auto_enqueue() {
    // Učitaj samo na naslovnici
    if ( is_front_page() || is_home() ) {
        pcz_hero_enqueue_styles();
    }
}
add_action( 'wp_enqueue_scripts', 'pcz_hero_auto_enqueue' );

/**
 * Debug funkcija - prikazuje putanje u admin baru
 */
function pcz_hero_debug_info() {
    if ( ! current_user_can( 'manage_options' ) || ! is_admin_bar_showing() ) {
        return;
    }
    
    $paths = pcz_get_hero_paths();
    
    if ( $paths && isset( $_GET['pcz_debug'] ) ) {
        echo '<div style="background:#333;color:#fff;padding:10px;position:fixed;bottom:0;left:0;right:0;z-index:99999;font-size:12px;">';
        echo '<strong>pcz Hero Debug:</strong><br>';
        echo 'Path: ' . esc_html( $paths['path'] ) . '<br>';
        echo 'URL: ' . esc_html( $paths['url'] ) . '<br>';
        echo 'File exists: ' . ( file_exists( $paths['path'] . 'hero.php' ) ? '✅' : '❌' );
        echo '</div>';
    }
}
add_action( 'wp_footer', 'pcz_hero_debug_info' );

