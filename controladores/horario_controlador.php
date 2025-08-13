<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/horario_modelo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/materia_modelo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/profesor_modelo.php');

$horarioModelo = new HorarioModelo($conn);
$materiaModelo = new MateriaModelo($conn);
$profesorModelo = new ProfesorModelo($conn);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'mostrar';

switch ($action) {
    case 'guardar':
        if (isset($_POST['seccion'])) {
            $id_seccion = $_POST['seccion'];
            $dia = $_POST['dia'];
            $inicio = $_POST['inicio'];
            $fin = $_POST['fin'];
            $id_materia = $_POST['materia'];
            $id_profesor = $_POST['profesor'];

            $resultado = $horarioModelo->guardarBloqueHorario($id_seccion, $dia, $inicio, $fin, $id_materia, $id_profesor);
            echo $resultado ? "Guardado con ID: " . $resultado : "Error al guardar";
        }
        exit();

    case 'eliminar':
        if (isset($_POST['seccion'])) {
            $id_seccion = $_POST['seccion'];
            $dia = $_POST['dia'];
            $hora = $_POST['hora'];

            $resultado = $horarioModelo->eliminarBloqueHorario($id_seccion, $dia, $hora);
            echo $resultado ? "OK" : "Error al eliminar";
        }
        exit();

    case 'mostrar':
    default:
        if (isset($_GET['secc'])) {
            $seccion_id = $_GET['secc'];

            // Data for the view
            $horario_result = $horarioModelo->getHorarioBySeccion($seccion_id);
            $horario_existente = [];
            while ($row = mysqli_fetch_assoc($horario_result)) {
                $horario_existente[] = $row;
            }

            $materias_result = $materiaModelo->obtenerTodasLasMaterias();
            $materias = [];
            while ($row = mysqli_fetch_assoc($materias_result)) {
                $materias[] = $row;
            }

            $profesores_result = $profesorModelo->obtenerTodosLosProfesores();
            $profesores = [];
            while ($row = mysqli_fetch_assoc($profesores_result)) {
                $profesores[] = $row;
            }

            $horas = [
                "7:20am - 8:10am", "8:10am - 8:50am", "8:50am - 9:05am", "9:05am - 9:45am",
                "9:45am - 10:25am", "10:25am - 10:30am", "10:30am - 11:45am", "11:45am - 12:10am",
                "12:10am - 12:50am", "12:50am - 1:30am"
            ];
            $dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];

            include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/horario_vista.php');
        } else {
            $_SESSION['status'] = "Por favor, seleccione una sección.";
            header('Location: /liceo/controladores/seccion_controlador.php');
            exit();
        }
        break;
}
?>
