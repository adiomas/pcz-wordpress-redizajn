<?php
/**
 * Mock podaci za Prijava sekciju
 * 
 * Sekcija s formom za prijavu na tečajeve/treninge.
 * Brand-aware: razlikuje Plesnu školu i Sportski klub.
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
        // Plesna Škola (default)
        'prijava_naslov'    => 'Želim plesati!',
        'prijava_podnaslov' => 'Prijavite se bez obaveza. Kontaktirat ćemo vas u najkraćem roku.',
        'prijava_form_id'   => 1, // Mock form ID
        'prijava_pozadina'  => 'light',
        
        // Sportski Klub
        'spk_prijava_naslov'    => 'Želim trenirati!',
        'spk_prijava_podnaslov' => 'Prijavite se za treninge sportskog plesa. Javit ćemo vam se uskoro.',
        'spk_prijava_form_id'   => 1, // Može biti isti ili različit form
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
        // Default - Plesna škola
        'default' => [],
        
        // Sportski klub brand
        'sportski_klub' => [
            'acf_fields' => [
                // Brand će biti postavljen iz URL parametra
            ],
        ],
        
        // Gradient pozadina
        'gradient_bg' => [
            'acf_fields' => [
                'prijava_pozadina' => 'gradient',
            ],
        ],
        
        // Custom naslov
        'custom_title' => [
            'acf_fields' => [
                'prijava_naslov'    => 'Upišite se danas!',
                'prijava_podnaslov' => 'Ograničen broj mjesta. Ne propustite priliku naučiti plesati.',
            ],
        ],
        
        // Bez podnaslova
        'no_subtitle' => [
            'acf_fields' => [
                'prijava_podnaslov' => '',
            ],
        ],
        
        // SPK s custom tekstom
        'spk_custom' => [
            'acf_fields' => [
                'spk_prijava_naslov'    => 'Postani šampion!',
                'spk_prijava_podnaslov' => 'Pridruži se našem natjecateljskom timu i osvoji medalje.',
            ],
        ],
        
        // Bez forme (testira error state)
        'no_form' => [
            'acf_fields' => [
                'prijava_form_id' => 0,
            ],
        ],
    ],
    
    // =========================================================================
    // Gravity Forms Mock (za test environment bez GF plugina)
    // =========================================================================
    'gravity_forms' => [
        'forms' => [
            1 => [
                'id'     => 1,
                'title'  => 'Prijava na tečaj',
                'fields' => [
                    ['id' => 1, 'type' => 'text',     'label' => 'Ime i Prezime', 'isRequired' => true],
                    ['id' => 2, 'type' => 'phone',    'label' => 'Kontakt telefon', 'isRequired' => true],
                    ['id' => 3, 'type' => 'email',    'label' => 'E-mail adresa', 'isRequired' => true],
                    ['id' => 4, 'type' => 'select',   'label' => 'Tečaj za koji se prijavljujem', 'choices' => [
                        ['text' => 'Tečaj društvenih plesova', 'value' => 'drustveni'],
                        ['text' => 'Latino i karipski plesovi', 'value' => 'latino'],
                        ['text' => 'Vjenčani ples', 'value' => 'vjencani'],
                        ['text' => 'Ples za djecu', 'value' => 'djeca'],
                    ]],
                    ['id' => 5, 'type' => 'select',   'label' => 'Dolazak', 'choices' => [
                        ['text' => 'Sam/a', 'value' => 'sam'],
                        ['text' => 'U paru', 'value' => 'par'],
                        ['text' => 'Grupa', 'value' => 'grupa'],
                    ]],
                    ['id' => 6, 'type' => 'checkbox', 'label' => 'Newsletter prijava'],
                    ['id' => 7, 'type' => 'consent',  'label' => 'Slažem se s uporabom informacija', 'isRequired' => true],
                    ['id' => 8, 'type' => 'hidden',   'label' => 'Brand', 'defaultValue' => '{brand}'],
                ],
            ],
        ],
    ],
];

