<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/materia_modelo.php');

$materiaModelo = new MateriaModelo($conn);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'crear':
        if (isset($_POST['save_data'])) {
            $resultado = $materiaModelo->crearMateria($_POST['nombre_materia'], $_POST['info_materia']);
            if ($resultado === true) {
                $_SESSION['status'] = "Materia creada correctamente";
            } elseif ($resultado === 1062) {
                $_SESSION['status'] = "La materia ya existe. Intente agregar otra.";
            } else {
                $_SESSION['status'] = "Ocurrió un error inesperado.";
            }
            header('Location: /liceo/controladores/materia_controlador.php');
            exit();
        }
        break;

    case 'ver':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $materiaModelo->obtenerMateriaPorId($id);
            if (mysqli_num_rows($resultado) > 0) {
                $row = mysqli_fetch_array($resultado);
                
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/materia_modal_view.php');
            } else {

                $row = [];
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/materia_modal_view.php');
            }
        }
        break;

    case 'editar':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $materiaModelo->obtenerMateriaPorId($id);
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
            $nombre = $_POST['nombre_materia_edit'];
            $info = $_POST['info_materia_edit'];
            $resultado = $materiaModelo->actualizarMateria($id, $nombre, $info);
            $_SESSION['status'] = $resultado ? "Datos actualizados correctamente" : "No se pudieron actualizar los datos";
            header('Location: /liceo/controladores/materia_controlador.php');
            exit();
        }
        break;

    case 'eliminar':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $materiaModelo->eliminarMateria($id);
            echo $resultado ? "Datos eliminados correctamente" : "Los datos no se han podido eliminar";
        }
        break;

    case 'listar':
    default:
        $materias = $materiaModelo->obtenerTodasLasMaterias();
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/materia_vista.php');
        break;
}
?>
