<?php

class ProfesorModelo
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function crearProfesor($nombre, $apellido, $cedula)
    {
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $apellido = mysqli_real_escape_string($this->conn, $apellido);
        $cedula = mysqli_real_escape_string($this->conn, $cedula);


        $query = "INSERT INTO profesor(nombre, apellido, cedula)
                  VALUES ('$nombre', '$apellido', '$cedula')";

        try {
            $insert_query_run = mysqli_query($this->conn, $query);
            return true; // éxito
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                return 1062; // clave duplicada
            }
            return false; // otro error
        }
    }

    public function obtenerProfesorPorId($id)
    {
        $id = (int)$id;
        $query = "SELECT * FROM profesor WHERE id_profesor = '$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerTodosLosProfesores()
    {
        $query = "SELECT * FROM profesor";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerReporteCompletoProfesores()
    {
    $query = "SELECT 
                p.id_profesor,
                p.nombre,
                p.apellido,
                p.cedula,
                GROUP_CONCAT(DISTINCT c.nombre SEPARATOR ', ') AS cargos,
                GROUP_CONCAT(DISTINCT m.nombre SEPARATOR ', ') AS materias
             FROM profesor p
             LEFT JOIN asigna_cargo ac ON p.id_profesor = ac.id_profesor AND ac.estado = 'activa'
             LEFT JOIN cargo c ON ac.id_cargo = c.id_cargo
             LEFT JOIN asigna_materia am ON p.id_profesor = am.id_profesor AND am.estado = 'activa'
             LEFT JOIN materia m ON am.id_materia = m.id_materia
             GROUP BY p.id_profesor, p.nombre, p.apellido, p.cedula
             ORDER BY p.apellido, p.nombre";
    
    $result = $this->conn->query($query);
    
    if (!$result) {
        throw new Exception("Error en la consulta: " . $this->conn->error);
    }
    
    $profesores = [];
    while ($row = $result->fetch_assoc()) {
        $profesores[] = [
            'nombre' => $row['nombre'],
            'apellido' => $row['apellido'],
            'cedula' => $row['cedula'],
            'cargos' => $row['cargos'] ?: 'Sin cargo asignado',
            'materias' => $row['materias'] ?: 'Sin materia asignada'
        ];
    }
    
    return $profesores;
}

    public function obtenerTodosLosProfesoresConCargo()
    {
        $query = "SELECT p.* FROM profesor p INNER JOIN asigna_cargo a ON a.id_profesor = p.id_profesor ";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerDirector()
{
    $query = "SELECT p.nombre, p.apellido, p.cedula 
              FROM profesor p 
              INNER JOIN asigna_cargo ac ON p.id_profesor = ac.id_profesor 
              INNER JOIN cargo c ON ac.id_cargo = c.id_cargo 
              WHERE c.nombre = 'Director' AND ac.estado = 'activa' 
              LIMIT 1";

    $result = mysqli_query($this->conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    
    return null; 
}

    public function actualizarProfesor($id, $nombre, $apellido, $cedula)
    {
        $id = (int)$id;
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $apellido = mysqli_real_escape_string($this->conn, $apellido);
        $cedula = mysqli_real_escape_string($this->conn, $cedula);


        $query = "UPDATE profesor SET
                    nombre = '$nombre',
                    apellido = '$apellido',
                    cedula = '$cedula'
                  WHERE id_profesor = $id";

        try {
            $update_query_run = mysqli_query($this->conn, $query);
            return $update_query_run;
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                return 1062; // clave duplicada
            }
            return false; // otro error
        }
    }

    public function eliminarProfesor($id)
    {
        $id = (int)$id;
        $query = "DELETE FROM profesor WHERE id_profesor ='$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerCargosPorProfesor($id_profesor)
    {
        $query = "SELECT c.nombre FROM cargo c
                  JOIN asigna_cargo ac ON c.id_cargo = ac.id_cargo
                  WHERE ac.id_profesor = ? AND ac.estado = 'activa'";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_profesor);
        $stmt->execute();

        $result = $stmt->get_result();

        $cargos = [];
        while ($row = $result->fetch_assoc()) {
            $cargos[] = $row;
        }

        return $cargos;
    }

    public function obtenerMateriasPorProfesor($id_profesor)
    {
        $query = "SELECT m.nombre FROM materia m
                  JOIN asigna_materia am ON m.id_materia = am.id_materia
                  WHERE am.id_profesor = ? AND am.estado = 'activa'";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_profesor);
        $stmt->execute();

        $result = $stmt->get_result();

        $materias = [];
        while ($row = $result->fetch_assoc()) {
            $materias[] = $row;
        }

        return $materias;
    }
}
