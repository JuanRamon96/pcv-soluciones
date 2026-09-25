<?php
if (!defined('PCV_ROOT')) {
    require_once __DIR__ . '/config.php';
}
require_once __DIR__ . '/db.php';
$pcv_page = $pcv_page ?? 'home';
$tel1_fmt = preg_replace('/(\d{2})(\d{4})(\d{4})/', '$1 $2 $3', PCV_TEL1);
$tel2_fmt = preg_replace('/(\d{2})(\d{4})(\d{4})/', '$1 $2 $3', PCV_TEL2);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= pcv_esc($pcv_title ?? 'PCV Soluciones Industriales') ?></title>
  <meta name="description" content="PCV Soluciones Industriales — maquinados CNC, corte láser, iluminación industrial y más. Creamos la figura más difícil de la industria.">
  <link rel="icon" href="<?= pcv_asset('img/logo-header.png') ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="<?= pcv_asset('plugins/bootstrap/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="<?= pcv_asset('plugins/sweetalert2/sweetalert2.min.css') ?>">
  <link rel="stylesheet" href="<?= pcv_asset('css/site.css') ?>">
</head>
<body>
<div class="pcv-topbar">
  <div class="container d-flex flex-wrap justify-content-between align-items-center py-1 gap-2">
    <div class="d-flex flex-wrap align-items-center">
      <a class="top-item" href="tel:<?= pcv_esc(PCV_TEL1) ?>"><i class="fas fa-phone"></i><span><?= pcv_esc($tel1_fmt) ?></span></a>
      <a class="top-item d-none d-sm-inline-flex" href="tel:<?= pcv_esc(PCV_TEL2) ?>"><i class="fas fa-phone"></i><span class="hide-xs"><?= pcv_esc($tel2_fmt) ?></span></a>
      <a class="top-item" href="mailto:<?= pcv_esc(PCV_EMAIL) ?>"><i class="fas fa-envelope"></i><span class="d-none d-md-inline"><?= pcv_esc(PCV_EMAIL) ?></span><span class="d-md-none">Email</span></a>
    </div>
    <div class="top-social">
      <a href="https://instagram.com/<?= pcv_esc(PCV_IG) ?>" target="_blank" rel="noopener" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
      <a href="<?= pcv_esc(PCV_FB) ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
      <a href="https://wa.me/<?= pcv_esc(PCV_WHATSAPP) ?>" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
    </div>
  </div>
</div>
<header class="site-header">
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="<?= pcv_url('index.php') ?>">
        <span class="brand-accent" aria-hidden="true"></span>
        <img src="<?= pcv_asset('img/logo-header.png') ?>" alt="PCV Soluciones" class="brand-logo">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Menú">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMain">
        <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
          <li class="nav-item"><a class="nav-link <?= $pcv_page==='home'?'active':'' ?>" href="<?= pcv_url('index.php') ?>">Inicio</a></li>
          <li class="nav-item"><a class="nav-link <?= $pcv_page==='catalogo'?'active':'' ?>" href="<?= pcv_url('catalogo.php') ?>">Catálogo</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= pcv_url('index.php') ?>#nosotros">Nosotros</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= pcv_url('index.php') ?>#contacto">Contacto</a></li>
          <li class="nav-item ms-lg-2">
            <a class="btn btn-cta" href="<?= pcv_url('cotizar.php') ?>">Cotizar <i class="fas fa-arrow-up-right-from-square"></i></a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>
