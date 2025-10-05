<?php
// Router for PHP built-in server to mimic pretty URLs
$requested = $_SERVER['REQUEST_URI'];
$path = parse_url($requested, PHP_URL_PATH);
$file = __DIR__ . $path;

// If the request is for an actual file in public/, serve it
if ($path !== '/' && file_exists($file) && is_file($file)) {
    return false; // let the built-in server serve the file
}

// Otherwise forward to the application's front controller
if (!defined('PREVENT_DIRECT_ACCESS')) define('PREVENT_DIRECT_ACCESS', true);
require_once __DIR__ . '/../index.php';
