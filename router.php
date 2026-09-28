<?php
/**
 * Router para `php -S host:port router.php` (servir desde la raíz del proyecto).
 * Sin prefijo /pcv-soluciones: las URLs son relativas al docroot del built-in server.
 */
require_once __DIR__ . '/admin/modelo/config/config.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rawurldecode($uri);

# /admin sin slash rompe rutas relativas de CSS/JS
if ($uri === '/admin') {
    header('Location: /admin/', true, 301);
    return true;
}

$rel = $uri;
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
