<?php
require_once __DIR__ . '/config.php';

function pcv_db(): mysqli {
    static $link = null;
    if ($link instanceof mysqli) {
        return $link;
    }
    $link = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    if ($link->connect_errno) {
        http_response_code(500);
        die('Error de conexión a la base de datos. Revisa includes/config.php y que MariaDB esté activo.');
    }
    $link->set_charset('utf8mb4');
    return $link;
}
