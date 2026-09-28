<?php
class usuarios
{
    public function _consultar()
    {
        $omodelo = new m_modelo();
        $currentUserId = (int)($_SESSION['user_pcv']['id'] ?? 0);

        $buscar = $omodelo->esc(trim($_POST['buscar'] ?? ''));
        $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 25;
        if ($limit <= 0) $limit = 25;
        $pagina = isset($_POST['pagina']) ? max(1, (int)$_POST['pagina']) : 1;
        $offset = ($pagina - 1) * $limit;

        $ordenCol = $_POST['ordenColumna'] ?? '';
        $ordenDir = (isset($_POST['orden']) && strtolower($_POST['orden']) === 'desc') ? 'DESC' : 'ASC';

        $colMap = [
            'id' => 'id',
            'nombre' => 'nombre',
            'usuario' => 'usuario',
            'correo' => 'correo',
            'estatus' => 'estatus',
            'creado_en' => 'creado_en'
        ];
        $orderBy = isset($colMap[$ordenCol]) ? "{$colMap[$ordenCol]} $ordenDir" : "id DESC";

        // Excluir siempre al usuario actual para evitar auto-eliminación o auto-desactivación
        $where = "id != $currentUserId";
        if ($buscar !== '') {
            $where .= " AND (nombre LIKE '%$buscar%' OR usuario LIKE '%$buscar%' OR correo LIKE '%$buscar%')";
        }

        // Conteo total para paginación de myDataTable
        $countRes = $omodelo->_consultar("SELECT COUNT(id) AS total FROM usuarios WHERE $where");
        $totalRows = ($countRes !== 'si' && isset($countRes[0]['total'])) ? (int)$countRes[0]['total'] : 0;

        $sql = "SELECT id, usuario, nombre, correo, estatus, creado_en 
                FROM usuarios 
                WHERE $where 
                ORDER BY $orderBy 
                LIMIT $offset, $limit";
        $rows = $omodelo->_consultar($sql);

        $data = [];
        if ($rows !== 'si' && $omodelo->numerofilas > 0) {
            foreach ($rows as $r) {
                $isActivo = ($r['estatus'] === 'activo');
                $estatusBadge = $isActivo 
                    ? '<span class="badge-table success">Activo</span>' 
                    : '<span class="badge-table failed">Inactivo</span>';

                $initial = strtoupper(substr($r['nombre'], 0, 1));

                $nombreCol = '
                <div class="d-flex align-items-center gap-2 text-start ps-2">
                    <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 36px; height: 36px; font-size: 0.85rem;">
                        ' . htmlspecialchars($initial, ENT_QUOTES, 'UTF-8') . '
                    </div>
                    <div>
                        <div class="fw-bold text-dark">' . htmlspecialchars($r['nombre'], ENT_QUOTES, 'UTF-8') . '</div>
                        <small class="text-muted" style="font-size: 0.72rem;">Administrador PCV</small>
                    </div>
                </div>';

                $usuarioCol = '<span class="badge bg-light text-dark border px-2 py-1"><i class="bi bi-at text-muted"></i>' . htmlspecialchars($r['usuario'], ENT_QUOTES, 'UTF-8') . '</span>';
                $correoCol = '<a href="mailto:' . htmlspecialchars($r['correo'], ENT_QUOTES, 'UTF-8') . '" class="text-secondary text-decoration-none small"><i class="bi bi-envelope me-1 text-muted"></i>' . htmlspecialchars($r['correo'], ENT_QUOTES, 'UTF-8') . '</a>';
                $fechaCol = '<span class="small text-muted">' . date('d/m/Y H:i', strtotime($r['creado_en'])) . '</span>';

                $accionesCol = '
                <div class="d-inline-flex align-items-center gap-1">
                    <button type="button" class="table-btn-action bEditarUsuario" data-id="' . (int)$r['id'] . '" title="Editar Usuario"><i class="bi bi-pencil"></i></button>
                    <button type="button" class="table-btn-action delete bEliminarUsuario" data-id="' . (int)$r['id'] . '" data-nombre="' . htmlspecialchars($r['nombre'], ENT_QUOTES, 'UTF-8') . '" title="Eliminar Usuario"><i class="bi bi-trash3"></i></button>
                </div>';

                $data[] = [
                    'id' => '<span class="text-muted fw-bold">#' . (int)$r['id'] . '</span>',
                    'nombre' => $nombreCol,
                    'usuario' => $usuarioCol,
                    'correo' => $correoCol,
                    'estatus' => $estatusBadge,
                    'creado_en' => $fechaCol,
                    'acciones' => $accionesCol
                ];
            }
        }

        echo json_encode([
            'data' => $data,
            'totales' => [
                'NumRows' => $totalRows
            ]
        ], JSON_UNESCAPED_UNICODE);
    }

    public function _detalles()
    {
        $omodelo = new m_modelo();
        $id = (int)($_POST['id'] ?? 0);
        $row = $omodelo->_consultar("SELECT id, usuario, nombre, correo, estatus FROM usuarios WHERE id = $id LIMIT 1");

        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }
        if (is_array($row) && !empty($row)) {
            echo json_encode(['ok' => true, 'data' => $row[0]], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['ok' => false, 'error' => 'Usuario no encontrado']);
        }
    }

    public function _insertar()
    {
        $omodelo = new m_modelo();
        $nombre = trim($_POST['nombre'] ?? '');
        $usuario = trim($_POST['usuario'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $estatus = (isset($_POST['estatus']) && $_POST['estatus'] === 'inactivo') ? 'inactivo' : 'activo';

        if ($nombre === '') {
            echo 'El nombre completo es obligatorio.';
            return;
        }

        if ($usuario === '' || strlen($usuario) < 3) {
            echo 'El nombre de usuario debe tener al menos 3 caracteres.';
            return;
        }

        if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $usuario)) {
            echo 'El usuario sólo puede contener letras, números, puntos, guiones y guiones bajos (sin espacios).';
            return;
        }

        if ($correo === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            echo 'Ingresa un correo electrónico válido.';
            return;
        }

        if ($password === '' || strlen($password) < 6) {
            echo 'La contraseña debe tener al menos 6 caracteres.';
            return;
        }

        // Validar que usuario no esté registrado
        $uEsc = $omodelo->esc($usuario);
        $checkUser = $omodelo->_consultar("SELECT id FROM usuarios WHERE usuario = '$uEsc' LIMIT 1");
        if (is_array($checkUser) && $omodelo->numerofilas > 0) {
            echo 'El nombre de usuario "' . htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8') . '" ya está en uso.';
            return;
        }

        // Validar que correo no esté registrado
        $cEsc = $omodelo->esc($correo);
        $checkCorreo = $omodelo->_consultar("SELECT id FROM usuarios WHERE correo = '$cEsc' LIMIT 1");
        if (is_array($checkCorreo) && $omodelo->numerofilas > 0) {
            echo 'El correo electrónico "' . htmlspecialchars($correo, ENT_QUOTES, 'UTF-8') . '" ya está registrado.';
            return;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $hEsc = $omodelo->esc($hash);
        $nEsc = $omodelo->esc($nombre);

        $sql = "INSERT INTO usuarios (usuario, nombre, correo, password_hash, estatus, creado_en)
                VALUES ('$uEsc', '$nEsc', '$cEsc', '$hEsc', '$estatus', NOW())";
        $res = $omodelo->_insertar($sql);

        if ($res === 'si') {
            echo 'Error de base de datos: ' . mysqli_error($omodelo->link);
            return;
        }

        echo 'Correcto';
    }

    public function _modificar()
    {
        $omodelo = new m_modelo();
        $currentUserId = (int)($_SESSION['user_pcv']['id'] ?? 0);
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            echo 'ID de usuario inválido.';
            return;
        }

        if ($id === $currentUserId) {
            echo 'No puedes modificar tu propia cuenta desde este módulo. Por favor usa "Mi Perfil".';
            return;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $usuario = trim($_POST['usuario'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $estatus = (isset($_POST['estatus']) && $_POST['estatus'] === 'inactivo') ? 'inactivo' : 'activo';

        if ($nombre === '') {
            echo 'El nombre completo es obligatorio.';
            return;
        }

        if ($usuario === '' || strlen($usuario) < 3) {
            echo 'El nombre de usuario debe tener al menos 3 caracteres.';
            return;
        }

        if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $usuario)) {
            echo 'El usuario sólo puede contener letras, números, puntos, guiones y guiones bajos (sin espacios).';
            return;
        }

        if ($correo === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            echo 'Ingresa un correo electrónico válido.';
            return;
        }

        // Validar unicidad usuario
        $uEsc = $omodelo->esc($usuario);
        $checkUser = $omodelo->_consultar("SELECT id FROM usuarios WHERE usuario = '$uEsc' AND id != $id LIMIT 1");
        if (is_array($checkUser) && $omodelo->numerofilas > 0) {
            echo 'El nombre de usuario "' . htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8') . '" ya está en uso por otra cuenta.';
            return;
        }

        // Validar unicidad correo
        $cEsc = $omodelo->esc($correo);
        $checkCorreo = $omodelo->_consultar("SELECT id FROM usuarios WHERE correo = '$cEsc' AND id != $id LIMIT 1");
        if (is_array($checkCorreo) && $omodelo->numerofilas > 0) {
            echo 'El correo electrónico "' . htmlspecialchars($correo, ENT_QUOTES, 'UTF-8') . '" ya está en uso por otra cuenta.';
            return;
        }

        $passSql = "";
        if ($password !== '') {
            if (strlen($password) < 6) {
                echo 'La contraseña debe tener al menos 6 caracteres.';
                return;
            }
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $hEsc = $omodelo->esc($hash);
            $passSql = ", password_hash = '$hEsc'";
        }

        $nEsc = $omodelo->esc($nombre);
        $sql = "UPDATE usuarios SET nombre = '$nEsc', usuario = '$uEsc', correo = '$cEsc', estatus = '$estatus' $passSql WHERE id = $id";
        $res = $omodelo->_insertar($sql);

        if ($res === 'si') {
            echo 'Error de base de datos: ' . mysqli_error($omodelo->link);
            return;
        }

        echo 'Correcto';
    }

    public function _eliminar()
    {
        $omodelo = new m_modelo();
        $currentUserId = (int)($_SESSION['user_pcv']['id'] ?? 0);
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            echo 'ID de usuario inválido.';
            return;
        }

        if ($id === $currentUserId) {
            echo 'Por seguridad, no puedes eliminar tu propia cuenta de usuario.';
            return;
        }

        $res = $omodelo->_insertar("DELETE FROM usuarios WHERE id = $id");
        if ($res === 'si') {
            echo 'Error de base de datos: ' . mysqli_error($omodelo->link);
            return;
        }

        echo 'Correcto';
    }
}
