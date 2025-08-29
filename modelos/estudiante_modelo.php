<?php

class EstudianteModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearEstudiante($nombre, $apellido, $cedula, $contacto, $parroquia, $anio) {
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $apellido = mysqli_real_escape_string($this->conn, $apellido);
        $cedula = mysqli_real_escape_string($this->conn, $cedula);
        $contacto = mysqli_real_escape_string($this->conn, $contacto);
        $parroquia = mysqli_real_escape_string($this->conn, $parroquia);
        $anio = mysqli_real_escape_string($this->conn, $anio);

        $query = "INSERT INTO estudiante(nombre, apellido, cedula, contacto, id_parroquia, id_grado)
                  VALUES ('$nombre', '$apellido', '$cedula', '$contacto', '$parroquia', '$anio')";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerEstudiantePorId($id) {
        $id = (int)$id;
        $query = "SELECT * FROM estudiante WHERE id_estudiante = '$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerEstudiantesPorSeccion($id_seccion) {
        $id_seccion = (int)$id_seccion;
        $query = "SELECT * FROM estudiante WHERE seccion_estudiante IN (SELECT nombre FROM seccion WHERE id_seccion = '$id_seccion')";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerTodosLosEstudiantes() {
        $query = "SELECT * FROM estudiante";
        return mysqli_query($this->conn, $query);
    }

    public function actualizarEstudiante($id, $nombre, $apellido, $cedula, $contacto, $parroquia, $anio) {
        $id = (int)$id;
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $apellido = mysqli_real_escape_string($this->conn, $apellido);
        $cedula = mysqli_real_escape_string($this->conn, $cedula);
        $contacto = mysqli_real_escape_string($this->conn, $contacto);
        $parroquia = mysqli_real_escape_string($this->conn, $parroquia);
        $anio = mysqli_real_escape_string($this->conn, $anio);

        $query = "UPDATE estudiante SET
                    nombre = '$nombre',
                    apellido = '$apellido',
                    cedula = '$cedula',
                    contacto = '$contacto',
                    id_parroquia = '$parroquia',
                    id_grado = '$anio'
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
