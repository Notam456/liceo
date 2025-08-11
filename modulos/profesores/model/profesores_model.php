<?php
class ProfesoresModel {
    private $conn;

    public function __construct() {
        include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
        $this->conn = $conn;
    }

    public function getAll() {
        $query = "SELECT * FROM profesores";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    public function getById($id) {
        $query = "SELECT * FROM profesores WHERE id_profesores = '$id'";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    public function create($data) {
        $nombre_profesores = $data['nombre_profesores'];
        $apellido_profesores = $data['apellido_profesores'];
        $cedula_profesores = $data['cedula_profesores'];
        $contacto_profesores = $data['contacto_profesores'];
        $materia_impartida = $data['materia_impartida'];
        $seccion_profesores = $data['seccion_profesores'];

        $insert_query = "INSERT INTO
        profesores(nombre_profesores, apellido_profesores, cedula_profesores, contacto_profesores, materia_impartida, seccion_profesores)
        VALUES ('$nombre_profesores', '$apellido_profesores', '$cedula_profesores', '$contacto_profesores', '$materia_impartida', '$seccion_profesores')";

        $result = mysqli_query($this->conn, $insert_query);
        return $result;
    }

    public function update($id, $data) {
        $nombre_profesores = mysqli_real_escape_string($this->conn, $data['nombre_profesores']);
        $apellido_profesores = mysqli_real_escape_string($this->conn, $data['apellido_profesores']);
        $cedula_profesores = mysqli_real_escape_string($this->conn, $data['cedula_profesores']);
        $contacto_profesores = mysqli_real_escape_string($this->conn, $data['contacto_profesores']);
        $materia_impartida = mysqli_real_escape_string($this->conn, $data['materia_impartida']);
        $seccion_profesores = mysqli_real_escape_string($this->conn, $data['seccion_profesores']);

        $update_query = "UPDATE profesores SET
            nombre_profesores = '$nombre_profesores',
            apellido_profesores = '$apellido_profesores',
            cedula_profesores = '$cedula_profesores',
            contacto_profesores = '$contacto_profesores',
            materia_impartida = '$materia_impartida',
            seccion_profesores = '$seccion_profesores'
            WHERE id_profesores = $id";

        $result = mysqli_query($this->conn, $update_query);
        return $result;
    }

    public function delete($id) {
        $delete_query = "DELETE FROM profesores WHERE id_profesores ='$id'";
        $result = mysqli_query($this->conn, $delete_query);
        return $result;
    }
}
?>
