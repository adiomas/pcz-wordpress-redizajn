<?php
/**
 * pcz Test Environment - Router
 * 
 * Ovaj fajl služi kao router za PHP development server.
 * Automatski servira statičke fajlove iz project root-a.
 * 
 * ROBUSNO RJEŠENJE:
 * - Automatski otkriva sve direktorije s komponentama
 * - Ne zahtijeva ručno dodavanje novih komponenti
 * - Servira sve potrebne MIME tipove
 * 
 * Korištenje:
 * php -S localhost:8080 router.php
 * 
 * @package pcz_Test_Environment
 * @since 1.0.0
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$project_root = dirname(__DIR__);
$test_root = __DIR__;

// =============================================================================
// MIME TYPES MAPPING
// =============================================================================

$mime_types = [
    // Styles & Scripts
    'css'   => 'text/css',
    'js'    => 'application/javascript',
    'mjs'   => 'application/javascript',
    
    // Images
    'svg'   => 'image/svg+xml',
    'png'   => 'image/png',
    'jpg'   => 'image/jpeg',
    'jpeg'  => 'image/jpeg',
    'gif'   => 'image/gif',
    'webp'  => 'image/webp',
    'ico'   => 'image/x-icon',
    
    // Fonts
    'woff'  => 'font/woff',
    'woff2' => 'font/woff2',
    'ttf'   => 'font/ttf',
    'otf'   => 'font/otf',
    'eot'   => 'application/vnd.ms-fontobject',
    
    // Data
    'json'  => 'application/json',
    'xml'   => 'application/xml',
    'txt'   => 'text/plain',
    'md'    => 'text/plain',
    
    // Media
    'mp4'   => 'video/mp4',
    'webm'  => 'video/webm',
    'mp3'   => 'audio/mpeg',
    'ogg'   => 'audio/ogg',
];

// =============================================================================
// HELPER FUNCTION: Serve Static File
// =============================================================================

function serve_static_file($filepath, $mime_types) {
    if (!file_exists($filepath) || !is_file($filepath)) {
        return false;
    }
    
    $ext = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
    
    // Set content type
    if (isset($mime_types[$ext])) {
        header('Content-Type: ' . $mime_types[$ext]);
    }
    
    // Cache headers for static files
    header('Cache-Control: public, max-age=3600');
    
    readfile($filepath);
    return true;
}

// =============================================================================
// HELPER FUNCTION: Discover Component Directories
// =============================================================================

function get_component_directories($project_root) {
    $component_dirs = [];
    
    // Skeniraj project root za direktorije koji imaju .php, .css ili .js fajlove
    $exclude_dirs = ['test', 'node_modules', 'vendor', '.git', '.idea', '.vscode', 'docs'];
    
    $items = scandir($project_root);
    foreach ($items as $item) {
        // Preskoči skrivene i isključene direktorije
        if ($item[0] === '.' || in_array($item, $exclude_dirs)) {
            continue;
        }
        
        $path = $project_root . '/' . $item;
        
        // Provjeri da li je direktorij i da li sadrži PHP/CSS/JS fajlove
        if (is_dir($path)) {
            $files = scandir($path);
            foreach ($files as $file) {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if (in_array($ext, ['php', 'css', 'js', 'json'])) {
                    $component_dirs[] = $item;
                    break;
                }
            }
        }
    }
    
    return $component_dirs;
}

// =============================================================================
// ROUTING LOGIC
// =============================================================================

// 1. Dinamički otkrivene komponente iz project root-a
$component_dirs = get_component_directories($project_root);

foreach ($component_dirs as $dir) {
    if (strpos($uri, '/' . $dir . '/') === 0) {
        $file = $project_root . $uri;
        if (serve_static_file($file, $mime_types)) {
            return true;
        }
    }
}

// 2. Test assets
if (strpos($uri, '/test/') === 0) {
    $file = $project_root . $uri;
    if (serve_static_file($file, $mime_types)) {
        return true;
    }
}

// 3. Direct file access from project root (za slike, itd.)
$direct_file = $project_root . $uri;
if (preg_match('/\.(css|js|svg|png|jpg|jpeg|gif|webp|ico|woff|woff2|ttf|otf|json)$/i', $uri)) {
    if (serve_static_file($direct_file, $mime_types)) {
        return true;
    }
}

// =============================================================================
// DEFAULT: Route to index.php
// =============================================================================

$_SERVER['SCRIPT_NAME'] = '/index.php';
require $test_root . '/index.php';
