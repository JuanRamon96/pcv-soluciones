<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCV Soluciones | Admin</title>
    <link rel="icon" href="/admin/vistas/assets/images/logos/icon.png" type="image/x-icon">

    <link rel="stylesheet" href="/admin/vistas/assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/admin/vistas/assets/plugins/plantilla/css/bootstrap.css">
    <link rel="stylesheet" href="/admin/vistas/assets/plugins/plantilla/vendors/chartjs/Chart.min.css">
    <link rel="stylesheet" href="/admin/vistas/assets/plugins/plantilla/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="/admin/vistas/assets/plugins/plantilla/css/app.css">
    <link rel="stylesheet" href="/admin/vistas/assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/admin/vistas/assets/plugins/myDataTable/css/myDataTable.css">
    <link rel="stylesheet" href="/admin/vistas/assets/plugins/fancybox/dist/jquery.fancybox.min.css">
    <link rel="stylesheet" href="/admin/vistas/assets/plugins/leaflet/leaflet.css">
    <link rel="stylesheet" href="/admin/vistas/assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/admin/vistas/assets/plugins/quill/quill.snow.css" >

    
    <link rel="stylesheet" href="/admin/vistas/assets/css/css.css">
</head>
<body>
    <div class="carga" id="carga">
      <div class="container" style="min-height: 100vh;">
        <div class="row align-items-center" style="min-height: 100vh;">
          <div class="col-12 text-center">
            <div class="spinner-border text-primary" style="width: 8rem; height: 8rem;" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="app">
        <div id="sidebar" class='active'>
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <img src="/admin/vistas/assets/images/logos/logo.png" width="40%">
                </div>
                <div class="sidebar-menu">
                                        <ul class="menu">
                        <li class="sidebar-title">Panel</li>
                        <li class="sidebar-item active">
                            <a href="javascript:void(0)" id="bMenuDashboard" class="sidebar-link cargarVista" carga="v_dashboard" titulo="Dashboard">
                                <i class="fa-solid fa-gauge" style="font-size: 20px;"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="javascript:void(0)" id="bMenuProductos" class="sidebar-link cargarVista" carga="v_productos" titulo="Productos / Servicios">
                                <i class="fa-solid fa-boxes-stacked" style="font-size: 20px;"></i>
                                <span>Productos / Servicios</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="javascript:void(0)" id="bMenuCotizaciones" class="sidebar-link cargarVista" carga="v_cotizaciones" titulo="Cotizaciones">
                                <i class="fa-solid fa-file-invoice" style="font-size: 20px;"></i>
                                <span>Cotizaciones</span>
                            </a>
                        </li>
                        <li class="sidebar-title">Sitio</li>
                        <li class="sidebar-item">
                            <a href="/" target="_blank" class="sidebar-link">
                                <i class="fa-solid fa-globe" style="font-size: 20px;"></i>
                                <span>Ver sitio</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="javascript:void(0)" class="sidebar-link bCerrarSe">
                                <i class="fa-solid fa-right-from-bracket" style="font-size: 20px;"></i>
                                <span>Salir</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>
        <div id="main">
            <nav class="navbar navbar-header navbar-expand navbar-light">
                <a class="sidebar-toggler" href="#"><span class="navbar-toggler-icon"></span></a>
                <button class="btn navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav d-flex align-items-center navbar-light ml-auto">
                        <!--<li class="dropdown nav-icon">
                            <a href="#" data-toggle="dropdown" class="nav-link  dropdown-toggle nav-link-lg nav-link-user">
                                <div class="d-lg-inline-block">
                                    <i data-feather="bell"></i>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-large">
                                <h6 class='py-2 px-4'>Notifications</h6>
                                <ul class="list-group rounded-none">
                                    <li class="list-group-item border-0 align-items-start">
                                        <div class="avatar bg-success mr-3">
                                            <span class="avatar-content"><i data-feather="shopping-cart"></i></span>
                                        </div>
                                        <div>
                                            <h6 class='text-bold'>New Order</h6>
                                            <p class='text-xs'>
                                                An order made by Ahmad Saugi for product Samsung Galaxy S69
                                            </p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="dropdown nav-icon mr-2">
                            <a href="#" data-toggle="dropdown" class="nav-link  dropdown-toggle nav-link-lg nav-link-user">
                                <div class="d-lg-inline-block">
                                    <i data-feather="mail"></i>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i data-feather="user"></i> Account</a>
                                <a class="dropdown-item active" href="#"><i data-feather="mail"></i> Messages</a>
                                <a class="dropdown-item" href="javascript:void(0)"><i data-feather="settings"></i> Settigs</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)"><i data-feather="log-out"></i> Log out</a>
                            </div>
                        </li>-->
                        <li class="dropdown">
                            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                                <div class="avatar mr-1" style="background-image: url('#fotoCuenta#'); width: 40px; height: 40px; border-radius: 100%; background-size: cover; background-position: center;">
                                </div>
                                <div class="d-none d-md-block d-lg-inline-block">#nombreUsuario#</div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <!--<a class="dropdown-item" href="#"><i data-feather="user"></i> Account</a>
                                <a class="dropdown-item active" href="#"><i data-feather="mail"></i> Messages</a>-->
                                <a class="dropdown-item" href="/" target="_blank"><i data-feather="globe"></i> Ver sitio</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item bCerrarSe" href="javascript:void(0)"><i data-feather="log-out"></i> Cerrar Sesión</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            
            <div class="main-content container-fluid">
                <div class="page-title">
                    <h3 class="vistaTitulo"></h3>
                </div>
                <section class="section" id="verVista">
                    
                </section>
            </div>
        </div>
    </div>

        <script src="/admin/vistas/assets/plugins/jquery-3.7.1.min.js"></script>
    <script src="/admin/vistas/assets/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="/admin/vistas/assets/plugins/plantilla/js/feather-icons/feather.min.js"></script>
    <script src="/admin/vistas/assets/plugins/plantilla/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="/admin/vistas/assets/plugins/plantilla/js/app.js"></script>
    <script src="/admin/vistas/assets/plugins/plantilla/vendors/chartjs/Chart.min.js"></script>
    <script src="/admin/vistas/assets/plugins/plantilla/vendors/apexcharts/apexcharts.min.js"></script>
    <script src="/admin/vistas/assets/plugins/plantilla/js/main.js"></script>
    <script src="/admin/vistas/assets/plugins/myDataTable/js/myDataTable.js"></script>
    <script src="/admin/vistas/assets/plugins/sweetalert/dist/sweetalert2.all.min.js"></script>
    <script src="/admin/vistas/assets/plugins/fancybox/dist/jquery.fancybox.min.js"></script>
    <script src="/admin/vistas/assets/plugins/moment.min.js"></script>
    <script src="/admin/vistas/assets/plugins/imask.js"></script>
    <script src="/admin/vistas/assets/plugins/select2/js/select2.min.js"></script>
    <script src="/admin/vistas/assets/js/main.js"></script>
    <script src="/admin/vistas/assets/js/dashboard.js"></script>
    <script src="/admin/vistas/assets/js/productos.js"></script>
    <script src="/admin/vistas/assets/js/cotizaciones.js"></script>
</body>
</html>
