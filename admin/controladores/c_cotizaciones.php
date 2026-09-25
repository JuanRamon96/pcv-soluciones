<?php
class cotizaciones
{
    public function _consultar()
    {
        $omodelo = new m_modelo();
        $estatus = $omodelo->esc($_POST['estatus'] ?? '');
        $where = '1=1';
        if (in_array($estatus, ['nueva', 'en_proceso', 'cerrada'], true)) {
            $where .= " AND c.estatus='$estatus'";
        }
        $sql = "SELECT c.*, p.nombre AS producto_nombre
                FROM cotizaciones c
                LEFT JOIN productos p ON p.id = c.producto_id
                WHERE $where
                ORDER BY c.id DESC
                LIMIT 500";
        $row = $omodelo->_consultar($sql);
        if ($row === 'si') {
            echo 'Error: ' . mysqli_error($omodelo->link);
            return;
        }
        $html = '';
        if ($omodelo->numerofilas > 0) {
            foreach ($row as $r) {
                $badge = ['nueva' => 'warning', 'en_proceso' => 'info', 'cerrada' => 'secondary'][$r['estatus']] ?? 'light';
                $html .= '<tr>
                  <td>' . pcv_esc($r['folio']) . '</td>
                  <td>' . pcv_esc($r['nombre']) . '<br><small class="text-muted">' . pcv_esc($r['empresa'] ?? '') . '</small></td>
                  <td>' . pcv_esc($r['telefono']) . '<br><small>' . pcv_esc($r['correo'] ?? '') . '</small></td>
                  <td>' . pcv_esc($r['producto_nombre'] ?? 'General') . '</td>
                  <td><span class="badge bg-' . $badge . '">' . pcv_esc($r['estatus']) . '</span></td>
                  <td>' . pcv_esc($r['creado_en']) . '</td>
                  <td class="text-nowrap">
                    <button type="button" class="btn btn-sm btn-outline-primary bVerCotizacion" data-id="' . (int)$r['id'] . '"><i class="fas fa-eye"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-success bEstatusCotizacion" data-id="' . (int)$r['id'] . '" data-estatus="en_proceso" title="En proceso"><i class="fas fa-spinner"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary bEstatusCotizacion" data-id="' . (int)$r['id'] . '" data-estatus="cerrada" title="Cerrar"><i class="fas fa-check"></i></button>
                  </td>
                </tr>';
            }
        }
        echo $html !== '' ? $html : '<tr><td colspan="7" class="text-center text-muted">Sin cotizaciones</td></tr>';
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
