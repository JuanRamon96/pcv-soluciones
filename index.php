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

require __DIR__ . '/includes/header.php';
?>
<section class="hero">
  <svg class="hero-anim" viewBox="0 0 1200 600" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
    <defs>
      <linearGradient id="g1" x1="0" y1="0" x2="1" y2="1">
        <stop offset="0%" stop-color="#104FA0"/><stop offset="100%" stop-color="#0A3D0A"/>
      </linearGradient>
    </defs>
    <!-- engranajes -->
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
    <!-- trayectoria láser / CNC -->
    <path class="laser-line" d="M80 420 L220 420 L220 280 L380 280 L380 360 L560 360 L560 200 L760 200"
          fill="none" stroke="url(#g1)" stroke-width="3"/>
    <circle class="pulse-dot" cx="760" cy="200" r="8" fill="#fff"/>
    <path d="M100 500 H1100" stroke="rgba(255,255,255,.15)" stroke-width="2"/>
    <path d="M140 480 V520 M280 470 V530 M420 460 V540" stroke="rgba(255,255,255,.2)" stroke-width="2"/>
  </svg>
  <div class="container hero-inner py-5">
    <div class="row align-items-center">
      <div class="col-lg-7">
        <p class="text-uppercase small mb-2 opacity-75">Soluciones metal-mecánicas e iluminación</p>
        <h1 class="display-5 mb-3"><?= pcv_esc(PCV_ESLOGAN) ?></h1>
        <p class="lead mb-4 opacity-90">Diseño, fabricación y comercialización de partes, equipos y piezas para la industria. Expertos en maquinados CNC, corte láser, doblez e iluminación industrial.</p>
        <div class="d-flex flex-wrap gap-2">
          <a href="<?= pcv_url('catalogo.php') ?>" class="btn btn-accent btn-lg">Ver catálogo</a>
          <a href="#cotizar" class="btn btn-outline-light btn-lg">Solicitar cotización</a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-5" id="servicios">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-end mb-4 gap-2">
      <div>
        <h2 class="section-title mb-1">Servicios</h2>
        <p class="text-muted mb-0">Experiencia y supervisión en cada proceso, con altos estándares de calidad.</p>
      </div>
      <a href="<?= pcv_url('catalogo.php') ?>" class="btn btn-outline-primary btn-sm">Catálogo completo</a>
    </div>
    <div class="row g-4">
      <?php foreach ($servicios as $s): ?>
      <div class="col-md-6 col-lg-4">
        <a class="text-dark" href="<?= pcv_url('ficha.php?slug=' . urlencode($s['slug'])) ?>">
          <div class="card card-service">
            <img class="card-img-top" src="<?= pcv_esc(pcv_img_producto($s['imagen_principal'])) ?>" alt="<?= pcv_esc($s['nombre']) ?>">
            <div class="card-body">
              <span class="badge badge-tipo mb-2">Servicio</span>
              <h3 class="h5"><?= pcv_esc($s['nombre']) ?></h3>
              <p class="small text-muted mb-0"><?= pcv_esc($s['resumen']) ?></p>
            </div>
          </div>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="py-5 bg-light" id="clasificaciones">
  <div class="container">
    <h2 class="section-title mb-4">Clasificaciones</h2>
    <div class="row g-3">
      <?php
      $icons = ['metal-mecanica'=>'fa-gears','luminaria'=>'fa-lightbulb','refaccionaria'=>'fa-truck'];
      foreach ($clasifs as $c):
        $icon = $icons[$c['slug']] ?? 'fa-industry';
      ?>
      <div class="col-md-4">
        <a href="<?= pcv_url('catalogo.php?clasificacion=' . urlencode($c['slug'])) ?>" class="card h-100 border-0 shadow-sm text-decoration-none text-dark">
          <div class="card-body p-4">
            <div class="mb-3 text-primary"><i class="fas <?= $icon ?> fa-2x" style="color:var(--navy)"></i></div>
            <h3 class="h5"><?= pcv_esc($c['nombre']) ?></h3>
            <p class="small text-muted mb-0">Explorar catálogo →</p>
          </div>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="py-5" id="nosotros">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-lg-6">
        <h2 class="section-title">Nosotros</h2>
        <p>PCV Soluciones Industriales es una empresa especializada en iluminación industrial y en el diseño, fabricación y comercialización de partes, equipos, piezas, productos metálicos y de acrílico para la industria metal mecánica.</p>
        <div class="row g-3 mt-2">
          <div class="col-md-6"><div class="values-card p-3"><strong>Misión</strong><p class="small mb-0 mt-1">Brindar soluciones de calidad inmediatas según las necesidades del cliente, al mejor costo y de forma eficiente.</p></div></div>
          <div class="col-md-6"><div class="values-card p-3"><strong>Visión</strong><p class="small mb-0 mt-1">Ser la principal opción del mercado, invirtiendo en capital humano y nuevas tecnologías.</p></div></div>
        </div>
        <ul class="mt-3 small">
          <li><strong>Respeto</strong> · <strong>Honestidad</strong> · <strong>Compromiso</strong> · <strong>Calidad y trabajo en equipo</strong></li>
        </ul>
      </div>
      <div class="col-lg-6 organigrama-wrap">
        <img src="<?= pcv_asset('img/organigrama.png') ?>" alt="Organigrama PCV">
      </div>
    </div>
  </div>
</section>

<section class="py-5 bg-light" id="cotizar">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <h2 class="section-title text-center mb-2">Cotiza con nosotros</h2>
        <p class="text-center text-muted mb-4">Sin precios públicos: cada proyecto se cotiza a la medida. Te contactamos y también puedes continuar por WhatsApp.</p>
        <form id="formCotizar" action="<?= pcv_url('cotizar.php') ?>" method="post" class="card border-0 shadow-sm p-4">
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Nombre *</label><input required name="nombre" class="form-control"></div>
            <div class="col-md-6"><label class="form-label">Empresa</label><input name="empresa" class="form-control"></div>
            <div class="col-md-6"><label class="form-label">Teléfono *</label><input required name="telefono" id="cotTel" class="form-control" placeholder="33 1144 4743"></div>
            <div class="col-md-6"><label class="form-label">Correo</label><input type="email" name="correo" class="form-control"></div>
            <div class="col-12"><label class="form-label">Mensaje</label><textarea name="mensaje" rows="3" class="form-control" placeholder="Describe tu pieza, material o luminaria…"></textarea></div>
            <div class="col-12 d-grid"><button class="btn btn-green btn-lg" type="submit">Enviar cotización</button></div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<section class="py-5" id="contacto">
  <div class="container text-center">
    <h2 class="section-title">Contacto</h2>
    <p class="mb-1"><a href="mailto:<?= pcv_esc(PCV_EMAIL) ?>"><?= pcv_esc(PCV_EMAIL) ?></a></p>
    <p class="mb-1"><a href="tel:<?= pcv_esc(PCV_TEL1) ?>"><?= pcv_esc(PCV_TEL1) ?></a> · <a href="tel:<?= pcv_esc(PCV_TEL2) ?>"><?= pcv_esc(PCV_TEL2) ?></a></p>
    <p class="mb-0">
      <a href="https://instagram.com/<?= pcv_esc(PCV_IG) ?>" target="_blank" rel="noopener"><i class="fab fa-instagram"></i> @<?= pcv_esc(PCV_IG) ?></a>
      ·
      <a href="<?= pcv_esc(PCV_FB) ?>" target="_blank" rel="noopener"><i class="fab fa-facebook"></i> Facebook</a>
    </p>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
