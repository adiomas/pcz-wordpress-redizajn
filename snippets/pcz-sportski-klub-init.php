<?php
/**
 * pcz "Sportski Klub" - Inicijalizacija
 * 
 * Ovaj snippet registrira:
 * - [pcz_sportski_klub] shortcode za prikaz sekcije
 * - Automatsko učitavanje CSS asseta
 * 
 * INSTALACIJA:
 * 1. Dodati ovaj kod u Code Snippets plugin
 * 2. Aktivirati snippet
 * 3. Koristiti [pcz_sportski_klub] shortcode ili Oxygen Code Block
 * 
 * @package pcz_Redizajn
 * @since 1.0.0
 */

// Sprječava direktan pristup
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Definira putanje za pcz Sportski Klub fajlove
 * 
 * Kompatibilno s WP Staging okruženjima
 * 
 * Redoslijed provjere:
 * 1. Uploads folder (wp-content/uploads/pcz-sportski-klub/) - PREPORUČENO
 * 2. Child tema (wp-content/themes/tvoja-tema/pcz-sportski-klub/)
 * 3. Parent tema
 */
function pcz_get_sportski_klub_paths() {
    $uploads = wp_upload_dir();
    
    // Opcija 1: Uploads folder (PREPORUČENO)
    if ( ! empty( $uploads['basedir'] ) && ! empty( $uploads['baseurl'] ) ) {
        $uploads_path = trailingslashit( $uploads['basedir'] ) . 'pcz-sportski-klub/';
        $uploads_url = trailingslashit( $uploads['baseurl'] ) . 'pcz-sportski-klub/';
        
        $test_file = $uploads_path . 'sportski-klub.php';
        if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
            return array(
                'path' => $uploads_path,
                'url'  => $uploads_url,
            );
        }
    }
    
    // Fallback: WP_CONTENT_DIR
    if ( defined( 'WP_CONTENT_DIR' ) && defined( 'WP_CONTENT_URL' ) ) {
        $wp_content_path = trailingslashit( WP_CONTENT_DIR ) . 'uploads/pcz-sportski-klub/';
        $wp_content_url = trailingslashit( WP_CONTENT_URL ) . 'uploads/pcz-sportski-klub/';
        
        $test_file = $wp_content_path . 'sportski-klub.php';
        if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
            return array(
                'path' => $wp_content_path,
                'url'  => $wp_content_url,
            );
        }
    }
    
    // Opcija 2: Child tema
    $child_theme_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-sportski-klub/';
    $child_theme_url = trailingslashit( get_stylesheet_directory_uri() ) . 'pcz-sportski-klub/';
    
    $is_oxygen_folder = ( strpos( $child_theme_path, 'oxygen' ) !== false );
    
    if ( ! $is_oxygen_folder ) {
        $test_file = $child_theme_path . 'sportski-klub.php';
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
            'path' => trailingslashit( $uploads['basedir'] ) . 'pcz-sportski-klub/',
            'url'  => trailingslashit( $uploads['baseurl'] ) . 'pcz-sportski-klub/',
        );
    }
    
    return null;
}

/**
 * Učitaj CSS za Sportski Klub sekciju
 */
function pcz_sportski_klub_enqueue_styles() {
    $paths = pcz_get_sportski_klub_paths();
    
    if ( ! $paths ) {
        return;
    }
    
    $css_file = $paths['path'] . 'sportski-klub.css';
    $css_url = $paths['url'] . 'sportski-klub.css';
    
    if ( file_exists( $css_file ) ) {
        wp_enqueue_style(
            'pcz-sportski-klub',
            $css_url,
            array(),
            filemtime( $css_file )
        );
    }
}

/**
 * Registriraj [pcz_sportski_klub] shortcode
 */
function pcz_sportski_klub_shortcode( $atts ) {
    $paths = pcz_get_sportski_klub_paths();
    
    if ( ! $paths ) {
        if ( current_user_can( 'manage_options' ) ) {
            return '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;">
                <strong>pcz Sportski Klub Error:</strong> Fajlovi nisu pronađeni. 
                Uploadajte ih u <code>wp-content/uploads/pcz-sportski-klub/</code>
            </div>';
        }
        return '';
    }
    
    $component_file = $paths['path'] . 'sportski-klub.php';
    
    if ( ! file_exists( $component_file ) ) {
        if ( current_user_can( 'manage_options' ) ) {
            return '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;">
                <strong>pcz Sportski Klub Error:</strong> sportski-klub.php nije pronađen.<br>
                Lokacija: ' . esc_html( $component_file ) . '
            </div>';
        }
        return '';
    }
    
    // Učitaj assets
    pcz_sportski_klub_enqueue_styles();
    
    // Output buffer za include
    ob_start();
    include $component_file;
    return ob_get_clean();
}
add_shortcode( 'pcz_sportski_klub', 'pcz_sportski_klub_shortcode' );

/**
 * Fallback podaci za "Sportski Klub" sekciju
 * 
 * Ovi podaci se koriste kada ACF nema podataka i fallback je omogućen.
 * NAPOMENA: Ovo je JEDINI IZVOR fallback podataka - NE dupliciramo u sportski-klub.php!
 * 
 * @param array $data Postojeći fallback podaci (obično prazni)
 * @return array Podaci za prikaz
 */
function pcz_sportski_klub_default_fallback_data( $data ) {
    // Ako već ima podataka, vrati ih
    if ( ! empty( $data ) ) {
        return $data;
    }
    
    // Default fallback podaci za demo/development
    return array(
        'opis' => 'uči, istražuje, stvara i svakodnevno pruža zadovoljstvo svakom našem plesaču – učeniku i njihovim roditeljima.',
        'nastavak_opisa' => 'Stvaramo sportske plesne parove s najnovijim plesnim stilom latinsko-američkih i standardnih plesova, sigurno i kontinuirano idemo naprijed oblikujući budućnost naših plesača, a stalnim usavršavanjem svih naših trenera – učitelja, osiguravamo velik, uspješan i zadovoljan Plesni Centar Zagreb.',
        'linkovi' => array(
            array(
                'tekst' => 'RASPORED TRENINGA',
                'url'   => home_url( '/raspored-treninga/' ),
            ),
            array(
                'tekst' => 'STRUČNI TIM',
                'url'   => home_url( '/strucni-tim/' ),
            ),
            array(
                'tekst' => 'GALERIJA',
                'url'   => home_url( '/galerija/' ),
            ),
            array(
                'tekst' => 'PROJEKTI',
                'url'   => home_url( '/projekti/' ),
            ),
            array(
                'tekst' => 'PRIVATNA PODUKA',
                'url'   => home_url( '/privatna-poduka/' ),
            ),
        ),
    );
}
add_filter( 'pcz_sportski_klub_fallback_data', 'pcz_sportski_klub_default_fallback_data', 10, 1 );

/**
 * Debug funkcija - prikazuje putanje u admin baru
 */
function pcz_sportski_klub_debug_info() {
    if ( ! current_user_can( 'manage_options' ) || ! is_admin_bar_showing() ) {
        return;
    }
    
    $paths = pcz_get_sportski_klub_paths();
    
    if ( $paths && isset( $_GET['pcz_debug'] ) ) {
        echo '<div style="background:#FF6B00;color:#fff;padding:10px;position:fixed;bottom:0;left:0;right:0;z-index:99999;font-size:12px;">';
        echo '<strong>pcz Sportski Klub Debug:</strong><br>';
        echo 'Path: ' . esc_html( $paths['path'] ) . '<br>';
        echo 'URL: ' . esc_html( $paths['url'] ) . '<br>';
        echo 'File exists: ' . ( file_exists( $paths['path'] . 'sportski-klub.php' ) ? '✅' : '❌' );
        echo '</div>';
    }
}
add_action( 'wp_footer', 'pcz_sportski_klub_debug_info' );


