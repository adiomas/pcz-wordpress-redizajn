<?php
/**
 * Mock podaci - Template
 * 
 * UPUTE:
 * 1. Definiraj ACF polja koja tvoja komponenta koristi
 * 2. Dodaj nav_menu ako komponenta koristi WordPress menije
 * 3. Kreiraj scenarije za testiranje različitih stanja
 * 
 * @package pcz_Test_Environment
 * @since 1.0.0
 */

$base_url = pcz_SITE_URL ?? 'http://localhost:8080';

return [
    // =========================================================================
    // ACF Fields
    // =========================================================================
    'acf_fields' => [
        // Primjer: Tekst polje
        // 'naslov' => 'Primjer naslova',
        
        // Primjer: Slika polje
        // 'hero_image' => '/test/assets/placeholder/hero.jpg',
        
        // Primjer: Repeater polje
        // 'stavke' => [
        //     ['naslov' => 'Stavka 1', 'opis' => 'Opis stavke 1'],
        //     ['naslov' => 'Stavka 2', 'opis' => 'Opis stavke 2'],
        // ],
        
        // Primjer: Flexible Content
        // 'sekcije' => [
        //     ['acf_fc_layout' => 'hero', 'naslov' => 'Hero naslov'],
        //     ['acf_fc_layout' => 'cta', 'tekst' => 'Poziv na akciju'],
        // ],
    ],
    
    // =========================================================================
    // WordPress Menu (opcionalno)
    // =========================================================================
    'nav_menu' => [
        // ['title' => 'Stavka 1', 'url' => '#', 'parent' => 0],
        // ['title' => 'Stavka 2', 'url' => '#', 'parent' => 0],
    ],
    
    // =========================================================================
    // Site Info
    // =========================================================================
    'site' => [
        'name' => 'Plesni Centar Zagreb',
        'url'  => $base_url,
    ],
    
    // =========================================================================
    // Theme Mods (opcionalno)
    // =========================================================================
    'theme_mods' => [
        // 'custom_logo' => 1,
    ],
    
    // =========================================================================
    // Attachments (opcionalno)
    // =========================================================================
    'attachments' => [
        // 1 => [
        //     'url' => '/test/assets/placeholder/logo.svg',
        //     'alt' => 'Logo',
        // ],
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
        // Default scenario - koristi gore navedene podatke
        'default' => [],
        
        // Primjer: Prazan sadržaj
        // 'empty' => [
        //     'acf_fields' => [
        //         'naslov' => '',
        //         'stavke' => [],
        //     ],
        // ],
        
        // Primjer: Puno sadržaja
        // 'full' => [
        //     'acf_fields' => [
        //         'naslov' => 'Vrlo dugačak naslov koji testira prelom teksta u komponenti',
        //         'stavke' => array_fill(0, 10, ['naslov' => 'Stavka', 'opis' => 'Opis']),
        //     ],
        // ],
    ],
];




