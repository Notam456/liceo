<?php
class AsistenciaModelo
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    private function executeQuery($query, $params = [], $types = "")
    {
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

    public function registrarAsistencia($id_estudiante, $fecha, $estado, $justificacion, $seccion)
    {
        $inasistencia = 0;
        $justificado = 0;
        if ($estado == 'A') {
            $inasistencia = 1;
            $justificado = 0;
        } else if ($estado == 'J') {
            $inasistencia = 0;
            $justificado = 1;
        } else {
            $inasistencia = 0;
            $justificado = 0;
        }
        $query = "INSERT INTO asistencia (id_estudiante, fecha, inasistencia, justificado, observacion, id_seccion) VALUES (?, ?, ?, ?, ?, ?)";
        return $this->executeQuery($query, [$id_estudiante, $fecha, $inasistencia, $justificado, $justificacion, $seccion], "isiisi");
    }

    public function obtenerEstudiantesPorSeccion($seccion)
    {
        $query = "SELECT id_estudiante, nombre, apellido FROM estudiante WHERE id_seccion = ? ORDER BY apellido, nombre";
        return $this->executeQuery($query, [$seccion], "s");
    }

    public function filtrarAsistencia($seccion, $fecha)
    {
        $query = "SELECT a.id_asistencia, a.fecha, a.inasistencia, a.justificado, a.observacion, e.nombre, e.apellido, s.letra, g.numero_anio
                  FROM asistencia a
                  JOIN estudiante e ON a.id_estudiante = e.id_estudiante
                  JOIN seccion s ON a.id_seccion = s.id_seccion
                  JOIN grado g ON s.id_grado = g.id_grado
                  WHERE 1=1";
        $params = [];
        $types = "";
        if (!empty($seccion)) {
            $query .= " AND a.id_seccion = ?";
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

    public function obtenerTodasLasAsistencias()
    {
        $query = "SELECT a.id_asistencia, a.fecha, a.inasistencia, a.justificado, a.observacion, e.nombre, e.apellido, s.letra, g.numero_anio
                  FROM asistencia a
                  JOIN estudiante e ON a.id_estudiante = e.id_estudiante
                  JOIN seccion s ON a.id_seccion = s.id_seccion
                  JOIN grado g ON s.id_grado = g.id_grado
                  ORDER BY a.fecha DESC";
        return $this->executeQuery($query);
    }


    public function obtenerAsistenciaPorId($id_asistencia)
    {
        $query = "SELECT a.*, e.nombre, e.apellido
                  FROM asistencia a
                  JOIN estudiante e ON a.id_estudiante = e.id_estudiante
                  WHERE a.id_asistencia = ?";
        return $this->executeQuery($query, [$id_asistencia], "i");
    }

    public function actualizarAsistencia($id_asistencia, $fecha, $estado, $justificacion)
    {
        $inasistencia = 0;
        $justificado = 0;
        if ($estado == 'A') {
            $inasistencia = 1;
            $justificado = 0;
        } else if ($estado == 'J') {
            $inasistencia = 0;
            $justificado = 1;
        } else {
            $inasistencia = 0;
            $justificado = 0;
        }
        $query = "UPDATE asistencia SET fecha = ?, inasistencia = ?, justificado = ?, observacion = ? WHERE id_asistencia = ?";
        return $this->executeQuery($query, [$fecha, $inasistencia, $justificado, $justificacion, $id_asistencia], "siisi");
    }

    public function eliminarAsistencia($id_asistencia)
    {
        $query = "DELETE FROM asistencia WHERE id_asistencia = ?";
        return $this->executeQuery($query, [$id_asistencia], "i");
    }

    public function obtenerSecciones()
    {
        $query = "SELECT s.*, a.numero_anio FROM seccion AS s JOIN grado AS a ON s.id_grado = a.id_grado;";
        return $this->executeQuery($query);
    }
}
