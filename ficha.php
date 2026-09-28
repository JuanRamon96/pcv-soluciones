<?php
require_once __DIR__ . '/admin/modelo/config/config.php';
require_once __DIR__ . '/admin/modelo/config/db.php';
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
    $pcv_title = 'No encontrado | PCV Soluciones';
    require __DIR__ . '/header.php';
    echo '<section class="py-5 text-center"><div class="container py-5"><h2>Producto o Servicio no encontrado</h2><a class="btn btn-navy mt-3" href="'.pcv_url('catalogo.php').'">Volver al catálogo</a></div></section>';
    require __DIR__ . '/footer.php';
    exit;
}

$pcv_title = $item['nombre'] . ' | PCV Soluciones Industriales';

$galeria = [];
$gid = (int)$item['id'];
if (!empty($item['imagen_principal'])) {
    $galeria[] = $item['imagen_principal'];
}
$resImg = $db->query("SELECT archivo FROM producto_imagenes WHERE producto_id=$gid ORDER BY orden, id");
while ($r = $resImg->fetch_assoc()) { 
    if (!in_array($r['archivo'], $galeria)) {
        $galeria[] = $r['archivo']; 
    }
}
$main = !empty($item['imagen_principal']) ? $item['imagen_principal'] : ($galeria[0] ?? null);
$isService = $item['tipo'] === 'servicio';

// Productos y servicios relacionados de la misma clasificación
$relacionados = [];
$cid = (int)$item['clasificacion_id'];
$resRel = $db->query("SELECT * FROM productos WHERE clasificacion_id=$cid AND id != $gid AND activo=1 LIMIT 3");
while ($r = $resRel->fetch_assoc()) {
    $relacionados[] = $r;
}

require __DIR__ . '/header.php';
?>

<!-- Breadcrumbs y Título Superior -->
<section class="py-4 bg-light border-bottom">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb small mb-0">
        <li class="breadcrumb-item"><a href="<?= pcv_url('') ?>" class="text-muted">Inicio</a></li>
        <li class="breadcrumb-item"><a href="<?= pcv_url('catalogo.php') ?>" class="text-muted">Catálogo</a></li>
        <li class="breadcrumb-item"><a href="<?= pcv_url('catalogo.php?clasificacion=' . urlencode($item['clas_slug'])) ?>" class="text-muted"><?= pcv_esc($item['clasificacion']) ?></a></li>
        <li class="breadcrumb-item active text-navy fw-bold" aria-current="page"><?= pcv_esc($item['nombre']) ?></li>
      </ol>
    </nav>
  </div>
</section>

<!-- Ficha Principal de Producto o Servicio -->
<section class="section py-5">
  <div class="container">
    <div class="row g-5">
      <!-- Columna Izquierda: Galería de Imágenes en Carrusel con Botones -->
      <div class="col-lg-6">
        <div class="ficha-carousel-card">
          <div id="fichaCarousel" class="carousel slide" data-bs-ride="false" data-bs-touch="true" data-bs-interval="false">
            
            <!-- Slides del Carrusel -->
            <div class="carousel-inner ficha-carousel-inner">
              <?php foreach ($galeria as $idx => $g): 
                $imgSrc = pcv_img_producto($g);
              ?>
              <div class="carousel-item <?= $idx === 0 ? 'active' : '' ?>">
                <div class="ficha-slide-box">
                  <img src="<?= pcv_esc($imgSrc) ?>" 
                       alt="<?= pcv_esc($item['nombre']) ?> - Imagen <?= $idx + 1 ?>" 
                       class="ficha-carousel-img"
                       loading="<?= $idx === 0 ? 'eager' : 'lazy' ?>">
                </div>
              </div>
              <?php endforeach; ?>
            </div>

            <?php if (count($galeria) > 1): ?>
            <!-- Botones Prev / Next con iconos de flecha para que el cliente cambie de imagen -->
            <button class="carousel-control-prev ficha-carousel-arrow prev" type="button" data-bs-target="#fichaCarousel" data-bs-slide="prev" aria-label="Imagen anterior" title="Imagen anterior">
              <span class="ficha-arrow-circle"><i class="fas fa-chevron-left"></i></span>
            </button>
            <button class="carousel-control-next ficha-carousel-arrow next" type="button" data-bs-target="#fichaCarousel" data-bs-slide="next" aria-label="Imagen siguiente" title="Imagen siguiente">
              <span class="ficha-arrow-circle"><i class="fas fa-chevron-right"></i></span>
            </button>

            <!-- Badge indicador de foto actual (ej: 1 / 4) -->
            <div class="ficha-carousel-badge">
              <i class="fas fa-camera me-1"></i> <span id="fichaCurrentSlide">1</span> / <?= count($galeria) ?>
            </div>
            <?php endif; ?>

          </div>

          <?php if (count($galeria) > 1): ?>
          <!-- Carrusel de miniaturas / Botones directos por imagen -->
          <div class="ficha-thumbs-container mt-3">
            <div class="ficha-thumbs-scroll d-flex gap-2">
              <?php foreach ($galeria as $idx => $g): 
                $imgSrc = pcv_img_producto($g);
              ?>
              <button type="button" class="ficha-thumb-btn <?= $idx === 0 ? 'active' : '' ?>" 
                      data-bs-target="#fichaCarousel" 
                      data-bs-slide-to="<?= $idx ?>"
                      aria-label="Ver imagen <?= $idx + 1 ?>"
                      title="Ver imagen <?= $idx + 1 ?>">
                <img src="<?= pcv_esc($imgSrc) ?>" alt="Miniatura <?= $idx + 1 ?>">
              </button>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Columna Derecha: Información Técnica y Botones de Cotización -->
      <div class="col-lg-6">
        <div class="d-flex align-items-center gap-2 mb-2">
          <span class="shopify-badge <?= $isService ? 'badge-service' : 'badge-product' ?> position-static">
            <?= $isService ? 'Servicio Industrial' : 'Producto / Equipo' ?>
          </span>
          <span class="text-muted small fw-bold">
            <?= pcv_esc($item['clasificacion']) ?><?= $item['subtipo'] ? ' · ' . pcv_esc($item['subtipo']) : '' ?>
          </span>
        </div>

        <h1 class="h2 fw-bold text-navy mb-3"><?= pcv_esc($item['nombre']) ?></h1>

        <div class="p-3 bg-light rounded border mb-4">
          <div class="text-navy fw-semibold small mb-1"><i class="fas fa-certificate text-success me-1"></i> Garantía y Normativa PCV</div>
          <p class="small text-muted mb-0">Fabricación bajo tolerancias estrictas, materiales certificados y supervisión de ingeniería en cada etapa.</p>
        </div>

        <div class="mb-4 text-muted" style="white-space: pre-line; line-height: 1.7;">
          <?= pcv_esc($item['descripcion'] ?: $item['resumen']) ?>
        </div>

        <!-- Tabla de Especificaciones Rápidas -->
        <table class="ficha-specs-table mb-4">
          <tbody>
            <tr>
              <th>Modalidad</th>
              <td><?= $isService ? 'Servicio especializado bajo especificación o plano' : 'Fabricación y suministro a la medida' ?></td>
            </tr>
            <tr>
              <th>División</th>
              <td><?= pcv_esc($item['clasificacion']) ?></td>
            </tr>
            <?php if ($item['subtipo']): ?>
            <tr>
              <th>Subtipo</th>
              <td><?= pcv_esc($item['subtipo']) ?></td>
            </tr>
            <?php endif; ?>
            <tr>
              <th>Cotización</th>
              <td>Sin precios públicos genéricos · Presupuesto formal en menos de 24 horas</td>
            </tr>
          </tbody>
        </table>

        <!-- Botones de Acción (Shopify Style RFQ) -->
        <div class="d-flex flex-wrap gap-3 mb-4">
          <button type="button" class="btn btn-green btn-lg btn-rfq-add py-3 px-4 flex-fill"
                  data-id="<?= (int)$item['id'] ?>"
                  data-nombre="<?= pcv_esc($item['nombre']) ?>"
                  data-clasificacion="<?= pcv_esc($item['clasificacion']) ?>"
                  data-imagen="<?= pcv_esc(pcv_img_producto($main)) ?>">
            <i class="fas fa-plus me-1"></i> A mi lista de cotización
          </button>
          
          <a class="btn btn-whatsapp btn-lg py-3 px-4 flex-fill" target="_blank" rel="noopener"
             href="https://wa.me/<?= pcv_esc(PCV_WHATSAPP) ?>?text=<?= rawurlencode('Hola PCV Soluciones, me interesa cotizar de inmediato: ' . $item['nombre']) ?>">
            <i class="fab fa-whatsapp me-1"></i> Cotizar en WhatsApp
          </a>
        </div>
      </div>
    </div>

    <!-- Formulario de Cotización Integrado en la Ficha -->
    <div class="row mt-5 pt-4 border-top">
      <div class="col-lg-8">
        <span class="section-eyebrow">Solicitud Rápida</span>
        <h3 class="h4 fw-bold text-navy mb-3">Pedir cotización de este producto o servicio</h3>
        <form id="formCotizar" action="<?= pcv_url('cotizar.php') ?>" method="post" class="card border p-4 shadow-sm">
          <input type="hidden" name="producto_id" value="<?= (int)$item['id'] ?>">
          <input type="hidden" name="origen" value="ficha_tecnica">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label small fw-bold">Tu Nombre *</label>
              <input required name="nombre" class="form-control" placeholder="Nombre y apellido">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Empresa</label>
              <input name="empresa" class="form-control" placeholder="Empresa o planta">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Teléfono / WhatsApp *</label>
              <input required name="telefono" type="tel" class="form-control" placeholder="33 1144 4743">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Correo Electrónico</label>
              <input type="email" name="correo" class="form-control" placeholder="nombre@correo.com">
            </div>
            <div class="col-12">
              <label class="form-label small fw-bold">Detalles de la solicitud</label>
              <textarea name="mensaje" class="form-control" rows="3">Me interesa cotizar: <?= pcv_esc($item['nombre']) ?>. Requiero información sobre tolerancias, tiempos de entrega y especificaciones.</textarea>
            </div>
            <div class="col-12 mt-3">
              <button type="submit" class="btn btn-navy btn-lg py-3 px-4">
                <i class="fas fa-paper-plane me-2"></i> Enviar y recibir atención técnica
              </button>
            </div>
          </div>
        </form>
      </div>

      <div class="col-lg-4 mt-4 mt-lg-0">
        <div class="card p-4 border bg-light h-100">
          <h5 class="fw-bold text-navy mb-3"><i class="fas fa-headset me-2 text-primary"></i> Asesoría de Ingeniería</h5>
          <p class="small text-muted mb-3">Si tienes dudas sobre el maquinado de una aleación particular, luxes requeridos o diseño de fixtura, contáctanos directamente:</p>
          <ul class="list-unstyled small mb-0">
            <li class="mb-2"><i class="fas fa-phone-alt me-2 text-navy"></i> (33) 1144 4743</li>
            <li class="mb-2"><i class="fas fa-phone-alt me-2 text-navy"></i> (33) 1043 8300</li>
            <li class="mb-2"><i class="fas fa-envelope me-2 text-navy"></i> <?= pcv_esc(PCV_EMAIL) ?></li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Productos y Servicios Relacionados -->
    <?php if (!empty($relacionados)): ?>
    <div class="mt-5 pt-4 border-top">
      <h3 class="h4 fw-bold text-navy mb-4">Más productos y servicios en <?= pcv_esc($item['clasificacion']) ?></h3>
      <div class="row g-4">
        <?php foreach ($relacionados as $rel): 
          $relImg = pcv_img_producto($rel['imagen_principal']);
          $relFicha = pcv_url('ficha.php?slug=' . urlencode($rel['slug']));
        ?>
        <div class="col-md-4">
          <div class="card h-100 border p-3 shadow-sm">
            <div style="aspect-ratio: 16/9; overflow: hidden; border-radius: 4px; margin-bottom: 0.75rem;">
              <img src="<?= pcv_esc($relImg) ?>" alt="<?= pcv_esc($rel['nombre']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <h6 class="fw-bold text-navy mb-2"><?= pcv_esc($rel['nombre']) ?></h6>
            <p class="small text-muted mb-3"><?= pcv_esc($rel['resumen']) ?></p>
            <a href="<?= pcv_esc($relFicha) ?>" class="btn btn-sm btn-outline-navy mt-auto">Ver Ficha →</a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var carouselEl = document.getElementById('fichaCarousel');
  if (carouselEl && typeof bootstrap !== 'undefined') {
    var myCarousel = bootstrap.Carousel.getOrCreateInstance(carouselEl, {
      interval: false,
      wrap: true
    });
    var counterEl = document.getElementById('fichaCurrentSlide');
    var thumbBtns = document.querySelectorAll('.ficha-thumb-btn');

    carouselEl.addEventListener('slide.bs.carousel', function (e) {
      if (counterEl) {
        counterEl.textContent = (e.to + 1);
      }
      thumbBtns.forEach(function (btn, idx) {
        if (idx === e.to) {
          btn.classList.add('active');
          btn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        } else {
          btn.classList.remove('active');
        }
      });
    });
  }
});
</script>

<?php require __DIR__ . '/footer.php'; ?>
