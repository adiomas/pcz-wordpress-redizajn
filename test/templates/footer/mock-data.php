<?php
/**
 * Mock podaci za Footer komponentu
 * 
 * Ovaj fajl simulira ACF polja za footer - kontakt info, social linkovi, copyright.
 * Struktura odgovara ACF field grupi definirano u acf_footer_fields.json
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
        // Logo (dijeli se s headerom)
        'site_logo' => '/test/assets/placeholder/logo.svg',
        
        // =====================================================================
        // LOGO VISINA - desktop i mobile
        // =====================================================================
        'footer_logo_height_desktop' => 50,  // Preporučeno: 40-60px
        'footer_logo_height_mobile' => 40,   // Preporučeno: 32-48px
        
        // =====================================================================
        // RADNO VRIJEME - footer_radno_vrijeme (Group)
        // =====================================================================
        'footer_radno_vrijeme' => [
            'pon_pet' => 'od 14:00 do 22:00 sata',
            'sub'     => 'od 10:00 do 14:00 sati',
            'ned'     => 'ne radimo',
        ],
        
        // =====================================================================
        // KONTAKT - footer_kontakt (Group)
        // =====================================================================
        'footer_kontakt' => [
            'telefon' => '+385 98 9157 443',
            'email'   => 'info@pcz.hr',
            'adresa'  => 'Ozaljska 93, Zagreb',
        ],
        
        // =====================================================================
        // GOOGLE MAPS - footer_google_maps_embed (URL)
        // =====================================================================
        'footer_google_maps_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2781.5!2d15.9441!3d45.7989!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4765d6e8c8c8c8c8%3A0x0!2sOzaljska%2093%2C%2010000%20Zagreb!5e0!3m2!1shr!2shr!4v1',
        
        // =====================================================================
        // SOCIAL LINKOVI - footer_social_links (Repeater)
        // =====================================================================
        'footer_social_links' => [
            [
                'platforma' => 'instagram',
                'url'       => 'https://instagram.com/plesnicentarzagreb',
            ],
            [
                'platforma' => 'facebook',
                'url'       => 'https://facebook.com/plesnicentarzagreb',
            ],
        ],
        
        // =====================================================================
        // FOOTER NAVIGACIJA - footer_nav_links (Repeater)
        // Koristi se ako nema WordPress Menu na "footer-menu" lokaciji
        // =====================================================================
        'footer_nav_links' => [
            ['label' => 'Naslovna', 'url' => $base_url . '/'],
            ['label' => 'Ponuda', 'url' => $base_url . '/ponuda/'],
            ['label' => 'Cjenik', 'url' => $base_url . '/cjenik-usluga/'],
            ['label' => 'O nama', 'url' => $base_url . '/strucni-tim/'],
            ['label' => 'Kontakt', 'url' => $base_url . '/kontakt-i-lokacija/'],
        ],
        
        // =====================================================================
        // COPYRIGHT - footer_copyright (Text)
        // {year} se automatski zamjenjuje s trenutnom godinom
        // =====================================================================
        'footer_copyright' => '© {year}. Plesni Centar Zagreb by Nicolas. Sva prava pridržana.',
        
        // =====================================================================
        // PRAVNI LINKOVI - footer_legal_links (Repeater)
        // =====================================================================
        'footer_legal_links' => [
            ['label' => 'Pravila privatnosti', 'url' => $base_url . '/pravila-privatnosti/'],
            ['label' => 'Uvjeti i odredbe', 'url' => $base_url . '/uvjeti-i-odredbe/'],
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
        // Default - puni footer sa svim podacima
        'default' => [],
        
        // Bez social linkova
        'no_social' => [
            'acf_fields' => [
                'footer_social_links' => [],
            ],
        ],
        
        // Minimalni footer (bez kontakt sekcije)
        'minimal' => [
            'acf_fields' => [
                'footer_social_links' => [],
                'footer_legal_links'  => [],
                'footer_kontakt'      => null,
                'footer_radno_vrijeme' => null,
                'footer_google_maps_embed' => '',
            ],
        ],
        
        // Bez loga (tekstualni prikaz)
        'no_logo' => [
            'acf_fields' => [
                'site_logo' => null,
            ],
        ],
        
        // Samo footer bez kontakt sekcije
        'footer_only' => [
            'acf_fields' => [
                'footer_kontakt'       => null,
                'footer_radno_vrijeme' => null,
                'footer_google_maps_embed' => '',
            ],
        ],
        
        // Svi social linkovi
        'all_social' => [
            'acf_fields' => [
                'footer_social_links' => [
                    ['platforma' => 'instagram', 'url' => 'https://instagram.com/plesnicentarzagreb'],
                    ['platforma' => 'facebook', 'url' => 'https://facebook.com/plesnicentarzagreb'],
                    ['platforma' => 'youtube', 'url' => 'https://youtube.com/plesnicentarzagreb'],
                    ['platforma' => 'twitter', 'url' => 'https://twitter.com/plesnicentarzagreb'],
                    ['platforma' => 'linkedin', 'url' => 'https://linkedin.com/company/plesnicentarzagreb'],
                ],
            ],
        ],
        
        // Duži copyright tekst
        'long_copyright' => [
            'acf_fields' => [
                'footer_copyright' => '© {year} Plesni Centar Zagreb by Nicolas d.o.o. - Plesna škola s tradicijom od 1990. godine. Sva prava pridržana. Zabranjeno je kopiranje sadržaja bez dozvole.',
            ],
        ],
        
        // Veći logo
        'large_logo' => [
            'acf_fields' => [
                'footer_logo_height_desktop' => 70,
                'footer_logo_height_mobile' => 55,
            ],
        ],
        
        // Manji logo
        'small_logo' => [
            'acf_fields' => [
                'footer_logo_height_desktop' => 36,
                'footer_logo_height_mobile' => 28,
            ],
        ],
    ],
];
