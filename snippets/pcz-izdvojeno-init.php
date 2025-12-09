<?php

/**
 * pcz "Izdvojeno iz ponude" - Inicijalizacija
 * 
 * Ovaj snippet registrira:
 * - [pcz_izdvojeno] shortcode za prikaz sekcije (Plesna škola)
 * - [pcz_izdvojeno brand="sportski-klub"] shortcode (Sportski klub)
 * - Automatsko učitavanje CSS asseta
 * 
 * INSTALACIJA:
 * 1. Dodati ovaj kod u Code Snippets plugin
 * 2. Aktivirati snippet
 * 3. Koristiti [pcz_izdvojeno] shortcode ili Oxygen Code Block
 * 
 * BRAND SUPPORT:
 * - Plesna škola: [pcz_izdvojeno] ili [pcz_izdvojeno brand="plesna-skola"]
 * - Sportski klub: [pcz_izdvojeno brand="sportski-klub"]
 * 
 * Sekcija automatski detektira brand ako nije eksplicitno naveden.
 * 
 * @package pcz_Redizajn
 * @since 1.0.0
 */

// Sprječava direktan pristup
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
* Definira putanje za pcz Izdvojeno fajlove
* 
* Kompatibilno s WP Staging okruženjima
* 
* Redoslijed provjere:
* 1. Uploads folder (wp-content/uploads/pcz-izdvojeno/) - PREPORUČENO
* 2. Child tema (wp-content/themes/tvoja-tema/pcz-izdvojeno/)
* 3. Parent tema
*/
function pcz_get_izdvojeno_paths() {
  $uploads = wp_upload_dir();
  
  // Opcija 1: Uploads folder (PREPORUČENO)
  if ( ! empty( $uploads['basedir'] ) && ! empty( $uploads['baseurl'] ) ) {
      $uploads_path = trailingslashit( $uploads['basedir'] ) . 'pcz-izdvojeno/';
      $uploads_url = trailingslashit( $uploads['baseurl'] ) . 'pcz-izdvojeno/';
      
      $test_file = $uploads_path . 'izdvojeno.php';
      if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
          return array(
              'path' => $uploads_path,
              'url'  => $uploads_url,
          );
      }
  }
  
  // Fallback: WP_CONTENT_DIR
  if ( defined( 'WP_CONTENT_DIR' ) && defined( 'WP_CONTENT_URL' ) ) {
      $wp_content_path = trailingslashit( WP_CONTENT_DIR ) . 'uploads/pcz-izdvojeno/';
      $wp_content_url = trailingslashit( WP_CONTENT_URL ) . 'uploads/pcz-izdvojeno/';
      
      $test_file = $wp_content_path . 'izdvojeno.php';
      if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
          return array(
              'path' => $wp_content_path,
              'url'  => $wp_content_url,
          );
      }
  }
  
  // Opcija 2: Child tema
  $child_theme_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-izdvojeno/';
  $child_theme_url = trailingslashit( get_stylesheet_directory_uri() ) . 'pcz-izdvojeno/';
  
  $is_oxygen_folder = ( strpos( $child_theme_path, 'oxygen' ) !== false );
  
  if ( ! $is_oxygen_folder ) {
      $test_file = $child_theme_path . 'izdvojeno.php';
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
          'path' => trailingslashit( $uploads['basedir'] ) . 'pcz-izdvojeno/',
          'url'  => trailingslashit( $uploads['baseurl'] ) . 'pcz-izdvojeno/',
      );
  }
  
  return null;
}

/**
* Učitaj CSS za Izdvojeno sekciju
*/
function pcz_izdvojeno_enqueue_styles() {
  $paths = pcz_get_izdvojeno_paths();
  
  if ( ! $paths ) {
      return;
  }
  
  $css_file = $paths['path'] . 'izdvojeno.css';
  $css_url = $paths['url'] . 'izdvojeno.css';
  
  if ( file_exists( $css_file ) ) {
      wp_enqueue_style(
          'pcz-izdvojeno',
          $css_url,
          array(),
          filemtime( $css_file )
      );
  }
}

/**
* Registriraj [pcz_izdvojeno] shortcode
* 
* Atributi:
* - brand: 'plesna-skola' (default) ili 'sportski-klub'
* 
* Primjeri:
* [pcz_izdvojeno]
* [pcz_izdvojeno brand="sportski-klub"]
*/
function pcz_izdvojeno_shortcode( $atts ) {
  // Parse atribute
  $atts = shortcode_atts( array(
      'brand' => '', // Prazan = auto-detekcija
  ), $atts, 'pcz_izdvojeno' );
  
  $paths = pcz_get_izdvojeno_paths();
  
  if ( ! $paths ) {
      if ( current_user_can( 'manage_options' ) ) {
          return '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;">
              <strong>pcz Izdvojeno Error:</strong> Fajlovi nisu pronađeni. 
              Uploadajte ih u <code>wp-content/uploads/pcz-izdvojeno/</code>
          </div>';
      }
      return '';
  }
  
  $izdvojeno_file = $paths['path'] . 'izdvojeno.php';
  
  if ( ! file_exists( $izdvojeno_file ) ) {
      if ( current_user_can( 'manage_options' ) ) {
          return '<div style="background:#ff6b6b;color:#fff;padding:20px;text-align:center;">
              <strong>pcz Izdvojeno Error:</strong> izdvojeno.php nije pronađen.<br>
              Lokacija: ' . esc_html( $izdvojeno_file ) . '
          </div>';
      }
      return '';
  }
  
  // Odredi brand
  $izdvojeno_brand = $atts['brand'];
  if ( empty( $izdvojeno_brand ) ) {
      // Auto-detekcija branda
      $izdvojeno_brand = function_exists( 'pcz_get_current_brand_id' ) 
          ? pcz_get_current_brand_id() 
          : 'plesna-skola';
  }
  
  // Validiraj brand
  $allowed_brands = array( 'plesna-skola', 'sportski-klub' );
  if ( ! in_array( $izdvojeno_brand, $allowed_brands, true ) ) {
      $izdvojeno_brand = 'plesna-skola';
  }
  
  // Visibility class - sekcija je uvijek vidljiva jer se koristi odgovarajući brand
  $visibility_class = 'pcz-izdvojeno--visible';
  
  // Učitaj assets
  pcz_izdvojeno_enqueue_styles();
  
  // Output buffer za include
  ob_start();
  include $izdvojeno_file;
  return ob_get_clean();
}
add_shortcode( 'pcz_izdvojeno', 'pcz_izdvojeno_shortcode' );

/**
* Automatski učitaj CSS na svim stranicama
* (Za slučaj korištenja u Oxygen Code Block-u bez shortcode-a)
*/
function pcz_izdvojeno_auto_enqueue() {
  pcz_izdvojeno_enqueue_styles();
}
// add_action( 'wp_enqueue_scripts', 'pcz_izdvojeno_auto_enqueue' );
// Odkomentiraj gornju liniju ako želiš globalno učitavanje CSS-a

/**
* Fallback podaci za "Izdvojeno iz ponude" sekciju (Plesna škola)
* 
* Ovi podaci se koriste kada ACF nema podataka i fallback je omogućen.
* NAPOMENA: Ovo je JEDINI IZVOR fallback podataka - NE dupliciramo u izdvojeno.php!
* 
* Za deaktivaciju fallbacka u produkciji:
* add_filter('pcz_izdvojeno_use_fallback', '__return_false');
* 
* @param array $data Postojeći fallback podaci (obično prazni)
* @return array Kartice za prikaz
*/
function pcz_izdvojeno_default_fallback_data( $data ) {
  // Ako već ima podataka, vrati ih
  if ( ! empty( $data ) ) {
      return $data;
  }
  
  // Default fallback podaci za demo/development - Plesna škola
  return array(
      array(
          'naziv' => 'TEČAJ DRUŠTVENIH PLESOVA',
          'slika' => 'https://images.unsplash.com/photo-1518834107812-67b0b7c58434?w=400&h=533&fit=crop&crop=faces',
          'link'  => '/tecaj-drustvenih-plesova/',
      ),
      array(
          'naziv' => 'TEČAJ LATINO PLESOVA',
          'slika' => 'https://images.unsplash.com/photo-1504609813442-a8924e83f76e?w=400&h=533&fit=crop&crop=faces',
          'link'  => '/tecaj-latino-plesova/',
      ),
      array(
          'naziv' => 'DJECA I MLADEŽ',
          'slika' => 'https://images.unsplash.com/photo-1508700929628-666bc8bd84ea?w=400&h=533&fit=crop&crop=faces',
          'link'  => '/djeca-i-mladez/',
      ),
      array(
          'naziv' => 'MLADENCI',
          'slika' => 'https://images.unsplash.com/photo-1519741497674-611481863552?w=400&h=533&fit=crop&crop=faces',
          'link'  => '/mladenci/',
      ),
  );
}
add_filter( 'pcz_izdvojeno_fallback_data', 'pcz_izdvojeno_default_fallback_data', 10, 1 );

/**
* Fallback podaci za "Sportske discipline" sekciju (Sportski klub)
* 
* Ovi podaci se koriste kada ACF nema podataka i fallback je omogućen.
* 
* @param array $data Postojeći fallback podaci (obično prazni)
* @return array Kartice za prikaz
*/
function pcz_izdvojeno_sk_default_fallback_data( $data ) {
  // Ako već ima podataka, vrati ih
  if ( ! empty( $data ) ) {
      return $data;
  }
  
  // Default fallback podaci za demo/development - Sportski klub
  return array(
      array(
          'naziv' => 'LA (latinsko-američki plesovi)',
          'slika' => 'https://images.unsplash.com/photo-1547153760-18fc86324498?w=400&h=533&fit=crop&crop=faces',
          'link'  => '/latinsko-americki-plesovi/',
      ),
      array(
          'naziv' => 'ST (standardni plesovi)',
          'slika' => 'https://images.unsplash.com/photo-1508700929628-666bc8bd84ea?w=400&h=533&fit=crop&crop=faces',
          'link'  => '/standardni-plesovi/',
      ),
      array(
          'naziv' => 'Kombinacija 10 sportskih plesova',
          'slika' => 'https://images.unsplash.com/photo-1504609813442-a8924e83f76e?w=400&h=533&fit=crop&crop=faces',
          'link'  => '/kombinacija-10-plesova/',
      ),
  );
}
add_filter( 'pcz_izdvojeno_sk_fallback_data', 'pcz_izdvojeno_sk_default_fallback_data', 10, 1 );

/**
* Debug funkcija - prikazuje putanje u admin baru
*/
function pcz_izdvojeno_debug_info() {
  if ( ! current_user_can( 'manage_options' ) || ! is_admin_bar_showing() ) {
      return;
  }
  
  $paths = pcz_get_izdvojeno_paths();
  
  if ( $paths && isset( $_GET['pcz_debug'] ) ) {
      echo '<div style="background:#333;color:#fff;padding:10px;position:fixed;bottom:0;left:0;right:0;z-index:99999;font-size:12px;">';
      echo '<strong>pcz Izdvojeno Debug:</strong><br>';
      echo 'Path: ' . esc_html( $paths['path'] ) . '<br>';
      echo 'URL: ' . esc_html( $paths['url'] ) . '<br>';
      echo 'File exists: ' . ( file_exists( $paths['path'] . 'izdvojeno.php' ) ? '✅' : '❌' );
      echo '</div>';
  }
}
add_action( 'wp_footer', 'pcz_izdvojeno_debug_info' );
