<?php
/**
 * pcz Custom Footer - Inicijalizacija
 * 
 * Ovaj snippet registrira:
 * - [pcz_footer] shortcode za prikaz footera
 * - Automatsko učitavanje CSS asseta
 * 
 * INSTALACIJA:
 * 1. Dodati ovaj kod u Code Snippets plugin
 * 2. Aktivirati snippet
 * 3. Koristiti [pcz_footer] shortcode u Oxygen Footer template-u
 * 
 * @package pcz_Redizajn
 * @since 1.0.0
 */

// Sprječava direktan pristup
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Definira putanje za pcz Footer fajlove
 * 
 * Kompatibilno s WP Staging okruženjima (defiant, staging, itd.)
 * Koristi WordPress konstante koje automatski detektiraju prave putanje
 * 
 * Redoslijed provjere:
 * 1. Uploads folder (wp-content/uploads/pcz-footer/) - PREPORUČENO ZA OXYGEN
 * 2. Child tema (wp-content/themes/tvoja-tema/pcz-footer/)
 * 3. Parent tema (wp-content/themes/parent-tema/pcz-footer/)
 */
function pcz_get_footer_paths() {
    // Koristi WordPress konstante koje automatski detektiraju staging okruženja
    $uploads = wp_upload_dir();
    
    // Opcija 1: Uploads folder (PREPORUČENO ZA OXYGEN)
    if ( ! empty( $uploads['basedir'] ) && ! empty( $uploads['baseurl'] ) ) {
        $uploads_path = trailingslashit( $uploads['basedir'] ) . 'pcz-footer/';
        $uploads_url = trailingslashit( $uploads['baseurl'] ) . 'pcz-footer/';
        
        $test_file = $uploads_path . 'footer.php';
        if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
            return array(
                'path' => $uploads_path,
                'url'  => $uploads_url,
            );
        }
    }
    
    // Fallback: Koristi WP_CONTENT_DIR konstante
    if ( defined( 'WP_CONTENT_DIR' ) && defined( 'WP_CONTENT_URL' ) ) {
        $wp_content_uploads_path = trailingslashit( WP_CONTENT_DIR ) . 'uploads/pcz-footer/';
        $wp_content_uploads_url = trailingslashit( WP_CONTENT_URL ) . 'uploads/pcz-footer/';
        
        $test_file = $wp_content_uploads_path . 'footer.php';
        if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
            return array(
                'path' => $wp_content_uploads_path,
                'url'  => $wp_content_uploads_url,
            );
        }
    }
    
    // Opcija 2: Child tema
    $child_theme_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-footer/';
    $child_theme_url = trailingslashit( get_stylesheet_directory_uri() ) . 'pcz-footer/';
    
    $is_oxygen_folder = ( strpos( $child_theme_path, 'oxygen' ) !== false || strpos( $child_theme_path, 'component-framework' ) !== false );
    
    if ( ! $is_oxygen_folder ) {
        $test_file = $child_theme_path . 'footer.php';
        if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
            return array(
                'path' => $child_theme_path,
                'url'  => $child_theme_url,
            );
        }
    }
    
    // Opcija 3: Parent tema (fallback)
    $parent_theme_path = trailingslashit( get_template_directory() ) . 'pcz-footer/';
    $parent_theme_url = trailingslashit( get_template_directory_uri() ) . 'pcz-footer/';
    
    $is_oxygen_parent = ( strpos( $parent_theme_path, 'oxygen' ) !== false || strpos( $parent_theme_path, 'component-framework' ) !== false );
    
    if ( ! $is_oxygen_parent ) {
        $test_file = $parent_theme_path . 'footer.php';
        if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
            return array(
                'path' => $parent_theme_path,
                'url'  => $parent_theme_url,
            );
        }
    }
    
    // Fallback - vrati uploads putanju
    if ( ! empty( $uploads['basedir'] ) && ! empty( $uploads['baseurl'] ) ) {
        return array(
            'path' => trailingslashit( $uploads['basedir'] ) . 'pcz-footer/',
            'url'  => trailingslashit( $uploads['baseurl'] ) . 'pcz-footer/',
        );
    }
    
    // Posljednji fallback
    if ( defined( 'WP_CONTENT_DIR' ) && defined( 'WP_CONTENT_URL' ) ) {
        return array(
            'path' => trailingslashit( WP_CONTENT_DIR ) . 'uploads/pcz-footer/',
            'url'  => trailingslashit( WP_CONTENT_URL ) . 'uploads/pcz-footer/',
        );
    }
    
    // Apsolutni fallback
    return array(
        'path' => trailingslashit( ABSPATH ) . 'wp-content/uploads/pcz-footer/',
        'url'  => content_url( '/uploads/pcz-footer/' ),
    );
}

/**
 * pcz Footer Shortcode
 * 
 * Korištenje: [pcz_footer]
 */
add_shortcode( 'pcz_footer', 'pcz_footer_shortcode' );
function pcz_footer_shortcode( $atts ) {
    $paths = pcz_get_footer_paths();
    $php_file = $paths['path'] . 'footer.php';
    
    if ( ! file_exists( $php_file ) ) {
        if ( current_user_can( 'manage_options' ) ) {
            $uploads = wp_upload_dir();
            
            $possible_locations = array(
                'wp_upload_dir() basedir' => trailingslashit( $uploads['basedir'] ) . 'pcz-footer/footer.php',
                'WP_CONTENT_DIR' => defined( 'WP_CONTENT_DIR' ) ? trailingslashit( WP_CONTENT_DIR ) . 'uploads/pcz-footer/footer.php' : 'N/A',
                'Child tema' => trailingslashit( get_stylesheet_directory() ) . 'pcz-footer/footer.php',
            );
            
            $debug_info = '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:left;max-width:900px;margin:20px auto;font-family:monospace;font-size:13px;">
                <strong style="font-size:18px;">⚠️ pcz Footer Error: Fajl nije pronađen</strong>
                <br><br>
                <strong>Tražena lokacija:</strong><br>
                <code style="background:rgba(0,0,0,0.3);padding:5px;display:block;margin:5px 0;">' . esc_html( $php_file ) . '</code>
                <br>
                <strong>Provjerene lokacije:</strong><br>
                <ul style="margin-left:20px;line-height:1.8;">';
            
            foreach ( $possible_locations as $label => $location_path ) {
                if ( $location_path === 'N/A' ) {
                    $debug_info .= '<li><strong>' . esc_html( $label ) . ':</strong> ❌ N/A</li>';
                } else {
                    $exists = file_exists( $location_path );
                    $debug_info .= '<li><strong>' . esc_html( $label ) . ':</strong><br>
                        <code>' . esc_html( $location_path ) . '</code><br>
                        Status: ' . ( $exists ? '✅ POSTOJI' : '❌ NE POSTOJI' ) . '</li>';
                }
            }
            
            $debug_info .= '</ul>
                <br>
                <strong style="color:#FFD700;">💡 Rješenje:</strong><br>
                Kopiraj fajlove (<code>footer.php</code>, <code>footer.css</code>) u:
                <code style="background:rgba(0,0,0,0.3);padding:5px;display:block;margin:5px 0;">' . esc_html( $uploads['basedir'] . '/pcz-footer/' ) . '</code>
            </div>';
            return $debug_info;
        }
        return '';
    }
    
    ob_start();
    include $php_file;
    return ob_get_clean();
}

/**
 * Učitaj CSS assets
 */
add_action( 'wp_enqueue_scripts', 'pcz_enqueue_footer_assets' );
function pcz_enqueue_footer_assets() {
    $paths = pcz_get_footer_paths();
    
    // CSS
    if ( file_exists( $paths['path'] . 'footer.css' ) ) {
        wp_enqueue_style( 
            'pcz-footer', 
            $paths['url'] . 'footer.css', 
            array(), 
            filemtime( $paths['path'] . 'footer.css' )
        );
    }
}

/**
 * Registriraj footer menu lokaciju
 */
add_action( 'after_setup_theme', 'pcz_register_footer_menu' );
function pcz_register_footer_menu() {
    register_nav_menus( array(
        'footer-menu' => __( 'Footer Menu', 'pcz' ),
    ) );
}

/**
 * Admin Notice - Provjeri da li su fajlovi na mjestu
 */
add_action( 'admin_notices', 'pcz_footer_admin_notice' );
function pcz_footer_admin_notice() {
    $screen = get_current_screen();
    if ( ! $screen || ! in_array( $screen->id, array( 'dashboard', 'toplevel_page_site-settings' ) ) ) {
        return;
    }
    
    $paths = pcz_get_footer_paths();
    $missing_files = array();
    
    $required_files = array( 'footer.php', 'footer.css' );
    foreach ( $required_files as $file ) {
        if ( ! file_exists( $paths['path'] . $file ) ) {
            $missing_files[] = $file;
        }
    }
    
    if ( ! empty( $missing_files ) ) {
        ?>
        <div class="notice notice-warning">
            <p><strong>pcz Footer:</strong> Nedostaju fajlovi u <code><?php echo esc_html( $paths['path'] ); ?></code></p>
            <ul style="margin-left:20px;list-style:disc;">
                <?php foreach ( $missing_files as $file ) : ?>
                    <li><code><?php echo esc_html( $file ); ?></code></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }
    
    // Provjeri ACF podatke
    if ( function_exists( 'get_field' ) ) {
        $footer_kontakt = get_field( 'footer_kontakt', 'option' );
        if ( empty( $footer_kontakt ) ) {
            ?>
            <div class="notice notice-info">
                <p><strong>pcz Footer:</strong> Footer kontakt podaci nisu popunjeni. 
                <a href="<?php echo admin_url( 'admin.php?page=site-settings' ); ?>">Popuni ih ovdje</a>.</p>
            </div>
            <?php
        }
    }
}

