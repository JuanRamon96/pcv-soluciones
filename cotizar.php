<?php
/**
 * Endpoint de cotización: guarda en DB y devuelve enlace WhatsApp.
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
    exit;
}

$db = pcv_db();
$nombre = trim($_POST['nombre'] ?? '');
$empresa = trim($_POST['empresa'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$telefono = preg_replace('/\s+/', '', trim($_POST['telefono'] ?? ''));
$mensaje = trim($_POST['mensaje'] ?? '');
$producto_id = (int)($_POST['producto_id'] ?? 0);
$origen = trim($_POST['origen'] ?? 'web');

if ($nombre === '' || $telefono === '') {
    echo json_encode(['ok' => false, 'error' => 'Nombre y teléfono son obligatorios']);
    exit;
}

$folio = 'PCV-' . date('ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
$n = $db->real_escape_string($nombre);
$e = $db->real_escape_string($empresa);
$c = $db->real_escape_string($correo);
$t = $db->real_escape_string($telefono);
$m = $db->real_escape_string($mensaje);
$o = $db->real_escape_string($origen);
$f = $db->real_escape_string($folio);
$pid = $producto_id > 0 ? $producto_id : 'NULL';

$sql = "INSERT INTO cotizaciones SET folio='$f', nombre='$n', empresa='$e', correo='$c', telefono='$t',
        mensaje='$m', producto_id=$pid, origen='$o', estatus='nueva'";
if (!$db->query($sql)) {
    echo json_encode(['ok' => false, 'error' => 'No se pudo guardar']);
    exit;
}

$prodNombre = '';
if ($producto_id > 0) {
    $r = $db->query("SELECT nombre FROM productos WHERE id=$producto_id");
    if ($r && $row = $r->fetch_assoc()) {
        $prodNombre = $row['nombre'];
    }
}

$texto = "Hola PCV, soy $nombre.";
if ($empresa) $texto .= " Empresa: $empresa.";
$texto .= " Folio $folio.";
if ($prodNombre) $texto .= " Me interesa: $prodNombre.";
if ($mensaje) $texto .= " Detalle: $mensaje";
$texto .= " Tel: $telefono";

$wa = 'https://wa.me/' . PCV_WHATSAPP . '?text=' . rawurlencode($texto);
echo json_encode(['ok' => true, 'folio' => $folio, 'whatsapp' => $wa]);
