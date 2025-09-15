<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/visita_modelo.php');

class ReporteModelo
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function obtenerReporteAusencias($desde = null, $hasta = null)
    {
        $where_condicion = "";
        if ($desde && $hasta) {
            $where_condicion = "AND a.fecha BETWEEN '{$desde}' AND '{$hasta}'";
        }

        $condicion = "1=1"; 
        switch ($_SESSION['tipo_cargo']) {
            case 'inferior':
                $condicion = " g.numero_anio < 4";
                break;
            case 'superior':
                $condicion = " g.numero_anio > 3";
                break;
                // en 'Administrador' no agregamos condición extra
        }

        $query = "SELECT 
                    e.id_estudiante,
                    CONCAT(e.nombre, ' ', e.apellido) AS nombre_completo,
                    CONCAT(COALESCE(g.numero_anio, ''), CASE WHEN g.numero_anio IS NULL THEN '' ELSE '° ' END, COALESCE(s.letra, '')) AS seccion,
                    e.contacto AS contacto,
                    e.cedula AS cedula,
                    COALESCE(SUM(CASE WHEN a.inasistencia = 1 THEN 1 ELSE 0 END), 0) AS ausencias,
                    COALESCE(SUM(CASE WHEN a.justificado = 1 THEN 1 ELSE 0 END), 0) AS justificadas,
                    COALESCE(SUM(CASE WHEN a.inasistencia = 1 OR a.justificado = 1 THEN 1 ELSE 0 END), 0) AS total,
                    (SELECT COALESCE(SUM(CASE WHEN a2.inasistencia = 1 OR a2.justificado = 1 THEN 1 ELSE 0 END), 0)
                     FROM asistencia a2
                     WHERE a2.id_estudiante = e.id_estudiante
                     AND YEARWEEK(a2.fecha, 1) = YEARWEEK(CURDATE(), 1)) AS total_ultima_semana
                 FROM estudiante e
                 LEFT JOIN asistencia a ON a.id_estudiante = e.id_estudiante $where_condicion
                 LEFT JOIN seccion s ON e.id_seccion = s.id_seccion
                 LEFT JOIN grado g ON s.id_grado = g.id_grado
                  WHERE $condicion
                 GROUP BY e.id_estudiante, nombre_completo, seccion, contacto, cedula
                ";

        $result = $this->db->query($query);

        if (!$result) {
            throw new Exception("Error en la consulta: " . $this->db->error);
        }

        $visitaModelo = new VisitaModelo($this->db);
        $reporte = [];
        while ($row = $result->fetch_assoc()) {
            $tiene_visita = $visitaModelo->tieneVisitaAgendada($row['id_estudiante']);
            $reporte[] = [
                'id_estudiante' => $row['id_estudiante'],
                'nombre' => $row['nombre_completo'],
                'seccion' => $row['seccion'],
                'contacto' => $row['contacto'],
                'cedula' => $row['cedula'],
                'ausencias' => (int)$row['ausencias'],
                'justificadas' => (int)$row['justificadas'],
                'total' => (int)$row['total'],
                'total_ultima_semana' => (int)$row['total_ultima_semana'],
                'tiene_visita_agendada' => $tiene_visita
            ];
        }

        return $reporte;
    }

    public function obtenerFechasAusencias($id_estudiante, $desde = null, $hasta = null)
    {
        $where_condicion = "";
        if ($desde && $hasta) {
            $where_condicion = "AND a.fecha BETWEEN '{$desde}' AND '{$hasta}'";
        }

        $query = "SELECT a.fecha, a.justificado 
                  FROM asistencia a 
                  WHERE a.id_estudiante = {$id_estudiante} 
                  AND (a.inasistencia = 1 OR a.justificado = 1)
                  {$where_condicion}
                  ORDER BY a.fecha DESC";

        $result = $this->db->query($query);

        if (!$result) {
            throw new Exception("Error en la consulta: " . $this->db->error);
        }

        $fechas = [];
        while ($row = $result->fetch_assoc()) {
            $fechas[] = [
                'fecha' => $row['fecha'],
                'justificado' => (bool)$row['justificado']
            ];
        }

        return $fechas;
    }
}
