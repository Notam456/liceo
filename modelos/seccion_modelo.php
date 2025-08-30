<?php

class SeccionModelo {
    private $conn;
      static $letras = [
        'A','B','C','D','E','F','G','H','I','J','K','L','M',
        'N','O','P','Q','R','S','T','U','V','W','X','Y','Z'
    ];
    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearSeccion($letra, $id_grado) {
        $letra = mysqli_real_escape_string($this->conn, $letra);
        $id_grado = mysqli_real_escape_string($this->conn, $id_grado);

        $query = "INSERT INTO seccion(letra, id_grado) VALUES ('$letra', $id_grado)";

        try {
            return mysqli_query($this->conn, $query);
        } catch (Exception $e) {
            return false;
        }
    }

    public function obtenerSeccionPorId($id) {
        $id = (int)$id;
        $query = "SELECT s.*, g.numero_anio FROM seccion AS s JOIN grado AS g ON s.id_grado = g.id_grado WHERE s.id_seccion = '$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerHorarioPorSeccion($id_seccion){
        $id_seccion = (int)$id_seccion;
        $query = "SELECT * FROM horario WHERE id_seccion = " . $id_seccion;
        return mysqli_query($this->conn, $query);
    }

    public function obtenerTodasLasSecciones() {
        $query = "SELECT s.*, g.numero_anio FROM seccion AS s JOIN grado AS g ON s.id_grado = g.id_grado";
        return mysqli_query($this->conn, $query);
    }

    public function actualizarSeccion($id, $letra, $id_grado) {
        $id = (int)$id;
        $letra = mysqli_real_escape_string($this->conn, $letra);
        $id_grado = mysqli_real_escape_string($this->conn, $id_grado);

        $query = "UPDATE seccion SET letra = '$letra', id_grado = $id_grado WHERE id_seccion = $id";

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

    public function generarSecciones($cantidad, $id_grado) {
          for ($i = 0; $i <= $cantidad; $i++) {
            $this->crearSeccion(self::$letras[$i], $id_grado);
        }
        return "success";
    }
}
?>
