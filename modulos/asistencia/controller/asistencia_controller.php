<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modulos/asistencia/model/asistencia_model.php');

class AsistenciaController {
    private $model;

    public function __construct() {
        $this->model = new AsistenciaModel();
    }

    public function index() {
        $asistencias = $this->model->getAll();
        $secciones = $this->model->getSecciones();
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modulos/asistencia/view/asistencia_view.php');
    }

    public function guardarAsistencia() {
        if(empty($_POST['fecha']) || empty($_POST['seccion'])) {
            $_SESSION['status'] = "Fecha y sección son requeridas";
        } elseif(isset($_POST['asistencia']) && is_array($_POST['asistencia'])) {
            $this->model->create($_POST);
            $_SESSION['status'] = "Asistencia registrada correctamente";
        } else {
            $_SESSION['status'] = "No se seleccionaron estudiantes";
        }
        header("Location: index.php");
        exit();
    }

    public function obtenerEstudiantes() {
        $result = $this->model->getEstudiantesPorSeccion($_POST['seccion']);
        if(mysqli_num_rows($result) > 0) {
            echo '<table class="table">';
            echo '<thead><tr><th>Estudiante</th><th>Estado</th><th>Justificación</th></tr></thead>';
            echo '<tbody>';

            while($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>'.htmlspecialchars($row['nombre_estudiante']).' '.htmlspecialchars($row['apellido_estudiante']).'</td>';
                echo '<td>';
                echo '<div class="form-check">';
                echo '<input class="form-check-input" type="radio" name="asistencia['.$row['id_estudiante'].'][estado]" value="P" checked> Presente<br>';
                echo '<input class="form-check-input" type="radio" name="asistencia['.$row['id_estudiante'].'][estado]" value="A"> Ausente<br>';
                echo '<input class="form-check-input justificado-radio" type="radio" name="asistencia['.$row['id_estudiante'].'][estado]" value="J"> Justificado';
                echo '</div>';
                echo '</td>';
                echo '<td>';
                echo '<textarea class="form-control justificado-note" name="asistencia['.$row['id_estudiante'].'][justificacion]" rows="2" style="display: none;"></textarea>';
                echo '</td>';
                echo '</tr>';
            }

            echo '</tbody></table>';
        } else {
            echo '<p class="text-muted">No hay estudiantes en esta sección</p>';
        }
    }

    public function filtrarAsistencia() {
        $result = $this->model->getFilteredAsistencia($_POST['seccion'], $_POST['fecha']);
        if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $estado = '';
                switch($row['estado']) {
                    case 'P': $estado = 'Presente'; break;
                    case 'A': $estado = 'Ausente'; break;
                    case 'J': $estado = 'Justificado'; break;
                }

                echo '<tr>';
                echo '<td style="display: none;">'.$row['id_asistencia'].'</td>';
                echo '<td>'.date('d/m/Y', strtotime($row['fecha'])).'</td>';
                echo '<td>'.$row['seccion_estudiante'].'</td>';
                echo '<td>'.$row['nombre_estudiante'].' '.$row['apellido_estudiante'].'</td>';
                echo '<td>'.$estado.'</td>';
                echo '<td>'.($row['justificacion'] ?: 'N/A').'</td>';
                echo '<td>';
                echo '<a href="#" class="btn btn-primary btn-sm edit-asistencia">Modificar</a> ';
                echo '<input type="hidden" class="delete_id_asistencia" value="'.$row['id_asistencia'].'">';
                echo '<a href="#" class="btn btn-danger btn-sm delete-asistencia">Eliminar</a>';
                echo '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="7">No hay registros de asistencia con estos filtros</td></tr>';
        }
    }

    public function obtenerAsistencia() {
        $result = $this->model->getAsistenciaById($_POST['id_asistencia']);
        if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            echo json_encode($row);
        }
    }

    public function actualizarAsistencia() {
        $this->model->update($_POST);
        $_SESSION['status'] = "Asistencia actualizada correctamente";
        header("Location: index.php");
        exit();
    }

    public function eliminarAsistencia() {
        if($this->model->delete($_POST['id_asistencia'])) {
            echo "Registro eliminado correctamente";
        } else {
            echo "Error al eliminar el registro";
        }
    }

    public function handleRequest() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';

        if (isset($_POST['guardar_asistencia'])) {
            $action = 'guardar_asistencia';
        } elseif (isset($_POST['obtener_estudiantes'])) {
            $action = 'obtener_estudiantes';
        } elseif (isset($_POST['filtrar_asistencia'])) {
            $action = 'filtrar_asistencia';
        } elseif (isset($_POST['obtener_asistencia'])) {
            $action = 'obtener_asistencia';
        } elseif (isset($_POST['actualizar_asistencia'])) {
            $action = 'actualizar_asistencia';
        } elseif (isset($_POST['eliminar_asistencia'])) {
            $action = 'eliminar_asistencia';
        }

        switch ($action) {
            case 'guardar_asistencia':
                $this->guardarAsistencia();
                break;
            case 'obtener_estudiantes':
                $this->obtenerEstudiantes();
                break;
            case 'filtrar_asistencia':
                $this->filtrarAsistencia();
                break;
            case 'obtener_asistencia':
                $this->obtenerAsistencia();
                break;
            case 'actualizar_asistencia':
                $this->actualizarAsistencia();
                break;
            case 'eliminar_asistencia':
                $this->eliminarAsistencia();
                break;
            default:
                $this->index();
                break;
        }
    }
}
?>
