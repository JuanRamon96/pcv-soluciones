<?php
/**
 * Cotizar: GET = página de formulario | POST = API JSON (guarda + WhatsApp)
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
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
    exit;
}

/* ---------- GET: página pública ---------- */
$pcv_page = 'cotizar';
$pcv_title = 'Cotizar | PCV Soluciones Industriales';
$tel1_fmt = preg_replace('/(\d{2})(\d{4})(\d{4})/', '$1 $2 $3', PCV_TEL1);
require __DIR__ . '/includes/header.php';
?>
<section class="page-banner">
  <div class="container">
    <h1>Cotizar</h1>
    <p>Cuéntanos tu proyecto. Sin precios públicos: cada solicitud se atiende a la medida y puedes continuar por WhatsApp.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-7">
        <span class="section-eyebrow">Formulario</span>
        <h2 class="section-title h3 mb-3">Solicita tu cotización</h2>
        <form id="formCotizar" action="<?= pcv_url('cotizar.php') ?>" method="post" class="pcv-form-card">
          <input type="hidden" name="origen" value="cotizar">
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Nombre *</label><input required name="nombre" class="form-control"></div>
            <div class="col-md-6"><label class="form-label">Empresa</label><input name="empresa" class="form-control"></div>
            <div class="col-md-6"><label class="form-label">Teléfono *</label><input required name="telefono" id="cotTel" class="form-control" placeholder="33 1144 4743"></div>
            <div class="col-md-6"><label class="form-label">Correo</label><input type="email" name="correo" class="form-control"></div>
            <div class="col-12"><label class="form-label">Mensaje</label><textarea name="mensaje" rows="4" class="form-control" placeholder="Describe tu pieza, material, tolerancias o luminaria…"></textarea></div>
            <div class="col-12 d-grid"><button class="btn btn-green btn-lg" type="submit">Enviar cotización</button></div>
          </div>
        </form>
      </div>
      <div class="col-lg-5">
        <span class="section-eyebrow">Contacto directo</span>
        <h2 class="section-title h3 mb-3">También puedes llamarnos</h2>
        <div class="contact-tile mb-3">
          <div class="ico"><i class="fas fa-phone"></i></div>
          <div>
            <strong>Teléfono</strong>
            <div><a href="tel:<?= pcv_esc(PCV_TEL1) ?>"><?= pcv_esc($tel1_fmt) ?></a></div>
          </div>
        </div>
        <div class="contact-tile mb-3">
          <div class="ico"><i class="fas fa-envelope"></i></div>
          <div>
            <strong>Correo</strong>
            <a href="mailto:<?= pcv_esc(PCV_EMAIL) ?>"><?= pcv_esc(PCV_EMAIL) ?></a>
          </div>
        </div>
        <div class="contact-tile mb-4">
          <div class="ico"><i class="fab fa-whatsapp"></i></div>
          <div>
            <strong>WhatsApp</strong>
            <a href="https://wa.me/<?= pcv_esc(PCV_WHATSAPP) ?>" target="_blank" rel="noopener">Escribir ahora</a>
          </div>
        </div>
        <a href="<?= pcv_url('catalogo.php') ?>" class="btn btn-outline-navy">Ver catálogo</a>
      </div>
    </div>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
