<?php
class login
{
	public function _consultar()
	{
		$omodelo = new m_modelo();
		$usuario = $omodelo->esc($_POST['usuario'] ?? $_POST['correo'] ?? '');
		$contrasena = trim($_POST['contrasena'] ?? $_POST['password'] ?? '');

		$row = $omodelo->_consultar("SELECT * FROM usuarios WHERE usuario = '$usuario' AND estatus = 'activo' LIMIT 1");
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
