<?php
// Global configuration & bootstrap
// Starts session, defines path/url helpers, and loads DB connection
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Absolute filesystem path to project root
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__);
}

// Derive first path segment under web root as base URL (e.g. /1) for XAMPP style folder deployment
if (!defined('BASE_URL')) {
    $script = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME']) : '';
    $parts  = array_values(array_filter(explode('/', $script)));
    // Remove script filename
    if ($parts && strpos(end($parts), '.php') !== false) {
        array_pop($parts);
    }
    $baseSegment = $parts ? '/' . $parts[0] : '';
    define('BASE_URL', $baseSegment);
}

// Helper to build URLs
if (!function_exists('url')) {
    function url(string $path = ''): string {
        return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
    }
}

// Helper to build asset URLs
if (!function_exists('asset')) {
    function asset(string $path): string {
        return url('assets/' . ltrim($path, '/'));
    }
}

// Load database connection once
require_once BASE_PATH . '/db.php';
?>
