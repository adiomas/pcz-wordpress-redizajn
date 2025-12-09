<?php
/**
 * Mock podaci za "Sportski Klub" komponentu
 * 
 * Sekcija za predstavljanje Sportskog kluba Plesnog centra Zagreb.
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
        'spk_naslov' => 'SPORTSKI KLUB',
        
        // Podnaslov (bold dio)
        'spk_podnaslov' => 'Sportski klub Plesnog centra Zagreb',
        
        // Opis - prvi paragraf (nastavlja se na podnaslov)
        'spk_opis' => 'uči, istražuje, stvara i svakodnevno pruža zadovoljstvo svakom našem plesaču – učeniku i njihovim roditeljima.',
        
        // Nastavak opisa - drugi paragraf
        'spk_nastavak_opisa' => 'Stvaramo sportske plesne parove s najnovijim plesnim stilom latinsko-američkih i standardnih plesova, sigurno i kontinuirano idemo naprijed oblikujući budućnost naših plesača, a stalnim usavršavanjem svih naših trenera – učitelja, osiguravamo velik, uspješan i zadovoljan Plesni Centar Zagreb.',
        
        // Navigacijski linkovi
        'spk_linkovi' => [
            [
                'tekst' => 'RASPORED TRENINGA',
                'stranica' => $base_url . '/raspored-treninga/',
            ],
            [
                'tekst' => 'STRUČNI TIM',
                'stranica' => $base_url . '/strucni-tim/',
            ],
            [
                'tekst' => 'GALERIJA',
                'stranica' => $base_url . '/galerija/',
            ],
            [
                'tekst' => 'PROJEKTI',
                'stranica' => $base_url . '/projekti/',
            ],
            [
                'tekst' => 'PRIVATNA PODUKA',
                'stranica' => $base_url . '/privatna-poduka/',
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
        
        // Minimalan - samo naslov i linkovi
        'minimal' => [
            'acf_fields' => [
                'spk_opis' => '',
                'spk_nastavak_opisa' => '',
            ],
        ],
        
        // Bez linkova
        'no_links' => [
            'acf_fields' => [
                'spk_linkovi' => [],
            ],
        ],
        
        // Kratak opis
        'short_description' => [
            'acf_fields' => [
                'spk_opis' => 'uči, istražuje i stvara.',
                'spk_nastavak_opisa' => '',
            ],
        ],
        
        // Dugačak opis
        'long_description' => [
            'acf_fields' => [
                'spk_opis' => 'uči, istražuje, stvara i svakodnevno pruža zadovoljstvo svakom našem plesaču – učeniku i njihovim roditeljima.',
                'spk_nastavak_opisa' => 'Stvaramo sportske plesne parove s najnovijim plesnim stilom latinsko-američkih i standardnih plesova, sigurno i kontinuirano idemo naprijed oblikujući budućnost naših plesača, a stalnim usavršavanjem svih naših trenera – učitelja, osiguravamo velik, uspješan i zadovoljan Plesni Centar Zagreb. Naša misija je širiti ljubav prema plesu i omogućiti svima da osjete magiju pokreta kroz glazbu i zajedništvo.',
            ],
        ],
        
        // Više linkova
        'many_links' => [
            'acf_fields' => [
                'spk_linkovi' => [
                    ['tekst' => 'RASPORED TRENINGA', 'stranica' => $base_url . '/raspored-treninga/'],
                    ['tekst' => 'STRUČNI TIM', 'stranica' => $base_url . '/strucni-tim/'],
                    ['tekst' => 'GALERIJA', 'stranica' => $base_url . '/galerija/'],
                    ['tekst' => 'PROJEKTI', 'stranica' => $base_url . '/projekti/'],
                    ['tekst' => 'PRIVATNA PODUKA', 'stranica' => $base_url . '/privatna-poduka/'],
                    ['tekst' => 'USPJESI', 'stranica' => $base_url . '/uspjesi/'],
                    ['tekst' => 'NATJECANJA', 'stranica' => $base_url . '/natjecanja/'],
                ],
            ],
        ],
        
        // Dva linka
        'two_links' => [
            'acf_fields' => [
                'spk_linkovi' => [
                    ['tekst' => 'RASPORED TRENINGA', 'stranica' => $base_url . '/raspored-treninga/'],
                    ['tekst' => 'KONTAKT', 'stranica' => $base_url . '/kontakt/'],
                ],
            ],
        ],
        
        // Alternativni naslov
        'alt_title' => [
            'acf_fields' => [
                'spk_naslov' => 'NATJECATELJSKI PROGRAM',
                'spk_podnaslov' => 'Sportski plesni program',
            ],
        ],
        
        // Prazni podaci
        'empty' => [
            'acf_fields' => [
                'spk_naslov' => 'SPORTSKI KLUB',
                'spk_podnaslov' => '',
                'spk_opis' => '',
                'spk_nastavak_opisa' => '',
                'spk_linkovi' => [],
            ],
        ],
    ],
];


