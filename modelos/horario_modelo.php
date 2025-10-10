<?php
class HorarioModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    private function executeQuery($query, $params = [], $types = "") {
        $stmt = mysqli_prepare($this->conn, $query);
        if (!$stmt) {
            die("Error preparing statement: " . mysqli_error($this->conn));
        }
        if ($params) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        if (!mysqli_stmt_execute($stmt)) {
            die("Error executing statement: " . mysqli_stmt_error($stmt));
        }
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    public function getHorarioBySeccion($id_seccion) {
        $query = "SELECT h.id_horario, h.dia, m.nombre AS nombre_materia, p.nombre AS nombre_profesor, p.apellido AS apellido_profesor
                  FROM horario h
                  JOIN asigna_materia am ON h.id_asignacion = am.id_asignacion
                  JOIN materia m ON am.id_materia = m.id_materia
                  JOIN profesor p ON am.id_profesor = p.id_profesor
                  WHERE h.id_seccion = ?";
        return $this->executeQuery($query, [$id_seccion], "i");
    }

    public function getAsignaciones() {
        $query = "SELECT am.id_asignacion, m.nombre AS nombre_materia, p.nombre AS nombre_profesor, p.apellido AS apellido_profesor
                  FROM asigna_materia am
                  JOIN materia m ON am.id_materia = m.id_materia
                  JOIN profesor p ON am.id_profesor = p.id_profesor
                  WHERE am.estado = 'activa'";
        return $this->executeQuery($query);
    }

    public function getMateriasPorSeccionYDia($id_seccion, $dia) {
        $query = "SELECT am.id_asignacion, m.nombre AS nombre_materia
                  FROM horario h
                  JOIN asigna_materia am ON h.id_asignacion = am.id_asignacion
                  JOIN materia m ON am.id_materia = m.id_materia
                  WHERE h.id_seccion = ? AND h.dia = ?";
        $result = $this->executeQuery($query, [$id_seccion, $dia], "is");
        $materias = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $materias[] = $row;
        }
        return $materias;
    }

    public function guardarBloqueHorario($id_seccion, $dia, $id_asignacion) {
        $query = "INSERT INTO horario (id_seccion, dia, id_asignacion) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "isi", $id_seccion, $dia, $id_asignacion);
        if (mysqli_stmt_execute($stmt)) {
            return mysqli_insert_id($this->conn);
        }
        return false;
    }

    public function eliminarBloqueHorario($id_horario) {
        $query = "DELETE FROM horario WHERE id_horario = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id_horario);
        return mysqli_stmt_execute($stmt);
    }
}
?>