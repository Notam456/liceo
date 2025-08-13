<?php

class SeccionModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearSeccion($nombre, $anio) {
        $nombre_completo = $anio . "°" . $nombre;
        $nombre_completo = mysqli_real_escape_string($this->conn, $nombre_completo);
        $anio = mysqli_real_escape_string($this->conn, $anio);

        $query = "INSERT INTO seccion(nombre, año) VALUES ('$nombre_completo', '$anio')";

        try {
            return mysqli_query($this->conn, $query);
        } catch (Exception $e) {
            return false;
        }
    }

    public function obtenerSeccionPorId($id) {
        $id = (int)$id;
        $query = "SELECT * FROM seccion WHERE id_seccion = '$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerHorarioPorSeccion($id_seccion){
        $id_seccion = (int)$id_seccion;
        $query = "SELECT * FROM horario WHERE id_seccion = " . $id_seccion;
        return mysqli_query($this->conn, $query);
    }

    public function obtenerTodasLasSecciones() {
        $query = "SELECT * FROM seccion";
        return mysqli_query($this->conn, $query);
    }

    public function actualizarSeccion($id, $nombre, $anio) {
        $id = (int)$id;
        $nombre_completo = $anio . "°" . $nombre;
        $nombre_completo = mysqli_real_escape_string($this->conn, $nombre_completo);
        $anio = mysqli_real_escape_string($this->conn, $anio);

        $query = "UPDATE seccion SET nombre = '$nombre_completo', año = '$anio' WHERE id_seccion = $id";

        try {
            return mysqli_query($this->conn, $query);
        } catch (Exception $e) {
            return false;
        }
    }

    public function eliminarSeccion($id) {
        $id = (int)$id;
        $query = "DELETE FROM seccion WHERE id_seccion ='$id'";
        return mysqli_query($this->conn, $query);
    }
}
?>
