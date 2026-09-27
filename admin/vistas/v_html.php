<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCV Soluciones | Admin (Spark Panel)</title>
    <link rel="icon" href="#PCV_ADMIN_BASE#/vistas/assets/images/logos/icon.png?v=3.0" type="image/png">
    <link rel="shortcut icon" href="#PCV_ADMIN_BASE#/vistas/assets/images/logos/favicon.ico?v=3.0" type="image/x-icon">
    <link rel="apple-touch-icon" href="#PCV_ADMIN_BASE#/vistas/assets/images/logos/icon.png?v=3.0">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="#PCV_ADMIN_BASE#/vistas/assets/plugins/fontawesome/css/all.min.css">

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="#PCV_ADMIN_BASE#/vistas/assets/plugins/bootstrap/css/bootstrap.min.css">

    <!-- Plugins -->
    <link rel="stylesheet" href="#PCV_ADMIN_BASE#/vistas/assets/plugins/myDataTable/css/myDataTable.css">
    <link rel="stylesheet" href="#PCV_ADMIN_BASE#/vistas/assets/plugins/fancybox/dist/jquery.fancybox.min.css">
    <link rel="stylesheet" href="#PCV_ADMIN_BASE#/vistas/assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="#PCV_ADMIN_BASE#/vistas/assets/plugins/quill/quill.snow.css">

    <!-- Spark Admin Stylesheets -->
    <link rel="stylesheet" href="#PCV_ADMIN_BASE#/vistas/assets/css/spark-admin.css?v=2.6">
    <link rel="stylesheet" href="#PCV_ADMIN_BASE#/vistas/assets/css/spark-admin-custom.css?v=2.7">
</head>
<body>
    <!-- Pantalla de carga animada -->
    <div class="carga" id="carga">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Cargando...</span>
      </div>
    </div>

    <!-- START: Spark Admin Sidebar Component -->
    <aside class="spark-sidebar-wrapper" id="sparkSidebar">
        <!-- Brand Logo -->
        <a href="javascript:void(0)" class="spark-sidebar-brand cargarVista" carga="v_dashboard" titulo="Dashboard">
            <img src="#PCV_ADMIN_BASE#/vistas/assets/images/logos/logo-white.png" alt="PCV Soluciones" class="spark-brand-logo-full" onerror="this.src='#PCV_BASE#/assets/img/logo-white.png'">
            <img src="#PCV_ADMIN_BASE#/vistas/assets/images/logos/logo-symbol-white.png" alt="PCV" class="spark-brand-logo-collapsed" onerror="this.src='#PCV_BASE#/assets/img/logo-symbol-white.png'">
        </a>

        <!-- Navigation Menu -->
        <div class="flex-grow-1 overflow-y-auto">
            <!-- Group: Menu Principal -->
            <div class="spark-sidebar-menu-section">
                <div class="spark-sidebar-menu-title">Administración</div>
                <ul class="spark-sidebar-menu-list">
                    <li class="spark-sidebar-menu-item sidebar-item active">
                        <a href="javascript:void(0)" id="bMenuDashboard" class="spark-sidebar-menu-link cargarVista" carga="v_dashboard" titulo="Dashboard General">
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="spark-sidebar-menu-item sidebar-item">
                        <a href="javascript:void(0)" id="bMenuProductos" class="spark-sidebar-menu-link cargarVista" carga="v_productos" titulo="Productos y Servicios">
                            <i class="bi bi-boxes"></i>
                            <span>Productos / Servicios</span>
                        </a>
                    </li>
                    <li class="spark-sidebar-menu-item sidebar-item">
                        <a href="javascript:void(0)" id="bMenuSubtipos" class="spark-sidebar-menu-link cargarVista" carga="v_subtipos" titulo="Filtros / Líneas">
                            <i class="bi bi-sliders2"></i>
                            <span>Filtros / Líneas</span>
                        </a>
                    </li>
                    <li class="spark-sidebar-menu-item sidebar-item">
                        <a href="javascript:void(0)" id="bMenuCotizaciones" class="spark-sidebar-menu-link cargarVista" carga="v_cotizaciones" titulo="Cotizaciones Recibidas">
                            <i class="bi bi-file-earmark-text"></i>
                            <span>Cotizaciones B2B</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Group: Accesos Rápidos -->
            <div class="spark-sidebar-menu-section">
                <div class="spark-sidebar-menu-title">Accesos Web</div>
                <ul class="spark-sidebar-menu-list">
                    <li class="spark-sidebar-menu-item">
                        <a href="#PCV_SITE_URL#" target="_blank" class="spark-sidebar-menu-link">
                            <i class="bi bi-box-arrow-up-right"></i>
                            <span>Ver Sitio Web</span>
                            <span class="badge rounded-pill bg-white text-dark ms-auto" style="font-size: 0.65rem; font-weight: 600;">Landing</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Sidebar Profile Card (Spark Style) -->
        <div class="spark-sidebar-profile">
            <div class="position-relative d-inline-block">
                <img src="#fotoCuenta#" alt="Usuario" class="spark-sidebar-profile-img" onerror="this.src='#PCV_ADMIN_BASE#/vistas/assets/images/default.jpg'">
                <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle" style="width: 10px; height: 10px; transform: translate(25%, 25%);"></span>
            </div>
            <div class="spark-sidebar-profile-info">
                <div class="spark-sidebar-profile-name" title="#nombreUsuarioPlain#">#nombreUsuarioPlain#</div>
                <div class="spark-sidebar-profile-role">Administrador PCV</div>
            </div>
        </div>
    </aside>
    <!-- END: Sidebar -->

    <!-- Backdrop for mobile drawer -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- START: Main Wrapper -->
    <div class="spark-main-wrapper" id="main">
        <!-- Top Navbar Spark Style -->
        <header class="spark-navbar">
            <div class="spark-nav-left d-flex align-items-center gap-2">
                <button type="button" class="btn-desktop-toggle spark-sidebar-toggle-btn" id="btnToggleSidebar" aria-label="Ocultar / Desplegar menú" title="Ocultar / Desplegar menú">
                    <i class="bi bi-chevron-bar-left"></i>
                </button>
            </div>

            <div class="spark-nav-right">
                <a href="#PCV_SITE_URL#" target="_blank" class="btn-spark-action d-none d-sm-inline-flex">
                    <i class="bi bi-globe"></i>
                    <span>Ver Landing</span>
                </a>

                <div class="dropdown">
                    <div class="spark-user-menu" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="#fotoCuenta#" alt="Avatar" class="spark-user-avatar">
                        <span class="d-none d-md-inline fw-bold small text-dark">#nombreUsuario#</span>
                        <i class="bi bi-chevron-down small text-muted"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 mt-2">
                        <li><a class="dropdown-item py-2" href="#PCV_SITE_URL#" target="_blank"><i class="bi bi-globe me-2"></i> Ver Sitio Web</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger bCerrarSe" href="javascript:void(0)"><i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Dynamic AJAX View Container -->
        <main class="spark-page-content" id="verVista">
            <!-- Cargado vía AJAX por c_controller / main.js -->
        </main>
    </div>

    <!-- Scripts Core -->
    <script src="#PCV_ADMIN_BASE#/vistas/assets/plugins/jquery-3.7.1.min.js"></script>
    <script src="#PCV_ADMIN_BASE#/vistas/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="#PCV_ADMIN_BASE#/vistas/assets/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="#PCV_ADMIN_BASE#/vistas/assets/plugins/myDataTable/js/myDataTable.js"></script>
    <script src="#PCV_ADMIN_BASE#/vistas/assets/plugins/sweetalert/dist/sweetalert2.all.min.js"></script>
    <script src="#PCV_ADMIN_BASE#/vistas/assets/plugins/fancybox/dist/jquery.fancybox.min.js"></script>
    <script src="#PCV_ADMIN_BASE#/vistas/assets/plugins/select2/js/select2.min.js"></script>
    
    <!-- Application Logic -->
    <script src="#PCV_ADMIN_BASE#/vistas/assets/js/main.js?v=2.6"></script>
    <script src="#PCV_ADMIN_BASE#/vistas/assets/js/dashboard.js?v=2.6"></script>
    <script src="#PCV_ADMIN_BASE#/vistas/assets/js/productos.js?v=2.6"></script>
    <script src="#PCV_ADMIN_BASE#/vistas/assets/js/cotizaciones.js?v=2.6"></script>
<script src="#PCV_ADMIN_BASE#/vistas/assets/js/subtipos.js?v=2.6"></script>
</body>
</html>
