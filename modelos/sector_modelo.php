<?php

class SectorModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getConnection() {
        return $this->conn;
    }

    public function crearSector($sector, $id_parroquia = null) {
        $sector = mysqli_real_escape_string($this->conn, $sector);
        if ($id_parroquia !== null && $id_parroquia !== '') {
            $id_parroquia = mysqli_real_escape_string($this->conn, $id_parroquia);
            $query = "INSERT INTO sector(sector, id_parroquia) VALUES ('$sector', '$id_parroquia')";
        } else {
            $query = "INSERT INTO sector(sector) VALUES ('$sector')";
        }
        
        // Activar el reporte de errores de MySQL
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        
        try {
            return mysqli_query($this->conn, $query);
        } catch (mysqli_sql_exception $e) {
            // Relanzar la excepción para manejarla en el controlador
            throw $e;
        }
    }

    public function obtenerSectorPorId($id) {
        $id = (int)$id;
        // Si existe relacion con estado, mostrarla; si no, devolver solo municipio
        $query = "SELECT s.*, p.parroquia
                  FROM sector AS s
                  LEFT JOIN parroquia AS p
                  ON s.id_parroquia = p.id_parroquia
                  WHERE id_sector = $id";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerTodosLosSectores() {
        $query = "SELECT s.*, p.parroquia as nombre_parroquia 
                 FROM sector s 
                 LEFT JOIN parroquia p ON s.id_parroquia = p.id_parroquia 
                 WHERE s.visibilidad = TRUE";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerSectoresPorParroquia($id_parroquia) {
        $id_parroquia = (int)$id_parroquia;
        $query = "SELECT * FROM sector WHERE id_parroquia = $id_parroquia AND visibilidad = TRUE";
        return mysqli_query($this->conn, $query);
    }

    public function actualizarSector($id, $sector, $id_parroquia = null) {
        $id = (int)$id;
        $sector = mysqli_real_escape_string($this->conn, $sector);
        if ($id_parroquia !== null && $id_parroquia !== '') {
            $id_parroquia = mysqli_real_escape_string($this->conn, $id_parroquia);
            $query = "UPDATE sector SET sector = '$sector', id_parroquia = '$id_parroquia' WHERE id_sector = $id";
        } else {
            $query = "UPDATE sector SET sector = '$sector' WHERE id_sector = $id";
        }
        
        // Activar el reporte de errores de MySQL
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        
        try {
            return mysqli_query($this->conn, $query);
        } catch (mysqli_sql_exception $e) {
            // Relanzar la excepción para manejarla en el controlador
            throw $e;
        }
    }

    public function eliminarSector($id) {
        $id = (int)$id;
        $query = "UPDATE sector SET visibilidad = FALSE WHERE id_sector ='$id'";
        return mysqli_query($this->conn, $query);
    }
}
?>
