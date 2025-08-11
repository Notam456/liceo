<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modulos/secciones/model/secciones_model.php');

class SeccionesController {
    private $model;

    public function __construct() {
        $this->model = new SeccionesModel();
    }

    public function index() {
        $secciones = $this->model->getAll();
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modulos/secciones/view/secciones_view.php');
    }

    public function create() {
        $result = $this->model->create($_POST);
        if ($result instanceof Exception) {
            if (strpos($result->getMessage(), 'Duplicate entry') !== false) {
                $_SESSION['status'] = "Esta sección ya existe, vuelva a intentarlo";
            } else {
                $_SESSION['status'] = "Datos ingresados incorrectamente, vuelva a intentar";
            }
        } else {
            $_SESSION['status'] = "Datos ingresados correctamente";
        }
        header('location: index.php');
    }

    public function view() {
        $result = $this->model->getById($_POST['id_seccion']);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                echo '
                    <h6> Id primaria: ' . $row['id_seccion'] . '</h6>
                    <h6> Nombre de la sección: ' . $row['nombre'] . '</h6>
                    <h6> Año de la sección: ' . $row['año'] . '</h6>
                    <a
                        name=""
                        id=""
                        class="btn btn-primary"
                        href="#"
                        role="button"
                        >Ver listado de estudiantes</a
                    >
                    <a
                        name=""
                        id=""
                        class="btn btn-primary"
                        href="../horario/construct_horario.php?secc=' .  $row['id_seccion'] . '"
                        role="button"
                        >Crear/modificar Horario</a
                    >
                ';
            }
        } else {
            echo '<h4>no se han encontrado datos</h4>';
        }
    }

    public function edit() {
        $result = $this->model->getById($_POST['id_seccion']);
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
        $result = $this->model->update($_POST['idEdit'], $_POST);
        if ($result instanceof Exception) {
            if (strpos($result->getMessage(), 'Duplicate entry') !== false) {
                $_SESSION['status'] = "Esta sección ya existe, vuelva a intentarlo";
            } else {
                $_SESSION['status'] = "Datos ingresados incorrectamente, vuelva a intentar";
            }
        } else {
            $_SESSION['status'] = "Datos actualizados correctamente";
        }
        header('location: index.php');
    }

    public function delete() {
        if ($this->model->delete($_POST['id_seccion'])) {
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
