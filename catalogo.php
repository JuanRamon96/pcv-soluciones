<?php
require_once __DIR__ . '/admin/modelo/config/config.php';
require_once __DIR__ . '/admin/modelo/config/db.php';
$pcv_page = 'catalogo';
$pcv_title = 'Catálogo Industrial (3 Divisiones) | PCV Soluciones';
$db = pcv_db();

$clasSlug = trim($_GET['clasificacion'] ?? '');
$subSlug = trim($_GET['subtipo'] ?? '');
$q = trim($_GET['q'] ?? '');

// Obtener las 3 clasificaciones con conteo de productos
$clasifs = [];
$res = $db->query("SELECT c.*, COUNT(p.id) as total_prods 
  FROM clasificaciones c 
  LEFT JOIN productos p ON p.clasificacion_id = c.id AND p.activo = 1
  WHERE c.activo = 1 
  GROUP BY c.id 
  ORDER BY c.orden, c.id");
while ($r = $res->fetch_assoc()) { 
    $clasifs[] = $r; 
}

$totalAll = 0;
foreach ($clasifs as $c) {
    $totalAll += (int)$c['total_prods'];
}

// Obtener subtipos
$subtipos = [];
$sqlSub = "SELECT s.*, c.slug AS clas_slug, c.nombre as clas_nombre 
           FROM subtipos s 
           INNER JOIN clasificaciones c ON c.id=s.clasificacion_id 
           WHERE s.activo=1";
if ($clasSlug !== '') {
    $cs = $db->real_escape_string($clasSlug);
    $sqlSub .= " AND c.slug='$cs'";
}
$sqlSub .= " ORDER BY s.clasificacion_id, s.orden";
$res = $db->query($sqlSub);
while ($r = $res->fetch_assoc()) { 
    $subtipos[] = $r; 
}

// Query de productos
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
    $where .= " AND (p.nombre LIKE '%$qq%' OR p.resumen LIKE '%$qq%' OR p.descripcion LIKE '%$qq%')";
}

$sql = "SELECT p.*, c.nombre AS clasificacion, c.slug AS clas_slug, s.nombre AS subtipo, s.slug AS sub_slug
        FROM productos p
        INNER JOIN clasificaciones c ON c.id=p.clasificacion_id
        LEFT JOIN subtipos s ON s.id=p.subtipo_id
        WHERE $where
        ORDER BY FIELD(p.tipo,'servicio','producto'), p.orden, p.id";

$items = [];
$res = $db->query($sql);
while ($r = $res->fetch_assoc()) { 
    $items[] = $r; 
}

require __DIR__ . '/header.php';
?>

<!-- Banner Superior de Catálogo -->
<section class="py-5 bg-navy text-white text-center position-relative overflow-hidden" style="background: linear-gradient(135deg, var(--pcv-navy-deep) 0%, var(--pcv-navy) 100%);">
  <div class="container position-relative py-2" style="z-index: 2;">
    <span class="hero-eyebrow mb-2"><i class="fas fa-boxes-stacked me-1"></i> Catálogo de Servicios y Productos</span>
    <h1 class="text-white display-5 fw-bold mb-2">Catálogo Industrial Dividido en 3</h1>
    <p class="lead opacity-75 mx-auto mb-0" style="max-width: 680px; font-size: 1.05rem;">
      Explora nuestras tres divisiones, agrega los productos o servicios que necesites a tu lista y pide tu cotización fácil y rápido.
    </p>
  </div>
</section>

<!-- Toolbar de Navegación por Divisiones y Filtros (Shopify / Elecgreen Style) -->
<div class="catalog-toolbar-wrapper">
  <div class="container">
    <!-- Pestañas de las 3 Divisiones -->
    <div class="division-nav-tabs mb-3">
      <a class="division-nav-btn <?= $clasSlug==='' ? 'active' : '' ?>" href="<?= pcv_url('catalogo.php') ?>">
        <span class="tab-icon-wrap icon-all"><i class="fas fa-border-all"></i></span>
        <span>Todas las Divisiones</span>
        <span class="count-tag"><?= $totalAll ?></span>
      </a>
      <?php 
      $icons = [
        'metal-mecanica' => ['icon' => 'fa-gears', 'class' => 'icon-metal'],
        'luminaria' => ['icon' => 'fa-lightbulb', 'class' => 'icon-led'],
        'refaccionaria' => ['icon' => 'fa-robot', 'class' => 'icon-auto']
      ];
      foreach ($clasifs as $c): 
        $meta = $icons[$c['slug']] ?? ['icon' => 'fa-cube', 'class' => 'icon-default'];
        $isActive = ($clasSlug === $c['slug']);
      ?>
      <a class="division-nav-btn <?= $isActive ? 'active' : '' ?>" href="<?= pcv_url('catalogo.php?clasificacion=' . urlencode($c['slug'])) ?>">
        <span class="tab-icon-wrap <?= $meta['class'] ?>"><i class="fas <?= $meta['icon'] ?>"></i></span>
        <span><?= pcv_esc($c['nombre']) ?></span>
        <span class="count-tag"><?= $c['total_prods'] ?></span>
      </a>
      <?php endforeach; ?>
    </div>

    <!-- Barra de Búsqueda en Vivo -->
    <div class="row g-2 align-items-center justify-content-between">
      <div class="col-md-7 col-lg-6">
        <div class="catalog-search-group">
          <i class="fas fa-magnifying-glass catalog-search-icon"></i>
          <input type="text" id="catalogSearchInput" class="catalog-search-input" 
                 placeholder="Buscar por proceso, pieza, torno CNC, lámpara, etc…" 
                 value="<?= pcv_esc($q) ?>">
        </div>
      </div>
      <div class="col-md-5 col-lg-6 text-md-end">
        <span class="small text-muted fw-semibold">
          Mostrando <strong><?= count($items) ?></strong> resultados
        </span>
      </div>
    </div>

    <!-- Chips de Subtipos de la División -->
    <?php if (!empty($subtipos)): 
      $subIcons = [
        'maquinados-cnc' => 'fa-gear',
        'maquinados-laser' => 'fa-bolt',
        'maquinados-convencional' => 'fa-wrench',
        'fixturas' => 'fa-microchip',
        'led-mexico' => 'fa-lightbulb',
        'tecnoled' => 'fa-sun',
        'diesel' => 'fa-truck-field'
      ];
    ?>
    <div class="subtypes-bar">
      <span class="small text-muted align-self-center me-1 fw-bold"><i class="fas fa-filter me-1 text-primary"></i> Filtrar por línea:</span>
      <a href="<?= pcv_url('catalogo.php' . ($clasSlug !== '' ? '?clasificacion=' . urlencode($clasSlug) : '')) ?>" 
         class="subtype-chip <?= $subSlug === '' ? 'active' : '' ?>">
        <i class="fas fa-layer-group me-1"></i> Todos
      </a>
      <?php foreach ($subtipos as $s): 
        $isSubActive = ($subSlug === $s['slug']);
        $subUrl = pcv_url('catalogo.php?clasificacion=' . urlencode($s['clas_slug']) . '&subtipo=' . urlencode($s['slug']));
        $sIcon = $subIcons[$s['slug']] ?? 'fa-tag';
      ?>
      <a href="<?= $subUrl ?>" class="subtype-chip <?= $isSubActive ? 'active' : '' ?>">
        <i class="fas <?= $sIcon ?> me-1"></i> <?= pcv_esc($s['nombre']) ?>
      </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</div>

<!-- Rejilla de Productos y Servicios (Shopify / Elecgreen Storefront) -->
<section class="section py-5">
  <div class="container">
    <div class="row g-4" id="catalogGrid">
      <?php if (empty($items)): ?>
      <div class="col-12 text-center py-5">
        <div class="text-muted mb-3" style="font-size: 3.5rem;"><i class="fas fa-boxes-packing opacity-50"></i></div>
        <h4 class="fw-bold text-navy">No se encontraron productos o servicios</h4>
        <p class="text-muted">Intenta ajustando el filtro de división o los términos de búsqueda.</p>
        <a href="<?= pcv_url('catalogo.php') ?>" class="btn btn-navy btn-sm mt-2">Restablecer Catálogo</a>
      </div>
      <?php endif; ?>

      <?php foreach ($items as $item): 
        $isService = $item['tipo'] === 'servicio';
        $badgeClass = $isService ? 'badge-service' : 'badge-product';
        $badgeLabel = $isService ? 'Servicio' : 'Producto';
        $imgUrl = pcv_img_producto($item['imagen_principal']);
        $fichaUrl = pcv_url('ficha.php?slug=' . urlencode($item['slug']));
        $waQuoteMsg = rawurlencode("Hola PCV Soluciones, deseo cotizar: " . $item['nombre']);
      ?>
      <div class="col-sm-6 col-lg-4 col-xl-3 catalog-item-col">
        <article class="shopify-card">
          <div class="shopify-media">
            <span class="shopify-badge <?= $badgeClass ?>"><?= $badgeLabel ?></span>
            <a href="<?= pcv_esc($fichaUrl) ?>" class="d-block w-100 h-100">
              <img src="<?= pcv_esc($imgUrl) ?>" alt="<?= pcv_esc($item['nombre']) ?>" loading="lazy">
            </a>
          </div>
          <div class="shopify-body">
            <div class="shopify-cat"><?= pcv_esc($item['clasificacion']) ?><?= $item['subtipo'] ? ' · ' . pcv_esc($item['subtipo']) : '' ?></div>
            <h3 class="shopify-title">
              <a href="<?= pcv_esc($fichaUrl) ?>"><?= pcv_esc($item['nombre']) ?></a>
            </h3>
            <p class="shopify-excerpt"><?= pcv_esc($item['resumen']) ?></p>
            
            <div class="shopify-actions mt-auto">
              <div class="shopify-btn-grid">
                <button type="button" class="btn btn-rfq-add" 
                        data-id="<?= (int)$item['id'] ?>"
                        data-nombre="<?= pcv_esc($item['nombre']) ?>"
                        data-clasificacion="<?= pcv_esc($item['clasificacion']) ?>"
                        data-imagen="<?= pcv_esc($imgUrl) ?>"
                        title="Agregar a mi lista de cotización">
                  <i class="fas fa-plus me-1"></i> A mi lista
                </button>
                <a href="https://wa.me/<?= pcv_esc(PCV_WHATSAPP) ?>?text=<?= $waQuoteMsg ?>" 
                   target="_blank" rel="noopener" class="btn btn-card-wa"
                   title="Cotizar por WhatsApp">
                  <i class="fab fa-whatsapp me-1"></i> Cotizar
                </a>
              </div>
              <a href="<?= pcv_esc($fichaUrl) ?>" class="btn btn-card-ficha">
                <span>Ver Ficha Técnica</span>
                <i class="fas fa-arrow-right ms-1"></i>
              </a>
            </div>
          </div>
        </article>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Contenedor de 'No Resultados' para el filtro instantáneo JS -->
    <div id="catalogNoResults" class="text-center py-5" style="display: none;">
      <div class="text-muted mb-3" style="font-size: 3rem;"><i class="fas fa-search opacity-50"></i></div>
      <h5 class="fw-bold text-navy">Sin coincidencias con tu búsqueda</h5>
      <p class="text-muted small">Intenta con otra palabra clave como "láser", "torno", "lámpara", "rack" o "pallet".</p>
    </div>
  </div>
</section>

<!-- Banda de Cierre CTA -->
<section class="section section-muted py-5 border-top">
  <div class="container text-center py-2" style="max-width: 720px;">
    <span class="section-eyebrow">¿No encuentras la pieza o proceso exacto?</span>
    <h2 class="h3 fw-bold text-navy mb-3">Diseñamos y fabricamos herramentales y piezas sobre plano</h2>
    <p class="text-muted mb-4">
      "Creamos la figura más difícil de la industria." Envíanos tu plano CAD o muestra física y nuestro departamento de ingeniería te brindará asesoría técnica.
    </p>
    <div class="d-flex justify-content-center gap-3 flex-wrap">
      <a href="<?= pcv_url('cotizar.php') ?>" class="btn btn-navy">
        <i class="fas fa-file-pen me-1"></i> Solicitar Cotización Especial
      </a>
      <a href="https://wa.me/<?= pcv_esc(PCV_WHATSAPP) ?>?text=<?= rawurlencode('Hola PCV Soluciones, tengo un plano especial que requiero cotizar.') ?>" 
         target="_blank" rel="noopener" class="btn btn-whatsapp">
        <i class="fab fa-whatsapp me-1"></i> Enviar Plano por WhatsApp
      </a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/footer.php'; ?>
