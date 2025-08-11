<?php
class HorarioModel {
    private $conn;

    public function __construct() {
        include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
        $this->conn = $conn;
    }

    private function executeQuery($query) {
        $result = mysqli_query($this->conn, $query);
        if ($result === false) {
            die("Error en la consulta: " . mysqli_error($this->conn) . "\nConsulta: " . $query);
        }
        return $result;
    }

    public function getHorarioBySeccion($seccion) {
        $seccion = mysqli_real_escape_string($this->conn, $seccion);
        $query = "SELECT h.*, m.nombre_materia, p.nombre_profesores
                  FROM horario h
                  JOIN materia m ON h.id_materia = m.id_materia
                  JOIN profesores p ON h.id_profesores = p.id_profesores
                  WHERE h.id_seccion = $seccion";
        return $this->executeQuery($query);
    }

    public function getMaterias() {
        $query = "SELECT * FROM materia";
        return $this->executeQuery($query);
    }

    public function getProfesores() {
        $query = "SELECT * FROM profesores";
        return $this->executeQuery($query);
    }

    public function saveHorarioSlot($data) {
        $id_seccion = $data['seccion'];
        $dia = $data['dia'];
        $hora_inicio = $data['inicio'];
        $hora_fin = $data['fin'];
        $id_materia = $data['materia'];
        $id_profesor = $data['profesor'];

        $horas_inicio = [
            0 => "07:20:00", 1 => "08:10:00", 2 => "08:50:00", 3 => "09:05:00", 4 => "09:45:00",
            5 => "10:25:00", 6 => "10:30:00", 7 => "11:45:00", 8 => "12:10:00", 9 => "12:50:00",
        ];
        $horas_fin_map = [
            0 => "08:10:00", 1 => "08:50:00", 2 => "09:05:00", 3 => "09:45:00", 4 => "10:25:00",
            5 => "10:30:00", 6 => "11:45:00", 7 => "12:10:00", 8 => "12:50:00", 9 => "13:30:00"
        ];

        $hora_inicio_real = $horas_inicio[$hora_inicio] ?? "00:00:00";
        $hora_fin_real = $horas_fin_map[$hora_fin] ?? "00:00:00";

        $query = "INSERT INTO horario (id_seccion, dia, hora_inicio, hora_fin, id_materia, id_profesores)
                  VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "isssii", $id_seccion, $dia, $hora_inicio_real, $hora_fin_real, $id_materia, $id_profesor);

        if (mysqli_stmt_execute($stmt)) {
            return mysqli_insert_id($this->conn);
        } else {
            return false;
        }
    }

    public function deleteHorarioSlot($data) {
        $id_seccion = $data['seccion'];
        $dia = $data['dia'];
        $hora = $data['hora'];

        $horas_inicio = [
            0 => "07:20:00", 1 => "08:10:00", 2 => "08:50:00", 3 => "09:05:00", 4 => "09:45:00",
            5 => "10:25:00", 6 => "10:30:00", 7 => "11:45:00", 8 => "12:10:00", 9 => "12:50:00"
        ];
        $horas_fin = [
            0 => "08:10:00", 1 => "08:50:00", 2 => "09:05:00", 3 => "09:45:00", 4 => "10:25:00",
            5 => "10:30:00", 6 => "11:45:00", 7 => "12:10:00", 8 => "12:50:00", 9 => "13:30:00"
        ];

        $hora_inicio_real = $horas_inicio[$hora] ?? null;
        $hora_fin_real = $horas_fin[$hora] ?? null;

        if (!$hora_inicio_real || !$hora_fin_real) {
            return false;
        }

        $query = "DELETE FROM horario
                  WHERE id_seccion = ? AND dia = ? AND hora_inicio = ? AND hora_fin = ?";

        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "isss", $id_seccion, $dia, $hora_inicio_real, $hora_fin_real);

        return mysqli_stmt_execute($stmt);
    }
}
?>
