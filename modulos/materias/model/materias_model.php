<?php
class MateriasModel {
    private $conn;

    public function __construct() {
        include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
        $this->conn = $conn;
    }

    public function getAll() {
        $query = "SELECT * FROM materia";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    public function getById($id) {
        $query = "SELECT * FROM materia WHERE id_materia = '$id'";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    public function create($data) {
        $nombre_materia = $data['nombre_materia'];
        $info_materia = $data['info_materia'];

        $insert_query = "INSERT INTO
        materia(nombre_materia, info_materia)
        VALUES ('$nombre_materia', '$info_materia')";

        $result = mysqli_query($this->conn, $insert_query);
        return $result;
    }

    public function update($id, $data) {
        $nombre_materia = mysqli_real_escape_string($this->conn, $data['nombre_materia_edit']);
        $info_materia = mysqli_real_escape_string($this->conn, $data['info_materia_edit']);

        $update_query = "UPDATE materia SET
            nombre_materia = '$nombre_materia',
            info_materia = '$info_materia'
            WHERE id_materia = $id";

        $result = mysqli_query($this->conn, $update_query);
        return $result;
    }

    public function delete($id) {
        $delete_query = "DELETE FROM materia WHERE id_materia ='$id'";
        $result = mysqli_query($this->conn, $delete_query);
        return $result;
    }
}
?>
