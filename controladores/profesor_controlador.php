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
                $_POST['nombre_profesor'],
                $_POST['apellido_profesor'],
                $_POST['cedula_profesor'],
                $_POST['contacto_profesor'],
                $_POST['id_materia'],
                $_POST['id_seccion']
            );
            $_SESSION['status'] = $resultado ? "Profesor creado correctamente" : "Error al crear el profesor";
            header('Location: /liceo/controladores/profesor_controlador.php');
            exit();
        }
        break;

    case 'ver':
        if (isset($_POST['id_profesor'])) {
            $id = $_POST['id_profesor'];
            $resultado = $profesorModelo->obtenerProfesorPorId($id);
            if ($row = mysqli_fetch_array($resultado)) {
                echo '<h6> Id primaria: '. $row['id_profesor'] .'</h6>
                      <h6> Nombres: '. $row['nombre_profesor'] .'</h6>
                      <h6> Apellidos: '. $row['apellido_profesor'] .'</h6>
                      <h6> C.I: '. $row['cedula_profesor'] .'</h6>
                      <h6> Contacto: '. $row['contacto_profesor'] .'</h6>
                      <h6> Materia: '. $row['nombre_materia'] .'</h6>
                      <h6> Sección: '. $row['nombre'] .'</h6>';
            } else {
                echo '<h4>No se han encontrado datos</h4>';
            }
        }
        break;

    case 'editar':
        if (isset($_POST['id_profesor'])) {
            $id = $_POST['id_profesor'];
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
                $_POST['id_profesor'],
                $_POST['nombre_profesor'],
                $_POST['apellido_profesor'],
                $_POST['cedula_profesor'],
                $_POST['contacto_profesor'],
                $_POST['id_materia'],
                $_POST['id_seccion']
            );
            $_SESSION['status'] = $resultado ? "Datos actualizados correctamente" : "No se pudieron actualizar los datos";
            header('Location: /liceo/controladores/profesor_controlador.php');
            exit();
        }
        break;

    case 'eliminar':
        if (isset($_POST['id_profesor'])) {
            $id = $_POST['id_profesor'];
            $resultado = $profesorModelo->eliminarProfesor($id);
            echo $resultado ? "Datos eliminados correctamente" : "Los datos no se han podido eliminar";
        }
        break;

    case 'listar':
    default:
        $profesores = $profesorModelo->obtenerTodosLosProfesores();
        $materias = $profesorModelo->obtenerMaterias();
        $secciones = $profesorModelo->obtenerSecciones();
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/profesor_vista.php');
        break;
}
?>
