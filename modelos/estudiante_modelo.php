<?php

class EstudianteModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearEstudiante($nombre, $apellido, $cedula, $contacto, $municipio, $parroquia, $anio, $seccion) {
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $apellido = mysqli_real_escape_string($this->conn, $apellido);
        $cedula = mysqli_real_escape_string($this->conn, $cedula);
        $contacto = mysqli_real_escape_string($this->conn, $contacto);
        $municipio = mysqli_real_escape_string($this->conn, $municipio);
        $parroquia = mysqli_real_escape_string($this->conn, $parroquia);
        $anio = mysqli_real_escape_string($this->conn, $anio);
        $seccion = mysqli_real_escape_string($this->conn, $seccion);

        $query = "INSERT INTO estudiante(nombre_estudiante, apellido_estudiante, cedula_estudiante, contacto_estudiante, Municipio, Parroquia, año_academico, seccion_estudiante)
                  VALUES ('$nombre', '$apellido', '$cedula', '$contacto', '$municipio', '$parroquia', '$anio', '$seccion')";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerEstudiantePorId($id) {
        $id = (int)$id;
        $query = "SELECT * FROM estudiante WHERE id_estudiante = '$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerEstudiantesPorSeccion($id_seccion) {
        $id_seccion = (int)$id_seccion;
        // This assumes the section name is stored in the student table.
        // A better approach would be to join with the seccion table, but for now, I will keep the logic as is.
        $query = "SELECT * FROM estudiante WHERE seccion_estudiante IN (SELECT nombre FROM seccion WHERE id_seccion = '$id_seccion')";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerTodosLosEstudiantes() {
        $query = "SELECT * FROM estudiante";
        return mysqli_query($this->conn, $query);
    }

    public function actualizarEstudiante($id, $nombre, $apellido, $cedula, $contacto, $municipio, $parroquia, $anio, $seccion) {
        $id = (int)$id;
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $apellido = mysqli_real_escape_string($this->conn, $apellido);
        $cedula = mysqli_real_escape_string($this->conn, $cedula);
        $contacto = mysqli_real_escape_string($this->conn, $contacto);
        $municipio = mysqli_real_escape_string($this->conn, $municipio);
        $parroquia = mysqli_real_escape_string($this->conn, $parroquia);
        $anio = mysqli_real_escape_string($this->conn, $anio);
        $seccion = mysqli_real_escape_string($this->conn, $seccion);

        $query = "UPDATE estudiante SET
                    nombre_estudiante = '$nombre',
                    apellido_estudiante = '$apellido',
                    cedula_estudiante = '$cedula',
                    contacto_estudiante = '$contacto',
                    Municipio= '$municipio',
                    Parroquia = '$parroquia',
                    año_academico = '$anio',
                    seccion_estudiante = '$seccion'
                  WHERE id_estudiante = '$id'";
        return mysqli_query($this->conn, $query);
    }

    public function eliminarEstudiante($id) {
        $id = (int)$id;
        $query = "DELETE FROM estudiante WHERE id_estudiante ='$id'";
        return mysqli_query($this->conn, $query);
    }
}
?>
