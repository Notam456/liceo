<?php
class AsistenciaModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    private function executeQuery($query, $params = [], $types = "") {
        $stmt = mysqli_prepare($this->conn, $query);
        if ($params) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        $success = mysqli_stmt_execute($stmt);
        if (!$success) {
            die("Error en la consulta: " . mysqli_stmt_error($stmt));
        }
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    public function registrarAsistencia($id_estudiante, $fecha, $estado, $justificacion) {
        $query = "INSERT INTO asistencia (id_estudiante, fecha, estado, justificacion) VALUES (?, ?, ?, ?)";
        return $this->executeQuery($query, [$id_estudiante, $fecha, $estado, $justificacion], "isss");
    }

    public function obtenerEstudiantesPorSeccion($seccion) {
        $query = "SELECT id_estudiante, nombre_estudiante, apellido_estudiante FROM estudiante WHERE seccion_estudiante = ? ORDER BY apellido_estudiante, nombre_estudiante";
        return $this->executeQuery($query, [$seccion], "s");
    }

    public function filtrarAsistencia($seccion, $fecha) {
        $query = "SELECT a.id_asistencia, a.fecha, a.estado, a.justificacion, e.nombre_estudiante, e.apellido_estudiante, e.seccion_estudiante
                  FROM asistencia a
                  JOIN estudiante e ON a.id_estudiante = e.id_estudiante
                  WHERE 1=1";
        $params = [];
        $types = "";
        if (!empty($seccion)) {
            $query .= " AND e.seccion_estudiante = ?";
            $params[] = $seccion;
            $types .= "s";
        }
        if (!empty($fecha)) {
            $query .= " AND a.fecha = ?";
            $params[] = $fecha;
            $types .= "s";
        }
        $query .= " ORDER BY a.fecha DESC";
        return $this->executeQuery($query, $params, $types);
    }

    public function obtenerTodasLasAsistencias() {
        $query = "SELECT a.id_asistencia, a.fecha, a.estado, a.justificacion, e.nombre_estudiante, e.apellido_estudiante, e.seccion_estudiante
                  FROM asistencia a
                  JOIN estudiante e ON a.id_estudiante = e.id_estudiante
                  ORDER BY a.fecha DESC";
        return $this->executeQuery($query);
    }


    public function obtenerAsistenciaPorId($id_asistencia) {
        $query = "SELECT a.*, e.nombre_estudiante, e.apellido_estudiante
                  FROM asistencia a
                  JOIN estudiante e ON a.id_estudiante = e.id_estudiante
                  WHERE a.id_asistencia = ?";
        return $this->executeQuery($query, [$id_asistencia], "i");
    }

    public function actualizarAsistencia($id_asistencia, $fecha, $estado, $justificacion) {
        $query = "UPDATE asistencia SET fecha = ?, estado = ?, justificacion = ? WHERE id_asistencia = ?";
        return $this->executeQuery($query, [$fecha, $estado, $justificacion, $id_asistencia], "sssi");
    }

    public function eliminarAsistencia($id_asistencia) {
        $query = "DELETE FROM asistencia WHERE id_asistencia = ?";
        return $this->executeQuery($query, [$id_asistencia], "i");
    }

    public function obtenerSecciones() {
        $query = "SELECT DISTINCT seccion_estudiante FROM estudiante";
        return $this->executeQuery($query);
    }
}
?>
