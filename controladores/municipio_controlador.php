<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/municipio_modelo.php');

$municipioModelo = new MunicipioModelo($conn);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'crear':
        if (isset($_POST['save_data'])) {
            $resultado = $municipioModelo->crearMunicipio($_POST['municipio'], isset($_POST['id_estado']) ? $_POST['id_estado'] : null);
            $_SESSION['status'] = $resultado ? "Municipio creado correctamente" : "Error al crear el municipio";
            header('Location: /liceo/controladores/municipio_controlador.php');
            exit();
        }
        break;

    case 'ver':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $municipioModelo->obtenerMunicipioPorId($id);
            if (mysqli_num_rows($resultado) > 0) {
                $row = mysqli_fetch_array($resultado);
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/municipio_modal_view.php');
            } else {
                $row = [];
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/municipio_modal_view.php');
            }
        }
        break;

    case 'editar':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $municipioModelo->obtenerMunicipioPorId($id);
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
            $municipio = $_POST['municipio_edit'];
            $id_estado = isset($_POST['id_estado_edit']) ? $_POST['id_estado_edit'] : null;
            $resultado = $municipioModelo->actualizarMunicipio($id, $municipio, $id_estado);
            $_SESSION['status'] = $resultado ? "Datos actualizados correctamente" : "No se pudieron actualizar los datos";
            header('Location: /liceo/controladores/municipio_controlador.php');
            exit();
        }
        break;

    case 'eliminar':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $municipioModelo->eliminarMunicipio($id);
            echo $resultado ? "Datos eliminados correctamente" : "Los datos no se han podido eliminar";
        }
        break;

    case 'listar':
    default:
        $municipios = $municipioModelo->obtenerTodosLosMunicipios();
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/municipio_vista.php');
        break;
}
?>
