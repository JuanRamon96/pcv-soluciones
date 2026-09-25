<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PCV Admin</title>
  <link rel="stylesheet" href="/admin/vistas/assets/plugins/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="/admin/vistas/assets/plugins/datatables/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="/admin/vistas/assets/plugins/sweetalert2/sweetalert2.min.css">
  <link rel="stylesheet" href="/admin/vistas/assets/css/admin.css">
</head>
<body>
<div class="d-flex" id="wrapper">
  <nav id="sidebar" class="sidebar">
    <div class="sidebar-brand p-3 text-center">
      <img src="/assets/img/logo-header.png" alt="PCV" class="sidebar-logo">
      <div class="small text-white-50 mt-2">Admin PCV</div>
    </div>
    <ul class="nav flex-column px-2">
      <li class="nav-item"><a href="javascript:void(0)" class="nav-link cargarVista active" carga="v_dashboard" titulo="Dashboard"><i class="fas fa-gauge"></i> Dashboard</a></li>
      <li class="nav-item"><a href="javascript:void(0)" class="nav-link cargarVista" carga="v_productos" titulo="Productos y servicios"><i class="fas fa-boxes-stacked"></i> Productos / Servicios</a></li>
      <li class="nav-item"><a href="javascript:void(0)" class="nav-link cargarVista" carga="v_cotizaciones" titulo="Cotizaciones"><i class="fas fa-file-invoice"></i> Cotizaciones</a></li>
      <li class="nav-item mt-3"><a href="/" class="nav-link" target="_blank"><i class="fas fa-globe"></i> Ver sitio</a></li>
      <li class="nav-item"><a href="javascript:void(0)" class="nav-link" id="bCerrarSesion"><i class="fas fa-right-from-bracket"></i> Salir</a></li>
    </ul>
  </nav>
  <div class="flex-grow-1" id="page-content">
    <header class="topbar d-flex align-items-center justify-content-between px-3 px-md-4">
      <div>
        <button class="btn btn-sm btn-outline-light d-lg-none" id="bToggleSidebar"><i class="fas fa-bars"></i></button>
        <span class="ms-2 fw-semibold" id="tituloVista">Dashboard</span>
      </div>
      <div class="small">Hola, <strong>#nombreUsuario#</strong></div>
    </header>
    <main class="p-3 p-md-4" id="contenido">
      <!-- AJAX views -->
    </main>
  </div>
</div>
<script src="/admin/vistas/assets/plugins/jquery-3.7.1.min.js"></script>
<script src="/admin/vistas/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/admin/vistas/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/admin/vistas/assets/plugins/datatables/dataTables.bootstrap5.min.js"></script>
<script src="/admin/vistas/assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script src="/admin/vistas/assets/plugins/imask/imask.min.js"></script>
<script src="/admin/vistas/assets/js/app.js"></script>
<script>
  $(function(){ $('.cargarVista[carga="v_dashboard"]').first().trigger('click'); });
</script>
</body>
</html>
