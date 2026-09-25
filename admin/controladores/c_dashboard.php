<?php
class dashboard
{
    public function _consultar()
    {
        $omodelo = new m_modelo();
        $p = $omodelo->_consultar("SELECT COUNT(*) c FROM productos WHERE activo=1");
        $c = $omodelo->_consultar("SELECT COUNT(*) c FROM cotizaciones WHERE estatus='nueva'");
        $s = $omodelo->_consultar("SELECT COUNT(*) c FROM productos WHERE tipo='servicio' AND activo=1");
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'productos' => (int)($p[0]['c'] ?? 0),
            'servicios' => (int)($s[0]['c'] ?? 0),
            'cotizaciones_nuevas' => (int)($c[0]['c'] ?? 0),
        ]);
    }
}
