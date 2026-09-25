<?php
/**
 * Configuración global PCV Soluciones Industriales
 *
 * PCV_BASE: prefijo URL del sitio.
 *   - XAMPP en htdocs/pcv-soluciones → '/pcv-soluciones'
 *   - Vhost o php -S en la raíz del proyecto → ''
 *
 * Overrides: si existe includes/config.local.php, se incluye ANTES de los
 * defaults (usa if (!defined(...)) define(...)).
 */
date_default_timezone_set('America/Mexico_City');

$pcv_local = __DIR__ . '/config.local.php';
if (is_file($pcv_local)) {
    require_once $pcv_local;
}

define('PCV_ROOT', dirname(__DIR__));

// Base URL: subcarpeta XAMPP por defecto. En raíz de vhost usar '' vía config.local.php
if (!defined('PCV_BASE')) {
    define('PCV_BASE', '/pcv-soluciones');
}
if (!defined('PCV_ADMIN_BASE')) {
    define('PCV_ADMIN_BASE', PCV_BASE . '/admin');
}

define('PCV_UPLOADS', PCV_ROOT . '/uploads/productos');
define('PCV_UPLOADS_URL', PCV_BASE . '/uploads/productos');
define('PCV_WHATSAPP', '5213311444743');
define('PCV_EMAIL', 'gerardo.solind@gmail.com');
define('PCV_TEL1', '3311444743');
define('PCV_TEL2', '3310438300');
define('PCV_IG', 'pcvsolind');
define('PCV_FB', 'https://www.facebook.com/share/1DzyvKrbSt/');
define('PCV_ESLOGAN', 'Creamos la figura más difícil de la industria');

// Credenciales DB — defaults XAMPP (root sin contraseña)
if (!defined('DB_HOST')) {
    define('DB_HOST', '127.0.0.1');
}
if (!defined('DB_USER')) {
    define('DB_USER', 'root');
}
if (!defined('DB_PASS')) {
    define('DB_PASS', '');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', 'pcv_soluciones');
}
if (!defined('DB_PORT')) {
    define('DB_PORT', 3306);
}

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
    $path = ltrim((string)$path, '/');
    if ($path === '') {
        return PCV_BASE === '' ? '/' : PCV_BASE . '/';
    }
    return PCV_BASE . '/' . $path;
}

function pcv_admin_asset($path) {
    return PCV_ADMIN_BASE . '/vistas/assets/' . ltrim($path, '/');
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
