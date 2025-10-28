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
            case 'directivo':
                $query = "SELECT s.*, g.numero_anio FROM seccion AS s JOIN grado AS g ON s.id_grado = g.id_grado WHERE s.visibilidad = TRUE";

                break;
            case 'inferior':
                $query = "SELECT s.*, g.numero_anio FROM seccion AS s JOIN grado AS g ON s.id_grado = g.id_grado WHERE g.numero_anio < 4 AND s.visibilidad = TRUE";
                break;
            case 'superior':
                $query = "SELECT s.*, g.numero_anio FROM seccion AS s JOIN grado AS g ON s.id_grado = g.id_grado WHERE g.numero_anio > 3 AND s.visibilidad = TRUE";
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
        $query = "UPDATE seccion SET visibilidad = FALSE WHERE id_seccion ='$id'";
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
        $query = "SELECT s.*, g.numero_anio FROM seccion AS s JOIN grado AS g ON s.id_grado = g.id_grado WHERE s.id_tutor = $id_tutor AND s.visibilidad = TRUE";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerMatriculaCompletaPorSeccion($id_seccion)
    {
        $id_seccion = (int)$id_seccion;

        $query = "SELECT 
                e.nombre,
                e.apellido,
                e.cedula,
                e.fecha_nacimiento,
                e.direccion_exacta,
                e.punto_referencia,
                m.municipio,
                p.parroquia,
                s.sector,
                g.numero_anio,
                sec.letra,
                prof.nombre AS nombre_tutor,
                prof.apellido AS apellido_tutor,
                prof.cedula AS cedula_tutor
             FROM estudiante e
             JOIN asigna_seccion asig ON e.id_estudiante = asig.id_estudiante
             JOIN anio_academico anio ON asig.id_anio = anio.id_anio AND anio.estado = 1
             JOIN sector s ON e.id_sector = s.id_sector
             JOIN parroquia p ON s.id_parroquia = p.id_parroquia
             JOIN municipio m ON p.id_municipio = m.id_municipio
             JOIN seccion sec ON asig.id_seccion = sec.id_seccion
             JOIN grado g ON sec.id_grado = g.id_grado
             LEFT JOIN profesor prof ON sec.id_tutor = prof.id_profesor
             WHERE asig.id_seccion = $id_seccion
             ORDER BY e.apellido, e.nombre";

        $result = $this->conn->query($query);

        if (!$result) {
            throw new Exception("Error en la consulta: " . $this->conn->error);
        }

        $matricula = [];
        $tutor_info = null;

        while ($row = $result->fetch_assoc()) {
            // Solo necesitamos la info del tutor una vez (es la misma para todos los estudiantes)
            if ($tutor_info === null) {
                $tutor_info = [
                    'nombre_tutor' => $row['nombre_tutor'],
                    'apellido_tutor' => $row['apellido_tutor'],
                    'cedula_tutor' => $row['cedula_tutor']
                ];
            }

            // Formatear la dirección completa
            $direccion_completa = $row['direccion_exacta'];
            if (!empty($row['punto_referencia'])) {
                $direccion_completa .= " (Ref: " . $row['punto_referencia'] . ")";
            }
            $direccion_completa .= ", " . $row['sector'] . ", " . $row['parroquia'] . ", " . $row['municipio'];

            // Formatear fecha de nacimiento
            $fecha_nacimiento = $row['fecha_nacimiento'] ? date('d/m/Y', strtotime($row['fecha_nacimiento'])) : 'No registrada';

            $matricula[] = [
                'nombre' => $row['nombre'],
                'apellido' => $row['apellido'],
                'cedula' => $row['cedula'],
                'fecha_nacimiento' => $fecha_nacimiento,
                'direccion_completa' => $direccion_completa,
                'grado_seccion' => $row['numero_anio'] . '° ' . $row['letra'],
                'tutor' => $tutor_info
            ];
        }

        // Si no hay estudiantes pero necesitamos la info del tutor
        if (empty($matricula) && $tutor_info === null) {
            // Consulta adicional para obtener solo la info del tutor
            $query_tutor = "SELECT 
                        sec.letra,
                        g.numero_anio,
                        prof.nombre AS nombre_tutor,
                        prof.apellido AS apellido_tutor,
                        prof.cedula AS cedula_tutor
                     FROM seccion sec
                     JOIN grado g ON sec.id_grado = g.id_grado
                     LEFT JOIN profesor prof ON sec.id_tutor = prof.id_profesor
                     WHERE sec.id_seccion = $id_seccion";

            $result_tutor = $this->conn->query($query_tutor);
            if ($result_tutor && $row_tutor = $result_tutor->fetch_assoc()) {
                $tutor_info = [
                    'nombre_tutor' => $row_tutor['nombre_tutor'],
                    'apellido_tutor' => $row_tutor['apellido_tutor'],
                    'cedula_tutor' => $row_tutor['cedula_tutor']
                ];

                // Agregar info de grado y sección
                $matricula['grado_seccion'] = $row_tutor['numero_anio'] . '° ' . $row_tutor['letra'];
                $matricula['tutor'] = $tutor_info;
            }
        }

        return $matricula;
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
                 JOIN asigna_seccion asig2 ON e2.id_estudiante = asig2.id_estudiante
                 JOIN anio_academico anio2 ON asig2.id_anio = anio2.id_anio AND anio2.estado = 1
                 LEFT JOIN asistencia a2 ON e2.id_estudiante = a2.id_estudiante {$where_condicion}
                 WHERE asig2.id_seccion = s.id_seccion
                 GROUP BY e2.id_estudiante 
                 ORDER BY SUM(CASE WHEN a2.inasistencia = 1 OR a2.justificado = 1 THEN 1 ELSE 0 END) DESC 
                 LIMIT 1) AS estudiante_mas_inasistencias
             FROM seccion s
             JOIN grado g ON s.id_grado = g.id_grado
             LEFT JOIN asigna_seccion asig ON s.id_seccion = asig.id_seccion
             LEFT JOIN anio_academico anio ON asig.id_anio = anio.id_anio AND anio.estado = 1
             LEFT JOIN estudiante e ON asig.id_estudiante = e.id_estudiante
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
