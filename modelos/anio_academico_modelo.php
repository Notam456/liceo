<?php

class AnioAcademicoModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearAnioAcademico($anio, $anio_academico) {
        $anio = mysqli_real_escape_string($this->conn, $anio);
        $anio_academico = mysqli_real_escape_string($this->conn, $anio_academico);

        $insert_query = "INSERT INTO anio_academico(anio, anio_academico) VALUES ('$anio', '$anio_academico')";
        return mysqli_query($this->conn, $insert_query);
    }

    public function obtenerAnioAcademicoPorId($id) {
        $id = (int)$id;
        $fetch_query = "SELECT * FROM anio_academico WHERE id_anio = '$id'";
        return mysqli_query($this->conn, $fetch_query);
    }

    public function obtenerTodosLosAniosAcademicos() {
        $query = "SELECT * FROM anio_academico";
        return mysqli_query($this->conn, $query);
    }

    public function actualizarAnioAcademico($id, $anio, $anio_academico) {
        $id = (int)$id;
        $anio = mysqli_real_escape_string($this->conn, $anio);
        $anio_academico = mysqli_real_escape_string($this->conn, $anio_academico);

        $update_query = "UPDATE anio_academico SET anio = '$anio', anio_academico = '$anio_academico' WHERE id_anio = $id";
        return mysqli_query($this->conn, $update_query);
    }

    public function eliminarAnioAcademico($id) {
        $id = (int)$id;
        $delete_query = "DELETE FROM anio_academico WHERE id_anio ='$id'";
        return mysqli_query($this->conn, $delete_query);
    }
}
?>
