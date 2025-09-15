<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/seccion_modelo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/grado_modelo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/estudiante_modelo.php');

$seccionModelo = new SeccionModelo($conn);
$gradoModelo = new GradoModelo($conn);
$estudianteModelo = new EstudianteModelo($conn);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'crear':
        if (isset($_POST['save_data'])) {
            $resultado = $seccionModelo->generarSecciones($_POST['cantidad'], $_POST['grado']);
            if ($resultado) {
                $_SESSION['status'] = "Sección creada correctamente";
            } else {
                $_SESSION['status'] = "Esta sección ya existe, vuelva a intentarlo";
            }
            header('Location: /liceo/controladores/seccion_controlador.php');
            exit();
        }
        break;

    case 'ver':
        if (isset($_POST['id_seccion'])) {
            $id = $_POST['id_seccion'];
            $resultado = $seccionModelo->obtenerSeccionPorId($id);
            if ($row = mysqli_fetch_array($resultado)) {
                $estudiantes = $estudianteModelo->obtenerEstudiantesPorSeccion($id);
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/seccion_modal_view.php');
            } else {
                $row = [];
                $estudiantes = false;
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/seccion_modal_view.php');
            }
        }
        break;

    case 'editar':
        if (isset($_POST['id_seccion'])) {
            $id = $_POST['id_seccion'];
            $resultado = $seccionModelo->obtenerSeccionPorId($id);
            $data = [];
            while ($row = mysqli_fetch_assoc($resultado)) {
                $data[] = $row;
            }
            header('Content-Type: application/json');
            echo json_encode($data);
        }
        break;

    case 'actualizar':
        if (isset($_POST['update-data'])) {
            $id = $_POST['idEdit'];
            $nombre = $_POST['nombreEdit'];
            $anio = $_POST['añoEdit'];
            $resultado = $seccionModelo->actualizarSeccion($id, $nombre, $anio);
            if ($resultado) {
                $_SESSION['status'] = "Datos actualizados correctamente";
            } else {
                $_SESSION['status'] = "Esta sección ya existe, vuelva a intentarlo";
            }
            header('Location: /liceo/controladores/seccion_controlador.php');
            exit();
        }
        break;

    case 'eliminar':
        if (isset($_POST['id_seccion'])) {
            $id = $_POST['id_seccion'];
            $resultado = $seccionModelo->eliminarSeccion($id);
            echo $resultado ? "Datos eliminados correctamente" : "Los datos no se han podido eliminar";
        }
        break;

    case 'listar':
    default:
        if ($_SESSION['rol'] == 'user') {
            $secciones = $seccionModelo->obtenerSeccionesPorTutor($_SESSION['profesor']);
        } else {
            $secciones = $seccionModelo->obtenerTodasLasSecciones();
        }
        $horarios_status = [];
        if ($secciones) {
            $secciones_copy = [];
            while ($row = $secciones->fetch_assoc()) {
                $secciones_copy[] = $row;
            }
            foreach ($secciones_copy as $row) {
                
                $horario_result = $seccionModelo->obtenerHorarioPorSeccion($row['id_seccion']);
                $horarios_status[$row['id_seccion']] = (mysqli_num_rows($horario_result) > 0);
            }
        }

        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/seccion_vista.php');
        break;
}
