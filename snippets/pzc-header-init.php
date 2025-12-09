<?php
/**
 * pcz Custom Header - Inicijalizacija
 * 
 * Ovaj snippet registrira:
 * - [pcz_header] shortcode za prikaz headera
 * - Automatsko učitavanje CSS i JS asseta
 * - ACF Options Page (ako nije već registrirana)
 * 
 * INSTALACIJA:
 * 1. Dodati ovaj kod u Code Snippets plugin
 * 2. Aktivirati snippet
 * 3. Koristiti [pcz_header] shortcode u Oxygen Header template-u
 * 
 * @package pcz_Redizajn
 * @since 1.0.0
 */

// Sprječava direktan pristup
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Definira putanje za pcz Header fajlove
 * 
 * Kompatibilno s WP Staging okruženjima (defiant, staging, itd.)
 * Koristi WordPress konstante koje automatski detektiraju prave putanje
 * 
 * Redoslijed provjere:
 * 1. Uploads folder (wp-content/uploads/pcz-header/) - PREPORUČENO ZA OXYGEN
 * 2. Child tema (wp-content/themes/tvoja-tema/pcz-header/)
 * 3. Parent tema (wp-content/themes/parent-tema/pcz-header/)
 */
function pcz_get_header_paths() {
    // Koristi WordPress konstante koje automatski detektiraju staging okruženja
    // WP_CONTENT_DIR i WP_CONTENT_URL su definirane u wp-config.php i rade na stagingu
    
    // Opcija 1: Uploads folder (PREPORUČENO ZA OXYGEN)
    // Koristi wp_upload_dir() koji automatski radi na staging okruženjima
    $uploads = wp_upload_dir();
    
    // Provjeri da li wp_upload_dir() vraća valjane podatke
    if ( ! empty( $uploads['basedir'] ) && ! empty( $uploads['baseurl'] ) ) {
        $uploads_path = trailingslashit( $uploads['basedir'] ) . 'pcz-header/';
        $uploads_url = trailingslashit( $uploads['baseurl'] ) . 'pcz-header/';
        
        // Provjeri da li folder postoji i da li fajl postoji
        $test_file = $uploads_path . 'mega-menu.php';
        if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
            return array(
                'path' => $uploads_path,
                'url'  => $uploads_url,
            );
        }
    }
    
    // Fallback: Koristi WP_CONTENT_DIR konstante (ako su definirane)
    if ( defined( 'WP_CONTENT_DIR' ) && defined( 'WP_CONTENT_URL' ) ) {
        $wp_content_uploads_path = trailingslashit( WP_CONTENT_DIR ) . 'uploads/pcz-header/';
        $wp_content_uploads_url = trailingslashit( WP_CONTENT_URL ) . 'uploads/pcz-header/';
        
        $test_file = $wp_content_uploads_path . 'mega-menu.php';
        if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
            return array(
                'path' => $wp_content_uploads_path,
                'url'  => $wp_content_uploads_url,
            );
        }
    }
    
    // Opcija 2: Child tema (koristi WordPress funkcije koje rade na stagingu)
    // ALI samo ako nije Oxygen folder (Oxygen koristi get_stylesheet_directory() za svoj folder)
    $child_theme_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-header/';
    $child_theme_url = trailingslashit( get_stylesheet_directory_uri() ) . 'pcz-header/';
    
    // Provjeri da li je to stvarno child tema ili Oxygen folder
    $is_oxygen_folder = ( strpos( $child_theme_path, 'oxygen' ) !== false || strpos( $child_theme_path, 'component-framework' ) !== false );
    
    if ( ! $is_oxygen_folder ) {
        $test_file = $child_theme_path . 'mega-menu.php';
        if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
            return array(
                'path' => $child_theme_path,
                'url'  => $child_theme_url,
            );
        }
    }
    
    // Opcija 3: Parent tema (fallback)
    $parent_theme_path = trailingslashit( get_template_directory() ) . 'pcz-header/';
    $parent_theme_url = trailingslashit( get_template_directory_uri() ) . 'pcz-header/';
    
    $is_oxygen_parent = ( strpos( $parent_theme_path, 'oxygen' ) !== false || strpos( $parent_theme_path, 'component-framework' ) !== false );
    
    if ( ! $is_oxygen_parent ) {
        $test_file = $parent_theme_path . 'mega-menu.php';
        if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
            return array(
                'path' => $parent_theme_path,
                'url'  => $parent_theme_url,
            );
        }
    }
    
    // Fallback - vrati uploads putanju koristeći wp_upload_dir()
    // Ovo će raditi čak i ako fajl ne postoji (za error poruku)
    if ( ! empty( $uploads['basedir'] ) && ! empty( $uploads['baseurl'] ) ) {
        return array(
            'path' => trailingslashit( $uploads['basedir'] ) . 'pcz-header/',
            'url'  => trailingslashit( $uploads['baseurl'] ) . 'pcz-header/',
        );
    }
    
    // Posljednji fallback - koristi WP_CONTENT_DIR ako je definiran
    if ( defined( 'WP_CONTENT_DIR' ) && defined( 'WP_CONTENT_URL' ) ) {
        return array(
            'path' => trailingslashit( WP_CONTENT_DIR ) . 'uploads/pcz-header/',
            'url'  => trailingslashit( WP_CONTENT_URL ) . 'uploads/pcz-header/',
        );
    }
    
    // Apsolutni fallback (ne bi trebalo doći ovdje)
    return array(
        'path' => trailingslashit( ABSPATH ) . 'wp-content/uploads/pcz-header/',
        'url'  => content_url( '/uploads/pcz-header/' ),
    );
}

/**
 * Registrira ACF Options Page (ako ACF postoji)
 */
add_action( 'acf/init', 'pcz_register_options_page' );
function pcz_register_options_page() {
    if ( ! function_exists( 'acf_add_options_page' ) ) {
        return;
    }
    
    // Provjeri da stranica nije već registrirana
    global $acf_options_pages;
    if ( isset( $acf_options_pages['site-settings'] ) ) {
        return;
    }
    
    acf_add_options_page( array(
        'page_title'    => 'Site Settings',
        'menu_title'    => 'Site Settings',
        'menu_slug'     => 'site-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false,
        'icon_url'      => 'dashicons-admin-generic',
        'position'      => 80,
        'update_button' => __( 'Spremi postavke', 'pcz' ),
        'updated_message' => __( 'Postavke su spremljene.', 'pcz' ),
    ) );
}

/**
 * pcz Header Shortcode
 * 
 * Korištenje: [pcz_header] (glavni) ili [pcz_header] (za kompatibilnost)
 */
add_shortcode( 'pcz_header', 'pcz_header_shortcode' ); // Glavni shortcode
add_shortcode( 'pcz_header', 'pcz_header_shortcode' ); // Fallback za kompatibilnost
function pcz_header_shortcode( $atts ) {
    // Koristi funkciju koja automatski detektira prave putanje (uključujući staging)
    $paths = pcz_get_header_paths();
    $php_file = $paths['path'] . 'mega-menu.php';
    
    if ( ! file_exists( $php_file ) ) {
        if ( current_user_can( 'manage_options' ) ) {
            // Debug informacije - prikaži sve moguće lokacije (kompatibilno s staging okruženjima)
            $uploads = wp_upload_dir();
            $uploads_check = trailingslashit( $uploads['basedir'] ) . 'pcz-header/mega-menu.php';
            
            // Provjeri različite moguće lokacije
            $possible_locations = array(
                'wp_upload_dir() basedir' => trailingslashit( $uploads['basedir'] ) . 'pcz-header/mega-menu.php',
                'WP_CONTENT_DIR (ako je definiran)' => defined( 'WP_CONTENT_DIR' ) ? trailingslashit( WP_CONTENT_DIR ) . 'uploads/pcz-header/mega-menu.php' : 'N/A',
                'Child tema' => trailingslashit( get_stylesheet_directory() ) . 'pcz-header/mega-menu.php',
                'Parent tema' => trailingslashit( get_template_directory() ) . 'pcz-header/mega-menu.php',
            );
            
            $debug_info = '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:left;max-width:900px;margin:20px auto;font-family:monospace;font-size:13px;">
                <strong style="font-size:18px;">⚠️ pcz Header Error: Fajl nije pronađen</strong>
                <br><br>
                <strong>Tražena lokacija:</strong><br>
                <code style="background:rgba(0,0,0,0.3);padding:5px;display:block;margin:5px 0;">' . esc_html( $php_file ) . '</code>
                <br>
                <strong>Provjerene lokacije:</strong><br>
                <ul style="margin-left:20px;line-height:1.8;">';
            
            $counter = 1;
            foreach ( $possible_locations as $label => $location_path ) {
                if ( $location_path === 'N/A' ) {
                    $debug_info .= '<li><strong>' . $counter . '. ' . esc_html( $label ) . ':</strong><br>
                        <span style="color:#FFB6C1;">❌ N/A (nije definirano)</span></li>';
                } else {
                    $exists = file_exists( $location_path );
                    $debug_info .= '<li><strong>' . $counter . '. ' . esc_html( $label ) . ':</strong><br>
                        <code style="background:rgba(0,0,0,0.3);padding:3px;">' . esc_html( $location_path ) . '</code><br>
                        Status: ' . ( $exists ? '<span style="color:#90EE90;">✅ POSTOJI</span>' : '<span style="color:#FFB6C1;">❌ NE POSTOJI</span>' ) . '</li>';
                }
                $counter++;
            }
            
            $debug_info .= '</ul>
                <br>
                <strong style="color:#FFD700;">💡 Rješenje:</strong><br>
                Kopiraj fajlove (<code>mega-menu.php</code>, <code>mega-menu.css</code>, <code>mega-menu.js</code>) u jednu od ovih lokacija:
                <ul style="margin-left:20px;line-height:1.8;">
                    <li><strong>PREPORUČENO:</strong> <code style="background:rgba(0,0,0,0.3);padding:3px;">' . esc_html( $uploads['basedir'] . '/pcz-header/' ) . '</code></li>
                    <li><strong>ILI:</strong> <code style="background:rgba(0,0,0,0.3);padding:3px;">' . esc_html( get_stylesheet_directory() . '/pcz-header/' ) . '</code></li>
                </ul>
                <br>
                <strong>Debug informacije (WP Staging kompatibilno):</strong><br>
                <ul style="margin-left:20px;line-height:1.8;">
                    <li>ABSPATH: <code>' . esc_html( ABSPATH ) . '</code></li>
                    <li>WP_CONTENT_DIR: <code>' . ( defined( 'WP_CONTENT_DIR' ) ? esc_html( WP_CONTENT_DIR ) : 'N/A (nije definiran)' ) . '</code></li>
                    <li>WP_CONTENT_URL: <code>' . ( defined( 'WP_CONTENT_URL' ) ? esc_html( WP_CONTENT_URL ) : 'N/A (nije definiran)' ) . '</code></li>
                    <li>wp_upload_dir() basedir: <code>' . esc_html( $uploads['basedir'] ) . '</code></li>
                    <li>wp_upload_dir() baseurl: <code>' . esc_html( $uploads['baseurl'] ) . '</code></li>
                    <li>Child tema path: <code>' . esc_html( get_stylesheet_directory() ) . '</code></li>
                    <li>Parent tema path: <code>' . esc_html( get_template_directory() ) . '</code></li>
                </ul>
                <br>
                <strong style="color:#FFD700;">💡 Preporučena lokacija za WP Staging:</strong><br>
                <code style="background:rgba(0,0,0,0.3);padding:5px;display:block;margin:5px 0;">' . esc_html( trailingslashit( $uploads['basedir'] ) . 'pcz-header/' ) . '</code>
            </div>';
            return $debug_info;
        }
        return '';
    }
    
    // CSS i JS se učitavaju preko wp_enqueue_scripts hook-a (linija 254)
    // Ne treba ih učitavati ovdje jer shortcode se izvršava kasnije u procesu
    
    ob_start();
    include $php_file;
    return ob_get_clean();
}

// Registriraj shortcode-e NAKON definicije funkcije
add_shortcode( 'pcz_header', 'pcz_header_shortcode' ); // Glavni shortcode
add_shortcode( 'pcz_header', 'pcz_header_shortcode' ); // Fallback za kompatibilnost

// Debug: Provjeri da li su shortcode-i registrirani (samo za admine)
if ( current_user_can( 'manage_options' ) ) {
    add_action( 'wp_footer', function() {
        if ( shortcode_exists( 'pcz_header' ) ) {
            // Shortcode postoji - sve OK
        } else {
            echo '<!-- pcz Header Debug: Shortcode pcz_header NIJE registriran! -->';
        }
    });
}

/**
 * Učitaj CSS i JS assets
 */
add_action( 'wp_enqueue_scripts', 'pcz_enqueue_header_assets' );
function pcz_enqueue_header_assets() {
    $paths = pcz_get_header_paths();
    
    // CSS
    if ( file_exists( $paths['path'] . 'mega-menu.css' ) ) {
        wp_enqueue_style( 
            'pcz-mega-menu', 
            $paths['url'] . 'mega-menu.css', 
            array(), 
            filemtime( $paths['path'] . 'mega-menu.css' )
        );
    }
    
    // Mobile Menu V2 CSS (override stilovi za mobile)
    // if ( file_exists( $paths['path'] . 'mega-menu-mobile-v2.css' ) ) {
    //     wp_enqueue_style( 
    //         'pcz-mega-menu-mobile-v2', 
    //         $paths['url'] . 'mega-menu-mobile-v2.css', 
    //         array( 'pcz-mega-menu' ), // Dependency - mora se učitati NAKON glavnog CSS-a
    //         filemtime( $paths['path'] . 'mega-menu-mobile-v2.css' )
    //     );
    // }
    
    // JavaScript
    if ( file_exists( $paths['path'] . 'mega-menu.js' ) ) {
        wp_enqueue_script( 
            'pcz-mega-menu', 
            $paths['url'] . 'mega-menu.js', 
            array(), 
            filemtime( $paths['path'] . 'mega-menu.js' ),
            true // U footer
        );
    }
}

/**
 * Dodaj body class kada je menu otvoren (za scroll lock)
 */
add_action( 'wp_head', 'pcz_header_inline_styles' );
function pcz_header_inline_styles() {
    ?>
    <style>
        body.pcz-menu-open {
            overflow: hidden;
        }
    </style>
    <?php
}

/**
 * Admin Notice - Provjeri da li su fajlovi na mjestu
 */
add_action( 'admin_notices', 'pcz_header_admin_notice' );
function pcz_header_admin_notice() {
    // Samo na relevantnim stranicama
    $screen = get_current_screen();
    if ( ! $screen || ! in_array( $screen->id, array( 'dashboard', 'toplevel_page_site-settings' ) ) ) {
        return;
    }
    
    $paths = pcz_get_header_paths();
    $missing_files = array();
    
    $required_files = array( 'mega-menu.php', 'mega-menu.css', 'mega-menu-mobile-v2.css', 'mega-menu.js' );
    foreach ( $required_files as $file ) {
        if ( ! file_exists( $paths['path'] . $file ) ) {
            $missing_files[] = $file;
        }
    }
    
    if ( ! empty( $missing_files ) ) {
        ?>
        <div class="notice notice-warning">
            <p><strong>pcz Header:</strong> Nedostaju fajlovi u <code><?php echo esc_html( $paths['path'] ); ?></code></p>
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
        $mega_menu_data = get_field( 'ponuda_blokovi', 'option' );
        if ( empty( $mega_menu_data ) ) {
            ?>
            <div class="notice notice-info">
                <p><strong>pcz Header:</strong> Mega menu podaci nisu popunjeni. 
                <a href="<?php echo admin_url( 'admin.php?page=site-settings' ); ?>">Popuni ih ovdje</a>.</p>
            </div>
            <?php
        }
    }
}

