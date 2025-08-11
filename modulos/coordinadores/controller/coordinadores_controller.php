<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modulos/coordinadores/model/coordinadores_model.php');

class CoordinadoresController {
    private $model;

    public function __construct() {
        $this->model = new CoordinadoresModel();
    }

    public function index() {
        $coordinadores = $this->model->getAll();
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modulos/coordinadores/view/coordinadores_view.php');
    }

    public function create() {
        if ($this->model->create($_POST)) {
            $_SESSION['status'] = "Datos de coordinador ingresados correctamente";
        } else {
            $_SESSION['status'] = "Datos de coordinador ingresados incorrectamente, vuelva a intentar";
        }
        header('location: index.php');
    }

    public function view() {
        $result = $this->model->getById($_POST['id_coordinadores']);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                echo '
                    <h6> Id primaria: '. $row['id_coordinadores'] .'</h6>
                    <h6> Nombres: '. $row['nombre_coordinadores'] .'</h6>
                    <h6> Apellidos: '. $row['apellido_coordinadores'] .'</h6>
                    <h6> C.I: '. $row['cedula_coordinadores'] .'</h6>
                    <h6> Contacto: '. $row['contacto_coordinadores'] .'</h6>
                    <h6> Área de Coordinación: '. $row['area_coordinacion'] .'</h6>
                    <h6> Sección Coordinada: '. $row['seccion_coordinadores'] .'</h6>
                ';
            }
        } else {
            echo '<h4>No se han encontrado datos del coordinador</h4>';
        }
    }

    public function edit() {
        $result = $this->model->getById($_POST['id_coordinadores']);
        $array_result = [];
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                array_push($array_result, $row);
            }
            header('content-type: application/json');
            echo json_encode($array_result);
        } else {
            echo '<h4>No se han encontrado datos del coordinador</h4>';
        }
    }

    public function update() {
        if ($this->model->update($_POST['id_coordinadores'], $_POST)) {
            $_SESSION['status'] = "Datos del coordinador actualizados correctamente";
        } else {
            $_SESSION['status'] = "Los datos del coordinador no se pudieron actualizar";
        }
        header('location: index.php');
    }

    public function delete() {
        if ($this->model->delete($_POST['id_coordinadores'])) {
            echo "Datos del coordinador eliminados correctamente";
        } else {
            echo "Los datos del coordinador no se han podido eliminar";
        }
    }

    public function handleRequest() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';

        if (isset($_POST['save_data'])) {
            $action = 'create';
        } elseif (isset($_POST['click-view-btn'])) {
            $action = 'view';
        } elseif (isset($_POST['click-edit-btn'])) {
            $action = 'edit';
        } elseif (isset($_POST['update-data'])) {
            $action = 'update';
        } elseif (isset($_POST['click-delete-btn'])) {
            $action = 'delete';
        }

        switch ($action) {
            case 'create':
                $this->create();
                break;
            case 'view':
                $this->view();
                break;
            case 'edit':
                $this->edit();
                break;
            case 'update':
                $this->update();
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
