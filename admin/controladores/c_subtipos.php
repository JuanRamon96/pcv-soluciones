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
