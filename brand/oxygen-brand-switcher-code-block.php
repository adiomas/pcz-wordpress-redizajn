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
if ( function_exists( 'get_field' ) ) {
    $is_enabled = get_field( 'brand_switcher_enabled', 'option' );
    // Provjeri poziciju - ako je "manual", ne renderaj automatski
    $position = get_field( 'brand_switcher_position', 'option' );
    if ( $position === 'manual' ) {
        // Korisnik koristi shortcode ručno, ne renderaj ovdje
        // Osim ako nije eksplicitno pozvan shortcode
        // (ovaj code block se koristi za inline prikaz)
    }
}

// Ako nije uključen, izađi
if ( ! $is_enabled ) {
    return;
}

// =============================================================================
// UČITAJ BRAND SWITCHER
// =============================================================================

if ( file_exists( $brand_switcher_path ) ) {
    include_once $brand_switcher_path;
}

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
    padding: 40px 20px;
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
        padding: 30px 15px;
    }
}

/* Mobile */
@media (max-width: 767px) {
    .pcz-brand-switcher-wrapper {
        padding: 25px 10px;
    }
}
</style>
