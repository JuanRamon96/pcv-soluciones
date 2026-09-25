<?php
class login
{
	public function _consultar()
	{
		$omodelo = new m_modelo();
		// Preferir correo; fallback usuario por compatibilidad
		$correo = trim($_POST['correo'] ?? '');
		$usuario = trim($_POST['usuario'] ?? '');
		$contrasena = trim($_POST['contrasena'] ?? $_POST['password'] ?? '');

		if ($correo !== '') {
			$loginVal = $omodelo->esc($correo);
			$row = $omodelo->_consultar("SELECT * FROM usuarios WHERE correo = '$loginVal' AND estatus = 'activo' LIMIT 1");
		} else {
			$loginVal = $omodelo->esc($usuario);
			$row = $omodelo->_consultar("SELECT * FROM usuarios WHERE usuario = '$loginVal' AND estatus = 'activo' LIMIT 1");
		}

		if ($row === 'si') {
			echo 'Error DB: ' . mysqli_error($omodelo->link);
			return;
		}
		if ($omodelo->numerofilas > 0 && password_verify($contrasena, $row[0]['password_hash'])) {
			$_SESSION['user_pcv'] = [
				'id' => $row[0]['id'],
				'usuario' => $row[0]['usuario'],
				'nombre' => $row[0]['nombre'],
				'correo' => $row[0]['correo'],
			];
			echo 'Correcto';
			return;
		}
		echo '0';
	}

	public function _eliminar()
	{
		unset($_SESSION['user_pcv']);
		echo 'Correcto';
	}
}
