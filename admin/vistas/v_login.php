<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PCV Admin | Login</title>
  <link rel="icon" href="/assets/img/logo-header.png">
  <link rel="stylesheet" href="/admin/vistas/assets/plugins/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="/admin/vistas/assets/plugins/sweetalert2/sweetalert2.min.css">
  <link rel="stylesheet" href="/admin/vistas/assets/css/admin.css">
</head>
<body class="login-body">
  <div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="card shadow-lg border-0 login-card">
      <div class="card-body p-4 p-md-5">
        <div class="text-center mb-4">
          <img src="/assets/img/logo-header.png" alt="PCV" class="login-logo mb-3">
          <h1 class="h4 mb-1">Panel administrativo</h1>
          <p class="text-muted small mb-0">PCV Soluciones Industriales</p>
        </div>
        <form id="formLogin" autocomplete="off">
          <div class="mb-3">
            <label class="form-label">Usuario</label>
            <input type="text" class="form-control" name="usuario" id="usuario" required placeholder="admin">
          </div>
          <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" class="form-control" name="contrasena" id="contrasena" required placeholder="••••••••">
          </div>
          <button type="submit" class="btn btn-pcv w-100" id="bIngresarLogin">Iniciar sesión <i class="fas fa-check"></i></button>
        </form>
      </div>
    </div>
  </div>
  <script src="/admin/vistas/assets/plugins/jquery-3.7.1.min.js"></script>
  <script src="/admin/vistas/assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
  <script src="/admin/vistas/assets/js/login.js"></script>
</body>
</html>
