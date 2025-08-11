<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modulos/materias/model/materias_model.php');

class MateriasController {
    private $model;

    public function __construct() {
        $this->model = new MateriasModel();
    }

    public function index() {
        $materias = $this->model->getAll();
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modulos/materias/view/materias_view.php');
    }

    public function create() {
        if ($this->model->create($_POST)) {
            $_SESSION['status'] = "Datos ingresados correctamente";
        } else {
            $_SESSION['status'] = "Datos ingresados incorrectamente, vuelva a intentar";
        }
        header('location: index.php');
    }

    public function view() {
        $result = $this->model->getById($_POST['id']);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                echo '
                    <h6> Id primaria: ' . $row['id_materia'] . '</h6>
                    <h6> Nombre de la materia: ' . $row['nombre_materia'] . '</h6>
                    <h6> Descripción: ' . $row['info_materia'] . '</h6>
                ';
            }
        } else {
            echo '<h4>no se han encontrado datos</h4>';
        }
    }

    public function edit() {
        $result = $this->model->getById($_POST['id']);
        $array_result = [];
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                array_push($array_result, $row);
            }
            header('content-type: application/json');
            echo json_encode($array_result);
        } else {
            echo '<h4>no se han encontrado datos</h4>';
        }
    }

    public function update() {
        if ($this->model->update($_POST['idEdit'], $_POST)) {
            $_SESSION['status'] = "Datos actualizados correctamente";
        } else {
            $_SESSION['status'] = "Los datos no se pudieron actualizar";
        }
        header('location: index.php');
    }

    public function delete() {
        if ($this->model->delete($_POST['id'])) {
            echo "Datos eliminados correctamente";
        } else {
            echo "Los datos no se han podido eliminar";
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
