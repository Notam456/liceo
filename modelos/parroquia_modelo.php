<?php

class ParroquiaModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getConnection() {
        return $this->conn;
    }

    public function crearParroquia($parroquia, $municipio) {
        $parroquia = mysqli_real_escape_string($this->conn, $parroquia);
        $municipio = mysqli_real_escape_string($this->conn, $municipio);

        $query = "INSERT INTO parroquia(parroquia, id_municipio) VALUES ('$parroquia', '$municipio')";
        
        // Activar el reporte de errores de MySQL
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        
        try {
            return mysqli_query($this->conn, $query);
        } catch (mysqli_sql_exception $e) {
            // Relanzar la excepción para manejarla en el controlador
            throw $e;
        }
    }

    public function obtenerParroquiaPorId($id) {
        $id = (int)$id;
        $query = "SELECT p.*, m.municipio FROM parroquia as p LEFT JOIN municipio as m ON p.id_municipio = m.id_municipio WHERE id_parroquia = '$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerTodasLasParroquias() {
        $query = "SELECT p.*, m.municipio FROM parroquia as p LEFT JOIN municipio as m ON p.id_municipio = m.id_municipio WHERE p.visibilidad = TRUE";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerParroquiasPorMunicipio($id_municipio) {
        $id_municipio = (int)$id_municipio;
        $query = "SELECT * FROM parroquia WHERE id_municipio = $id_municipio AND visibilidad = TRUE";
        return mysqli_query($this->conn, $query);
    }

    public function actualizarParroquia($id, $parroquia, $municipio) {
        $id = (int)$id;
        $parroquia = mysqli_real_escape_string($this->conn, $parroquia);
        $municipio = mysqli_real_escape_string($this->conn, $municipio);

        $query = "UPDATE parroquia SET parroquia = '$parroquia', id_municipio = '$municipio' WHERE id_parroquia = $id";
        
        // Activar el reporte de errores de MySQL
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        
        try {
            return mysqli_query($this->conn, $query);
        } catch (mysqli_sql_exception $e) {
            // Relanzar la excepción para manejarla en el controlador
            throw $e;
        }
    }

    public function eliminarParroquia($id) {
        $id = (int)$id;
        $query = "UPDATE parroquia SET visibilidad = FALSE WHERE id_parroquia ='$id'";
        return mysqli_query($this->conn, $query);
    }

    
}
?>
