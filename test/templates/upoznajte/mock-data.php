<?php
/**
 * Mock podaci za "Upoznajte nas" komponentu
 * 
 * Sekcija za upoznavanje s plesnom školom - vlasnici, misija, vizija
 * SAMO za Plesnu Školu (skriveno za Sportski Klub)
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
        'upoznajte_naslov' => 'UPOZNAJTE NAS',
        
        // Slogan (rukopisni tekst)
        'upoznajte_slogan' => 'Za trapave noge i vesele duše',
        
        // Istaknute riječi u sloganu (magenta boja)
        'upoznajte_slogan_highlight_1' => 'trapave',
        'upoznajte_slogan_highlight_2' => 'vesele',
        
        // Slika vlasnika/instruktora - fullwidth pozadina (desktop)
        'upoznajte_slika' => 'https://pcz.hr/wp-content/uploads/2023/01/nicolas-i-martina.jpg',
        
        // Slika za mobile (portrait format)
        'upoznajte_slika_mobile' => 'https://pcz.hr/wp-content/uploads/2023/01/nicolas-i-martina-mobile.jpg',
        
        // Opacity pozadinske slike (0-100, default 40)
        'upoznajte_opacity' => 40,
        
        // Podnaslov
        'upoznajte_podnaslov' => 'Prvi korak je Vaš ostali su naši',
        
        // Glavni tekst
        'upoznajte_tekst' => 'U današnjem vremenu stalnih promjena, brojnih ponuda i odluka, dobro je imati jednu konstantu – nešto sigurno, pouzdano dobro, provjereno, svoje...',
        
        // Istaknut tekst (bold)
        'upoznajte_istaknut_tekst' => 'Plesni centar Zagreb by Nicolas',
        
        // Nastavak teksta
        'upoznajte_nastavak_teksta' => '– najprepoznatljiviji, najprisutniji i najraznolikiji je Plesni centar u Hrvatskoj! Već više godina smo na samom vrhu, zajedno s vama! Veselimo se Vašem posjetu',
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
        
        // Bez slike
        'no_image' => [
            'acf_fields' => [
                'upoznajte_slika' => '',
                'upoznajte_slika_mobile' => '',
            ],
        ],
        
        // Bez mobile slike (koristi desktop)
        'no_mobile_image' => [
            'acf_fields' => [
                'upoznajte_slika_mobile' => '',
            ],
        ],
        
        // Kratki tekst
        'short_text' => [
            'acf_fields' => [
                'upoznajte_tekst' => 'Dobrodošli u naš plesni centar!',
                'upoznajte_nastavak_teksta' => '– najbolja škola plesa u gradu.',
            ],
        ],
        
        // Dugi tekst
        'long_text' => [
            'acf_fields' => [
                'upoznajte_tekst' => 'U današnjem vremenu stalnih promjena, brojnih ponuda i odluka, dobro je imati jednu konstantu – nešto sigurno, pouzdano dobro, provjereno, svoje. U našoj plesnoj školi pronašli smo upravo to – dom za sve koji vole ples, glazbu i druženje. Ovdje svaki korak ima značenje, svaka melodija priča priču.',
                'upoznajte_nastavak_teksta' => '– najprepoznatljiviji, najprisutniji i najraznolikiji je Plesni centar u Hrvatskoj! Već više godina smo na samom vrhu, zajedno s vama! Naši instruktori su profesionalci s višegodišnjim iskustvom koji će vas voditi korak po korak. Veselimo se Vašem posjetu i prvom plesu zajedno!',
            ],
        ],
        
        // Bez istaknutog teksta
        'no_highlight' => [
            'acf_fields' => [
                'upoznajte_istaknut_tekst' => '',
            ],
        ],
        
        // Alternativni slogan
        'alt_slogan' => [
            'acf_fields' => [
                'upoznajte_slogan' => 'Gdje svaki korak postaje umjetnost',
                'upoznajte_slogan_highlight_1' => 'korak',
                'upoznajte_slogan_highlight_2' => 'umjetnost',
            ],
        ],
        
        // Bez highlighta u sloganu
        'no_slogan_highlight' => [
            'acf_fields' => [
                'upoznajte_slogan_highlight_1' => '',
                'upoznajte_slogan_highlight_2' => '',
            ],
        ],
        
        // Minimalni sadržaj
        'minimal' => [
            'acf_fields' => [
                'upoznajte_naslov' => 'O NAMA',
                'upoznajte_slogan' => '',
                'upoznajte_slogan_highlight_1' => '',
                'upoznajte_slogan_highlight_2' => '',
                'upoznajte_podnaslov' => 'Dobrodošli',
                'upoznajte_tekst' => 'Plesni centar Zagreb osnovao je Nicolas i danas je jedna od najpopularnijih škola plesa.',
                'upoznajte_istaknut_tekst' => '',
                'upoznajte_nastavak_teksta' => '',
            ],
        ],
        
        // Placeholder slika
        'placeholder_image' => [
            'acf_fields' => [
                'upoznajte_slika' => 'https://via.placeholder.com/1920x1080/f0f0f0/333333?text=Upoznajte+nas',
                'upoznajte_slika_mobile' => 'https://via.placeholder.com/800x1200/f0f0f0/333333?text=Mobile',
            ],
        ],
        
        // Različite opacity vrijednosti za testiranje
        'low_opacity' => [
            'acf_fields' => [
                'upoznajte_opacity' => 20, // Vrlo vidljiva slika
            ],
        ],
        
        'high_opacity' => [
            'acf_fields' => [
                'upoznajte_opacity' => 70, // Manje vidljiva slika
            ],
        ],
        
        'medium_opacity' => [
            'acf_fields' => [
                'upoznajte_opacity' => 50, // Srednja vidljivost
            ],
        ],
        
        // Prazni podaci - testira prazan state
        'empty' => [
            'acf_fields' => [
                'upoznajte_naslov' => 'UPOZNAJTE NAS',
                'upoznajte_slogan' => '',
                'upoznajte_slogan_highlight_1' => '',
                'upoznajte_slogan_highlight_2' => '',
                'upoznajte_slika' => '',
                'upoznajte_slika_mobile' => '',
                'upoznajte_podnaslov' => '',
                'upoznajte_tekst' => '',
                'upoznajte_istaknut_tekst' => '',
                'upoznajte_nastavak_teksta' => '',
            ],
        ],
    ],
];
