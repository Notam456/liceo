<?php
class ReporteModelo {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function obtenerReporteAusencias() {
        $query = "SELECT 
                    CONCAT(e.nombre_estudiante, ' ', e.apellido_estudiante) as nombre_completo,
                    e.seccion_estudiante as seccion,
                    e.contacto_estudiante as contacto,
                    e.cedula_estudiante as cedula,
                    COALESCE((SELECT COUNT(*) FROM asistencia a 
                             WHERE a.id_estudiante = e.id_estudiante 
                             AND a.estado = 'A'), 0) as ausencias,
                    COALESCE((SELECT COUNT(*) FROM asistencia a 
                             WHERE a.id_estudiante = e.id_estudiante 
                             AND a.estado = 'J'), 0) as justificadas,
                    COALESCE((SELECT COUNT(*) FROM asistencia a 
                             WHERE a.id_estudiante = e.id_estudiante 
                             AND (a.estado = 'A' OR a.estado = 'J')), 0) as total
                 FROM estudiante e";

        $result = $this->db->query($query);

        if (!$result) {
            throw new Exception("Error en la consulta: " . $this->db->error);
        }

        $reporte = [];
        while ($row = $result->fetch_assoc()) {
            $reporte[] = [
                'nombre' => $row['nombre_completo'],
                'seccion' => $row['seccion'],
                'contacto' => $row['contacto'],
                'cedula' => $row['cedula'],
                'ausencias' => (int)$row['ausencias'],
                'justificadas' => (int)$row['justificadas'],
                'total' => (int)$row['total']
            ];
        }

        return $reporte;
    }
}
?>