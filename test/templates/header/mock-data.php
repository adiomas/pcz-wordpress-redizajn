<?php
/**
 * Mock podaci za Header komponentu
 * 
 * Ovaj fajl definira sve podatke potrebne za renderiranje header-a
 * bez potrebe za WordPressom ili ACF-om.
 * 
 * @package pcz_Test_Environment
 * @since 1.0.0
 */

// Base URL za linkove (prilagodi se automatski)
$base_url = pcz_SITE_URL ?? 'http://localhost:8080';

// =============================================================================
// ⚡ BRZA KONFIGURACIJA LOGA
// =============================================================================
// Promijeni URL ispod da testiraš svoj logo:
//
// Opcije:
// - Produkcijski logo:    '/header/header-logo.svg'
// - Lokalni placeholder:  '/test/assets/placeholder/logo.svg'
// - Vanjski URL:          'https://example.com/logo.png'
// - Base64 inline:        'data:image/svg+xml;base64,...'
// - Prazan za tekst:      null ili ''
//
$logo_url = '/header/header-logo.svg';
// =============================================================================

return [
    // =========================================================================
    // ACF Fields (get_field simulacija)
    // =========================================================================
    'acf_fields' => [
        // Logo - koristi varijablu odozgo
        'site_logo' => $logo_url,
        
        // Logo veličine (u pikselima)
        'logo_height_desktop' => 48,  // Preporučeno: 40-60px
        'logo_height_mobile' => 40,   // Preporučeno: 32-48px
        
        // Mega Menu Blokovi
        'ponuda_blokovi' => [
            // PONUDA ZA ODRASLE
            [
                'naslov' => 'PONUDA ZA ODRASLE',
                'podsekcije' => [
                    [
                        'naslov_podsekcije' => 'PLESNI TEČAJEVI',
                        'page_link_podsekcije' => '',
                        'stavke' => [
                            ['label' => 'Tečaj Društvenih plesova', 'url' => $base_url . '/tecaj-drustvenih-plesova/'],
                            ['label' => 'Tečaj Latin Jam', 'url' => $base_url . '/tecaj-latin-jam/'],
                        ],
                    ],
                    [
                        'naslov_podsekcije' => 'PLESNE REKREACIJE',
                        'page_link_podsekcije' => '',
                        'stavke' => [
                            ['label' => 'Rekreacija Društvenih plesova', 'url' => $base_url . '/rekreacija-drustvenih-plesova/'],
                            ['label' => 'Rekreacija Latin Jam', 'url' => $base_url . '/rekreacija-latin-jam/'],
                        ],
                    ],
                    [
                        'naslov_podsekcije' => 'BRZI TEČAJEVI',
                        'page_link_podsekcije' => $base_url . '/brzi-tecajevi/',
                        'stavke' => [],
                    ],
                    [
                        'naslov_podsekcije' => 'TEČAJEVI ZA MLADENCE',
                        'page_link_podsekcije' => $base_url . '/tecajevi-za-mladence/',
                        'stavke' => [],
                    ],
                    [
                        'naslov_podsekcije' => 'FULL DANCE WORKOUT',
                        'page_link_podsekcije' => $base_url . '/full-dance-workout/',
                        'stavke' => [],
                    ],
                    [
                        'naslov_podsekcije' => 'HIP HOP ZA ODRASLE',
                        'page_link_podsekcije' => $base_url . '/hip-hop-za-odrasle/',
                        'stavke' => [],
                    ],
                    [
                        'naslov_podsekcije' => 'INDIVIDUALNA PODUKA',
                        'page_link_podsekcije' => $base_url . '/individualna-poduka/',
                        'stavke' => [],
                    ],
                ],
            ],
            // PONUDA ZA DJECU
            [
                'naslov' => 'PONUDA ZA DJECU',
                'podsekcije' => [
                    [
                        'naslov_podsekcije' => 'PLESNA IGRAONICA',
                        'page_link_podsekcije' => $base_url . '/plesna-igraonica/',
                        'stavke' => [],
                    ],
                    [
                        'naslov_podsekcije' => 'HIP HOP ZA DJECU',
                        'page_link_podsekcije' => '',
                        'stavke' => [
                            ['label' => 'Rekreacijske grupe', 'url' => $base_url . '/hip-hop-rekreacijske-grupe/'],
                            ['label' => 'Natjecateljske grupe', 'url' => $base_url . '/hip-hop-natjecateljske-grupe/'],
                        ],
                    ],
                    [
                        'naslov_podsekcije' => 'PLESNE REKREACIJE',
                        'page_link_podsekcije' => '',
                        'stavke' => [
                            ['label' => 'Rekreacija Društvenih plesova za djecu', 'url' => $base_url . '/djeca-rekreacija-drustvenih-plesova/'],
                        ],
                    ],
                ],
            ],
        ],
    ],
    
    // =========================================================================
    // WordPress Menu simulacija
    // =========================================================================
    'nav_menu' => [
        [
            'title'     => 'Naslovna',
            'url'       => $base_url . '/',
            'is_ponuda' => false,
            'parent'    => 0,
        ],
        [
            'title'     => 'Ponuda',
            'url'       => '#',
            'is_ponuda' => true, // Backward compatibility
            'has_dropdown' => true,
            'dropdown_field' => 'ponuda_blokovi',
            'parent'    => 0,
        ],
        [
            'title'     => 'Cjenik',
            'url'       => $base_url . '/cjenik-usluga/',
            'is_ponuda' => false,
            'parent'    => 0,
        ],
        [
            'title'     => 'O nama',
            'url'       => $base_url . '/strucni-tim/',
            'is_ponuda' => false,
            'parent'    => 0,
        ],
        [
            'title'     => 'Kontakt',
            'url'       => $base_url . '/kontakt-i-lokacija/',
            'is_ponuda' => false,
            'parent'    => 0,
        ],
    ],
    
    // =========================================================================
    // Site info
    // =========================================================================
    'site' => [
        'name'        => 'Plesni Centar Zagreb by Nicolas',
        'description' => 'Najbolja plesna škola u Zagrebu',
        'url'         => $base_url,
    ],
    
    // =========================================================================
    // Theme Mods
    // =========================================================================
    'theme_mods' => [
        'custom_logo' => null, // Nema WordPress custom logo, koristi ACF
    ],
    
    // =========================================================================
    // Attachments (za wp_get_attachment_image)
    // =========================================================================
    'attachments' => [
        1 => [
            'url' => '/test/assets/placeholder/logo.svg',
            'alt' => 'Plesni Centar Zagreb Logo',
            'sizes' => [
                'thumbnail' => '/test/assets/placeholder/logo.svg',
                'medium'    => '/test/assets/placeholder/logo.svg',
                'full'      => '/test/assets/placeholder/logo.svg',
            ],
        ],
    ],
    
    // =========================================================================
    // Page State
    // =========================================================================
    'page_state' => [
        'is_front_page' => true,
        'is_home'       => true,
        'is_single'     => false,
        'is_page'       => false,
        'is_archive'    => false,
        'is_admin'      => false,
    ],
    
    // =========================================================================
    // Test Scenarios
    // =========================================================================
    'scenarios' => [
        // Default - koristi gore navedene podatke (SVG logo)
        'default' => [],
        
        // Bez loga (tekstualni prikaz)
        'no_logo' => [
            'acf_fields' => [
                'site_logo' => null,
            ],
        ],
        
        // Prazan menu (samo fallback)
        'empty_menu' => [
            'nav_menu' => [],
        ],
        
        // Minimalni podaci (bez mega menu-a)
        'minimal' => [
            'acf_fields' => [
                'site_logo'      => '/test/assets/placeholder/logo.svg',
                'ponuda_blokovi' => [],
            ],
        ],
        
        // Samo odrasli
        'only_adults' => [
            'acf_fields' => [
                'ponuda_blokovi' => [
                    [
                        'naslov' => 'PONUDA ZA ODRASLE',
                        'podsekcije' => [
                            [
                                'naslov_podsekcije' => 'PLESNI TEČAJEVI',
                                'page_link_podsekcije' => '',
                                'stavke' => [
                                    ['label' => 'Tečaj Društvenih plesova', 'url' => '#'],
                                    ['label' => 'Tečaj Latin Jam', 'url' => '#'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        
        // Aktivna stranica - Cjenik
        'active_cjenik' => [
            'page_state' => [
                'is_front_page' => false,
                'is_page'       => true,
            ],
        ],
    ],
];

