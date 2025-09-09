<?php

class AsignaCargoModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearAsignaCargo($id_profesor, $id_cargo) {
        $id_profesor = mysqli_real_escape_string($this->conn, $id_profesor);
        $id_cargo = mysqli_real_escape_string($this->conn, $id_cargo);

        $query = "INSERT INTO asigna_cargo(id_profesor, id_cargo) VALUES ('$id_profesor', '$id_cargo')";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerAsignaCargoPorIdConNombres($id) {
        $id = (int)$id;
        $query = "SELECT ac.*, p.nombre, p.apellido, c.nombre
                  FROM asigna_cargo ac
                  JOIN profesor p ON ac.id_profesor = p.id_profesor
                  JOIN cargo c ON ac.id_cargo = c.id_cargo
                  WHERE ac.id_asig = '$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerTodasLasAsignacionesConNombres() {
        $query = "SELECT ac.*, p.nombre, p.apellido, c.nombre
                  FROM asigna_cargo ac
                  JOIN profesor p ON ac.id_profesor = p.id_profesor
                  JOIN cargo c ON ac.id_cargo = c.id_cargo";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerCargos() {
        $query = "SELECT id_cargo, nombre FROM cargo ORDER BY nombre ASC";
        return mysqli_query($this->conn, $query);
    }

    // jose yajure, obtenerAsigCargoPorId($id_profesor) SI NO PUES ELIMINAR BOTON CONSULTAR
    public function obtenerAsignaCargoPorId($id) {
        $id = (int)$id;
        $query = "SELECT * FROM asigna_cargo WHERE id_asig = '$id'";
        return mysqli_query($this->conn, $query);
    }

    // jose yajure, AGREGAR ID_PROFESOR EN CASO DE NECESITAR IDENTIFICADOR
    public function actualizarAsignaCargo($id, $id_profesor, $id_cargo) {
        $id = (int)$id;
        $id_profesor = mysqli_real_escape_string($this->conn, $id_profesor);
        $id_cargo = mysqli_real_escape_string($this->conn, $id_cargo);

        $query = "UPDATE asigna_cargo SET id_profesor = '$id_profesor', id_cargo = '$id_cargo' WHERE id_asig = $id";
        return mysqli_query($this->conn, $query);
    }

    public function eliminarAsignaCargo($id) {
        $id = (int)$id;
        $query = "DELETE FROM asigna_cargo WHERE id_asig ='$id'";
        return mysqli_query($this->conn, $query);
    }
}
?>
