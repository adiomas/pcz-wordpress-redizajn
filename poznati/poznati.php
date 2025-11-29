<?php
/**
 * pcz "Poznati o PCZ-u" - Kompletno Rješenje
 * 
 * Sadrži: Testimonijali poznatih osoba s kružnim slikama
 * Generički pristup - radi na staging i produkciji
 * 
 * KORIŠTENJE:
 * 1. Direktno uključi u Oxygen Code Block
 * 2. Ili koristi [pcz_poznati] shortcode
 * 
 * ACF POLJA (u Site Settings):
 * - poznati_naslov (Text)
 * - poznati_testimonijali (Repeater)
 *   - ime (Text)
 *   - citat (Textarea)
 *   - slika (Image)
 * 
 * FALLBACK PONAŠANJE:
 * - Produkcija: Sekcija se ne prikazuje ako nema ACF podataka
 * - Admin vidi poruku da doda sadržaj
 * - Može se overrideati s filterom 'pcz_poznati_fallback_data'
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
 * 
 * TRUE  = Koristi fallback ako ACF nema podataka (za development/demo)
 * FALSE = Ne prikazuj sekciju ako nema podataka (za produkciju)
 * 
 * Override: add_filter('pcz_poznati_use_fallback', '__return_true');
 */
$pcz_use_fallback = defined('pcz_TEST_ENVIRONMENT') && pcz_TEST_ENVIRONMENT === true;

// Provjeri filter za override
if ( function_exists('apply_filters') ) {
    $pcz_use_fallback = apply_filters( 'pcz_poznati_use_fallback', $pcz_use_fallback );
}

// =============================================================================
// DOHVAT PODATAKA IZ ACF
// =============================================================================

$naslov = 'POZNATI O PCZ-u';
$testimonijali = array();
$using_fallback = false;

// Dohvati podatke iz ACF
if ( function_exists( 'get_field' ) ) {
    // Naslov sekcije
    $acf_naslov = get_field( 'poznati_naslov', 'option' );
    if ( ! empty( $acf_naslov ) ) {
        $naslov = $acf_naslov;
    }
    
    // Testimonijali (Repeater)
    $acf_testimonijali = get_field( 'poznati_testimonijali', 'option' );
    if ( ! empty( $acf_testimonijali ) && is_array( $acf_testimonijali ) ) {
        foreach ( $acf_testimonijali as $item ) {
            // Dohvati sliku - može biti ID, array ili URL
            $slika_url = '';
            if ( ! empty( $item['slika'] ) ) {
                if ( is_array( $item['slika'] ) && isset( $item['slika']['url'] ) ) {
                    $slika_url = $item['slika']['url'];
                } elseif ( is_numeric( $item['slika'] ) ) {
                    $slika_url = wp_get_attachment_image_url( $item['slika'], 'medium' );
                } else {
                    $slika_url = $item['slika'];
                }
            }
            
            $testimonijali[] = array(
                'ime'   => ! empty( $item['ime'] ) ? $item['ime'] : '',
                'citat' => ! empty( $item['citat'] ) ? $item['citat'] : '',
                'slika' => $slika_url,
            );
        }
    }
}

// =============================================================================
// FALLBACK PODACI (za development/test)
// =============================================================================

/**
 * Fallback mehanizam:
 * 
 * - Podaci se NE dupliciraju ovdje jer postoje u test/templates/poznati/mock-data.php
 * - U test okruženju, mock-data.php simulira ACF get_field() pozive
 * - Za custom fallback u produkciji, koristi filter 'pcz_poznati_fallback_data'
 * 
 * Primjer korištenja filtera:
 * add_filter('pcz_poznati_fallback_data', function($data) {
 *     return [
 *         ['ime' => 'Ime Prezime', 'citat' => 'Tekst citata', 'slika' => 'URL'],
 *     ];
 * });
 */
if ( empty( $testimonijali ) && $pcz_use_fallback ) {
    $using_fallback = true;
    
    // Dohvati fallback podatke preko filtera (omogućuje centralizirano upravljanje)
    $fallback_data = array();
    
    if ( function_exists('apply_filters') ) {
        $fallback_data = apply_filters( 'pcz_poznati_fallback_data', $fallback_data );
    }
    
    $testimonijali = $fallback_data;
}

// =============================================================================
// PROVJERA - IZLAZ AKO NEMA SADRŽAJA
// =============================================================================

if ( empty( $testimonijali ) ) {
    // Samo admin vidi poruku
    if ( function_exists('current_user_can') && current_user_can( 'manage_options' ) ) {
        $admin_url = function_exists('admin_url') ? admin_url( 'admin.php?page=site-settings' ) : '#';
        echo '<div class="pcz-poznati pcz-poznati--empty" style="background: linear-gradient(180deg, #C71585 0%, #e87db8 50%, transparent 100%); padding: 60px 20px; text-align: center;">';
        echo '<p style="color: white; font-size: 18px; margin: 0;">⚠️ <strong>Poznati o PCZ-u</strong> sekcija nema sadržaja.</p>';
        echo '<p style="color: rgba(255,255,255,0.9); font-size: 14px; margin-top: 10px;">';
        echo 'Dodajte testimonijale u <a href="' . esc_url( $admin_url ) . '" style="color: white; text-decoration: underline;">Site Settings → Poznati</a>';
        echo '</p></div>';
    }
    // Na frontendu za obične korisnike - ne prikazuj ništa
    return;
}

// =============================================================================
// GENERIRANJE JEDINSTVENOG ID-a
// =============================================================================

$slider_id = 'pcz-poznati-' . ( function_exists( 'wp_rand' ) ? wp_rand( 1000, 9999 ) : mt_rand( 1000, 9999 ) );

?>

<!-- ==================== POZNATI O PCZ-u SEKCIJA ==================== -->
<?php if ( $using_fallback && function_exists('current_user_can') && current_user_can( 'manage_options' ) ) : ?>
<!-- Admin Notice: Using Fallback Data -->
<div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px 16px; margin: 0; font-size: 14px;">
    ⚠️ <strong>Demo mod:</strong> Prikazuju se primjeri podataka. 
    <a href="<?php echo esc_url( function_exists('admin_url') ? admin_url( 'admin.php?page=site-settings' ) : '#' ); ?>">Dodajte testimonijale u ACF</a> 
    da biste vidjeli stvarne podatke.
</div>
<?php endif; ?>

<section class="pcz-poznati" id="<?php echo esc_attr( $slider_id ); ?>" data-fallback="<?php echo $using_fallback ? 'true' : 'false'; ?>">
    <div class="pcz-poznati__container">
        
        <!-- Header -->
        <header class="pcz-poznati__header">
            <h2 class="pcz-poznati__title"><?php echo esc_html( $naslov ); ?></h2>
            <div class="pcz-poznati__divider"></div>
        </header>
        
        <!-- Desktop Grid -->
        <div class="pcz-poznati__grid">
            <?php foreach ( $testimonijali as $index => $item ) : ?>
            <article class="pcz-poznati__item" data-index="<?php echo esc_attr( $index ); ?>">
                <div class="pcz-poznati__image-wrapper">
                    <?php if ( ! empty( $item['slika'] ) ) : ?>
                    <img 
                        src="<?php echo esc_url( $item['slika'] ); ?>" 
                        alt="<?php echo esc_attr( $item['ime'] ); ?>"
                        class="pcz-poznati__image"
                        loading="lazy"
                    >
                    <?php else : ?>
                    <div class="pcz-poznati__image pcz-poznati__image--placeholder">
                        <?php echo esc_html( function_exists('mb_substr') ? mb_substr( $item['ime'], 0, 2 ) : substr( $item['ime'], 0, 2 ) ); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <blockquote class="pcz-poznati__quote">
                    <?php echo esc_html( $item['citat'] ); ?>
                </blockquote>
                <cite class="pcz-poznati__name"><?php echo esc_html( $item['ime'] ); ?></cite>
            </article>
            <?php endforeach; ?>
        </div>
        
        <!-- Mobile Slider -->
        <div class="pcz-poznati__slider" aria-label="Testimonijali slider">
            <div class="pcz-poznati__slider-track">
                <?php foreach ( $testimonijali as $index => $item ) : ?>
                <div class="pcz-poznati__slide" data-index="<?php echo esc_attr( $index ); ?>">
                    <article class="pcz-poznati__item">
                        <div class="pcz-poznati__image-wrapper">
                            <?php if ( ! empty( $item['slika'] ) ) : ?>
                            <img 
                                src="<?php echo esc_url( $item['slika'] ); ?>" 
                                alt="<?php echo esc_attr( $item['ime'] ); ?>"
                                class="pcz-poznati__image"
                                loading="lazy"
                            >
                            <?php else : ?>
                            <div class="pcz-poznati__image pcz-poznati__image--placeholder">
                                <?php echo esc_html( function_exists('mb_substr') ? mb_substr( $item['ime'], 0, 2 ) : substr( $item['ime'], 0, 2 ) ); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <blockquote class="pcz-poznati__quote">
                            <?php echo esc_html( $item['citat'] ); ?>
                        </blockquote>
                        <cite class="pcz-poznati__name"><?php echo esc_html( $item['ime'] ); ?></cite>
                    </article>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Arrow Navigation -->
            <div class="pcz-poznati__arrows">
                <button type="button" class="pcz-poznati__arrow pcz-poznati__arrow--prev" aria-label="Prethodni">
                    ‹
                </button>
                <button type="button" class="pcz-poznati__arrow pcz-poznati__arrow--next" aria-label="Sljedeći">
                    ›
                </button>
            </div>
            
            <!-- Dot Navigation -->
            <nav class="pcz-poznati__nav" aria-label="Slider navigacija">
                <?php foreach ( $testimonijali as $index => $item ) : ?>
                <button 
                    type="button" 
                    class="pcz-poznati__dot<?php echo $index === 0 ? ' is-active' : ''; ?>"
                    data-slide="<?php echo esc_attr( $index ); ?>"
                    aria-label="Slide <?php echo esc_attr( $index + 1 ); ?>"
                ></button>
                <?php endforeach; ?>
            </nav>
        </div>
        
    </div>
</section>
<!-- ==================== /POZNATI O PCZ-u SEKCIJA ==================== -->
