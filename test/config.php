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

