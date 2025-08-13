<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/profesor_modelo.php');

$profesorModelo = new ProfesorModelo($conn);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'crear':
        if (isset($_POST['save_data'])) {
            $resultado = $profesorModelo->crearProfesor(
                $_POST['nombre_profesores'],
                $_POST['apellido_profesores'],
                $_POST['cedula_profesores'],
                $_POST['contacto_profesores'],
                $_POST['materia_impartida'],
                $_POST['seccion_profesores']
            );
            $_SESSION['status'] = $resultado ? "Profesor creado correctamente" : "Error al crear el profesor";
            header('Location: /liceo/controladores/profesor_controlador.php');
            exit();
        }
        break;

    case 'ver':
        if (isset($_POST['id_profesores'])) {
            $id = $_POST['id_profesores'];
            $resultado = $profesorModelo->obtenerProfesorPorId($id);
            if ($row = mysqli_fetch_array($resultado)) {
                echo '<h6> Id primaria: '. $row['id_profesores'] .'</h6>
                      <h6> Nombres: '. $row['nombre_profesores'] .'</h6>
                      <h6> Apellidos: '. $row['apellido_profesores'] .'</h6>
                      <h6> C.I: '. $row['cedula_profesores'] .'</h6>
                      <h6> Contacto: '. $row['contacto_profesores'] .'</h6>
                      <h6> Materia: '. $row['materia_impartida'] .'</h6>
                      <h6> Sección: '. $row['seccion_profesores'] .'</h6>';
            } else {
                echo '<h4>No se han encontrado datos</h4>';
            }
        }
        break;

    case 'editar':
        if (isset($_POST['id_profesores'])) {
            $id = $_POST['id_profesores'];
            $resultado = $profesorModelo->obtenerProfesorPorId($id);
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
            $resultado = $profesorModelo->actualizarProfesor(
                $_POST['id_profesores'],
                $_POST['nombre_profesores'],
                $_POST['apellido_profesores'],
                $_POST['cedula_profesores'],
                $_POST['contacto_profesores'],
                $_POST['materia_impartida'],
                $_POST['seccion_profesores']
            );
            $_SESSION['status'] = $resultado ? "Datos actualizados correctamente" : "No se pudieron actualizar los datos";
            header('Location: /liceo/controladores/profesor_controlador.php');
            exit();
        }
        break;

    case 'eliminar':
        if (isset($_POST['id_profesores'])) {
            $id = $_POST['id_profesores'];
            $resultado = $profesorModelo->eliminarProfesor($id);
            echo $resultado ? "Datos eliminados correctamente" : "Los datos no se han podido eliminar";
        }
        break;

    case 'listar':
    default:
        $profesores = $profesorModelo->obtenerTodosLosProfesores();
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/profesor_vista.php');
        break;
}
?>
