<?php
/**
 * pcz "Upoznajte nas" - Inicijalizacija
 * 
 * Ovaj snippet registrira:
 * - [pcz_upoznajte] shortcode za prikaz sekcije
 * - Automatsko učitavanje CSS asseta
 * 
 * INSTALACIJA:
 * 1. Dodati ovaj kod u Code Snippets plugin
 * 2. Aktivirati snippet
 * 3. Koristiti [pcz_upoznajte] shortcode ili Oxygen Code Block
 * 
 * BRAND VISIBILITY:
 * - Ova sekcija se prikazuje SAMO za Plesnu Školu
 * - Za Sportski Klub sekcija je skrivena
 * 
 * @package pcz_Redizajn
 * @since 1.0.0
 */

// Sprječava direktan pristup
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Definira putanje za pcz Upoznajte fajlove
 * 
 * Kompatibilno s WP Staging okruženjima
 * 
 * Redoslijed provjere:
 * 1. Uploads folder (wp-content/uploads/pcz-upoznajte/) - PREPORUČENO
 * 2. Child tema (wp-content/themes/tvoja-tema/pcz-upoznajte/)
 * 3. Parent tema
 */
function pcz_get_upoznajte_paths() {
    $uploads = wp_upload_dir();
    
    // Opcija 1: Uploads folder (PREPORUČENO)
    if ( ! empty( $uploads['basedir'] ) && ! empty( $uploads['baseurl'] ) ) {
        $uploads_path = trailingslashit( $uploads['basedir'] ) . 'pcz-upoznajte/';
        $uploads_url = trailingslashit( $uploads['baseurl'] ) . 'pcz-upoznajte/';
        
        $test_file = $uploads_path . 'upoznajte.php';
        if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
            return array(
                'path' => $uploads_path,
                'url'  => $uploads_url,
            );
        }
    }
    
    // Fallback: WP_CONTENT_DIR
    if ( defined( 'WP_CONTENT_DIR' ) && defined( 'WP_CONTENT_URL' ) ) {
        $wp_content_path = trailingslashit( WP_CONTENT_DIR ) . 'uploads/pcz-upoznajte/';
        $wp_content_url = trailingslashit( WP_CONTENT_URL ) . 'uploads/pcz-upoznajte/';
        
        $test_file = $wp_content_path . 'upoznajte.php';
        if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
            return array(
                'path' => $wp_content_path,
                'url'  => $wp_content_url,
            );
        }
    }
    
    // Opcija 2: Child tema
    $child_theme_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-upoznajte/';
    $child_theme_url = trailingslashit( get_stylesheet_directory_uri() ) . 'pcz-upoznajte/';
    
    $is_oxygen_folder = ( strpos( $child_theme_path, 'oxygen' ) !== false );
    
    if ( ! $is_oxygen_folder ) {
        $test_file = $child_theme_path . 'upoznajte.php';
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
            'path' => trailingslashit( $uploads['basedir'] ) . 'pcz-upoznajte/',
            'url'  => trailingslashit( $uploads['baseurl'] ) . 'pcz-upoznajte/',
        );
    }
    
    return null;
}

/**
 * Učitaj CSS za Upoznajte sekciju
 */
function pcz_upoznajte_enqueue_styles() {
    $paths = pcz_get_upoznajte_paths();
    
    if ( ! $paths ) {
        return;
    }
    
    $css_file = $paths['path'] . 'upoznajte.css';
    $css_url = $paths['url'] . 'upoznajte.css';
    
    if ( file_exists( $css_file ) ) {
        wp_enqueue_style(
            'pcz-upoznajte',
            $css_url,
            array(),
            filemtime( $css_file )
        );
    }
}

/**
 * Registriraj [pcz_upoznajte] shortcode
 */
function pcz_upoznajte_shortcode( $atts ) {
    // Provjeri brand visibility
    $allowed_brands = array( 'plesna-skola' );
    $current_brand = function_exists( 'pcz_get_current_brand_id' ) 
        ? pcz_get_current_brand_id() 
        : 'plesna-skola';
    
    $is_visible = in_array( $current_brand, $allowed_brands, true );
    $visibility_class = $is_visible ? 'pcz-upoznajte--visible' : 'pcz-upoznajte--hidden';
    
    // Ako potpuno skriveno (neće se ni renderati)
    // if ( ! $is_visible ) { return ''; }
    
    $paths = pcz_get_upoznajte_paths();
    
    if ( ! $paths ) {
        if ( current_user_can( 'manage_options' ) ) {
            return '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;">
                <strong>pcz Upoznajte Error:</strong> Fajlovi nisu pronađeni. 
                Uploadajte ih u <code>wp-content/uploads/pcz-upoznajte/</code>
            </div>';
        }
        return '';
    }
    
    $upoznajte_file = $paths['path'] . 'upoznajte.php';
    
    if ( ! file_exists( $upoznajte_file ) ) {
        if ( current_user_can( 'manage_options' ) ) {
            return '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;">
                <strong>pcz Upoznajte Error:</strong> upoznajte.php nije pronađen.<br>
                Lokacija: ' . esc_html( $upoznajte_file ) . '
            </div>';
        }
        return '';
    }
    
    // Učitaj assets
    pcz_upoznajte_enqueue_styles();
    
    // Output buffer za include
    ob_start();
    include $upoznajte_file;
    return ob_get_clean();
}
add_shortcode( 'pcz_upoznajte', 'pcz_upoznajte_shortcode' );

/**
 * Fallback podaci za "Upoznajte nas" sekciju
 * 
 * @param array $data Postojeći fallback podaci (obično prazni)
 * @return array Podaci za prikaz
 */
function pcz_upoznajte_default_fallback_data( $data ) {
    // Ako već ima podataka, vrati ih
    if ( ! empty( $data ) ) {
        return $data;
    }
    
    // Default fallback podaci za demo/development
    return array(
        'tekst' => 'U današnjem vremenu stalnih promjena, brojnih ponuda i odluka, dobro je imati jednu konstantu – nešto sigurno, pouzdano dobro, provjereno, svoje...',
        'nastavak_teksta' => '– najprepoznatljiviji, najprisutniji i najraznolikiji je Plesni centar u Hrvatskoj! Već više godina smo na samom vrhu, zajedno s vama! Veselimo se Vašem posjetu',
        'slika' => 'https://pcz.hr/wp-content/uploads/2023/01/nicolas-i-martina.jpg',
        'slika_mobile' => 'https://pcz.hr/wp-content/uploads/2023/01/nicolas-i-martina-mobile.jpg',
        'opacity' => 40, // Default: 40% opacity overlay
    );
}
add_filter( 'pcz_upoznajte_fallback_data', 'pcz_upoznajte_default_fallback_data', 10, 1 );

/**
 * Debug funkcija - prikazuje putanje u admin baru
 */
function pcz_upoznajte_debug_info() {
    if ( ! current_user_can( 'manage_options' ) || ! is_admin_bar_showing() ) {
        return;
    }
    
    $paths = pcz_get_upoznajte_paths();
    
    if ( $paths && isset( $_GET['pcz_debug'] ) ) {
        echo '<div style="background:#333;color:#fff;padding:10px;position:fixed;bottom:0;left:0;right:0;z-index:99999;font-size:12px;">';
        echo '<strong>pcz Upoznajte Debug:</strong><br>';
        echo 'Path: ' . esc_html( $paths['path'] ) . '<br>';
        echo 'URL: ' . esc_html( $paths['url'] ) . '<br>';
        echo 'File exists: ' . ( file_exists( $paths['path'] . 'upoznajte.php' ) ? '✅' : '❌' );
        echo '</div>';
    }
}
add_action( 'wp_footer', 'pcz_upoznajte_debug_info' );

