<?php
/**
 * pcz "Dodatne Informacije" - Inicijalizacija
 * 
 * Ovaj snippet registrira:
 * - [pcz_dodatne_info] shortcode za prikaz sekcije
 * - Automatsko učitavanje CSS asseta
 * 
 * INSTALACIJA:
 * 1. Dodati ovaj kod u Code Snippets plugin
 * 2. Aktivirati snippet
 * 3. Koristiti [pcz_dodatne_info] shortcode ili Oxygen Code Block
 * 
 * BRAND VISIBILITY:
 * - Ova sekcija se prikazuje SAMO za brand "Sportski Klub"
 * 
 * @package pcz_Redizajn
 * @since 1.0.0
 */

// Sprječava direktan pristup
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Definira putanje za pcz Dodatne Info fajlove
 * 
 * Kompatibilno s WP Staging okruženjima
 * 
 * Redoslijed provjere:
 * 1. Uploads folder (wp-content/uploads/pcz-dodatne-info/) - PREPORUČENO
 * 2. Child tema (wp-content/themes/tvoja-tema/pcz-dodatne-info/)
 * 3. Parent tema
 */
function pcz_get_dodatne_info_paths() {
    $uploads = wp_upload_dir();
    
    // Opcija 1: Uploads folder (PREPORUČENO)
    if ( ! empty( $uploads['basedir'] ) && ! empty( $uploads['baseurl'] ) ) {
        $uploads_path = trailingslashit( $uploads['basedir'] ) . 'pcz-dodatne-info/';
        $uploads_url = trailingslashit( $uploads['baseurl'] ) . 'pcz-dodatne-info/';
        
        $test_file = $uploads_path . 'dodatne-info.php';
        if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
            return array(
                'path' => $uploads_path,
                'url'  => $uploads_url,
            );
        }
    }
    
    // Fallback: WP_CONTENT_DIR
    if ( defined( 'WP_CONTENT_DIR' ) && defined( 'WP_CONTENT_URL' ) ) {
        $wp_content_path = trailingslashit( WP_CONTENT_DIR ) . 'uploads/pcz-dodatne-info/';
        $wp_content_url = trailingslashit( WP_CONTENT_URL ) . 'uploads/pcz-dodatne-info/';
        
        $test_file = $wp_content_path . 'dodatne-info.php';
        if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
            return array(
                'path' => $wp_content_path,
                'url'  => $wp_content_url,
            );
        }
    }
    
    // Opcija 2: Child tema
    $child_theme_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-dodatne-info/';
    $child_theme_url = trailingslashit( get_stylesheet_directory_uri() ) . 'pcz-dodatne-info/';
    
    $is_oxygen_folder = ( strpos( $child_theme_path, 'oxygen' ) !== false );
    
    if ( ! $is_oxygen_folder ) {
        $test_file = $child_theme_path . 'dodatne-info.php';
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
            'path' => trailingslashit( $uploads['basedir'] ) . 'pcz-dodatne-info/',
            'url'  => trailingslashit( $uploads['baseurl'] ) . 'pcz-dodatne-info/',
        );
    }
    
    return null;
}

/**
 * Učitaj CSS za Dodatne Info sekciju
 */
function pcz_dodatne_info_enqueue_styles() {
    $paths = pcz_get_dodatne_info_paths();
    
    if ( ! $paths ) {
        return;
    }
    
    $css_file = $paths['path'] . 'dodatne-info.css';
    $css_url = $paths['url'] . 'dodatne-info.css';
    
    if ( file_exists( $css_file ) ) {
        wp_enqueue_style(
            'pcz-dodatne-info',
            $css_url,
            array(),
            filemtime( $css_file )
        );
    }
}

/**
 * Registriraj [pcz_dodatne_info] shortcode
 */
function pcz_dodatne_info_shortcode( $atts ) {
    // Provjeri brand visibility
    $allowed_brands = [ 'sportski-klub' ];
    $current_brand = function_exists( 'pcz_get_current_brand_id' ) 
        ? pcz_get_current_brand_id() 
        : 'plesna-skola';
    
    $is_visible = in_array( $current_brand, $allowed_brands, true );
    
    // Postavi visibility klasu za PHP
    $visibility_class = $is_visible ? 'pcz-dodatne-info--visible' : 'pcz-dodatne-info--hidden';
    
    // Ako želimo potpuno sakriti (ne renderirati) za druge brandove, odkomentiraj:
    // if ( ! $is_visible ) { return ''; }
    
    $paths = pcz_get_dodatne_info_paths();
    
    if ( ! $paths ) {
        if ( current_user_can( 'manage_options' ) ) {
            return '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;">
                <strong>pcz Dodatne Info Error:</strong> Fajlovi nisu pronađeni. 
                Uploadajte ih u <code>wp-content/uploads/pcz-dodatne-info/</code>
            </div>';
        }
        return '';
    }
    
    $dodatne_file = $paths['path'] . 'dodatne-info.php';
    
    if ( ! file_exists( $dodatne_file ) ) {
        if ( current_user_can( 'manage_options' ) ) {
            return '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;">
                <strong>pcz Dodatne Info Error:</strong> dodatne-info.php nije pronađen.<br>
                Lokacija: ' . esc_html( $dodatne_file ) . '
            </div>';
        }
        return '';
    }
    
    // Učitaj assets
    pcz_dodatne_info_enqueue_styles();
    
    // Output buffer za include
    ob_start();
    include $dodatne_file;
    return ob_get_clean();
}
add_shortcode( 'pcz_dodatne_info', 'pcz_dodatne_info_shortcode' );

/**
 * Fallback podaci za "Dodatne Informacije" sekciju
 * 
 * Ovi podaci se koriste kada ACF nema podataka i fallback je omogućen.
 * 
 * @param array $data Postojeći fallback podaci (obično prazni)
 * @return array Stavke za prikaz
 */
function pcz_dodatne_info_default_fallback_data( $data ) {
    // Ako već ima podataka, vrati ih
    if ( ! empty( $data ) ) {
        return $data;
    }
    
    // Default fallback podaci za demo/development
    return array(
        'stavke' => array(
            array(
                'ikona'      => 'euro',
                'custom_svg' => '',
                'naziv'      => 'CJENIK',
                'url'        => home_url( '/cjenik/' ),
            ),
            array(
                'ikona'      => 'kontakt',
                'custom_svg' => '',
                'naziv'      => 'KONTAKT',
                'url'        => home_url( '/kontakt/' ),
            ),
            array(
                'ikona'      => 'lokacija',
                'custom_svg' => '',
                'naziv'      => 'LOKACIJE TRENINGA',
                'url'        => home_url( '/lokacije-treninga/' ),
            ),
            array(
                'ikona'      => 'trofej',
                'custom_svg' => '',
                'naziv'      => 'NAŠI REZULTATI',
                'url'        => home_url( '/rezultati/' ),
            ),
            array(
                'ikona'      => 'kamp',
                'custom_svg' => '',
                'naziv'      => 'KAMPOVI I RADIONICE',
                'url'        => home_url( '/kampovi/' ),
            ),
            array(
                'ikona'      => 'faq',
                'custom_svg' => '',
                'naziv'      => 'FAQ',
                'url'        => home_url( '/faq/' ),
            ),
            array(
                'ikona'      => 'podrska',
                'custom_svg' => '',
                'naziv'      => 'PRIVATNA PODRŠKA',
                'url'        => home_url( '/privatna-podrska/' ),
            ),
        ),
    );
}
add_filter( 'pcz_dodatne_info_fallback_data', 'pcz_dodatne_info_default_fallback_data', 10, 1 );

/**
 * Debug funkcija - prikazuje putanje u admin baru
 */
function pcz_dodatne_info_debug_info() {
    if ( ! current_user_can( 'manage_options' ) || ! is_admin_bar_showing() ) {
        return;
    }
    
    $paths = pcz_get_dodatne_info_paths();
    
    if ( $paths && isset( $_GET['pcz_debug'] ) ) {
        echo '<div style="background:#333;color:#fff;padding:10px;position:fixed;bottom:0;left:0;right:0;z-index:99999;font-size:12px;">';
        echo '<strong>pcz Dodatne Info Debug:</strong><br>';
        echo 'Path: ' . esc_html( $paths['path'] ) . '<br>';
        echo 'URL: ' . esc_html( $paths['url'] ) . '<br>';
        echo 'File exists: ' . ( file_exists( $paths['path'] . 'dodatne-info.php' ) ? '✅' : '❌' );
        echo '</div>';
    }
}
add_action( 'wp_footer', 'pcz_dodatne_info_debug_info' );



