<?php

class CoordinadorModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearCoordinador($nombre, $apellido, $cedula, $contacto, $area) {
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $apellido = mysqli_real_escape_string($this->conn, $apellido);
        $cedula = mysqli_real_escape_string($this->conn, $cedula);
        $contacto = mysqli_real_escape_string($this->conn, $contacto);
        $area = mysqli_real_escape_string($this->conn, $area);

        $query = "INSERT INTO coordinadores(nombre_coordinador, apellido_coordinador, cedula_coordinador, contacto_coordinador, area_coordinacion)
                  VALUES ('$nombre', '$apellido', '$cedula', '$contacto', '$area')";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerCoordinadorPorId($id) {
        $id = (int)$id;
        $query = "SELECT * FROM coordinadores WHERE id_coordinador = '$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerTodosLosCoordinadores() {
        $query = "SELECT * FROM coordinadores";
        return mysqli_query($this->conn, $query);
    }

    public function actualizarCoordinador($id, $nombre, $apellido, $cedula, $contacto, $area) {
        $id = (int)$id;
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $apellido = mysqli_real_escape_string($this->conn, $apellido);
        $cedula = mysqli_real_escape_string($this->conn, $cedula);
        $contacto = mysqli_real_escape_string($this->conn, $contacto);
        $area = mysqli_real_escape_string($this->conn, $area);

        $query = "UPDATE coordinadores SET
                    nombre_coordinador = '$nombre',
                    apellido_coordinador = '$apellido',
                    cedula_coordinador = '$cedula',
                    contacto_coordinador = '$contacto',
                    area_coordinacion = '$area'
                  WHERE id_coordinador = $id";
        return mysqli_query($this->conn, $query);
    }

    public function eliminarCoordinador($id) {
        $id = (int)$id;
        $query = "DELETE FROM coordinadores WHERE id_coordinador ='$id'";
        return mysqli_query($this->conn, $query);
    }
}
?>
