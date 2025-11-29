<?php
/**
 * Mock podaci za Hero sekciju
 * 
 * Fullwidth hero s pozadinskom slikom i intro sekcijom.
 * 
 * @package pcz_Test_Environment
 * @since 1.0.0
 */

$base_url = pcz_SITE_URL ?? 'http://localhost:8080';

return [
    // =========================================================================
    // ACF Fields (simulira get_field() pozive)
    // =========================================================================
    'acf_fields' => [
        // Hero pozadinska slika - koristi PCZ produkcijsku sliku
        'hero_pozadinska_slika' => 'https://pcz.hr/wp-content/uploads/2025/01/hero-main-scaled.jpg',
        
        // Tagline (kurziv tekst iznad naslova)
        'hero_tagline' => 'plesom do zdravlja!',
        
        // Glavni naslov
        'hero_naslov' => "Pokret koji\nmijenja život",
        
        // Podnaslov
        'hero_podnaslov' => 'Plesna škola i Sportski klub koji spajaju rekreativni i profesionalni ples.',
        
        // CTA gumb
        'hero_cta_tekst' => 'ŽELIM PLESATI!',
        'hero_cta_link'  => '#kontakt',
        
        // Intro sekcija
        'hero_intro_naslov'  => 'Jedan plesni centar, dva svijeta plesa.',
        'hero_intro_tekst'   => 'U <strong>Plesnoj školi</strong> učimo plesati srcem, a u <strong>Sportskom klubu</strong> srcem postajemo prvaci.',
        'hero_intro_tekst_2' => 'Zaprati nas na društvenim mrežama i budi dio naše plesne energije — priča, inspiracija i pokreta koji povezuju.',
        
        // Socijalne mreže
        'hero_socijalne_mreze' => [
            [
                'ikona' => 'instagram',
                'url'   => 'https://instagram.com/plesnicentarzagreb',
            ],
            [
                'ikona' => 'facebook',
                'url'   => 'https://facebook.com/plesnicentarzagreb',
            ],
        ],
    ],
    
    // =========================================================================
    // Site Info
    // =========================================================================
    'site' => [
        'name'        => 'Plesni Centar Zagreb by Nicolas',
        'description' => 'Najbolja plesna škola u Zagrebu',
        'url'         => $base_url,
    ],
    
    // =========================================================================
    // Page State
    // =========================================================================
    'page_state' => [
        'is_front_page' => true,
        'is_home'       => true,
    ],
    
    // =========================================================================
    // Test Scenarios
    // =========================================================================
    'scenarios' => [
        // Default - svi podaci
        'default' => [],
        
        // Bez intro sekcije
        'no_intro' => [
            'acf_fields' => [
                'hero_intro_naslov'  => '',
                'hero_intro_tekst'   => '',
                'hero_intro_tekst_2' => '',
                'hero_socijalne_mreze' => [],
            ],
        ],
        
        // Bez socijalnih mreža
        'no_social' => [
            'acf_fields' => [
                'hero_socijalne_mreze' => [],
            ],
        ],
        
        // Minimalni sadržaj - samo naslov i slika
        'minimal' => [
            'acf_fields' => [
                'hero_tagline'         => '',
                'hero_podnaslov'       => '',
                'hero_cta_tekst'       => '',
                'hero_cta_link'        => '',
                'hero_intro_naslov'    => '',
                'hero_intro_tekst'     => '',
                'hero_intro_tekst_2'   => '',
                'hero_socijalne_mreze' => [],
            ],
        ],
        
        // Kratki naslov (jedna linija)
        'short_title' => [
            'acf_fields' => [
                'hero_naslov' => 'Zaplešimo zajedno!',
            ],
        ],
        
        // Dugi naslov
        'long_title' => [
            'acf_fields' => [
                'hero_naslov'    => "Pokret koji\nmijenja život\ni otvara nove horizonte",
                'hero_podnaslov' => 'Plesna škola i Sportski klub koji spajaju rekreativni i profesionalni ples. Pridruži se stotinama zadovoljnih polaznika i otkrij čaroliju plesa.',
            ],
        ],
        
        // Sve socijalne mreže
        'all_social' => [
            'acf_fields' => [
                'hero_socijalne_mreze' => [
                    [ 'ikona' => 'instagram', 'url' => 'https://instagram.com/plesnicentarzagreb' ],
                    [ 'ikona' => 'facebook', 'url' => 'https://facebook.com/plesnicentarzagreb' ],
                    [ 'ikona' => 'youtube', 'url' => 'https://youtube.com/@plesnicentarzagreb' ],
                    [ 'ikona' => 'tiktok', 'url' => 'https://tiktok.com/@plesnicentarzagreb' ],
                ],
            ],
        ],
        
        // Alternativna pozadinska slika (placeholder)
        'placeholder_bg' => [
            'acf_fields' => [
                'hero_pozadinska_slika' => 'https://images.unsplash.com/photo-1547153760-18fc86324498?w=1920&q=80',
            ],
        ],
        
        // Tamna slika - za testiranje kontrasta
        'dark_bg' => [
            'acf_fields' => [
                'hero_pozadinska_slika' => 'https://images.unsplash.com/photo-1508700929628-666bc8bd84ea?w=1920&q=80',
            ],
        ],
        
        // =======================================================================
        // BRAND-AWARE SCENARIJI
        // =======================================================================
        
        // Brand: Plesna Škola (default, magenta)
        'brand_plesna_skola' => [
            'acf_fields' => [
                'hero_tagline'   => 'plesom do zdravlja!',
                'hero_naslov'    => "Naučite plesati\ns ljubavlju",
                'hero_podnaslov' => 'Plesna škola koja spaja srce i pokret. Za sve uzraste.',
                'hero_cta_tekst' => 'UPIŠITE SE DANAS!',
                'hero_cta_link'  => '#upis',
                'hero_intro_naslov' => 'Plesna škola za sve generacije',
                'hero_intro_tekst'  => 'U <strong>Plesnoj školi</strong> učimo plesati srcem, uživamo u pokretu i gradimo prijateljstva.',
                
                // Brand konfiguracija
                'current_brand'    => 'plesna-skola',
                'ps_primary_color' => '#CB007C',
            ],
        ],
        
        // Brand: Sportski Klub (orange)
        'brand_sportski_klub' => [
            'acf_fields' => [
                // SPK Hero polja
                'spk_hero_pozadinska_slika' => 'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=1920&q=80',
                'spk_hero_tagline'          => 'plesni šampioni!',
                'spk_hero_naslov'           => "Postani\nprvak",
                'spk_hero_podnaslov'        => 'Sportski klub koji oblikuje natjecatelje i osvaja medalje.',
                'spk_hero_cta_tekst'        => 'PRIDRUŽI SE TIMU!',
                'spk_hero_cta_link'         => '#prijava',
                'spk_intro_naslov'          => 'Natjecateljski duh u svakom koraku',
                'spk_intro_tekst'           => 'U <strong>Sportskom klubu</strong> treniramo za pobjedu i gradimo karaktere šampiona.',
                
                // Brand konfiguracija
                'current_brand'     => 'sportski-klub',
                'spk_primary_color' => '#FF6B00',
            ],
        ],
    ],
];

