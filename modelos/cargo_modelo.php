<?php

class CargoModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearCargo($nombre) {
        $nombre = mysqli_real_escape_string($this->conn, $nombre);

        $query = "INSERT INTO cargo(nombre) VALUES ('$nombre')";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerCargoPorId($id) {
        $id = (int)$id;
        $query = "SELECT * FROM cargo WHERE id_cargo = '$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerTodosLosCargos() {
        $query = "SELECT * FROM cargo";
        return mysqli_query($this->conn, $query);
    }

    public function actualizarCargo($id, $nombre) {
        $id = (int)$id;
        $nombre = mysqli_real_escape_string($this->conn, $nombre);

        $query = "UPDATE cargo SET nombre = '$nombre' WHERE id_cargo = $id";
        return mysqli_query($this->conn, $query);
    }

    public function eliminarCargo($id) {
        $id = (int)$id;
        $query = "DELETE FROM cargo WHERE id_cargo ='$id'";
        return mysqli_query($this->conn, $query);
    }
}
?>
