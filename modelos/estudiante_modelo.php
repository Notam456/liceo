<?php

class EstudianteModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearEstudiante($nombre, $apellido, $cedula, $contacto, $parroquia, $anio, $fecha) {
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $apellido = mysqli_real_escape_string($this->conn, $apellido);
        $cedula = mysqli_real_escape_string($this->conn, $cedula);
        $contacto = mysqli_real_escape_string($this->conn, $contacto);
        $parroquia = mysqli_real_escape_string($this->conn, $parroquia);
        $anio = mysqli_real_escape_string($this->conn, $anio);
        $fecha = mysqli_real_escape_string($this->conn, $fecha);

        $query = "INSERT INTO estudiante(nombre, apellido, cedula, contacto, id_parroquia, id_grado, fecha_nacimiento)
                  VALUES ('$nombre', '$apellido', '$cedula', '$contacto', '$parroquia', '$anio', '$fecha')";

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

    public function obtenerEstudiantePorId($id) {
        $id = (int)$id;
        $query = "SELECT e.*, p.parroquia, s.letra, g.numero_anio FROM estudiante e
        JOIN parroquia p ON e.id_parroquia = p.id_parroquia
        LEFT JOIN seccion s ON e.id_seccion = s.id_seccion
        LEFT JOIN grado g ON s.id_grado = g.id_grado 
        WHERE id_estudiante = '$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerEstudiantesPorSeccion($id_seccion) {
        $id_seccion = (int)$id_seccion;
        $query = "SELECT * FROM estudiante WHERE id_seccion = '$id_seccion' ORDER BY apellido, nombre";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerTodosLosEstudiantes() {
        $query = "SELECT * FROM estudiante";
        return mysqli_query($this->conn, $query);
    }

    public function actualizarEstudiante($id, $nombre, $apellido, $cedula, $contacto, $parroquia, $anio, $fecha) {
        $id = (int)$id;
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $apellido = mysqli_real_escape_string($this->conn, $apellido);
        $cedula = mysqli_real_escape_string($this->conn, $cedula);
        $contacto = mysqli_real_escape_string($this->conn, $contacto);
        $parroquia = mysqli_real_escape_string($this->conn, $parroquia);
        $anio = mysqli_real_escape_string($this->conn, $anio);
        $fecha = mysqli_real_escape_string($this->conn, $fecha);

        $query = "UPDATE estudiante SET
                    nombre = '$nombre',
                    apellido = '$apellido',
                    cedula = '$cedula',
                    contacto = '$contacto',
                    id_parroquia = '$parroquia',
                    id_grado = '$anio',
                    fecha_nacimiento = '$fecha'
                  WHERE id_estudiante = '$id'";

        try {
            $update_query_run = mysqli_query($this->conn, $query);
            return $update_query_run;
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                return 1062; // clave duplicada
            }
            return false; // otro error
        }
    }

    public function eliminarEstudiante($id) {
        $id = (int)$id;
        $query = "DELETE FROM estudiante WHERE id_estudiante ='$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerEstudiantesSinSeccion() {
        $query = "SELECT * FROM estudiante WHERE id_seccion IS NULL OR id_seccion = 0";
        return mysqli_query($this->conn, $query);
    }

    public function asignarSeccion($id_estudiante, $id_seccion) {
        $id_estudiante = (int)$id_estudiante;
        $id_seccion = (int)$id_seccion;
        
        $query = "UPDATE estudiante SET id_seccion = $id_seccion WHERE id_estudiante = $id_estudiante";
        return mysqli_query($this->conn, $query);
    }

    public function asignarSeccionMasiva($estudiantes_ids, $id_seccion) {
        $id_seccion = (int)$id_seccion;
        $success = true;
        
        foreach ($estudiantes_ids as $id_estudiante) {
            $id_estudiante = (int)$id_estudiante;
            $query = "UPDATE estudiante SET id_seccion = $id_seccion WHERE id_estudiante = $id_estudiante";
            if (!mysqli_query($this->conn, $query)) {
                $success = false;
            }
        }
        
        return $success;
    }
}
?>
