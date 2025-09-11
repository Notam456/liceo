<?php

class MunicipioModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearMunicipio($municipio, $id_estado = null) {
        $municipio = mysqli_real_escape_string($this->conn, $municipio);
        if ($id_estado !== null && $id_estado !== '') {
            $id_estado = mysqli_real_escape_string($this->conn, $id_estado);
            $query = "INSERT INTO municipio(municipio, id_estado) VALUES ('$municipio', '$id_estado')";
        } else {
            $query = "INSERT INTO municipio(municipio) VALUES ('$municipio')";
        }
        return mysqli_query($this->conn, $query);
    }

    public function obtenerMunicipioPorId($id) {
        $id = (int)$id;
        // Si existe relacion con estado, mostrarla; si no, devolver solo municipio
        $query = "SELECT m.*" .
                 " FROM municipio as m" .
                 " WHERE m.id_municipio = '$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerTodosLosMunicipios() {
        $query = "SELECT m.* FROM municipio as m";
        return mysqli_query($this->conn, $query);
    }

    public function actualizarMunicipio($id, $municipio, $id_estado = null) {
        $id = (int)$id;
        $municipio = mysqli_real_escape_string($this->conn, $municipio);
        if ($id_estado !== null && $id_estado !== '') {
            $id_estado = mysqli_real_escape_string($this->conn, $id_estado);
            $query = "UPDATE municipio SET municipio = '$municipio', id_estado = '$id_estado' WHERE id_municipio = $id";
        } else {
            $query = "UPDATE municipio SET municipio = '$municipio' WHERE id_municipio = $id";
        }
        return mysqli_query($this->conn, $query);
    }

    public function eliminarMunicipio($id) {
        $id = (int)$id;
        $query = "DELETE FROM municipio WHERE id_municipio ='$id'";
        return mysqli_query($this->conn, $query);
    }
}
?>
