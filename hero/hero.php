<?php
/**
 * pcz Hero Sekcija - Kompletno Rješenje
 * 
 * Fullwidth hero s pozadinskom slikom, naslovom i CTA gumbom.
 * Uključuje i intro sekciju sa socijalnim mrežama.
 * 
 * KORIŠTENJE:
 * 1. Direktno uključi u Oxygen Code Block
 * 2. Ili koristi [pcz_hero] shortcode
 * 
 * ACF POLJA (u Site Settings):
 * - hero_pozadinska_slika (Image)
 * - hero_tagline (Text)
 * - hero_naslov (Textarea)
 * - hero_podnaslov (Textarea)
 * - hero_cta_tekst (Text)
 * - hero_cta_link (URL)
 * - hero_intro_naslov (Text)
 * - hero_intro_tekst (Textarea)
 * - hero_intro_tekst_2 (Textarea)
 * - hero_socijalne_mreze (Repeater)
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
    $pcz_use_fallback = apply_filters( 'pcz_hero_use_fallback', $pcz_use_fallback );
}

// =============================================================================
// DOHVAT PODATAKA IZ ACF
// =============================================================================

// Hero sadržaj
$hero_data = [
    'pozadinska_slika' => '',
    'tagline'          => 'plesom do zdravlja!',
    'naslov'           => "Pokret koji\nmijenja život",
    'podnaslov'        => 'Plesna škola i Sportski klub koji spajaju rekreativni i profesionalni ples.',
    'cta_tekst'        => 'ŽELIM PLESATI!',
    'cta_link'         => '#prijava',
];

// Intro sadržaj
$intro_data = [
    'naslov'  => 'Jedan plesni centar, dva svijeta plesa.',
    'tekst'   => 'U <strong>Plesnoj školi</strong> učimo plesati srcem, a u <strong>Sportskom klubu</strong> srcem postajemo prvaci.',
    'tekst_2' => 'Zaprati nas na društvenim mrežama i budi dio naše plesne energije — priča, inspiracija i pokreta koji povezuju.',
];

// Socijalne mreže
$social_links = [];

$using_fallback = false;

// Dohvati podatke iz ACF
if ( function_exists( 'get_field' ) ) {
    // Hero podaci
    $acf_slika = get_field( 'hero_pozadinska_slika', 'option' );
    if ( ! empty( $acf_slika ) ) {
        if ( is_array( $acf_slika ) && isset( $acf_slika['url'] ) ) {
            $hero_data['pozadinska_slika'] = $acf_slika['url'];
        } elseif ( is_numeric( $acf_slika ) ) {
            $hero_data['pozadinska_slika'] = wp_get_attachment_image_url( $acf_slika, 'full' );
        } else {
            $hero_data['pozadinska_slika'] = $acf_slika;
        }
    }
    
    $acf_tagline = get_field( 'hero_tagline', 'option' );
    if ( ! empty( $acf_tagline ) ) {
        $hero_data['tagline'] = $acf_tagline;
    }
    
    $acf_naslov = get_field( 'hero_naslov', 'option' );
    if ( ! empty( $acf_naslov ) ) {
        $hero_data['naslov'] = $acf_naslov;
    }
    
    $acf_podnaslov = get_field( 'hero_podnaslov', 'option' );
    if ( ! empty( $acf_podnaslov ) ) {
        $hero_data['podnaslov'] = $acf_podnaslov;
    }
    
    $acf_cta_tekst = get_field( 'hero_cta_tekst', 'option' );
    if ( ! empty( $acf_cta_tekst ) ) {
        $hero_data['cta_tekst'] = $acf_cta_tekst;
    }
    
    $acf_cta_link = get_field( 'hero_cta_link', 'option' );
    if ( ! empty( $acf_cta_link ) ) {
        $hero_data['cta_link'] = $acf_cta_link;
    }
    
    // Intro podaci
    $acf_intro_naslov = get_field( 'hero_intro_naslov', 'option' );
    if ( ! empty( $acf_intro_naslov ) ) {
        $intro_data['naslov'] = $acf_intro_naslov;
    }
    
    $acf_intro_tekst = get_field( 'hero_intro_tekst', 'option' );
    if ( ! empty( $acf_intro_tekst ) ) {
        $intro_data['tekst'] = $acf_intro_tekst;
    }
    
    $acf_intro_tekst_2 = get_field( 'hero_intro_tekst_2', 'option' );
    if ( ! empty( $acf_intro_tekst_2 ) ) {
        $intro_data['tekst_2'] = $acf_intro_tekst_2;
    }
    
}

// =============================================================================
// SOCIJALNE MREŽE - Brand ima prioritet!
// =============================================================================
// Logika:
// 1. PRIORITET: Brand-specific socijalne (ps_socijalne_mreze / spk_socijalne_mreze)
// 2. FALLBACK: Hardkodirane vrijednosti (ako brand socials nisu definirane)

// Omogući brand sustavu da postavi socijalne mreže (PRIORITET)
if ( function_exists('apply_filters') ) {
    $social_links = apply_filters( 'pcz_hero_social_links', $social_links );
}

// FALLBACK - samo ako brand sustav nije vratio ništa
if ( empty( $social_links ) && function_exists( 'get_field' ) ) {
    // Pokušaj dohvatiti iz ps_socijalne_mreze kao fallback
    $acf_social = get_field( 'ps_socijalne_mreze', 'option' );
    if ( ! empty( $acf_social ) && is_array( $acf_social ) ) {
        foreach ( $acf_social as $item ) {
            if ( ! empty( $item['url'] ) ) {
                $social_links[] = [
                    'ikona' => $item['platforma'] ?? 'instagram',
                    'url'   => $item['url'],
                ];
            }
        }
    }
}

// =============================================================================
// BRAND-AWARE FILTER
// =============================================================================

// Omogući brand sustavu da override-a hero podatke
if ( function_exists('apply_filters') ) {
    $hero_data = apply_filters( 'pcz_hero_data', $hero_data );
    $intro_data = apply_filters( 'pcz_hero_intro_data', $intro_data );
}

// =============================================================================
// FALLBACK PODACI
// =============================================================================

if ( empty( $hero_data['pozadinska_slika'] ) && $pcz_use_fallback ) {
    $using_fallback = true;
    
    // Fallback slika - koristimo placeholder ili pcz.hr sliku
    $hero_data['pozadinska_slika'] = 'https://pcz.hr/wp-content/uploads/2020/01/hero-bg.jpg';
    
    // Ako nema socijalnih linkova, dodaj fallback
    if ( empty( $social_links ) ) {
        $social_links = [
            [ 'ikona' => 'instagram', 'url' => 'https://instagram.com/plesnicentarzagreb' ],
            [ 'ikona' => 'facebook', 'url' => 'https://facebook.com/plesnicentarzagreb' ],
        ];
    }
    
    if ( function_exists('apply_filters') ) {
        $hero_data = apply_filters( 'pcz_hero_fallback_data', $hero_data );
        $social_links = apply_filters( 'pcz_hero_fallback_social', $social_links );
    }
}

// =============================================================================
// PROVJERA - ADMIN PORUKA AKO NEMA SLIKE
// =============================================================================

if ( empty( $hero_data['pozadinska_slika'] ) && ! $pcz_use_fallback ) {
    if ( function_exists('current_user_can') && current_user_can( 'manage_options' ) ) {
        $admin_url = function_exists('admin_url') ? admin_url( 'admin.php?page=site-settings' ) : '#';
        echo '<div class="pcz-hero pcz-hero--empty" style="background: linear-gradient(135deg, #C71585, #a01269); min-height: 400px; display: flex; align-items: center; justify-content: center; text-align: center; padding: 40px;">';
        echo '<div style="color: white;">';
        echo '<p style="font-size: 24px; margin: 0 0 10px;">⚠️ Hero sekcija nema pozadinsku sliku</p>';
        echo '<p style="font-size: 14px; opacity: 0.9;">Dodajte sliku u <a href="' . esc_url( $admin_url ) . '" style="color: white; text-decoration: underline;">Site Settings → Hero Sekcija</a></p>';
        echo '</div></div>';
    }
    return;
}

// =============================================================================
// SVG IKONE ZA SOCIJALNE MREŽE
// =============================================================================

$social_icons = [
    'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>',
    'facebook'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor"><path d="M512 256C512 114.6 397.4 0 256 0S0 114.6 0 256C0 376 82.7 476.8 194.2 504.5V334.2H141.4V256h52.8V222.3c0-87.1 39.4-127.5 125-127.5c16.2 0 44.2 3.2 55.7 6.4V172c-6-.6-16.5-1-29.6-1c-42 0-58.2 15.9-58.2 57.2V256h83.6l-14.4 78.2H287V510.1C413.8 494.8 512 386.9 512 256z"/></svg>',
    'youtube'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" fill="currentColor"><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg>',
    'tiktok'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor"><path d="M448,209.91a210.06,210.06,0,0,1-122.77-39.25V349.38A162.55,162.55,0,1,1,185,188.31V278.2a74.62,74.62,0,1,0,52.23,71.18V0l88,0a121.18,121.18,0,0,0,1.86,22.17h0A122.18,122.18,0,0,0,381,102.39a121.43,121.43,0,0,0,67,20.14Z"/></svg>',
];

?>

<!-- ==================== HERO SEKCIJA ==================== -->
<?php if ( $using_fallback && function_exists('current_user_can') && current_user_can( 'manage_options' ) ) : ?>
<!-- Admin Notice: Using Fallback Data -->
<div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px 16px; margin: 0; font-size: 14px; position: relative; z-index: 100;">
    ⚠️ <strong>Demo mod:</strong> Prikazuju se primjeri podataka. 
    <a href="<?php echo esc_url( function_exists('admin_url') ? admin_url( 'admin.php?page=site-settings' ) : '#' ); ?>">Dodajte hero podatke u ACF</a>.
</div>
<?php endif; ?>

<section class="pcz-hero" data-fallback="<?php echo $using_fallback ? 'true' : 'false'; ?>">
    
    <!-- Hero Main -->
    <div class="pcz-hero__main">
        <!-- Background Image -->
        <div class="pcz-hero__bg" style="background-image: url('<?php echo esc_url( $hero_data['pozadinska_slika'] ); ?>');"></div>
        
        <!-- Overlay -->
        <div class="pcz-hero__overlay"></div>
        
        <!-- Content -->
        <div class="pcz-hero__content">
            <div class="pcz-hero__container">
                
                <?php if ( ! empty( $hero_data['tagline'] ) ) : ?>
                <p class="pcz-hero__tagline"><?php echo esc_html( $hero_data['tagline'] ); ?></p>
                <?php endif; ?>
                
                <h1 class="pcz-hero__title"><?php 
                    // Escapaj tekst, ali dopusti <br> tagove
                    $naslov_safe = esc_html( $hero_data['naslov'] );
                    // Konvertiraj newlines u <br>
                    echo nl2br( $naslov_safe );
                ?></h1>
                
                <?php if ( ! empty( $hero_data['podnaslov'] ) ) : ?>
                <p class="pcz-hero__subtitle"><?php 
                    $podnaslov_safe = esc_html( $hero_data['podnaslov'] );
                    echo nl2br( $podnaslov_safe );
                ?></p>
                <?php endif; ?>
                
                <?php if ( ! empty( $hero_data['cta_tekst'] ) && ! empty( $hero_data['cta_link'] ) ) : ?>
                <a href="<?php echo esc_url( $hero_data['cta_link'] ); ?>" class="pcz-hero__cta">
                    <?php echo esc_html( $hero_data['cta_tekst'] ); ?>
                </a>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
    
    <!-- Intro Section -->
    <div class="pcz-hero__intro">
        <div class="pcz-hero__intro-container">
            
            <?php if ( ! empty( $intro_data['naslov'] ) ) : ?>
            <h2 class="pcz-hero__intro-title"><?php echo esc_html( $intro_data['naslov'] ); ?></h2>
            <?php endif; ?>
            
            <?php if ( ! empty( $intro_data['tekst'] ) ) : ?>
            <p class="pcz-hero__intro-text"><?php echo wp_kses_post( $intro_data['tekst'] ); ?></p>
            <?php endif; ?>
            
            <?php if ( ! empty( $intro_data['tekst_2'] ) ) : ?>
            <p class="pcz-hero__intro-text pcz-hero__intro-text--secondary"><?php echo wp_kses_post( $intro_data['tekst_2'] ); ?></p>
            <?php endif; ?>
            
            <?php if ( ! empty( $social_links ) ) : ?>
            <div class="pcz-hero__social">
                <?php foreach ( $social_links as $social ) : ?>
                <a href="<?php echo esc_url( $social['url'] ); ?>" 
                   class="pcz-hero__social-link" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   aria-label="<?php echo esc_attr( ucfirst( $social['ikona'] ) ); ?>">
                    <?php echo $social_icons[ $social['ikona'] ] ?? $social_icons['instagram']; ?>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
        </div>
    </div>
    
    <?php 
    // Hook za brand switcher ili druge elemente nakon intro sekcije
    if ( function_exists('do_action') ) {
        do_action( 'pcz_after_hero_intro' );
    }
    ?>
    
</section>
<!-- ==================== /HERO SEKCIJA ==================== -->

