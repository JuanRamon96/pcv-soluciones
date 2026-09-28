<?php
class cotizaciones
{
    public function _consultar()
    {
        $omodelo = new m_modelo();
        $buscar = $omodelo->esc($_POST['buscar'] ?? '');
        $estatus = $omodelo->esc($_POST['estatus'] ?? '');

        $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 25;
        if ($limit <= 0) $limit = 25;
        $pagina = isset($_POST['pagina']) ? max(1, (int)$_POST['pagina']) : 1;
        $offset = ($pagina - 1) * $limit;

        $ordenCol = $_POST['ordenColumna'] ?? '';
        $ordenDir = (isset($_POST['orden']) && strtolower($_POST['orden']) === 'desc') ? 'DESC' : 'ASC';

        $colMap = [
            'folio' => 'c.folio',
            'cliente' => 'c.nombre',
            'contacto' => 'c.telefono',
            'producto' => 'p.nombre',
            'estatus' => 'c.estatus',
            'fecha' => 'c.creado_en'
        ];
        $orderBy = isset($colMap[$ordenCol]) ? "{$colMap[$ordenCol]} $ordenDir" : "c.id DESC";

        $where = '1=1';
        if ($buscar !== '') {
            $where .= " AND (c.folio LIKE '%$buscar%' OR c.nombre LIKE '%$buscar%' OR c.empresa LIKE '%$buscar%' OR c.telefono LIKE '%$buscar%' OR c.correo LIKE '%$buscar%' OR p.nombre LIKE '%$buscar%')";
        }
        if (in_array($estatus, ['nueva', 'en_proceso', 'cerrada'], true)) {
            $where .= " AND c.estatus = '$estatus'";
        }

        // Conteo total para paginación de myDataTable
        $countSql = "SELECT COUNT(c.id) AS total
                     FROM cotizaciones c
                     LEFT JOIN productos p ON p.id = c.producto_id
                     WHERE $where";
        $countRes = $omodelo->_consultar($countSql);
        $totalRows = ($countRes !== 'si' && isset($countRes[0]['total'])) ? (int)$countRes[0]['total'] : 0;

        $sql = "SELECT c.*, p.nombre AS producto_nombre
                FROM cotizaciones c
                LEFT JOIN productos p ON p.id = c.producto_id
                WHERE $where
                ORDER BY $orderBy
                LIMIT $offset, $limit";
        $row = $omodelo->_consultar($sql);

        $data = [];
        if ($row !== 'si' && $omodelo->numerofilas > 0) {
            foreach ($row as $r) {
                $statusMap = [
                    'nueva' => '<span class="badge-table pending">Nueva</span>',
                    'en_proceso' => '<span class="badge-table info">En proceso</span>',
                    'cerrada' => '<span class="badge-table success">Cerrada</span>'
                ];
                $statusBadge = $statusMap[$r['estatus']] ?? '<span class="badge-table pending">' . pcv_esc($r['estatus']) . '</span>';
                $empresaStr = !empty($r['empresa']) ? '<small class="text-muted d-block"><i class="bi bi-building me-1"></i>' . pcv_esc($r['empresa']) . '</small>' : '';
                $correoStr = !empty($r['correo']) ? '<small class="text-muted d-block">' . pcv_esc($r['correo']) . '</small>' : '';
                $itemNombre = !empty($r['producto_nombre']) ? pcv_esc($r['producto_nombre']) : '<span class="text-muted fst-italic">General / Varios</span>';

                $data[] = [
                    'ID' => (int)$r['id'],
                    'folio' => '<span class="badge bg-light text-dark border px-2 py-1 font-monospace small">' . pcv_esc($r['folio']) . '</span>',
                    'cliente' => '<strong class="text-dark">' . pcv_esc($r['nombre']) . '</strong>' . $empresaStr,
                    'contacto' => '<a href="tel:' . pcv_esc($r['telefono']) . '" class="text-dark text-decoration-none fw-semibold">' . pcv_esc($r['telefono']) . '</a>' . $correoStr,
                    'producto' => $itemNombre,
                    'estatus' => $statusBadge,
                    'fecha' => '<small class="text-muted">' . pcv_esc(substr($r['creado_en'], 0, 16)) . '</small>',
                    'acciones' => '<div class="d-inline-flex align-items-center gap-1">
                      <button type="button" class="table-btn-action bVerCotizacion" data-id="' . (int)$r['id'] . '" title="Ver requerimiento completo"><i class="bi bi-eye"></i></button>
                      <button type="button" class="table-btn-action bEstatusCotizacion" data-id="' . (int)$r['id'] . '" data-estatus="en_proceso" title="Marcar En Proceso"><i class="bi bi-arrow-repeat"></i></button>
                      <button type="button" class="table-btn-action bEstatusCotizacion" data-id="' . (int)$r['id'] . '" data-estatus="cerrada" title="Marcar Cerrada"><i class="bi bi-check2-circle"></i></button>
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
        $row = $omodelo->_consultar("SELECT c.*, p.nombre AS producto_nombre FROM cotizaciones c LEFT JOIN productos p ON p.id=c.producto_id WHERE c.id=$id");
        header('Content-Type: application/json; charset=utf-8');
        if ($row === 'si' || $omodelo->numerofilas < 1) {
            echo json_encode(['ok' => false]);
            return;
        }
        echo json_encode(['ok' => true, 'data' => $row[0]]);
    }

    public function _modificar()
    {
        $omodelo = new m_modelo();
        $id = (int)($_POST['id'] ?? 0);
        $estatus = $omodelo->esc($_POST['estatus'] ?? '');
        if (!in_array($estatus, ['nueva', 'en_proceso', 'cerrada'], true)) {
            echo 'Estatus inválido';
            return;
        }
        $err = $omodelo->_insertar("UPDATE cotizaciones SET estatus='$estatus' WHERE id=$id");
        echo $err === 'si' ? ('Error: ' . mysqli_error($omodelo->link)) : 'Correcto';
    }

    public function _insertar()
    {
        // Alta pública también puede pasar por aquí; preferir cotizar.php
        echo 'Use cotizar.php';
    }

    public function _eliminar()
    {
        $omodelo = new m_modelo();
        $id = (int)($_POST['id'] ?? 0);
        $err = $omodelo->_insertar("DELETE FROM cotizaciones WHERE id=$id");
        echo $err === 'si' ? ('Error: ' . mysqli_error($omodelo->link)) : 'Correcto';
    }
}
