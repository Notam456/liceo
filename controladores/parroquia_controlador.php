<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/parroquia_modelo.php');

$parroquiaModelo = new ParroquiaModelo($conn);


$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'crear':
        if (isset($_POST['save_data'])) {
            try {
                $resultado = $parroquiaModelo->crearParroquia($_POST['parroquia'], $_POST['id_municipio']);
                if ($resultado) {
                    $_SESSION['status'] = "Parroquia creada correctamente";
                } else {
                    $_SESSION['error'] = "Error al crear la parroquia";
                }
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                    $_SESSION['error'] = "Ya existe una parroquia con ese nombre en el municipio seleccionado";
                } else {
                    $_SESSION['error'] = "Error al crear la parroquia: " . $e->getMessage();
                }
                $_SESSION['form_data'] = $_POST;
            }
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
            try {
                $id = $_POST['idEdit'];
                $resultado = $parroquiaModelo->actualizarParroquia($id, $_POST['parroquia_edit'], $_POST['id_municipio_edit']);
                if ($resultado) {
                    $_SESSION['status'] = "Parroquia actualizada correctamente";
                } else {
                    $_SESSION['error'] = "Error al actualizar la parroquia";
                }
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                    $_SESSION['error'] = "Ya existe una parroquia con ese nombre en el municipio seleccionado";
                } else {
                    $_SESSION['error'] = "Error al actualizar la parroquia: " . $e->getMessage();
                }
            }
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
