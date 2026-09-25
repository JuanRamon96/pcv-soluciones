<?php
/**
 * Router para `php -S host:port router.php`
 */
require_once __DIR__ . '/includes/config.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rawurldecode($uri);

$base = PCV_BASE; // '' o '/pcv-soluciones'
$adminPath = ($base === '' ? '/admin' : rtrim($base, '/') . '/admin');

# /admin sin slash rompe rutas relativas de CSS/JS
if ($uri === $adminPath) {
    header('Location: ' . $adminPath . '/', true, 301);
    return true;
}

# Quitar prefijo PCV_BASE para resolver archivos en disco
$rel = $uri;
if ($base !== '' && strpos($uri, $base) === 0) {
    $rel = substr($uri, strlen($base));
    if ($rel === '' || $rel === false) {
        $rel = '/';
    }
}

$file = __DIR__ . $rel;

if ($rel !== '/' && is_file($file)) {
    return false; // servir estático
}

if ($rel !== '/' && is_dir($file)) {
    $index = rtrim($file, '/') . '/index.php';
    if (is_file($index)) {
        require $index;
        return true;
    }
}

if ($rel === '/' || $rel === '' || $rel === '/index.php') {
    require __DIR__ . '/index.php';
    return true;
}

http_response_code(404);
echo '404 Not Found';
