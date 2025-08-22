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

        $query = "INSERT INTO profesores(nombre_profesor, apellido_profesor, cedula_profesor, contacto_profesor, id_materia, id_seccion)
                  VALUES ('$nombre', '$apellido', '$cedula', '$contacto', '$materia', '$seccion')";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerProfesorPorId($id) {
        $id = (int)$id;
        $query = "SELECT p.*, m.nombre_materia, s.nombre
        FROM profesores p
        JOIN materia m ON p.id_materia = m.id_materia
        JOIN seccion s ON p.id_seccion = s.id_seccion
        WHERE p.id_profesor = '$id'";
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
                    nombre_profesor = '$nombre',
                    apellido_profesor = '$apellido',
                    cedula_profesor = '$cedula',
                    contacto_profesor = '$contacto',
                    id_materia = '$materia',
                    id_seccion = '$seccion'
                  WHERE id_profesor = $id";
        return mysqli_query($this->conn, $query);
    }

    public function eliminarProfesor($id) {
        $id = (int)$id;
        $query = "DELETE FROM profesores WHERE id_profesor ='$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerMaterias() {
        $query = "SELECT id_materia, nombre_materia FROM materia ORDER BY nombre_materia ASC";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerSecciones() {
        $query = "SELECT id_seccion, nombre FROM seccion ORDER BY nombre ASC";
        return mysqli_query($this->conn, $query);
    }
}
?>
