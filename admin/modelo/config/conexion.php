<?php
require_once dirname(__DIR__, 3) . '/includes/config.php';

class conexion
{
    /** @return mysqli */
    public function __construct()
    {
        $link = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
        if ($link->connect_errno) {
            die('Error de conexión: ' . $link->connect_error);
        }
        $link->set_charset('utf8mb4');
        // En PHP, retornar desde __construct no asigna; soccer lo usa igual.
        // Exponemos vía propiedad estática temporal:
        $GLOBALS['__pcv_mysqli'] = $link;
        return $link;
    }
}
