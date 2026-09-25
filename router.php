<?php
/**
 * Router para `php -S host:port router.php`
 */
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rawurldecode($uri);

# /admin sin slash rompe rutas relativas de CSS/JS
if ($uri === '/admin') {
    header('Location: /admin/', true, 301);
    return true;
}

$file = __DIR__ . $uri;

if ($uri !== '/' && is_file($file)) {
    return false; // servir estático
}

if ($uri !== '/' && is_dir($file)) {
    $index = rtrim($file, '/') . '/index.php';
    if (is_file($index)) {
        require $index;
        return true;
    }
}

if ($uri === '/' || $uri === '' || $uri === '/index.php') {
    require __DIR__ . '/index.php';
    return true;
}

http_response_code(404);
echo '404 Not Found';
