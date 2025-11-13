<?php

class GradoModelo
{

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function buscarPorNumero($numero, $id_anio)
    {
        $numero = mysqli_real_escape_string($this->conn, $numero);
        $id_anio = mysqli_real_escape_string($this->conn, $id_anio);
        $query = "SELECT * FROM grado WHERE numero_anio = '$numero' AND id_anio = '$id_anio'";
        $result = mysqli_query($this->conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        return null;
    }

    public function generarGrados($cantidad, $id_anio)
    {
        $max_grados = 6; // límite total
        $id_anio = (int)$id_anio;

        // Obtener todos los números existentes (visibles u ocultos)
        $query = "SELECT numero_anio, visibilidad FROM grado WHERE id_anio = $id_anio";
        $result = mysqli_query($this->conn, $query);

        $existentes = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $existentes[(int)$row['numero_anio']] = (int)$row['visibilidad'];
        }

        $creados = 0;

        for ($i = 1; $i <= $max_grados && $creados < $cantidad; $i++) {
            if (!isset($existentes[$i])) {
                // No existe → crear nuevo
                $this->crearGrado($i, $id_anio);
                $creados++;
            } elseif ($existentes[$i] == 0) {
                // Existe pero está oculto → reactivar
                $gradoExistente = $this->buscarPorNumero($i, $id_anio);
                $id_grado = $gradoExistente['id_grado'];
                mysqli_query($this->conn, "UPDATE grado SET visibilidad = 1 WHERE id_grado = $id_grado");
                $creados++;
            }
        }

        return $creados > 0 ? "success" : "muchos";
    }
    public function crearGrado($numero_anio, $id_anio)
    {
        $numero_anio = mysqli_real_escape_string($this->conn, $numero_anio);
        $id_anio = mysqli_real_escape_string($this->conn, $id_anio);

        $gradoExistente = $this->buscarPorNumero($numero_anio, $id_anio);

        if ($gradoExistente) {
            if ($gradoExistente['visibilidad'] == 0) {
                $id_grado = $gradoExistente['id_grado'];
                $query = "UPDATE grado SET numero_anio = '$numero_anio', visibilidad = TRUE WHERE id_grado = $id_grado";
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
        $query = "INSERT INTO grado(numero_anio, id_anio) VALUES ('$numero_anio', '$id_anio')";
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            return mysqli_query($this->conn, $query);
        } catch (mysqli_sql_exception $e) {
            throw $e;
        }
    }

    public function obtenerGradoPorId($id)
    {
        $id = (int)$id;
        switch ($_SESSION['tipo_cargo']) {
            case 'Administrador':
            case 'directivo':
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
            case 'directivo':
                $query = "SELECT g.* FROM grado g JOIN anio_academico a ON g.id_anio = a.id_anio WHERE a.estado = 1 AND g.visibilidad = TRUE";
                break;
            case 'inferior':
                $query = "SELECT g.* FROM grado g JOIN anio_academico a ON g.id_anio = a.id_anio WHERE a.estado = 1 AND numero_anio < 4 AND g.visibilidad = TRUE";
                break;
            case 'superior':
                $query = "SELECT g.* FROM grado g JOIN anio_academico a ON g.id_anio = a.id_anio WHERE a.estado = 1 AND numero_anio > 3 AND g.visibilidad = TRUE";
                break;
        }
        return mysqli_query($this->conn, $query);
    }

    public function actualizarGrado($id, $numero_anio)
    {
        $id = (int)$id;
        $numero_anio = (int)$numero_anio; // número, no texto
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            // Buscar si existe un grado con el mismo número pero visibilidad 0
            $query_buscar = "SELECT id_grado, numero_anio 
                         FROM grado 
                         WHERE numero_anio = $numero_anio 
                           AND visibilidad = 0
                           AND id_anio = (
                                SELECT id_anio 
                                FROM anio_academico 
                                WHERE estado = 1
                                LIMIT 1
                             ) 
                         LIMIT 1";
            $result = mysqli_query($this->conn, $query_buscar);

            if ($result && mysqli_num_rows($result) > 0) {
                $grado_oculto = mysqli_fetch_assoc($result);
                $id_oculto = (int)$grado_oculto['id_grado'];

                // Obtener el numero_anio actual del grado visible
                $query_actual = "SELECT numero_anio FROM grado WHERE id_grado = $id LIMIT 1";
                $res_actual = mysqli_query($this->conn, $query_actual);
                $grado_actual = mysqli_fetch_assoc($res_actual);
                $numero_actual = (int)$grado_actual['numero_anio'];

                // Intercambio seguro con valor temporal
                mysqli_begin_transaction($this->conn);

                // Paso 1: asignar un valor temporal al grado oculto (que no colisione)
                $temp = -1; // puede ser cualquier número que no exista en la tabla
                mysqli_query($this->conn, "UPDATE grado SET numero_anio = $temp WHERE id_grado = $id_oculto");

                // Paso 2: actualizar el visible con el número del oculto
                mysqli_query($this->conn, "UPDATE grado SET numero_anio = $numero_anio WHERE id_grado = $id");

                // Paso 3: actualizar el oculto con el número original del visible
                mysqli_query($this->conn, "UPDATE grado SET numero_anio = $numero_actual WHERE id_grado = $id_oculto");

                mysqli_commit($this->conn);
                return true;
            } else {
                // Si no hay grado con visibilidad 0, actualizar normalmente
                $query_update = "UPDATE grado SET numero_anio = $numero_anio WHERE id_grado = $id";
                return mysqli_query($this->conn, $query_update);
            }
        } catch (mysqli_sql_exception $e) {
            mysqli_rollback($this->conn);
            throw $e;
        }
    }

    public function eliminarGrado($id)
    {
        $id = (int)$id;
        $query = "UPDATE grado SET visibilidad = FALSE WHERE id_grado ='$id'";
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            return mysqli_query($this->conn, $query);
        } catch (mysqli_sql_exception $e) {
            throw $e;
        }
    }
}
