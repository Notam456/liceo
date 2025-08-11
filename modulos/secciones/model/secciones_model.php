<?php
class SeccionesModel {
    private $conn;

    public function __construct() {
        include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
        $this->conn = $conn;
    }

    public function getAll() {
        $query = "SELECT * FROM seccion";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    public function getHorario($id_seccion) {
        $query = "SELECT * FROM horario WHERE id_seccion = " . $id_seccion;
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    public function getById($id) {
        $query = "SELECT * FROM seccion WHERE id_seccion = '$id'";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    public function create($data) {
        $nombre = $data['año'] . "°" . $data['nombre'];
        $año = $data['año'];

        $insert_query = "INSERT INTO
        seccion(nombre, año)
        VALUES ('$nombre', '$año')";

        try {
            $result = mysqli_query($this->conn, $insert_query);
            return $result;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function update($id, $data) {
        $nombre = $data['añoEdit'] . "°" . $data['nombreEdit'];
        $año = $data['añoEdit'];

        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $año = mysqli_real_escape_string($this->conn, $año);

        $update_query = "UPDATE seccion SET
            nombre = '$nombre',
            año = '$año'
            WHERE id_seccion = $id";

        try {
            $result = mysqli_query($this->conn, $update_query);
            return $result;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function delete($id) {
        $delete_query = "DELETE FROM seccion WHERE id_seccion ='$id'";
        $result = mysqli_query($this->conn, $delete_query);
        return $result;
    }
}
?>
