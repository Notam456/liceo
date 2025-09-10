<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/usuario_modelo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/profesor_modelo.php');

$usuarioModelo = new UsuarioModelo($conn);
$profesorModelo = new ProfesorModelo($conn);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'crear':
        if (isset($_POST['save_data'])) {
            $usuario = $_POST['usuario'];
            $contrasena = $_POST['contrasena'];
            $rol = $_POST['rol'];

            $profesor = isset($_POST['profesor']) ? $_POST['profesor'] : NULL;
            $resultado = $usuarioModelo->crearUsuario($usuario, $contrasena, $rol, $profesor);

            if ($resultado) {
                $_SESSION['status'] = "Datos ingresados correctamente";
            } else {
                $_SESSION['status'] = "Datos ingresados incorrectamente, vuelva a intentar";
            }
            header('Location: /liceo/controladores/usuario_controlador.php');
            exit();
        }
        break;

    case 'ver':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $usuarioModelo->obtenerUsuarioPorId($id);
            if (mysqli_num_rows($resultado) > 0) {
                $row = mysqli_fetch_array($resultado);
                
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/usuario_modal_view.php');
            } else {

                $row = [];
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/usuario_modal_view.php');
            }
        }
        break;

    case 'editar':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $usuarioModelo->obtenerUsuarioPorId($id);
            $array_result = [];
            if (mysqli_num_rows($resultado) > 0) {
                while ($row = mysqli_fetch_assoc($resultado)) {
                    $array_result[] = $row;
                }
                header('content-type: application/json');
                echo json_encode($array_result[0]); // Devuelve solo el primer usuario como un objeto
            } else {
                echo '<h4>no se han encontrado datos</h4>';
            }
        }
        break;

    case 'actualizar':
        if (isset($_POST['update-data'])) {
            $id = $_POST['id'];
            $usuario = $_POST['usuario'];
            $contrasena = $_POST['contrasena'];
            $rol = $_POST['rol'];
            print_r($_POST);
            $profesor = isset($_POST['profesor']) ? $_POST['profesor'] : NULL;
            $resultado = $usuarioModelo->actualizarUsuario($id, $usuario, $contrasena, $rol, $profesor);

            if ($resultado) {
                $_SESSION['status'] = "Datos actualizados correctamente";
            } else {
                $_SESSION['status'] = "Los datos no se pudieron actualizar";
            }
            header('Location: /liceo/controladores/usuario_controlador.php');
            exit();
        }
        break;

    case 'eliminar':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $usuarioModelo->eliminarUsuario($id);
            if ($resultado) {
                echo "Datos eliminados correctamente";
            } else {
                echo "Los datos no se han podido eliminar";
            }
        }
        break;

    case 'listar':
    default:
        $usuarios = $usuarioModelo->obtenerTodosLosUsuarios();
        $query = $profesorModelo->obtenerTodosLosProfesores();
        $profesores = $query->fetch_all(MYSQLI_ASSOC);
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/usuario_vista.php');
        break;
}

?>
