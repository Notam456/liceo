<?php

class UsuarioModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearUsuario($usuario, $contrasena, $rol) {
        $usuario = mysqli_real_escape_string($this->conn, $usuario);
        $contrasena = mysqli_real_escape_string($this->conn, $contrasena);
        $rol = mysqli_real_escape_string($this->conn, $rol);

        $insert_query = "INSERT INTO usuario(usuario, contrasena, rol) VALUES ('$usuario', '$contrasena', '$rol')";
        $insert_query_run = mysqli_query($this->conn, $insert_query);

        return $insert_query_run;
    }

    public function obtenerUsuarioPorId($id) {
        $id = (int)$id;
        $fetch_query = "SELECT * FROM usuario WHERE `id` = '$id'";
        $fetch_query_run = mysqli_query($this->conn, $fetch_query);
        return $fetch_query_run;
    }

    public function obtenerTodosLosUsuarios() {
        $query = "SELECT * FROM usuario";
        $query_run = mysqli_query($this->conn, $query);
        return $query_run;
    }

    public function actualizarUsuario($id, $usuario, $contrasena, $rol) {
        $id = (int)$id;
        $usuario = mysqli_real_escape_string($this->conn, $usuario);
        $contrasena = mysqli_real_escape_string($this->conn, $contrasena);
        $rol = mysqli_real_escape_string($this->conn, $rol);

        $update_query = "UPDATE usuario SET usuario = '$usuario', contrasena = '$contrasena', rol = '$rol' WHERE `id` = $id";
        $update_query_run = mysqli_query($this->conn, $update_query);

        return $update_query_run;
    }

    public function eliminarUsuario($id) {
        $id = (int)$id;
        $delete_query = "DELETE FROM usuario WHERE id ='$id'";
        $delete_query_run = mysqli_query($this->conn, $delete_query);
        return $delete_query_run;
    }
}
?>
