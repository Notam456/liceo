<?php

class UsuarioModelo
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function crearUsuario($usuario, $contrasena, $rol, $profesor)
    {
        $usuario = mysqli_real_escape_string($this->conn, $usuario);
        $contrasena = mysqli_real_escape_string($this->conn, $contrasena);
        $rol = mysqli_real_escape_string($this->conn, $rol);

        if (empty($profesor)) {
            $profesor = "NULL";
        } else {
            $profesor = (int)$profesor;
        }

        $insert_query = "INSERT INTO usuario(usuario, contrasena, rol, id_profesor) 
                     VALUES ('$usuario', '$contrasena', '$rol', $profesor)";

        try {
            $insert_query_run = mysqli_query($this->conn, $insert_query);
            return true; // éxito
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                return 1062; // clave duplicada
            }
            return false; // otro error
        }
    }

    public function obtenerUsuarioPorId($id)
    {
        $id = (int)$id;
        $fetch_query = "
        SELECT u.*, p.nombre, p.apellido
        FROM usuario u
        LEFT JOIN profesor p ON u.id_profesor = p.id_profesor
        WHERE u.id_usuario = '$id'
    ";
        $fetch_query_run = mysqli_query($this->conn, $fetch_query);
        return $fetch_query_run;
    }

    public function obtenerTodosLosUsuarios()
    {
        $query = "SELECT * FROM usuario WHERE visibilidad = TRUE";
        $query_run = mysqli_query($this->conn, $query);
        return $query_run;
    }

    public function actualizarUsuario($id, $usuario, $contrasena, $rol, $profesor)
    {
        $id = (int)$id;
        $usuario = mysqli_real_escape_string($this->conn, $usuario);
        $contrasena = mysqli_real_escape_string($this->conn, $contrasena);
        $rol = mysqli_real_escape_string($this->conn, $rol);

        if (empty($profesor)) {
            $profesor = "NULL";
        } else {
            $profesor = (int)$profesor; // aseguramos que sea un número válido
        }
        $update_query = "UPDATE usuario SET usuario = '$usuario', contrasena = '$contrasena', rol = '$rol', id_profesor = $profesor WHERE `id_usuario` = $id";

        try {
            $update_query_run = mysqli_query($this->conn, $update_query);
            return $update_query_run;
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                return 1062; // clave duplicada
            }
            return false; // otro error
        }
    }

    public function eliminarUsuario($id)
    {
        $id = (int)$id;
        $delete_query = "UPDATE usuario SET visibilidad = FALSE WHERE id_usuario ='$id'";
        $delete_query_run = mysqli_query($this->conn, $delete_query);
        return $delete_query_run;
    }
}
