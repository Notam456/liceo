<?php

class CargoModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    private function buscarPorNombre($nombre)
    {
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $query = "SELECT * FROM cargo WHERE nombre = '$nombre'";
        $result = mysqli_query($this->conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        return null;
    }

    public function crearCargo($nombre, $tipo) {
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $tipo = mysqli_real_escape_string($this->conn, $tipo);

        $cargoExistente = $this->buscarPorNombre($nombre);

        if ($cargoExistente) {
            if ($cargoExistente['visibilidad'] == 0) {
                $id_cargo = $cargoExistente['id_cargo'];
                $query = "UPDATE cargo SET tipo = '$tipo', visibilidad = TRUE WHERE id_cargo = $id_cargo";
                try {
                    mysqli_query($this->conn, $query);
                    return true;
                } catch (mysqli_sql_exception $e) {
                    return false;
                }
            } else {
                return 1062;
            }
        }

        $query = "INSERT INTO cargo(nombre, tipo) VALUES ('$nombre', '$tipo')";
        try {
            return mysqli_query($this->conn, $query);
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                return 1062;
            }
            return false;
        }
    }

    public function obtenerCargoPorId($id) {
        $id = (int)$id;
        $query = "SELECT * FROM cargo WHERE id_cargo = '$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerTodosLosCargos() {
        $query = "SELECT * FROM cargo WHERE visibilidad = TRUE";
        return mysqli_query($this->conn, $query);
    }

    public function actualizarCargo($id, $nombre, $tipo) {
        $id = (int)$id;
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $tipo = mysqli_real_escape_string($this->conn, $tipo);

        $cargoExistente = $this->buscarPorNombre($nombre);
        if ($cargoExistente && $cargoExistente['id_cargo'] != $id) {
            return 1062;
        }

        $query = "UPDATE cargo SET nombre = '$nombre', tipo = '$tipo' WHERE id_cargo = $id";

        try {
            return mysqli_query($this->conn, $query);
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                return 1062;
            }
            return false;
        }
    }

    public function eliminarCargo($id) {
        $id = (int)$id;
        $query = "UPDATE cargo SET visibilidad = FALSE WHERE id_cargo ='$id'";
        return mysqli_query($this->conn, $query);
    }
}
?>
