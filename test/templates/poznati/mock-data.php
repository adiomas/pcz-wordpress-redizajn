<?php
/**
 * Mock podaci za "Poznati o PCZ-u" komponentu
 * 
 * Testimonijali poznatih osoba o Plesnom Centru Zagreb.
 * 
 * ⚠️ NAPOMENA: Ovo je JEDINI IZVOR test podataka za ovu komponentu!
 * Prema PROMPT_TEMPLATE.md - ne dupliciramo podatke u produkcijskom kodu.
 * poznati.php koristi filter 'pcz_poznati_fallback_data' za fallback.
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
        'poznati_naslov' => 'POZNATI O PCZ-u',
        
        // Testimonijali - Repeater field
        'poznati_testimonijali' => [
            [
                'ime'   => 'DUBRAVKO MERLIĆ',
                'citat' => 'Ispalo je da se cijeli doživljaj pretvorio u iznimno iskustvo!',
                'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/D-merlic-min.png',
            ],
            [
                'ime'   => 'DANIELA TRBOVIĆ',
                'citat' => 'Suradnja s Nicolasom zbog Plesa sa zvijezdama bila je jedna od ljepših u mom životu.',
                'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/trbovic-min.png',
            ],
            [
                'ime'   => 'ZORAN VAKULA',
                'citat' => 'U Plesni centar Zagreb hodočastim gotovo svaki drugi dan.',
                'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/vakula-min.png',
            ],
            [
                'ime'   => 'ZRINKA CVITEŠIĆ',
                'citat' => 'Uživala sam u svakoj sekundi ovdje u plesnom centru.',
                'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/cvitesic-min.png',
            ],
        ],
        
        // Pozadinska boja / gradient - glatki prijelaz u footer contact bg (#f5f5f5)
        'poznati_pozadina' => 'linear-gradient(180deg, #C71585 0%, #d84a9a 25%, #e87db8 45%, #f0b8d4 60%, #f5d4e6 72%, #f7e4ed 82%, #f6eeef 90%, #f5f5f5 100%)',
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
        // Default - svi testimonijali
        'default' => [],
        
        // Samo 2 testimonijala
        'two_items' => [
            'acf_fields' => [
                'poznati_testimonijali' => [
                    [
                        'ime'   => 'DUBRAVKO MERLIĆ',
                        'citat' => 'Ispalo je da se cijeli doživljaj pretvorio u iznimno iskustvo!',
                        'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/D-merlic-min.png',
                    ],
                    [
                        'ime'   => 'ZRINKA CVITEŠIĆ',
                        'citat' => 'Uživala sam u svakoj sekundi ovdje u plesnom centru.',
                        'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/cvitesic-min.png',
                    ],
                ],
            ],
        ],
        
        // Više testimonijala (6 komada)
        'many_items' => [
            'acf_fields' => [
                'poznati_testimonijali' => [
                    [
                        'ime'   => 'DUBRAVKO MERLIĆ',
                        'citat' => 'Ispalo je da se cijeli doživljaj pretvorio u iznimno iskustvo!',
                        'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/D-merlic-min.png',
                    ],
                    [
                        'ime'   => 'DANIELA TRBOVIĆ',
                        'citat' => 'Suradnja s Nicolasom zbog Plesa sa zvijezdama bila je jedna od ljepših u mom životu.',
                        'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/trbovic-min.png',
                    ],
                    [
                        'ime'   => 'ZORAN VAKULA',
                        'citat' => 'U Plesni centar Zagreb hodočastim gotovo svaki drugi dan.',
                        'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/vakula-min.png',
                    ],
                    [
                        'ime'   => 'ZRINKA CVITEŠIĆ',
                        'citat' => 'Uživala sam u svakoj sekundi ovdje u plesnom centru.',
                        'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/cvitesic-min.png',
                    ],
                    [
                        'ime'   => 'IVAN PERIĆ',
                        'citat' => 'Sjajan tim profesionalaca koji znaju prenijeti ljubav prema plesu.',
                        'slika' => 'https://via.placeholder.com/200x200/C71585/ffffff?text=IP',
                    ],
                    [
                        'ime'   => 'MARIJA NOVAK',
                        'citat' => 'Nicolas i njegov tim su mi pomogli da zaplešem kao nikad prije.',
                        'slika' => 'https://via.placeholder.com/200x200/C71585/ffffff?text=MN',
                    ],
                ],
            ],
        ],
        
        // Alternativna pozadina (tamnija - koristi --pcz-primary-hover: #a01269)
        'dark_bg' => [
            'acf_fields' => [
                'poznati_pozadina' => 'linear-gradient(180deg, #a01269 0%, #C71585 25%, #d84a9a 45%, #e8a8c8 60%, #f2c8dc 72%, #f5dce8 82%, #f6eeef 90%, #f5f5f5 100%)',
            ],
        ],
        
        // Jedan testimonijal
        'single' => [
            'acf_fields' => [
                'poznati_testimonijali' => [
                    [
                        'ime'   => 'DUBRAVKO MERLIĆ',
                        'citat' => 'Ispalo je da se cijeli doživljaj pretvorio u iznimno iskustvo!',
                        'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/D-merlic-min.png',
                    ],
                ],
            ],
        ],
        
        // Dugi citati
        'long_quotes' => [
            'acf_fields' => [
                'poznati_testimonijali' => [
                    [
                        'ime'   => 'DUBRAVKO MERLIĆ',
                        'citat' => 'Ispalo je da se cijeli doživljaj pretvorio u iznimno iskustvo! Svaki put kada dođem u Plesni centar Zagreb osjećam se kao kod kuće.',
                        'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/D-merlic-min.png',
                    ],
                    [
                        'ime'   => 'DANIELA TRBOVIĆ',
                        'citat' => 'Suradnja s Nicolasom zbog Plesa sa zvijezdama bila je jedna od ljepših u mom životu. Naučila sam toliko toga i preporučujem svima koji žele naučiti plesati.',
                        'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/trbovic-min.png',
                    ],
                    [
                        'ime'   => 'ZORAN VAKULA',
                        'citat' => 'U Plesni centar Zagreb hodočastim gotovo svaki drugi dan. Atmosfera je fenomenalna, a instruktori su pravi profesionalci koji znaju prenijeti znanje.',
                        'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/vakula-min.png',
                    ],
                    [
                        'ime'   => 'ZRINKA CVITEŠIĆ',
                        'citat' => 'Uživala sam u svakoj sekundi ovdje u plesnom centru. Ples me opušta i čini sretnom, a ovdje sam pronašla pravo mjesto za to.',
                        'slika' => 'https://pcz.hr/wp-content/uploads/2020/01/cvitesic-min.png',
                    ],
                ],
            ],
        ],
        
        // Prazni podaci - testira prazan state
        'empty' => [
            'acf_fields' => [
                'poznati_naslov' => 'POZNATI O PCZ-u',
                'poznati_testimonijali' => [],
            ],
        ],
        
        // Bez slika - testira placeholder inicijale
        'no_images' => [
            'acf_fields' => [
                'poznati_testimonijali' => [
                    [
                        'ime'   => 'DUBRAVKO MERLIĆ',
                        'citat' => 'Ispalo je da se cijeli doživljaj pretvorio u iznimno iskustvo!',
                        'slika' => '',
                    ],
                    [
                        'ime'   => 'DANIELA TRBOVIĆ',
                        'citat' => 'Suradnja s Nicolasom bila je jedna od ljepših u mom životu.',
                        'slika' => '',
                    ],
                    [
                        'ime'   => 'ZORAN VAKULA',
                        'citat' => 'U Plesni centar Zagreb hodočastim gotovo svaki drugi dan.',
                        'slika' => '',
                    ],
                    [
                        'ime'   => 'ZRINKA CVITEŠIĆ',
                        'citat' => 'Uživala sam u svakoj sekundi ovdje u plesnom centru.',
                        'slika' => '',
                    ],
                ],
            ],
        ],
    ],
];

