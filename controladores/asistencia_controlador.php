<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/asistencia_modelo.php');

$asistenciaModelo = new AsistenciaModelo($conn);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'registrar':
        if(isset($_POST['guardar_asistencia'])) {
            $fecha = $_POST['fecha'];
            $seccion = $_POST['seccion'];

            if(empty($fecha) || empty($seccion)) {
                $_SESSION['status'] = "Fecha y sección son requeridas";
            } elseif(isset($_POST['asistencia']) && is_array($_POST['asistencia'])) {
                foreach($_POST['asistencia'] as $id_estudiante => $datos) {
                    $estado = $datos['estado'];
                    $justificacion = isset($datos['justificacion']) ? $datos['justificacion'] : '';
                    $asistenciaModelo->registrarAsistencia($id_estudiante, $fecha, $estado, $justificacion);
                }
                $_SESSION['status'] = "Asistencia registrada correctamente";
            } else {
                $_SESSION['status'] = "No se seleccionaron estudiantes";
            }
        }
        header("Location: /liceo/controladores/asistencia_controlador.php");
        exit();

    case 'obtener_estudiantes':
        if(isset($_POST['seccion'])) {
            $seccion = $_POST['seccion'];
            $result = $asistenciaModelo->obtenerEstudiantesPorSeccion($seccion);

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
                    echo '<textarea class="form-control justificado-note" name="asistencia['.$row['id_estudiante'].'][justificacion]" rows="2"></textarea>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
            } else {
                echo '<p class="text-muted">No hay estudiantes en esta sección</p>';
            }
        }
        exit();

    case 'filtrar':
         if(isset($_POST['seccion']) || isset($_POST['fecha'])) {
            $seccion = $_POST['seccion'];
            $fecha = $_POST['fecha'];
            $result = $asistenciaModelo->filtrarAsistencia($seccion, $fecha);

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
        exit();

    case 'obtener_para_editar':
        if(isset($_POST['id_asistencia'])) {
            $id_asistencia = $_POST['id_asistencia'];
            $result = $asistenciaModelo->obtenerAsistenciaPorId($id_asistencia);
            if(mysqli_num_rows($result) > 0) {
                echo json_encode(mysqli_fetch_assoc($result));
            }
        }
        exit();

    case 'actualizar':
        if(isset($_POST['actualizar_asistencia'])) {
            $id_asistencia = $_POST['id_asistencia'];
            $fecha = $_POST['fecha'];
            $estado = $_POST['estado'];
            $justificacion = ($estado == 'J') ? $_POST['justificacion'] : '';
            $asistenciaModelo->actualizarAsistencia($id_asistencia, $fecha, $estado, $justificacion);
            $_SESSION['status'] = "Asistencia actualizada correctamente";
        }
        header("Location: /liceo/controladores/asistencia_controlador.php");
        exit();

    case 'eliminar':
        if(isset($_POST['id_asistencia'])) {
            $id_asistencia = $_POST['id_asistencia'];
            $asistenciaModelo->eliminarAsistencia($id_asistencia);
            echo "Registro eliminado correctamente";
        }
        exit();

    case 'listar':
    default:
        $secciones = $asistenciaModelo->obtenerSecciones();
        $asistencias = $asistenciaModelo->obtenerTodasLasAsistencias();
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/asistencia_vista.php');
        break;
}
?>
