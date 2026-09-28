<?php
class dashboard
{
    public function _consultar()
    {
        $omodelo = new m_modelo();
        $p = $omodelo->_consultar("SELECT COUNT(*) c FROM productos WHERE activo = 1");
        $c = $omodelo->_consultar("SELECT COUNT(*) c FROM cotizaciones WHERE estatus = 'nueva'");
        $cTotal = $omodelo->_consultar("SELECT COUNT(*) c FROM cotizaciones");
        $s = $omodelo->_consultar("SELECT COUNT(*) c FROM productos WHERE tipo = 'servicio' AND activo = 1");

        // Construir rango continuo de los últimos 14 días
        $diasAtras = 14;
        $fechas = [];
        for ($i = $diasAtras - 1; $i >= 0; $i--) {
            $f = date('Y-m-d', strtotime("-$i days"));
            $fechas[$f] = [
                'fecha' => $f,
                'label' => date('d M', strtotime($f)),
                'total' => 0
            ];
        }

        $minFecha = date('Y-m-d 00:00:00', strtotime("-" . ($diasAtras - 1) . " days"));
        $qDias = $omodelo->_consultar("
            SELECT DATE(creado_en) as f, COUNT(*) as cant 
            FROM cotizaciones 
            WHERE creado_en >= '$minFecha' 
            GROUP BY DATE(creado_en)
        ");
        if (is_array($qDias)) {
            foreach ($qDias as $qd) {
                if (isset($fechas[$qd['f']])) {
                    $fechas[$qd['f']]['total'] = (int)$qd['cant'];
                }
            }
        }

        // Distribución por meses del año actual
        $mesesNombres = [1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'];
        $mesesData = [];
        $currentYear = date('Y');
        for ($m = 1; $m <= 12; $m++) {
            $mesesData[$m] = [
                'mes_num' => $m,
                'label' => $mesesNombres[$m],
                'total' => 0
            ];
        }

        $qMeses = $omodelo->_consultar("
            SELECT MONTH(creado_en) as m, COUNT(*) as cant 
            FROM cotizaciones 
            WHERE YEAR(creado_en) = $currentYear 
            GROUP BY MONTH(creado_en)
        ");
        if (is_array($qMeses)) {
            foreach ($qMeses as $qm) {
                $mIndex = (int)$qm['m'];
                if (isset($mesesData[$mIndex])) {
                    $mesesData[$mIndex]['total'] = (int)$qm['cant'];
                }
            }
        }

        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }

        echo json_encode([
            'productos' => (int)($p[0]['c'] ?? 0),
            'productos_servicios_activos' => (int)($p[0]['c'] ?? 0),
            'servicios' => (int)($s[0]['c'] ?? 0),
            'cotizaciones_nuevas' => (int)($c[0]['c'] ?? 0),
            'cotizaciones_totales' => (int)($cTotal[0]['c'] ?? 0),
            'timeline_dias' => array_values($fechas),
            'timeline_meses' => array_values($mesesData)
        ], JSON_UNESCAPED_UNICODE);
    }
}
