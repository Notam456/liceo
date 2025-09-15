<?php

class GradoModelo
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function generarGrados($cantidad, $id)
    {
        $cantidad = (int)$cantidad;
        for ($i = 1; $i <= $cantidad; $i++) {
            $this->crearGrado($i, $id);
        }
        return "success";
    }
    public function crearGrado($numero_anio, $id_anio)
    {
        $numero_anio = mysqli_real_escape_string($this->conn, $numero_anio);
        $id_anio = mysqli_real_escape_string($this->conn, $id_anio);

        $query = "INSERT INTO grado(numero_anio, id_anio) VALUES ('$numero_anio', '$id_anio')";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerGradoPorId($id)
    {
        $id = (int)$id;
        switch ($_SESSION['tipo_cargo']) {
            case 'Administrador':
                $query = "SELECT g.*,  CONCAT(YEAR(a.desde), '-', YEAR(a.hasta)) AS periodo FROM grado g JOIN anio_academico a ON g.id_anio = a.id_anio WHERE id_grado = '$id'";
                break;
            case 'inferior':
                $query = "SELECT g.*,  CONCAT(YEAR(a.desde), '-', YEAR(a.hasta)) AS periodo FROM grado g JOIN anio_academico a ON g.id_anio = a.id_anio WHERE id_grado = '$id' AND numero_anio < 4";
                break;
            case 'superior':
                $query = "SELECT g.*,  CONCAT(YEAR(a.desde), '-', YEAR(a.hasta)) AS periodo FROM grado g JOIN anio_academico a ON g.id_anio = a.id_anio WHERE id_grado = '$id' AND numero_anio > 3";
                break;
        }
        return mysqli_query($this->conn, $query);
    }

    public function obtenerTodosLosGrados()
    {
        switch ($_SESSION['tipo_cargo']) {
            case 'Administrador':
                $query = "SELECT g.* FROM grado g JOIN anio_academico a ON g.id_anio = a.id_anio WHERE a.estado = 1";
                break;
            case 'inferior':
                $query = "SELECT g.* FROM grado g JOIN anio_academico a ON g.id_anio = a.id_anio WHERE a.estado = 1 AND numero_anio < 4";
                break;
            case 'superior':
                $query = "SELECT g.* FROM grado g JOIN anio_academico a ON g.id_anio = a.id_anio WHERE a.estado = 1 AND numero_anio > 3";
                break;
        }
        return mysqli_query($this->conn, $query);
    }

    public function actualizarGrado($id, $numero_anio)
    {
        $id = (int)$id;
        $numero_anio = mysqli_real_escape_string($this->conn, $numero_anio);

        $query = "UPDATE grado SET numero_anio = '$numero_anio' WHERE id_grado = $id";
        return mysqli_query($this->conn, $query);
    }

    public function eliminarGrado($id)
    {
        $id = (int)$id;
        $query = "DELETE FROM grado WHERE id_grado ='$id'";
        return mysqli_query($this->conn, $query);
    }
}
