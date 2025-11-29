<?php
/**
 * pcz Test Environment - Helper Functions
 * 
 * Utility funkcije za test okruženje.
 * 
 * @package pcz_Test_Environment
 * @since 1.0.0
 */

// =============================================================================
// PATH HELPERS
// =============================================================================

/**
 * Dohvati putanju relativnu prema project root-u
 *
 * @param string $path Putanja
 * @return string
 */
function pcz_path(string $path = ''): string {
    return pcz_PROJECT_ROOT . '/' . ltrim($path, '/');
}

/**
 * Dohvati putanju prema test root-u
 *
 * @param string $path Putanja
 * @return string
 */
function pcz_test_path(string $path = ''): string {
    return pcz_TEST_ROOT . '/' . ltrim($path, '/');
}

/**
 * Dohvati URL prema test root-u
 *
 * @param string $path Putanja
 * @return string
 */
function pcz_test_url(string $path = ''): string {
    return pcz_TEST_BASE_URL . '/test/' . ltrim($path, '/');
}

// =============================================================================
// ASSET HELPERS
// =============================================================================

/**
 * Generiraj placeholder sliku URL
 *
 * @param int $width Širina
 * @param int $height Visina
 * @param string $text Tekst na slici
 * @param string $bg Boja pozadine
 * @param string $color Boja teksta
 * @return string
 */
function pcz_placeholder_image(int $width = 300, int $height = 200, string $text = '', string $bg = 'cccccc', string $color = '666666'): string {
    $text = $text ?: "{$width}x{$height}";
    return "https://via.placeholder.com/{$width}x{$height}/{$bg}/{$color}?text=" . urlencode($text);
}

/**
 * Dohvati placeholder logo SVG
 *
 * @return string SVG kod
 */
function pcz_placeholder_logo(): string {
    return '/test/assets/placeholder/logo.svg';
}

/**
 * Generiraj inline SVG placeholder za ikonu
 *
 * @param string $name Ime ikone
 * @param int $size Veličina
 * @return string
 */
function pcz_placeholder_icon(string $name = 'default', int $size = 24): string {
    $icons = [
        'default' => '<rect width="100%" height="100%" fill="#ccc"/><text x="50%" y="50%" fill="#666" text-anchor="middle" dy=".3em">?</text>',
        'menu'    => '<path fill="#333" d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>',
        'close'   => '<path fill="#333" d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>',
        'arrow'   => '<path fill="#333" d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>',
    ];
    
    $content = $icons[$name] ?? $icons['default'];
    
    return sprintf(
        '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 24 24">%s</svg>',
        $size,
        $size,
        $content
    );
}

// =============================================================================
// HTML HELPERS
// =============================================================================

/**
 * Generiraj CSS klase kao string
 *
 * @param array $classes Lista klasa
 * @return string
 */
function pcz_classes(array $classes): string {
    return implode(' ', array_filter($classes));
}

/**
 * Generiraj HTML atribute iz array-a
 *
 * @param array $attrs Atributi
 * @return string
 */
function pcz_attrs(array $attrs): string {
    $html = [];
    
    foreach ($attrs as $name => $value) {
        if (is_bool($value)) {
            if ($value) {
                $html[] = esc_attr($name);
            }
        } elseif ($value !== null) {
            $html[] = esc_attr($name) . '="' . esc_attr($value) . '"';
        }
    }
    
    return implode(' ', $html);
}

/**
 * Ispis debug informacija u HTML komentaru
 *
 * @param mixed $data Podaci za ispis
 * @param string $label Labela
 * @return void
 */
function pcz_debug_comment($data, string $label = 'Debug'): void {
    if (!pcz_TEST_DEBUG) {
        return;
    }
    
    echo "\n<!-- $label: ";
    if (is_array($data) || is_object($data)) {
        echo "\n" . print_r($data, true);
    } else {
        echo $data;
    }
    echo " -->\n";
}

// =============================================================================
// TEMPLATE HELPERS
// =============================================================================

/**
 * Uključi template s varijablama
 *
 * @param string $template_path Putanja do template-a
 * @param array $vars Varijable za template
 * @return void
 */
function pcz_include_template(string $template_path, array $vars = []): void {
    if (!file_exists($template_path)) {
        if (pcz_TEST_DEBUG) {
            echo "<!-- Template not found: {$template_path} -->";
        }
        return;
    }
    
    extract($vars, EXTR_SKIP);
    include $template_path;
}

/**
 * Dohvati sadržaj template-a kao string
 *
 * @param string $template_path Putanja do template-a
 * @param array $vars Varijable za template
 * @return string
 */
function pcz_get_template(string $template_path, array $vars = []): string {
    ob_start();
    pcz_include_template($template_path, $vars);
    return ob_get_clean();
}

// =============================================================================
// RESPONSIVE HELPERS
// =============================================================================

/**
 * Generiraj responsive breakpoint info
 *
 * @return array
 */
function pcz_get_breakpoints(): array {
    return [
        'mobile'  => ['width' => 375, 'height' => 667, 'label' => 'Mobile'],
        'tablet'  => ['width' => 768, 'height' => 1024, 'label' => 'Tablet'],
        'desktop' => ['width' => 1440, 'height' => 900, 'label' => 'Desktop'],
    ];
}

/**
 * Generiraj HTML za responsive preview toggle
 *
 * @return string
 */
function pcz_responsive_toggles(): string {
    $breakpoints = pcz_get_breakpoints();
    $html = '<div class="pcz-test-responsive">';
    
    foreach ($breakpoints as $key => $bp) {
        $html .= sprintf(
            '<button type="button" class="pcz-test-responsive__btn" data-width="%d" data-height="%d">%s</button>',
            $bp['width'],
            $bp['height'],
            $bp['label']
        );
    }
    
    $html .= '<button type="button" class="pcz-test-responsive__btn pcz-test-responsive__btn--active" data-width="100%" data-height="auto">Full</button>';
    $html .= '</div>';
    
    return $html;
}

// =============================================================================
// SCENARIO HELPERS
// =============================================================================

/**
 * Generiraj HTML za scenario selector
 *
 * @param string $current_template Trenutni template
 * @param string $current_scenario Trenutni scenario
 * @param array $scenarios Lista dostupnih scenarija
 * @return string
 */
function pcz_scenario_selector(string $current_template, string $current_scenario, array $scenarios): string {
    $html = '<div class="pcz-test-scenarios">';
    $html .= '<label>Scenario:</label>';
    $html .= '<select onchange="window.location.href=this.value">';
    
    foreach ($scenarios as $scenario) {
        $url = get_test_url($current_template, $scenario);
        $selected = ($scenario === $current_scenario) ? ' selected' : '';
        $label = ucfirst(str_replace(['_', '-'], ' ', $scenario));
        
        $html .= sprintf(
            '<option value="%s"%s>%s</option>',
            esc_attr($url),
            $selected,
            esc_html($label)
        );
    }
    
    $html .= '</select>';
    $html .= '</div>';
    
    return $html;
}

// =============================================================================
// DATA GENERATION HELPERS
// =============================================================================

/**
 * Generiraj lorem ipsum tekst
 *
 * @param int $words Broj riječi
 * @return string
 */
function pcz_lorem(int $words = 50): string {
    $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
    
    $all_words = explode(' ', $lorem);
    
    if ($words >= count($all_words)) {
        return $lorem;
    }
    
    return implode(' ', array_slice($all_words, 0, $words)) . '.';
}

/**
 * Generiraj random ime za testiranje
 *
 * @return string
 */
function pcz_random_name(): string {
    $first_names = ['Ivan', 'Marija', 'Ante', 'Ana', 'Josip', 'Ivana', 'Marko', 'Petra'];
    $last_names = ['Horvat', 'Kovač', 'Babić', 'Marić', 'Novak', 'Jurić', 'Vuković', 'Knežević'];
    
    return $first_names[array_rand($first_names)] . ' ' . $last_names[array_rand($last_names)];
}

/**
 * Generiraj random email
 *
 * @param string|null $name Ime za email
 * @return string
 */
function pcz_random_email(?string $name = null): string {
    $name = $name ?? pcz_random_name();
    $slug = strtolower(str_replace(' ', '.', $name));
    $domains = ['test.hr', 'example.com', 'mail.hr'];
    
    return $slug . '@' . $domains[array_rand($domains)];
}


