<?php
/**
 * Mock podaci za "Dodatne Informacije" komponentu
 * 
 * Grid ikona s linkovima na dodatne informacije.
 * SAMO za Sportski Klub brand.
 * 
 * ⚠️ NAPOMENA: Ovo je JEDINI IZVOR test podataka za ovu komponentu!
 * Prema PROMPT_TEMPLATE.md - ne dupliciramo podatke u produkcijskom kodu.
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
        // Naslov sekcije
        'spk_dodatne_naslov' => 'DODATNE INFORMACIJE',
        
        // Stavke - repeater
        'spk_dodatne_stavke' => [
            [
                'ikona' => 'euro',
                'custom_svg' => '',
                'naziv' => 'CJENIK',
                'stranica' => $base_url . '/cjenik/',
            ],
            [
                'ikona' => 'kontakt',
                'custom_svg' => '',
                'naziv' => 'KONTAKT',
                'stranica' => $base_url . '/kontakt/',
            ],
            [
                'ikona' => 'lokacija',
                'custom_svg' => '',
                'naziv' => 'LOKACIJE TRENINGA',
                'stranica' => $base_url . '/lokacije-treninga/',
            ],
            [
                'ikona' => 'trofej',
                'custom_svg' => '',
                'naziv' => 'NAŠI REZULTATI',
                'stranica' => $base_url . '/rezultati/',
            ],
            [
                'ikona' => 'kamp',
                'custom_svg' => '',
                'naziv' => 'KAMPOVI I RADIONICE',
                'stranica' => $base_url . '/kampovi/',
            ],
            [
                'ikona' => 'faq',
                'custom_svg' => '',
                'naziv' => 'FAQ',
                'stranica' => $base_url . '/faq/',
            ],
            [
                'ikona' => 'podrska',
                'custom_svg' => '',
                'naziv' => 'PRIVATNA PODRŠKA',
                'stranica' => $base_url . '/privatna-podrska/',
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
        // Default - svi podaci (7 stavki)
        'default' => [],
        
        // Minimal - 3 stavke
        'minimal' => [
            'acf_fields' => [
                'spk_dodatne_stavke' => [
                    [
                        'ikona' => 'euro',
                        'custom_svg' => '',
                        'naziv' => 'CJENIK',
                        'stranica' => $base_url . '/cjenik/',
                    ],
                    [
                        'ikona' => 'kontakt',
                        'custom_svg' => '',
                        'naziv' => 'KONTAKT',
                        'stranica' => $base_url . '/kontakt/',
                    ],
                    [
                        'ikona' => 'faq',
                        'custom_svg' => '',
                        'naziv' => 'FAQ',
                        'stranica' => $base_url . '/faq/',
                    ],
                ],
            ],
        ],
        
        // Puno stavki - 10 stavki
        'many_items' => [
            'acf_fields' => [
                'spk_dodatne_stavke' => [
                    [
                        'ikona' => 'euro',
                        'custom_svg' => '',
                        'naziv' => 'CJENIK',
                        'stranica' => $base_url . '/cjenik/',
                    ],
                    [
                        'ikona' => 'kontakt',
                        'custom_svg' => '',
                        'naziv' => 'KONTAKT',
                        'stranica' => $base_url . '/kontakt/',
                    ],
                    [
                        'ikona' => 'lokacija',
                        'custom_svg' => '',
                        'naziv' => 'LOKACIJE',
                        'stranica' => $base_url . '/lokacije/',
                    ],
                    [
                        'ikona' => 'trofej',
                        'custom_svg' => '',
                        'naziv' => 'REZULTATI',
                        'stranica' => $base_url . '/rezultati/',
                    ],
                    [
                        'ikona' => 'kamp',
                        'custom_svg' => '',
                        'naziv' => 'KAMPOVI',
                        'stranica' => $base_url . '/kampovi/',
                    ],
                    [
                        'ikona' => 'faq',
                        'custom_svg' => '',
                        'naziv' => 'FAQ',
                        'stranica' => $base_url . '/faq/',
                    ],
                    [
                        'ikona' => 'podrska',
                        'custom_svg' => '',
                        'naziv' => 'PODRŠKA',
                        'stranica' => $base_url . '/podrska/',
                    ],
                    [
                        'ikona' => 'euro',
                        'custom_svg' => '',
                        'naziv' => 'UPISI',
                        'stranica' => $base_url . '/upisi/',
                    ],
                    [
                        'ikona' => 'trofej',
                        'custom_svg' => '',
                        'naziv' => 'NATJECANJA',
                        'stranica' => $base_url . '/natjecanja/',
                    ],
                    [
                        'ikona' => 'lokacija',
                        'custom_svg' => '',
                        'naziv' => 'PARKING',
                        'stranica' => $base_url . '/parking/',
                    ],
                ],
            ],
        ],
        
        // Dugi nazivi
        'long_labels' => [
            'acf_fields' => [
                'spk_dodatne_stavke' => [
                    [
                        'ikona' => 'euro',
                        'custom_svg' => '',
                        'naziv' => 'CJENIK TRENINGA',
                        'stranica' => $base_url . '/cjenik/',
                    ],
                    [
                        'ikona' => 'kontakt',
                        'custom_svg' => '',
                        'naziv' => 'KONTAKTIRAJTE NAS',
                        'stranica' => $base_url . '/kontakt/',
                    ],
                    [
                        'ikona' => 'lokacija',
                        'custom_svg' => '',
                        'naziv' => 'LOKACIJE TRENINGA',
                        'stranica' => $base_url . '/lokacije/',
                    ],
                    [
                        'ikona' => 'trofej',
                        'custom_svg' => '',
                        'naziv' => 'NAŠI REZULTATI I USPJESI',
                        'stranica' => $base_url . '/rezultati/',
                    ],
                ],
            ],
        ],
        
        // Samo 2 stavke
        'two_items' => [
            'acf_fields' => [
                'spk_dodatne_stavke' => [
                    [
                        'ikona' => 'kontakt',
                        'custom_svg' => '',
                        'naziv' => 'KONTAKT',
                        'stranica' => $base_url . '/kontakt/',
                    ],
                    [
                        'ikona' => 'faq',
                        'custom_svg' => '',
                        'naziv' => 'FAQ',
                        'stranica' => $base_url . '/faq/',
                    ],
                ],
            ],
        ],
        
        // Alternativni naslov
        'alt_title' => [
            'acf_fields' => [
                'spk_dodatne_naslov' => 'BRZI LINKOVI',
            ],
        ],
        
        // Prazni podaci
        'empty' => [
            'acf_fields' => [
                'spk_dodatne_naslov' => 'DODATNE INFORMACIJE',
                'spk_dodatne_stavke' => [],
            ],
        ],
        
        // Custom SVG ikone
        'custom_icons' => [
            'acf_fields' => [
                'spk_dodatne_stavke' => [
                    [
                        'ikona' => '',
                        'custom_svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>',
                        'naziv' => 'RASPORED',
                        'stranica' => $base_url . '/raspored/',
                    ],
                    [
                        'ikona' => '',
                        'custom_svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
                        'naziv' => 'O NAMA',
                        'stranica' => $base_url . '/o-nama/',
                    ],
                    [
                        'ikona' => '',
                        'custom_svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
                        'naziv' => 'RECENZIJE',
                        'stranica' => $base_url . '/recenzije/',
                    ],
                ],
            ],
        ],
    ],
];



