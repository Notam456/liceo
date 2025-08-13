<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/anio_academico_modelo.php');

$anioAcademicoModelo = new AnioAcademicoModelo($conn);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'crear':
        if (isset($_POST['save_data'])) {
            $anio = $_POST['anio'];
            $anio_academico = $_POST['anio_academico'];
            $resultado = $anioAcademicoModelo->crearAnioAcademico($anio, $anio_academico);
            $_SESSION['status'] = $resultado ? "Año académico creado correctamente" : "Error al crear el año académico";
            header('Location: /liceo/controladores/anio_academico_controlador.php');
            exit();
        }
        break;

    case 'ver':
        if (isset($_POST['id_anio'])) {
            $id = $_POST['id_anio'];
            $resultado = $anioAcademicoModelo->obtenerAnioAcademicoPorId($id);
            if (mysqli_num_rows($resultado) > 0) {
                $row = mysqli_fetch_array($resultado);
                echo '<h6> Id primaria: '. $row['id_anio'] .'</h6>
                      <h6> Año: '. $row['anio'] .'</h6>
                      <h6> Año academico: '. $row['anio_academico'] .'</h6>';
            } else {
                echo '<h4>No se han encontrado datos</h4>';
            }
        }
        break;

    case 'editar':
        if (isset($_POST['id_anio'])) {
            $id = $_POST['id_anio'];
            $resultado = $anioAcademicoModelo->obtenerAnioAcademicoPorId($id);
            $data = [];
            if (mysqli_num_rows($resultado) > 0) {
                while($row = mysqli_fetch_assoc($resultado)) {
                    $data[] = $row;
                }
            }
            header('Content-Type: application/json');
            echo json_encode($data);
        }
        break;

    case 'actualizar':
        if (isset($_POST['update-data'])) {
            $id = $_POST['id_anio'];
            $anio = $_POST['anio'];
            $anio_academico = $_POST['anio_academico'];
            $resultado = $anioAcademicoModelo->actualizarAnioAcademico($id, $anio, $anio_academico);
            $_SESSION['status'] = $resultado ? "Datos actualizados correctamente" : "No se pudieron actualizar los datos";
            header('Location: /liceo/controladores/anio_academico_controlador.php');
            exit();
        }
        break;

    case 'eliminar':
        if (isset($_POST['id_anio'])) {
            $id = $_POST['id_anio'];
            $resultado = $anioAcademicoModelo->eliminarAnioAcademico($id);
            if ($resultado) {
                echo "Datos eliminados correctamente";
            } else {
                echo "Los datos no se han podido eliminar";
            }
        }
        break;

    case 'listar':
    default:
        $anios_academicos = $anioAcademicoModelo->obtenerTodosLosAniosAcademicos();
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/anio_academico_vista.php');
        break;
}
?>
