<?php

class ProfesorModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearProfesor($nombre, $apellido, $cedula) {
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $apellido = mysqli_real_escape_string($this->conn, $apellido);
        $cedula = mysqli_real_escape_string($this->conn, $cedula);


        $query = "INSERT INTO profesor(nombre, apellido, cedula)
                  VALUES ('$nombre', '$apellido', '$cedula')";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerProfesorPorId($id) {
        $id = (int)$id;
        $query = "SELECT * FROM profesor WHERE id_profesor = '$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerTodosLosProfesores() {
        $query = "SELECT * FROM profesor";
        return mysqli_query($this->conn, $query);
    }

    public function actualizarProfesor($id, $nombre, $apellido, $cedula) {
        $id = (int)$id;
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $apellido = mysqli_real_escape_string($this->conn, $apellido);
        $cedula = mysqli_real_escape_string($this->conn, $cedula);


        $query = "UPDATE profesor SET
                    nombre_profesor = '$nombre',
                    apellido_profesor = '$apellido',
                    cedula_profesor = '$cedula'
                  WHERE id_profesor = $id";
        return mysqli_query($this->conn, $query);
    }

    public function eliminarProfesor($id) {
        $id = (int)$id;
        $query = "DELETE FROM profesor WHERE id_profesor ='$id'";
        return mysqli_query($this->conn, $query);
    }


}
?>
