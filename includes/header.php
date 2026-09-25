<?php
if (!defined('PCV_ROOT')) {
    require_once __DIR__ . '/config.php';
}
require_once __DIR__ . '/db.php';
$pcv_page = $pcv_page ?? 'home';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= pcv_esc($pcv_title ?? 'PCV Soluciones Industriales') ?></title>
  <meta name="description" content="PCV Soluciones Industriales — maquinados CNC, corte láser, iluminación industrial y más. Creamos la figura más difícil de la industria.">
  <link rel="icon" href="<?= pcv_asset('img/logo-header.png') ?>">
  <link rel="stylesheet" href="<?= pcv_asset('plugins/bootstrap/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="<?= pcv_asset('plugins/sweetalert2/sweetalert2.min.css') ?>">
  <link rel="stylesheet" href="<?= pcv_asset('css/site.css') ?>">
</head>
<body>
<header class="site-header sticky-top">
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="<?= pcv_url('index.php') ?>">
        <img src="<?= pcv_asset('img/logo-header.png') ?>" alt="PCV" class="brand-logo">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain"><span class="navbar-toggler-icon"></span></button>
      <div class="collapse navbar-collapse" id="navMain">
        <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
          <li class="nav-item"><a class="nav-link <?= $pcv_page==='home'?'active':'' ?>" href="<?= pcv_url('index.php') ?>">Inicio</a></li>
          <li class="nav-item"><a class="nav-link <?= $pcv_page==='catalogo'?'active':'' ?>" href="<?= pcv_url('catalogo.php') ?>">Catálogo</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= pcv_url('index.php') ?>#nosotros">Nosotros</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= pcv_url('index.php') ?>#contacto">Contacto</a></li>
          <li class="nav-item"><a class="btn btn-accent btn-sm ms-lg-2" href="<?= pcv_url('catalogo.php') ?>#cotizar">Cotizar</a></li>
        </ul>
      </div>
    </div>
  </nav>
</header>
