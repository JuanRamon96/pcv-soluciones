<?php
if (!defined('PCV_ROOT')) {
    require_once __DIR__ . '/admin/modelo/config/config.php';
}
require_once __DIR__ . '/admin/modelo/config/db.php';
$pcv_page = $pcv_page ?? 'home';
$tel1_fmt = preg_replace('/(\d{2})(\d{4})(\d{4})/', '$1 $2 $3', PCV_TEL1);
$tel2_fmt = preg_replace('/(\d{2})(\d{4})(\d{4})/', '$1 $2 $3', PCV_TEL2);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= pcv_esc($pcv_title ?? 'PCV Soluciones Industriales | ' . PCV_ESLOGAN) ?></title>
  <meta name="description" content="PCV Soluciones Industriales — Maquinados CNC, corte láser 2D y tubo, iluminación industrial LED con estudios de luxes, automatización, fixturas y pallets para PCB.">
  <link rel="icon" href="<?= pcv_asset('images/icon.png?v=' . filemtime(PCV_ROOT . '/assets/images/icon.png')) ?>" type="image/png">
  <link rel="shortcut icon" href="<?= pcv_asset('images/favicon.ico?v=' . filemtime(PCV_ROOT . '/assets/images/favicon.ico')) ?>" type="image/x-icon">
  <link rel="apple-touch-icon" href="<?= pcv_asset('images/icon.png?v=' . filemtime(PCV_ROOT . '/assets/images/icon.png')) ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="<?= pcv_asset('plugins/bootstrap/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="<?= pcv_asset('plugins/sweetalert2/sweetalert2.min.css') ?>">
  <link rel="stylesheet" href="<?= pcv_asset('css/site.css?v=' . filemtime(PCV_ROOT . '/assets/css/site.css')) ?>">
</head>
<body>

<!-- Topbar estilo Cripar -->
<div class="pcv-topbar py-2">
  <div class="container-fluid header-container d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div class="d-flex flex-wrap align-items-center">
      <a class="top-item" href="tel:<?= pcv_esc(PCV_TEL1) ?>">
        <i class="fas fa-phone-volume"></i>
        <span><?= pcv_esc($tel1_fmt) ?></span>
      </a>
      <a class="top-item d-none d-sm-inline-flex" href="tel:<?= pcv_esc(PCV_TEL2) ?>">
        <i class="fas fa-phone"></i>
        <span><?= pcv_esc($tel2_fmt) ?></span>
      </a>
      <a class="top-item" href="mailto:<?= pcv_esc(PCV_EMAIL) ?>">
        <i class="fas fa-envelope"></i>
        <span class="d-none d-md-inline"><?= pcv_esc(PCV_EMAIL) ?></span>
      </a>
    </div>
    <div class="top-social d-flex align-items-center">
      <span class="small me-2 opacity-75 d-none d-lg-inline">Atención directa:</span>
      <a href="https://instagram.com/<?= pcv_esc(PCV_IG) ?>" target="_blank" rel="noopener" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
      <a href="<?= pcv_esc(PCV_FB) ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
      <a href="https://wa.me/<?= pcv_esc(PCV_WHATSAPP) ?>" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
    </div>
  </div>
</div>

<!-- Header y Navegación Principal Full Responsive -->
<header class="site-header">
  <nav class="navbar navbar-expand-lg py-0">
    <div class="container-fluid header-container">
      <a class="navbar-brand d-flex align-items-center me-2 me-xl-4 py-2" href="<?= pcv_url('') ?>">
        <img src="<?= pcv_asset('img/logo-header.png?v=' . filemtime(PCV_ROOT . '/assets/img/logo-header.png')) ?>" alt="PCV Soluciones Industriales" class="brand-logo">
      </a>
      
      <!-- Mobile Right Quick Actions -->
      <div class="d-flex align-items-center gap-2 d-lg-none">
        <button class="btn rfq-mobile-trigger" type="button" data-bs-toggle="offcanvas" data-bs-target="#rfqDrawer" aria-controls="rfqDrawer" title="Ver Lista de Cotización">
          <i class="fas fa-file-invoice"></i>
          <span class="badge-rfq-count rfq-count">0</span>
        </button>
        <button class="navbar-toggler header-toggler-btn collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Abrir Menú de Navegación">
          <span class="toggler-bar bar-1"></span>
          <span class="toggler-bar bar-2"></span>
          <span class="toggler-bar bar-3"></span>
        </button>
      </div>

      <!-- Collapsible Nav -->
      <div class="collapse navbar-collapse" id="navMain">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item">
            <a class="nav-link <?= $pcv_page==='home'?'active':'' ?>" href="<?= pcv_url('') ?>">Inicio</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle <?= $pcv_page==='catalogo'?'active':'' ?>" href="<?= pcv_url('catalogo.php') ?>" id="navDropCatalogo" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <span>Catálogo (3 Divisiones)</span>
            </a>
            <ul class="dropdown-menu shadow-lg border-0 header-divisions-dropdown" aria-labelledby="navDropCatalogo">
              <li>
                <a class="dropdown-item py-2 fw-bold d-flex align-items-center gap-2" href="<?= pcv_url('catalogo.php') ?>">
                  <span class="nav-icon-badge nav-icon-all"><i class="fas fa-border-all"></i></span>
                  <span>Ver Catálogo Completo</span>
                </a>
              </li>
              <li><hr class="dropdown-divider my-1"></li>
              <li>
                <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="<?= pcv_url('catalogo.php?clasificacion=metal-mecanica') ?>">
                  <span class="nav-icon-badge nav-icon-metal"><i class="fas fa-gears"></i></span>
                  <div>
                    <strong class="d-block text-navy" style="font-size: 0.88rem;">1. Metal Mecánica y Procesos</strong>
                    <small class="text-muted d-block" style="font-size: 0.74rem;">Torno CNC, Corte Láser, Soldadura</small>
                  </div>
                </a>
              </li>
              <li>
                <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="<?= pcv_url('catalogo.php?clasificacion=luminaria') ?>">
                  <span class="nav-icon-badge nav-icon-led"><i class="fas fa-lightbulb"></i></span>
                  <div>
                    <strong class="d-block text-navy" style="font-size: 0.88rem;">2. Iluminación Industrial LED</strong>
                    <small class="text-muted d-block" style="font-size: 0.74rem;">Estudios de Luxes, Campanas, Proyectores</small>
                  </div>
                </a>
              </li>
              <li>
                <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="<?= pcv_url('catalogo.php?clasificacion=refaccionaria') ?>">
                  <span class="nav-icon-badge nav-icon-auto"><i class="fas fa-robot"></i></span>
                  <div>
                    <strong class="d-block text-navy" style="font-size: 0.88rem;">3. Automatización y Mobiliario</strong>
                    <small class="text-muted d-block" style="font-size: 0.74rem;">Racks, Conveyors, Fixturas PCB</small>
                  </div>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $pcv_page==='home' ? '#nosotros' : pcv_url('') . '#nosotros' ?>">Nosotros</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-nowrap" href="<?= $pcv_page==='home' ? '#materiales' : pcv_url('') . '#materiales' ?>">Materiales y Acabados</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $pcv_page==='home' ? '#contacto' : pcv_url('') . '#contacto' ?>">Contacto</a>
          </li>
        </ul>

        <!-- Desktop Action Buttons -->
        <div class="header-desktop-actions d-none d-lg-flex align-items-center gap-2 ms-xl-3 ms-lg-2">
          <button class="btn rfq-nav-btn text-nowrap" type="button" data-bs-toggle="offcanvas" data-bs-target="#rfqDrawer" aria-controls="rfqDrawer">
            <i class="fas fa-file-invoice text-navy me-1"></i>
            <span>Mi Cotización</span>
            <span class="badge-rfq-count rfq-count">0</span>
          </button>
          <a class="btn btn-header-cotizar text-nowrap" href="<?= pcv_url('cotizar.php') ?>">
            <i class="fas fa-paper-plane me-1"></i>
            <span>Cotizar</span>
          </a>
        </div>

        <!-- Mobile Menu Footer Actions -->
        <div class="mobile-menu-footer d-lg-none pt-3 mt-3 border-top">
          <button class="btn rfq-nav-btn w-100 mb-2 py-2 d-flex align-items-center justify-content-center gap-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#rfqDrawer">
            <i class="fas fa-file-invoice"></i>
            <span>Ver Mi Lista de Cotización</span>
            <span class="badge-rfq-count rfq-count">0</span>
          </button>
          <a class="btn btn-header-cotizar w-100 py-2 mb-3 d-flex align-items-center justify-content-center gap-2" href="<?= pcv_url('cotizar.php') ?>">
            <i class="fas fa-paper-plane"></i>
            <span>Solicitar Cotización Formal</span>
          </a>
          <div class="row g-2">
            <div class="col-6">
              <a href="tel:<?= pcv_esc(PCV_TEL1) ?>" class="btn btn-sm btn-outline-dark w-100 py-2 d-flex align-items-center justify-content-center gap-1 rounded-3">
                <i class="fas fa-phone text-success"></i> Llamar
              </a>
            </div>
            <div class="col-6">
              <a href="https://wa.me/<?= pcv_esc(PCV_WHATSAPP) ?>" target="_blank" rel="noopener" class="btn btn-sm btn-success w-100 py-2 d-flex align-items-center justify-content-center gap-1 rounded-3">
                <i class="fab fa-whatsapp"></i> WhatsApp
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>
</header>
