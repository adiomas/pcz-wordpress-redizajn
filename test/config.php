<?php
/**
 * pcz Test Environment - Global Configuration
 * 
 * Centralizirana konfiguracija za test okruženje.
 * Prilagodi vrijednosti prema potrebi.
 * 
 * @package pcz_Test_Environment
 * @since 1.0.0
 */

// Sprječava direktan pristup
if (!defined('pcz_TEST_ENVIRONMENT')) {
    define('pcz_TEST_ENVIRONMENT', true);
}

// =============================================================================
// PATHS
// =============================================================================

// Root putanje
define('pcz_TEST_ROOT', __DIR__);
define('pcz_PROJECT_ROOT', dirname(__DIR__));

// Test environment putanje
define('pcz_TEST_CORE', pcz_TEST_ROOT . '/core');
define('pcz_TEST_TEMPLATES', pcz_TEST_ROOT . '/templates');
define('pcz_TEST_ASSETS', pcz_TEST_ROOT . '/assets');

// Component putanje (originalni template-i)
define('pcz_HEADER_PATH', pcz_PROJECT_ROOT . '/header');
define('pcz_FOOTER_PATH', pcz_PROJECT_ROOT . '/footer');
define('pcz_POZNATI_PATH', pcz_PROJECT_ROOT . '/poznati');
define('pcz_SIDEBAR_PATH', pcz_PROJECT_ROOT . '/sidebar');
define('pcz_HERO_PATH', pcz_PROJECT_ROOT . '/hero');
define('pcz_BRAND_PATH', pcz_PROJECT_ROOT . '/brand');
define('pcz_PRIJAVA_PATH', pcz_PROJECT_ROOT . '/prijava');
define('pcz_IZDVOJENO_PATH', pcz_PROJECT_ROOT . '/izdvojeno');
define('pcz_UPOZNAJTE_PATH', pcz_PROJECT_ROOT . '/upoznajte');
define('pcz_SPORTSKI_KLUB_PATH', pcz_PROJECT_ROOT . '/sportski-klub');
define('pcz_O_NAMA_SPK_PATH', pcz_PROJECT_ROOT . '/o-nama-spk');

// =============================================================================
// URLS
// =============================================================================

// Automatski detektiraj base URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost:8080';
$base_path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

define('pcz_TEST_BASE_URL', $protocol . '://' . $host);
define('pcz_SITE_URL', $protocol . '://' . $host);

// =============================================================================
// MOCK WORDPRESS CONSTANTS
// =============================================================================

// WordPress core constants koje template-i koriste
if (!defined('ABSPATH')) {
    define('ABSPATH', pcz_PROJECT_ROOT . '/');
}

if (!defined('WP_DEBUG')) {
    define('WP_DEBUG', true);
}

if (!defined('TEMPLATEPATH')) {
    define('TEMPLATEPATH', pcz_PROJECT_ROOT);
}

if (!defined('STYLESHEETPATH')) {
    define('STYLESHEETPATH', pcz_PROJECT_ROOT);
}

// =============================================================================
// SITE CONFIGURATION
// =============================================================================

// Osnovne site informacije za mock
$GLOBALS['pcz_site_config'] = [
    'name'        => 'Plesni Centar Zagreb',
    'description' => 'Najbolja plesna škola u Zagrebu',
    'url'         => pcz_SITE_URL,
    'admin_email' => 'info@plesnicentar.hr',
    'language'    => 'hr',
    'charset'     => 'UTF-8',
    'version'     => '6.4.0',
];

// =============================================================================
// AVAILABLE TEMPLATES
// =============================================================================

// Registriraj dostupne template-e za testiranje
$GLOBALS['pcz_available_templates'] = [
    'header' => [
        'name'        => 'Header / Mega Menu',
        'description' => 'Glavni header s logom, navigacijom i mega menu dropdown-om',
        'status'      => 'ready',
        'path'        => pcz_HEADER_PATH,
        'test_path'   => pcz_TEST_TEMPLATES . '/header',
        'scenarios'   => ['default', 'no_logo', 'empty_menu', 'minimal'],
    ],
    'footer' => [
        'name'        => 'Footer',
        'description' => 'Footer s kontakt informacijama, social linkovima i copyright-om',
        'status'      => 'ready',
        'path'        => pcz_FOOTER_PATH,
        'test_path'   => pcz_TEST_TEMPLATES . '/footer',
        'scenarios'   => ['default', 'no_social', 'minimal', 'no_logo', 'footer_only', 'large_logo', 'small_logo'],
    ],
    'sidebar' => [
        'name'        => 'Sidebar',
        'description' => 'Bočna traka s widgetima',
        'status'      => 'planned',
        'path'        => pcz_SIDEBAR_PATH,
        'test_path'   => pcz_TEST_TEMPLATES . '/sidebar',
        'scenarios'   => ['default'],
    ],
    'poznati' => [
        'name'        => 'Poznati o PCZ-u',
        'description' => 'Testimonijali poznatih osoba - kružne slike, citati, mobile slider',
        'status'      => 'ready',
        'path'        => pcz_PROJECT_ROOT . '/poznati',
        'test_path'   => pcz_TEST_TEMPLATES . '/poznati',
        'scenarios'   => ['default', 'two_items', 'many_items', 'single', 'long_quotes'],
    ],
    'hero' => [
        'name'        => 'Hero Sekcija',
        'description' => 'Fullwidth hero s pozadinskom slikom, naslovom, CTA gumbom i intro sekcijom',
        'status'      => 'ready',
        'path'        => pcz_HERO_PATH,
        'test_path'   => pcz_TEST_TEMPLATES . '/hero',
        'scenarios'   => ['default', 'no_intro', 'no_social', 'minimal', 'short_title', 'long_title', 'all_social', 'placeholder_bg', 'dark_bg'],
    ],
    'brand' => [
        'name'        => 'Brand System',
        'description' => 'Multi-brand sustav s switcherom, header toggle-om i CSS varijablama',
        'status'      => 'ready',
        'path'        => pcz_PROJECT_ROOT . '/brand',
        'test_path'   => pcz_TEST_TEMPLATES . '/brand',
        'scenarios'   => ['default', 'sportski_klub', 'switcher_pills', 'switcher_buttons', 'brand_switch'],
    ],
    'prijava' => [
        'name'        => 'Prijava Forma',
        'description' => 'Sekcija s formom za prijavu na tečajeve/treninge - brand-aware s Gravity Forms integracijom',
        'status'      => 'ready',
        'path'        => pcz_PROJECT_ROOT . '/prijava',
        'test_path'   => pcz_TEST_TEMPLATES . '/prijava',
        'scenarios'   => ['default', 'sportski_klub', 'gradient_bg', 'custom_title', 'no_subtitle', 'spk_custom', 'no_form'],
    ],
    'izdvojeno' => [
        'name'        => 'Izdvojeno iz ponude',
        'description' => 'Sekcija s karticama kategorija tečajeva - samo za Plesnu školu',
        'status'      => 'ready',
        'path'        => pcz_PROJECT_ROOT . '/izdvojeno',
        'test_path'   => pcz_TEST_TEMPLATES . '/izdvojeno',
        'scenarios'   => ['default', 'four_cards', 'two_cards', 'no_cta', 'long_titles'],
    ],
    'upoznajte' => [
        'name'        => 'Upoznajte nas',
        'description' => 'Sekcija za upoznavanje s plesnom školom - vlasnici, misija, vizija. Samo za Plesnu školu.',
        'status'      => 'ready',
        'path'        => pcz_PROJECT_ROOT . '/upoznajte',
        'test_path'   => pcz_TEST_TEMPLATES . '/upoznajte',
        'scenarios'   => ['default', 'no_image', 'short_text', 'long_text', 'no_highlight', 'alt_slogan', 'minimal', 'placeholder_image', 'empty'],
    ],
    'sportski-klub' => [
        'name'        => 'Sportski Klub',
        'description' => 'Sekcija za predstavljanje Sportskog kluba - naslov, opis i navigacijski linkovi. Samo za Sportski Klub brand.',
        'status'      => 'ready',
        'path'        => pcz_PROJECT_ROOT . '/sportski-klub',
        'test_path'   => pcz_TEST_TEMPLATES . '/sportski-klub',
        'scenarios'   => ['default', 'minimal', 'no_links', 'short_description', 'long_description', 'many_links', 'two_links', 'alt_title', 'empty'],
    ],
    'dodatne-info' => [
        'name'        => 'Dodatne Informacije',
        'description' => 'Grid ikona s linkovima na dodatne informacije - cjenik, kontakt, lokacije, rezultati, kampovi, FAQ, privatna podrška. Samo za Sportski Klub brand.',
        'status'      => 'ready',
        'path'        => pcz_PROJECT_ROOT . '/dodatne-info',
        'test_path'   => pcz_TEST_TEMPLATES . '/dodatne-info',
        'scenarios'   => ['default', 'minimal', 'many_items', 'long_labels', 'two_items', 'alt_title', 'empty', 'custom_icons'],
    ],
    'o-nama-spk' => [
        'name'        => 'O Nama - Sportski Klub',
        'description' => 'Detaljni About Us za Sportski Plesni Klub Zagreb - logo, slika, tekst, bullet lista, kontakt info i linkovi na stranice. Samo za Sportski Klub brand.',
        'status'      => 'ready',
        'path'        => pcz_PROJECT_ROOT . '/o-nama-spk',
        'test_path'   => pcz_TEST_TEMPLATES . '/o-nama-spk',
        'scenarios'   => ['default', 'minimal', 'no_image', 'no_logo', 'no_contact', 'short_text', 'long_text', 'alt_title', 'empty'],
    ],
];

// =============================================================================
// DEBUG MODE
// =============================================================================

// Omogući debug poruke
define('pcz_TEST_DEBUG', true);

// Error reporting
if (pcz_TEST_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

// =============================================================================
// HELPER: Get Config Value
// =============================================================================

/**
 * Dohvati konfiguracijsku vrijednost
 *
 * @param string $key Ključ konfiguracije (npr. 'site.name')
 * @param mixed $default Defaultna vrijednost
 * @return mixed
 */
function pcz_config($key, $default = null) {
    $parts = explode('.', $key);
    $value = $GLOBALS['pcz_site_config'] ?? [];
    
    foreach ($parts as $part) {
        if (is_array($value) && isset($value[$part])) {
            $value = $value[$part];
        } else {
            return $default;
        }
    }
    
    return $value;
}

/**
 * Dohvati informacije o template-u
 *
 * @param string $template_id ID template-a (npr. 'header')
 * @return array|null
 */
function pcz_get_template_info($template_id) {
    return $GLOBALS['pcz_available_templates'][$template_id] ?? null;
}

/**
 * Provjeri je li template dostupan
 *
 * @param string $template_id ID template-a
 * @return bool
 */
function pcz_is_template_ready($template_id) {
    $info = pcz_get_template_info($template_id);
    return $info && $info['status'] === 'ready';
}

