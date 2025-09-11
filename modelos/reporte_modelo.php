<?php
class ReporteModelo {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function obtenerReporteAusencias() {
        $query = "SELECT 
                    CONCAT(e.nombre, ' ', e.apellido) AS nombre_completo,
                    CONCAT(COALESCE(g.numero_anio, ''), CASE WHEN g.numero_anio IS NULL THEN '' ELSE '° ' END, COALESCE(s.letra, '')) AS seccion,
                    e.contacto AS contacto,
                    e.cedula AS cedula,
                    COALESCE(SUM(CASE WHEN a.inasistencia = 1 THEN 1 ELSE 0 END), 0) AS ausencias,
                    COALESCE(SUM(CASE WHEN a.justificado = 1 THEN 1 ELSE 0 END), 0) AS justificadas,
                    COALESCE(SUM(CASE WHEN a.inasistencia = 1 OR a.justificado = 1 THEN 1 ELSE 0 END), 0) AS total
                 FROM estudiante e
                 LEFT JOIN asistencia a ON a.id_estudiante = e.id_estudiante
                 LEFT JOIN seccion s ON e.id_seccion = s.id_seccion
                 LEFT JOIN grado g ON s.id_grado = g.id_grado
                 GROUP BY e.id_estudiante, nombre_completo, seccion, contacto, cedula";

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