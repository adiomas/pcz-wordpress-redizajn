<?php
/**
 * pcz Test Environment - WordPress Mock Functions
 * 
 * Simulira WordPress funkcije za lokalno testiranje.
 * Sve funkcije su "drop-in" zamjene s istim API-jem kao WordPress.
 * 
 * @package pcz_Test_Environment
 * @since 1.0.0
 */

// Sprječava višestruko učitavanje
if (defined('pcz_WP_MOCK_LOADED')) {
    return;
}
define('pcz_WP_MOCK_LOADED', true);

// =============================================================================
// GLOBAL STATE
// =============================================================================

// Globalno spremište za mock podatke
$GLOBALS['pcz_mock_data'] = [
    'acf_fields'   => [],
    'acf_options'  => [],
    'nav_menu'     => [],
    'site'         => [],
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

// ACF Repeater state
$GLOBALS['pcz_acf_repeater_stack'] = [];
$GLOBALS['pcz_acf_current_row'] = [];

// =============================================================================
// ACF FUNCTIONS
// =============================================================================

if (!function_exists('get_field')) {
    /**
     * Get ACF field value
     *
     * @param string $field_name Ime polja
     * @param mixed $post_id Post ID ili 'option' za options page
     * @return mixed
     */
    function get_field($field_name, $post_id = false) {
        $mock = $GLOBALS['pcz_mock_data'] ?? [];
        
        // Za options page
        if ($post_id === 'option' || $post_id === 'options') {
            return $mock['acf_options'][$field_name] ?? $mock['acf_fields'][$field_name] ?? null;
        }
        
        // Za specifični post
        if ($post_id && isset($mock['posts'][$post_id]['fields'][$field_name])) {
            return $mock['posts'][$post_id]['fields'][$field_name];
        }
        
        // Za trenutni post ili globalno
        return $mock['acf_fields'][$field_name] ?? null;
    }
}

if (!function_exists('get_fields')) {
    /**
     * Get all ACF fields
     *
     * @param mixed $post_id Post ID ili 'option'
     * @return array
     */
    function get_fields($post_id = false) {
        $mock = $GLOBALS['pcz_mock_data'] ?? [];
        
        if ($post_id === 'option' || $post_id === 'options') {
            return array_merge($mock['acf_options'] ?? [], $mock['acf_fields'] ?? []);
        }
        
        if ($post_id && isset($mock['posts'][$post_id]['fields'])) {
            return $mock['posts'][$post_id]['fields'];
        }
        
        return $mock['acf_fields'] ?? [];
    }
}

if (!function_exists('have_rows')) {
    /**
     * Check if ACF repeater has rows
     *
     * @param string $field_name Ime repeater polja
     * @param mixed $post_id Post ID ili 'option'
     * @return bool
     */
    function have_rows($field_name, $post_id = false) {
        $value = get_field($field_name, $post_id);
        
        if (!is_array($value) || empty($value)) {
            return false;
        }
        
        // Inicijaliziraj repeater stack
        $stack_key = $post_id . '_' . $field_name;
        
        if (!isset($GLOBALS['pcz_acf_repeater_stack'][$stack_key])) {
            $GLOBALS['pcz_acf_repeater_stack'][$stack_key] = [
                'data'  => $value,
                'index' => -1,
                'count' => count($value),
            ];
        }
        
        // Provjeri ima li još redova
        $stack = &$GLOBALS['pcz_acf_repeater_stack'][$stack_key];
        
        if ($stack['index'] + 1 < $stack['count']) {
            return true;
        }
        
        // Resetiraj za sljedeće korištenje
        unset($GLOBALS['pcz_acf_repeater_stack'][$stack_key]);
        return false;
    }
}

if (!function_exists('the_row')) {
    /**
     * Iterate to next ACF repeater row
     *
     * @return void
     */
    function the_row() {
        if (empty($GLOBALS['pcz_acf_repeater_stack'])) {
            return;
        }
        
        // Dohvati zadnji repeater
        $stack_key = array_key_last($GLOBALS['pcz_acf_repeater_stack']);
        $stack = &$GLOBALS['pcz_acf_repeater_stack'][$stack_key];
        
        $stack['index']++;
        
        if (isset($stack['data'][$stack['index']])) {
            $GLOBALS['pcz_acf_current_row'][$stack_key] = $stack['data'][$stack['index']];
        }
    }
}

if (!function_exists('get_sub_field')) {
    /**
     * Get ACF sub-field value in repeater
     *
     * @param string $field_name Ime sub-polja
     * @return mixed
     */
    function get_sub_field($field_name) {
        if (empty($GLOBALS['pcz_acf_repeater_stack'])) {
            return null;
        }
        
        $stack_key = array_key_last($GLOBALS['pcz_acf_repeater_stack']);
        $current_row = $GLOBALS['pcz_acf_current_row'][$stack_key] ?? [];
        
        return $current_row[$field_name] ?? null;
    }
}

if (!function_exists('the_sub_field')) {
    /**
     * Echo ACF sub-field value
     *
     * @param string $field_name Ime sub-polja
     * @return void
     */
    function the_sub_field($field_name) {
        echo get_sub_field($field_name);
    }
}

if (!function_exists('get_row_index')) {
    /**
     * Get current ACF repeater row index (1-based)
     *
     * @return int
     */
    function get_row_index() {
        if (empty($GLOBALS['pcz_acf_repeater_stack'])) {
            return 0;
        }
        
        $stack_key = array_key_last($GLOBALS['pcz_acf_repeater_stack']);
        return ($GLOBALS['pcz_acf_repeater_stack'][$stack_key]['index'] ?? -1) + 1;
    }
}

// =============================================================================
// URL / PATH FUNCTIONS
// =============================================================================

if (!function_exists('home_url')) {
    /**
     * Get home URL
     *
     * @param string $path Path to append
     * @return string
     */
    function home_url($path = '/') {
        $url = $GLOBALS['pcz_mock_data']['site']['url'] ?? pcz_SITE_URL;
        return rtrim($url, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('site_url')) {
    /**
     * Get site URL
     *
     * @param string $path Path to append
     * @return string
     */
    function site_url($path = '/') {
        return home_url($path);
    }
}

if (!function_exists('admin_url')) {
    /**
     * Get admin URL
     *
     * @param string $path Path to append
     * @return string
     */
    function admin_url($path = '') {
        return home_url('wp-admin/' . ltrim($path, '/'));
    }
}

if (!function_exists('get_permalink')) {
    /**
     * Get post permalink
     *
     * @param int|null $post_id Post ID
     * @return string
     */
    function get_permalink($post_id = null) {
        if ($post_id && isset($GLOBALS['pcz_mock_data']['posts'][$post_id]['url'])) {
            return $GLOBALS['pcz_mock_data']['posts'][$post_id]['url'];
        }
        return home_url('/');
    }
}

if (!function_exists('get_template_directory_uri')) {
    /**
     * Get template directory URI
     *
     * @return string
     */
    function get_template_directory_uri() {
        return pcz_SITE_URL;
    }
}

if (!function_exists('get_stylesheet_directory_uri')) {
    /**
     * Get stylesheet directory URI
     *
     * @return string
     */
    function get_stylesheet_directory_uri() {
        return pcz_SITE_URL;
    }
}

if (!function_exists('get_template_directory')) {
    /**
     * Get template directory path
     *
     * @return string
     */
    function get_template_directory() {
        return pcz_PROJECT_ROOT;
    }
}

if (!function_exists('get_stylesheet_directory')) {
    /**
     * Get stylesheet directory path
     *
     * @return string
     */
    function get_stylesheet_directory() {
        return pcz_PROJECT_ROOT;
    }
}

// =============================================================================
// SITE INFO FUNCTIONS
// =============================================================================

if (!function_exists('get_bloginfo')) {
    /**
     * Get blog/site info
     *
     * @param string $show What to show
     * @return string
     */
    function get_bloginfo($show) {
        $site = $GLOBALS['pcz_mock_data']['site'] ?? [];
        $config = $GLOBALS['pcz_site_config'] ?? [];
        
        switch ($show) {
            case 'name':
                return $site['name'] ?? $config['name'] ?? 'Test Site';
            case 'description':
            case 'blogdescription':
                return $site['description'] ?? $config['description'] ?? '';
            case 'url':
            case 'home':
            case 'siteurl':
                return $site['url'] ?? $config['url'] ?? pcz_SITE_URL;
            case 'admin_email':
                return $site['admin_email'] ?? $config['admin_email'] ?? 'test@example.com';
            case 'charset':
                return $config['charset'] ?? 'UTF-8';
            case 'language':
                return $config['language'] ?? 'hr';
            case 'version':
                return $config['version'] ?? '6.4.0';
            case 'template_directory':
                return get_template_directory_uri();
            case 'stylesheet_directory':
                return get_stylesheet_directory_uri();
            default:
                return '';
        }
    }
}

if (!function_exists('get_theme_mod')) {
    /**
     * Get theme modification value
     *
     * @param string $name Mod name
     * @param mixed $default Default value
     * @return mixed
     */
    function get_theme_mod($name, $default = false) {
        $mods = $GLOBALS['pcz_mock_data']['theme_mods'] ?? [];
        return $mods[$name] ?? $default;
    }
}

// =============================================================================
// ATTACHMENT / MEDIA FUNCTIONS
// =============================================================================

if (!function_exists('wp_get_attachment_image_url')) {
    /**
     * Get attachment image URL
     *
     * @param int $attachment_id Attachment ID
     * @param string $size Image size
     * @return string|false
     */
    function wp_get_attachment_image_url($attachment_id, $size = 'thumbnail') {
        $attachments = $GLOBALS['pcz_mock_data']['attachments'] ?? [];
        
        if (isset($attachments[$attachment_id])) {
            $att = $attachments[$attachment_id];
            
            // Ako ima više veličina
            if (isset($att['sizes'][$size])) {
                return $att['sizes'][$size];
            }
            
            // Vrati default URL
            return $att['url'] ?? false;
        }
        
        return false;
    }
}

if (!function_exists('wp_get_attachment_image')) {
    /**
     * Get attachment image HTML
     *
     * @param int $attachment_id Attachment ID
     * @param string $size Image size
     * @param bool $icon Use icon
     * @param array $attr Additional attributes
     * @return string
     */
    function wp_get_attachment_image($attachment_id, $size = 'thumbnail', $icon = false, $attr = []) {
        $url = wp_get_attachment_image_url($attachment_id, $size);
        
        if (!$url) {
            return '';
        }
        
        $attachments = $GLOBALS['pcz_mock_data']['attachments'] ?? [];
        $att = $attachments[$attachment_id] ?? [];
        
        $default_attr = [
            'src'   => $url,
            'class' => "attachment-$size size-$size",
            'alt'   => $att['alt'] ?? '',
        ];
        
        $attr = array_merge($default_attr, $attr);
        
        $html = '<img';
        foreach ($attr as $name => $value) {
            $html .= ' ' . esc_attr($name) . '="' . esc_attr($value) . '"';
        }
        $html .= '>';
        
        return $html;
    }
}

// =============================================================================
// MENU FUNCTIONS
// =============================================================================

if (!function_exists('get_nav_menu_locations')) {
    /**
     * Get registered menu locations
     *
     * @return array
     */
    function get_nav_menu_locations() {
        $menus = $GLOBALS['pcz_mock_data']['nav_menu'] ?? [];
        
        // Ako ima menu, vrati mock lokacije
        if (!empty($menus)) {
            return [
                'main-menu' => 1,
                'primary'   => 1,
                'footer'    => 2,
            ];
        }
        
        return [];
    }
}

if (!function_exists('wp_get_nav_menu_object')) {
    /**
     * Get menu object
     *
     * @param mixed $menu Menu ID, slug, ili name
     * @return object|false
     */
    function wp_get_nav_menu_object($menu) {
        if (!$menu) {
            return false;
        }
        
        return (object) [
            'term_id' => is_numeric($menu) ? $menu : 1,
            'name'    => 'Main Menu',
            'slug'    => 'main-menu',
        ];
    }
}

if (!function_exists('wp_get_nav_menu_items')) {
    /**
     * Get menu items
     *
     * @param int $menu_id Menu ID
     * @return array|false
     */
    function wp_get_nav_menu_items($menu_id) {
        $menus = $GLOBALS['pcz_mock_data']['nav_menu'] ?? [];
        
        if (empty($menus)) {
            return false;
        }
        
        $items = [];
        $id = 1;
        
        foreach ($menus as $item) {
            $items[] = (object) [
                'ID'               => $id,
                'title'            => $item['title'] ?? 'Menu Item',
                'url'              => $item['url'] ?? '#',
                'menu_item_parent' => $item['parent'] ?? 0,
                'classes'          => $item['classes'] ?? [],
                'target'           => $item['target'] ?? '',
                'attr_title'       => $item['attr_title'] ?? '',
                'description'      => $item['description'] ?? '',
                'object'           => $item['object'] ?? 'page',
                'object_id'        => $item['object_id'] ?? $id,
                'type'             => $item['type'] ?? 'post_type',
                // Custom properties za naš kod (backward compatibility)
                'is_ponuda'        => $item['is_ponuda'] ?? ($item['has_dropdown'] ?? false),
                'has_dropdown'     => $item['has_dropdown'] ?? ($item['is_ponuda'] ?? false),
                'dropdown_field'   => $item['dropdown_field'] ?? null,
            ];
            $id++;
        }
        
        return $items;
    }
}

if (!function_exists('has_nav_menu')) {
    /**
     * Check if menu location has menu assigned
     *
     * @param string $location Menu location
     * @return bool
     */
    function has_nav_menu($location) {
        $locations = get_nav_menu_locations();
        return isset($locations[$location]) && $locations[$location] > 0;
    }
}

// =============================================================================
// CONDITIONAL TAGS
// =============================================================================

if (!function_exists('is_front_page')) {
    function is_front_page() {
        return $GLOBALS['pcz_mock_data']['page_state']['is_front_page'] ?? true;
    }
}

if (!function_exists('is_home')) {
    function is_home() {
        return $GLOBALS['pcz_mock_data']['page_state']['is_home'] ?? true;
    }
}

if (!function_exists('is_single')) {
    function is_single($post = '') {
        return $GLOBALS['pcz_mock_data']['page_state']['is_single'] ?? false;
    }
}

if (!function_exists('is_page')) {
    function is_page($page = '') {
        return $GLOBALS['pcz_mock_data']['page_state']['is_page'] ?? false;
    }
}

if (!function_exists('is_archive')) {
    function is_archive() {
        return $GLOBALS['pcz_mock_data']['page_state']['is_archive'] ?? false;
    }
}

if (!function_exists('is_admin')) {
    function is_admin() {
        return $GLOBALS['pcz_mock_data']['page_state']['is_admin'] ?? false;
    }
}

if (!function_exists('is_user_logged_in')) {
    function is_user_logged_in() {
        return $GLOBALS['pcz_mock_data']['page_state']['is_logged_in'] ?? false;
    }
}

// =============================================================================
// ESCAPING FUNCTIONS (Critical for security!)
// =============================================================================

if (!function_exists('esc_url')) {
    /**
     * Escape URL
     *
     * @param string $url URL to escape
     * @param array $protocols Allowed protocols
     * @return string
     */
    function esc_url($url, $protocols = null) {
        if (empty($url)) {
            return '';
        }
        
        $url = trim($url);
        
        // Provjeri protocol
        if (strpos($url, '//') === 0) {
            $url = 'https:' . $url;
        }
        
        // Filter dangerous characters
        $url = str_replace([' ', '"', "'", '<', '>', "\n", "\r"], '', $url);
        
        return htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('esc_html')) {
    /**
     * Escape HTML
     *
     * @param string $text Text to escape
     * @return string
     */
    function esc_html($text) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('esc_attr')) {
    /**
     * Escape attribute
     *
     * @param string $text Text to escape
     * @return string
     */
    function esc_attr($text) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('esc_js')) {
    /**
     * Escape for JS
     *
     * @param string $text Text to escape
     * @return string
     */
    function esc_js($text) {
        return addslashes($text);
    }
}

if (!function_exists('esc_textarea')) {
    /**
     * Escape textarea content
     *
     * @param string $text Text to escape
     * @return string
     */
    function esc_textarea($text) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('wp_kses_post')) {
    /**
     * Sanitize content for allowed HTML tags for post content
     *
     * @param string $content Content to sanitize
     * @return string
     */
    function wp_kses_post($content) {
        // Pojednostavljena verzija - dozvoli osnovne HTML tagove
        $allowed = '<a><br><p><strong><b><em><i><ul><ol><li><h1><h2><h3><h4><h5><h6><img><span><div>';
        return strip_tags($content, $allowed);
    }
}

// =============================================================================
// UTILITY FUNCTIONS
// =============================================================================

if (!function_exists('add_query_arg')) {
    /**
     * Add query arguments to URL
     *
     * @param array|string $args Arguments to add
     * @param string $url URL (optional, uses current if not provided)
     * @return string
     */
    function add_query_arg($args, $url = '') {
        if (empty($url)) {
            $url = $_SERVER['REQUEST_URI'] ?? '/';
        }
        
        // Parse existing URL
        $url_parts = parse_url($url);
        $base_url = ($url_parts['scheme'] ?? '') . 
                    (isset($url_parts['host']) ? '://' . $url_parts['host'] : '') .
                    ($url_parts['path'] ?? '/');
        
        // Parse existing query string
        $existing_query = [];
        if (isset($url_parts['query'])) {
            parse_str($url_parts['query'], $existing_query);
        }
        
        // Merge with new args
        if (is_array($args)) {
            $merged = array_merge($existing_query, $args);
        } else {
            $merged = $existing_query;
        }
        
        // Build final URL
        $query_string = http_build_query($merged);
        if (!empty($query_string)) {
            return $base_url . '?' . $query_string;
        }
        
        return $base_url;
    }
}

if (!function_exists('remove_query_arg')) {
    /**
     * Remove query arguments from URL
     *
     * @param string|array $key Query key(s) to remove
     * @param string $url URL to modify (optional)
     * @return string
     */
    function remove_query_arg($key, $url = '') {
        if (empty($url)) {
            $url = $_SERVER['REQUEST_URI'] ?? '/';
        }
        
        // Parse URL
        $url_parts = parse_url($url);
        $base_url = ($url_parts['scheme'] ?? '') . 
                    (isset($url_parts['host']) ? '://' . $url_parts['host'] : '') .
                    ($url_parts['path'] ?? '/');
        
        // Parse existing query string
        $query = [];
        if (isset($url_parts['query'])) {
            parse_str($url_parts['query'], $query);
        }
        
        // Remove specified keys
        $keys = is_array($key) ? $key : [$key];
        foreach ($keys as $k) {
            unset($query[$k]);
        }
        
        // Rebuild URL
        if (!empty($query)) {
            return $base_url . '?' . http_build_query($query);
        }
        
        return $base_url;
    }
}

if (!function_exists('trailingslashit')) {
    /**
     * Add trailing slash to path
     *
     * @param string $string Path
     * @return string
     */
    function trailingslashit($string) {
        return rtrim($string, '/\\') . '/';
    }
}

if (!function_exists('untrailingslashit')) {
    /**
     * Remove trailing slash from path
     *
     * @param string $string Path
     * @return string
     */
    function untrailingslashit($string) {
        return rtrim($string, '/\\');
    }
}

if (!function_exists('current_user_can')) {
    /**
     * Check if current user has capability
     *
     * @param string $capability Capability to check
     * @return bool
     */
    function current_user_can($capability) {
        // Za testiranje, uvijek vraćamo true za administratorske sposobnosti
        $admin_caps = ['manage_options', 'edit_posts', 'publish_posts'];
        return in_array($capability, $admin_caps);
    }
}

if (!function_exists('shortcode_exists')) {
    /**
     * Check if shortcode exists
     *
     * @param string $tag Shortcode tag
     * @return bool
     */
    function shortcode_exists($tag) {
        return false;
    }
}

if (!function_exists('wp_enqueue_style')) {
    function wp_enqueue_style($handle, $src = '', $deps = [], $ver = false, $media = 'all') {
        // Stub - ne radi ništa u test okruženju
    }
}

if (!function_exists('wp_enqueue_script')) {
    function wp_enqueue_script($handle, $src = '', $deps = [], $ver = false, $in_footer = false) {
        // Stub - ne radi ništa u test okruženju
    }
}

// =============================================================================
// HOOKS SYSTEM (Stub Implementation)
// =============================================================================

$GLOBALS['pcz_actions'] = [];
$GLOBALS['pcz_filters'] = [];

if (!function_exists('add_action')) {
    /**
     * Add action hook
     *
     * @param string $tag Action tag
     * @param callable $callback Callback function
     * @param int $priority Priority
     * @param int $accepted_args Number of accepted arguments
     * @return true
     */
    function add_action($tag, $callback, $priority = 10, $accepted_args = 1) {
        $GLOBALS['pcz_actions'][$tag][$priority][] = [
            'callback'      => $callback,
            'accepted_args' => $accepted_args,
        ];
        return true;
    }
}

if (!function_exists('add_filter')) {
    /**
     * Add filter hook
     *
     * @param string $tag Filter tag
     * @param callable $callback Callback function
     * @param int $priority Priority
     * @param int $accepted_args Number of accepted arguments
     * @return true
     */
    function add_filter($tag, $callback, $priority = 10, $accepted_args = 1) {
        $GLOBALS['pcz_filters'][$tag][$priority][] = [
            'callback'      => $callback,
            'accepted_args' => $accepted_args,
        ];
        return true;
    }
}

if (!function_exists('do_action')) {
    /**
     * Execute action hooks
     *
     * @param string $tag Action tag
     * @param mixed ...$args Arguments to pass
     * @return void
     */
    function do_action($tag, ...$args) {
        if (!isset($GLOBALS['pcz_actions'][$tag])) {
            return;
        }
        
        ksort($GLOBALS['pcz_actions'][$tag]);
        
        foreach ($GLOBALS['pcz_actions'][$tag] as $priority => $callbacks) {
            foreach ($callbacks as $callback_data) {
                $callback = $callback_data['callback'];
                $accepted_args = $callback_data['accepted_args'];
                
                call_user_func_array($callback, array_slice($args, 0, $accepted_args));
            }
        }
    }
}

if (!function_exists('apply_filters')) {
    /**
     * Apply filter hooks
     *
     * @param string $tag Filter tag
     * @param mixed $value Value to filter
     * @param mixed ...$args Additional arguments
     * @return mixed
     */
    function apply_filters($tag, $value, ...$args) {
        if (!isset($GLOBALS['pcz_filters'][$tag])) {
            return $value;
        }
        
        ksort($GLOBALS['pcz_filters'][$tag]);
        
        foreach ($GLOBALS['pcz_filters'][$tag] as $priority => $callbacks) {
            foreach ($callbacks as $callback_data) {
                $callback = $callback_data['callback'];
                $accepted_args = $callback_data['accepted_args'];
                
                $all_args = array_merge([$value], $args);
                $value = call_user_func_array($callback, array_slice($all_args, 0, $accepted_args));
            }
        }
        
        return $value;
    }
}

// =============================================================================
// WORDPRESS GLOBALS SIMULATION
// =============================================================================

// Simuliraj $wp global objekt
$GLOBALS['wp'] = new stdClass();
$GLOBALS['wp']->request = '';

// Simuliraj $wpdb
$GLOBALS['wpdb'] = new stdClass();
$GLOBALS['wpdb']->prefix = 'wp_';

// Simuliraj $post
$GLOBALS['post'] = new stdClass();
$GLOBALS['post']->ID = 1;
$GLOBALS['post']->post_title = 'Test Page';
$GLOBALS['post']->post_content = '';
$GLOBALS['post']->post_type = 'page';

// =============================================================================
// HEADER HELPER FUNCTIONS (Mock Implementation)
// =============================================================================

if (!function_exists('pcz_get_dropdown_field_name')) {
    /**
     * Mock implementation of pcz_get_dropdown_field_name
     * 
     * @param string $menu_item_title
     * @param string $menu_item_url
     * @return string|null
     */
    function pcz_get_dropdown_field_name($menu_item_title, $menu_item_url) {
        // Provjeri filter
        $config = apply_filters('pcz_header_dropdown_config', array(), $menu_item_title, $menu_item_url);
        if (!empty($config['acf_field'])) {
            return $config['acf_field'];
        }
        
        // Default mappings
        $default_mappings = apply_filters('pcz_header_dropdown_mappings', array(
            'ponuda' => 'ponuda_blokovi',
        ));
        
        $title_lower = strtolower(trim($menu_item_title));
        if (isset($default_mappings[$title_lower])) {
            return $default_mappings[$title_lower];
        }
        
        // Auto-detect pattern
        if ($menu_item_url === '#' || empty($menu_item_url) || strpos($menu_item_url, '#') === 0) {
            $slug = sanitize_title($menu_item_title);
            $field_name = $slug . '_blokovi';
            
            // Provjeri postoji li field
            $test_data = get_field($field_name, 'option');
            if (!empty($test_data)) {
                return $field_name;
            }
        }
        
        return null;
    }
}

if (!function_exists('pcz_has_dropdown')) {
    /**
     * Mock implementation of pcz_has_dropdown
     * 
     * @param string $menu_item_title
     * @param string $menu_item_url
     * @return bool
     */
    function pcz_has_dropdown($menu_item_title, $menu_item_url) {
        $field_name = pcz_get_dropdown_field_name($menu_item_title, $menu_item_url);
        if (!$field_name) {
            return false;
        }
        
        $data = get_field($field_name, 'option');
        return !empty($data);
    }
}

if (!function_exists('sanitize_title')) {
    /**
     * Sanitize title to slug
     * 
     * @param string $title
     * @return string
     */
    function sanitize_title($title) {
        $title = strtolower($title);
        $title = preg_replace('/[^a-z0-9]+/', '-', $title);
        return trim($title, '-');
    }
}

if (!function_exists('sanitize_key')) {
    /**
     * Sanitize a string key
     * 
     * Keys are used as internal identifiers. Lowercase alphanumeric characters,
     * dashes and underscores are allowed.
     * 
     * @param string $key String key
     * @return string Sanitized key
     */
    function sanitize_key($key) {
        $key = strtolower($key);
        $key = preg_replace('/[^a-z0-9_\-]/', '', $key);
        return $key;
    }
}

if (!function_exists('sanitize_text_field')) {
    /**
     * Sanitize a string from user input
     * 
     * @param string $str String to sanitize
     * @return string Sanitized string
     */
    function sanitize_text_field($str) {
        $str = strip_tags($str);
        $str = preg_replace('/[\r\n\t]+/', ' ', $str);
        $str = trim($str);
        return $str;
    }
}

// =============================================================================
// PCZ BRAND SYSTEM MOCK FUNCTIONS
// =============================================================================

// Globalna varijabla za trenutni brand (može se postaviti u testu)
$GLOBALS['pcz_current_brand'] = $_GET['brand'] ?? 'plesna-skola';

if (!function_exists('pcz_get_current_brand_id')) {
    /**
     * Mock implementation of pcz_get_current_brand_id
     * 
     * @return string Current brand ID ('plesna-skola' or 'sportski-klub')
     */
    function pcz_get_current_brand_id() {
        return $GLOBALS['pcz_current_brand'] ?? 'plesna-skola';
    }
}

if (!function_exists('pcz_is_plesna_skola')) {
    /**
     * Check if current brand is Plesna Škola
     * 
     * @return bool
     */
    function pcz_is_plesna_skola() {
        return pcz_get_current_brand_id() === 'plesna-skola';
    }
}

if (!function_exists('pcz_is_sportski_klub')) {
    /**
     * Check if current brand is Sportski Klub
     * 
     * @return bool
     */
    function pcz_is_sportski_klub() {
        return pcz_get_current_brand_id() === 'sportski-klub';
    }
}

if (!function_exists('pcz_brand_is')) {
    /**
     * Check if current brand matches given brand ID
     * 
     * @param string $brand_id Brand ID to check
     * @return bool
     */
    function pcz_brand_is($brand_id) {
        return pcz_get_current_brand_id() === $brand_id;
    }
}

if (!function_exists('pcz_brand_is_not')) {
    /**
     * Check if current brand does NOT match given brand ID
     * 
     * @param string $brand_id Brand ID to check
     * @return bool
     */
    function pcz_brand_is_not($brand_id) {
        return pcz_get_current_brand_id() !== $brand_id;
    }
}

/**
 * Set current brand for testing
 * 
 * @param string $brand_id Brand ID to set
 * @return void
 */
function pcz_set_current_brand($brand_id) {
    $GLOBALS['pcz_current_brand'] = $brand_id;
}

