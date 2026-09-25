<?php
date_default_timezone_set('America/Mexico_City');
require_once __DIR__ . '/../modelo/m_modelo.php';
require_once __DIR__ . '/c_login.php';
require_once __DIR__ . '/c_productos.php';
require_once __DIR__ . '/c_cotizaciones.php';
require_once __DIR__ . '/c_dashboard.php';

class controller
{
	function _layouts()
	{
		$pagina = file_get_contents(__DIR__ . '/../vistas/v_html.php');
		$foto = '/admin/vistas/assets/images/default.jpg';
		$nombre = htmlspecialchars($_SESSION['user_pcv']['nombre'] ?? 'Admin', ENT_QUOTES, 'UTF-8');
		$pagina = str_replace('#fotoCuenta#', $foto, $pagina);
		$pagina = str_replace('#nombreUsuario#', 'Hola, <span id="nombreUserP">' . $nombre . '</span>', $pagina);
		return $pagina;
	}

	function _contenido($vista)
	{
		$vista = preg_replace('/[^a-z0-9_]/i', '', (string)$vista);
		$path = __DIR__ . '/../vistas/' . $vista . '.php';
		if (!is_file($path)) {
			return '<div class="alert alert-warning">Vista no encontrada</div>';
		}
		$pagina = file_get_contents($path);
		return $this->remplazar($pagina, $vista);
	}

	function _consultar($metodo)
	{
		$objeto = new $metodo();
		$objeto->_consultar();
	}

	function _insertar($metodo)
	{
		$objeto = new $metodo();
		$objeto->_insertar();
	}

	function _modificar($metodo)
	{
		$objeto = new $metodo();
		$objeto->_modificar();
	}

	function _eliminar($metodo)
	{
		$objeto = new $metodo();
		$objeto->_eliminar();
	}

	function _detalles($metodo)
	{
		$objeto = new $metodo();
		$objeto->_detalles();
	}

	function remplazar($pagina, $nombre)
	{
		$omodelo = new m_modelo();

		if ($nombre == 'v_productos') {
			$opts = '<option value="">— Clasificación —</option>';
			$row = $omodelo->_consultar('SELECT id, nombre FROM clasificaciones WHERE activo=1 ORDER BY orden');
			if (is_array($row)) {
				foreach ($row as $r) {
					$opts .= '<option value="' . (int)$r['id'] . '">' . htmlspecialchars($r['nombre'], ENT_QUOTES, 'UTF-8') . '</option>';
				}
			}
			$pagina = str_replace('#opcionesClasificacion#', $opts, $pagina);

			$subs = '<option value="">— Subtipo —</option>';
			$row2 = $omodelo->_consultar('SELECT id, clasificacion_id, nombre FROM subtipos WHERE activo=1 ORDER BY orden');
			if (is_array($row2)) {
				foreach ($row2 as $r) {
					$subs .= '<option value="' . (int)$r['id'] . '" data-clas="' . (int)$r['clasificacion_id'] . '">' . htmlspecialchars($r['nombre'], ENT_QUOTES, 'UTF-8') . '</option>';
				}
			}
			$pagina = str_replace('#opcionesSubtipo#', $subs, $pagina);

			$pagina = str_replace('#bAgregar#', '<button type="button" class="btn btn-sm btn-primary" id="bAgregarProducto">Agregar <i class="fas fa-plus"></i></button>', $pagina);
		}

		return $pagina;
	}
}
