<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
$pcv_page = 'home';
$pcv_title = 'PCV Soluciones Industriales | ' . PCV_ESLOGAN;
$db = pcv_db();

$servicios = [];
$res = $db->query("SELECT p.*, c.nombre AS clasificacion FROM productos p
  INNER JOIN clasificaciones c ON c.id=p.clasificacion_id
  WHERE p.activo=1 AND p.tipo='servicio' ORDER BY p.destacado DESC, p.orden, p.id LIMIT 6");
while ($row = $res->fetch_assoc()) { $servicios[] = $row; }

$clasifs = [];
$res = $db->query("SELECT * FROM clasificaciones WHERE activo=1 ORDER BY orden");
while ($row = $res->fetch_assoc()) { $clasifs[] = $row; }

$tel1_fmt = preg_replace('/(\d{2})(\d{4})(\d{4})/', '$1 $2 $3', PCV_TEL1);

require __DIR__ . '/includes/header.php';
?>
<section class="hero">
  <div class="hero-bg" aria-hidden="true"></div>
  <div class="hero-diagonal" aria-hidden="true"></div>
  <svg class="hero-anim" viewBox="0 0 1200 600" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
    <defs>
      <linearGradient id="g1" x1="0" y1="0" x2="1" y2="1">
        <stop offset="0%" stop-color="#104FA0"/><stop offset="100%" stop-color="#0A3D0A"/>
      </linearGradient>
    </defs>
    <g class="gear" transform="translate(980 120)">
      <circle r="70" fill="none" stroke="#fff" stroke-width="6"/>
      <circle r="28" fill="none" stroke="#fff" stroke-width="4"/>
      <?php for ($i=0;$i<12;$i++): $a=$i*30; ?>
      <rect x="-8" y="-88" width="16" height="22" fill="#fff" transform="rotate(<?= $a ?>)"/>
      <?php endfor; ?>
    </g>
    <g class="gear slow" transform="translate(1080 230)">
      <circle r="46" fill="none" stroke="#fff" stroke-width="5"/>
      <circle r="16" fill="none" stroke="#fff" stroke-width="3"/>
      <?php for ($i=0;$i<10;$i++): $a=$i*36; ?>
      <rect x="-6" y="-58" width="12" height="16" fill="#fff" transform="rotate(<?= $a ?>)"/>
      <?php endfor; ?>
    </g>
    <path class="laser-line" d="M80 420 L220 420 L220 280 L380 280 L380 360 L560 360 L560 200 L760 200"
          fill="none" stroke="url(#g1)" stroke-width="3"/>
    <circle class="pulse-dot" cx="760" cy="200" r="8" fill="#fff"/>
  </svg>
  <div class="container hero-inner">
    <div class="row align-items-center">
      <div class="col-lg-7 col-xl-6">
        <p class="hero-kicker">Soluciones metal-mecánicas e iluminación</p>
        <h1><?= pcv_esc(PCV_ESLOGAN) ?></h1>
        <p class="lead">Diseño, fabricación y comercialización de partes, equipos y piezas para la industria. Expertos en maquinados CNC, corte láser, doblez e iluminación industrial.</p>
        <div class="hero-actions">
          <a href="<?= pcv_url('cotizar.php') ?>" class="btn btn-cta btn-lg">Cotizar ahora <i class="fas fa-arrow-right"></i></a>
          <a href="<?= pcv_url('catalogo.php') ?>" class="btn btn-outline-light">Ver catálogo</a>
          <a class="hero-phone" href="tel:<?= pcv_esc(PCV_TEL1) ?>">
            <span class="phone-ico"><i class="fas fa-phone"></i></span>
            <span><?= pcv_esc($tel1_fmt) ?></span>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section" id="servicios">
  <div class="container">
    <div class="section-head d-flex flex-wrap justify-content-between align-items-end gap-3">
      <div>
        <span class="section-eyebrow">Lo que hacemos</span>
        <h2 class="section-title">Servicios industriales</h2>
        <p class="section-sub">Experiencia y supervisión en cada proceso, con altos estándares de calidad.</p>
      </div>
      <a href="<?= pcv_url('catalogo.php') ?>" class="btn btn-outline-navy">Catálogo completo</a>
    </div>
    <div class="row g-4">
      <?php foreach ($servicios as $s): ?>
      <div class="col-md-6 col-lg-4">
        <a class="pcv-card-link" href="<?= pcv_url('ficha.php?slug=' . urlencode($s['slug'])) ?>">
          <article class="pcv-card pcv-card-service">
            <div class="pcv-card-media">
              <span class="pcv-card-badge badge-tipo">Servicio</span>
              <img src="<?= pcv_esc(pcv_img_producto($s['imagen_principal'])) ?>" alt="<?= pcv_esc($s['nombre']) ?>" loading="lazy">
            </div>
            <div class="pcv-card-body">
              <div class="pcv-card-cat"><?= pcv_esc($s['clasificacion']) ?></div>
              <h3 class="pcv-card-title"><?= pcv_esc($s['nombre']) ?></h3>
              <p class="pcv-card-excerpt"><?= pcv_esc($s['resumen']) ?></p>
            </div>
          </article>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section-muted" id="clasificaciones">
  <div class="container">
    <div class="section-head">
      <span class="section-eyebrow">Especialidades</span>
      <h2 class="section-title">Clasificaciones</h2>
      <p class="section-sub">Explora nuestro catálogo por línea de especialidad.</p>
    </div>
    <div class="row g-4">
      <?php
      $icons = ['metal-mecanica'=>'fa-gears','luminaria'=>'fa-lightbulb','refaccionaria'=>'fa-truck'];
      foreach ($clasifs as $c):
        $icon = $icons[$c['slug']] ?? 'fa-industry';
      ?>
      <div class="col-md-4">
        <a href="<?= pcv_url('catalogo.php?clasificacion=' . urlencode($c['slug'])) ?>" class="clasif-card">
          <div class="ico"><i class="fas <?= $icon ?>"></i></div>
          <h3><?= pcv_esc($c['nombre']) ?></h3>
          <span class="linkish">Explorar catálogo →</span>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section" id="nosotros">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6">
        <span class="section-eyebrow">Quiénes somos</span>
        <h2 class="section-title">Nosotros</h2>
        <p>PCV Soluciones Industriales es una empresa especializada en iluminación industrial y en el diseño, fabricación y comercialización de partes, equipos, piezas, productos metálicos y de acrílico para la industria metal mecánica.</p>
        <div class="row g-3 mt-1">
          <div class="col-md-6"><div class="values-card p-3"><strong class="text-navy">Misión</strong><p class="small mb-0 mt-1 text-muted">Brindar soluciones de calidad inmediatas según las necesidades del cliente, al mejor costo y de forma eficiente.</p></div></div>
          <div class="col-md-6"><div class="values-card p-3"><strong class="text-navy">Visión</strong><p class="small mb-0 mt-1 text-muted">Ser la principal opción del mercado, invirtiendo en capital humano y nuevas tecnologías.</p></div></div>
        </div>
        <p class="mt-3 mb-0 small text-muted"><strong class="text-navy">Valores:</strong> Respeto · Honestidad · Compromiso · Calidad y trabajo en equipo</p>
      </div>
      <div class="col-lg-6 organigrama-wrap">
        <img src="<?= pcv_asset('img/organigrama.png') ?>" alt="Organigrama PCV" loading="lazy">
      </div>
    </div>
  </div>
</section>

<section class="cta-band">
  <div class="container">
    <div class="row align-items-center g-3">
      <div class="col-lg-8">
        <h2>¿Listo para cotizar tu proyecto?</h2>
        <p>Sin precios públicos: cada pieza o luminaria se cotiza a la medida. Te contactamos y puedes continuar por WhatsApp.</p>
      </div>
      <div class="col-lg-4 text-lg-end">
        <a href="<?= pcv_url('cotizar.php') ?>" class="btn btn-cta btn-lg">Solicitar cotización <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>
  </div>
</section>

<section class="section" id="contacto">
  <div class="container">
    <div class="section-head text-center mx-auto" style="max-width:40rem">
      <span class="section-eyebrow">Hablemos</span>
      <h2 class="section-title">Contacto</h2>
      <p class="section-sub mx-auto">Estamos listos para atender tu requerimiento industrial.</p>
    </div>
    <div class="row g-4 contact-strip">
      <div class="col-md-4">
        <div class="contact-tile">
          <div class="ico"><i class="fas fa-envelope"></i></div>
          <div>
            <strong>Correo</strong>
            <a href="mailto:<?= pcv_esc(PCV_EMAIL) ?>"><?= pcv_esc(PCV_EMAIL) ?></a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="contact-tile">
          <div class="ico"><i class="fas fa-phone"></i></div>
          <div>
            <strong>Teléfonos</strong>
            <div><a href="tel:<?= pcv_esc(PCV_TEL1) ?>"><?= pcv_esc($tel1_fmt) ?></a></div>
            <div><a href="tel:<?= pcv_esc(PCV_TEL2) ?>"><?= pcv_esc(preg_replace('/(\d{2})(\d{4})(\d{4})/', '$1 $2 $3', PCV_TEL2)) ?></a></div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="contact-tile">
          <div class="ico"><i class="fas fa-share-nodes"></i></div>
          <div>
            <strong>Redes</strong>
            <div><a href="https://instagram.com/<?= pcv_esc(PCV_IG) ?>" target="_blank" rel="noopener">@<?= pcv_esc(PCV_IG) ?></a></div>
            <div><a href="<?= pcv_esc(PCV_FB) ?>" target="_blank" rel="noopener">Facebook</a></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
