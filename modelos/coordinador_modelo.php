<?php

class CoordinadorModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearCoordinador($nombre, $apellido, $cedula, $contacto, $area, $seccion) {
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $apellido = mysqli_real_escape_string($this->conn, $apellido);
        $cedula = mysqli_real_escape_string($this->conn, $cedula);
        $contacto = mysqli_real_escape_string($this->conn, $contacto);
        $area = mysqli_real_escape_string($this->conn, $area);
        $seccion = mysqli_real_escape_string($this->conn, $seccion);

        $query = "INSERT INTO coordinadores(nombre_coordinadores, apellido_coordinadores, cedula_coordinadores, contacto_coordinadores, area_coordinacion, seccion_coordinadores)
                  VALUES ('$nombre', '$apellido', '$cedula', '$contacto', '$area', '$seccion')";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerCoordinadorPorId($id) {
        $id = (int)$id;
        $query = "SELECT * FROM coordinadores WHERE id_coordinadores = '$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerTodosLosCoordinadores() {
        $query = "SELECT * FROM coordinadores";
        return mysqli_query($this->conn, $query);
    }

    public function actualizarCoordinador($id, $nombre, $apellido, $cedula, $contacto, $area, $seccion) {
        $id = (int)$id;
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $apellido = mysqli_real_escape_string($this->conn, $apellido);
        $cedula = mysqli_real_escape_string($this->conn, $cedula);
        $contacto = mysqli_real_escape_string($this->conn, $contacto);
        $area = mysqli_real_escape_string($this->conn, $area);
        $seccion = mysqli_real_escape_string($this->conn, $seccion);

        $query = "UPDATE coordinadores SET
                    nombre_coordinadores = '$nombre',
                    apellido_coordinadores = '$apellido',
                    cedula_coordinadores = '$cedula',
                    contacto_coordinadores = '$contacto',
                    area_coordinacion = '$area',
                    seccion_coordinadores = '$seccion'
                  WHERE id_coordinadores = $id";
        return mysqli_query($this->conn, $query);
    }

    public function eliminarCoordinador($id) {
        $id = (int)$id;
        $query = "DELETE FROM coordinadores WHERE id_coordinadores ='$id'";
        return mysqli_query($this->conn, $query);
    }
}
?>
