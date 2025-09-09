<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/asigna_cargo_modelo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/profesor_modelo.php');

$AsignaCargoModelo = new AsignaCargoModelo($conn);
$profesorModelo = new ProfesorModelo($conn);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'crear':
        if (isset($_POST['save_data'])) {
            $resultado = $AsignaCargoModelo->crearAsignaCargo($_POST['id_profesor'], $_POST['id_cargo']);
            $_SESSION['status'] = $resultado ? "Asignación creada correctamente" : "Error al crear la Asignación";
            header('Location: /liceo/controladores/asigna_cargo_controlador.php');
            exit();
        }
        break;

    case 'ver':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $AsignaCargoModelo->obtenerAsignaCargoPorIdConNombres($id);
            if (mysqli_num_rows($resultado) > 0) {
                $row = mysqli_fetch_array($resultado);
                
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/asigna_cargo_modal_view.php');
            } else {

                $row = [];
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/asigna_cargo_modal_view.php');
            }
        }
        break; 

    case 'editar':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $AsignaCargoModelo->obtenerAsignaCargoPorId($id);
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
            $id_profesor = $_POST['id_profesor_edit'];
            $id_cargo = $_POST['id_cargo_edit'];
            $resultado = $AsignaCargoModelo->actualizarAsignaCargo($id, $id_profesor, $id_cargo);
            $_SESSION['status'] = $resultado ? "Datos actualizados correctamente" : "No se pudieron actualizar los datos";
            header('Location: /liceo/controladores/asigna_cargo_controlador.php');
            exit();
        }
        break;

    case 'eliminar':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $AsignaCargoModelo->eliminarAsignaCargo($id);
            echo $resultado ? "Datos eliminados correctamente" : "Los datos no se han podido eliminar";
        }
        break;

    case 'listar':
    default:
        $asigna_cargos = $AsignaCargoModelo->obtenerTodasLasAsignacionesConNombres();
        $profesores = $profesorModelo->obtenerTodosLosProfesores();
        $cargos = $AsignaCargoModelo->obtenerCargos();
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/asigna_cargo_vista.php');
        break;
}
?>
