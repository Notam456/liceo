<?php

class CargoModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearCargo($nombre, $tipo) {
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $tipo = mysqli_real_escape_string($this->conn, $tipo);

        $query = "INSERT INTO cargo(nombre, tipo) VALUES ('$nombre', '$tipo')";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerCargoPorId($id) {
        $id = (int)$id;
        $query = "SELECT * FROM cargo WHERE id_cargo = '$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerTodosLosCargos() {
        $query = "SELECT * FROM cargo WHERE visibilidad = TRUE";
        return mysqli_query($this->conn, $query);
    }

    public function actualizarCargo($id, $nombre, $tipo) {
        $id = (int)$id;
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
          $tipo = mysqli_real_escape_string($this->conn, $tipo);

        $query = "UPDATE cargo SET nombre = '$nombre', tipo = '$tipo' WHERE id_cargo = $id";
        return mysqli_query($this->conn, $query);
    }

    public function eliminarCargo($id) {
        $id = (int)$id;
        $query = "UPDATE cargo SET visibilidad = FALSE WHERE id_cargo ='$id'";
        return mysqli_query($this->conn, $query);
    }
}
?>
