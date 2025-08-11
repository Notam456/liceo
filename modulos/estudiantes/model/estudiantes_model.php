<?php
class EstudiantesModel {
    private $conn;

    public function __construct() {
        include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
        $this->conn = $conn;
    }

    public function getAll() {
        $query = "SELECT * FROM estudiante";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    public function getById($id) {
        $query = "SELECT * FROM estudiante WHERE id_estudiante = '$id'";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    public function create($data) {
        $nombre_estudiante = $data['nombre_estudiante'];
        $apellido_estudiante = $data['apellido_estudiante'];
        $cedula_estudiante = $data['cedula_estudiante'];
        $contacto_estudiante = $data['contacto_estudiante'];
        $Municipio = $data['Municipio'];
        $Parroquia = $data['Parroquia'];
        $año_academico = $data['año_academico'];
        $seccion_estudiante = $data['seccion_estudiante'];

        $insert_query = "INSERT INTO
        estudiante(nombre_estudiante, apellido_estudiante, cedula_estudiante, contacto_estudiante, Municipio, Parroquia, año_academico, seccion_estudiante)
        VALUES ('$nombre_estudiante', '$apellido_estudiante', '$cedula_estudiante', '$contacto_estudiante', '$Municipio', '$Parroquia', '$año_academico', '$seccion_estudiante')";

        $result = mysqli_query($this->conn, $insert_query);
        return $result;
    }

    public function update($id, $data) {
        $nombre_estudiante = mysqli_real_escape_string($this->conn, $data['nombre_estudiante']);
        $apellido_estudiante = mysqli_real_escape_string($this->conn, $data['apellido_estudiante']);
        $cedula_estudiante = mysqli_real_escape_string($this->conn, $data['cedula_estudiante']);
        $contacto_estudiante = mysqli_real_escape_string($this->conn, $data['contacto_estudiante']);
        $Municipio = mysqli_real_escape_string($this->conn, $data['Municipio']);
        $Parroquia = mysqli_real_escape_string($this->conn, $data['Parroquia']);
        $año_academico = mysqli_real_escape_string($this->conn, $data['año_academico']);
        $seccion_estudiante = mysqli_real_escape_string($this->conn, $data['seccion_estudiante']);

        $update_query = "UPDATE estudiante SET
            nombre_estudiante = '$nombre_estudiante',
            apellido_estudiante = '$apellido_estudiante',
            cedula_estudiante = '$cedula_estudiante',
            contacto_estudiante = '$contacto_estudiante',
            Municipio= '$Municipio',
            Parroquia = '$Parroquia',
            año_academico = '$año_academico',
            seccion_estudiante = '$seccion_estudiante'
            WHERE id_estudiante = '$id'";

        $result = mysqli_query($this->conn, $update_query);
        return $result;
    }

    public function delete($id) {
        $delete_query = "DELETE FROM estudiante WHERE id_estudiante ='$id'";
        $result = mysqli_query($this->conn, $delete_query);
        return $result;
    }
}
?>
