<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
$pcv_page = 'catalogo';
$pcv_title = 'Catálogo | PCV Soluciones Industriales';
$db = pcv_db();

$clasSlug = trim($_GET['clasificacion'] ?? '');
$subSlug = trim($_GET['subtipo'] ?? '');
$q = trim($_GET['q'] ?? '');

$clasifs = [];
$res = $db->query("SELECT * FROM clasificaciones WHERE activo=1 ORDER BY orden");
while ($r = $res->fetch_assoc()) { $clasifs[] = $r; }

$subtipos = [];
$sqlSub = "SELECT s.*, c.slug AS clas_slug FROM subtipos s INNER JOIN clasificaciones c ON c.id=s.clasificacion_id WHERE s.activo=1";
if ($clasSlug !== '') {
    $cs = $db->real_escape_string($clasSlug);
    $sqlSub .= " AND c.slug='$cs'";
}
$sqlSub .= " ORDER BY s.orden";
$res = $db->query($sqlSub);
while ($r = $res->fetch_assoc()) { $subtipos[] = $r; }

$where = "p.activo=1";
if ($clasSlug !== '') {
    $cs = $db->real_escape_string($clasSlug);
    $where .= " AND c.slug='$cs'";
}
if ($subSlug !== '') {
    $ss = $db->real_escape_string($subSlug);
    $where .= " AND s.slug='$ss'";
}
if ($q !== '') {
    $qq = $db->real_escape_string($q);
    $where .= " AND (p.nombre LIKE '%$qq%' OR p.resumen LIKE '%$qq%')";
}

$sql = "SELECT p.*, c.nombre AS clasificacion, c.slug AS clas_slug, s.nombre AS subtipo, s.slug AS sub_slug
        FROM productos p
        INNER JOIN clasificaciones c ON c.id=p.clasificacion_id
        LEFT JOIN subtipos s ON s.id=p.subtipo_id
        WHERE $where
        ORDER BY FIELD(p.tipo,'servicio','producto'), p.orden, p.id";
$items = [];
$res = $db->query($sql);
while ($r = $res->fetch_assoc()) { $items[] = $r; }

$servicios = array_values(array_filter($items, fn($i) => $i['tipo'] === 'servicio'));
$productos = array_values(array_filter($items, fn($i) => $i['tipo'] === 'producto'));

require __DIR__ . '/includes/header.php';

function pcv_card($item) {
    $badge = $item['tipo'] === 'servicio' ? 'badge-tipo' : 'badge-prod';
    $label = $item['tipo'] === 'servicio' ? 'Servicio' : 'Producto';
    $url = pcv_url('ficha.php?slug=' . urlencode($item['slug']));
    $img = pcv_img_producto($item['imagen_principal']);
    echo '<div class="col-md-6 col-lg-4"><a class="text-dark" href="'.pcv_esc($url).'"><div class="card card-service card-product">';
    echo '<img class="card-img-top" src="'.pcv_esc($img).'" alt="'.pcv_esc($item['nombre']).'">';
    echo '<div class="card-body"><span class="badge '.$badge.' mb-2">'.$label.'</span>';
    echo '<h3 class="h6 mb-1">'.pcv_esc($item['nombre']).'</h3>';
    echo '<p class="small text-muted mb-0">'.pcv_esc($item['clasificacion']).($item['subtipo']?' · '.pcv_esc($item['subtipo']):'').'</p>';
    echo '</div></div></a></div>';
}
?>
<section class="py-4 bg-light border-bottom">
  <div class="container">
    <h1 class="h3 section-title mb-3">Catálogo</h1>
    <form class="row g-2 mb-3" method="get">
      <div class="col-md-6"><input type="search" name="q" value="<?= pcv_esc($q) ?>" class="form-control" placeholder="Buscar…"></div>
      <div class="col-auto"><button class="btn btn-pcv btn-accent">Buscar</button></div>
    </form>
    <div>
      <a class="filter-chip <?= $clasSlug===''?'active':'' ?>" href="<?= pcv_url('catalogo.php') ?>">Todas</a>
      <?php foreach ($clasifs as $c): ?>
        <a class="filter-chip <?= $clasSlug===$c['slug']?'active':'' ?>" href="<?= pcv_url('catalogo.php?clasificacion='.urlencode($c['slug'])) ?>"><?= pcv_esc($c['nombre']) ?></a>
      <?php endforeach; ?>
    </div>
    <?php if ($subtipos): ?>
    <div class="mt-2">
      <?php foreach ($subtipos as $s): ?>
        <a class="filter-chip <?= $subSlug===$s['slug']?'active':'' ?>" href="<?= pcv_url('catalogo.php?clasificacion='.urlencode($s['clas_slug']).'&subtipo='.urlencode($s['slug'])) ?>"><?= pcv_esc($s['nombre']) ?></a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <h2 class="h4 section-title mb-3">Servicios</h2>
    <div class="row g-4 mb-5">
      <?php if (!$servicios): ?><p class="text-muted">No hay servicios con este filtro.</p><?php endif; ?>
      <?php foreach ($servicios as $it) { pcv_card($it); } ?>
    </div>
    <h2 class="h4 section-title mb-3">Productos</h2>
    <div class="row g-4">
      <?php if (!$productos): ?><p class="text-muted">No hay productos con este filtro.</p><?php endif; ?>
      <?php foreach ($productos as $it) { pcv_card($it); } ?>
    </div>

    <div class="mt-5 pt-4 border-top" id="cotizar">
      <h2 class="h4 section-title">¿Necesitas una cotización?</h2>
      <p class="text-muted">No publicamos precios. Cuéntanos qué necesitas.</p>
      <form id="formCotizar" action="<?= pcv_url('cotizar.php') ?>" method="post" class="row g-3">
        <div class="col-md-4"><input required name="nombre" class="form-control" placeholder="Nombre *"></div>
        <div class="col-md-4"><input required name="telefono" id="cotTel" class="form-control" placeholder="Teléfono *"></div>
        <div class="col-md-4"><input type="email" name="correo" class="form-control" placeholder="Correo"></div>
        <div class="col-12"><textarea name="mensaje" class="form-control" rows="2" placeholder="Mensaje"></textarea></div>
        <div class="col-12"><button class="btn btn-green">Enviar</button></div>
      </form>
    </div>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
