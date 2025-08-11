<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modulos/horario/model/horario_model.php');

class HorarioController {
    private $model;

    public function __construct() {
        $this->model = new HorarioModel();
    }

    public function index() {
        if (isset($_GET['secc'])) {
            $seccion = $_GET['secc'];
            $horario_existente_result = $this->model->getHorarioBySeccion($seccion);
            $horario_existente = [];
            while ($row = mysqli_fetch_assoc($horario_existente_result)) {
                $horario_existente[] = $row;
            }

            $materias_result = $this->model->getMaterias();
            $materias = [];
            while ($row = mysqli_fetch_assoc($materias_result)) {
                $materias[] = $row;
            }

            $profesores_result = $this->model->getProfesores();
            $profesores = [];
            while ($row = mysqli_fetch_assoc($profesores_result)) {
                $profesores[] = $row;
            }

            $horas = [
                "7:20am - 8:10am", "8:10am - 8:50am", "8:50am - 9:05am", "9:05am - 9:45am", "9:45am - 10:25am",
                "10:25am - 10:30am", "10:30am - 11:45am", "11:45am - 12:10am", "12:10am - 12:50am", "12:50am - 1:30am"
            ];
            $dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];

            include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modulos/horario/view/horario_view.php');
        } else {
            echo '<script language="javascript">';
            echo 'alert("Por favor, seleccione una sección.");';
            echo 'window.location.href = "../secciones/index.php"';
            echo '</script>';
        }
    }

    public function save() {
        $result = $this->model->saveHorarioSlot($_POST);
        if ($result) {
            echo $result;
        } else {
            echo "Error al guardar";
        }
    }

    public function delete() {
        if ($this->model->deleteHorarioSlot($_POST)) {
            echo "OK";
        } else {
            echo "Error al eliminar";
        }
    }

    public function handleRequest() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';

        switch ($action) {
            case 'save':
                $this->save();
                break;
            case 'delete':
                $this->delete();
                break;
            default:
                $this->index();
                break;
        }
    }
}
?>
