<?php
class AsignaMateriaModelo {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function obtenerAsignaciones() {
        $query = "SELECT 
                    am.id_asignacion,
                    p.id_profesor,
                    p.nombre, 
                    p.apellido,
                    p.cedula,
                    m.id_materia,
                    m.nombre AS nombre_materia,
                    am.fecha_asignacion,
                    am.estado
                  FROM asigna_materia am
                  JOIN profesor p ON am.id_profesor = p.id_profesor
                  JOIN materia m ON am.id_materia = m.id_materia
                  WHERE am.estado = 'activa'
                  ORDER BY p.apellido, p.nombre, m.nombre";
        
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

    public function obtenerMaterias() {
        $query = "SELECT * FROM materia ORDER BY nombre";
        $result = $this->db->query($query);
        
        $materias = [];
        while ($row = $result->fetch_assoc()) {
            $materias[] = $row;
        }

        return $materias;
    }

    public function crearAsignacion($id_profesor, $id_materia) {
        $asignacionExistente = $this->existeAsignacion($id_profesor, $id_materia);

        if ($asignacionExistente) {
            if ($asignacionExistente['estado'] == 'inactiva') {
                $id_asignacion = $asignacionExistente['id_asignacion'];
                $query = "UPDATE asigna_materia SET estado = 'activa' WHERE id_asignacion = ?";
                $stmt = $this->db->prepare($query);
                $stmt->bind_param("i", $id_asignacion);
                return $stmt->execute();
            } else {
                return false;
            }
        }

        $query = "INSERT INTO asigna_materia (id_profesor, id_materia) VALUES (?, ?)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $id_profesor, $id_materia);
        
        return $stmt->execute();
    }

    public function eliminarAsignacion($id_asignacion) {
        $query = "UPDATE asigna_materia SET estado = 'inactiva' WHERE id_asignacion = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id_asignacion);
        
        return $stmt->execute();
    }

    public function existeAsignacion($id_profesor, $id_materia) {
        $query = "SELECT * FROM asigna_materia WHERE id_profesor = ? AND id_materia = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $id_profesor, $id_materia);
        $stmt->execute();
        
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function obtenerMateriasPorProfesor($id_profesor) {
        $query = "SELECT m.* FROM materia m
                  JOIN asigna_materia am ON m.id_materia = am.id_materia
                  WHERE am.id_profesor = ? AND am.estado = 'activa'";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id_profesor);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        $materias = [];
        while ($row = $result->fetch_assoc()) {
            $materias[] = $row;
        }

        return $materias;
    }

    public function obtenerProfesoresPorMateria($id_materia) {
        $query = "SELECT p.* FROM profesor p
                  JOIN asigna_materia am ON p.id_profesor = am.id_profesor
                  WHERE am.id_materia = ? AND am.estado = 'activa'";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id_materia);
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
                    am.id_asignacion,
                    p.id_profesor,
                    p.nombre, 
                    p.apellido,
                    p.cedula,
                    m.id_materia,
                    m.nombre AS nombre_materia,
                    am.fecha_asignacion,
                    am.estado
                  FROM asigna_materia am
                  JOIN profesor p ON am.id_profesor = p.id_profesor
                  JOIN materia m ON am.id_materia = m.id_materia
                  WHERE am.id_asignacion = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id_asignacion);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function actualizarAsignacion($id_asignacion, $id_profesor, $id_materia) {
        $asignacionExistente = $this->existeAsignacion($id_profesor, $id_materia);
        if ($asignacionExistente && $asignacionExistente['id_asignacion'] != $id_asignacion) {
            return false;
        }

        $query = "UPDATE asigna_materia SET id_profesor = ?, id_materia = ? WHERE id_asignacion = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iii", $id_profesor, $id_materia, $id_asignacion);

        return $stmt->execute();
    }
}
?>
