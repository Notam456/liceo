<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/grado_modelo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/anio_academico_modelo.php');

$gradoModelo = new gradoModelo($conn);
$anioModelo = new AnioAcademicoModelo($conn);


$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'crear':
        if (isset($_POST['save_data'])) {
            $anioActivo = mysqli_fetch_array($anioModelo->obtenerAnioActivo());
            $resultado = $gradoModelo->generarGrados($_POST['cantidad'], $anioActivo['id_anio']);
            $_SESSION['status'] = $resultado ? "Grados generados correctamente" : "Error al crear los grados";
            header('Location: /liceo/controladores/grado_controlador.php');
            exit();
        }
        break;

    case 'ver':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $gradoModelo->obtenerGradoPorId($id);
            if ($row = mysqli_fetch_array($resultado)) {
                echo '<h6> Id primaria: ' . $row['id_parroquia'] . '</h6>
                      <h6> Nombre de la parroquia: ' . $row['parroquia'] . '</h6>
                      <h6> Municipio: ' . $row['municipio'] . '</h6>';
            } else {
                echo '<h4>No se han encontrado datos</h4>';
            }
        }
        break;

    case 'editar':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $gradoModelo->obtenerGradoPorId($id);
            $data = [];
            while($row = mysqli_fetch_assoc($resultado)) {
                $data[] = $row;
            }
            header('Content-Type: application/json');
            echo json_encode($data);
        }
        break;

    case 'actualizar':
        if (isset($_POST['update-data'])) {
            $id = $_POST['idEdit'];
            $parroquia = $_POST['parroquia_edit'];
            $municipio = $_POST['municipio_edit'];
            $resultado = $parroquiaModelo->actualizarParroquia($id, $parroquia, $municipio);
            $_SESSION['status'] = $resultado ? "Datos actualizados correctamente" : "No se pudieron actualizar los datos";
            header('Location: /liceo/controladores/parroquia_controlador.php');
            exit();
        }
        break;

    case 'eliminar':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $gradoModelo->eliminarGrado($id);
            echo $resultado ? "Datos eliminados correctamente" : "Los datos no se han podido eliminar";
        }
        break;

    case 'listar':
    default:
        $materias = $gradoModelo->obtenerTodosLosGrados();
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/grado_vista.php');
        break;
}
?>
