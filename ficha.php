<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
$pcv_page = 'catalogo';
$db = pcv_db();
$slug = trim($_GET['slug'] ?? '');
if ($slug === '') {
    header('Location: ' . pcv_url('catalogo.php'));
    exit;
}
$ss = $db->real_escape_string($slug);
$res = $db->query("SELECT p.*, c.nombre AS clasificacion, c.slug AS clas_slug, s.nombre AS subtipo, s.slug AS sub_slug
  FROM productos p
  INNER JOIN clasificaciones c ON c.id=p.clasificacion_id
  LEFT JOIN subtipos s ON s.id=p.subtipo_id
  WHERE p.slug='$ss' AND p.activo=1 LIMIT 1");
$item = $res ? $res->fetch_assoc() : null;
if (!$item) {
    http_response_code(404);
    $pcv_title = 'No encontrado | PCV';
    require __DIR__ . '/includes/header.php';
    echo '<section class="page-banner"><div class="container"><h1>No encontrado</h1></div></section>';
    echo '<div class="container py-5"><p>Producto no encontrado.</p><a class="btn btn-navy" href="'.pcv_url('catalogo.php').'">Volver al catálogo</a></div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}
$pcv_title = $item['nombre'] . ' | PCV Soluciones Industriales';
$galeria = [];
$gid = (int)$item['id'];
$res = $db->query("SELECT archivo FROM producto_imagenes WHERE producto_id=$gid ORDER BY orden, id");
while ($r = $res->fetch_assoc()) { $galeria[] = $r['archivo']; }
if (!$galeria && $item['imagen_principal']) {
    $galeria[] = $item['imagen_principal'];
}
$main = $galeria[0] ?? $item['imagen_principal'];
$isService = $item['tipo'] === 'servicio';

require __DIR__ . '/includes/header.php';
?>
<section class="page-banner">
  <div class="container">
    <nav aria-label="breadcrumb" class="mb-2">
      <ol class="breadcrumb small mb-0" style="--bs-breadcrumb-divider-color:rgba(255,255,255,.5)">
        <li class="breadcrumb-item"><a class="text-white-50" href="<?= pcv_url('catalogo.php') ?>">Catálogo</a></li>
        <li class="breadcrumb-item"><a class="text-white-50" href="<?= pcv_url('catalogo.php?clasificacion='.urlencode($item['clas_slug'])) ?>"><?= pcv_esc($item['clasificacion']) ?></a></li>
        <li class="breadcrumb-item active text-white" aria-current="page"><?= pcv_esc($item['nombre']) ?></li>
      </ol>
    </nav>
    <h1><?= pcv_esc($item['nombre']) ?></h1>
    <p><?= pcv_esc($item['clasificacion']) ?><?= $item['subtipo'] ? ' · '.pcv_esc($item['subtipo']) : '' ?></p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-6">
        <div class="ficha-main mb-3">
          <img id="imgPrincipal" src="<?= pcv_esc(pcv_img_producto($main)) ?>" alt="<?= pcv_esc($item['nombre']) ?>">
        </div>
        <?php if (count($galeria) > 1): ?>
        <div class="d-flex flex-wrap gap-2">
          <?php foreach ($galeria as $i => $g): ?>
            <img class="gallery-thumb <?= $i===0?'active':'' ?>" src="<?= pcv_esc(pcv_img_producto($g)) ?>" data-full="<?= pcv_esc(pcv_img_producto($g)) ?>" alt="">
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <div class="col-lg-6">
        <span class="pcv-card-badge <?= $isService?'badge-tipo':'badge-prod' ?>" style="position:static;display:inline-block;margin-bottom:.75rem"><?= $isService?'Servicio':'Producto' ?></span>
        <p class="ficha-meta mb-2"><?= pcv_esc($item['clasificacion']) ?><?= $item['subtipo'] ? ' · '.pcv_esc($item['subtipo']) : '' ?></p>
        <h2 class="section-title h1 mb-3"><?= pcv_esc($item['nombre']) ?></h2>
        <div class="mb-4"><?= nl2br(pcv_esc($item['descripcion'] ?: $item['resumen'])) ?></div>
        <p class="small text-muted mb-4"><i class="fas fa-info-circle me-1"></i> Los precios no se publican; solicita cotización personalizada.</p>
        <div class="d-flex flex-wrap gap-2">
          <a href="#formFichaCotizar" class="btn btn-cta btn-lg">Cotizar este ítem <i class="fas fa-arrow-right"></i></a>
          <a class="btn btn-outline-navy btn-lg" target="_blank" rel="noopener"
             href="https://wa.me/<?= pcv_esc(PCV_WHATSAPP) ?>?text=<?= rawurlencode('Hola PCV, quiero cotizar: '.$item['nombre']) ?>">
            <i class="fab fa-whatsapp"></i> WhatsApp
          </a>
        </div>
      </div>
    </div>

    <div class="row mt-5 pt-4" id="formFichaCotizar">
      <div class="col-lg-8">
        <span class="section-eyebrow">Cotización</span>
        <h2 class="section-title h3 mb-3">Solicitar cotización</h2>
        <form id="formCotizar" action="<?= pcv_url('cotizar.php') ?>" method="post" class="pcv-form-card">
          <input type="hidden" name="producto_id" value="<?= (int)$item['id'] ?>">
          <input type="hidden" name="origen" value="ficha">
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Nombre *</label><input required name="nombre" class="form-control"></div>
            <div class="col-md-6"><label class="form-label">Empresa</label><input name="empresa" class="form-control"></div>
            <div class="col-md-6"><label class="form-label">Teléfono *</label><input required name="telefono" id="cotTel" class="form-control" placeholder="33 1144 4743"></div>
            <div class="col-md-6"><label class="form-label">Correo</label><input type="email" name="correo" class="form-control"></div>
            <div class="col-12"><label class="form-label">Mensaje</label><textarea name="mensaje" class="form-control" rows="3">Me interesa cotizar: <?= pcv_esc($item['nombre']) ?></textarea></div>
            <div class="col-12"><button type="submit" class="btn btn-green btn-lg">Enviar y abrir WhatsApp</button></div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
<script>
document.querySelectorAll('.gallery-thumb').forEach(function(el){
  el.addEventListener('click', function(){
    document.querySelectorAll('.gallery-thumb').forEach(function(t){ t.classList.remove('active'); });
    el.classList.add('active');
    document.getElementById('imgPrincipal').src = el.getAttribute('data-full');
  });
});
</script>
<?php require __DIR__ . '/includes/footer.php'; ?>
