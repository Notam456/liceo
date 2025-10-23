<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/anio_academico_modelo.php');

class SeccionModelo
{
    private $conn;
    static $letras = [
        'A',
        'B',
        'C',
        'D',
        'E',
        'F',
        'G',
        'H',
        'I',
        'J',
        'K',
        'L',
        'M',
        'N',
        'O',
        'P',
        'Q',
        'R',
        'S',
        'T',
        'U',
        'V',
        'W',
        'X',
        'Y',
        'Z'
    ];
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function crearSeccion($letra, $id_grado)
    {
        $letra = mysqli_real_escape_string($this->conn, $letra);
        $id_grado = mysqli_real_escape_string($this->conn, $id_grado);

        $query = "INSERT INTO seccion(letra, id_grado) VALUES ('$letra', $id_grado)";

        try {
            return mysqli_query($this->conn, $query);
        } catch (Exception $e) {
            return false;
        }
    }

    public function obtenerSeccionPorId($id)
    {
        $id = (int)$id;
        $query = "SELECT s.*, g.numero_anio, p.nombre AS nombre_tutor, p.apellido AS apellido_tutor
                  FROM seccion AS s
                  JOIN grado AS g ON s.id_grado = g.id_grado
                  LEFT JOIN profesor AS p ON s.id_tutor = p.id_profesor
                  WHERE s.id_seccion = '$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerHorarioPorSeccion($id_seccion)
    {
        $id_seccion = (int)$id_seccion;
        $query = "SELECT * FROM horario WHERE id_seccion = " . $id_seccion;
        return mysqli_query($this->conn, $query);
    }

    public function obtenerTodasLasSecciones()
    {
        switch ($_SESSION['tipo_cargo']) {
            case 'Administrador':
                $query = "SELECT s.*, g.numero_anio FROM seccion AS s JOIN grado AS g ON s.id_grado = g.id_grado";
                break;
            case 'inferior':
                $query = "SELECT s.*, g.numero_anio FROM seccion AS s JOIN grado AS g ON s.id_grado = g.id_grado WHERE g.numero_anio < 4";
                break;
            case 'superior':
                $query = "SELECT s.*, g.numero_anio FROM seccion AS s JOIN grado AS g ON s.id_grado = g.id_grado WHERE g.numero_anio < 4";
                break;
        }
        return mysqli_query($this->conn, $query);
    }

    public function actualizarSeccion($id, $letra, $id_grado)
    {
        $id = (int)$id;
        $letra = mysqli_real_escape_string($this->conn, $letra);
        $id_grado = mysqli_real_escape_string($this->conn, $id_grado);

        $query = "UPDATE seccion SET letra = '$letra', id_grado = $id_grado WHERE id_seccion = $id";

        try {
            return mysqli_query($this->conn, $query);
        } catch (Exception $e) {
            return false;
        }
    }

    public function eliminarSeccion($id)
    {
        $id = (int)$id;
        $query = "DELETE FROM seccion WHERE id_seccion ='$id'";
        return mysqli_query($this->conn, $query);
    }

    public function generarSecciones($cantidad, $id_grado)
    {
        for ($i = 0; $i < $cantidad; $i++) {
            $this->crearSeccion(self::$letras[$i], $id_grado);
        }
        return "success";
    }

    public function actualizarTutor($id_seccion, $id_tutor)
    {
        $id_seccion = (int)$id_seccion;
        $id_tutor = (int)$id_tutor;
        $query = "UPDATE seccion SET id_tutor = $id_tutor WHERE id_seccion = $id_seccion";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerSeccionesPorTutor($id_tutor)
    {
        $id_tutor = (int)$id_tutor;
        $query = "SELECT s.*, g.numero_anio FROM seccion AS s JOIN grado AS g ON s.id_grado = g.id_grado WHERE s.id_tutor = $id_tutor";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerReporteInasistenciasPorSeccion($desde = null, $hasta = null)
{
    // Obtener el año académico activo (siempre requerido)
    $anio_modelo = new AnioAcademicoModelo($this->conn);
    $anio_activo_result = $anio_modelo->obtenerAnioActivo();
    
    // VALIDACIÓN ESTRICTA: Debe existir un año académico activo
    if (!$anio_activo_result || mysqli_num_rows($anio_activo_result) == 0) {
        throw new Exception("No hay un año académico activo configurado en el sistema.");
    }
    
    $anio_activo = mysqli_fetch_assoc($anio_activo_result);
    $desde_anio = $anio_activo['desde'];
    $hasta_anio = $anio_activo['hasta'];
    $hoy = date('Y-m-d');

    // VALIDACIÓN: Si se proporcionan fechas personalizadas, deben estar dentro del año académico
    if ($desde && $hasta) {
        if ($desde < $desde_anio || $desde > $hasta_anio) {
            throw new Exception("La fecha 'Desde' ({$desde}) está fuera del año académico activo ({$desde_anio} - {$hasta_anio})");
        }
        if ($hasta < $desde_anio || $hasta > $hasta_anio) {
            throw new Exception("La fecha 'Hasta' ({$hasta}) está fuera del año académico activo ({$desde_anio} - {$hasta_anio})");
        }
        if ($desde > $hasta) {
            throw new Exception("La fecha 'Desde' no puede ser mayor que la fecha 'Hasta'");
        }
        
        // Usar las fechas validadas
        $desde_usar = $desde;
        $hasta_usar = $hasta;
    } else {
        // Usar el año académico completo por defecto
        $desde_usar = $desde_anio;
        $hasta_usar = $hasta_anio;
        
        // Si HOY está dentro del año académico, usarlo como límite superior
        if ($hoy >= $desde_anio && $hoy <= $hasta_anio) {
            $hasta_usar = $hoy;
        }
        // Si HOY está antes del año académico, usar solo el primer día
        elseif ($hoy < $desde_anio) {
            $hasta_usar = $desde_anio;
        }
        // Si HOY está después, ya usamos el hasta_anio (año académico completo)
    }

    // Calcular días hábiles dentro del período validado
    $dias_habiles_periodo = $this->calcularDiasHabiles($desde_usar, $hasta_usar);

    // Validar que hay días hábiles para evitar división por cero
    if ($dias_habiles_periodo <= 0) {
        throw new Exception("No hay días hábiles en el período seleccionado ({$desde_usar} - {$hasta_usar})");
    }

    $where_condicion = "AND a.fecha BETWEEN '{$desde_usar}' AND '{$hasta_usar}'";

    $query = "SELECT 
                s.id_seccion,
                g.numero_anio AS grado,
                s.letra AS seccion,
                COUNT(DISTINCT e.id_estudiante) AS total_estudiantes,
                COALESCE(SUM(CASE WHEN a.inasistencia = 1 OR a.justificado = 1 THEN 1 ELSE 0 END), 0) AS total_inasistencias,
                (SELECT CONCAT(e2.nombre, ' ', e2.apellido) 
                 FROM estudiante e2 
                 LEFT JOIN asistencia a2 ON e2.id_estudiante = a2.id_estudiante {$where_condicion}
                 WHERE e2.id_seccion = s.id_seccion
                 GROUP BY e2.id_estudiante 
                 ORDER BY SUM(CASE WHEN a2.inasistencia = 1 OR a2.justificado = 1 THEN 1 ELSE 0 END) DESC 
                 LIMIT 1) AS estudiante_mas_inasistencias
             FROM seccion s
             JOIN grado g ON s.id_grado = g.id_grado
             LEFT JOIN estudiante e ON s.id_seccion = e.id_seccion
             LEFT JOIN asistencia a ON e.id_estudiante = a.id_estudiante {$where_condicion}
             WHERE e.id_estudiante IS NOT NULL
             GROUP BY s.id_seccion, g.numero_anio, s.letra
             ORDER BY g.numero_anio ASC, s.letra ASC";

    $result = $this->conn->query($query);

    if (!$result) {
        throw new Exception("Error en la consulta: " . $this->conn->error);
    }

    $reporte = [];
    while ($row = $result->fetch_assoc()) {
        // NUEVA FÓRMULA: (Total inasistencias sección / Total asistencia posible) × 100
        if ($row['total_estudiantes'] > 0 && $dias_habiles_periodo > 0) {
            $total_asistencia_posible = $row['total_estudiantes'] * $dias_habiles_periodo;
            $porcentaje_inasistencia = ($row['total_inasistencias'] / $total_asistencia_posible) * 100;
            $porcentaje_inasistencia = min(100, max(0, $porcentaje_inasistencia));
        } else {
            $porcentaje_inasistencia = 0;
            $total_asistencia_posible = 0;
        }

        $reporte[] = [
            'id_seccion' => $row['id_seccion'],
            'grado' => $row['grado'],
            'seccion' => $row['seccion'],
            'grado_seccion' => $row['grado'] . '° ' . $row['seccion'],
            'total_estudiantes' => $row['total_estudiantes'],
            'total_inasistencias' => $row['total_inasistencias'],
            'porcentaje_inasistencia' => round($porcentaje_inasistencia, 2),
            'estudiante_mas_inasistencias' => $row['estudiante_mas_inasistencias'] ?: 'Sin inasistencias',
            'dias_habiles_periodo' => $dias_habiles_periodo,
            'total_asistencia_posible' => $total_asistencia_posible,
            'periodo_desde' => $desde_usar,
            'periodo_hasta' => $hasta_usar
        ];
    }

    return $reporte;
}

private function calcularDiasHabiles($desde, $hasta)
{
    // Validar que las fechas sean válidas
    if (!$desde || !$hasta) {
        return 0;
    }
    
    try {
        $inicio = new DateTime($desde);
        $fin = new DateTime($hasta);
        
        // Si la fecha desde es mayor que hasta, invertirlas
        if ($inicio > $fin) {
            list($inicio, $fin) = array($fin, $inicio);
        }
        
        $dias_habiles = 0;
        $intervalo = new DateInterval('P1D');
        $periodo = new DatePeriod($inicio, $intervalo, $fin->modify('+1 day'));

        foreach ($periodo as $fecha) {
            $dia_semana = $fecha->format('N');
            if ($dia_semana >= 1 && $dia_semana <= 5) {
                $dias_habiles++;
            }
        }

        return $dias_habiles;
    } catch (Exception $e) {
        error_log("Error calculando días hábiles: " . $e->getMessage());
        return 0;
    }
}

}
