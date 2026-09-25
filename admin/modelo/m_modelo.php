<?php
date_default_timezone_set('America/Mexico_City');
require_once __DIR__ . '/config/conexion.php';

class m_modelo
{
    /** @var mysqli */
    public $link;
    public $numerofilas = 0;
    public $error = 'no';
    public $insert_id = 0;

    public function __construct()
    {
        $this->link = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
        if ($this->link->connect_errno) {
            die('Error de conexión: ' . $this->link->connect_error);
        }
        $this->link->set_charset('utf8mb4');
    }

    public function _insertar($query)
    {
        $result = $this->link->query($query);
        $this->numerofilas = $this->link->affected_rows;
        $this->insert_id = (int)$this->link->insert_id;
        if (!$result) {
            return 'si';
        }
        return 'no';
    }

    public function _consultar($query)
    {
        $result = $this->link->query($query);
        $resultado = [];
        if (!$result) {
            $this->error = 'si';
            $this->numerofilas = 0;
            return 'si';
        }
        $this->error = 'no';
        $this->numerofilas = $result->num_rows;
        while ($row = $result->fetch_assoc()) {
            $resultado[] = $row;
        }
        // Compat soccer: índice extra null al final a veces; aquí limpio
        return $resultado;
    }

    public function esc($s)
    {
        return $this->link->real_escape_string(trim((string)$s));
    }
}
