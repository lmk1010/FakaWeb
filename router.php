<?php
declare(strict_types=1);

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$path = __DIR__ . $uri;

// Let the built-in server serve real files directly.
if ($uri !== '/' && is_file($path)) {
    return false;
}

// Emulate nginx/apache rewrite: /foo/bar -> index.php?s=/foo/bar
if (!isset($_GET['s']) || $_GET['s'] === '') {
    $_GET['s'] = $uri === '/' ? '/user/index/index' : '/' . trim($uri, '/');
}

require __DIR__ . '/index.php';
