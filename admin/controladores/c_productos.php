<?php
class productos
{
    private function slugify($text)
    {
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        $text = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $text));
        return trim($text, '-') ?: 'item-' . time();
    }

    /**
     * Procesa, optimiza y comprime una imagen a un peso máximo de 1 MB.
     * Asigna un identificador único para prevenir sobreescrituras por nombres duplicados.
     */
    private function saveAndCompressImage($tmpPath, $origName)
    {
        if (empty($tmpPath) || !is_file($tmpPath)) {
            return null;
        }
        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
            return null;
        }
        if (!is_dir(PCV_UPLOADS)) {
            mkdir(PCV_UPLOADS, 0775, true);
        }

        $extNorm = ($ext === 'jpeg') ? 'jpg' : $ext;
        // Identificador único garantizado
        $uniqueName = 'p_' . date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.' . $extNorm;
        $destPath = PCV_UPLOADS . '/' . $uniqueName;

        $maxBytes = 1048576; // 1 MB exactamente

        // Cargar recurso GD según formato
        $imgResource = null;
        switch ($extNorm) {
            case 'jpg':
                if (function_exists('imagecreatefromjpeg')) $imgResource = @imagecreatefromjpeg($tmpPath);
                break;
            case 'png':
                if (function_exists('imagecreatefrompng')) $imgResource = @imagecreatefrompng($tmpPath);
                break;
            case 'webp':
                if (function_exists('imagecreatefromwebp')) $imgResource = @imagecreatefromwebp($tmpPath);
                break;
            case 'gif':
                if (function_exists('imagecreatefromgif')) $imgResource = @imagecreatefromgif($tmpPath);
                break;
        }

        if (!$imgResource) {
            // Si GD no puede procesarla por algún formato exótico, copiar directamente
            if (@move_uploaded_file($tmpPath, $destPath) || @copy($tmpPath, $destPath)) {
                return $uniqueName;
            }
            return null;
        }

        $origW = imagesx($imgResource);
        $origH = imagesy($imgResource);

        // Si la imagen es gigante, redimensionar a máx 1920x1920 manteniendo proporción
        $maxDim = 1920;
        $targetW = $origW;
        $targetH = $origH;

        if ($origW > $maxDim || $origH > $maxDim) {
            $ratio = min($maxDim / $origW, $maxDim / $origH);
            $targetW = max(1, (int)round($origW * $ratio));
            $targetH = max(1, (int)round($origH * $ratio));
        }

        $canvas = imagecreatetruecolor($targetW, $targetH);

        // Preservar transparencia para PNG y WEBP
        if ($extNorm === 'png' || $extNorm === 'webp') {
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            $transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
            imagefilledrectangle($canvas, 0, 0, $targetW, $targetH, $transparent);
        }

        imagecopyresampled($canvas, $imgResource, 0, 0, 0, 0, $targetW, $targetH, $origW, $origH);
        imagedestroy($imgResource);

        // Guardar inicialmente a 85% de calidad
        $quality = 85;
        $this->writeGdImage($canvas, $destPath, $extNorm, $quality);

        // Si la imagen supera 1 MB, reducir calidad y/o dimensiones iterativamente hasta que pese <= 1 MB
        $iter = 0;
        while (is_file($destPath) && filesize($destPath) > $maxBytes && $iter < 6) {
            $iter++;
            $quality -= 10;
            if ($quality < 35) {
                // Reducir dimensiones 20%
                $newW = max(400, (int)round(imagesx($canvas) * 0.8));
                $newH = max(300, (int)round(imagesy($canvas) * 0.8));
                $resizedCanvas = imagecreatetruecolor($newW, $newH);
                if ($extNorm === 'png' || $extNorm === 'webp') {
                    imagealphablending($resizedCanvas, false);
                    imagesavealpha($resizedCanvas, true);
                    $transparent = imagecolorallocatealpha($resizedCanvas, 255, 255, 255, 127);
                    imagefilledrectangle($resizedCanvas, 0, 0, $newW, $newH, $transparent);
                }
                imagecopyresampled($resizedCanvas, $canvas, 0, 0, 0, 0, $newW, $newH, imagesx($canvas), imagesy($canvas));
                imagedestroy($canvas);
                $canvas = $resizedCanvas;
                $quality = 75;
            }
            $this->writeGdImage($canvas, $destPath, $extNorm, $quality);
        }

        imagedestroy($canvas);
        return $uniqueName;
    }

    private function writeGdImage($canvas, $destPath, $extNorm, $quality)
    {
        switch ($extNorm) {
            case 'png':
                $pngComp = min(9, max(0, (int)round((100 - $quality) / 10)));
                imagepng($canvas, $destPath, $pngComp);
                break;
            case 'webp':
                imagewebp($canvas, $destPath, $quality);
                break;
            case 'gif':
                imagegif($canvas, $destPath);
                break;
            case 'jpg':
            default:
                imagejpeg($canvas, $destPath, $quality);
                break;
        }
    }

    /**
     * Elimina el archivo físico de disco de forma segura, protegiendo imágenes del sistema.
     */
    private function borrarArchivoFisico($archivo)
    {
        if (empty($archivo)) return;
        $clean = basename($archivo);
        $protegidos = [
            'default.jpg', 'default.png', 'logo.png', 'no-image.png',
            'item-1.png', 'item-2.png', 'item-3.png', 'item-4.png', 'item-5.png', 'item-6.png'
        ];
        if (in_array(strtolower($clean), $protegidos, true)) {
            return;
        }
        $rutas = [
            PCV_UPLOADS . '/' . $clean,
            PCV_ROOT . '/uploads/' . $clean
        ];
        foreach ($rutas as $p) {
            if (is_file($p)) {
                @unlink($p);
            }
        }
    }

    /**
     * Guarda múltiples imágenes en la galería secundaria con reducción a <= 1 MB.
     */
    private function guardarImagenesGaleria($productoId, $files)
    {
        if (empty($files) || !isset($files['name']) || !is_array($files['name'])) {
            return;
        }
        $omodelo = new m_modelo();
        $total = count($files['name']);
        for ($i = 0; $i < $total; $i++) {
            if (isset($files['error'][$i]) && $files['error'][$i] === UPLOAD_ERR_OK) {
                $saved = $this->saveAndCompressImage($files['tmp_name'][$i], $files['name'][$i]);
                if ($saved) {
                    $omodelo->_insertar("INSERT INTO producto_imagenes SET producto_id=$productoId, archivo='$saved', orden=$i");
                }
            }
        }
    }

    public function _consultar()
    {
        $omodelo = new m_modelo();
        $buscar = $omodelo->esc($_POST['buscar'] ?? $_POST['filtro'] ?? '');
        $tipo = $omodelo->esc($_POST['tipo'] ?? '');

        $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 25;
        if ($limit <= 0) $limit = 25;
        $pagina = isset($_POST['pagina']) ? max(1, (int)$_POST['pagina']) : 1;
        $offset = ($pagina - 1) * $limit;

        $ordenCol = $_POST['ordenColumna'] ?? '';
        $ordenDir = (isset($_POST['orden']) && strtolower($_POST['orden']) === 'desc') ? 'DESC' : 'ASC';

        $colMap = [
            'tipo' => 'p.tipo',
            'nombre' => 'p.nombre',
            'clasificacion' => 'c.nombre',
            'subtipo' => 's.nombre',
            'estado' => 'p.activo'
        ];
        $orderBy = isset($colMap[$ordenCol]) ? "{$colMap[$ordenCol]} $ordenDir" : "FIELD(p.tipo,'servicio','producto'), p.orden, p.id DESC";

        $where = '1=1';
        if ($buscar !== '') {
            $where .= " AND (p.nombre LIKE '%$buscar%' OR p.resumen LIKE '%$buscar%' OR c.nombre LIKE '%$buscar%' OR s.nombre LIKE '%$buscar%')";
        }
        if ($tipo === 'servicio' || $tipo === 'producto') {
            $where .= " AND p.tipo = '$tipo'";
        }

        // Conteo total para paginación de myDataTable
        $countSql = "SELECT COUNT(p.id) AS total
                     FROM productos p
                     INNER JOIN clasificaciones c ON c.id = p.clasificacion_id
                     LEFT JOIN subtipos s ON s.id = p.subtipo_id
                     WHERE $where";
        $countRes = $omodelo->_consultar($countSql);
        $totalRows = ($countRes !== 'si' && isset($countRes[0]['total'])) ? (int)$countRes[0]['total'] : 0;

        $sql = "SELECT p.id, p.tipo, p.nombre, p.slug, p.activo, p.destacado, p.imagen_principal,
                       c.nombre AS clasificacion, s.nombre AS subtipo
                FROM productos p
                INNER JOIN clasificaciones c ON c.id = p.clasificacion_id
                LEFT JOIN subtipos s ON s.id = p.subtipo_id
                WHERE $where
                ORDER BY $orderBy
                LIMIT $offset, $limit";
        $row = $omodelo->_consultar($sql);

        $data = [];
        if ($row !== 'si' && $omodelo->numerofilas > 0) {
            foreach ($row as $r) {
                $img = pcv_img_producto($r['imagen_principal']);
                if (strpos($img, '/uploads/') === 0 || strpos($img, '/assets/') === 0) {
                    $img = '..' . $img;
                }
                $typeBadge = $r['tipo'] === 'servicio' 
                    ? '<span class="badge-spark-service"><i class="bi bi-tools me-1"></i>Servicio</span>' 
                    : '<span class="badge-spark-product"><i class="bi bi-box-seam me-1"></i>Producto</span>';
                $estado = (int)$r['activo'] 
                    ? '<span class="badge-table success">Activo</span>' 
                    : '<span class="badge-table failed">Inactivo</span>';
                $subtipoText = !empty($r['subtipo']) ? pcv_esc($r['subtipo']) : '<span class="text-muted">—</span>';

                $data[] = [
                    'ID' => (int)$r['id'],
                    'imagen' => '<img src="' . pcv_esc($img) . '" alt="" style="width:44px;height:44px;object-fit:cover;border-radius:10px;border:1px solid #E2E8F0;background:#F8FAFC;">',
                    'tipo' => $typeBadge,
                    'nombre' => '<strong class="text-dark">' . pcv_esc($r['nombre']) . '</strong>',
                    'clasificacion' => '<span class="badge bg-light text-secondary border px-2 py-1 small">' . pcv_esc($r['clasificacion']) . '</span>',
                    'subtipo' => $subtipoText,
                    'estado' => $estado,
                    'acciones' => '<div class="d-inline-flex align-items-center gap-1">
                        <button type="button" class="table-btn-action bEditarProducto" data-id="' . (int)$r['id'] . '" title="Editar"><i class="bi bi-pencil"></i></button>
                        <button type="button" class="table-btn-action delete bEliminarProducto" data-id="' . (int)$r['id'] . '" title="Eliminar"><i class="bi bi-trash3"></i></button>
                      </div>'
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
        $row = $omodelo->_consultar("SELECT * FROM productos WHERE id = $id LIMIT 1");
        if ($row === 'si' || $omodelo->numerofilas < 1) {
            echo json_encode(['ok' => false]);
            return;
        }

        $prod = $row[0];
        $prod['imagen_url'] = !empty($prod['imagen_principal']) ? pcv_img_producto($prod['imagen_principal']) : '';

        $imgs = $omodelo->_consultar("SELECT id, archivo, orden FROM producto_imagenes WHERE producto_id = $id ORDER BY orden, id");
        $galeria = [];
        if (is_array($imgs)) {
            foreach ($imgs as $g) {
                $galeria[] = [
                    'id' => (int)$g['id'],
                    'archivo' => $g['archivo'],
                    'url' => pcv_img_producto($g['archivo'])
                ];
            }
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => true, 'data' => $prod, 'galeria' => $galeria]);
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

        // Imagen principal
        $img = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $img = $this->saveAndCompressImage($_FILES['imagen']['tmp_name'], $_FILES['imagen']['name']);
        }
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

        // Galería de imágenes adicionales
        if (isset($_FILES['imagenes_galeria'])) {
            $this->guardarImagenesGaleria($pid, $_FILES['imagenes_galeria']);
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

        // Consultar registro actual para gestionar imagen principal previa
        $curr = $omodelo->_consultar("SELECT imagen_principal FROM productos WHERE id=$id LIMIT 1");
        $oldPrincipal = (is_array($curr) && !empty($curr[0]['imagen_principal'])) ? $curr[0]['imagen_principal'] : null;

        $extra = '';
        $quitarPrincipal = isset($_POST['quitar_imagen_principal']) && $_POST['quitar_imagen_principal'] == '1';

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $newImg = $this->saveAndCompressImage($_FILES['imagen']['tmp_name'], $_FILES['imagen']['name']);
            if ($newImg) {
                if ($oldPrincipal && $oldPrincipal !== $newImg) {
                    $this->borrarArchivoFisico($oldPrincipal);
                }
                $extra = ", imagen_principal='$newImg'";
            }
        } elseif ($quitarPrincipal) {
            if ($oldPrincipal) {
                $this->borrarArchivoFisico($oldPrincipal);
            }
            $extra = ", imagen_principal=NULL";
        }

        $q = "UPDATE productos SET tipo='$tipo', clasificacion_id=$clasificacion_id, subtipo_id=$subSql,
              nombre='$nombre', resumen='$resumen', descripcion='$descripcion',
              destacado=$destacado, activo=$activo, orden=$orden $extra WHERE id=$id";
        $err = $omodelo->_insertar($q);
        if ($err === 'si') {
            echo 'Error: ' . mysqli_error($omodelo->link);
            return;
        }

        // Subir nuevas imágenes para la galería
        if (isset($_FILES['imagenes_galeria'])) {
            $this->guardarImagenesGaleria($id, $_FILES['imagenes_galeria']);
        }

        // Eliminar imágenes de galería seleccionadas para borrar
        if (!empty($_POST['eliminar_galeria_ids'])) {
            $ids = is_array($_POST['eliminar_galeria_ids']) ? $_POST['eliminar_galeria_ids'] : explode(',', $_POST['eliminar_galeria_ids']);
            foreach ($ids as $gid) {
                $gid = (int)$gid;
                if ($gid > 0) {
                    $gRow = $omodelo->_consultar("SELECT archivo FROM producto_imagenes WHERE id=$gid AND producto_id=$id LIMIT 1");
                    if (is_array($gRow) && !empty($gRow[0]['archivo'])) {
                        $this->borrarArchivoFisico($gRow[0]['archivo']);
                    }
                    $omodelo->_insertar("DELETE FROM producto_imagenes WHERE id=$gid AND producto_id=$id");
                }
            }
        }

        echo 'Correcto';
    }

    public function _eliminar()
    {
        $omodelo = new m_modelo();

        // 1. Caso: Eliminar una sola imagen secundaria de la galería
        $imgId = (int)($_POST['imagen_id'] ?? 0);
        if ($imgId > 0) {
            $gRow = $omodelo->_consultar("SELECT archivo FROM producto_imagenes WHERE id=$imgId LIMIT 1");
            if (is_array($gRow) && !empty($gRow[0]['archivo'])) {
                $this->borrarArchivoFisico($gRow[0]['archivo']);
            }
            $omodelo->_insertar("DELETE FROM producto_imagenes WHERE id=$imgId");
            echo 'Correcto';
            return;
        }

        // 2. Caso: Eliminar un producto completo y todas sus imágenes asociadas
        $id = (int)($_POST['id'] ?? 0);
        if ($id < 1) {
            echo 'ID inválido';
            return;
        }

        // Borrar archivo físico de la imagen principal
        $p = $omodelo->_consultar("SELECT imagen_principal FROM productos WHERE id=$id LIMIT 1");
        if (is_array($p) && !empty($p[0]['imagen_principal'])) {
            $this->borrarArchivoFisico($p[0]['imagen_principal']);
        }

        // Borrar todos los archivos físicos de la galería
        $imgs = $omodelo->_consultar("SELECT archivo FROM producto_imagenes WHERE producto_id=$id");
        if (is_array($imgs)) {
            foreach ($imgs as $im) {
                $this->borrarArchivoFisico($im['archivo']);
            }
        }

        // Eliminar registros de base de datos
        $omodelo->_insertar("DELETE FROM producto_imagenes WHERE producto_id=$id");
        $err = $omodelo->_insertar("DELETE FROM productos WHERE id=$id");
        if ($err === 'si') {
            echo 'Error: ' . mysqli_error($omodelo->link);
            return;
        }
        echo 'Correcto';
    }
}
