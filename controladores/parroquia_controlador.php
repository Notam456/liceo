<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/parroquia_modelo.php');

$parroquiaModelo = new ParroquiaModelo($conn);


$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'crear':
        if (isset($_POST['save_data'])) {
            $resultado = $parroquiaModelo->crearParroquia($_POST['parroquia'], $_POST['id_municipio']);
            $_SESSION['status'] = $resultado ? "Parroquia creada correctamente" : "Error al crear la parroquia";
            header('Location: /liceo/controladores/parroquia_controlador.php');
            exit();
        }
        break;

    case 'ver':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $parroquiaModelo->obtenerParroquiaPorId($id);
            if (mysqli_num_rows($resultado) > 0) {
                $row = mysqli_fetch_array($resultado);
                
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/parroquia_modal_view.php');
            } else {

                $row = [];
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/parroquia_modal_view.php');
            }
        }
        break; 
        
    case 'editar':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $parroquiaModelo->obtenerParroquiaPorId($id);
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
            $municipio = $_POST['id_municipio_edit'];
            $resultado = $parroquiaModelo->actualizarParroquia($id, $parroquia, $municipio);
            $_SESSION['status'] = $resultado ? "Datos actualizados correctamente" : "No se pudieron actualizar los datos";
            header('Location: /liceo/controladores/parroquia_controlador.php');
            exit();
        }
        break;

    case 'eliminar':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $parroquiaModelo->eliminarParroquia($id);
            echo $resultado ? "Datos eliminados correctamente" : "Los datos no se han podido eliminar";
        }
        break;

    case 'listar':
    default:
        $materias = $parroquiaModelo->obtenerTodasLasParroquias();
        $municipios = mysqli_query($conn, "SELECT id_municipio, municipio FROM municipio ORDER BY municipio");
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/parroquia_vista.php');
        break;
}
?>
