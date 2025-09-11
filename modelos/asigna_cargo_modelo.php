<?php
class AsignaCargoModelo {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Obtener todas las asignaciones con información de profesor y cargo
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

    // Obtener todos los profesores
    public function obtenerProfesores() {
        $query = "SELECT * FROM profesor ORDER BY apellido, nombre";
        $result = $this->db->query($query);

        $profesores = [];
        while ($row = $result->fetch_assoc()) {
            $profesores[] = $row;
        }

        return $profesores;
    }

    // Obtener todos los cargos
    public function obtenerCargos() {
        $query = "SELECT * FROM cargo ORDER BY nombre";
        $result = $this->db->query($query);

        $cargos = [];
        while ($row = $result->fetch_assoc()) {
            $cargos[] = $row;
        }

        return $cargos;
    }

    // Crear nueva asignación en la tabla intermedia
    public function crearAsignacion($id_profesor, $id_cargo) {
        // Verificar si ya existe la asignación activa
        if ($this->existeAsignacion($id_profesor, $id_cargo)) {
            return false;
        }

        $query = "INSERT INTO asigna_cargo (id_profesor, id_cargo) VALUES (?, ?)";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $id_profesor, $id_cargo);

        return $stmt->execute();
    }

    // Eliminar asignación
    public function eliminarAsignacion($id_asignacion) {
        $query = "DELETE FROM asigna_cargo WHERE id_asignacion = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id_asignacion);

        return $stmt->execute();
    }

    // Verificar si ya existe la asignación
    public function existeAsignacion($id_profesor, $id_cargo) {
        $query = "SELECT COUNT(*) as count FROM asigna_cargo
                  WHERE id_profesor = ? AND id_cargo = ? AND estado = 'activa'";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $id_profesor, $id_cargo);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['count'] > 0;
    }

    // Obtener cargos asignados a un profesor específico
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

    // Obtener profesores que tienen un cargo específico
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
}
?>
