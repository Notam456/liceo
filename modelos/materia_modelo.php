<?php

class MateriaModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    private function buscarPorNombre($nombre)
    {
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $query = "SELECT * FROM materia WHERE nombre = '$nombre'";
        $result = mysqli_query($this->conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        return null;
    }

    public function crearMateria($nombre, $info) {
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $info = mysqli_real_escape_string($this->conn, $info);

        $materiaExistente = $this->buscarPorNombre($nombre);

        if ($materiaExistente) {
            if ($materiaExistente['visibilidad'] == 0) {
                $id_materia = $materiaExistente['id_materia'];
                $query = "UPDATE materia SET descripcion = '$info', visibilidad = TRUE WHERE id_materia = $id_materia";
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

        $query = "INSERT INTO materia(nombre, descripcion) VALUES ('$nombre', '$info')";
        try {
            mysqli_query($this->conn, $query);
            return true;
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                return 1062;
            }
            return false;
        }
    }

    public function obtenerMateriaPorId($id) {
        $id = (int)$id;
        $query = "SELECT * FROM materia WHERE id_materia = '$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerTodasLasMaterias() {
        $query = "SELECT * FROM materia WHERE visibilidad = TRUE";
        return mysqli_query($this->conn, $query);
    }

    public function actualizarMateria($id, $nombre, $info) {
        $id = (int)$id;
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $info = mysqli_real_escape_string($this->conn, $info);

        $materiaExistente = $this->buscarPorNombre($nombre);
        if ($materiaExistente && $materiaExistente['id_materia'] != $id) {
            return 1062;
        }

        $query = "UPDATE materia SET nombre = '$nombre', descripcion = '$info' WHERE id_materia = $id";

        try {
            return mysqli_query($this->conn, $query);
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                return 1062;
            }
            return false;
        }
    }

    public function eliminarMateria($id) {
        $id = (int)$id;
        $query = "UPDATE materia SET visibilidad = FALSE WHERE id_materia ='$id'";
        return mysqli_query($this->conn, $query);
    }
}
?>
