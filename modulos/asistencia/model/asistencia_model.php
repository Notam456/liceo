<?php
class AsistenciaModel {
    private $conn;

    public function __construct() {
        include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
        $this->conn = $conn;
    }

    private function executeQuery($query) {
        $result = mysqli_query($this->conn, $query);
        if ($result === false) {
            die("Error en la consulta: " . mysqli_error($this->conn) . "\nConsulta: " . $query);
        }
        return $result;
    }

    public function getAll() {
        $query = "SELECT a.id_asistencia, a.fecha, a.estado, a.justificacion,
                  e.nombre_estudiante, e.apellido_estudiante, e.seccion_estudiante
                  FROM asistencia a
                  JOIN estudiante e ON a.id_estudiante = e.id_estudiante
                  ORDER BY a.fecha DESC";
        return $this->executeQuery($query);
    }

    public function getSecciones() {
        $query = "SELECT DISTINCT seccion_estudiante FROM estudiante";
        return $this->executeQuery($query);
    }

    public function getEstudiantesPorSeccion($seccion) {
        $seccion = mysqli_real_escape_string($this->conn, $seccion);
        $query = "SELECT id_estudiante, nombre_estudiante, apellido_estudiante
                  FROM estudiante
                  WHERE seccion_estudiante = '$seccion'
                  ORDER BY apellido_estudiante, nombre_estudiante";
        return $this->executeQuery($query);
    }

    public function create($data) {
        $fecha = mysqli_real_escape_string($this->conn, $data['fecha']);

        foreach($data['asistencia'] as $id_estudiante => $datos) {
            $id_estudiante = mysqli_real_escape_string($this->conn, $id_estudiante);
            $estado = mysqli_real_escape_string($this->conn, $datos['estado']);
            $justificacion = isset($datos['justificacion']) ? mysqli_real_escape_string($this->conn, $datos['justificacion']) : '';

            $query = "INSERT INTO asistencia (id_estudiante, fecha, estado, justificacion)
                      VALUES ('$id_estudiante', '$fecha', '$estado', '$justificacion')";
            $this->executeQuery($query);
        }
        return true;
    }

    public function getFilteredAsistencia($seccion, $fecha) {
        $query = "SELECT a.id_asistencia, a.fecha, a.estado, a.justificacion,
                  e.nombre_estudiante, e.apellido_estudiante, e.seccion_estudiante
                  FROM asistencia a
                  JOIN estudiante e ON a.id_estudiante = e.id_estudiante
                  WHERE 1=1";

        if(!empty($seccion)) {
            $seccion = mysqli_real_escape_string($this->conn, $seccion);
            $query .= " AND e.seccion_estudiante = '$seccion'";
        }

        if(!empty($fecha)) {
            $fecha = mysqli_real_escape_string($this->conn, $fecha);
            $query .= " AND a.fecha = '$fecha'";
        }

        $query .= " ORDER BY a.fecha DESC";

        return $this->executeQuery($query);
    }

    public function getAsistenciaById($id_asistencia) {
        $id_asistencia = mysqli_real_escape_string($this->conn, $id_asistencia);
        $query = "SELECT a.*, e.nombre_estudiante, e.apellido_estudiante
                  FROM asistencia a
                  JOIN estudiante e ON a.id_estudiante = e.id_estudiante
                  WHERE a.id_asistencia = '$id_asistencia'";
        return $this->executeQuery($query);
    }

    public function update($data) {
        $id_asistencia = mysqli_real_escape_string($this->conn, $data['id_asistencia']);
        $fecha = mysqli_real_escape_string($this->conn, $data['fecha']);
        $estado = mysqli_real_escape_string($this->conn, $data['estado']);
        $justificacion = ($estado == 'J') ? mysqli_real_escape_string($this->conn, $data['justificacion']) : '';

        $query = "UPDATE asistencia SET
                  fecha = '$fecha',
                  estado = '$estado',
                  justificacion = '$justificacion'
                  WHERE id_asistencia = '$id_asistencia'";
        return $this->executeQuery($query);
    }

    public function delete($id_asistencia) {
        $id_asistencia = mysqli_real_escape_string($this->conn, $id_asistencia);
        $query = "DELETE FROM asistencia WHERE id_asistencia = '$id_asistencia'";
        return $this->executeQuery($query);
    }
}
?>
