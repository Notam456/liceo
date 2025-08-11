<?php
class UsuariosModel {
    private $conn;

    public function __construct() {
        include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
        $this->conn = $conn;
    }

    public function getAll() {
        $query = "SELECT * FROM usuario";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    public function getById($id) {
        $query = "SELECT * FROM usuario WHERE id = '$id'";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    public function create($data) {
        $usuario = $data['usuario'];
        $contrasena = $data['contrasena'];
        $rol = $data['rol'];

        $insert_query = "INSERT INTO
        usuario(usuario, contrasena, rol)
        VALUES ('$usuario', '$contrasena', '$rol')";

        $result = mysqli_query($this->conn, $insert_query);
        return $result;
    }

    public function update($id, $data) {
        $usuario = mysqli_real_escape_string($this->conn, $data['usuario']);
        $contrasena = mysqli_real_escape_string($this->conn, $data['contrasena']);
        $rol = mysqli_real_escape_string($this->conn, $data['rol']);

        $update_query = "UPDATE usuario SET
            usuario = '$usuario',
            contrasena = '$contrasena',
            rol = '$rol'
            WHERE id = $id";

        $result = mysqli_query($this->conn, $update_query);
        return $result;
    }

    public function delete($id) {
        $delete_query = "DELETE FROM usuario WHERE id ='$id'";
        $result = mysqli_query($this->conn, $delete_query);
        return $result;
    }
}
?>
