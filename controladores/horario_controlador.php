<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/horario_modelo.php');

$horarioModelo = new HorarioModelo($conn);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'mostrar';

if ($action == 'guardar') {
    if (isset($_POST['seccion']) && isset($_POST['dia']) && isset($_POST['id_asignacion'])) {
        $id_seccion = $_POST['seccion'];
        $dia = $_POST['dia'];


        $id_asignacion = $_POST['id_asignacion'];

        $resultado = $horarioModelo->guardarBloqueHorario($id_seccion, $dia, $id_asignacion);
        if ($resultado) {
            echo json_encode(['success' => true, 'id_horario' => $resultado, 'dia' => $dia ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar el horario.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    }
    exit();
}

if ($action == 'eliminar') {
    if (isset($_POST['id_horario'])) {
        $id_horario = $_POST['id_horario'];
        $resultado = $horarioModelo->eliminarBloqueHorario($id_horario);
        if ($resultado) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el bloque.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID de horario no proporcionado.']);
    }
    exit();
}

if ($action == 'mostrar') {
    header_remove('Content-Type');

    if (isset($_GET['secc'])) {
        $seccion_id = $_GET['secc'];

        $horario_result = $horarioModelo->getHorarioBySeccion($seccion_id);
        $horario_existente = [];
        while ($row = mysqli_fetch_assoc($horario_result)) {
            $horario_existente[] = $row;
        }

        $asignaciones_result = $horarioModelo->getAsignaciones();
        $asignaciones = [];
        while ($row = mysqli_fetch_assoc($asignaciones_result)) {
            $asignaciones[] = $row;
        }

        $dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];

        // This will load the HTML file which in turn makes the AJAX calls
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/horario_vista.php');
    } else {
        // If no section is selected, redirect back to the sections page
        $_SESSION['status'] = "Por favor, seleccione una sección para ver o editar el horario.";
        header('Location: /liceo/controladores/seccion_controlador.php');
        exit();
    }
}
