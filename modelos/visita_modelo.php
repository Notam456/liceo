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

    public function obtenerVisitas() {
        $query = "SELECT v.id_visita, v.fecha_visita, v.estado, e.nombre, e.apellido, e.cedula
                  FROM visita v
                  JOIN asistencia a ON v.id_asistencia = a.id_asistencia
                  JOIN estudiante e ON a.id_estudiante = e.id_estudiante
                  ORDER BY v.fecha_visita DESC";
        return mysqli_query($this->conn, $query);
    }

    public function actualizarEstadoVisita($id_visita, $estado) {
        $id_visita = (int)$id_visita;
        $estado = mysqli_real_escape_string($this->conn, $estado);
        $query = "UPDATE visita SET estado = '$estado' WHERE id_visita = $id_visita";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerVisitaPorId($id) {
        $id = (int)$id;
        $query = "SELECT
                    v.id_visita,
                    v.fecha_visita,
                    v.estado,
                    e.id_estudiante,
                    e.nombre,
                    e.apellido,
                    e.cedula,
                    e.contacto,
                    e.fecha_nacimiento,
                    p.parroquia,
                    g.numero_anio,
                    s.letra AS letra_seccion
                  FROM visita v
                  JOIN asistencia a ON v.id_asistencia = a.id_asistencia
                  JOIN estudiante e ON a.id_estudiante = e.id_estudiante
                  LEFT JOIN parroquia p ON e.id_parroquia = p.id_parroquia
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
