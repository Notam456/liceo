<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/anio_academico_modelo.php');

$anioAcademicoModelo = new AnioAcademicoModelo($conn);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'crear':
        if (isset($_POST['save_data'])) {
            $desde = $_POST['inicio'];
            $hasta = $_POST['fin'];
            $resultado = $anioAcademicoModelo->crearAnioAcademico($desde, $hasta);
            $_SESSION['status'] = $resultado ? "Año académico creado correctamente" : "Error: Ya existe un año académico que coincide con esas fechas";
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
                
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/anio_modal_view.php');
            } else {

                $row = [];
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/anio_modal_view.php');
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
            $desde = $_POST['inicio'];
            $hasta = $_POST['fin'];
            $resultado = $anioAcademicoModelo->actualizarAnioAcademico($id, $desde, $hasta);
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

    case 'establecerActivo':
        if (isset($_POST['id_anio'])){
            $id = $_POST['id_anio'];
            $resultado = $anioAcademicoModelo->establecerAnioActivo($id);
              $_SESSION['status'] = $resultado ? "Año activo actualizado correctamente" : "No se pudo actualizar el año activo";
        }

    case 'listar':
    default:
        $anios_academicos = $anioAcademicoModelo->obtenerTodosLosAniosAcademicos();
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/anio_academico_vista.php');
        break;
}
?>

