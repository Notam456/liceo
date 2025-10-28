<?php
class AsignaCargoModelo {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function obtenerAsignaciones() {
        $query = "SELECT
                    ac.id_asignacion,
                    p.id_profesor,
                    p.nombre,
                    p.apellido,
                    p.cedula,
                    c.id_cargo,
                    c.nombre AS nombre_cargo,
                    ac.fecha_asignacion,
                    ac.estado
                  FROM asigna_cargo ac
                  JOIN profesor p ON ac.id_profesor = p.id_profesor
                  JOIN cargo c ON ac.id_cargo = c.id_cargo
                  WHERE ac.estado = 'activa'
                  ORDER BY p.apellido, p.nombre, c.nombre";

        $result = $this->db->query($query);

        if (!$result) {
            throw new Exception("Error en la consulta: " . $this->db->error);
        }

        $asignaciones = [];
        while ($row = $result->fetch_assoc()) {
            $asignaciones[] = $row;
        }

        return $asignaciones;
    }

    public function obtenerProfesores() {
        $query = "SELECT * FROM profesor ORDER BY apellido, nombre";
        $result = $this->db->query($query);

        $profesores = [];
        while ($row = $result->fetch_assoc()) {
            $profesores[] = $row;
        }

        return $profesores;
    }

    public function obtenerCargos() {
        $query = "SELECT * FROM cargo ORDER BY nombre";
        $result = $this->db->query($query);

        $cargos = [];
        while ($row = $result->fetch_assoc()) {
            $cargos[] = $row;
        }

        return $cargos;
    }

    public function crearAsignacion($id_profesor, $id_cargo) {
        $asignacionExistente = $this->existeAsignacion($id_profesor, $id_cargo);

        if ($asignacionExistente) {
            if ($asignacionExistente['estado'] == 'inactiva') {
                $id_asignacion = $asignacionExistente['id_asignacion'];
                $query = "UPDATE asigna_cargo SET estado = 'activa' WHERE id_asignacion = ?";
                $stmt = $this->db->prepare($query);
                $stmt->bind_param("i", $id_asignacion);
                return $stmt->execute();
            } else {
                return false;
            }
        }

        $query = "INSERT INTO asigna_cargo (id_profesor, id_cargo) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $id_profesor, $id_cargo);
        return $stmt->execute();
    }

    public function eliminarAsignacion($id_asignacion) {
        $query = "UPDATE asigna_cargo SET estado = 'inactiva' WHERE id_asignacion = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id_asignacion);
        return $stmt->execute();
    }

    public function existeAsignacion($id_profesor, $id_cargo) {
        $query = "SELECT * FROM asigna_cargo WHERE id_profesor = ? AND id_cargo = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $id_profesor, $id_cargo);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function obtenerCargosPorProfesor($id_profesor) {
        $query = "SELECT c.* FROM cargo c
                  JOIN asigna_cargo ac ON c.id_cargo = ac.id_cargo
                  WHERE ac.id_profesor = ? AND ac.estado = 'activa'";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id_profesor);
        $stmt->execute();
        $result = $stmt->get_result();
        $cargos = [];
        while ($row = $result->fetch_assoc()) {
            $cargos[] = $row;
        }
        return $cargos;
    }

    public function obtenerProfesoresPorCargo($id_cargo) {
        $query = "SELECT p.* FROM profesor p
                  JOIN asigna_cargo ac ON p.id_profesor = ac.id_profesor
                  WHERE ac.id_cargo = ? AND ac.estado = 'activa'";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id_cargo);
        $stmt->execute();
        $result = $stmt->get_result();
        $profesores = [];
        while ($row = $result->fetch_assoc()) {
            $profesores[] = $row;
        }
        return $profesores;
    }

    public function obtenerAsignacionPorId($id_asignacion) {
        $query = "SELECT
                    ac.id_asignacion,
                    p.id_profesor,
                    p.nombre,
                    p.apellido,
                    p.cedula,
                    c.id_cargo,
                    c.nombre AS nombre_cargo,
                    ac.fecha_asignacion,
                    ac.estado
                  FROM asigna_cargo ac
                  JOIN profesor p ON ac.id_profesor = p.id_profesor
                  JOIN cargo c ON ac.id_cargo = c.id_cargo
                  WHERE ac.id_asignacion = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id_asignacion);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function actualizarAsignacion($id_asignacion, $id_profesor, $id_cargo) {
        $asignacionExistente = $this->existeAsignacion($id_profesor, $id_cargo);
        if ($asignacionExistente && $asignacionExistente['id_asignacion'] != $id_asignacion) {
            return false;
        }

        $query = "UPDATE asigna_cargo SET id_profesor = ?, id_cargo = ? WHERE id_asignacion = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iii", $id_profesor, $id_cargo, $id_asignacion);
        return $stmt->execute();
    }
}
?>
