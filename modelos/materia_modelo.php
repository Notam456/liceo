<?php

class MateriaModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearMateria($nombre, $info) {
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $info = mysqli_real_escape_string($this->conn, $info);

        $query = "INSERT INTO materia(nombre, descripcion) VALUES ('$nombre', '$info')";
        try {
            $insert_query_run = mysqli_query($this->conn, $query);
            return true; // éxito
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                return 1062; // clave duplicada
            }
            return false; // otro error
        }
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

        $query = "UPDATE materia SET nombre = '$nombre', descripcion = '$info' WHERE id_materia = $id";
        return mysqli_query($this->conn, $query);
    }

    public function eliminarMateria($id) {
        $id = (int)$id;
        $query = "DELETE FROM materia WHERE id_materia ='$id'";
        return mysqli_query($this->conn, $query);
    }
}
?>
