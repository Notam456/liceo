<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/estudiante_modelo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/profesor_modelo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/seccion_modelo.php');

$estudianteModelo = new EstudianteModelo($conn);
$profesorModelo = new ProfesorModelo($conn);
$seccionModelo = new SeccionModelo($conn);
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'obtener_estudiantes';

switch ($action) {
    case 'obtener_estudiantes':
        if (isset($_POST['id_seccion'])) {
            $id_seccion = $_POST['id_seccion'];
            $estudiantes = $estudianteModelo->obtenerEstudiantesSinSeccion();
            
            if ($estudiantes && mysqli_num_rows($estudiantes) > 0) {
                echo '<div class="mb-3">
                        <label class="form-label">Seleccionar estudiantes para asignar:</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="selectAll">
                            <label class="form-check-label" for="selectAll">
                                <strong>Seleccionar todos</strong>
                            </label>
                        </div>
                      </div>
                      <div style="max-height: 300px; overflow-y: auto; border: 1px solid #dee2e6; padding: 10px; border-radius: 5px;">';
                
                while ($row = mysqli_fetch_array($estudiantes)) {
                    echo '<div class="form-check">
                            <input class="form-check-input estudiante-checkbox" type="checkbox" value="' . $row['id_estudiante'] . '" id="estudiante_' . $row['id_estudiante'] . '">
                            <label class="form-check-label" for="estudiante_' . $row['id_estudiante'] . '">
                                ' . $row['nombre'] . ' ' . $row['apellido'] . ' - C.I: ' . $row['cedula'] . '
                            </label>
                          </div>';
                }
                
                echo '</div>
                      <input type="hidden" id="seccion_asignar" value="' . $id_seccion . '">';
            } else {
                echo '<div class="alert alert-info">No hay estudiantes sin sección asignada.</div>';
            }
        }
        break;
        
    case 'asignar_masiva':
        if (isset($_POST['estudiantes']) && isset($_POST['id_seccion'])) {
            $estudiantes_ids = $_POST['estudiantes'];
            $id_seccion = $_POST['id_seccion'];
            $id_tutor = isset($_POST['id_tutor']) ? $_POST['id_tutor'] : null;
            
            $resultado = $estudianteModelo->asignarSeccionMasiva($estudiantes_ids, $id_seccion);
            
            if ($id_tutor) {
                $seccionModelo->actualizarTutor($id_seccion, $id_tutor);
            }

            if ($resultado) {
                $_SESSION['status'] = "Estudiantes asignados correctamente a la sección";
            } else {
                $_SESSION['status'] = "Error al asignar algunos estudiantes";
            }
            
            echo json_encode(['success' => $resultado]);
        }
        break;

    case 'obtener_profesores':
        $profesores = $profesorModelo->obtenerTodosLosProfesores();
        if ($profesores && mysqli_num_rows($profesores) > 0) {
            echo '<div class="form-group mb-3">
                    <label for="tutor_id">Seleccionar Tutor</label>
                    <select class="form-select" id="tutor_id" name="tutor_id">
                        <option value="">Seleccione un tutor...</option>';
            while ($row = mysqli_fetch_array($profesores)) {
                echo '<option value="' . $row['id_profesor'] . '">' . $row['nombre'] . ' ' . $row['apellido'] . '</option>';
            }
            echo '</select></div>';
        } else {
            echo '<div class="alert alert-info">No hay profesores disponibles.</div>';
        }
        break;
}
?>
