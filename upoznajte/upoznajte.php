<?php
/**
 * pcz "Upoznajte nas" - Kompletno Rješenje
 * 
 * Sadrži: Sekcija za upoznavanje s plesnom školom - vlasnici, misija, vizija
 * Fullwidth pozadinska slika s niskim opacityem i tekstom
 * 
 * KORIŠTENJE:
 * 1. Direktno uključi u Oxygen Code Block
 * 2. Ili koristi [pcz_upoznajte] shortcode
 * 
 * ACF POLJA (u Site Settings):
 * - upoznajte_naslov (Text)
 * - upoznajte_slogan (Text) - rukopisni slogan
 * - upoznajte_slogan_highlight_1 (Text) - prva istaknuta riječ (npr. "trapave")
 * - upoznajte_slogan_highlight_2 (Text) - druga istaknuta riječ (npr. "vesele")
 * - upoznajte_slika (Image) - pozadinska slika desktop
 * - upoznajte_slika_mobile (Image) - pozadinska slika mobile
 * - upoznajte_opacity (Range 0-100) - opacity overlay pozadinske slike (default: 40)
 * - upoznajte_podnaslov (Text)
 * - upoznajte_tekst (Textarea)
 * - upoznajte_istaknut_tekst (Text)
 * - upoznajte_nastavak_teksta (Textarea)
 * 
 * BRAND VISIBILITY:
 * - Ova sekcija se prikazuje SAMO za Plesnu Školu
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

$pcz_use_fallback = defined('pcz_TEST_ENVIRONMENT') && pcz_TEST_ENVIRONMENT === true;

if ( function_exists('apply_filters') ) {
    $pcz_use_fallback = apply_filters( 'pcz_upoznajte_use_fallback', $pcz_use_fallback );
}

// =============================================================================
// DOHVAT PODATAKA IZ ACF
// =============================================================================

$naslov = 'UPOZNAJTE NAS';
$slogan = 'Za trapave noge i vesele duše';
$slogan_highlight_1 = 'trapave';
$slogan_highlight_2 = 'vesele';
$slika = '';
$slika_mobile = '';
$opacity = 40; // Default: 40% (0.4) - više vidljiva slika
$podnaslov = 'Prvi korak je Vaš ostali su naši';
$tekst = '';
$istaknut_tekst = 'Plesni centar Zagreb by Nicolas';
$nastavak_teksta = '';
$using_fallback = false;

// Dohvati podatke iz ACF
if ( function_exists( 'get_field' ) ) {
    $acf_naslov = get_field( 'upoznajte_naslov', 'option' );
    if ( ! empty( $acf_naslov ) ) {
        $naslov = $acf_naslov;
    }
    
    $acf_slogan = get_field( 'upoznajte_slogan', 'option' );
    if ( ! empty( $acf_slogan ) ) {
        $slogan = $acf_slogan;
    }
    
    $acf_highlight_1 = get_field( 'upoznajte_slogan_highlight_1', 'option' );
    if ( ! empty( $acf_highlight_1 ) ) {
        $slogan_highlight_1 = $acf_highlight_1;
    }
    
    $acf_highlight_2 = get_field( 'upoznajte_slogan_highlight_2', 'option' );
    if ( ! empty( $acf_highlight_2 ) ) {
        $slogan_highlight_2 = $acf_highlight_2;
    }
    
    // Desktop slika
    $acf_slika = get_field( 'upoznajte_slika', 'option' );
    if ( ! empty( $acf_slika ) ) {
        if ( is_array( $acf_slika ) && isset( $acf_slika['url'] ) ) {
            $slika = $acf_slika['url'];
        } elseif ( is_numeric( $acf_slika ) ) {
            $slika = wp_get_attachment_image_url( $acf_slika, 'full' );
        } else {
            $slika = $acf_slika;
        }
    }
    
    // Mobile slika
    $acf_slika_mobile = get_field( 'upoznajte_slika_mobile', 'option' );
    if ( ! empty( $acf_slika_mobile ) ) {
        if ( is_array( $acf_slika_mobile ) && isset( $acf_slika_mobile['url'] ) ) {
            $slika_mobile = $acf_slika_mobile['url'];
        } elseif ( is_numeric( $acf_slika_mobile ) ) {
            $slika_mobile = wp_get_attachment_image_url( $acf_slika_mobile, 'large' );
        } else {
            $slika_mobile = $acf_slika_mobile;
        }
    }
    
    // Opacity overlay (0-100, default 40)
    $acf_opacity = get_field( 'upoznajte_opacity', 'option' );
    if ( $acf_opacity !== false && $acf_opacity !== '' ) {
        // Koristi abs(intval()) umjesto absint() za kompatibilnost s test okruženjem
        $opacity = abs( intval( $acf_opacity ) );
        // Ograniči između 0 i 100
        $opacity = min( 100, max( 0, $opacity ) );
    }
    
    $acf_podnaslov = get_field( 'upoznajte_podnaslov', 'option' );
    if ( ! empty( $acf_podnaslov ) ) {
        $podnaslov = $acf_podnaslov;
    }
    
    $acf_tekst = get_field( 'upoznajte_tekst', 'option' );
    if ( ! empty( $acf_tekst ) ) {
        $tekst = $acf_tekst;
    }
    
    $acf_istaknut = get_field( 'upoznajte_istaknut_tekst', 'option' );
    if ( ! empty( $acf_istaknut ) ) {
        $istaknut_tekst = $acf_istaknut;
    }
    
    $acf_nastavak = get_field( 'upoznajte_nastavak_teksta', 'option' );
    if ( ! empty( $acf_nastavak ) ) {
        $nastavak_teksta = $acf_nastavak;
    }
}

// =============================================================================
// FALLBACK PODACI (za development/test)
// =============================================================================

if ( empty( $tekst ) && $pcz_use_fallback ) {
    $using_fallback = true;
    
    $fallback_data = array(
        'tekst' => 'U današnjem vremenu stalnih promjena, brojnih ponuda i odluka, dobro je imati jednu konstantu – nešto sigurno, pouzdano dobro, provjereno, svoje...',
        'nastavak_teksta' => '– najprepoznatljiviji, najprisutniji i najraznolikiji je Plesni centar u Hrvatskoj! Već više godina smo na samom vrhu, zajedno s vama! Veselimo se Vašem posjetu',
        'slika' => 'https://pcz.hr/wp-content/uploads/2023/01/nicolas-i-martina.jpg',
        'slika_mobile' => 'https://pcz.hr/wp-content/uploads/2023/01/nicolas-i-martina-mobile.jpg',
    );
    
    if ( function_exists('apply_filters') ) {
        $fallback_data = apply_filters( 'pcz_upoznajte_fallback_data', $fallback_data );
    }
    
    if ( isset( $fallback_data['tekst'] ) ) $tekst = $fallback_data['tekst'];
    if ( isset( $fallback_data['nastavak_teksta'] ) ) $nastavak_teksta = $fallback_data['nastavak_teksta'];
    if ( isset( $fallback_data['slika'] ) && empty( $slika ) ) $slika = $fallback_data['slika'];
    if ( isset( $fallback_data['slika_mobile'] ) && empty( $slika_mobile ) ) $slika_mobile = $fallback_data['slika_mobile'];
    if ( isset( $fallback_data['opacity'] ) && $opacity === 40 ) $opacity = $fallback_data['opacity'];
}

// =============================================================================
// PROVJERA - IZLAZ AKO NEMA SADRŽAJA
// =============================================================================

if ( empty( $tekst ) && empty( $slika ) ) {
    if ( function_exists('current_user_can') && current_user_can( 'manage_options' ) ) {
        $admin_url = function_exists('admin_url') ? admin_url( 'admin.php?page=site-settings' ) : '#';
        echo '<div class="pcz-upoznajte pcz-upoznajte--empty" style="background: #f5f5f5; padding: 60px 20px; text-align: center;">';
        echo '<p style="color: #333; font-size: 18px; margin: 0;">⚠️ <strong>Upoznajte nas</strong> sekcija nema sadržaja.</p>';
        echo '<p style="color: #666; font-size: 14px; margin-top: 10px;">';
        echo 'Dodajte sadržaj u <a href="' . esc_url( $admin_url ) . '" style="color: #C71585; text-decoration: underline;">Site Settings → Upoznajte nas</a>';
        echo '</p></div>';
    }
    return;
}

// =============================================================================
// SLOGAN S HIGHLIGHTANIM RIJEČIMA
// =============================================================================

/**
 * Formatira slogan s highlightanim riječima u magenta boji
 */
function pcz_format_slogan( $slogan, $highlight_words = array() ) {
    $formatted = esc_html( $slogan );
    
    foreach ( $highlight_words as $word ) {
        if ( ! empty( $word ) ) {
            // Case-insensitive zamjena
            $pattern = '/(' . preg_quote( $word, '/' ) . ')/iu';
            $replacement = '<span class="pcz-upoznajte__slogan-highlight">$1</span>';
            $formatted = preg_replace( $pattern, $replacement, $formatted );
        }
    }
    
    return $formatted;
}

$formatted_slogan = pcz_format_slogan( $slogan, array( $slogan_highlight_1, $slogan_highlight_2 ) );

// =============================================================================
// GENERIRANJE JEDINSTVENOG ID-a
// =============================================================================

$section_id = 'pcz-upoznajte-' . ( function_exists( 'wp_rand' ) ? wp_rand( 1000, 9999 ) : mt_rand( 1000, 9999 ) );

// =============================================================================
// BRAND VISIBILITY CLASS
// =============================================================================

$section_visibility_class = isset( $visibility_class ) ? $visibility_class : 'pcz-upoznajte--visible';

$brand_classes = '';
if ( function_exists( 'pcz_get_current_brand_id' ) ) {
    $brand_classes = ' pcz-upoznajte--brand-' . pcz_get_current_brand_id();
}

// Background classes
$bg_class = ! empty( $slika ) ? '' : ' pcz-upoznajte__bg--placeholder';
$has_mobile_bg = ! empty( $slika_mobile );

// Konvertiraj opacity iz 0-100 u 0-1 za rgba
$opacity_decimal = $opacity / 100;
$opacity_style = 'rgba(255, 255, 255, ' . number_format( $opacity_decimal, 2, '.', '' ) . ')';

// Gradient overlay opacity (malo niži na vrhu, viši dolje)
$opacity_top = min( 100, $opacity + 20 ) / 100;
$opacity_mid = min( 100, $opacity + 10 ) / 100;
$opacity_bottom = $opacity_decimal;
$overlay_gradient = sprintf(
    'linear-gradient(180deg, rgba(255, 255, 255, %.2f) 0%%, rgba(255, 255, 255, %.2f) 40%%, rgba(255, 255, 255, %.2f) 100%%)',
    $opacity_top,
    $opacity_mid,
    $opacity_bottom
);

?>

<!-- ==================== UPOZNAJTE NAS SEKCIJA ==================== -->
<?php if ( $using_fallback && function_exists('current_user_can') && current_user_can( 'manage_options' ) ) : ?>
<div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px 16px; margin: 0; font-size: 14px;">
    ⚠️ <strong>Demo mod:</strong> Prikazuju se primjeri podataka. 
    <a href="<?php echo esc_url( function_exists('admin_url') ? admin_url( 'admin.php?page=site-settings' ) : '#' ); ?>">Dodajte sadržaj u ACF</a>.
</div>
<?php endif; ?>

<section class="pcz-upoznajte <?php echo esc_attr( $section_visibility_class . $brand_classes ); ?>" id="<?php echo esc_attr( $section_id ); ?>" data-fallback="<?php echo $using_fallback ? 'true' : 'false'; ?>" data-opacity="<?php echo esc_attr( $opacity ); ?>">
    
    <!-- Pozadinska slika - Desktop -->
    <div class="pcz-upoznajte__bg<?php echo esc_attr( $bg_class ); ?><?php echo $has_mobile_bg ? ' pcz-upoznajte__bg--has-mobile' : ''; ?>" 
         <?php if ( ! empty( $slika ) ) : ?>
         style="background-image: url('<?php echo esc_url( $slika ); ?>');"
         <?php endif; ?>
    ></div>
    
    <!-- Pozadinska slika - Mobile (ako postoji) -->
    <?php if ( $has_mobile_bg ) : ?>
    <div class="pcz-upoznajte__bg-mobile" 
         style="background-image: url('<?php echo esc_url( $slika_mobile ); ?>');">
    </div>
    <?php endif; ?>
    
    <!-- Overlay za blend - dinamički opacity -->
    <div class="pcz-upoznajte__overlay" style="background: <?php echo esc_attr( $overlay_gradient ); ?>;"></div>
    
    <!-- Inline style za opacity overlay na background -->
    <style>
        #<?php echo esc_attr( $section_id ); ?> .pcz-upoznajte__bg::after {
            background: <?php echo esc_attr( $opacity_style ); ?> !important;
        }
        <?php if ( $has_mobile_bg ) : ?>
        #<?php echo esc_attr( $section_id ); ?> .pcz-upoznajte__bg-mobile::after {
            background: <?php echo esc_attr( $opacity_style ); ?> !important;
        }
        <?php endif; ?>
    </style>
    
    <!-- Content -->
    <div class="pcz-upoznajte__container">
        
        <!-- Header s naslovom i sloganom -->
        <header class="pcz-upoznajte__header">
            <h2 class="pcz-upoznajte__title"><?php echo esc_html( $naslov ); ?></h2>
            <?php if ( ! empty( $slogan ) ) : ?>
            <p class="pcz-upoznajte__slogan"><?php echo $formatted_slogan; ?></p>
            <?php endif; ?>
        </header>
        
        <!-- Tekst sadržaj -->
        <div class="pcz-upoznajte__content">
            <div class="pcz-upoznajte__text-content">
                <h3 class="pcz-upoznajte__subtitle"><?php echo esc_html( $podnaslov ); ?></h3>
                
                <div class="pcz-upoznajte__description">
                    <?php if ( ! empty( $tekst ) ) : ?>
                    <p class="pcz-upoznajte__paragraph">
                        <?php echo nl2br( esc_html( $tekst ) ); ?>
                    </p>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $istaknut_tekst ) || ! empty( $nastavak_teksta ) ) : ?>
                    <p class="pcz-upoznajte__paragraph pcz-upoznajte__paragraph--highlight">
                        <?php if ( ! empty( $istaknut_tekst ) ) : ?>
                        <strong class="pcz-upoznajte__highlight"><?php echo esc_html( $istaknut_tekst ); ?></strong>
                        <?php endif; ?>
                        <?php if ( ! empty( $nastavak_teksta ) ) : ?>
                        <?php echo nl2br( esc_html( $nastavak_teksta ) ); ?>
                        <?php endif; ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
    </div>
</section>
<!-- ==================== /UPOZNAJTE NAS SEKCIJA ==================== -->
