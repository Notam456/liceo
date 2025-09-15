<?php

class VisitaModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearVisita($id_estudiante, $fecha_visita) {
        $id_estudiante = (int)$id_estudiante;
        $fecha_visita = mysqli_real_escape_string($this->conn, $fecha_visita);
        $estado = 'agendada';

        // The DB schema forces a link to a single assistance, but the visit is for multiple absences.
        // As a workaround, we'll link the visit to the student's most recent absence.
        $query_asistencia = "SELECT id_asistencia FROM asistencia WHERE id_estudiante = $id_estudiante AND inasistencia = 1 ORDER BY fecha DESC LIMIT 1";
        $result_asistencia = mysqli_query($this->conn, $query_asistencia);

        if(mysqli_num_rows($result_asistencia) > 0){
            $row = mysqli_fetch_assoc($result_asistencia);
            $id_asistencia = $row['id_asistencia'];

            $query = "INSERT INTO visita(id_asistencia, fecha_visita, estado)
                      VALUES ('$id_asistencia', '$fecha_visita', '$estado')";
            return mysqli_query($this->conn, $query);
        }
        return false; // No absence found for the student.
    }

    public function tieneVisitaAgendada($id_estudiante) {
        $id_estudiante = (int)$id_estudiante;
        $query = "SELECT 1
                  FROM visita v
                  JOIN asistencia a ON v.id_asistencia = a.id_asistencia
                  WHERE a.id_estudiante = $id_estudiante AND v.estado = 'agendada'
                  LIMIT 1";
        $result = mysqli_query($this->conn, $query);
        return mysqli_num_rows($result) > 0;
    }

    public function obtenerVisitas() {
        $query = "SELECT v.id_visita, v.fecha_visita, v.estado, e.nombre, e.apellido, e.cedula
                  FROM visita v
                  JOIN asistencia a ON v.id_asistencia = a.id_asistencia
                  JOIN estudiante e ON a.id_estudiante = e.id_estudiante
                  ORDER BY v.fecha_visita DESC";
        return mysqli_query($this->conn, $query);
    }

    public function actualizarVisita($id_visita, $estado, $observaciones, $fecha_realizada) {
        $id_visita = (int)$id_visita;
        $estado = mysqli_real_escape_string($this->conn, $estado);
        $observaciones = mysqli_real_escape_string($this->conn, $observaciones);
        if (empty($fecha_realizada)) {
            $fecha_realizada = date('Y-m-d');
        } else {
            $fecha_realizada = mysqli_real_escape_string($this->conn, $fecha_realizada);
        }
        $query = "UPDATE visita SET estado = '$estado', observaciones = '$observaciones', fecha_realizada = '$fecha_realizada' WHERE id_visita = $id_visita";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerVisitaPorId($id) {
        $id = (int)$id;
        $query = "SELECT
                    v.id_visita,
                    v.fecha_visita,
                    v.estado,
                    v.observaciones,
                    v.fecha_realizada,
                    e.id_estudiante,
                    e.nombre,
                    e.apellido,
                    e.cedula,
                    e.contacto,
                    e.fecha_nacimiento,
                    e.direccion_exacta,
                    e.punto_referencia,
                    sec.sector,
                    p.parroquia,
                    m.municipio,
                    g.numero_anio,
                    s.letra AS letra_seccion
                  FROM visita v
                  JOIN asistencia a ON v.id_asistencia = a.id_asistencia
                  JOIN estudiante e ON a.id_estudiante = e.id_estudiante
                  LEFT JOIN sector sec ON e.id_sector = sec.id_sector
                  LEFT JOIN parroquia p ON sec.id_parroquia = p.id_parroquia
                  LEFT JOIN municipio m ON p.id_municipio = m.id_municipio
                  LEFT JOIN seccion s ON e.id_seccion = s.id_seccion
                  LEFT JOIN grado g ON s.id_grado = g.id_grado
                  WHERE v.id_visita = $id";
        return mysqli_query($this->conn, $query);
    }

    public function eliminarVisita($id) {
        $id = (int)$id;
        $query = "DELETE FROM visita WHERE id_visita = $id";
        return mysqli_query($this->conn, $query);
    }
}
?>
