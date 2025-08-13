<?php

class ProfesorModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearProfesor($nombre, $apellido, $cedula, $contacto, $materia, $seccion) {
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $apellido = mysqli_real_escape_string($this->conn, $apellido);
        $cedula = mysqli_real_escape_string($this->conn, $cedula);
        $contacto = mysqli_real_escape_string($this->conn, $contacto);
        $materia = mysqli_real_escape_string($this->conn, $materia);
        $seccion = mysqli_real_escape_string($this->conn, $seccion);

        $query = "INSERT INTO profesores(nombre_profesores, apellido_profesores, cedula_profesores, contacto_profesores, materia_impartida, seccion_profesores)
                  VALUES ('$nombre', '$apellido', '$cedula', '$contacto', '$materia', '$seccion')";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerProfesorPorId($id) {
        $id = (int)$id;
        $query = "SELECT * FROM profesores WHERE id_profesores = '$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerTodosLosProfesores() {
        $query = "SELECT * FROM profesores";
        return mysqli_query($this->conn, $query);
    }

    public function actualizarProfesor($id, $nombre, $apellido, $cedula, $contacto, $materia, $seccion) {
        $id = (int)$id;
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $apellido = mysqli_real_escape_string($this->conn, $apellido);
        $cedula = mysqli_real_escape_string($this->conn, $cedula);
        $contacto = mysqli_real_escape_string($this->conn, $contacto);
        $materia = mysqli_real_escape_string($this->conn, $materia);
        $seccion = mysqli_real_escape_string($this->conn, $seccion);

        $query = "UPDATE profesores SET
                    nombre_profesores = '$nombre',
                    apellido_profesores = '$apellido',
                    cedula_profesores = '$cedula',
                    contacto_profesores = '$contacto',
                    materia_impartida = '$materia',
                    seccion_profesores = '$seccion'
                  WHERE id_profesores = $id";
        return mysqli_query($this->conn, $query);
    }

    public function eliminarProfesor($id) {
        $id = (int)$id;
        $query = "DELETE FROM profesores WHERE id_profesores ='$id'";
        return mysqli_query($this->conn, $query);
    }
}
?>
