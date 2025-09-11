<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/cargo_modelo.php');

$cargoModelo = new CargoModelo($conn);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'crear':
        if (isset($_POST['save_data'])) {
            $nombre = $_POST['nombre'];
            $resultado = $cargoModelo->crearCargo($nombre);
            $_SESSION['status'] = $resultado ? "Cargo creado correctamente" : "Error al crear el cargo";
            header('Location: /liceo/controladores/cargo_controlador.php');
            exit();
        }
        break;

    case 'ver':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $cargoModelo->obtenerCargoPorId($id);
            if (mysqli_num_rows($resultado) > 0) {
                $row = mysqli_fetch_array($resultado);
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/cargo_modal_view.php');
            } else {
                $row = [];
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/cargo_modal_view.php');
            }
        }
        break;

    case 'editar':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $cargoModelo->obtenerCargoPorId($id);
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
            $nombre = $_POST['nombre_edit'];
            $resultado = $cargoModelo->actualizarCargo($id, $nombre);
            $_SESSION['status'] = $resultado ? "Datos actualizados correctamente" : "No se pudieron actualizar los datos";
            header('Location: /liceo/controladores/cargo_controlador.php');
            exit();
        }
        break;

    case 'eliminar':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $cargoModelo->eliminarCargo($id);
            echo $resultado ? "Datos eliminados correctamente" : "Los datos no se han podido eliminar";
        }
        break;

    case 'listar':
    default:
        $cargos = $cargoModelo->obtenerTodosLosCargos();
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/cargo_vista.php');
        break;
}
?>
