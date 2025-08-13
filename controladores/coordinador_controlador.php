<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/coordinador_modelo.php');

$coordinadorModelo = new CoordinadorModelo($conn);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'crear':
        if (isset($_POST['save_data'])) {
            $resultado = $coordinadorModelo->crearCoordinador(
                $_POST['nombre_coordinadores'],
                $_POST['apellido_coordinadores'],
                $_POST['cedula_coordinadores'],
                $_POST['contacto_coordinadores'],
                $_POST['area_coordinacion'],
                $_POST['seccion_coordinadores']
            );
            $_SESSION['status'] = $resultado ? "Coordinador creado correctamente" : "Error al crear el coordinador";
            header('Location: /liceo/controladores/coordinador_controlador.php');
            exit();
        }
        break;

    case 'ver':
        if (isset($_POST['id_coordinadores'])) {
            $id = $_POST['id_coordinadores'];
            $resultado = $coordinadorModelo->obtenerCoordinadorPorId($id);
            if ($row = mysqli_fetch_array($resultado)) {
                echo '<h6> Id primaria: '. $row['id_coordinadores'] .'</h6>
                      <h6> Nombres: '. $row['nombre_coordinadores'] .'</h6>
                      <h6> Apellidos: '. $row['apellido_coordinadores'] .'</h6>
                      <h6> C.I: '. $row['cedula_coordinadores'] .'</h6>
                      <h6> Contacto: '. $row['contacto_coordinadores'] .'</h6>
                      <h6> Área de Coordinación: '. $row['area_coordinacion'] .'</h6>
                      <h6> Sección Coordinada: '. $row['seccion_coordinadores'] .'</h6>';
            } else {
                echo '<h4>No se han encontrado datos</h4>';
            }
        }
        break;

    case 'editar':
        if (isset($_POST['id_coordinadores'])) {
            $id = $_POST['id_coordinadores'];
            $resultado = $coordinadorModelo->obtenerCoordinadorPorId($id);
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
            $resultado = $coordinadorModelo->actualizarCoordinador(
                $_POST['id_coordinadores'],
                $_POST['nombre_coordinadores'],
                $_POST['apellido_coordinadores'],
                $_POST['cedula_coordinadores'],
                $_POST['contacto_coordinadores'],
                $_POST['area_coordinacion'],
                $_POST['seccion_coordinadores']
            );
            $_SESSION['status'] = $resultado ? "Datos actualizados correctamente" : "No se pudieron actualizar los datos";
            header('Location: /liceo/controladores/coordinador_controlador.php');
            exit();
        }
        break;

    case 'eliminar':
        if (isset($_POST['id_coordinadores'])) {
            $id = $_POST['id_coordinadores'];
            $resultado = $coordinadorModelo->eliminarCoordinador($id);
            echo $resultado ? "Datos eliminados correctamente" : "Los datos no se han podido eliminar";
        }
        break;

    case 'listar':
    default:
        $coordinadores = $coordinadorModelo->obtenerTodosLosCoordinadores();
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/coordinador_vista.php');
        break;
}
?>
