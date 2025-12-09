<?php
/**
 * pcz "O Nama - Sportski Klub" - Inicijalizacija
 * 
 * Ovaj snippet registrira:
 * - [pcz_o_nama_spk] shortcode za prikaz sekcije
 * - Automatsko učitavanje CSS asseta
 * - Google Fonts za Playfair Display i DM Sans
 * 
 * INSTALACIJA:
 * 1. Dodati ovaj kod u Code Snippets plugin
 * 2. Aktivirati snippet
 * 3. Koristiti [pcz_o_nama_spk] shortcode ili Oxygen Code Block
 * 
 * @package pcz_Redizajn
 * @since 2.0.0
 */

// Sprječava direktan pristup
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Definira putanje za pcz O Nama SPK fajlove
 * 
 * Kompatibilno s WP Staging okruženjima
 * 
 * Redoslijed provjere:
 * 1. Uploads folder (wp-content/uploads/pcz-o-nama-spk/) - PREPORUČENO
 * 2. Child tema (wp-content/themes/tvoja-tema/pcz-o-nama-spk/)
 * 3. Parent tema
 */
function pcz_get_o_nama_spk_paths() {
    $uploads = wp_upload_dir();
    
    // Opcija 1: Uploads folder (PREPORUČENO)
    if ( ! empty( $uploads['basedir'] ) && ! empty( $uploads['baseurl'] ) ) {
        $uploads_path = trailingslashit( $uploads['basedir'] ) . 'pcz-o-nama-spk/';
        $uploads_url = trailingslashit( $uploads['baseurl'] ) . 'pcz-o-nama-spk/';
        
        $test_file = $uploads_path . 'o-nama-spk.php';
        if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
            return array(
                'path' => $uploads_path,
                'url'  => $uploads_url,
            );
        }
    }
    
    // Fallback: WP_CONTENT_DIR
    if ( defined( 'WP_CONTENT_DIR' ) && defined( 'WP_CONTENT_URL' ) ) {
        $wp_content_path = trailingslashit( WP_CONTENT_DIR ) . 'uploads/pcz-o-nama-spk/';
        $wp_content_url = trailingslashit( WP_CONTENT_URL ) . 'uploads/pcz-o-nama-spk/';
        
        $test_file = $wp_content_path . 'o-nama-spk.php';
        if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
            return array(
                'path' => $wp_content_path,
                'url'  => $wp_content_url,
            );
        }
    }
    
    // Opcija 2: Child tema
    $child_theme_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-o-nama-spk/';
    $child_theme_url = trailingslashit( get_stylesheet_directory_uri() ) . 'pcz-o-nama-spk/';
    
    $is_oxygen_folder = ( strpos( $child_theme_path, 'oxygen' ) !== false );
    
    if ( ! $is_oxygen_folder ) {
        $test_file = $child_theme_path . 'o-nama-spk.php';
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
            'path' => trailingslashit( $uploads['basedir'] ) . 'pcz-o-nama-spk/',
            'url'  => trailingslashit( $uploads['baseurl'] ) . 'pcz-o-nama-spk/',
        );
    }
    
    return null;
}

/**
 * Učitaj CSS i Google Fonts za O Nama SPK sekciju
 */
function pcz_o_nama_spk_enqueue_styles() {
    $paths = pcz_get_o_nama_spk_paths();
    
    // Google Fonts - Playfair Display + DM Sans
    wp_enqueue_style(
        'pcz-o-nama-spk-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600;700&display=swap',
        array(),
        null
    );
    
    if ( ! $paths ) {
        return;
    }
    
    $css_file = $paths['path'] . 'o-nama-spk.css';
    $css_url = $paths['url'] . 'o-nama-spk.css';
    
    if ( file_exists( $css_file ) ) {
        wp_enqueue_style(
            'pcz-o-nama-spk',
            $css_url,
            array( 'pcz-o-nama-spk-fonts' ),
            filemtime( $css_file )
        );
    }
}

/**
 * Registriraj [pcz_o_nama_spk] shortcode
 */
function pcz_o_nama_spk_shortcode( $atts ) {
    $paths = pcz_get_o_nama_spk_paths();
    
    if ( ! $paths ) {
        if ( current_user_can( 'manage_options' ) ) {
            return '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;">
                <strong>pcz O Nama SPK Error:</strong> Fajlovi nisu pronađeni. 
                Uploadajte ih u <code>wp-content/uploads/pcz-o-nama-spk/</code>
            </div>';
        }
        return '';
    }
    
    $component_file = $paths['path'] . 'o-nama-spk.php';
    
    if ( ! file_exists( $component_file ) ) {
        if ( current_user_can( 'manage_options' ) ) {
            return '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;">
                <strong>pcz O Nama SPK Error:</strong> o-nama-spk.php nije pronađen.<br>
                Lokacija: ' . esc_html( $component_file ) . '
            </div>';
        }
        return '';
    }
    
    // Učitaj assets
    pcz_o_nama_spk_enqueue_styles();
    
    // Output buffer za include
    ob_start();
    include $component_file;
    return ob_get_clean();
}
add_shortcode( 'pcz_o_nama_spk', 'pcz_o_nama_spk_shortcode' );

/**
 * Fallback podaci za "O Nama - Sportski Klub" sekciju
 * 
 * Struktura odgovara preview.html dizajnu
 * 
 * @param array $data Postojeći fallback podaci
 * @return array Podaci za prikaz
 */
function pcz_o_nama_spk_default_fallback_data( $data ) {
    if ( ! empty( $data ) ) {
        return $data;
    }
    
    return array(
        'logo'   => '',
        'slika'  => 'https://images.unsplash.com/photo-1504609773096-104ff2c73ba4?w=1920&q=80',
        
        // Lead paragraf
        'lead' => '<p><strong>Sportski ples</strong> prema mišljenju mnogih, najljepši je dvoranski sport na svijetu. Na vrlo specifičan i atraktivan način u sebi sjedinjuje sport, umjetnost, glamur i smisao za show.</p>',
        
        // Lijevi stupac
        'lijevi_stupac' => '<p>No iza sveg tog glamura, i malih i velikih plesača stoje sati i godine napornog rada, odricanja i treninga.</p>
<p>Znanje je temelj svakog uspjeha i jedini put u bolju budućnost, a potvrdu svojega rada vidimo svake godine u osmjehu svakog našeg plesača, roditelja i učitelja-trenera.</p>
<p>Sve što Vam je potrebno:</p>
<ul>
<li>redovan trening</li>
<li>stalan i pouzdan plesni partner</li>
<li>stručni i kvalitetni treneri-učitelji</li>
</ul>',
        
        // Desni stupac
        'desni_stupac' => '<p>Plesači se natječu u tri sportske discipline: LA (latinsko-američki plesovi), ST (standardni plesovi) i kombinacija 10 sportskih plesova.</p>
<p>Natječu se u više starosnih razreda, od mlađih osnovaca do veterana.</p>',
        
        // Highlight box
        'highlight_naslov' => 'Vodstvo',
        'highlight_tekst' => '<p>Sportsku grupu vodi naš najuspješniji hrvatski plesni par: <strong>Ksenija Pluščec Quesnoit & Nicolas Quesnoit</strong>, profesionalni prvaci Hrvatske.</p>',
        
        // Kontakt
        'kontakt_osoba' => 'Dijana',
        'kontakt_telefon' => '091 1157 442',
        'kontakt_email' => 'spkzagreb@pcz.hr',
    );
}
add_filter( 'pcz_o_nama_spk_fallback_data', 'pcz_o_nama_spk_default_fallback_data', 10, 1 );

/**
 * Debug funkcija - prikazuje putanje u admin baru
 */
function pcz_o_nama_spk_debug_info() {
    if ( ! current_user_can( 'manage_options' ) || ! is_admin_bar_showing() ) {
        return;
    }
    
    $paths = pcz_get_o_nama_spk_paths();
    
    if ( $paths && isset( $_GET['pcz_debug'] ) ) {
        echo '<div style="background:#333;color:#fff;padding:10px;position:fixed;bottom:0;left:0;right:0;z-index:99999;font-size:12px;">';
        echo '<strong>pcz O Nama SPK Debug:</strong><br>';
        echo 'Path: ' . esc_html( $paths['path'] ) . '<br>';
        echo 'URL: ' . esc_html( $paths['url'] ) . '<br>';
        echo 'File exists: ' . ( file_exists( $paths['path'] . 'o-nama-spk.php' ) ? '✅' : '❌' );
        echo '</div>';
    }
}
add_action( 'wp_footer', 'pcz_o_nama_spk_debug_info' );
