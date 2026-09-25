<?php
class productos
{
    private function slugify($text)
    {
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        $text = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $text));
        return trim($text, '-') ?: 'item-' . time();
    }

    private function saveImage($field = 'imagen')
    {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
            return null;
        }
        if (!is_dir(PCV_UPLOADS)) {
            mkdir(PCV_UPLOADS, 0775, true);
        }
        $name = 'p_' . date('YmdHis') . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
        $dest = PCV_UPLOADS . '/' . $name;
        if (!move_uploaded_file($_FILES[$field]['tmp_name'], $dest)) {
            return null;
        }
        return $name;
    }

    public function _consultar()
    {
        $omodelo = new m_modelo();
        $filtro = $omodelo->esc($_POST['filtro'] ?? '');
        $tipo = $omodelo->esc($_POST['tipo'] ?? '');
        $where = '1=1';
        if ($filtro !== '') {
            $where .= " AND (p.nombre LIKE '%$filtro%' OR p.resumen LIKE '%$filtro%')";
        }
        if ($tipo === 'servicio' || $tipo === 'producto') {
            $where .= " AND p.tipo = '$tipo'";
        }
        $sql = "SELECT p.id, p.tipo, p.nombre, p.slug, p.activo, p.destacado, p.imagen_principal,
                       c.nombre AS clasificacion, s.nombre AS subtipo
                FROM productos p
                INNER JOIN clasificaciones c ON c.id = p.clasificacion_id
                LEFT JOIN subtipos s ON s.id = p.subtipo_id
                WHERE $where
                ORDER BY FIELD(p.tipo,'servicio','producto'), p.orden, p.id DESC";
        $row = $omodelo->_consultar($sql);
        if ($row === 'si') {
            echo 'Error: ' . mysqli_error($omodelo->link);
            return;
        }
        $html = '';
        if ($omodelo->numerofilas > 0) {
            foreach ($row as $r) {
                $img = pcv_img_producto($r['imagen_principal']);
                // Adjust relative URL for admin context
                if (strpos($img, '/uploads/') === 0 || strpos($img, '/assets/') === 0) {
                    $img = '..' . $img;
                }
                $badge = $r['tipo'] === 'servicio' ? 'success' : 'primary';
                $estado = (int)$r['activo'] ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>';
                $html .= '<tr>
                    <td><img src="' . pcv_esc($img) . '" alt="" style="width:48px;height:36px;object-fit:cover;border-radius:4px"></td>
                    <td><span class="badge bg-' . $badge . '">' . pcv_esc($r['tipo']) . '</span></td>
                    <td>' . pcv_esc($r['nombre']) . '</td>
                    <td>' . pcv_esc($r['clasificacion']) . '</td>
                    <td>' . pcv_esc($r['subtipo'] ?? '—') . '</td>
                    <td>' . $estado . '</td>
                    <td class="text-nowrap">
                      <button type="button" class="btn btn-sm btn-outline-primary bEditarProducto" data-id="' . (int)$r['id'] . '"><i class="fas fa-edit"></i></button>
                      <button type="button" class="btn btn-sm btn-outline-danger bEliminarProducto" data-id="' . (int)$r['id'] . '"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>';
            }
        }
        echo $html !== '' ? $html : '<tr><td colspan="7" class="text-center text-muted">Sin registros</td></tr>';
    }

    public function _detalles()
    {
        $omodelo = new m_modelo();
        $id = (int)($_POST['id'] ?? 0);
        $row = $omodelo->_consultar("SELECT * FROM productos WHERE id = $id LIMIT 1");
        if ($row === 'si' || $omodelo->numerofilas < 1) {
            echo json_encode(['ok' => false]);
            return;
        }
        $imgs = $omodelo->_consultar("SELECT id, archivo FROM producto_imagenes WHERE producto_id = $id ORDER BY orden, id");
        $galeria = is_array($imgs) ? $imgs : [];
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => true, 'data' => $row[0], 'galeria' => $galeria]);
    }

    public function _insertar()
    {
        $omodelo = new m_modelo();
        $tipo = ($_POST['tipo'] ?? '') === 'producto' ? 'producto' : 'servicio';
        $nombre = $omodelo->esc($_POST['nombre'] ?? '');
        $resumen = $omodelo->esc($_POST['resumen'] ?? '');
        $descripcion = $omodelo->esc($_POST['descripcion'] ?? '');
        $clasificacion_id = (int)($_POST['clasificacion_id'] ?? 0);
        $subtipo_id = (int)($_POST['subtipo_id'] ?? 0);
        $destacado = isset($_POST['destacado']) ? 1 : 0;
        $activo = isset($_POST['activo']) ? 1 : 0;
        $orden = (int)($_POST['orden'] ?? 0);
        if ($nombre === '' || $clasificacion_id < 1) {
            echo 'Datos incompletos';
            return;
        }
        $slug = $this->slugify($nombre);
        $check = $omodelo->_consultar("SELECT id FROM productos WHERE slug = '$slug'");
        if (is_array($check) && $omodelo->numerofilas > 0) {
            $slug .= '-' . time();
        }
        $img = $this->saveImage('imagen');
        $imgSql = $img ? "'$img'" : 'NULL';
        $subSql = $subtipo_id > 0 ? $subtipo_id : 'NULL';
        $q = "INSERT INTO productos SET tipo='$tipo', clasificacion_id=$clasificacion_id, subtipo_id=$subSql,
              nombre='$nombre', slug='$slug', resumen='$resumen', descripcion='$descripcion',
              imagen_principal=$imgSql, destacado=$destacado, activo=$activo, orden=$orden";
        $err = $omodelo->_insertar($q);
        if ($err === 'si') {
            echo 'Error: ' . mysqli_error($omodelo->link);
            return;
        }
        $pid = $omodelo->insert_id;
        if ($img) {
            $omodelo->_insertar("INSERT INTO producto_imagenes SET producto_id=$pid, archivo='$img', orden=0");
        }
        echo 'Correcto';
    }

    public function _modificar()
    {
        $omodelo = new m_modelo();
        $id = (int)($_POST['id'] ?? 0);
        if ($id < 1) {
            echo 'ID inválido';
            return;
        }
        $tipo = ($_POST['tipo'] ?? '') === 'producto' ? 'producto' : 'servicio';
        $nombre = $omodelo->esc($_POST['nombre'] ?? '');
        $resumen = $omodelo->esc($_POST['resumen'] ?? '');
        $descripcion = $omodelo->esc($_POST['descripcion'] ?? '');
        $clasificacion_id = (int)($_POST['clasificacion_id'] ?? 0);
        $subtipo_id = (int)($_POST['subtipo_id'] ?? 0);
        $destacado = isset($_POST['destacado']) ? 1 : 0;
        $activo = isset($_POST['activo']) ? 1 : 0;
        $orden = (int)($_POST['orden'] ?? 0);
        $subSql = $subtipo_id > 0 ? $subtipo_id : 'NULL';
        $img = $this->saveImage('imagen');
        $extra = '';
        if ($img) {
            $extra = ", imagen_principal='$img'";
            $omodelo->_insertar("INSERT INTO producto_imagenes SET producto_id=$id, archivo='$img', orden=0");
        }
        $q = "UPDATE productos SET tipo='$tipo', clasificacion_id=$clasificacion_id, subtipo_id=$subSql,
              nombre='$nombre', resumen='$resumen', descripcion='$descripcion',
              destacado=$destacado, activo=$activo, orden=$orden $extra WHERE id=$id";
        $err = $omodelo->_insertar($q);
        if ($err === 'si') {
            echo 'Error: ' . mysqli_error($omodelo->link);
            return;
        }
        echo 'Correcto';
    }

    public function _eliminar()
    {
        $omodelo = new m_modelo();
        $id = (int)($_POST['id'] ?? 0);
        $err = $omodelo->_insertar("UPDATE productos SET activo=0 WHERE id=$id");
        if ($err === 'si') {
            echo 'Error: ' . mysqli_error($omodelo->link);
            return;
        }
        echo 'Correcto';
    }
}
