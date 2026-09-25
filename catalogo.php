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

function pcv_card($item, $large = false) {
    $isService = $item['tipo'] === 'servicio';
    $badge = $isService ? 'badge-tipo' : 'badge-prod';
    $label = $isService ? 'Servicio' : 'Producto';
    $url = pcv_url('ficha.php?slug=' . urlencode($item['slug']));
    $img = pcv_img_producto($item['imagen_principal']);
    $col = $large ? 'col-md-6 col-xl-4' : 'col-sm-6 col-lg-4 col-xl-3';
    $cardClass = $large ? 'pcv-card pcv-card-service' : 'pcv-card';
    $cat = pcv_esc($item['clasificacion']) . ($item['subtipo'] ? ' · ' . pcv_esc($item['subtipo']) : '');
    echo '<div class="'.$col.'">';
    echo '<a class="pcv-card-link" href="'.pcv_esc($url).'">';
    echo '<article class="'.$cardClass.'">';
    echo '<div class="pcv-card-media"><span class="pcv-card-badge '.$badge.'">'.$label.'</span>';
    echo '<img src="'.pcv_esc($img).'" alt="'.pcv_esc($item['nombre']).'" loading="lazy"></div>';
    echo '<div class="pcv-card-body">';
    echo '<div class="pcv-card-cat">'.$cat.'</div>';
    echo '<h3 class="pcv-card-title">'.pcv_esc($item['nombre']).'</h3>';
    if (!empty($item['resumen'])) {
        echo '<p class="pcv-card-excerpt">'.pcv_esc($item['resumen']).'</p>';
    }
    echo '</div></article></a></div>';
}
?>
<section class="page-banner">
  <div class="container">
    <h1>Catálogo</h1>
    <p>Servicios y productos industriales. Solicita cotización personalizada — sin precios públicos.</p>
  </div>
</section>

<section class="catalog-toolbar">
  <div class="container">
    <form class="catalog-search mb-3" method="get">
      <?php if ($clasSlug !== ''): ?><input type="hidden" name="clasificacion" value="<?= pcv_esc($clasSlug) ?>"><?php endif; ?>
      <?php if ($subSlug !== ''): ?><input type="hidden" name="subtipo" value="<?= pcv_esc($subSlug) ?>"><?php endif; ?>
      <input type="search" name="q" value="<?= pcv_esc($q) ?>" class="form-control" placeholder="Buscar servicio o producto…">
      <button class="btn btn-navy" type="submit"><i class="fas fa-search me-1"></i> Buscar</button>
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

<section class="section pt-4">
  <div class="container">
    <h2 class="catalog-block-title section-title">Servicios</h2>
    <div class="row g-4 mb-5">
      <?php if (!$servicios): ?><div class="col-12"><p class="text-muted mb-0">No hay servicios con este filtro.</p></div><?php endif; ?>
      <?php foreach ($servicios as $it) { pcv_card($it, true); } ?>
    </div>

    <h2 class="catalog-block-title section-title">Productos</h2>
    <div class="row g-4">
      <?php if (!$productos): ?><div class="col-12"><p class="text-muted mb-0">No hay productos con este filtro.</p></div><?php endif; ?>
      <?php foreach ($productos as $it) { pcv_card($it, false); } ?>
    </div>
  </div>
</section>

<section class="cta-band">
  <div class="container">
    <div class="row align-items-center g-3">
      <div class="col-lg-8">
        <h2>¿Necesitas una cotización?</h2>
        <p>Cuéntanos qué pieza, proceso o luminaria necesitas. Te respondemos a la medida.</p>
      </div>
      <div class="col-lg-4 text-lg-end">
        <a href="<?= pcv_url('cotizar.php') ?>" class="btn btn-cta btn-lg">Ir a cotizar <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
