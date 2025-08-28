<?php

class GradoModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function generarGrados($cantidad, $id){
        $cantidad = (int)$cantidad;
        for ($i = 1; $i <= $cantidad; $i++) {
            $this->crearGrado($i, $id);
        }
        return "success";
    }
    public function crearGrado($numero_anio, $id_anio) {
        $numero_anio = mysqli_real_escape_string($this->conn, $numero_anio);
        $id_anio = mysqli_real_escape_string($this->conn, $id_anio);

        $query = "INSERT INTO grado(numero_anio, id_anio) VALUES ('$numero_anio', '$id_anio')";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerGradoPorId($id) {
        $id = (int)$id;
        $query = "SELECT * FROM grado WHERE id_grado = '$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerTodosLosGrados() {
        $query = "SELECT g.* FROM grado g INNER JOIN anio_academico a ON g.id_anio = a.id_anio WHERE a.estado = 1;";
        return mysqli_query($this->conn, $query);
    }

    public function actualizarParroquia($id, $parroquia, $municipio) {
        $id = (int)$id;
        $parroquia = mysqli_real_escape_string($this->conn, $parroquia);
        $municipio = mysqli_real_escape_string($this->conn, $municipio);

        $query = "UPDATE parroquia SET parroquia = '$parroquia', id_municipio = '$municipio' WHERE id_parroquia = $id";
        return mysqli_query($this->conn, $query);
    }

    public function eliminarGrado($id) {
        $id = (int)$id;
        $query = "DELETE FROM grado WHERE id_grado ='$id'";
        return mysqli_query($this->conn, $query);
    }

    
}
?>
