<?php
/**
 * pcz Brand Switcher - UI Komponenta
 * 
 * Tab switcher za prebacivanje između brandova na naslovnoj stranici.
 * 
 * KORIŠTENJE:
 * 1. Shortcode: [pcz_brand_switcher]
 * 2. Direktno: include 'brand/brand-switcher.php';
 * 
 * ATRIBUTI:
 * - style: "tabs" (default) | "buttons" | "pills"
 * - size: "normal" (default) | "small" | "large"
 * - position: "inline" (default) | "fixed-top" | "fixed-bottom"
 * 
 * @package pcz_Redizajn
 * @since 4.0.0
 */

// Sprječava direktan pristup
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Uključi brand logic ako nije već učitan
if ( ! function_exists( 'pcz_get_current_brand' ) ) {
    require_once __DIR__ . '/brand.php';
}

// =============================================================================
// SHORTCODE HANDLER
// =============================================================================

/**
 * Render brand switcher shortcode
 * 
 * Shortcode atributi override-aju ACF postavke.
 * Ako atribut nije zadan, koristi se ACF vrijednost.
 * 
 * @param array $atts Shortcode atributi
 * @return string HTML output
 */
function pcz_brand_switcher_shortcode( $atts = [] ) {
    // Dohvati ACF postavke kao defaulte
    $acf = pcz_get_switcher_settings();
    
    // Definiraj shortcode atribute s ACF defaultima
    $atts = shortcode_atts( [
        'style'      => '',             // Prazan = koristi ACF
        'size'       => '',             // Prazan = koristi ACF
        'position'   => 'inline',       // inline, fixed-top, fixed-bottom
        'alignment'  => '',             // Prazan = koristi ACF
        'labels'     => 'full',         // full, short, icon
        'show_icons' => '',             // Prazan = koristi ACF
        'ps_label'   => '',             // Prazan = koristi ACF
        'spk_label'  => '',             // Prazan = koristi ACF
        'intro'      => '',             // Prazan = koristi ACF
        'animated'   => 'true',         // true, false
    ], $atts, 'pcz_brand_switcher' );
    
    // Build options array - koristi shortcode ako je zadan, inače ACF
    $options = [
        'style'      => ! empty( $atts['style'] ) ? $atts['style'] : $acf['style'],
        'size'       => ! empty( $atts['size'] ) ? $atts['size'] : $acf['size'],
        'position'   => $atts['position'],
        'alignment'  => ! empty( $atts['alignment'] ) ? $atts['alignment'] : $acf['alignment'],
        'labels'     => $atts['labels'],
        'show_icons' => $atts['show_icons'] !== '' ? filter_var( $atts['show_icons'], FILTER_VALIDATE_BOOLEAN ) : $acf['show_icons'],
        'ps_label'   => ! empty( $atts['ps_label'] ) ? $atts['ps_label'] : $acf['ps_label'],
        'spk_label'  => ! empty( $atts['spk_label'] ) ? $atts['spk_label'] : $acf['spk_label'],
        'intro_text' => ! empty( $atts['intro'] ) ? $atts['intro'] : $acf['intro_text'],
        'animated'   => filter_var( $atts['animated'], FILTER_VALIDATE_BOOLEAN ),
    ];
    
    ob_start();
    pcz_render_brand_switcher( $options );
    return ob_get_clean();
}

/**
 * Dohvati ACF postavke za brand switcher
 * 
 * @return array Postavke iz ACF-a ili defaulti
 */
function pcz_get_switcher_settings() {
    $defaults = [
        'enabled'     => true,
        'style'       => 'tabs',
        'size'        => 'normal',
        'position'    => 'hero',
        'alignment'   => 'center',
        'show_icons'  => false,
        'ps_label'    => 'Plesna Škola',
        'spk_label'   => 'Sportski Klub',
        'intro_text'  => '',
    ];
    
    // Ako ACF nije dostupan, vrati defaulte
    if ( ! function_exists( 'get_field' ) ) {
        return $defaults;
    }
    
    // Dohvati postavke iz ACF-a
    return [
        'enabled'     => get_field( 'brand_switcher_enabled', 'option' ) ?? $defaults['enabled'],
        'style'       => get_field( 'brand_switcher_style', 'option' ) ?: $defaults['style'],
        'size'        => get_field( 'brand_switcher_size', 'option' ) ?: $defaults['size'],
        'position'    => get_field( 'brand_switcher_position', 'option' ) ?: $defaults['position'],
        'alignment'   => get_field( 'brand_switcher_alignment', 'option' ) ?: $defaults['alignment'],
        'show_icons'  => get_field( 'brand_switcher_show_icons', 'option' ) ?? $defaults['show_icons'],
        'ps_label'    => get_field( 'brand_switcher_ps_label', 'option' ) ?: $defaults['ps_label'],
        'spk_label'   => get_field( 'brand_switcher_spk_label', 'option' ) ?: $defaults['spk_label'],
        'intro_text'  => get_field( 'brand_switcher_intro_text', 'option' ) ?: $defaults['intro_text'],
    ];
}

/**
 * Render brand switcher HTML
 * 
 * @param array $options Opcije za renderiranje (override ACF postavki)
 * @return void
 */
function pcz_render_brand_switcher( $options = [] ) {
    // Dohvati ACF postavke
    $acf_settings = pcz_get_switcher_settings();
    
    // Provjeri je li switcher uključen
    if ( empty( $options ) && ! $acf_settings['enabled'] ) {
        return;
    }
    
    // Definiraj defaulte
    $defaults = [
        'style'      => $acf_settings['style'],
        'size'       => $acf_settings['size'],
        'position'   => 'inline',
        'alignment'  => $acf_settings['alignment'],
        'show_icons' => $acf_settings['show_icons'],
        'ps_label'   => $acf_settings['ps_label'],
        'spk_label'  => $acf_settings['spk_label'],
        'intro_text' => $acf_settings['intro_text'],
        'labels'     => 'full',
        'animated'   => true,
    ];
    
    // Merge s proslijeđenim opcijama (shortcode atributi override)
    $options = array_merge( $defaults, $options );
    
    // Dohvati brand podatke
    $brands = pcz_get_brand_defaults();
    $current_brand_id = pcz_get_current_brand_id();
    
    // Custom labele za brandove
    $brand_labels = [
        'plesna-skola'  => $options['ps_label'],
        'sportski-klub' => $options['spk_label'],
    ];
    
    // CSS klase
    $wrapper_classes = [
        'pcz-brand-switcher',
        'pcz-brand-switcher--' . $options['style'],
        'pcz-brand-switcher--' . $options['size'],
        'pcz-brand-switcher--' . $options['position'],
        'pcz-brand-switcher--align-' . $options['alignment'],
    ];
    
    if ( $options['animated'] ) {
        $wrapper_classes[] = 'pcz-brand-switcher--animated';
    }
    
    ?>
    <div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" 
         role="tablist" 
         aria-label="Odabir branda"
         data-current-brand="<?php echo esc_attr( $current_brand_id ); ?>">
        
        <?php if ( ! empty( $options['intro_text'] ) ) : ?>
            <span class="pcz-brand-switcher__intro">
                <?php echo esc_html( $options['intro_text'] ); ?>
            </span>
        <?php endif; ?>
        
        <div class="pcz-brand-switcher__tabs">
            <?php foreach ( $brands as $brand_id => $brand ) : 
                $is_active = ( $brand_id === $current_brand_id );
                $brand_url = pcz_get_brand_url( $brand_id );
                
                // Koristi custom labelu ako postoji, inače default
                $label = isset( $brand_labels[ $brand_id ] ) && ! empty( $brand_labels[ $brand_id ] ) 
                    ? $brand_labels[ $brand_id ] 
                    : ( $options['labels'] === 'short' ? $brand['short_name'] : $brand['name'] );
                
                // CSS klase za tab
                $tab_classes = [
                    'pcz-brand-switcher__tab',
                    'pcz-brand-switcher__tab--' . $brand_id,
                ];
                if ( $is_active ) {
                    $tab_classes[] = 'is-active';
                }
                ?>
                
                <a href="<?php echo esc_url( $brand_url ); ?>"
                   class="<?php echo esc_attr( implode( ' ', $tab_classes ) ); ?>"
                   role="tab"
                   aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                   aria-controls="brand-content-<?php echo esc_attr( $brand_id ); ?>"
                   data-brand="<?php echo esc_attr( $brand_id ); ?>"
                   data-primary-color="<?php echo esc_attr( $brand['primary_color'] ); ?>"
                   style="--tab-color: <?php echo esc_attr( $brand['primary_color'] ); ?>;">
                    
                    <?php if ( $options['show_icons'] || $options['labels'] === 'icon' ) : ?>
                        <span class="pcz-brand-switcher__icon">
                            <?php echo pcz_get_brand_icon( $brand_id ); ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ( $options['labels'] !== 'icon' ) : ?>
                        <span class="pcz-brand-switcher__label">
                            <?php echo esc_html( strtoupper( $label ) ); ?>
                        </span>
                    <?php endif; ?>
                    
                </a>
                
            <?php endforeach; ?>
            
            <!-- Active indicator (za animaciju) -->
            <span class="pcz-brand-switcher__indicator" aria-hidden="true"></span>
        </div>
        
    </div>
    <?php
}

/**
 * Dohvati SVG ikonu za brand
 * 
 * @param string $brand_id Brand ID
 * @return string SVG HTML
 */
function pcz_get_brand_icon( $brand_id ) {
    $icons = [
        'plesna-skola' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="5" r="3"/><path d="m12 8-1.5 3.5-3.5.5 2.5 2.5-.5 3.5 3-2 3 2-.5-3.5 2.5-2.5-3.5-.5z"/><path d="M12 22v-5"/></svg>',
        'sportski-klub' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>',
    ];
    
    return $icons[ $brand_id ] ?? $icons['plesna-skola'];
}

// =============================================================================
// ALTERNATIVE RENDERS
// =============================================================================

/**
 * Render compact brand toggle (za header)
 * 
 * DIZAJN: Moderan pill toggle s dva kruga u brand bojama
 * Aktivni brand ima veći krug, klik prebacuje na drugi brand
 * 
 * @return void
 */
function pcz_render_brand_toggle() {
    $current = pcz_get_current_brand();
    $brands = pcz_get_brand_defaults();
    
    // Pronađi drugi brand
    $other_brand_id = null;
    foreach ( $brands as $id => $brand ) {
        if ( $id !== $current['id'] ) {
            $other_brand_id = $id;
            break;
        }
    }
    
    if ( ! $other_brand_id ) {
        return;
    }
    
    $other_brand = $brands[ $other_brand_id ];
    $is_ps_active = ( $current['id'] === 'plesna-skola' );
    ?>
    <a href="<?php echo esc_url( pcz_get_brand_url( $other_brand_id ) ); ?>"
       class="pcz-brand-mini-toggle"
       title="Prebaci na <?php echo esc_attr( $other_brand['name'] ); ?>"
       data-brand="<?php echo esc_attr( $other_brand_id ); ?>"
       data-current="<?php echo esc_attr( $current['id'] ); ?>">
        <!-- Plesna Škola dot -->
        <span class="pcz-brand-mini-toggle__dot pcz-brand-mini-toggle__dot--ps <?php echo $is_ps_active ? 'is-active' : ''; ?>"
              style="--dot-color: #C71585;">
            <svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
        </span>
        <!-- Slider track -->
        <span class="pcz-brand-mini-toggle__track"></span>
        <!-- Sportski Klub dot -->
        <span class="pcz-brand-mini-toggle__dot pcz-brand-mini-toggle__dot--spk <?php echo !$is_ps_active ? 'is-active' : ''; ?>"
              style="--dot-color: #FF6B00;">
            <svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
        </span>
    </a>
    <?php
}

/**
 * Render floating brand badge (za corner display)
 * 
 * @return void
 */
function pcz_render_brand_badge() {
    $brand = pcz_get_current_brand();
    ?>
    <div class="pcz-brand-badge" style="--badge-color: <?php echo esc_attr( $brand['primary_color'] ); ?>;">
        <span class="pcz-brand-badge__name"><?php echo esc_html( $brand['short_name'] ); ?></span>
    </div>
    <?php
}

