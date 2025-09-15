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
        // Si no hay result set (INSERT/UPDATE/DELETE), devolver true para indicar éxito
        if ($result === false) {
            $affected = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $affected >= 0; // true si ejecutó correctamente
        }
        mysqli_stmt_close($stmt);
        return $result;
    }

    public function registrarAsistencia($id_estudiante, $fecha, $estado, $justificacion, $seccion, $profesor)
    {
        // Mapear estado a flags
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

        // Determinar id_coordinador válido (entero) o NULL si no aplica (p.ej., Administrador)
        $idCoordinador = null;
        if (is_numeric($profesor) && (int)$profesor > 0) {
            $idCoordinador = (int)$profesor;
        }

        if ($idCoordinador === null) {
            // Omitir la columna id_coordinador para no violar la FK
            $query = "INSERT INTO asistencia (id_estudiante, fecha, inasistencia, justificado, observacion, id_seccion) VALUES (?, ?, ?, ?, ?, ?)";
            return $this->executeQuery($query, [$id_estudiante, $fecha, $inasistencia, $justificado, $justificacion, $seccion], "isiisi");
        } else {
            $query = "INSERT INTO asistencia (id_estudiante, fecha, inasistencia, justificado, observacion, id_seccion, id_coordinador) VALUES (?, ?, ?, ?, ?, ?, ?)";
            return $this->executeQuery($query, [$id_estudiante, $fecha, $inasistencia, $justificado, $justificacion, $seccion, $idCoordinador], "isiisii");
        }
    }

    public function obtenerEstudiantesPorSeccion($seccion)
    {
        $query = "SELECT id_estudiante, nombre, apellido FROM estudiante WHERE id_seccion = ? ORDER BY apellido, nombre";
        return $this->executeQuery($query, [$seccion], "s");
    }

    public function filtrarAsistencia($seccion, $fecha)
    {
        $query = "SELECT a.id_asistencia, a.fecha, a.inasistencia, a.justificado, a.observacion, e.nombre, e.apellido, s.letra, g.numero_anio, p.nombre, p.apellido
                  FROM asistencia a
                  JOIN estudiante e ON a.id_estudiante = e.id_estudiante
                  JOIN seccion s ON a.id_seccion = s.id_seccion
                  JOIN grado g ON s.id_grado = g.id_grado
                   JOIN profesor p ON a.id_coordinador = p.id_profesor
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
        $query = "SELECT a.id_asistencia, a.fecha, a.inasistencia, a.justificado, a.observacion, e.nombre, e.apellido, s.letra, g.numero_anio, p.nombre, p.apellido
                  FROM asistencia a
                  JOIN estudiante e ON a.id_estudiante = e.id_estudiante
                  JOIN seccion s ON a.id_seccion = s.id_seccion
                  JOIN grado g ON s.id_grado = g.id_grado
                  JOIN profesor p ON a.id_coordinador = p.id_profesor
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

    public function actualizarAsistencia($id_asistencia, $inasistencia, $justificado, $observacion)
    {
        $query = "UPDATE asistencia SET inasistencia = ?, justificado = ?, observacion = ? WHERE id_asistencia = ?";
        return $this->executeQuery($query, [$inasistencia, $justificado, $observacion, $id_asistencia], "iisi");
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

    public function obtenerGrados()
    {
       switch ($_SESSION['tipo_cargo']) {
            case 'Administrador':
                $query = "SELECT g.* FROM grado g JOIN anio_academico a ON g.id_anio = a.id_anio WHERE a.estado = 1 ORDER BY g.numero_anio";
                break;
            case 'inferior':
                $query = "SELECT g.* FROM grado g JOIN anio_academico a ON g.id_anio = a.id_anio WHERE a.estado = 1 AND numero_anio < 4  ORDER BY g.numero_anio";
                break;
            case 'superior':
                $query = "SELECT g.* FROM grado g JOIN anio_academico a ON g.id_anio = a.id_anio WHERE a.estado = 1 AND numero_anio > 3  ORDER BY g.numero_anio";
                break;
        }
        return mysqli_query($this->conn, $query);
    }

    public function obtenerSeccionesPorGrado($id_grado)
    {
        $query = "SELECT s.*, g.numero_anio FROM seccion s 
                  JOIN grado g ON s.id_grado = g.id_grado 
                  WHERE s.id_grado = ? ORDER BY s.letra";
        return $this->executeQuery($query, [$id_grado], "i");
    }

    public function obtenerAsistenciasAgrupadasPorFecha()
    {
        $query = "SELECT 
                    a.fecha,
                    s.id_seccion,
                    s.letra,
                    g.numero_anio,
                    p.nombre AS nombre_prof, 
                    p.apellido AS apellido_prof,
                    COUNT(a.id_asistencia) as total_estudiantes,
                    SUM(CASE WHEN a.inasistencia = 1 THEN 1 ELSE 0 END) as ausentes,
                    SUM(CASE WHEN a.justificado = 1 THEN 1 ELSE 0 END) as justificados
                  FROM asistencia a
                  JOIN seccion s ON a.id_seccion = s.id_seccion
                  JOIN grado g ON s.id_grado = g.id_grado
                  LEFT JOIN profesor p ON a.id_coordinador = p.id_profesor
                  GROUP BY a.fecha, s.id_seccion, s.letra, g.numero_anio
                  ORDER BY a.fecha DESC, g.numero_anio, s.letra";
        return $this->executeQuery($query);
    }

    public function obtenerDetalleAsistencia($fecha, $id_seccion)
    {
        $query = "SELECT 
                    a.id_asistencia,
                    a.fecha,
                    a.inasistencia,
                    a.justificado,
                    a.observacion,
                    e.id_estudiante,
                    e.nombre,
                    e.apellido,
                    e.cedula,
                    s.letra,
                    g.numero_anio
                  FROM asistencia a
                  JOIN estudiante e ON a.id_estudiante = e.id_estudiante
                  JOIN seccion s ON a.id_seccion = s.id_seccion
                  JOIN grado g ON s.id_grado = g.id_grado
                  WHERE a.fecha = ? AND a.id_seccion = ?
                  ORDER BY e.apellido, e.nombre";
        return $this->executeQuery($query, [$fecha, $id_seccion], "si");
    }

    public function verificarAsistenciaExistente($fecha, $id_seccion)
    {
        $query = "SELECT COUNT(*) as count FROM asistencia WHERE fecha = ? AND id_seccion = ?";
        $result = $this->executeQuery($query, [$fecha, $id_seccion], "si");
        $row = mysqli_fetch_assoc($result);
        return $row['count'] > 0;
    }

    public function eliminarAsistenciaPorFechaSeccion($fecha, $id_seccion)
    {
        $query = "DELETE FROM asistencia WHERE fecha = ? AND id_seccion = ?";
        return $this->executeQuery($query, [$fecha, $id_seccion], "si");
    }

    public function filtrarAsistenciasAgrupadas($seccion, $fecha, $grado)
    {
        $query = "SELECT 
                    a.fecha,
                    s.id_seccion,
                    s.letra,
                    g.numero_anio,
                    COUNT(a.id_asistencia) as total_estudiantes,
                    SUM(CASE WHEN a.inasistencia = 1 THEN 1 ELSE 0 END) as ausentes,
                    SUM(CASE WHEN a.justificado = 1 THEN 1 ELSE 0 END) as justificados
                  FROM asistencia a
                  JOIN seccion s ON a.id_seccion = s.id_seccion
                  JOIN grado g ON s.id_grado = g.id_grado
                  WHERE 1=1";
        
        $params = [];
        $types = "";
        
        if (!empty($fecha)) {
            $query .= " AND a.fecha = ?";
            $params[] = $fecha;
            $types .= "s";
        }
        
        if (!empty($seccion)) {
            $query .= " AND a.id_seccion = ?";
            $params[] = $seccion;
            $types .= "i";
        }
        
        if (!empty($grado)) {
            $query .= " AND g.id_grado = ?";
            $params[] = $grado;
            $types .= "i";
        }
        
        $query .= " GROUP BY a.fecha, s.id_seccion, s.letra, g.numero_anio
                   ORDER BY a.fecha DESC, g.numero_anio, s.letra";
        
        return $this->executeQuery($query, $params, $types);
    }

}
