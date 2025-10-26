<?php
class AsignaMateriaModelo {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Obtener todas las asignaciones con información de profesor y materia
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

    // Obtener todas las materias
    public function obtenerMaterias() {
        $query = "SELECT * FROM materia ORDER BY nombre";
        $result = $this->db->query($query);
        
        $materias = [];
        while ($row = $result->fetch_assoc()) {
            $materias[] = $row;
        }

        return $materias;
    }

    // Crear nueva asignación en la tabla intermedia
    public function crearAsignacion($id_profesor, $id_materia) {
        // Verificar si ya existe la asignación activa
        if ($this->existeAsignacion($id_profesor, $id_materia)) {
            return false;
        }

        $query = "INSERT INTO asigna_materia (id_profesor, id_materia) VALUES (?, ?)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $id_profesor, $id_materia);
        
        return $stmt->execute();
    }

    // Eliminar asignación
    public function eliminarAsignacion($id_asignacion) {
        $query = "UPDATE asigna_materia SET estado = 'inactiva' WHERE id_asignacion = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id_asignacion);
        
        return $stmt->execute();
    }

    // Verificar si ya existe la asignación
    public function existeAsignacion($id_profesor, $id_materia) {
        $query = "SELECT COUNT(*) as count FROM asigna_materia 
                  WHERE id_profesor = ? AND id_materia = ? AND estado = 'activa'";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $id_profesor, $id_materia);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['count'] > 0;
    }

    // Obtener materias asignadas a un profesor específico
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

    // Obtener profesores que enseñan una materia específica
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

    // Obtener asignación por ID
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

    // Actualizar asignación
    public function actualizarAsignacion($id_asignacion, $id_profesor, $id_materia) {
        // Verificar si ya existe otra asignación con el mismo profesor y materia
        $query_check = "SELECT COUNT(*) as count FROM asigna_materia
                       WHERE id_profesor = ? AND id_materia = ? AND id_asignacion != ? AND estado = 'activa'";

        $stmt_check = $this->db->prepare($query_check);
        $stmt_check->bind_param("iii", $id_profesor, $id_materia, $id_asignacion);
        $stmt_check->execute();

        $result = $stmt_check->get_result();
        $row = $result->fetch_assoc();

        if ($row['count'] > 0) {
            return false; // Ya existe otra asignación con estos datos
        }

        $query = "UPDATE asigna_materia SET id_profesor = ?, id_materia = ? WHERE id_asignacion = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iii", $id_profesor, $id_materia, $id_asignacion);

        return $stmt->execute();
    }
}
?>