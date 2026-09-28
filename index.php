<?php
require_once __DIR__ . '/admin/modelo/config/config.php';
require_once __DIR__ . '/admin/modelo/config/db.php';
$pcv_page = 'home';
$pcv_title = 'PCV Soluciones Industriales | ' . PCV_ESLOGAN;
$db = pcv_db();

// Obtener todas las divisiones con todos sus productos para los carruseles por categoría
$divisiones_catalogo = [];
$total_prods_count = 0;
$resClas = $db->query("SELECT * FROM clasificaciones WHERE activo=1 ORDER BY orden, id");
while ($c = $resClas->fetch_assoc()) {
    $cId = (int)$c['id'];
    $sqlP = "SELECT p.*, c.nombre AS clasificacion, c.slug AS clas_slug, s.nombre AS subtipo
             FROM productos p
             INNER JOIN clasificaciones c ON c.id=p.clasificacion_id
             LEFT JOIN subtipos s ON s.id=p.subtipo_id
             WHERE p.activo=1 AND p.clasificacion_id = $cId
             ORDER BY FIELD(p.tipo,'servicio','producto'), p.orden, p.id";
    $resP = $db->query($sqlP);
    $prods = [];
    while ($p = $resP->fetch_assoc()) {
        $prods[] = $p;
        $total_prods_count++;
    }
    $c['productos'] = $prods;
    $divisiones_catalogo[] = $c;
}

$tel1_fmt = preg_replace('/(\d{2})(\d{4})(\d{4})/', '$1 $2 $3', PCV_TEL1);

require __DIR__ . '/header.php';
?>

<!-- ==========================================================================
     HERO SECTION CRIPAR SPLIT (Video Persona Soldando + Corte Diagonal)
     Inspirado fielmente en la referencia Cripar con paleta oficial PCV
     ========================================================================== -->
<section class="hero-cripar-split">
  <div class="hero-split-watermark"></div>
  <div class="container hero-split-container">
    <div class="row align-items-center">
      <!-- Columna Izquierda: Información de alto impacto -->
      <div class="col-lg-6 hero-split-left">
        <div class="hero-eyebrow-light">
          <span class="dot-green"></span>
          <span>Soluciones Industriales & Metal Mecánica</span>
        </div>
        <h1 class="hero-split-title">
          Creamos la figura <span class="text-green">más difícil</span> de la industria
        </h1>
        <p class="hero-split-desc">
          Maquinados CNC de alta precisión, corte láser 2D y tubo, soldadura especializada TIG/MIG/Láser, iluminación industrial y automatización. Tolerancias micrométricas y entregas garantizadas para no parar tu planta.
        </p>
        <div class="hero-split-actions d-flex flex-wrap align-items-center gap-3">
          <a href="<?= pcv_url('catalogo.php') ?>" class="btn-hero-cta">
            <span>Ver Catálogo (3 Divisiones)</span>
            <i class="fas fa-arrow-up-right-from-square ms-1"></i>
          </a>
          <a href="tel:<?= pcv_esc(PCV_TEL1) ?>" class="hero-phone-card">
            <div class="phone-icon-bubble">
              <i class="fas fa-phone-alt"></i>
            </div>
            <div class="phone-meta">
              <span class="phone-label">Línea Directa / Planta</span>
              <strong class="phone-number"><?= pcv_esc($tel1_fmt) ?></strong>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Columna Derecha con Corte Diagonal y Video Real de Persona Soldando -->
  <div class="hero-split-video-wrap">
    <!-- Barras Diagonales de Acento (Cripar Style) -->
    <div class="hero-diagonal-bar-main"></div>
    <div class="hero-diagonal-bar-secondary"></div>

    <!-- Video en Loop de Persona Soldando con Máquinas CNC al fondo (Optimizado < 1.2MB) -->
    <video class="hero-welding-video" autoplay muted loop playsinline preload="auto" poster="<?= pcv_asset('video/welding-poster.jpg?v=' . filemtime(PCV_ROOT . '/assets/video/welding-poster.jpg')) ?>">
      <source src="<?= pcv_asset('video/welding-hero.webm?v=' . filemtime(PCV_ROOT . '/assets/video/welding-hero.webm')) ?>" type="video/webm">
      <source src="<?= pcv_asset('video/welding-hero.mp4?v=' . filemtime(PCV_ROOT . '/assets/video/welding-hero.mp4')) ?>" type="video/mp4">
      Tu navegador no soporta reproducción de video HTML5.
    </video>
    <div class="hero-video-overlay"></div>

    <!-- Insignia Flotante sobre el Video -->
    <div class="hero-video-badge">
      <div class="badge-dot-live"></div>
      <div>
        <strong>Soldadura & Procesos Especiales</strong>
        <small>Acero Inoxidable, Aluminio y Carbón</small>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     STATS RIBBON (Cripar Stats Strip)
     ========================================================================== -->
<div class="container">
  <div class="stats-strip">
    <div class="row g-3 text-center text-md-start">
      <div class="col-6 col-md-3">
        <div class="stat-item justify-content-center justify-content-md-start">
          <div class="stat-icon"><i class="fas fa-award text-primary"></i></div>
          <div>
            <div class="stat-num">+15</div>
            <p class="stat-label">Años de Trayectoria</p>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-item justify-content-center justify-content-md-start">
          <div class="stat-icon"><i class="fas fa-gears text-success"></i></div>
          <div>
            <div class="stat-num">100%</div>
            <p class="stat-label">Precisión y Calidad</p>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-item justify-content-center justify-content-md-start">
          <div class="stat-icon"><i class="fas fa-layer-group text-warning"></i></div>
          <div>
            <div class="stat-num">3</div>
            <p class="stat-label">Divisiones Especializadas</p>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-item justify-content-center justify-content-md-start">
          <div class="stat-icon"><i class="fas fa-bolt text-danger"></i></div>
          <div>
            <div class="stat-num">24/7</div>
            <p class="stat-label">Respuesta a Cotizaciones</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ==========================================================================
     LAS 3 DIVISIONES PRINCIPALES (Catálogo dividido en 3)
     ========================================================================== -->
<section class="section" id="divisiones">
  <div class="container">
    <div class="section-head text-center mx-auto" style="max-width: 750px;">
      <span class="section-eyebrow">Capacidades de Planta</span>
      <h2 class="section-title">Nuestras 3 Divisiones Especializadas</h2>
      <p class="section-sub mx-auto">
        Una solución integral para la manufactura, ensamble, automatización e infraestructura industrial.
      </p>
    </div>

    <div class="row g-4">
      <!-- División 1: Metal Mecánica -->
      <div class="col-lg-4">
        <article class="division-card">
          <div class="division-media">
            <span class="division-badge"><i class="fas fa-gear me-1"></i> División 01</span>
            <img src="<?= pcv_img_producto('maquinados-cnc.png') ?>" alt="Metal Mecánica y Procesos">
          </div>
          <div class="division-body">
            <h3 class="division-title">Metal Mecánica y Procesos</h3>
            <p class="division-desc">
              Maquinados en Torno CNC y Centros de Maquinado vertical, corte láser 2D y tubo, corte por chorro de agua, soldadura láser para materiales especiales y acabados superficiales de alto nivel.
            </p>
            <div class="division-tags">
              <span class="division-tag">Torno CNC</span>
              <span class="division-tag">Corte Láser 2D y Tubo</span>
              <span class="division-tag">Soldadura Láser</span>
              <span class="division-tag">Waterjet</span>
              <span class="division-tag">Acabados</span>
            </div>
            <a href="<?= pcv_url('catalogo.php?clasificacion=metal-mecanica') ?>" class="btn btn-outline-navy mt-auto">
              Ver Catálogo Metal Mecánica →
            </a>
          </div>
        </article>
      </div>

      <!-- División 2: Iluminación Industrial -->
      <div class="col-lg-4">
        <article class="division-card">
          <div class="division-media">
            <span class="division-badge" style="background: #D97706;"><i class="fas fa-lightbulb me-1"></i> División 02</span>
            <img src="<?= pcv_img_producto('iluminacion-industrial.png') ?>" alt="Iluminación Industrial LED">
          </div>
          <div class="division-body">
            <h3 class="division-title">Iluminación Industrial LED</h3>
            <p class="division-desc">
              Estudios de ingeniería de iluminación computarizados para garantizar los luxes normativos (NOM-025-STPS), luminarias High Bay para naves (TORINO-200, RF-300W) y suburbanas viales (COB-OVAL AP-130W).
            </p>
            <div class="division-tags">
              <span class="division-tag">Estudios de Luxes</span>
              <span class="division-tag">Campanas High Bay</span>
              <span class="division-tag">Viales IP65</span>
              <span class="division-tag">Torino-200</span>
              <span class="division-tag">Ahorro Energético</span>
            </div>
            <a href="<?= pcv_url('catalogo.php?clasificacion=luminaria') ?>" class="btn btn-outline-navy mt-auto">
              Ver Catálogo Iluminación →
            </a>
          </div>
        </article>
      </div>

      <!-- División 3: Automatización y Mobiliario -->
      <div class="col-lg-4">
        <article class="division-card">
          <div class="division-media">
            <span class="division-badge" style="background: var(--pcv-green);"><i class="fas fa-industry me-1"></i> División 03</span>
            <img src="<?= pcv_img_producto('mesas-trabajo.png') ?>" alt="Automatización y Mobiliario">
          </div>
          <div class="division-body">
            <h3 class="division-title">Automatización y Mobiliario</h3>
            <p class="division-desc">
              Mesas y estaciones ergonómicas de trabajo, racks a la medida para manufactura, sistemas de transporte conveyor, hornos, emplayadoras, fixturas Go/No-Go y pallets para ensamble e inspección de PCB.
            </p>
            <div class="division-tags">
              <span class="division-tag">Estaciones de Trabajo</span>
              <span class="division-tag">Racks a Medida</span>
              <span class="division-tag">Conveyors</span>
              <span class="division-tag">Fixturas Go/No Go</span>
              <span class="division-tag">Pallets PCB</span>
            </div>
            <a href="<?= pcv_url('catalogo.php?clasificacion=refaccionaria') ?>" class="btn btn-outline-navy mt-auto">
              Ver Catálogo Automatización →
            </a>
          </div>
        </article>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     CATÁLOGO COMPLETO POR DIVISIÓN (Carruseles Continuos con Autoplay y Flechas)
     Un carrusel interactivo por cada categoría industrial con todos sus productos
     ========================================================================== -->
<section class="section section-muted" id="destacados">
  <div class="container">
    <div class="section-head d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
      <div>
        <span class="section-eyebrow"><i class="fas fa-arrows-spin me-1"></i> Catálogo Interactivo en Movimiento</span>
        <h2 class="section-title">Productos y Servicios por División</h2>
        <p class="section-sub">Carruseles continuos con avance automático y flechas de navegación: cotiza directamente cualquier requerimiento de planta.</p>
      </div>
      <a href="<?= pcv_url('catalogo.php') ?>" class="btn btn-navy">
        <i class="fas fa-boxes-stacked me-1"></i> Ver Catálogo Completo (<?= $total_prods_count ?> productos y servicios)
      </a>
    </div>

    <!-- Pestañas de Salto Rápido a Divisiones -->
    <div class="d-flex flex-wrap gap-2 mb-4">
      <?php foreach ($divisiones_catalogo as $idx => $div): 
        $catIcons = [
          'metal-mecanica' => 'fa-gears',
          'luminaria' => 'fa-lightbulb',
          'refaccionaria' => 'fa-robot'
        ];
        $catIcon = $catIcons[$div['slug']] ?? 'fa-cube';
      ?>
      <a href="#carrusel-<?= pcv_esc($div['slug']) ?>" class="btn btn-white border shadow-sm d-inline-flex align-items-center gap-2 fw-semibold px-3 py-2 rounded-3 text-navy">
        <i class="fas <?= $catIcon ?> text-success"></i>
        <span><?= pcv_esc($div['nombre']) ?></span>
        <span class="badge bg-light text-secondary border"><?= count($div['productos']) ?></span>
      </a>
      <?php endforeach; ?>
    </div>

    <!-- Carruseles por cada una de las 3 Divisiones -->
    <?php 
    $metaIcons = [
      'metal-mecanica' => ['icon' => 'fa-gears', 'class' => 'icon-metal-mecanica', 'num' => '01', 'sub' => 'Torno CNC, Centros de Maquinado, Corte Láser 2D/Tubo, Soldadura de Precisión y Waterjet.'],
      'luminaria' => ['icon' => 'fa-lightbulb', 'class' => 'icon-luminaria', 'num' => '02', 'sub' => 'Estudios de Iluminación y Luxes (NOM-025-STPS), Campanas LED High-Bay y Luminarias Viales.'],
      'refaccionaria' => ['icon' => 'fa-robot', 'class' => 'icon-refaccionaria', 'num' => '03', 'sub' => 'Mesas ergonómicas de trabajo, Racks industriales, Conveyors, Fixturas Go/No-Go y Pallets PCB.']
    ];
    foreach ($divisiones_catalogo as $div): 
      $meta = $metaIcons[$div['slug']] ?? ['icon' => 'fa-cube', 'class' => 'icon-metal-mecanica', 'num' => '00', 'sub' => ''];
      $prods = $div['productos'];
    ?>
    <div class="category-carousel-wrap" id="carrusel-<?= pcv_esc($div['slug']) ?>">
      <!-- Cabecera del Carrusel con Controles de Flechas -->
      <div class="category-carousel-header">
        <div class="category-carousel-title-group">
          <div class="category-carousel-icon <?= $meta['class'] ?>">
            <i class="fas <?= $meta['icon'] ?>"></i>
          </div>
          <div>
            <h3 class="category-carousel-title">
              <span class="text-muted fw-bold me-1">División <?= $meta['num'] ?> ·</span> <?= pcv_esc($div['nombre']) ?>
            </h3>
            <p class="category-carousel-subtitle mb-0"><?= $meta['sub'] ?></p>
          </div>
        </div>

        <div class="category-carousel-controls">
          <span class="badge bg-light text-dark border px-3 py-2 fw-semibold d-none d-md-inline-block">
            <?= count($prods) ?> disponibles
          </span>
          <button type="button" class="carousel-arrow-btn prev" aria-label="Anterior en <?= pcv_esc($div['nombre']) ?>" title="Anterior">
            <i class="fas fa-chevron-left"></i>
          </button>
          <button type="button" class="carousel-arrow-btn next" aria-label="Siguiente en <?= pcv_esc($div['nombre']) ?>" title="Siguiente">
            <i class="fas fa-chevron-right"></i>
          </button>
          <a href="<?= pcv_url('catalogo.php?clasificacion=' . urlencode($div['slug'])) ?>" class="btn btn-sm btn-outline-navy ms-2 d-none d-sm-inline-flex align-items-center gap-1 rounded-3">
            <span>Ver Todos</span>
            <i class="fas fa-arrow-right small"></i>
          </a>
        </div>
      </div>

      <!-- Ventana y Pista del Carrusel Multiproducto -->
      <div class="category-carousel-viewport">
        <div class="category-carousel-track">
          <?php foreach ($prods as $item): 
            $isService = $item['tipo'] === 'servicio';
            $badgeClass = $isService ? 'badge-service' : 'badge-product';
            $badgeLabel = $isService ? 'Servicio' : 'Producto';
            $imgUrl = pcv_img_producto($item['imagen_principal']);
            $fichaUrl = pcv_url('ficha.php?slug=' . urlencode($item['slug']));
            $waQuoteMsg = rawurlencode("Hola PCV Soluciones, me interesa cotizar: " . $item['nombre']);
          ?>
          <div class="category-carousel-slide">
            <article class="shopify-card h-100">
              <div class="shopify-media">
                <span class="shopify-badge <?= $badgeClass ?>"><?= $badgeLabel ?></span>
                <a href="<?= pcv_esc($fichaUrl) ?>" class="d-block w-100 h-100">
                  <img src="<?= pcv_esc($imgUrl) ?>" alt="<?= pcv_esc($item['nombre']) ?>" loading="lazy">
                </a>
              </div>
              <div class="shopify-body">
                <div class="shopify-cat"><?= pcv_esc($item['clasificacion']) ?><?= $item['subtipo'] ? ' · ' . pcv_esc($item['subtipo']) : '' ?></div>
                <h4 class="shopify-title">
                  <a href="<?= pcv_esc($fichaUrl) ?>"><?= pcv_esc($item['nombre']) ?></a>
                </h4>
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
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ==========================================================================
     MATERIALES Y ACABADOS (Directo del Slide 12 de tu Brochure)
     ========================================================================== -->
<section class="section" id="materiales">
  <div class="container">
    <div class="section-head text-center mx-auto" style="max-width: 700px;">
      <span class="section-eyebrow">Ingeniería de Materiales</span>
      <h2 class="section-title">Materiales y Acabados de Alto Rendimiento</h2>
      <p class="section-sub mx-auto">
        Trabajamos con especificaciones técnicas certificadas para tolerar ambientes agresivos, corrosión y altas cargas mecánicas.
      </p>
    </div>

    <div class="row g-4">
      <!-- Materiales -->
      <div class="col-lg-7">
        <div class="materials-card h-100">
          <div class="d-flex align-items-center gap-2 mb-3">
            <div class="stat-icon" style="width:40px;height:40px;font-size:1.1rem;"><i class="fas fa-cube text-primary"></i></div>
            <h3 class="h4 mb-0 text-navy">Materiales que Maquinamos</h3>
          </div>
          <p class="text-muted small mb-4">Desde aceros endurecidos hasta plásticos antiestáticos para la industria electrónica:</p>
          <div class="pill-grid">
            <span class="material-pill"><i class="fas fa-check"></i> Acero Inoxidable (304, 316)</span>
            <span class="material-pill"><i class="fas fa-check"></i> Aceros Endurecidos y al Carbón</span>
            <span class="material-pill"><i class="fas fa-check"></i> Aluminio (6061, 7075)</span>
            <span class="material-pill"><i class="fas fa-check"></i> Latón, Bronce y Cobre</span>
            <span class="material-pill"><i class="fas fa-check"></i> Delryn y Nylamid</span>
            <span class="material-pill"><i class="fas fa-check"></i> Acetal y Acetal ESD</span>
            <span class="material-pill"><i class="fas fa-check"></i> Materiales ESD (Acrílico, Eva, UHMW)</span>
            <span class="material-pill"><i class="fas fa-check"></i> Durapol y Durostone</span>
            <span class="material-pill"><i class="fas fa-check"></i> G-10 y Celorón</span>
            <span class="material-pill"><i class="fas fa-check"></i> Foamy Industrial</span>
          </div>
        </div>
      </div>

      <!-- Acabados -->
      <div class="col-lg-5">
        <div class="materials-card h-100">
          <div class="d-flex align-items-center gap-2 mb-3">
            <div class="stat-icon" style="width:40px;height:40px;font-size:1.1rem;"><i class="fas fa-brush text-success"></i></div>
            <h3 class="h4 mb-0 text-navy">Tratamientos y Acabados</h3>
          </div>
          <p class="text-muted small mb-4">Protección contra el desgaste, aislamiento y apariencia profesional:</p>
          <div class="pill-grid">
            <span class="material-pill"><i class="fas fa-shield-alt"></i> Anodizado (Natural y Color)</span>
            <span class="material-pill"><i class="fas fa-shield-alt"></i> Pavonado Químico</span>
            <span class="material-pill"><i class="fas fa-shield-alt"></i> Cromado Industrial</span>
            <span class="material-pill"><i class="fas fa-shield-alt"></i> Pintura Electrostática Horneada</span>
            <span class="material-pill"><i class="fas fa-shield-alt"></i> Pintura Líquida Poliuretano</span>
            <span class="material-pill"><i class="fas fa-shield-alt"></i> Pulido Espejo y Satinado</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     PROCESO DE TRABAJO EN 4 PASOS (Estilo Shimpol Modern con Animaciones)
     Inspirado en la referencia del usuario: dot pattern, tarjetas curvas con acentos geométricos
     ========================================================================== -->
<section class="section section-shimpol-process" id="proceso">
  <div class="shimpol-dots-bg"></div>
  <div class="container position-relative" style="z-index: 2;">
    <div class="section-head text-center mx-auto" style="max-width: 700px;">
      <div class="shimpol-badge-pill mb-2">
        <span class="shimpol-badge-zig">~</span>
        <span>Flujo de Trabajo Industrial</span>
        <span class="shimpol-badge-zig">~</span>
      </div>
      <h2 class="section-title text-navy">Nuestro Proceso de Manufactura</h2>
      <p class="section-sub mx-auto">
        Precisión milimétrica desde la recepción de planos CAD hasta el despacho en tu línea de producción.
      </p>
    </div>

    <div class="row g-4 justify-content-center">
      <!-- Paso 1 -->
      <div class="col-sm-6 col-lg-3">
        <div class="shimpol-step-card h-100">
          <div class="shimpol-corner-accent top-right"></div>
          <div class="shimpol-corner-border bottom-left"></div>
          <div class="shimpol-card-content">
            <div class="shimpol-icon-wrap">
              <i class="fas fa-drafting-compass"></i>
            </div>
            <h4 class="shimpol-step-title">1. Planos & Diagnóstico</h4>
            <p class="shimpol-step-desc">
              Recepción y análisis de requerimientos en SolidWorks, AutoCAD, STEP o muestra física con tolerancias.
            </p>
          </div>
        </div>
      </div>

      <!-- Paso 2 -->
      <div class="col-sm-6 col-lg-3">
        <div class="shimpol-step-card h-100">
          <div class="shimpol-corner-accent top-right"></div>
          <div class="shimpol-corner-border bottom-left"></div>
          <div class="shimpol-card-content">
            <div class="shimpol-icon-wrap">
              <i class="fas fa-lightbulb"></i>
            </div>
            <h4 class="shimpol-step-title">2. Ingeniería & Costeo</h4>
            <p class="shimpol-step-desc">
              Selección de materia prima certificada, estimación de tiempos de ciclo y cotización técnica transparente.
            </p>
          </div>
        </div>
      </div>

      <!-- Paso 3 -->
      <div class="col-sm-6 col-lg-3">
        <div class="shimpol-step-card h-100">
          <div class="shimpol-corner-accent top-right"></div>
          <div class="shimpol-corner-border bottom-left"></div>
          <div class="shimpol-card-content">
            <div class="shimpol-icon-wrap">
              <i class="fas fa-cogs"></i>
            </div>
            <h4 class="shimpol-step-title">3. Maquinado & Láser</h4>
            <p class="shimpol-step-desc">
              Manufactura en tornos CNC, centros de maquinado, corte láser 2D/tubo, soldadura y acabados superficiales.
            </p>
          </div>
        </div>
      </div>

      <!-- Paso 4 -->
      <div class="col-sm-6 col-lg-3">
        <div class="shimpol-step-card h-100">
          <div class="shimpol-corner-accent top-right"></div>
          <div class="shimpol-corner-border bottom-left"></div>
          <div class="shimpol-card-content">
            <div class="shimpol-icon-wrap">
              <i class="fas fa-box-open"></i>
            </div>
            <h4 class="shimpol-step-title">4. Inspección & Entrega</h4>
            <p class="shimpol-step-desc">
              Control de calidad dimensional ±0.005 mm, empaque industrial protector y entrega Just-in-Time en planta.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================
     NOSOTROS, MISIÓN, VISIÓN Y VALORES (Full-Width Executive Design)
     ========================================== -->
<section class="section section-about-full" id="nosotros">
  <div class="container">
    <div class="row justify-content-center text-center mb-5">
      <div class="col-lg-10">
        <span class="section-eyebrow">Identidad & Trayectoria Corporativa</span>
        <h2 class="section-title mb-3">Sobre PCV Soluciones Industriales</h2>
        <div class="about-quote-box mx-auto mb-4">
          <i class="fas fa-quote-left text-success me-2"></i>
          <span class="fw-bold">Creamos la figura más difícil de la industria</span>
          <i class="fas fa-quote-right text-success ms-2"></i>
        </div>
        <p class="section-sub mx-auto" style="max-width: 860px; font-size: 1.12rem; line-height: 1.75;">
          Empresa líder especializada en la <strong>iluminación industrial</strong>, diseño, fabricación y comercialización de partes, equipos, piezas y productos metálicos de alta precisión para la <strong>INDUSTRIA METAL MECÁNICA</strong>. Respaldados por tecnología CNC avanzada, corte láser, doblez y soldadura certificada, garantizamos entregas en tiempo récord y cumplimiento estricto de tolerancias.
        </p>
      </div>
    </div>

    <!-- Misión, Visión y Compromiso en Rejilla Amplia -->
    <div class="row g-4 mb-5">
      <div class="col-md-6 col-lg-4">
        <div class="about-pillar-card h-100">
          <div class="pillar-header">
            <div class="pillar-icon pillar-icon-navy">
              <i class="fas fa-bullseye"></i>
            </div>
            <div>
              <span class="pillar-sub">Propósito</span>
              <h3 class="pillar-title">Nuestra Misión</h3>
            </div>
          </div>
          <p class="pillar-text">
            Brindar soluciones de calidad inmediatas, de acuerdo a las necesidades críticas de nuestros clientes industriales, al mejor costo del mercado y con máxima eficiencia en cada ciclo operativo.
          </p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="about-pillar-card h-100">
          <div class="pillar-header">
            <div class="pillar-icon pillar-icon-green">
              <i class="fas fa-eye"></i>
            </div>
            <div>
              <span class="pillar-sub">Futuro</span>
              <h3 class="pillar-title">Nuestra Visión</h3>
            </div>
          </div>
          <p class="pillar-text">
            Consolidarnos como la principal opción del mercado nacional en soluciones metal-mecánicas e iluminación, invirtiendo continuamente en capital humano calificado y tecnologías de vanguardia.
          </p>
        </div>
      </div>

      <div class="col-md-12 col-lg-4">
        <div class="about-pillar-card h-100">
          <div class="pillar-header">
            <div class="pillar-icon pillar-icon-accent">
              <i class="fas fa-shield-halved"></i>
            </div>
            <div>
              <span class="pillar-sub">Garantía</span>
              <h3 class="pillar-title">Calidad Certificada</h3>
            </div>
          </div>
          <p class="pillar-text">
            Supervisión integral de tolerancias dimensionales, trazabilidad en aceros y aleaciones especiales, ingeniería SolidWorks CAD/CAM y soporte técnico dedicado para no frenar líneas de producción.
          </p>
        </div>
      </div>
    </div>

    <!-- Barra de 4 Valores Fundamentales -->
    <div class="values-bar-card p-4 p-md-5 rounded-4 shadow-sm border">
      <div class="row align-items-center g-4">
        <div class="col-lg-3 text-center text-lg-start">
          <span class="text-uppercase fw-bold small text-success letter-spacing-1 d-block mb-1">Cultura Organizacional</span>
          <h4 class="fw-bold text-navy mb-0">Nuestros Valores Fundamentales</h4>
        </div>
        <div class="col-lg-9">
          <div class="row g-3">
            <div class="col-sm-6 col-md-3">
              <div class="value-item text-center text-sm-start">
                <div class="value-icon"><i class="fas fa-handshake"></i></div>
                <h5 class="value-name">Respeto</h5>
                <p class="value-desc">Trato digno, honesto y transparente con clientes y proveedores.</p>
              </div>
            </div>
            <div class="col-sm-6 col-md-3">
              <div class="value-item text-center text-sm-start">
                <div class="value-icon"><i class="fas fa-scale-balanced"></i></div>
                <h5 class="value-name">Honestidad</h5>
                <p class="value-desc">Prácticas éticas y veracidad en costos y tiempos de entrega.</p>
              </div>
            </div>
            <div class="col-sm-6 col-md-3">
              <div class="value-item text-center text-sm-start">
                <div class="value-icon"><i class="fas fa-medal"></i></div>
                <h5 class="value-name">Compromiso</h5>
                <p class="value-desc">Responsabilidad total en la ejecución de cada requerimiento.</p>
              </div>
            </div>
            <div class="col-sm-6 col-md-3">
              <div class="value-item text-center text-sm-start">
                <div class="value-icon"><i class="fas fa-users-gear"></i></div>
                <h5 class="value-name">Trabajo en Equipo</h5>
                <p class="value-desc">Sinergia multidisciplinaria orientada a altos estándares.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     FORMULARIO DE COTIZACIÓN RÁPIDA Y CONTACTO (Cripar Form Band)
     ========================================================================== -->
<section class="section section-muted" id="contacto">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6">
        <div class="card border-0 shadow-lg p-4 p-md-5 bg-white rounded-3">
          <span class="section-eyebrow mb-1">Presupuesto sin compromiso</span>
          <h2 class="h3 fw-bold text-navy mb-4">Solicita tu cotización técnica</h2>
          <form id="formCotizar" action="<?= pcv_url('cotizar.php') ?>" method="post">
            <input type="hidden" name="origen" value="landing_home">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label small fw-bold">Nombre Completo *</label>
                <input type="text" name="nombre" class="form-control" required placeholder="Tu nombre">
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-bold">Empresa / Razón Social</label>
                <input type="text" name="empresa" class="form-control" placeholder="Nombre de tu empresa">
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-bold">Teléfono / WhatsApp *</label>
                <input type="tel" name="telefono" class="form-control" required placeholder="33 1144 4743">
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-bold">Correo Electrónico</label>
                <input type="email" name="correo" class="form-control" placeholder="nombre@correo.com">
              </div>
              <div class="col-12">
                <label class="form-label small fw-bold">Detalle de la pieza o servicio</label>
                <textarea name="mensaje" rows="3" class="form-control" placeholder="Describe tu requerimiento: material, cantidad estimada, acabado o servicio…"></textarea>
              </div>
              <div class="col-12 d-grid mt-4">
                <button type="submit" class="btn btn-green btn-lg py-3">
                  <i class="fas fa-paper-plane me-2"></i> Enviar Cotización y Abrir WhatsApp
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div class="col-lg-6">
        <span class="section-eyebrow">Atención Personalizada</span>
        <h2 class="section-title">Estamos listos para colaborar en tu proyecto</h2>
        <p class="text-muted mb-4">
          Sin precios públicos genéricos: cada pieza de maquinado, corte láser o instalación de iluminación se cotiza acorde al plano, volumen y especificación técnica exacta.
        </p>

        <div class="d-flex flex-column gap-3 mb-4">
          <div class="d-flex align-items-center gap-3 p-3 bg-white border rounded shadow-sm">
            <div class="stat-icon" style="width:46px;height:46px;font-size:1.1rem;"><i class="fab fa-whatsapp text-success"></i></div>
            <div>
              <strong class="d-block text-navy">WhatsApp Inmediato</strong>
              <a href="https://wa.me/<?= pcv_esc(PCV_WHATSAPP) ?>" target="_blank" rel="noopener" class="text-muted small">Atención en línea: +52 33 1144 4743</a>
            </div>
          </div>
          <div class="d-flex align-items-center gap-3 p-3 bg-white border rounded shadow-sm">
            <div class="stat-icon" style="width:46px;height:46px;font-size:1.1rem;"><i class="fas fa-phone-alt text-primary"></i></div>
            <div>
              <strong class="d-block text-navy">Líneas de Oficina</strong>
              <span class="text-muted small">(33) 1043 8300 · (34) 8123 3200</span>
            </div>
          </div>
          <div class="d-flex align-items-center gap-3 p-3 bg-white border rounded shadow-sm">
            <div class="stat-icon" style="width:46px;height:46px;font-size:1.1rem;"><i class="fas fa-envelope text-warning"></i></div>
            <div>
              <strong class="d-block text-navy">Correo de Ingeniería y Cotizaciones</strong>
              <a href="mailto:<?= pcv_esc(PCV_EMAIL) ?>" class="text-muted small"><?= pcv_esc(PCV_EMAIL) ?></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/footer.php'; ?>
