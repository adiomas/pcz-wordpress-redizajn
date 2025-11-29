<?php
/**
 * pcz Test Environment - Bootstrap
 * 
 * Inicijalizira test okruženje. Učitaj ovaj fajl na početku svakog testa.
 * 
 * @package pcz_Test_Environment
 * @since 1.0.0
 */

// Sprječava višestruko učitavanje
if (defined('pcz_TEST_BOOTSTRAPPED')) {
    return;
}
define('pcz_TEST_BOOTSTRAPPED', true);

// =============================================================================
// LOAD CONFIGURATION
// =============================================================================

require_once __DIR__ . '/../config.php';

// =============================================================================
// LOAD CORE MODULES
// =============================================================================

require_once __DIR__ . '/wp-mock.php';
require_once __DIR__ . '/helpers.php';

// =============================================================================
// MOCK DATA LOADING
// =============================================================================

/**
 * Učitaj mock podatke za trenutnu komponentu
 *
 * @param array $mock_data Podaci iz mock-data.php
 * @return void
 */
function load_mock_data(array $mock_data): void {
    // ACF Fields
    if (isset($mock_data['acf_fields'])) {
        $GLOBALS['pcz_mock_data']['acf_fields'] = $mock_data['acf_fields'];
        $GLOBALS['pcz_mock_data']['acf_options'] = $mock_data['acf_fields'];
    }
    
    // ACF Options (ako je odvojeno)
    if (isset($mock_data['acf_options'])) {
        $GLOBALS['pcz_mock_data']['acf_options'] = array_merge(
            $GLOBALS['pcz_mock_data']['acf_options'] ?? [],
            $mock_data['acf_options']
        );
    }
    
    // Nav Menu
    if (isset($mock_data['nav_menu'])) {
        $GLOBALS['pcz_mock_data']['nav_menu'] = $mock_data['nav_menu'];
    }
    
    // Site Info
    if (isset($mock_data['site'])) {
        $GLOBALS['pcz_mock_data']['site'] = array_merge(
            $GLOBALS['pcz_site_config'] ?? [],
            $mock_data['site']
        );
    }
    
    // Theme Mods
    if (isset($mock_data['theme_mods'])) {
        $GLOBALS['pcz_mock_data']['theme_mods'] = $mock_data['theme_mods'];
    }
    
    // Attachments
    if (isset($mock_data['attachments'])) {
        $GLOBALS['pcz_mock_data']['attachments'] = $mock_data['attachments'];
    }
    
    // Page State
    if (isset($mock_data['page_state'])) {
        $GLOBALS['pcz_mock_data']['page_state'] = array_merge(
            $GLOBALS['pcz_mock_data']['page_state'] ?? [],
            $mock_data['page_state']
        );
    }
    
    // Posts
    if (isset($mock_data['posts'])) {
        $GLOBALS['pcz_mock_data']['posts'] = $mock_data['posts'];
    }
}

/**
 * Primijeni scenario na mock podatke
 *
 * @param string $scenario_name Ime scenarija
 * @param array $mock_data Originalni mock podaci s definiranim scenarijima
 * @return void
 */
function apply_scenario(string $scenario_name, array $mock_data): void {
    if (!isset($mock_data['scenarios'][$scenario_name])) {
        return;
    }
    
    $scenario = $mock_data['scenarios'][$scenario_name];
    
    // Merge scenario podatke preko postojećih
    foreach ($scenario as $key => $value) {
        if ($key === 'acf_fields') {
            $GLOBALS['pcz_mock_data']['acf_fields'] = array_merge(
                $GLOBALS['pcz_mock_data']['acf_fields'] ?? [],
                $value
            );
            $GLOBALS['pcz_mock_data']['acf_options'] = $GLOBALS['pcz_mock_data']['acf_fields'];
        } elseif ($key === 'nav_menu') {
            $GLOBALS['pcz_mock_data']['nav_menu'] = $value;
        } elseif ($key === 'site') {
            $GLOBALS['pcz_mock_data']['site'] = array_merge(
                $GLOBALS['pcz_mock_data']['site'] ?? [],
                $value
            );
        } elseif ($key === 'page_state') {
            $GLOBALS['pcz_mock_data']['page_state'] = array_merge(
                $GLOBALS['pcz_mock_data']['page_state'] ?? [],
                $value
            );
        }
    }
}

/**
 * Reset mock data to defaults
 *
 * @return void
 */
function reset_mock_data(): void {
    $GLOBALS['pcz_mock_data'] = [
        'acf_fields'   => [],
        'acf_options'  => [],
        'nav_menu'     => [],
        'site'         => $GLOBALS['pcz_site_config'] ?? [],
        'theme_mods'   => [],
        'attachments'  => [],
        'posts'        => [],
        'current_post' => null,
        'page_state'   => [
            'is_front_page' => true,
            'is_home'       => true,
            'is_single'     => false,
            'is_page'       => false,
            'is_archive'    => false,
            'is_admin'      => false,
        ],
    ];
    
    // Reset ACF repeater state
    $GLOBALS['pcz_acf_repeater_stack'] = [];
    $GLOBALS['pcz_acf_current_row'] = [];
}

// =============================================================================
// TEMPLATE DISCOVERY
// =============================================================================

/**
 * Pronađi sve dostupne template-e za testiranje
 *
 * @return array Lista template-a s njihovim statusom
 */
function discover_templates(): array {
    $templates = [];
    $template_dir = pcz_TEST_TEMPLATES;
    
    if (!is_dir($template_dir)) {
        return $templates;
    }
    
    $dirs = array_filter(glob($template_dir . '/*'), 'is_dir');
    
    foreach ($dirs as $dir) {
        $name = basename($dir);
        
        // Preskoči template placeholder
        if ($name === '_template') {
            continue;
        }
        
        // Provjeri ima li potrebne fajlove
        $has_test = file_exists($dir . '/test.php');
        $has_mock = file_exists($dir . '/mock-data.php');
        
        // Dohvati info iz globalne konfiguracije ako postoji
        $global_info = $GLOBALS['pcz_available_templates'][$name] ?? [];
        
        $templates[$name] = [
            'name'        => $global_info['name'] ?? ucfirst($name),
            'description' => $global_info['description'] ?? '',
            'status'      => ($has_test && $has_mock) ? 'ready' : 'incomplete',
            'has_test'    => $has_test,
            'has_mock'    => $has_mock,
            'path'        => $dir,
            'scenarios'   => get_template_scenarios($name),
        ];
    }
    
    return $templates;
}

/**
 * Dohvati scenarije za template
 *
 * @param string $template_name Ime template-a
 * @return array Lista scenarija
 */
function get_template_scenarios(string $template_name): array {
    $mock_file = pcz_TEST_TEMPLATES . '/' . $template_name . '/mock-data.php';
    
    if (!file_exists($mock_file)) {
        return ['default'];
    }
    
    $mock_data = require $mock_file;
    
    if (!isset($mock_data['scenarios'])) {
        return ['default'];
    }
    
    return array_keys($mock_data['scenarios']);
}

// =============================================================================
// UTILITY
// =============================================================================

/**
 * Generiraj URL za testiranje template-a
 *
 * @param string $template_name Ime template-a
 * @param string|null $scenario Ime scenarija (opcionalno)
 * @return string
 */
function get_test_url(string $template_name, ?string $scenario = null): string {
    $url = '/?template=' . urlencode($template_name);
    
    if ($scenario) {
        $url .= '&scenario=' . urlencode($scenario);
    }
    
    return $url;
}

/**
 * Dohvati direktan URL do originalnog asseta
 *
 * @param string $component Ime komponente (npr. 'header')
 * @param string $file Ime fajla (npr. 'mega-menu.css')
 * @return string
 */
function get_component_asset_url(string $component, string $file): string {
    return '/' . $component . '/' . $file;
}

/**
 * Debug ispis mock podataka
 *
 * @param bool $die Da li zaustaviti izvršavanje
 * @return void
 */
function debug_mock_data(bool $die = false): void {
    if (!pcz_TEST_DEBUG) {
        return;
    }
    
    echo '<pre style="background:#1e1e1e;color:#9cdcfe;padding:20px;font-size:12px;overflow:auto;max-height:500px;">';
    echo '<strong style="color:#dcdcaa;">Mock Data Debug:</strong>' . "\n\n";
    print_r($GLOBALS['pcz_mock_data']);
    echo '</pre>';
    
    if ($die) {
        die();
    }
}

