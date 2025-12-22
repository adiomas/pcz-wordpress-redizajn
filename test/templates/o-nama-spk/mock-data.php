<?php
/**
 * Mock Data za O Nama - Sportski Klub sekciju
 * 
 * Simulira ACF get_field() pozive za lokalno testiranje
 * NOVI SVIJETLI DIZAJN (stil upoznajte)
 * 
 * @package pcz_Redizajn
 * @since 2.1.0
 */

return [
    // =========================================================================
    // DEFAULT PODACI (simulira ACF polja)
    // =========================================================================
    'acf_fields' => [
        // Naslov sekcije (uppercase)
        'spk_onama_naslov' => 'O nama',
        
        // Naslov akcent - sad kao slogan (handwritten stil)
        'spk_onama_naslov_akcent' => 'Sportski ples je naša strast',
        
        // Ime kluba
        'spk_onama_ime_kluba' => 'Sportski Plesni Klub Zagreb',
        
        // Logo kluba
        'spk_onama_logo' => '',
        
        // Pozadinska slika (svijetla slika za bijeli overlay)
        'spk_onama_slika' => 'https://images.unsplash.com/photo-1504609773096-104ff2c73ba4?w=1920&q=80',
        
        // Lead paragraf (istaknuti uvod)
        'spk_onama_lead' => '<p><strong>Sportski ples</strong> prema mišljenju mnogih, najljepši je dvoranski sport na svijetu. Na vrlo specifičan i atraktivan način u sebi sjedinjuje sport, umjetnost, glamur i smisao za show.</p>',
        
        // Lijevi stupac - glavni tekst
        'spk_onama_lijevi_stupac' => '<p>No iza sveg tog glamura, i malih i velikih plesača stoje sati i godine napornog rada, odricanja i treninga.</p>

<p>Znanje je temelj svakog uspjeha i jedini put u bolju budućnost, a potvrdu svojega rada vidimo svake godine u osmjehu svakog našeg plesača, roditelja i učitelja-trenera.</p>

<p>Sve što Vam je potrebno:</p>
<ul>
<li>redovan trening</li>
<li>stalan i pouzdan plesni partner</li>
<li>stručni i kvalitetni treneri-učitelji</li>
</ul>',
        
        // Desni stupac - dodatni tekst
        'spk_onama_desni_stupac' => '<p>Plesači se natječu u tri sportske discipline: LA (latinsko-američki plesovi), ST (standardni plesovi) i kombinacija 10 sportskih plesova.</p>

<p>Natječu se u više starosnih razreda, od mlađih osnovaca do veterana.</p>',
        
        // Highlight box
        'spk_onama_highlight_naslov' => 'Vodstvo',
        'spk_onama_highlight_tekst' => '<p>Sportsku grupu vodi naš najuspješniji hrvatski plesni par: <strong>Ksenija Pluščec Quesnoit & Nicolas Quesnoit</strong>, profesionalni prvaci Hrvatske.</p>',
        
        // Kontakt informacije
        'spk_onama_kontakt_osoba' => 'Dijana',
        'spk_onama_kontakt_telefon' => '091 1157 442',
        'spk_onama_kontakt_email' => 'spkzagreb@pcz.hr',
    ],
    
    // =========================================================================
    // SCENARIJI ZA TESTIRANJE
    // =========================================================================
    'scenarios' => [
        // Default - svi podaci
        'default' => [],
        
        // Minimalni sadržaj (samo lead)
        'minimal' => [
            'acf_fields' => [
                'spk_onama_logo' => '',
                'spk_onama_slika' => '',
                'spk_onama_naslov_akcent' => '',
                'spk_onama_lead' => '<p><strong>Sportski ples</strong> je najljepši dvoranski sport na svijetu. Pridružite nam se!</p>',
                'spk_onama_lijevi_stupac' => '',
                'spk_onama_desni_stupac' => '',
                'spk_onama_highlight_tekst' => '',
                'spk_onama_kontakt_osoba' => '',
                'spk_onama_kontakt_telefon' => '',
                'spk_onama_kontakt_email' => '',
            ],
        ],
        
        // Bez slike (testira placeholder pozadinu)
        'no_image' => [
            'acf_fields' => [
                'spk_onama_slika' => '',
            ],
        ],
        
        // Bez loga (koristi default SVG)
        'no_logo' => [
            'acf_fields' => [
                'spk_onama_logo' => '',
            ],
        ],
        
        // Bez kontakta
        'no_contact' => [
            'acf_fields' => [
                'spk_onama_kontakt_osoba' => '',
                'spk_onama_kontakt_telefon' => '',
                'spk_onama_kontakt_email' => '',
            ],
        ],
        
        // Bez highlight boxa
        'no_highlight' => [
            'acf_fields' => [
                'spk_onama_highlight_naslov' => '',
                'spk_onama_highlight_tekst' => '',
            ],
        ],
        
        // Samo glavna kolona (bez desne)
        'single_column' => [
            'acf_fields' => [
                'spk_onama_desni_stupac' => '',
                'spk_onama_highlight_tekst' => '',
            ],
        ],
        
        // Bez naslov akcenta (slogana)
        'no_slogan' => [
            'acf_fields' => [
                'spk_onama_naslov_akcent' => '',
            ],
        ],
        
        // Alternativni naslov s drugim sloganom
        'alt_title' => [
            'acf_fields' => [
                'spk_onama_naslov' => 'Naša priča',
                'spk_onama_naslov_akcent' => 'Od početnika do prvaka',
            ],
        ],
        
        // Kratak slogan
        'short_slogan' => [
            'acf_fields' => [
                'spk_onama_naslov_akcent' => 'Plesna strast',
            ],
        ],
        
        // Dugi slogan
        'long_slogan' => [
            'acf_fields' => [
                'spk_onama_naslov_akcent' => 'Gdje strast za plesom postaje životni stil i put do uspjeha',
            ],
        ],
        
        // Dugi tekst u highlight boxu
        'long_highlight' => [
            'acf_fields' => [
                'spk_onama_highlight_tekst' => '<p>Sportsku grupu vodi naš najuspješniji hrvatski plesni par: <strong>Ksenija Pluščec Quesnoit & Nicolas Quesnoit</strong>, profesionalni prvaci Hrvatske, sa cijelim timom plesnih stručnjaka, pedagoga, psihologa, fizijatara, nutricionista, masera i ostalih stručnjaka koji brinu o svakom aspektu razvoja naših plesača.</p>',
            ],
        ],
        
        // Prazni podaci (testira empty state)
        'empty' => [
            'acf_fields' => [
                'spk_onama_naslov' => 'O nama',
                'spk_onama_naslov_akcent' => '',
                'spk_onama_ime_kluba' => 'Sportski Plesni Klub Zagreb',
                'spk_onama_logo' => '',
                'spk_onama_slika' => '',
                'spk_onama_lead' => '',
                'spk_onama_lijevi_stupac' => '',
                'spk_onama_desni_stupac' => '',
                'spk_onama_highlight_naslov' => '',
                'spk_onama_highlight_tekst' => '',
                'spk_onama_kontakt_osoba' => '',
                'spk_onama_kontakt_telefon' => '',
                'spk_onama_kontakt_email' => '',
            ],
        ],
        
        // Samo s lead tekstom i highlight-om
        'lead_and_highlight' => [
            'acf_fields' => [
                'spk_onama_lijevi_stupac' => '',
                'spk_onama_desni_stupac' => '',
                'spk_onama_kontakt_osoba' => '',
                'spk_onama_kontakt_telefon' => '',
                'spk_onama_kontakt_email' => '',
            ],
        ],
    ],
];
