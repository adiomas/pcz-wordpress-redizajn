<?php
/**
 * PCZ "Želim plesati!" - Prijava Sekcija
 * 
 * Sekcija s formom za prijavu na tečajeve/treninge.
 * Koristi Gravity Forms za upravljanje prijavama.
 * Brand-aware: razlikuje prijave za Plesnu školu i Sportski klub.
 * 
 * KORIŠTENJE:
 * 1. Direktno uključi u Oxygen Code Block
 * 2. Ili koristi [pcz_prijava] shortcode
 * 
 * ACF POLJA (u Site Settings):
 * - prijava_naslov (Text) - Naslov sekcije
 * - prijava_podnaslov (Textarea) - Podnaslov/opis
 * - prijava_form_id (Number) - ID Gravity Forms forme
 * - prijava_pozadina (Select) - Stil pozadine (light/gradient)
 * 
 * BRAND SUSTAV:
 * - Plesna škola: form ID iz prijava_form_id
 * - Sportski klub: form ID iz spk_prijava_form_id (ili isti form s hidden field)
 * - Hidden field "{brand}" automatski se popunjava s trenutnim brandom
 * 
 * @package PCZ_Redizajn
 * @since 1.0.0
 */

// Sprječava direktan pristup
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// =============================================================================
// KONFIGURACIJA
// =============================================================================

$pcz_use_fallback = defined('pcz_TEST_ENVIRONMENT') && pcz_TEST_ENVIRONMENT === true;

if ( function_exists('apply_filters') ) {
    $pcz_use_fallback = apply_filters( 'pcz_prijava_use_fallback', $pcz_use_fallback );
}

// =============================================================================
// BRAND DETECTION
// =============================================================================

$current_brand = 'plesna-skola'; // Default
if ( function_exists( 'pcz_get_current_brand_id' ) ) {
    $current_brand = pcz_get_current_brand_id();
}

$is_sportski_klub = ( $current_brand === 'sportski-klub' );

// =============================================================================
// DOHVAT PODATAKA IZ ACF
// =============================================================================

// Default vrijednosti
$naslov = $is_sportski_klub ? 'Želim trenirati!' : 'Želim plesati!';
$podnaslov = 'Prijavite se bez obaveza. Kontaktirat ćemo vas u najkraćem roku.';
$form_id = 0;
$pozadina_stil = 'light'; // light, gradient
$using_fallback = false;

// Dohvati podatke iz ACF
if ( function_exists( 'get_field' ) ) {
    // Naslov - brand-aware
    $field_prefix = $is_sportski_klub ? 'spk_' : '';
    
    $acf_naslov = get_field( $field_prefix . 'prijava_naslov', 'option' );
    if ( empty( $acf_naslov ) && $is_sportski_klub ) {
        // Fallback na default field ako SPK nema specifičan
        $acf_naslov = get_field( 'prijava_naslov', 'option' );
    }
    if ( ! empty( $acf_naslov ) ) {
        $naslov = $acf_naslov;
    }
    
    // Podnaslov
    $acf_podnaslov = get_field( $field_prefix . 'prijava_podnaslov', 'option' );
    if ( empty( $acf_podnaslov ) && $is_sportski_klub ) {
        $acf_podnaslov = get_field( 'prijava_podnaslov', 'option' );
    }
    if ( ! empty( $acf_podnaslov ) ) {
        $podnaslov = $acf_podnaslov;
    }
    
    // Form ID - može biti različit za svaki brand
    $acf_form_id = get_field( $field_prefix . 'prijava_form_id', 'option' );
    if ( empty( $acf_form_id ) && $is_sportski_klub ) {
        $acf_form_id = get_field( 'prijava_form_id', 'option' );
    }
    if ( ! empty( $acf_form_id ) ) {
        $form_id = intval( $acf_form_id );
    }
    
    // Pozadina stil
    $acf_pozadina = get_field( 'prijava_pozadina', 'option' );
    if ( ! empty( $acf_pozadina ) ) {
        $pozadina_stil = $acf_pozadina;
    }
}

// =============================================================================
// FALLBACK ZA TEST ENVIRONMENT
// =============================================================================

if ( $pcz_use_fallback ) {
    $using_fallback = true;
    
    // U test environmentu koristimo mock formu
    if ( empty( $form_id ) ) {
        $form_id = 1; // Placeholder - u testu se renderira mock forma
    }
    
    if ( function_exists('apply_filters') ) {
        $naslov = apply_filters( 'pcz_prijava_fallback_naslov', $naslov );
        $podnaslov = apply_filters( 'pcz_prijava_fallback_podnaslov', $podnaslov );
    }
}

// =============================================================================
// PROVJERA - IZLAZ AKO NEMA FORME
// =============================================================================

$has_gravity_forms = function_exists( 'gravity_form' ) || class_exists( 'GFAPI' );

if ( ! $has_gravity_forms && ! $pcz_use_fallback ) {
    if ( function_exists('current_user_can') && current_user_can( 'manage_options' ) ) {
        echo '<div class="pcz-prijava pcz-prijava--error" style="background: #f8d7da; border: 1px solid #f5c6cb; padding: 40px 20px; text-align: center;">';
        echo '<p style="color: #721c24; font-size: 18px; margin: 0;">⚠️ <strong>Gravity Forms</strong> plugin nije aktivan.</p>';
        echo '<p style="color: #721c24; font-size: 14px; margin-top: 10px;">Instalirajte i aktivirajte Gravity Forms plugin za prikaz forme prijave.</p>';
        echo '</div>';
    }
    return;
}

// =============================================================================
// GENERIRANJE JEDINSTVENOG ID-a
// =============================================================================

$section_id = 'pcz-prijava-' . ( function_exists( 'wp_rand' ) ? wp_rand( 1000, 9999 ) : mt_rand( 1000, 9999 ) );

// =============================================================================
// CSS KLASE
// =============================================================================

$section_classes = array(
    'pcz-prijava',
    'pcz-prijava--' . $pozadina_stil,
    'pcz-prijava--brand-' . $current_brand,
);

if ( isset( $visibility_class ) ) {
    $section_classes[] = $visibility_class;
}

?>

<!-- ==================== PRIJAVA SEKCIJA ==================== -->
<?php if ( $using_fallback && function_exists('current_user_can') && current_user_can( 'manage_options' ) ) : ?>
<div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px 16px; margin: 0; font-size: 14px;">
    ⚠️ <strong>Demo mod:</strong> Prikazuje se mock forma. 
    <?php if ( ! $has_gravity_forms ) : ?>
        Gravity Forms nije instaliran - koristi se HTML preview.
    <?php else : ?>
        <a href="<?php echo esc_url( function_exists('admin_url') ? admin_url( 'admin.php?page=site-settings' ) : '#' ); ?>">Postavite Form ID u ACF</a>
    <?php endif; ?>
</div>
<?php endif; ?>

<section 
    class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>" 
    id="<?php echo esc_attr( $section_id ); ?>"
    data-brand="<?php echo esc_attr( $current_brand ); ?>"
    data-fallback="<?php echo $using_fallback ? 'true' : 'false'; ?>"
>
    <!-- Anchor za scroll -->
    <span id="prijava" class="pcz-prijava__anchor"></span>
    <span id="forma" class="pcz-prijava__anchor"></span>
    
    <div class="pcz-prijava__container">
        
        <!-- Header -->
        <header class="pcz-prijava__header">
            <h2 class="pcz-prijava__title"><?php echo esc_html( $naslov ); ?></h2>
            <?php if ( ! empty( $podnaslov ) ) : ?>
            <p class="pcz-prijava__subtitle"><?php echo esc_html( $podnaslov ); ?></p>
            <?php endif; ?>
        </header>
        
        <!-- Form Container -->
        <div class="pcz-prijava__form-wrapper">
            <?php if ( $has_gravity_forms && $form_id > 0 ) : ?>
                <?php 
                // Renderaj Gravity Form
                // field_values parametar šalje brand informaciju u hidden field
                gravity_form( 
                    $form_id, 
                    false,  // display_title
                    false,  // display_description
                    false,  // display_inactive
                    array( 'brand' => $current_brand ), // field_values za pre-population
                    true,   // ajax
                    0,      // tabindex
                    true    // echo
                );
                ?>
            <?php elseif ( $pcz_use_fallback ) : ?>
                <!-- Mock Form za Test Environment -->
                <form class="pcz-prijava__mock-form" onsubmit="event.preventDefault(); alert('Demo forma - prijava nije poslana.');">
                    <div class="pcz-prijava__field">
                        <input type="text" placeholder="Ime i Prezime*" required class="pcz-prijava__input">
                    </div>
                    <div class="pcz-prijava__field">
                        <input type="tel" placeholder="Kontakt telefon*" required class="pcz-prijava__input">
                    </div>
                    <div class="pcz-prijava__field">
                        <input type="email" placeholder="E-mail adresa*" required class="pcz-prijava__input">
                    </div>
                    <div class="pcz-prijava__field">
                        <select class="pcz-prijava__select" required>
                            <option value="">Tečaj za koji se prijavljujem</option>
                            <?php if ( $is_sportski_klub ) : ?>
                                <option value="sportski-ples">Sportski ples - Standard</option>
                                <option value="latino">Sportski ples - Latino</option>
                                <option value="formacije">Formacije</option>
                                <option value="natjecanja">Priprema za natjecanja</option>
                            <?php else : ?>
                                <option value="drustveni">Tečaj društvenih plesova</option>
                                <option value="latino">Latino i karipski plesovi</option>
                                <option value="vjenčani">Vjenčani ples</option>
                                <option value="djeca">Ples za djecu</option>
                                <option value="individualni">Individualni sati</option>
                            <?php endif; ?>
                        </select>
                        <span class="pcz-prijava__select-arrow">
                            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </span>
                    </div>
                    <div class="pcz-prijava__field">
                        <select class="pcz-prijava__select">
                            <option value="">Sam/a</option>
                            <option value="sam">Dolazim sam/a</option>
                            <option value="par">Dolazim u paru</option>
                            <option value="grupa">Dolazimo kao grupa</option>
                        </select>
                        <span class="pcz-prijava__select-arrow">
                            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </span>
                    </div>
                    
                    <!-- Hidden Brand Field -->
                    <input type="hidden" name="brand" value="<?php echo esc_attr( $current_brand ); ?>">
                    
                    <!-- Checkboxes -->
                    <div class="pcz-prijava__checkboxes">
                        <label class="pcz-prijava__checkbox-label">
                            <input type="checkbox" class="pcz-prijava__checkbox">
                            <span class="pcz-prijava__checkbox-custom"></span>
                            <span class="pcz-prijava__checkbox-text">Newsletter prijava</span>
                        </label>
                        <label class="pcz-prijava__checkbox-label">
                            <input type="checkbox" required class="pcz-prijava__checkbox">
                            <span class="pcz-prijava__checkbox-custom"></span>
                            <span class="pcz-prijava__checkbox-text">Slažem se s <a href="/privatnost/" target="_blank">uporabom informacija.</a></span>
                        </label>
                    </div>
                    
                    <!-- reCAPTCHA -->
                    <div class="pcz-prijava__captcha-wrapper">
                        <div class="pcz-prijava__captcha-placeholder">
                            <label class="pcz-prijava__checkbox-label">
                                <input type="checkbox" class="pcz-prijava__checkbox">
                                <span class="pcz-prijava__checkbox-custom"></span>
                                <span class="pcz-prijava__checkbox-text">Nisam robot</span>
                            </label>
                            <div class="pcz-prijava__captcha-badge">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 64 64">
                                    <path fill="#1c3aa9" d="M32 0C14.3 0 0 14.3 0 32c0 7.4 2.5 14.2 6.8 19.6l12.9-11.5c-.4-1.3-.7-2.7-.7-4.1 0-7.2 5.8-13 13-13s13 5.8 13 13c0 7.2-5.8 13-13 13-1.5 0-2.9-.3-4.3-.7L16.2 61c4.8 2 10.1 3 15.8 3 17.7 0 32-14.3 32-32S49.7 0 32 0z"/>
                                    <path fill="#4285f4" d="M32 19c-7.2 0-13 5.8-13 13s5.8 13 13 13 13-5.8 13-13-5.8-13-13-13z"/>
                                    <circle fill="#ababab" cx="32" cy="32" r="8"/>
                                </svg>
                                <small>reCAPTCHA</small>
                                <span class="captcha-links">Pravila o privatnosti - Uvjeti</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit -->
                    <div class="pcz-prijava__submit-wrapper">
                        <button type="submit" class="pcz-prijava__submit">
                            PRIJAVI SE
                        </button>
                    </div>
                </form>
            <?php else : ?>
                <!-- No Form Available -->
                <?php if ( function_exists('current_user_can') && current_user_can( 'manage_options' ) ) : ?>
                <div class="pcz-prijava__no-form">
                    <p>⚠️ Form ID nije postavljen.</p>
                    <p><a href="<?php echo esc_url( admin_url( 'admin.php?page=site-settings' ) ); ?>">Postavite Form ID u Site Settings</a></p>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        
    </div>
</section>
<!-- ==================== /PRIJAVA SEKCIJA ==================== -->

