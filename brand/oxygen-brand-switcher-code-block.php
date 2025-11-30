<?php
/**
 * pcz Brand Switcher - Oxygen PHP Code Block v2.0
 * 
 * UPUTE: Kopiraj CIJELI sadržaj ovog fajla u Oxygen → Code Block → PHP tab
 * 
 * LOKACIJA: Dodaj ovaj Code Block ISPOD Hero sekcije
 * 
 * KONFIGURACIJA: Sve postavke se definiraju u:
 * WordPress Admin → Site Settings → Brand Switcher tab
 * 
 * Dostupne postavke u ACF-u:
 * - Stil (tabs, pills, buttons, minimal, toggle)
 * - Veličina (small, normal, large)
 * - Poravnanje (left, center, right)
 * - Prikaži ikone (da/ne)
 * - Labele za svaki brand (custom tekst)
 * - Uvodni tekst iznad switchera
 * 
 * @version 2.0.0
 * @package pcz_Redizajn
 */

// Sprječava direktan pristup
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// =============================================================================
// PATH DETECTION
// =============================================================================

$uploads = wp_upload_dir();

// Brand switcher path
$brand_switcher_path = trailingslashit( $uploads['basedir'] ) . 'pcz-brand/brand-switcher.php';

// Fallback na child temu
if ( ! file_exists( $brand_switcher_path ) ) {
    $brand_switcher_path = trailingslashit( get_stylesheet_directory() ) . 'pcz-brand/brand-switcher.php';
}

// =============================================================================
// PROVJERA JE LI SWITCHER UKLJUČEN U ACF
// =============================================================================

$is_enabled = true; // Default
$position = 'hero';  // Default pozicija

if ( function_exists( 'get_field' ) ) {
    $is_enabled = get_field( 'brand_switcher_enabled', 'option' );
    $position = get_field( 'brand_switcher_position', 'option' ) ?: 'hero';
}

// Ako nije uključen, izađi
if ( ! $is_enabled ) {
    return;
}

// Ako je pozicija "manual", korisnik koristi shortcode ručno - ne renderaj ovdje
if ( $position === 'manual' ) {
    return;
}

// Ako je pozicija "header", toggle se renderira u mega-menu.php - ne renderaj ovdje
if ( $position === 'header' ) {
    if ( current_user_can( 'manage_options' ) ) {
        echo '<!-- PCZ Brand Switcher: Pozicija je "header", toggle se renderira u headeru -->';
    }
    return;
}

// =============================================================================
// DUPLICATE PROTECTION
// =============================================================================

// Sprječava višestruko renderiranje switchera na istoj stranici
global $pcz_brand_switcher_rendered;
if ( ! empty( $pcz_brand_switcher_rendered ) ) {
    // Switcher je već renderiran - ne renderaj opet
    if ( current_user_can( 'manage_options' ) ) {
        echo '<!-- pcz Brand Switcher: Već renderiran, preskačem duplikat -->';
    }
    return;
}

// =============================================================================
// UČITAJ BRAND SWITCHER
// =============================================================================

if ( file_exists( $brand_switcher_path ) ) {
    include_once $brand_switcher_path;
}

// Označi da je switcher renderiran
$pcz_brand_switcher_rendered = true;

// Provjeri je li shortcode registriran
if ( shortcode_exists( 'pcz_brand_switcher' ) ) {
    // Koristi shortcode - automatski čita ACF postavke
    echo '<div class="pcz-brand-switcher-wrapper">';
    echo do_shortcode( '[pcz_brand_switcher]' );
    echo '</div>';
} elseif ( function_exists( 'pcz_render_brand_switcher' ) ) {
    // Direktni poziv funkcije ako shortcode nije dostupan
    echo '<div class="pcz-brand-switcher-wrapper">';
    pcz_render_brand_switcher();
    echo '</div>';
} elseif ( current_user_can( 'manage_options' ) ) {
    echo '<div style="background:#ff6b6b;color:#fff;padding:10px;text-align:center;">
        <strong>pcz Brand Switcher Error:</strong> Fajl nije pronađen.<br>
        Lokacija: ' . esc_html( $brand_switcher_path ) . '
    </div>';
}
?>
<style>
/* Wrapper stilovi - centriranje */
.pcz-brand-switcher-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 30px 20px;
    background: transparent;
    width: 100%;
    box-sizing: border-box;
}

/* Alignment override */
.pcz-brand-switcher-wrapper:has(.pcz-brand-switcher--align-left) {
    align-items: flex-start;
}

.pcz-brand-switcher-wrapper:has(.pcz-brand-switcher--align-right) {
    align-items: flex-end;
}

/* Tablet */
@media (max-width: 991px) {
    .pcz-brand-switcher-wrapper {
        padding: 20px 15px;
    }
}

/* Mobile - kompaktniji switcher */
@media (max-width: 767px) {
    .pcz-brand-switcher-wrapper {
        padding: 15px 10px;
    }
    
    /* Manji tabovi na mobitelu */
    .pcz-brand-switcher-wrapper .pcz-brand-switcher__tab {
        padding: 10px 16px;
        font-size: 11px;
        letter-spacing: 0.5px;
    }
    
    /* Toggle stil - kompaktniji */
    .pcz-brand-switcher-wrapper .pcz-brand-switcher--toggle .pcz-brand-switcher__tab {
        padding: 8px 16px;
    }
}

/* Extra mali ekrani */
@media (max-width: 480px) {
    .pcz-brand-switcher-wrapper {
        padding: 12px 8px;
    }
    
    .pcz-brand-switcher-wrapper .pcz-brand-switcher__tab {
        padding: 8px 12px;
        font-size: 10px;
    }
}
</style>
