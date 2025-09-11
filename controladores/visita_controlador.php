<?php
session_start();
date_default_timezone_set('America/Caracas');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/visita_modelo.php');

$visitaModelo = new VisitaModelo($conn);
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'crear':
        if (isset($_POST['id_estudiante_visita']) && isset($_POST['fecha_visita'])) {
            $resultado = $visitaModelo->crearVisita(
                $_POST['id_estudiante_visita'],
                $_POST['fecha_visita']
            );
            if ($resultado) {
                $_SESSION['status'] = "Visita agendada correctamente";
            } else {
                $_SESSION['status'] = "Error al agendar la visita. El estudiante no tiene inasistencias registradas.";
            }
            header('Location: /liceo/controladores/visita_controlador.php');
            exit();
        }
        break;

    case 'ver':
        if (isset($_POST['id_visita'])) {
            $id = $_POST['id_visita'];
            $resultado = $visitaModelo->obtenerVisitaPorId($id);
            if ($resultado && mysqli_num_rows($resultado) > 0) {
                $row = mysqli_fetch_array($resultado);
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/visita_ver_modal.php');
            } else {
                echo "No se encontraron datos para la visita con el ID proporcionado.";
            }
        }
        break;

    case 'editar':
        if (isset($_POST['id_visita'])) {
            $id = $_POST['id_visita'];
            $resultado = $visitaModelo->obtenerVisitaPorId($id);
            $data = [];
            while($row = mysqli_fetch_assoc($resultado)) {
                $data[] = $row;
            }
            header('Content-Type: application/json');
            echo json_encode($data);
        }
        break;

    case 'actualizar_estado':
        if (isset($_POST['id_visita']) && isset($_POST['estado'])) {
            $resultado = $visitaModelo->actualizarEstadoVisita(
                $_POST['id_visita'],
                $_POST['estado']
            );
            echo $resultado ? "Estado actualizado correctamente" : "No se pudo actualizar el estado";
        }
        break;

    case 'eliminar':
        if (isset($_POST['id_visita'])) {
            $id = $_POST['id_visita'];
            $resultado = $visitaModelo->eliminarVisita($id);
            echo $resultado ? "Visita eliminada correctamente" : "No se pudo eliminar la visita";
        }
        break;

    case 'listar':
    default:
        $visitas = $visitaModelo->obtenerVisitas();
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/visita_vista.php');
        break;
}
?>
