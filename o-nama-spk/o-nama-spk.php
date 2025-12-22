<?php
/**
 * pcz "O Nama - Sportski Klub" - Svijetli Dizajn (stil upoznajte)
 * 
 * Fullwidth pozadinska slika s bijelim overlayem
 * Usklađeno s dizajnom "Upoznajte nas" sekcije
 * 
 * KORIŠTENJE:
 * 1. Direktno uključi u Oxygen Code Block
 * 2. Ili koristi [pcz_o_nama_spk] shortcode
 * 
 * ACF POLJA (u Site Settings):
 * - spk_onama_naslov (Text) - glavni naslov
 * - spk_onama_naslov_akcent (Text) - slogan tekst (handwritten stil)
 * - spk_onama_ime_kluba (Text) - naziv kluba
 * - spk_onama_logo (Image) - mala ikona loga
 * - spk_onama_slika (Image) - pozadinska slika
 * - spk_onama_lead (WYSIWYG) - istaknuti uvodni paragraf
 * - spk_onama_lijevi_stupac (WYSIWYG) - glavni tekst
 * - spk_onama_desni_stupac (WYSIWYG) - dodatni tekst
 * - spk_onama_highlight_naslov (Text) - naslov highlight boxa
 * - spk_onama_highlight_tekst (WYSIWYG) - sadržaj highlight boxa
 * - spk_onama_kontakt_osoba (Text)
 * - spk_onama_kontakt_telefon (Text)
 * - spk_onama_kontakt_email (Text)
 * 
 * BRAND VISIBILITY:
 * - Prikazuje se SAMO za brand "Sportski Klub"
 * 
 * @package pcz_Redizajn
 * @since 2.1.0
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
    $pcz_use_fallback = apply_filters( 'pcz_o_nama_spk_use_fallback', $pcz_use_fallback );
}

// =============================================================================
// DOHVAT PODATAKA IZ ACF
// =============================================================================

$naslov = 'O NAMA';
$naslov_akcent = 'Sportski ples je naša strast';
$ime_kluba = 'Sportski Plesni Klub Zagreb';
$logo = '';
$slika = '';
$lead = '';
$lijevi_stupac = '';
$desni_stupac = '';
$highlight_naslov = 'Vodstvo';
$highlight_tekst = '';
$kontakt_osoba = '';
$kontakt_telefon = '';
$kontakt_email = '';
$using_fallback = false;

// Dohvati podatke iz ACF
if ( function_exists( 'get_field' ) ) {
    $acf_naslov = get_field( 'spk_onama_naslov', 'option' );
    if ( ! empty( $acf_naslov ) ) {
        $naslov = $acf_naslov;
    }
    
    $acf_naslov_akcent = get_field( 'spk_onama_naslov_akcent', 'option' );
    if ( ! empty( $acf_naslov_akcent ) ) {
        $naslov_akcent = $acf_naslov_akcent;
    }
    
    $acf_ime_kluba = get_field( 'spk_onama_ime_kluba', 'option' );
    if ( ! empty( $acf_ime_kluba ) ) {
        $ime_kluba = $acf_ime_kluba;
    }
    
    $acf_logo = get_field( 'spk_onama_logo', 'option' );
    if ( ! empty( $acf_logo ) ) {
        if ( is_array( $acf_logo ) && isset( $acf_logo['url'] ) ) {
            $logo = $acf_logo['url'];
        } elseif ( is_numeric( $acf_logo ) ) {
            $logo = wp_get_attachment_image_url( $acf_logo, 'medium' );
        } else {
            $logo = $acf_logo;
        }
    }
    
    $acf_slika = get_field( 'spk_onama_slika', 'option' );
    if ( ! empty( $acf_slika ) ) {
        if ( is_array( $acf_slika ) && isset( $acf_slika['url'] ) ) {
            $slika = $acf_slika['url'];
        } elseif ( is_numeric( $acf_slika ) ) {
            $slika = wp_get_attachment_image_url( $acf_slika, 'full' );
        } else {
            $slika = $acf_slika;
        }
    }
    
    $acf_lead = get_field( 'spk_onama_lead', 'option' );
    if ( ! empty( $acf_lead ) ) {
        $lead = $acf_lead;
    }
    
    $acf_lijevi_stupac = get_field( 'spk_onama_lijevi_stupac', 'option' );
    if ( ! empty( $acf_lijevi_stupac ) ) {
        $lijevi_stupac = $acf_lijevi_stupac;
    }
    
    $acf_desni_stupac = get_field( 'spk_onama_desni_stupac', 'option' );
    if ( ! empty( $acf_desni_stupac ) ) {
        $desni_stupac = $acf_desni_stupac;
    }
    
    $acf_highlight_naslov = get_field( 'spk_onama_highlight_naslov', 'option' );
    if ( ! empty( $acf_highlight_naslov ) ) {
        $highlight_naslov = $acf_highlight_naslov;
    }
    
    $acf_highlight_tekst = get_field( 'spk_onama_highlight_tekst', 'option' );
    if ( ! empty( $acf_highlight_tekst ) ) {
        $highlight_tekst = $acf_highlight_tekst;
    }
    
    $acf_kontakt_osoba = get_field( 'spk_onama_kontakt_osoba', 'option' );
    if ( ! empty( $acf_kontakt_osoba ) ) {
        $kontakt_osoba = $acf_kontakt_osoba;
    }
    
    $acf_kontakt_telefon = get_field( 'spk_onama_kontakt_telefon', 'option' );
    if ( ! empty( $acf_kontakt_telefon ) ) {
        $kontakt_telefon = $acf_kontakt_telefon;
    }
    
    $acf_kontakt_email = get_field( 'spk_onama_kontakt_email', 'option' );
    if ( ! empty( $acf_kontakt_email ) ) {
        $kontakt_email = $acf_kontakt_email;
    }
}

// =============================================================================
// FALLBACK PODACI (za development/test)
// =============================================================================

$has_content = ! empty( $lead ) || ! empty( $lijevi_stupac ) || ! empty( $desni_stupac );

if ( ! $has_content && $pcz_use_fallback ) {
    $using_fallback = true;
    
    $fallback_data = array();
    
    if ( function_exists('apply_filters') ) {
        $fallback_data = apply_filters( 'pcz_o_nama_spk_fallback_data', $fallback_data );
    }
    
    if ( ! empty( $fallback_data ) ) {
        if ( isset( $fallback_data['logo'] ) && empty( $logo ) ) $logo = $fallback_data['logo'];
        if ( isset( $fallback_data['slika'] ) && empty( $slika ) ) $slika = $fallback_data['slika'];
        if ( isset( $fallback_data['lead'] ) ) $lead = $fallback_data['lead'];
        if ( isset( $fallback_data['lijevi_stupac'] ) ) $lijevi_stupac = $fallback_data['lijevi_stupac'];
        if ( isset( $fallback_data['desni_stupac'] ) ) $desni_stupac = $fallback_data['desni_stupac'];
        if ( isset( $fallback_data['highlight_naslov'] ) ) $highlight_naslov = $fallback_data['highlight_naslov'];
        if ( isset( $fallback_data['highlight_tekst'] ) ) $highlight_tekst = $fallback_data['highlight_tekst'];
        if ( isset( $fallback_data['kontakt_osoba'] ) ) $kontakt_osoba = $fallback_data['kontakt_osoba'];
        if ( isset( $fallback_data['kontakt_telefon'] ) ) $kontakt_telefon = $fallback_data['kontakt_telefon'];
        if ( isset( $fallback_data['kontakt_email'] ) ) $kontakt_email = $fallback_data['kontakt_email'];
        
        // Ponovno provjeri ima li sada sadržaja
        $has_content = ! empty( $lead ) || ! empty( $lijevi_stupac ) || ! empty( $desni_stupac );
    }
}

// =============================================================================
// PROVJERA - IZLAZ AKO NEMA SADRŽAJA
// =============================================================================

if ( ! $has_content ) {
    if ( function_exists('current_user_can') && current_user_can( 'manage_options' ) ) {
        $admin_url = function_exists('admin_url') ? admin_url( 'admin.php?page=site-settings' ) : '#';
        echo '<div class="o-nama o-nama--empty">';
        echo '<p>⚠️ <strong>O Nama (Sportski Klub)</strong> sekcija nema sadržaja.</p>';
        echo '<p><a href="' . esc_url( $admin_url ) . '">Dodajte sadržaj u Site Settings → O Nama SPK</a></p>';
        echo '</div>';
    }
    return;
}

// =============================================================================
// GENERIRANJE JEDINSTVENOG ID-a
// =============================================================================

$section_id = 'o-nama-' . ( function_exists( 'wp_rand' ) ? wp_rand( 1000, 9999 ) : mt_rand( 1000, 9999 ) );

// =============================================================================
// BRAND VISIBILITY CLASS
// =============================================================================

$section_visibility_class = isset( $visibility_class ) ? $visibility_class : 'o-nama--visible';

$brand_classes = '';
if ( function_exists( 'pcz_get_current_brand_id' ) ) {
    $brand_classes = ' o-nama--brand-' . pcz_get_current_brand_id();
}

// Provjeri ima li kontakt info
$has_contact = ! empty( $kontakt_osoba ) || ! empty( $kontakt_telefon ) || ! empty( $kontakt_email );

// Provjeri ima li highlight
$has_highlight = ! empty( $highlight_tekst );

// Background class
$bg_class = ! empty( $slika ) ? '' : ' o-nama__bg--placeholder';

?>

<!-- ==================== O NAMA - SPORTSKI KLUB | SVIJETLI DIZAJN ==================== -->
<?php if ( $using_fallback && function_exists('current_user_can') && current_user_can( 'manage_options' ) ) : ?>
<div class="o-nama__dev-notice">
    ⚠️ <strong>Demo mod:</strong> Prikazuju se primjeri podataka. 
    <a href="<?php echo esc_url( function_exists('admin_url') ? admin_url( 'admin.php?page=site-settings' ) : '#' ); ?>">Dodajte sadržaj u ACF</a>.
</div>
<?php endif; ?>

<section class="o-nama <?php echo esc_attr( $section_visibility_class . $brand_classes ); ?>" id="<?php echo esc_attr( $section_id ); ?>" data-fallback="<?php echo $using_fallback ? 'true' : 'false'; ?>">
    
    <!-- Background s bijelim overlayem -->
    <div class="o-nama__bg<?php echo esc_attr( $bg_class ); ?>" <?php if ( ! empty( $slika ) ) : ?>style="background-image: url('<?php echo esc_url( $slika ); ?>');"<?php endif; ?>></div>
    <div class="o-nama__overlay"></div>
    
    <div class="o-nama__container">
        
        <!-- Header s naslovom i sloganom (stil upoznajte) -->
        <header class="o-nama__header">
            <div>
                <!-- Badge s logom -->
                <div class="o-nama__badge">
                    <?php if ( ! empty( $logo ) ) : ?>
                    <div class="o-nama__logo">
                        <img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( $ime_kluba ); ?>" loading="lazy">
                    </div>
                    <?php else : ?>
                    <div class="o-nama__logo">
                        <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="48" height="48" fill="#FF6B00"/>
                            <text x="24" y="22" text-anchor="middle" fill="white" font-size="8" font-weight="bold" font-family="sans-serif">SPK</text>
                            <text x="24" y="34" text-anchor="middle" fill="white" font-size="6" font-weight="600" font-family="sans-serif">ZAGREB</text>
                        </svg>
                    </div>
                    <?php endif; ?>
                    <span class="o-nama__club-name"><?php echo esc_html( $ime_kluba ); ?></span>
                </div>
                
                <!-- Naslov -->
                <h2 class="o-nama__title">
                    <?php echo esc_html( strtoupper( $naslov ) ); ?>
                    <?php if ( ! empty( $naslov_akcent ) ) : ?>
                    <span class="o-nama__title-accent"><?php echo esc_html( $naslov_akcent ); ?></span>
                    <?php endif; ?>
                </h2>
            </div>
        </header>
        
        <!-- Content -->
        <div class="o-nama__content">
            
            <!-- Lead paragraph - istaknuti uvod -->
            <?php if ( ! empty( $lead ) ) : ?>
            <div class="o-nama__lead">
                <?php echo wp_kses_post( $lead ); ?>
            </div>
            <?php endif; ?>
            
            <!-- Glavni tekst (lijevi stupac) -->
            <?php if ( ! empty( $lijevi_stupac ) ) : ?>
            <div class="o-nama__text">
                <?php echo wp_kses_post( $lijevi_stupac ); ?>
            </div>
            <?php endif; ?>
            
            <!-- Dodatni tekst + highlight -->
            <?php if ( ! empty( $desni_stupac ) || $has_highlight ) : ?>
            <div class="o-nama__right">
                <?php if ( ! empty( $desni_stupac ) ) : ?>
                <div class="o-nama__text">
                    <?php echo wp_kses_post( $desni_stupac ); ?>
                </div>
                <?php endif; ?>
                
                <?php if ( $has_highlight ) : ?>
                <div class="o-nama__highlight">
                    <div class="o-nama__highlight-title"><?php echo esc_html( $highlight_naslov ); ?></div>
                    <?php echo wp_kses_post( $highlight_tekst ); ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
        </div>
        
        <!-- Footer / Contact -->
        <?php if ( $has_contact ) : ?>
        <footer class="o-nama__footer">
            <div class="o-nama__deco"></div>
            
            <div class="o-nama__contact">
                <span class="o-nama__contact-label">Kontakt</span>
                
                <div class="o-nama__contact-links">
                    <?php if ( ! empty( $kontakt_osoba ) ) : ?>
                    <span class="o-nama__contact-person">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <?php echo esc_html( $kontakt_osoba ); ?>
                    </span>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $kontakt_telefon ) ) : ?>
                    <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $kontakt_telefon ) ); ?>" class="o-nama__contact-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <?php echo esc_html( $kontakt_telefon ); ?>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $kontakt_email ) ) : ?>
                    <a href="mailto:<?php echo esc_attr( $kontakt_email ); ?>" class="o-nama__contact-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="4" width="20" height="16" rx="2"/>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                        </svg>
                        <?php echo esc_html( $kontakt_email ); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </footer>
        <?php endif; ?>
        
    </div>
</section>
<!-- ==================== /O NAMA - SPORTSKI KLUB ==================== -->
