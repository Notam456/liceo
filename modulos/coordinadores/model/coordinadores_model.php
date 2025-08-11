<?php
class CoordinadoresModel {
    private $conn;

    public function __construct() {
        include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
        $this->conn = $conn;
    }

    public function getAll() {
        $query = "SELECT * FROM coordinadores";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    public function getById($id) {
        $query = "SELECT * FROM coordinadores WHERE id_coordinadores = '$id'";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    public function create($data) {
        $nombre_coordinadores = $data['nombre_coordinadores'];
        $apellido_coordinadores = $data['apellido_coordinadores'];
        $cedula_coordinadores = $data['cedula_coordinadores'];
        $contacto_coordinadores = $data['contacto_coordinadores'];
        $area_coordinacion = $data['area_coordinacion'];
        $seccion_coordinadores = $data['seccion_coordinadores'];

        $insert_query = "INSERT INTO
        coordinadores(nombre_coordinadores, apellido_coordinadores, cedula_coordinadores, contacto_coordinadores, area_coordinacion, seccion_coordinadores)
        VALUES ('$nombre_coordinadores', '$apellido_coordinadores', '$cedula_coordinadores', '$contacto_coordinadores', '$area_coordinacion', '$seccion_coordinadores')";

        $result = mysqli_query($this->conn, $insert_query);
        return $result;
    }

    public function update($id, $data) {
        $nombre_coordinadores = mysqli_real_escape_string($this->conn, $data['nombre_coordinadores']);
        $apellido_coordinadores = mysqli_real_escape_string($this->conn, $data['apellido_coordinadores']);
        $cedula_coordinadores = mysqli_real_escape_string($this->conn, $data['cedula_coordinadores']);
        $contacto_coordinadores = mysqli_real_escape_string($this->conn, $data['contacto_coordinadores']);
        $area_coordinacion = mysqli_real_escape_string($this->conn, $data['area_coordinacion']);
        $seccion_coordinadores = mysqli_real_escape_string($this->conn, $data['seccion_coordinadores']);

        $update_query = "UPDATE coordinadores SET
            nombre_coordinadores = '$nombre_coordinadores',
            apellido_coordinadores = '$apellido_coordinadores',
            cedula_coordinadores = '$cedula_coordinadores',
            contacto_coordinadores = '$contacto_coordinadores',
            area_coordinacion = '$area_coordinacion',
            seccion_coordinadores = '$seccion_coordinadores'
            WHERE id_coordinadores = $id";

        $result = mysqli_query($this->conn, $update_query);
        return $result;
    }

    public function delete($id) {
        $delete_query = "DELETE FROM coordinadores WHERE id_coordinadores ='$id'";
        $result = mysqli_query($this->conn, $delete_query);
        return $result;
    }
}
?>
