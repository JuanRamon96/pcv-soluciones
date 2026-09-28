<?php
class subtipos
{
    private function slugify($text)
    {
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        $text = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $text));
        return trim($text, '-') ?: 'linea-' . time();
    }

    public function _consultar()
    {
        $omodelo = new m_modelo();

        // Si se llama para combos/dropdowns o sin paginación de myDataTable
        if (isset($_POST['combo']) || isset($_POST['todos']) || (!isset($_POST['limit']) && !isset($_POST['ordenColumna']))) {
            $sql = "SELECT s.*, c.nombre AS clasificacion_nombre,
                    (SELECT COUNT(*) FROM productos p WHERE p.subtipo_id = s.id AND p.activo = 1) AS total_prods
                    FROM subtipos s
                    INNER JOIN clasificaciones c ON c.id = s.clasificacion_id
                    ORDER BY s.clasificacion_id, s.orden, s.nombre";
            $rows = $omodelo->_consultar($sql);
            if (!headers_sent()) {
                header('Content-Type: application/json; charset=utf-8');
            }
            echo json_encode([
                'ok' => true,
                'data' => is_array($rows) ? $rows : []
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        // --- Modo myDataTable con paginación, búsqueda y ordenamiento ---
        $buscar = $omodelo->esc(trim($_POST['buscar'] ?? ''));
        $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 25;
        if ($limit <= 0) $limit = 25;
        $pagina = isset($_POST['pagina']) ? max(1, (int)$_POST['pagina']) : 1;
        $offset = ($pagina - 1) * $limit;

        $ordenCol = $_POST['ordenColumna'] ?? '';
        $ordenDir = (isset($_POST['orden']) && strtolower($_POST['orden']) === 'desc') ? 'DESC' : 'ASC';

        $colMap = [
            'nombre' => 's.nombre',
            'clasificacion' => 'c.nombre',
            'productos' => 'total_prods',
            'estado' => 's.activo'
        ];
        $orderBy = isset($colMap[$ordenCol]) ? "{$colMap[$ordenCol]} $ordenDir" : "s.clasificacion_id ASC, s.orden ASC, s.nombre ASC";

        $where = "1=1";
        if ($buscar !== '') {
            $where .= " AND (s.nombre LIKE '%$buscar%' OR c.nombre LIKE '%$buscar%')";
        }

        // Conteo total para paginación de myDataTable
        $countRes = $omodelo->_consultar("SELECT COUNT(s.id) AS total FROM subtipos s INNER JOIN clasificaciones c ON c.id = s.clasificacion_id WHERE $where");
        $totalRows = ($countRes !== 'si' && isset($countRes[0]['total'])) ? (int)$countRes[0]['total'] : 0;

        $sql = "SELECT s.*, c.nombre AS clasificacion_nombre,
                (SELECT COUNT(*) FROM productos p WHERE p.subtipo_id = s.id AND p.activo = 1) AS total_prods
                FROM subtipos s
                INNER JOIN clasificaciones c ON c.id = s.clasificacion_id
                WHERE $where
                ORDER BY $orderBy
                LIMIT $offset, $limit";
        $rows = $omodelo->_consultar($sql);

        $data = [];
        if ($rows !== 'si' && $omodelo->numerofilas > 0) {
            foreach ($rows as $r) {
                $isActivo = ((int)$r['activo'] === 1);
                $estadoBadge = $isActivo 
                    ? '<span class="badge-table success">Activo</span>' 
                    : '<span class="badge-table secondary" style="background:#F1F5F9; color:#64748B;">Inactivo</span>';

                $nombreCol = '<div class="text-start ps-3 fw-bold text-dark">' . htmlspecialchars($r['nombre'], ENT_QUOTES, 'UTF-8') . '</div>';

                $clasifCol = '<span class="badge bg-light text-dark border px-2 py-1">' . htmlspecialchars($r['clasificacion_nombre'] ?? '', ENT_QUOTES, 'UTF-8') . '</span>';

                $prodsCol = '<span class="badge bg-info-subtle text-info-emphasis px-2 py-1 fw-bold">' . (int)$r['total_prods'] . '</span>';

                $toggleIcon = $isActivo ? 'bi-eye-slash' : 'bi-eye';
                $toggleTitle = $isActivo ? 'Desactivar Filtro' : 'Activar Filtro';
                $toggleStyle = $isActivo ? '' : 'style="color:#16A34A;"';

                $accionesCol = '
                <div class="d-inline-flex align-items-center gap-1">
                    <button type="button" class="table-btn-action bEditarSubtipoVista" data-id="' . (int)$r['id'] . '" data-nombre="' . htmlspecialchars($r['nombre'], ENT_QUOTES, 'UTF-8') . '" data-clas="' . (int)$r['clasificacion_id'] . '" data-orden="' . (int)$r['orden'] . '" data-activo="' . (int)$r['activo'] . '" title="Editar"><i class="bi bi-pencil"></i></button>
                    <button type="button" class="table-btn-action bToggleSubtipoVista" data-id="' . (int)$r['id'] . '" data-nombre="' . htmlspecialchars($r['nombre'], ENT_QUOTES, 'UTF-8') . '" data-clas="' . (int)$r['clasificacion_id'] . '" data-orden="' . (int)$r['orden'] . '" data-activo="' . (int)$r['activo'] . '" ' . $toggleStyle . ' title="' . $toggleTitle . '"><i class="bi ' . $toggleIcon . '"></i></button>
                    <button type="button" class="table-btn-action delete bEliminarSubtipoVista" data-id="' . (int)$r['id'] . '" data-nombre="' . htmlspecialchars($r['nombre'], ENT_QUOTES, 'UTF-8') . '" title="Eliminar"><i class="bi bi-trash3"></i></button>
                </div>';

                $data[] = [
                    'nombre' => $nombreCol,
                    'clasificacion' => $clasifCol,
                    'productos' => $prodsCol,
                    'estado' => $estadoBadge,
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
        $row = $omodelo->_consultar("SELECT * FROM subtipos WHERE id = $id LIMIT 1");
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }
        if (is_array($row) && !empty($row)) {
            echo json_encode(['ok' => true, 'data' => $row[0]]);
        } else {
            echo json_encode(['ok' => false]);
        }
    }

    public function _insertar()
    {
        $omodelo = new m_modelo();
        $nombre = $omodelo->esc(trim($_POST['nombre'] ?? ''));
        $clasificacion_id = (int)($_POST['clasificacion_id'] ?? 0);
        $orden = (int)($_POST['orden'] ?? 0);
        $activo = isset($_POST['activo']) && $_POST['activo'] == '1' ? 1 : (isset($_POST['activo']) ? (int)$_POST['activo'] : 1);

        if ($nombre === '' || $clasificacion_id < 1) {
            echo 'Nombre y División son obligatorios';
            return;
        }

        $slug = $this->slugify($nombre);
        $check = $omodelo->_consultar("SELECT id FROM subtipos WHERE slug = '$slug'");
        if (is_array($check) && $omodelo->numerofilas > 0) {
            $slug .= '-' . time();
        }

        $q = "INSERT INTO subtipos (clasificacion_id, nombre, slug, orden, activo) 
              VALUES ($clasificacion_id, '$nombre', '$slug', $orden, $activo)";
        $err = $omodelo->_insertar($q);
        if ($err === 'si') {
            echo 'Error al guardar filtro';
            return;
        }
        echo 'Correcto';
    }

    public function _modificar()
    {
        $omodelo = new m_modelo();
        $id = (int)($_POST['id'] ?? 0);
        $nombre = $omodelo->esc(trim($_POST['nombre'] ?? ''));
        $clasificacion_id = (int)($_POST['clasificacion_id'] ?? 0);
        $orden = (int)($_POST['orden'] ?? 0);
        $activo = isset($_POST['activo']) && $_POST['activo'] == '1' ? 1 : 0;

        if ($id < 1 || $nombre === '' || $clasificacion_id < 1) {
            echo 'Datos incompletos';
            return;
        }

        $q = "UPDATE subtipos SET clasificacion_id=$clasificacion_id, nombre='$nombre', orden=$orden, activo=$activo WHERE id=$id";
        $err = $omodelo->_insertar($q);
        if ($err === 'si') {
            echo 'Error al actualizar filtro';
            return;
        }
        echo 'Correcto';
    }

    public function _eliminar()
    {
        $omodelo = new m_modelo();
        $id = (int)($_POST['id'] ?? 0);
        if ($id < 1) {
            echo 'ID inválido';
            return;
        }

        $checkProds = $omodelo->_consultar("SELECT COUNT(*) AS total FROM productos WHERE subtipo_id = $id");
        $prodsCount = is_array($checkProds) ? (int)$checkProds[0]['total'] : 0;

        if ($prodsCount > 0) {
            $omodelo->_insertar("UPDATE subtipos SET activo = 0 WHERE id = $id");
            echo 'Desactivado (tiene ' . $prodsCount . ' productos asociados)';
            return;
        }

        $err = $omodelo->_insertar("DELETE FROM subtipos WHERE id = $id");
        if ($err === 'si') {
            echo 'Error al eliminar';
            return;
        }
        echo 'Correcto';
    }
}
