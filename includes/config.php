<?php
/**
 * Configuración global PCV Soluciones Industriales
 */
date_default_timezone_set('America/Mexico_City');

define('PCV_ROOT', dirname(__DIR__));
define('PCV_BASE', ''); // ajustar si se sirve desde subcarpeta, ej. '/pcv-soluciones'
define('PCV_UPLOADS', PCV_ROOT . '/uploads/productos');
define('PCV_UPLOADS_URL', PCV_BASE . '/uploads/productos');
define('PCV_WHATSAPP', '5213311444743');
define('PCV_EMAIL', 'gerardo.solind@gmail.com');
define('PCV_TEL1', '3311444743');
define('PCV_TEL2', '3310438300');
define('PCV_IG', 'pcvsolind');
define('PCV_FB', 'https://www.facebook.com/share/1DzyvKrbSt/');
define('PCV_ESLOGAN', 'Creamos la figura más difícil de la industria');

define('DB_HOST', '127.0.0.1');
define('DB_USER', 'pcv');
define('DB_PASS', 'pcv_local');
define('DB_NAME', 'pcv_soluciones');
define('DB_PORT', 3306);

if (session_status() === PHP_SESSION_NONE) {
    session_cache_expire(30);
    session_start();
}

function pcv_esc($s) {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

function pcv_asset($path) {
    return PCV_BASE . '/assets/' . ltrim($path, '/');
}

function pcv_url($path = '') {
    return PCV_BASE . '/' . ltrim($path, '/');
}

function pcv_img_producto($archivo) {
    if (!$archivo) {
        return pcv_asset('img/placeholders/item-1.png');
    }
    $local = PCV_UPLOADS . '/' . $archivo;
    if (is_file($local)) {
        return PCV_UPLOADS_URL . '/' . rawurlencode($archivo);
    }
    $ph = PCV_ROOT . '/assets/img/placeholders/' . $archivo;
    if (is_file($ph)) {
        return pcv_asset('img/placeholders/' . $archivo);
    }
    return pcv_asset('img/placeholders/item-1.png');
}
