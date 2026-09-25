<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>PCV Soluciones | Login</title>
  <link rel="icon" href="/admin/vistas/assets/images/logos/icon.png" type="image/x-icon">
  
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/admin/vistas/assets/fonts/icomoon/style.css">
  <link rel="stylesheet" href="/admin/vistas/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/admin/vistas/assets/plugins/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="/admin/vistas/assets/css/style.css">

</head>

<body class="">
  <div class="d-lg-flex half">
    <div class="bg order-1 order-md-2">
      <img src="/admin/vistas/assets/images/logos/logo.png" width="30%" class="img-logo">
    </div>
    <div class="contents order-2 order-md-1">

      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-md-8">
            <h3><strong>Bienvenido</strong> de regreso</h3>
            <p class="mb-4">PCV Soluciones Industriales — ingresa tu usuario y contraseña.</p>
            <div id="mensaAV">
              
            </div>
            <form id="formLogin">
              <div class="form-group first">
                <label for="usuario">Usuario</label>
                <input type="text" class="form-control" placeholder="Usuario" id="usuario" name="usuario" autocomplete="username">
              </div>
              <div class="form-group last mb-3">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control" placeholder="Contraseña" id="pass" name="pass">
              </div>
              
              <div class="d-flex mb-5 align-items-center">
                <label class="control control--checkbox mb-0"><span class="caption"> Recordarme</span>
                  <input type="checkbox" checked="checked"/>
                  <div class="control__indicator"></div>
                </label>
              </div>

              <button type="submit" class="btn btn-block btn-primary" id="bIngresarLogin">Iniciar <i class="fas fa-check"></i></button>

            </form>
          </div>
        </div>
      </div>
    </div>

    
  </div>

  <!--   Core JS Files   -->
  <script src="/admin/vistas/assets/plugins/jquery-3.7.1.min.js"></script>
  <script src="/admin/vistas/assets/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
  <script src="/admin/vistas/assets/js/login.js"></script>
</body>

</html>