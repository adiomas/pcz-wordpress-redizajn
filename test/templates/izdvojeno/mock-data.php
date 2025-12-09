<?php
/**
 * Mock podaci za "Izdvojeno iz ponude" sekciju
 * 
 * Prikazuje kategorije tečajeva/disciplina s karticama.
 * Podržava oba branda: Plesna škola i Sportski klub.
 * 
 * @package PCZ_Test_Environment
 * @since 1.0.0
 */

$base_url = pcz_SITE_URL ?? 'http://localhost:8080';

return [
    // =========================================================================
    // ACF Fields (simulira get_field() pozive)
    // =========================================================================
    'acf_fields' => [
        // =====================================================================
        // PLESNA ŠKOLA (prefix: izdvojeno_)
        // =====================================================================
        
        // Naslov sekcije
        'izdvojeno_naslov' => 'Izdvojeno iz ponude',
        
        // Kartice - Repeater field
        'izdvojeno_kartice' => [
            [
                'naziv' => 'TEČAJ DRUŠTVENIH PLESOVA',
                'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/DSC_6605-min-scaled.jpg',
                'link'  => '/tecajevi/drustveni-plesovi/',
            ],
            [
                'naziv' => 'LATINO & KARIPSKI PLESOVI',
                'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/salsa-cubana-min.jpg',
                'link'  => '/tecajevi/latino-plesovi/',
            ],
            [
                'naziv' => 'VJENČANI PLES',
                'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/vjencani-ples-min.jpg',
                'link'  => '/tecajevi/vjencani-ples/',
            ],
            [
                'naziv' => 'PLESNA ŠKOLA ZA DJECU',
                'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/djeca-ples-min.jpg',
                'link'  => '/tecajevi/djeca/',
            ],
        ],
        
        // CTA Button
        'izdvojeno_cta_tekst' => 'VIŠE O USLUGAMA',
        'izdvojeno_cta_link'  => '/usluge/',
        
        // =====================================================================
        // SPORTSKI KLUB (prefix: izdvojeno_sk_)
        // =====================================================================
        
        // Naslov sekcije
        'izdvojeno_sk_naslov' => 'Sportske discipline',
        
        // Kartice - Repeater field
        'izdvojeno_sk_kartice' => [
            [
                'naziv' => 'LA (latinsko-američki plesovi)',
                'slika' => 'https://images.unsplash.com/photo-1547153760-18fc86324498?w=400&h=533&fit=crop',
                'link'  => '/discipline/latinsko-americki-plesovi/',
            ],
            [
                'naziv' => 'ST (standardni plesovi)',
                'slika' => 'https://images.unsplash.com/photo-1508700929628-666bc8bd84ea?w=400&h=533&fit=crop',
                'link'  => '/discipline/standardni-plesovi/',
            ],
            [
                'naziv' => 'Kombinacija 10 sportskih plesova',
                'slika' => 'https://images.unsplash.com/photo-1504609813442-a8924e83f76e?w=400&h=533&fit=crop',
                'link'  => '/discipline/kombinacija-10-plesova/',
            ],
        ],
        
        // CTA Button
        'izdvojeno_sk_cta_tekst' => 'VIDI VIŠE',
        'izdvojeno_sk_cta_link'  => '/sportski-klub/',
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
        // Default - 4 kartice (Plesna škola)
        'default' => [],
        
        // =====================================================================
        // SPORTSKI KLUB SCENARIJI
        // =====================================================================
        
        // Sportski klub - 3 discipline
        'sportski_klub' => [
            'brand' => 'sportski-klub',
        ],
        
        // Sportski klub - 4 discipline
        'sportski_klub_4_cards' => [
            'brand' => 'sportski-klub',
            'acf_fields' => [
                'izdvojeno_sk_kartice' => [
                    [
                        'naziv' => 'LA (latinsko-američki plesovi)',
                        'slika' => 'https://images.unsplash.com/photo-1547153760-18fc86324498?w=400&h=533&fit=crop',
                        'link'  => '/discipline/latinsko-americki-plesovi/',
                    ],
                    [
                        'naziv' => 'ST (standardni plesovi)',
                        'slika' => 'https://images.unsplash.com/photo-1508700929628-666bc8bd84ea?w=400&h=533&fit=crop',
                        'link'  => '/discipline/standardni-plesovi/',
                    ],
                    [
                        'naziv' => 'Kombinacija 10 sportskih plesova',
                        'slika' => 'https://images.unsplash.com/photo-1504609813442-a8924e83f76e?w=400&h=533&fit=crop',
                        'link'  => '/discipline/kombinacija-10-plesova/',
                    ],
                    [
                        'naziv' => 'NATJECATELJSKI PROGRAM',
                        'slika' => 'https://images.unsplash.com/photo-1518834107812-67b0b7c58434?w=400&h=533&fit=crop',
                        'link'  => '/discipline/natjecateljski-program/',
                    ],
                ],
            ],
        ],
        
        // =====================================================================
        // PLESNA ŠKOLA SCENARIJI
        // =====================================================================
        
        // Točno 4 kartice (optimalni prikaz)
        'four_cards' => [],
        
        // Samo 2 kartice
        'two_cards' => [
            'acf_fields' => [
                'izdvojeno_kartice' => [
                    [
                        'naziv' => 'TEČAJ DRUŠTVENIH PLESOVA',
                        'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/DSC_6605-min-scaled.jpg',
                        'link'  => '/tecajevi/drustveni-plesovi/',
                    ],
                    [
                        'naziv' => 'LATINO & KARIPSKI PLESOVI',
                        'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/salsa-cubana-min.jpg',
                        'link'  => '/tecajevi/latino-plesovi/',
                    ],
                ],
            ],
        ],
        
        // 6 kartica
        'six_cards' => [
            'acf_fields' => [
                'izdvojeno_kartice' => [
                    [
                        'naziv' => 'TEČAJ DRUŠTVENIH PLESOVA',
                        'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/DSC_6605-min-scaled.jpg',
                        'link'  => '/tecajevi/drustveni-plesovi/',
                    ],
                    [
                        'naziv' => 'LATINO & KARIPSKI PLESOVI',
                        'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/salsa-cubana-min.jpg',
                        'link'  => '/tecajevi/latino-plesovi/',
                    ],
                    [
                        'naziv' => 'VJENČANI PLES',
                        'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/vjencani-ples-min.jpg',
                        'link'  => '/tecajevi/vjencani-ples/',
                    ],
                    [
                        'naziv' => 'PLESNA ŠKOLA ZA DJECU',
                        'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/djeca-ples-min.jpg',
                        'link'  => '/tecajevi/djeca/',
                    ],
                    [
                        'naziv' => 'INDIVIDUALNI SATI',
                        'slika' => 'https://images.unsplash.com/photo-1504609813442-a8924e83f76e?w=400&q=80',
                        'link'  => '/tecajevi/individualni/',
                    ],
                    [
                        'naziv' => 'GRUPNI TEČAJEVI',
                        'slika' => 'https://images.unsplash.com/photo-1547153760-18fc86324498?w=400&q=80',
                        'link'  => '/tecajevi/grupni/',
                    ],
                ],
            ],
        ],
        
        // Bez CTA buttona
        'no_cta' => [
            'acf_fields' => [
                'izdvojeno_cta_tekst' => '',
                'izdvojeno_cta_link'  => '',
            ],
        ],
        
        // Dugi nazivi kartica
        'long_titles' => [
            'acf_fields' => [
                'izdvojeno_kartice' => [
                    [
                        'naziv' => 'TEČAJ DRUŠTVENIH PLESOVA ZA POČETNIKE I NAPREDNE',
                        'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/DSC_6605-min-scaled.jpg',
                        'link'  => '/tecajevi/drustveni-plesovi/',
                    ],
                    [
                        'naziv' => 'LATINO & KARIPSKI PLESOVI - SALSA, BACHATA, KIZOMBA',
                        'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/salsa-cubana-min.jpg',
                        'link'  => '/tecajevi/latino-plesovi/',
                    ],
                    [
                        'naziv' => 'VJENČANI PLES - PRIPREMA ZA NAJLJEPŠI DAN',
                        'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/vjencani-ples-min.jpg',
                        'link'  => '/tecajevi/vjencani-ples/',
                    ],
                    [
                        'naziv' => 'PLESNA ŠKOLA ZA DJECU OD 4 DO 14 GODINA',
                        'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/djeca-ples-min.jpg',
                        'link'  => '/tecajevi/djeca/',
                    ],
                ],
            ],
        ],
        
        // Bez slika (placeholder)
        'no_images' => [
            'acf_fields' => [
                'izdvojeno_kartice' => [
                    [
                        'naziv' => 'TEČAJ DRUŠTVENIH PLESOVA',
                        'slika' => '',
                        'link'  => '/tecajevi/drustveni-plesovi/',
                    ],
                    [
                        'naziv' => 'LATINO & KARIPSKI PLESOVI',
                        'slika' => '',
                        'link'  => '/tecajevi/latino-plesovi/',
                    ],
                    [
                        'naziv' => 'VJENČANI PLES',
                        'slika' => '',
                        'link'  => '/tecajevi/vjencani-ples/',
                    ],
                    [
                        'naziv' => 'PLESNA ŠKOLA ZA DJECU',
                        'slika' => '',
                        'link'  => '/tecajevi/djeca/',
                    ],
                ],
            ],
        ],
        
        // Prazna sekcija (testira empty state)
        'empty' => [
            'acf_fields' => [
                'izdvojeno_kartice' => [],
            ],
        ],
        
        // Custom naslov
        'custom_title' => [
            'acf_fields' => [
                'izdvojeno_naslov' => 'Naša ponuda tečajeva',
            ],
        ],
    ],
];
