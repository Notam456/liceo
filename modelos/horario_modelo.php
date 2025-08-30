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
        $query = "SELECT h.*, m.nombre AS nombre_materia, p.nombre AS nombre_profesor
                  FROM horario h
                  JOIN materia m ON h.id_materia = m.id_materia
                  JOIN profesor p ON h.id_profesor = p.id_profesor
                  WHERE h.id_seccion = ?";
        return $this->executeQuery($query, [$id_seccion], "i");
    }

    public function getMaterias() {
        $query = "SELECT * FROM materia";
        return $this->executeQuery($query);
    }

    public function getProfesores() {
        $query = "SELECT * FROM profesores";
        return $this->executeQuery($query);
    }

    public function guardarBloqueHorario($id_seccion, $dia, $hora_inicio, $hora_fin, $id_materia, $id_profesor) {
        $horas_inicio_map = ["07:20:00", "08:10:00", "08:50:00", "09:05:00", "09:45:00", "10:25:00", "10:30:00", "11:45:00", "12:10:00", "12:50:00"];
        $horas_fin_map = ["08:10:00", "08:50:00", "09:05:00", "09:45:00", "10:25:00", "10:30:00", "11:45:00", "12:10:00", "12:50:00", "13:30:00"];

        $hora_inicio_real = $horas_inicio_map[$hora_inicio] ?? "00:00:00";
        $hora_fin_real = $horas_fin_map[$hora_fin] ?? "00:00:00";

        $query = "INSERT INTO horario (id_seccion, dia, hora_inicio, hora_fin, id_materia, id_profesores)
                  VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "isssii", $id_seccion, $dia, $hora_inicio_real, $hora_fin_real, $id_materia, $id_profesor);

        if (mysqli_stmt_execute($stmt)) {
            return mysqli_insert_id($this->conn);
        }
        return false;
    }

    public function eliminarBloqueHorario($id_seccion, $dia, $hora_index) {
        $horas_inicio_map = ["07:20:00", "08:10:00", "08:50:00", "09:05:00", "09:45:00", "10:25:00", "10:30:00", "11:45:00", "12:10:00", "12:50:00"];
        $horas_fin_map = ["08:10:00", "08:50:00", "09:05:00", "09:45:00", "10:25:00", "10:30:00", "11:45:00", "12:10:00", "12:50:00", "13:30:00"];

        $hora_inicio_real = $horas_inicio_map[$hora_index] ?? null;
        $hora_fin_real = $horas_fin_map[$hora_index] ?? null;

        if (!$hora_inicio_real || !$hora_fin_real) {
            return false;
        }

        $query = "DELETE FROM horario WHERE id_seccion = ? AND dia = ? AND hora_inicio = ? AND hora_fin = ?";

        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "isss", $id_seccion, $dia, $hora_inicio_real, $hora_fin_real);

        return mysqli_stmt_execute($stmt);
    }
}
?>
