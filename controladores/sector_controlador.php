<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/sector_modelo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/parroquia_modelo.php');

$sectorModelo = new SectorModelo($conn);
$parroquiaModelo = new ParroquiaModelo($conn);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'crear':
        if (isset($_POST['save_data'])) {
            try {
                $resultado = $sectorModelo->crearSector($_POST['sector'], isset($_POST['id_parroquia']) ? $_POST['id_parroquia'] : null);
                if ($resultado) {
                    $_SESSION['status'] = "Sector creado correctamente";
                } else {
                    $_SESSION['error'] = "Error al crear el sector";
                }
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                    $_SESSION['error'] = "Ya existe un sector con ese nombre en la parroquia seleccionada";
                } else {
                    $_SESSION['error'] = "Error al crear el sector: " . $e->getMessage();
                }
                $_SESSION['form_data'] = $_POST;
            }
            header('Location: /liceo/controladores/sector_controlador.php');
            exit();
        }
        break;

    case 'ver':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $sectorModelo->obtenerSectorPorId($id);
            if (mysqli_num_rows($resultado) > 0) {
                $row = mysqli_fetch_array($resultado);
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/sector_modal_view.php');
            } else {
                $row = [];
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/sector_modal_view.php');
            }
        }
        break;

    case 'editar':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $sectorModelo->obtenerSectorPorId($id);
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
            try {
                $id = $_POST['idEdit'];
                $sector = $_POST['sector_edit'];
                $id_parroquia = isset($_POST['id_parroquia_edit']) ? $_POST['id_parroquia_edit'] : null;
                $resultado = $sectorModelo->actualizarSector($id, $sector, $id_parroquia);
                if ($resultado) {
                    $_SESSION['status'] = "Sector actualizado correctamente";
                } else {
                    $_SESSION['error'] = "Error al actualizar el sector";
                }
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                    $_SESSION['error'] = "Ya existe un sector con ese nombre en la parroquia seleccionada";
                } else {
                    $_SESSION['error'] = "Error al actualizar el sector: " . $e->getMessage();
                }
            }
            header('Location: /liceo/controladores/sector_controlador.php');
            exit();
        }
        break;

    case 'eliminar':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $sectorModelo->eliminarSector($id);
            echo $resultado ? "Datos eliminados correctamente" : "Los datos no se han podido eliminar";
        }
        break;

    case 'listar':
    default:
        $sectores = $sectorModelo->obtenerTodosLosSectores();
        $parroquias = $parroquiaModelo->obtenerTodasLasParroquias();
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/sector_vista.php');
        break;
}
?>
