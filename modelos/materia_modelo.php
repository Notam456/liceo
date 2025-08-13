<?php

class MateriaModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearMateria($nombre, $info) {
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $info = mysqli_real_escape_string($this->conn, $info);

        $query = "INSERT INTO materia(nombre_materia, info_materia) VALUES ('$nombre', '$info')";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerMateriaPorId($id) {
        $id = (int)$id;
        $query = "SELECT * FROM materia WHERE id_materia = '$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerTodasLasMaterias() {
        $query = "SELECT * FROM materia";
        return mysqli_query($this->conn, $query);
    }

    public function actualizarMateria($id, $nombre, $info) {
        $id = (int)$id;
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $info = mysqli_real_escape_string($this->conn, $info);

        $query = "UPDATE materia SET nombre_materia = '$nombre', info_materia = '$info' WHERE id_materia = $id";
        return mysqli_query($this->conn, $query);
    }

    public function eliminarMateria($id) {
        $id = (int)$id;
        $query = "DELETE FROM materia WHERE id_materia ='$id'";
        return mysqli_query($this->conn, $query);
    }
}
?>
