<?php
/**
 * pcz "Sportski Klub" - Kompletno Rješenje
 * 
 * Sadrži: Predstavljanje Sportskog Kluba Plesnog Centra Zagreb
 * Naslov, opis i navigacijski linkovi na podstranice
 * 
 * KORIŠTENJE:
 * 1. Direktno uključi u Oxygen Code Block
 * 2. Ili koristi [pcz_sportski_klub] shortcode
 * 
 * ACF POLJA (u Site Settings):
 * - spk_naslov (Text)
 * - spk_podnaslov (Text)
 * - spk_opis (Textarea)
 * - spk_nastavak_opisa (Textarea)
 * - spk_linkovi (Repeater)
 *   - tekst (Text) - tekst linka
 *   - stranica (Page Link) - odabir stranice
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

// =============================================================================
// KONFIGURACIJA
// =============================================================================

/**
 * Kontrolira da li se koriste fallback podaci
 */
$pcz_use_fallback = defined('pcz_TEST_ENVIRONMENT') && pcz_TEST_ENVIRONMENT === true;

if ( function_exists('apply_filters') ) {
    $pcz_use_fallback = apply_filters( 'pcz_sportski_klub_use_fallback', $pcz_use_fallback );
}

// =============================================================================
// DOHVAT PODATAKA IZ ACF
// =============================================================================

$naslov = 'SPORTSKI KLUB';
$podnaslov = 'Sportski klub Plesnog centra Zagreb';
$opis = '';
$nastavak_opisa = '';
$linkovi = array();
$using_fallback = false;

// Dohvati podatke iz ACF
if ( function_exists( 'get_field' ) ) {
    // Naslov sekcije
    $acf_naslov = get_field( 'spk_naslov', 'option' );
    if ( ! empty( $acf_naslov ) ) {
        $naslov = $acf_naslov;
    }
    
    // Podnaslov
    $acf_podnaslov = get_field( 'spk_podnaslov', 'option' );
    if ( ! empty( $acf_podnaslov ) ) {
        $podnaslov = $acf_podnaslov;
    }
    
    // Opis
    $acf_opis = get_field( 'spk_opis', 'option' );
    if ( ! empty( $acf_opis ) ) {
        $opis = $acf_opis;
    }
    
    // Nastavak opisa
    $acf_nastavak = get_field( 'spk_nastavak_opisa', 'option' );
    if ( ! empty( $acf_nastavak ) ) {
        $nastavak_opisa = $acf_nastavak;
    }
    
    // Linkovi (Repeater)
    $acf_linkovi = get_field( 'spk_linkovi', 'option' );
    if ( ! empty( $acf_linkovi ) && is_array( $acf_linkovi ) ) {
        foreach ( $acf_linkovi as $item ) {
            // Page link može vratiti URL ili post object
            $url = '';
            if ( ! empty( $item['stranica'] ) ) {
                if ( is_numeric( $item['stranica'] ) ) {
                    $url = get_permalink( $item['stranica'] );
                } else {
                    $url = $item['stranica'];
                }
            }
            
            if ( ! empty( $item['tekst'] ) && ! empty( $url ) ) {
                $linkovi[] = array(
                    'tekst' => $item['tekst'],
                    'url'   => $url,
                );
            }
        }
    }
}

// =============================================================================
// FALLBACK PODACI (za development/test)
// =============================================================================

if ( ( empty( $opis ) || empty( $linkovi ) ) && $pcz_use_fallback ) {
    $using_fallback = true;
    
    $fallback_data = array();
    
    if ( function_exists('apply_filters') ) {
        $fallback_data = apply_filters( 'pcz_sportski_klub_fallback_data', $fallback_data );
    }
    
    if ( ! empty( $fallback_data ) ) {
        if ( isset( $fallback_data['opis'] ) && empty( $opis ) ) {
            $opis = $fallback_data['opis'];
        }
        if ( isset( $fallback_data['nastavak_opisa'] ) && empty( $nastavak_opisa ) ) {
            $nastavak_opisa = $fallback_data['nastavak_opisa'];
        }
        if ( isset( $fallback_data['linkovi'] ) && empty( $linkovi ) ) {
            $linkovi = $fallback_data['linkovi'];
        }
    }
}

// =============================================================================
// PROVJERA - IZLAZ AKO NEMA SADRŽAJA
// =============================================================================

if ( empty( $opis ) && empty( $linkovi ) ) {
    // Samo admin vidi poruku
    if ( function_exists('current_user_can') && current_user_can( 'manage_options' ) ) {
        $admin_url = function_exists('admin_url') ? admin_url( 'admin.php?page=site-settings' ) : '#';
        echo '<div class="pcz-sportski-klub pcz-sportski-klub--empty" style="background: #f5f5f5; padding: 60px 20px; text-align: center;">';
        echo '<p style="color: #333; font-size: 18px; margin: 0;">⚠️ <strong>Sportski Klub</strong> sekcija nema sadržaja.</p>';
        echo '<p style="color: #666; font-size: 14px; margin-top: 10px;">';
        echo 'Dodajte sadržaj u <a href="' . esc_url( $admin_url ) . '" style="color: #FF6B00; text-decoration: underline;">Site Settings → Sportski Klub</a>';
        echo '</p></div>';
    }
    return;
}

// =============================================================================
// GENERIRANJE JEDINSTVENOG ID-a
// =============================================================================

$section_id = 'pcz-sportski-klub-' . ( function_exists( 'wp_rand' ) ? wp_rand( 1000, 9999 ) : mt_rand( 1000, 9999 ) );

// =============================================================================
// BRAND VISIBILITY CLASS (postavlja oxygen-sportski-klub-code-block.php)
// =============================================================================

$section_visibility_class = isset( $visibility_class ) ? $visibility_class : 'pcz-sportski-klub--visible';

// Dodatna provjera: Ako je brand awareness uključen, dodaj klase
$brand_classes = '';
if ( function_exists( 'pcz_get_current_brand_id' ) ) {
    $brand_classes = ' pcz-sportski-klub--brand-' . pcz_get_current_brand_id();
}

?>

<!-- ==================== SPORTSKI KLUB SEKCIJA ==================== -->
<?php if ( $using_fallback && function_exists('current_user_can') && current_user_can( 'manage_options' ) ) : ?>
<div style="background: #fff3cd; border-left: 4px solid #FF6B00; padding: 12px 16px; margin: 0; font-size: 14px;">
    ⚠️ <strong>Demo mod:</strong> Prikazuju se primjeri podataka. 
    <a href="<?php echo esc_url( function_exists('admin_url') ? admin_url( 'admin.php?page=site-settings' ) : '#' ); ?>">Dodajte sadržaj u ACF</a>.
</div>
<?php endif; ?>

<section class="pcz-sportski-klub <?php echo esc_attr( $section_visibility_class . $brand_classes ); ?>" id="<?php echo esc_attr( $section_id ); ?>" data-fallback="<?php echo $using_fallback ? 'true' : 'false'; ?>">
    <div class="pcz-sportski-klub__container">
        
        <!-- Header -->
        <header class="pcz-sportski-klub__header">
            <h2 class="pcz-sportski-klub__title"><?php echo esc_html( $naslov ); ?></h2>
        </header>
        
        <!-- Content -->
        <div class="pcz-sportski-klub__content">
            <?php if ( ! empty( $podnaslov ) ) : ?>
            <p class="pcz-sportski-klub__podnaslov">
                <strong><?php echo esc_html( $podnaslov ); ?></strong>
                <?php if ( ! empty( $opis ) ) : ?>
                <?php echo ' ' . esc_html( $opis ); ?>
                <?php endif; ?>
            </p>
            <?php endif; ?>
            
            <?php if ( ! empty( $nastavak_opisa ) ) : ?>
            <p class="pcz-sportski-klub__opis">
                <?php echo nl2br( esc_html( $nastavak_opisa ) ); ?>
            </p>
            <?php endif; ?>
        </div>
        
        <!-- Divider -->
        <?php if ( ! empty( $linkovi ) ) : ?>
        <div class="pcz-sportski-klub__divider"></div>
        
        <!-- Navigation Links -->
        <nav class="pcz-sportski-klub__nav" aria-label="Sportski klub navigacija">
            <?php foreach ( $linkovi as $index => $link ) : ?>
            <a href="<?php echo esc_url( $link['url'] ); ?>" class="pcz-sportski-klub__link">
                <?php echo esc_html( $link['tekst'] ); ?>
            </a>
            <?php endforeach; ?>
        </nav>
        <?php endif; ?>
        
    </div>
</section>
<!-- ==================== /SPORTSKI KLUB SEKCIJA ==================== -->

