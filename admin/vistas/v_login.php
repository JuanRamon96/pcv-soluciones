<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>PCV Soluciones | Acceso al Panel</title>
  <link rel="icon" href="#PCV_ADMIN_BASE#/vistas/assets/images/logos/icon.png?v=3.0" type="image/png">
  <link rel="shortcut icon" href="#PCV_ADMIN_BASE#/vistas/assets/images/logos/favicon.ico?v=3.0" type="image/x-icon">
  <link rel="apple-touch-icon" href="#PCV_ADMIN_BASE#/vistas/assets/images/logos/icon.png?v=3.0">

  <!-- Google Fonts: Plus Jakarta Sans (Spark Admin Core) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Bootstrap Icons & FontAwesome -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="#PCV_ADMIN_BASE#/vistas/assets/plugins/fontawesome/css/all.min.css">

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="#PCV_ADMIN_BASE#/vistas/assets/plugins/bootstrap/css/bootstrap.min.css">

  <!-- Spark Admin Official & Custom CSS -->
  <link rel="stylesheet" href="#PCV_ADMIN_BASE#/vistas/assets/css/spark-admin.css">
  <link rel="stylesheet" href="#PCV_ADMIN_BASE#/vistas/assets/css/spark-admin-custom.css">
</head>

<body>
  <!-- Spark Admin Auth Container -->
  <div class="login-wrapper">
    <!-- Glow Background Shapes -->
    <div class="login-bg-shape login-bg-shape-1"></div>
    <div class="login-bg-shape login-bg-shape-2"></div>

    <!-- Login Card -->
    <div class="login-card">
      <!-- Brand Header -->
      <a href="#PCV_SITE_URL#" class="login-brand text-decoration-none">
        <i class="bi bi-asterisk"></i>
        <span>PCV Soluciones</span>
      </a>

      <h1 class="login-title text-center mt-3">Panel Administrativo</h1>
      <p class="login-subtitle">Ingresa tus datos para administrar el catálogo y las cotizaciones</p>

      <!-- Alert Container (Controlado por login.js) -->
      <div id="mensaAV" style="display:none;" class="mb-3"></div>

      <!-- Formulario Login -->
      <form id="formLogin" novalidate="novalidate">
        <div class="login-form-group">
          <label for="correo" class="login-form-label">Correo o Nombre de Usuario</label>
          <div class="login-input-group">
            <input type="text" class="login-input" placeholder="Correo" id="correo" name="correo" autocomplete="username" required>
            <i class="bi bi-person input-icon"></i>
          </div>
        </div>

        <div class="login-form-group">
          <label for="pass" class="login-form-label">Contraseña</label>
          <div class="login-input-group">
            <input type="password" class="login-input login-input-password" placeholder="Contraseña" id="pass" name="pass" autocomplete="current-password" required>
            <i class="bi bi-lock input-icon"></i>
            <button type="button" class="password-toggle-btn" id="btnTogglePassword" aria-label="Mostrar u ocultar contraseña">
              <i class="bi bi-eye" id="toggleIcon"></i>
            </button>
          </div>
        </div>

        <div class="login-options">
          <label class="custom-control-label">
            <input type="checkbox" class="custom-checkbox-input" checked id="chkRecordar">
            <span>Recordar mi sesión</span>
          </label>
          <span class="text-muted small">Acceso Seguro SSL</span>
        </div>

        <button type="submit" class="btn-login" id="bIngresarLogin">
          <span>Ingresar al Sistema</span>
          <i class="bi bi-arrow-right"></i>
        </button>
      </form>

      <div class="login-divider my-4"></div>

      <!-- Footer Info -->
      <div class="login-footer-text">
        <a href="#PCV_SITE_URL#">
          <i class="bi bi-arrow-left me-1"></i> Regresar a la Landing Page
        </a>
      </div>
    </div>
  </div>

  <!-- Core Scripts -->
  <script src="#PCV_ADMIN_BASE#/vistas/assets/plugins/jquery-3.7.1.min.js"></script>
  <script src="#PCV_ADMIN_BASE#/vistas/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="#PCV_ADMIN_BASE#/vistas/assets/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
  <script src="#PCV_ADMIN_BASE#/vistas/assets/js/login.js"></script>

  <!-- Password toggle script -->
  <script>
    $(document).ready(function() {
      $('#btnTogglePassword').on('click', function() {
        var input = $('#pass');
        var icon = $('#toggleIcon');
        if (input.attr('type') === 'password') {
          input.attr('type', 'text');
          icon.removeClass('bi-eye').addClass('bi-eye-slash');
        } else {
          input.attr('type', 'password');
          icon.removeClass('bi-eye-slash').addClass('bi-eye');
        }
      });
    });
  </script>
</body>
</html>
