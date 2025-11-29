<?php
/**
 * Mock podaci za Brand Switcher komponentu
 * 
 * Ovaj fajl definira sve podatke potrebne za testiranje brand sustava
 * bez potrebe za WordPressom ili ACF-om.
 * 
 * @package pcz_Test_Environment
 * @since 1.0.0
 */

$base_url = pcz_SITE_URL ?? 'http://localhost:8080';

return [
    // =========================================================================
    // ACF Fields (get_field simulacija)
    // =========================================================================
    'acf_fields' => [
        // Opće postavke
        'default_brand'        => 'plesna-skola',
        'brand_aware_header'   => true,
        'brand_switcher_position' => 'hero',
        
        // === PLESNA ŠKOLA ===
        'site_logo' => '/header/header-logo.svg',
        
        // Hero - Plesna Škola
        'hero_pozadinska_slika' => 'https://pcz.hr/wp-content/uploads/2020/01/hero-bg.jpg',
        'hero_tagline'          => 'plesom do zdravlja!',
        'hero_naslov'           => "Pokret koji\nmijenja život",
        'hero_podnaslov'        => 'Plesna škola i Sportski klub koji spajaju rekreativni i profesionalni ples.',
        'hero_cta_tekst'        => 'ŽELIM PLESATI!',
        'hero_cta_link'         => '#kontakt',
        
        // Intro - Plesna Škola
        'hero_intro_naslov'   => 'Jedan plesni centar, dva svijeta plesa.',
        'hero_intro_tekst'    => 'U <strong>Plesnoj školi</strong> učimo plesati srcem, a u <strong>Sportskom klubu</strong> srcem postajemo prvaci.',
        'hero_intro_tekst_2'  => 'Zaprati nas na društvenim mrežama i budi dio naše plesne energije.',
        
        // === SPORTSKI KLUB ===
        'sportski_klub_logo'    => '/test/assets/placeholder/spk-logo.svg',
        'spk_primary_color'     => '#FF6B00',
        
        // Hero - Sportski Klub
        'spk_hero_pozadinska_slika' => 'https://pcz.hr/wp-content/uploads/2023/sport-hero.jpg',
        'spk_hero_tagline'          => 'sportom do pobjede!',
        'spk_hero_naslov'           => "Pobjednički\nduh u plesu",
        'spk_hero_podnaslov'        => 'Natjecateljski programi za mlade plesače s ambicijama.',
        'spk_hero_cta_tekst'        => 'PRIJAVI SE!',
        'spk_hero_cta_link'         => '#prijava',
        
        // Intro - Sportski Klub
        'spk_intro_naslov' => 'Gdje se rađaju prvaci.',
        'spk_intro_tekst'  => 'Naš sportski program usmjeren je na <strong>natjecateljski ples</strong> i razvoj mladih talenata.',
        
        // Socijalne mreže (zajedničke)
        'hero_socijalne_mreze' => [
            ['ikona' => 'instagram', 'url' => 'https://instagram.com/plesnicentarzagreb'],
            ['ikona' => 'facebook', 'url' => 'https://facebook.com/plesnicentarzagreb'],
            ['ikona' => 'youtube', 'url' => 'https://youtube.com/plesnicentarzagreb'],
        ],
        
        // Poznati testimonijali (za oba branda)
        'poznati_naslov' => 'POZNATI O PCZ-u',
        'poznati_testimonijali' => [
            [
                'ime'   => 'Marija Husar',
                'citat' => 'PCZ je mjesto gdje sam pronašla ljubav prema plesu.',
                'slika' => 'https://pcz.hr/wp-content/uploads/poznati/marija.jpg',
            ],
            [
                'ime'   => 'Ivan Perić',
                'citat' => 'Najbolji treneri i prijateljska atmosfera!',
                'slika' => 'https://pcz.hr/wp-content/uploads/poznati/ivan.jpg',
            ],
            [
                'ime'   => 'Ana Kovač',
                'citat' => 'Od prvog tečaja do prvog plesnog natjecanja - sve zahvaljujući PCZ-u.',
                'slika' => 'https://pcz.hr/wp-content/uploads/poznati/ana.jpg',
            ],
        ],
    ],
    
    // =========================================================================
    // Brand Configuration
    // =========================================================================
    'brands' => [
        'plesna-skola' => [
            'id'               => 'plesna-skola',
            'name'             => 'Plesna Škola',
            'short_name'       => 'PCZ',
            'tagline'          => 'Plesni Centar Zagreb by Nicolas',
            'primary_color'    => '#C71585',
            'primary_hover'    => '#a01269',
            'secondary_color'  => '#ff6b9d',
            'accent_color'     => '#ffc107',
            'gradient'         => 'linear-gradient(135deg, #C71585 0%, #ff6b9d 100%)',
            'is_default'       => true,
        ],
        'sportski-klub' => [
            'id'               => 'sportski-klub',
            'name'             => 'Sportski Klub',
            'short_name'       => 'SPK',
            'tagline'          => 'Sportski Plesni Klub Zagreb',
            'primary_color'    => '#FF6B00',
            'primary_hover'    => '#CC5500',
            'secondary_color'  => '#FFA500',
            'accent_color'     => '#FFD700',
            'gradient'         => 'linear-gradient(135deg, #FF6B00 0%, #FFA500 100%)',
            'is_default'       => false,
        ],
    ],
    
    // =========================================================================
    // Site info
    // =========================================================================
    'site' => [
        'name'        => 'Plesni Centar Zagreb',
        'description' => 'Plesna škola i sportski klub',
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
        // Default - Plesna Škola
        'default' => [],
        
        // Sportski Klub aktivni
        'sportski_klub' => [
            'acf_fields' => [
                'current_brand' => 'sportski-klub',
            ],
            'query_params' => [
                'brand' => 'sportski-klub',
            ],
        ],
        
        // Brand switching simulacija
        'brand_switch' => [
            'acf_fields' => [
                'brand_switcher_position' => 'fixed-bottom',
            ],
        ],
        
        // Header bez brand awareness
        'header_neutral' => [
            'acf_fields' => [
                'brand_aware_header' => false,
            ],
        ],
        
        // Samo switcher (minimalno)
        'switcher_only' => [
            'acf_fields' => [
                'brand_switcher_position' => 'fixed-top',
            ],
        ],
        
        // Pills stil switchera
        'switcher_pills' => [
            'acf_fields' => [
                'brand_switcher_style' => 'pills',
            ],
        ],
        
        // Buttons stil switchera
        'switcher_buttons' => [
            'acf_fields' => [
                'brand_switcher_style' => 'buttons',
            ],
        ],
    ],
];

