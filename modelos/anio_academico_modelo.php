<?php

class AnioAcademicoModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearAnioAcademico($desde, $hasta) {
        $desde = mysqli_real_escape_string($this->conn, $desde);
        $hasta = mysqli_real_escape_string($this->conn, $hasta);

        $overlap_query = "SELECT COUNT(*) AS count_overlap FROM anio_academico WHERE desde <= '$hasta' AND hasta >= '$desde'";

        $result = mysqli_query($this->conn, $overlap_query);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $count_overlap = (int)$row['count_overlap'];

            if ($count_overlap > 0) {
                return false;
            }
        } else {
            return false;
        }
        $insert_query = "INSERT INTO anio_academico(desde, hasta, estado, visibilidad) VALUES ('$desde', '$hasta', 0, 1)";
        return mysqli_query($this->conn, $insert_query);
    }

    public function obtenerAnioAcademicoPorId($id) {
        $id = (int)$id;
        $fetch_query = "SELECT *,  CONCAT(YEAR(desde), '-', YEAR(hasta)) AS periodo FROM anio_academico WHERE id_anio = '$id'";
        return mysqli_query($this->conn, $fetch_query);
    }

    public function obtenerTodosLosAniosAcademicos() {
        $query = "SELECT *,  CONCAT(YEAR(desde), '-', YEAR(hasta)) AS periodo FROM anio_academico WHERE visibilidad = TRUE";
        return mysqli_query($this->conn, $query);
    }

    public function actualizarAnioAcademico($id, $desde, $hasta) {
        $id = (int)$id;
        $desde = mysqli_real_escape_string($this->conn, $desde);
        $hasta = mysqli_real_escape_string($this->conn, $hasta);

        $update_query = "UPDATE anio_academico SET desde = '$desde', hasta = '$hasta' WHERE id_anio = $id";
        return mysqli_query($this->conn, $update_query);
    }

    public function eliminarAnioAcademico($id) {
        $id = (int)$id;
        $delete_query = "UPDATE anio_academico SET visibilidad = FALSE WHERE id_anio ='$id'";
        return mysqli_query($this->conn, $delete_query);
    }

    public function establecerAnioActivo($id) {
        $id = (int)$id;
        
        // Obtener el año que estaba activo antes para registrar su desactivación
        $query_activo_anterior = "SELECT id_anio FROM anio_academico WHERE estado = 1";
        $resultado_anterior = mysqli_query($this->conn, $query_activo_anterior);
        $anio_anterior = null;
        if ($resultado_anterior && mysqli_num_rows($resultado_anterior) > 0) {
            $row = mysqli_fetch_array($resultado_anterior);
            $anio_anterior = $row['id_anio'];
        }
        
        // Desactivar todos los años
        $query = "UPDATE anio_academico SET estado = 0 WHERE estado = 1";
        mysqli_query($this->conn, $query);
        
        // Activar el año seleccionado
        $set_query = "UPDATE anio_academico SET estado = 1 WHERE id_anio = $id";
        $resultado = mysqli_query($this->conn, $set_query);
        
        return array(
            'resultado' => $resultado,
            'anio_anterior' => $anio_anterior
        );
    }

    public function registrarLogAnio($id_anio, $id_usuario, $accion) {
        $id_anio = (int)$id_anio;
        $id_usuario = (int)$id_usuario;
        $accion = mysqli_real_escape_string($this->conn, $accion);
        
        $insert_query = "INSERT INTO historial_anio (id_anio, id_usuario, accion) VALUES ($id_anio, $id_usuario, '$accion')";
        return mysqli_query($this->conn, $insert_query);
    }

    // Función para obtener todos los logs con información detallada
    public function obtenerHistorialLogs($filtro_usuario = null, $filtro_anio = null, $filtro_accion = null) {
        $query = "SELECT 
                    l.id_log,
                    l.id_anio,
                    l.id_usuario,
                    l.accion,
                    l.fecha,
                    u.usuario as nombre_usuario,
                    CONCAT(YEAR(a.desde), '-', YEAR(a.hasta)) AS periodo_anio
                  FROM historial_anio l
                  INNER JOIN usuario u ON l.id_usuario = u.id_usuario
                  INNER JOIN anio_academico a ON l.id_anio = a.id_anio
                  WHERE 1=1";
        
        // Aplicar filtros si se proporcionan
        if ($filtro_usuario !== null && $filtro_usuario !== '') {
            $filtro_usuario = (int)$filtro_usuario;
            $query .= " AND l.id_usuario = $filtro_usuario";
        }
        
        if ($filtro_anio !== null && $filtro_anio !== '') {
            $filtro_anio = (int)$filtro_anio;
            $query .= " AND l.id_anio = $filtro_anio";
        }
        
        if ($filtro_accion !== null && $filtro_accion !== '') {
            $filtro_accion = mysqli_real_escape_string($this->conn, $filtro_accion);
            $query .= " AND l.accion = '$filtro_accion'";
        }
        
        $query .= " ORDER BY l.fecha DESC";
        
        return mysqli_query($this->conn, $query);
    }

    // Función para obtener usuarios para el filtro
    public function obtenerUsuariosParaFiltro() {
        $query = "SELECT DISTINCT u.id_usuario, u.usuario 
                  FROM usuario u 
                  WHERE u.id_usuario IN (SELECT DISTINCT id_usuario FROM historial_anio)
                  ORDER BY u.usuario";
        return mysqli_query($this->conn, $query);
    }

    // Función para obtener años académicos para el filtro
    public function obtenerAniosParaFiltro() {
        $query = "SELECT DISTINCT a.id_anio, CONCAT(YEAR(a.desde), '-', YEAR(a.hasta)) AS periodo
                  FROM anio_academico a 
                  WHERE a.id_anio IN (SELECT DISTINCT id_anio FROM historial_anio)
                  ORDER BY a.desde DESC";
        return mysqli_query($this->conn, $query);
    }
    public function obtenerAnioActivo(){
        $query = "SELECT * FROM anio_academico WHERE estado = 1 AND visibilidad = TRUE";
        return mysqli_query($this->conn, $query);
    }
}
?>
