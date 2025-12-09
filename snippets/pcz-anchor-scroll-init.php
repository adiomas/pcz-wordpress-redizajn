<?php
/**
 * PCZ Anchor Scroll - Inicijalizacija
 * 
 * Ovaj snippet registrira:
 * - pcz-anchor-scroll.js za glatki scroll na anchor sekcije
 * - Automatsko postavljanje header offseta
 * 
 * INSTALACIJA:
 * 1. Dodati ovaj kod u Code Snippets plugin
 * 2. Aktivirati snippet
 * 3. Uploadati pcz-anchor-scroll.js u wp-content/uploads/pcz-anchor-scroll/
 * 
 * DOSTUPNI ANCHOR ID-jevi:
 * - #prijava     - Scroll na prijava formu
 * - #forma       - Alternativa za prijavu
 * - #kontakt     - Kontakt sekcija (ako postoji)
 * - #tecajevi    - Tečajevi sekcija (ako postoji)
 * 
 * @package PCZ_Redizajn
 * @since 1.0.0
 */

// Sprječava direktan pristup
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Definira putanje za PCZ Anchor Scroll fajlove
 */
function pcz_get_anchor_scroll_paths() {
    $uploads = wp_upload_dir();
    
    // Opcija 1: Uploads folder (PREPORUČENO)
    if ( ! empty( $uploads['basedir'] ) && ! empty( $uploads['baseurl'] ) ) {
        $uploads_path = trailingslashit( $uploads['basedir'] ) . 'pcz-anchor-scroll/';
        $uploads_url = trailingslashit( $uploads['baseurl'] ) . 'pcz-anchor-scroll/';
        
        $test_file = $uploads_path . 'pcz-anchor-scroll.js';
        if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
            return array(
                'path' => $uploads_path,
                'url'  => $uploads_url,
            );
        }
    }
    
    // Fallback: snippets folder (za razvoj)
    $snippets_path = trailingslashit( WP_CONTENT_DIR ) . 'uploads/pcz-snippets/';
    $snippets_url = trailingslashit( WP_CONTENT_URL ) . 'uploads/pcz-snippets/';
    
    $test_file = $snippets_path . 'pcz-anchor-scroll.js';
    if ( file_exists( $test_file ) && is_readable( $test_file ) ) {
        return array(
            'path' => $snippets_path,
            'url'  => $snippets_url,
        );
    }
    
    return null;
}

/**
 * Učitaj JavaScript za Anchor Scroll
 */
function pcz_anchor_scroll_enqueue_scripts() {
    $paths = pcz_get_anchor_scroll_paths();
    
    // Ako nema upload fajla, koristi inline skriptu
    if ( $paths ) {
        $js_file = $paths['path'] . 'pcz-anchor-scroll.js';
        $js_url = $paths['url'] . 'pcz-anchor-scroll.js';
        
        if ( file_exists( $js_file ) ) {
            wp_enqueue_script(
                'pcz-anchor-scroll',
                $js_url,
                array(),
                filemtime( $js_file ),
                true // U footer
            );
            
            // Dodaj inline konfiguraciju
            $header_height = apply_filters( 'pcz_anchor_scroll_offset', 100 );
            
            wp_add_inline_script(
                'pcz-anchor-scroll',
                "if(window.PCZAnchorScroll){PCZAnchorScroll.setOffset({$header_height});}"
            );
            
            return;
        }
    }
    
    // Fallback: minimalna inline skripta
    pcz_anchor_scroll_inline_fallback();
}
add_action( 'wp_enqueue_scripts', 'pcz_anchor_scroll_enqueue_scripts', 20 );

/**
 * Fallback inline skripta ako JS fajl nije dostupan
 * Minimalna verzija za smooth scroll
 */
function pcz_anchor_scroll_inline_fallback() {
    $header_offset = apply_filters( 'pcz_anchor_scroll_offset', 100 );
    
    $inline_js = "
    (function(){
        var offset = {$header_offset};
        
        // Handle clicks na anchor linkove
        document.addEventListener('click', function(e) {
            var link = e.target.closest('a[href^=\"#\"]');
            if (!link) return;
            
            var href = link.getAttribute('href');
            if (!href || href === '#') return;
            
            var target = document.querySelector(href);
            if (!target) return;
            
            e.preventDefault();
            
            var top = target.getBoundingClientRect().top + window.pageYOffset - offset;
            window.scrollTo({ top: top, behavior: 'smooth' });
            
            history.pushState(null, null, href);
        });
        
        // Handle initial hash
        if (window.location.hash) {
            setTimeout(function() {
                var target = document.querySelector(window.location.hash);
                if (target) {
                    var top = target.getBoundingClientRect().top + window.pageYOffset - offset;
                    window.scrollTo({ top: top, behavior: 'smooth' });
                }
            }, 100);
        }
    })();
    ";
    
    wp_add_inline_script( 'jquery', $inline_js );
}

/**
 * Filter za postavljanje header offseta
 * Koristi se za prilagodbu offseta ovisno o temi/headeru
 * 
 * Primjer korištenja u temi:
 * add_filter( 'pcz_anchor_scroll_offset', function() { return 120; } );
 */
// Default: 100px - standardna visina headera

/**
 * Helper funkcija - generira anchor span
 * Koristi u custom sekcijama za dodavanje anchor točke
 * 
 * @param string $id - Anchor ID (bez #)
 * @return string - HTML span element
 */
function pcz_anchor( $id ) {
    $id = sanitize_title( $id );
    return '<span id="' . esc_attr( $id ) . '" class="pcz-anchor"></span>';
}

/**
 * Shortcode za anchor
 * Korištenje: [pcz_anchor id="prijava"]
 */
function pcz_anchor_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'id' => '',
    ), $atts, 'pcz_anchor' );
    
    if ( empty( $atts['id'] ) ) {
        return '';
    }
    
    return pcz_anchor( $atts['id'] );
}
add_shortcode( 'pcz_anchor', 'pcz_anchor_shortcode' );

