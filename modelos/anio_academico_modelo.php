<?php

class AnioAcademicoModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearAnioAcademico($desde, $hasta) {
        $desde = mysqli_real_escape_string($this->conn, $desde);
        $hasta = mysqli_real_escape_string($this->conn, $hasta);

        $insert_query = "INSERT INTO anio_academico(desde, hasta) VALUES ('$desde', '$hasta')";
        return mysqli_query($this->conn, $insert_query);
    }

    public function obtenerAnioAcademicoPorId($id) {
        $id = (int)$id;
        $fetch_query = "SELECT *,  CONCAT(YEAR(desde), '-', YEAR(hasta)) AS periodo FROM anio_academico WHERE id_anio = '$id'";
        return mysqli_query($this->conn, $fetch_query);
    }

    public function obtenerTodosLosAniosAcademicos() {
        $query = "SELECT *,  CONCAT(YEAR(desde), '-', YEAR(hasta)) AS periodo FROM anio_academico";
        return mysqli_query($this->conn, $query);
    }

    public function actualizarAnioAcademico($id, $desde, $hasta) {
        $id = (int)$id;
        $desde = mysqli_real_escape_string($this->conn, $desde);
        $hasta = mysqli_real_escape_string($this->conn, $hasta);

        $update_query = "UPDATE anio_academico SET desde = '$desde', hasta = '$hasta' WHERE id_anio = $id";
        return mysqli_query($this->conn, $update_query);
    }

    public function eliminarAnioAcademico($id) {
        $id = (int)$id;
        $delete_query = "DELETE FROM anio_academico WHERE id_anio ='$id'";
        return mysqli_query($this->conn, $delete_query);
    }

    public function establecerAnioActivo($id) {
        $id = (int)$id;
        $query = "UPDATE anio_academico SET estado = 0 WHERE estado = 1";
        mysqli_query($this->conn, $query);
        $set_query = "UPDATE anio_academico SET estado = 1 WHERE id_anio = $id";
        return mysqli_query($this->conn, $set_query);
    }
}
?>
