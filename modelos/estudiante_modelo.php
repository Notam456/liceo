<?php

class EstudianteModelo
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    private function buscarPorCedula($cedula)
    {
        $cedula = mysqli_real_escape_string($this->conn, $cedula);
        $query = "SELECT * FROM estudiante WHERE cedula = '$cedula'";
        $result = mysqli_query($this->conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        return null;
    }

    public function crearEstudiante($nombre, $apellido, $cedula, $contacto, $id_sector, $anio, $fecha, $direccion_exacta, $punto_referencia)
    {
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $apellido = mysqli_real_escape_string($this->conn, $apellido);
        $cedula = mysqli_real_escape_string($this->conn, $cedula);
        $contacto = mysqli_real_escape_string($this->conn, $contacto);
        $id_sector = mysqli_real_escape_string($this->conn, $id_sector);
        $anio = mysqli_real_escape_string($this->conn, $anio);
        $fecha = mysqli_real_escape_string($this->conn, $fecha);
        $direccion_exacta = mysqli_real_escape_string($this->conn, $direccion_exacta);
        $punto_referencia = mysqli_real_escape_string($this->conn, $punto_referencia);

        $estudianteExistente = $this->buscarPorCedula($cedula);

        if ($estudianteExistente) {
            if ($estudianteExistente['visibilidad'] == 0) {
                $id_estudiante = $estudianteExistente['id_estudiante'];
                $query = "UPDATE estudiante SET
                            nombre = '$nombre',
                            apellido = '$apellido',
                            contacto = '$contacto',
                            id_sector = '$id_sector',
                            id_grado = '$anio',
                            fecha_nacimiento = '$fecha',
                            direccion_exacta = '$direccion_exacta',
                            punto_referencia = '$punto_referencia',
                            visibilidad = TRUE
                          WHERE id_estudiante = '$id_estudiante'";
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

        $query = "INSERT INTO estudiante(nombre, apellido, cedula, contacto, id_sector, id_grado, fecha_nacimiento, direccion_exacta, punto_referencia)
                  VALUES ('$nombre', '$apellido', '$cedula', '$contacto', '$id_sector', '$anio', '$fecha', '$direccion_exacta', '$punto_referencia')";

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

    public function obtenerEstudiantePorId($id)
    {
        $id = (int)$id;
        $query = "SELECT 
    e.*, 
    sec.id_sector, sec.sector, 
    p.id_parroquia, p.parroquia, 
    m.id_municipio, m.municipio,
    s.letra, 
    g.numero_anio AS numero_anio_seccion,
    g_directo.numero_anio,
    s.id_seccion, 
    anio.id_anio
FROM estudiante e
JOIN sector sec ON e.id_sector = sec.id_sector
JOIN parroquia p ON sec.id_parroquia = p.id_parroquia
JOIN municipio m ON p.id_municipio = m.id_municipio
LEFT JOIN (
    SELECT a.* 
    FROM asigna_seccion a
    INNER JOIN anio_academico aa ON a.id_anio = aa.id_anio
    WHERE aa.estado = 1
) AS asig ON e.id_estudiante = asig.id_estudiante
LEFT JOIN anio_academico anio ON asig.id_anio = anio.id_anio
LEFT JOIN seccion s ON asig.id_seccion = s.id_seccion
LEFT JOIN grado g ON s.id_grado = g.id_grado
JOIN grado g_directo ON e.id_grado = g_directo.id_grado
WHERE e.id_estudiante = '$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerEstudiantesPorSeccion($id_seccion)
    {
        $id_seccion = (int)$id_seccion;
        $query = "SELECT e.*
                  FROM estudiante e
                  JOIN asigna_seccion asig ON e.id_estudiante = asig.id_estudiante
                  JOIN anio_academico anio ON asig.id_anio = anio.id_anio AND anio.estado = 1
                  WHERE asig.id_seccion = '$id_seccion' AND e.visibilidad = TRUE AND asig.visibilidad = TRUE
                  ORDER BY e.apellido, e.nombre";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerTodosLosEstudiantes()
    {
        switch ($_SESSION['tipo_cargo']) {
            case 'Administrador':
            case 'directivo':
                $query = "SELECT * FROM estudiante WHERE visibilidad = TRUE";
                break;
            case 'inferior':
                $query = "SELECT e.* FROM estudiante e JOIN grado g ON e.id_grado = g.id_grado WHERE g.numero_anio < 4 AND e.visibilidad = TRUE";
                break;
            case 'superior':
                $query = "SELECT e.* FROM estudiante e JOIN grado g ON e.id_grado = g.id_grado WHERE g.numero_anio > 3 AND e.visibilidad = TRUE";
                break;
        }
        return mysqli_query($this->conn, $query);
    }

    public function actualizarEstudiante($id, $nombre, $apellido, $cedula, $contacto, $id_sector, $anio, $fecha, $direccion_exacta, $punto_referencia)
    {
        $id = (int)$id;
        $nombre = mysqli_real_escape_string($this->conn, $nombre);
        $apellido = mysqli_real_escape_string($this->conn, $apellido);
        $cedula = mysqli_real_escape_string($this->conn, $cedula);
        $contacto = mysqli_real_escape_string($this->conn, $contacto);
        $id_sector = mysqli_real_escape_string($this->conn, $id_sector);
        $anio = mysqli_real_escape_string($this->conn, $anio);
        $fecha = mysqli_real_escape_string($this->conn, $fecha);
        $direccion_exacta = mysqli_real_escape_string($this->conn, $direccion_exacta);
        $punto_referencia = mysqli_real_escape_string($this->conn, $punto_referencia);

        $estudianteExistente = $this->buscarPorCedula($cedula);
        if ($estudianteExistente && $estudianteExistente['id_estudiante'] != $id) {
            return 1062;
        }

        $query = "UPDATE estudiante SET
                    nombre = '$nombre',
                    apellido = '$apellido',
                    cedula = '$cedula',
                    contacto = '$contacto',
                    id_sector = '$id_sector',
                    id_grado = '$anio',
                    fecha_nacimiento = '$fecha',
                    direccion_exacta = '$direccion_exacta',
                    punto_referencia = '$punto_referencia'
                  WHERE id_estudiante = '$id'";

        try {
            $update_query_run = mysqli_query($this->conn, $query);
            return $update_query_run;
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                return 1062;
            }
            return false;
        }
    }

    public function eliminarEstudiante($id)
    {
        $id = (int)$id;
        $query = "UPDATE estudiante SET visibilidad = FALSE WHERE id_estudiante ='$id'";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerEstudiantesSinSeccion($seccion)
    {
        $query_anio = "SELECT id_anio FROM anio_academico WHERE estado = 1 AND visibilidad = TRUE LIMIT 1";
        $result_anio = mysqli_query($this->conn, $query_anio);
        $id_anio_activo = 0;
        if ($result_anio && mysqli_num_rows($result_anio) > 0) {
            $anio_activo = mysqli_fetch_assoc($result_anio);
            $id_anio_activo = $anio_activo['id_anio'];
        }

        if ($id_anio_activo == 0) {
            return mysqli_query($this->conn, "SELECT * FROM estudiante WHERE 1=0");
        }

        $sub_query = "
    SELECT 1
    FROM asigna_seccion
    WHERE id_estudiante = e.id_estudiante
      AND id_anio = $id_anio_activo
";

        switch ($_SESSION['tipo_cargo']) {
            case 'Administrador':
                $query = "
            SELECT e.*
            FROM estudiante e
            WHERE NOT EXISTS ($sub_query)
              AND e.visibilidad = TRUE
              AND e.id_grado = (SELECT id_grado FROM seccion WHERE id_seccion = $seccion)
        ";
                break;

            case 'inferior':
                $query = "
            SELECT e.*
            FROM estudiante e
            JOIN grado g ON e.id_grado = g.id_grado
            WHERE g.numero_anio < 4
              AND NOT EXISTS ($sub_query)
              AND e.visibilidad = TRUE
              AND e.id_grado = (SELECT id_grado FROM seccion WHERE id_seccion = $seccion)
        ";
                break;

            case 'superior':
                $query = "
            SELECT e.*
            FROM estudiante e
            JOIN grado g ON e.id_grado = g.id_grado
            WHERE g.numero_anio > 3
              AND NOT EXISTS ($sub_query)
              AND e.visibilidad = TRUE
              AND e.id_grado = (SELECT id_grado FROM seccion WHERE id_seccion = $seccion)
        ";
                break;

            default:
                return mysqli_query($this->conn, "SELECT * FROM estudiante WHERE 1=0");
        }

        return mysqli_query($this->conn, $query);
    }

    public function asignarSeccion($id_estudiante, $id_seccion)
    {
        $id_estudiante = (int)$id_estudiante;
        $id_seccion = (int)$id_seccion;

        $query_anio = "SELECT id_anio FROM anio_academico WHERE estado = 1 LIMIT 1";
        $result_anio = mysqli_query($this->conn, $query_anio);
        if (!$result_anio || mysqli_num_rows($result_anio) == 0) {
            return false;
        }
        $anio_activo = mysqli_fetch_assoc($result_anio);
        $id_anio_activo = $anio_activo['id_anio'];

        $query = "INSERT INTO asigna_seccion (id_estudiante, id_seccion, id_anio)
                  VALUES ($id_estudiante, $id_seccion, $id_anio_activo)
                  ON DUPLICATE KEY UPDATE id_seccion = VALUES(id_seccion), visibilidad = TRUE";

        return mysqli_query($this->conn, $query);
    }

    public function asignarSeccionMasiva($estudiantes_ids, $id_seccion)
    {
        $id_seccion = (int)$id_seccion;

        $query_anio = "SELECT id_anio FROM anio_academico WHERE estado = 1 LIMIT 1";
        $result_anio = mysqli_query($this->conn, $query_anio);
        if (!$result_anio || mysqli_num_rows($result_anio) == 0) {
            return false;
        }
        $anio_activo = mysqli_fetch_assoc($result_anio);
        $id_anio_activo = $anio_activo['id_anio'];

        $success = true;

        foreach ($estudiantes_ids as $id_estudiante) {
            $id_estudiante = (int)$id_estudiante;
            $query = "INSERT INTO asigna_seccion (id_estudiante, id_seccion, id_anio)
                      VALUES ($id_estudiante, $id_seccion, $id_anio_activo)
                      ON DUPLICATE KEY UPDATE id_seccion = VALUES(id_seccion), visibilidad = TRUE";
            if (!mysqli_query($this->conn, $query)) {
                $success = false;
            }
        }

        return $success;
    }
}
