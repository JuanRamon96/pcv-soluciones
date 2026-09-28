<?php
class perfil
{
    public function _consultar()
    {
        $userId = (int)($_SESSION['user_pcv']['id'] ?? 0);
        if ($userId <= 0) {
            if (!headers_sent()) {
                header('Content-Type: application/json; charset=utf-8');
            }
            echo json_encode(['ok' => false, 'error' => 'Sesión no válida o expirada.']);
            return;
        }

        $omodelo = new m_modelo();
        $row = $omodelo->_consultar("SELECT id, usuario, nombre, correo, creado_en FROM usuarios WHERE id = $userId LIMIT 1");
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }
        if (is_array($row) && !empty($row)) {
            echo json_encode(['ok' => true, 'data' => $row[0]], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['ok' => false, 'error' => 'No se encontró el registro de usuario.']);
        }
    }

    public function _modificar()
    {
        $userId = (int)($_SESSION['user_pcv']['id'] ?? 0);
        if ($userId <= 0) {
            if (!headers_sent()) {
                header('Content-Type: application/json; charset=utf-8');
            }
            echo json_encode(['ok' => false, 'error' => 'Sesión no válida o expirada. Por favor recarga e inicia sesión nuevamente.']);
            return;
        }

        $omodelo = new m_modelo();
        $nombre = trim($_POST['nombre'] ?? '');
        $usuario = trim($_POST['usuario'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $password_actual = trim($_POST['password_actual'] ?? '');
        $password_nueva = trim($_POST['password_nueva'] ?? '');
        $password_confirmar = trim($_POST['password_confirmar'] ?? '');

        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }

        // Validaciones básicas de campos
        if ($nombre === '') {
            echo json_encode(['ok' => false, 'error' => 'El nombre completo es obligatorio.']);
            return;
        }

        if ($usuario === '' || strlen($usuario) < 3) {
            echo json_encode(['ok' => false, 'error' => 'El nombre de usuario debe contener al menos 3 caracteres.']);
            return;
        }

        if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $usuario)) {
            echo json_encode(['ok' => false, 'error' => 'El usuario sólo puede contener letras, números, puntos, guiones y guiones bajos (sin espacios).']);
            return;
        }

        if ($correo === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['ok' => false, 'error' => 'Ingresa un correo electrónico válido.']);
            return;
        }

        // Validar unicidad del nombre de usuario
        $uEsc = $omodelo->esc($usuario);
        $checkUser = $omodelo->_consultar("SELECT id FROM usuarios WHERE usuario = '$uEsc' AND id != $userId LIMIT 1");
        if (is_array($checkUser) && $omodelo->numerofilas > 0) {
            echo json_encode(['ok' => false, 'error' => 'El nombre de usuario "' . htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8') . '" ya está en uso por otra cuenta.']);
            return;
        }

        // Validar unicidad del correo electrónico
        $cEsc = $omodelo->esc($correo);
        $checkCorreo = $omodelo->_consultar("SELECT id FROM usuarios WHERE correo = '$cEsc' AND id != $userId LIMIT 1");
        if (is_array($checkCorreo) && $omodelo->numerofilas > 0) {
            echo json_encode(['ok' => false, 'error' => 'El correo electrónico "' . htmlspecialchars($correo, ENT_QUOTES, 'UTF-8') . '" ya está en uso por otra cuenta.']);
            return;
        }

        // Obtener datos actuales del usuario
        $currUser = $omodelo->_consultar("SELECT * FROM usuarios WHERE id = $userId LIMIT 1");
        if (!is_array($currUser) || empty($currUser)) {
            echo json_encode(['ok' => false, 'error' => 'No se encontró el registro de usuario en la base de datos.']);
            return;
        }
        $currentHash = $currUser[0]['password_hash'];

        // Manejo de cambio de contraseña
        $updatePassSql = "";
        $passwordCambiado = false;

        if ($password_nueva !== '' || $password_confirmar !== '') {
            if ($password_actual === '') {
                echo json_encode(['ok' => false, 'error' => 'Debes ingresar tu contraseña actual para autorizar el cambio de contraseña.']);
                return;
            }

            if (!password_verify($password_actual, $currentHash)) {
                echo json_encode(['ok' => false, 'error' => 'La contraseña actual ingresada es incorrecta.']);
                return;
            }

            if (strlen($password_nueva) < 6) {
                echo json_encode(['ok' => false, 'error' => 'La nueva contraseña debe tener al menos 6 caracteres.']);
                return;
            }

            if ($password_nueva !== $password_confirmar) {
                echo json_encode(['ok' => false, 'error' => 'La confirmación de la nueva contraseña no coincide.']);
                return;
            }

            $newHash = password_hash($password_nueva, PASSWORD_DEFAULT);
            $updatePassSql = ", password_hash = '" . $omodelo->esc($newHash) . "'";
            $passwordCambiado = true;
        }

        $nEsc = $omodelo->esc($nombre);
        $sql = "UPDATE usuarios SET nombre = '$nEsc', usuario = '$uEsc', correo = '$cEsc' $updatePassSql WHERE id = $userId";
        $res = $omodelo->_insertar($sql);

        if ($res === 'si') {
            echo json_encode(['ok' => false, 'error' => 'Error al actualizar en la base de datos: ' . mysqli_error($omodelo->link)]);
            return;
        }

        // Actualizar sesión activa
        $_SESSION['user_pcv']['nombre'] = $nombre;
        $_SESSION['user_pcv']['usuario'] = $usuario;
        $_SESSION['user_pcv']['correo'] = $correo;

        echo json_encode([
            'ok' => true,
            'message' => $passwordCambiado 
                ? '¡Perfil y contraseña actualizados exitosamente!' 
                : '¡Datos de perfil actualizados exitosamente!',
            'password_cambiado' => $passwordCambiado,
            'data' => [
                'id' => $userId,
                'nombre' => $nombre,
                'usuario' => $usuario,
                'correo' => $correo
            ]
        ], JSON_UNESCAPED_UNICODE);
    }
}
