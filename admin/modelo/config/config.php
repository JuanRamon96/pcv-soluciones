<?php
/**
 * Configuración global PCV Soluciones Industriales
 *
 * URLs públicas/admin: helpers con rutas RELATIVAS al script actual
 * (pcv_rel_prefix). Mismo código en XAMPP (subcarpeta) y en la raíz del
 * dominio — sin /pcv-soluciones hardcodeado ni PCV_BASE distinto por host.
 *
 * Overrides DB: si existe config.local.php se incluye ANTES de los defaults.
 */
date_default_timezone_set('America/Mexico_City');

$pcv_local = __DIR__ . '/config.local.php';
if (is_file($pcv_local)) {
    require_once $pcv_local;
}

define('PCV_ROOT', dirname(__DIR__, 3));

// Compat legacy: ya no se usa para armar CSS/JS/links (ver pcv_rel_prefix).
if (!defined('PCV_BASE')) {
    define('PCV_BASE', '');
}
if (!defined('PCV_ADMIN_BASE')) {
    define('PCV_ADMIN_BASE', '');
}

define('PCV_UPLOADS', PCV_ROOT . '/uploads/productos');
define('PCV_UPLOADS_URL', 'uploads/productos');
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

function pcv_esc($s) {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

/**
 * Prefijo relativo desde el directorio del script hasta la raíz del proyecto.
 * index.php en raíz → ''; admin/index.php → '../'
 */
function pcv_rel_prefix() {
    static $prefix = null;
    if ($prefix !== null) {
        return $prefix;
    }
    $root = realpath(PCV_ROOT);
    $script = isset($_SERVER['SCRIPT_FILENAME']) ? realpath($_SERVER['SCRIPT_FILENAME']) : false;
    if (!$root || !$script) {
        $prefix = '';
        return $prefix;
    }
    $root = str_replace('\\', '/', $root);
    $dir = str_replace('\\', '/', dirname($script));
    if (stripos($dir, $root) !== 0) {
        $prefix = '';
        return $prefix;
    }
    $rel = trim(substr($dir, strlen($root)), '/');
    if ($rel === '') {
        $prefix = '';
    } else {
        $depth = substr_count($rel, '/') + 1;
        $prefix = str_repeat('../', $depth);
    }
    return $prefix;
}

function pcv_asset($path) {
    return pcv_rel_prefix() . 'assets/' . ltrim((string)$path, '/');
}

function pcv_url($path = '') {
    $path = (string)$path;
    if ($path !== '' && isset($path[0]) && $path[0] === '/') {
        $path = ltrim($path, '/');
    }
    $prefix = pcv_rel_prefix();
    if ($path === '' || $path === '/') {
        return $prefix === '' ? './' : $prefix;
    }
    return $prefix . $path;
}

function pcv_admin_asset($path) {
    return pcv_rel_prefix() . 'admin/vistas/assets/' . ltrim((string)$path, '/');
}

function pcv_img_producto($archivo) {
    $pre = pcv_rel_prefix();
    if (!$archivo) {
        return pcv_asset('img/placeholders/item-1.png');
    }
    if (is_file(PCV_ROOT . '/uploads/productos/' . $archivo)) {
        return $pre . 'uploads/productos/' . rawurlencode($archivo);
    }
    if (is_file(PCV_ROOT . '/uploads/' . $archivo)) {
        return $pre . 'uploads/' . rawurlencode($archivo);
    }
    if (is_file(PCV_ROOT . '/assets/img/placeholders/' . $archivo)) {
        return pcv_asset('img/placeholders/' . $archivo);
    }
    if (is_file(PCV_ROOT . '/assets/img/catalogo/' . $archivo)) {
        return pcv_asset('img/catalogo/' . $archivo);
    }
    return pcv_asset('img/placeholders/item-1.png');
}
