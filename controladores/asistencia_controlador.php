<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/asistencia_modelo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/anio_academico_modelo.php');

$asistenciaModelo = new AsistenciaModelo($conn);
$anioAcademicoModelo = new AnioAcademicoModelo($conn);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'registrar':
        if (isset($_POST['guardar_asistencia'])) {
            $fecha = $_POST['fecha'];
            $seccion = $_POST['seccion'];
            $profesor = $_SESSION['profesor'];

            if (empty($fecha) || empty($seccion)) {
                $_SESSION['status'] = "Fecha y sección son requeridas";
            } elseif (isset($_POST['asistencia']) && is_array($_POST['asistencia'])) {
                foreach ($_POST['asistencia'] as $id_estudiante => $datos) {
                    $estado = $datos['estado'];
                    $justificacion = isset($datos['justificacion']) ? $datos['justificacion'] : '';
                    $asistenciaModelo->registrarAsistencia($id_estudiante, $fecha, $estado, $justificacion, $seccion, $profesor);
                }
                $_SESSION['status'] = "Asistencia registrada correctamente";
            } else {
                $_SESSION['status'] = "No se seleccionaron estudiantes";
            }
        }
        header("Location: /liceo/controladores/asistencia_controlador.php");
        exit();

    case 'obtener_grados':
        $grados = $asistenciaModelo->obtenerGrados();
        $options = '<option value="">Seleccione un grado</option>';
        while ($row = mysqli_fetch_assoc($grados)) {
            $options .= '<option value="' . $row['id_grado'] . '">' . $row['numero_anio'] . '° año</option>';
        }
        echo $options;
        exit();

    case 'obtener_secciones_por_grado':
        if (isset($_POST['id_grado'])) {
            $id_grado = $_POST['id_grado'];
            $secciones = $asistenciaModelo->obtenerSeccionesPorGrado($id_grado);
            $options = '<option value="">Seleccione una sección</option>';
            while ($row = mysqli_fetch_assoc($secciones)) {
                $options .= '<option value="' . $row['id_seccion'] . '">' . $row['numero_anio'] . '° ' . $row['letra'] . '</option>';
            }
            echo $options;
        }
        exit();

    case 'obtener_estudiantes':
        if (isset($_POST['seccion'])) {
            $seccion = $_POST['seccion'];
            $fecha = $_POST['fecha'] ?? '';
            
            // Verificar si ya existe asistencia para esta fecha y sección
            if (!empty($fecha) && $asistenciaModelo->verificarAsistenciaExistente($fecha, $seccion)) {
                echo '<div class="alert alert-warning">Ya existe un registro de asistencia para esta fecha y sección.</div>';
                exit();
            }
            
            $result = $asistenciaModelo->obtenerEstudiantesPorSeccion($seccion);

            if (mysqli_num_rows($result) > 0) {
                echo '<table class="table">';
                echo '<thead><tr><th>Estudiante</th><th>Estado</th><th>Justificación</th></tr></thead>';
                echo '<tbody>';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['nombre']) . ' ' . htmlspecialchars($row['apellido']) . '</td>';
                    echo '<td>';
                    echo '<div class="form-check">';
                    echo '<input class="form-check-input" type="radio" name="asistencia[' . $row['id_estudiante'] . '][estado]" value="P" checked> Presente<br>';
                    echo '<input class="form-check-input" type="radio" name="asistencia[' . $row['id_estudiante'] . '][estado]" value="A"> Ausente<br>';
                    echo '<input class="form-check-input justificado-radio" type="radio" name="asistencia[' . $row['id_estudiante'] . '][estado]" value="J"> Justificado';
                    echo '</div>';
                    echo '</td>';
                    echo '<td>';
                    echo '<textarea class="form-control justificado-note" name="asistencia[' . $row['id_estudiante'] . '][justificacion]" rows="2" style="display:none;"></textarea>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
            } else {
                echo '<p class="text-muted">No hay estudiantes en esta sección</p>';
            }
        }
        exit();

    case 'consultar_detalle':
        if (isset($_POST['fecha']) && isset($_POST['id_seccion'])) {
            $fecha = $_POST['fecha'];
            $id_seccion = $_POST['id_seccion'];
            $result = $asistenciaModelo->obtenerDetalleAsistencia($fecha, $id_seccion);
            
            if (mysqli_num_rows($result) > 0) {
                echo '<table class="table table-striped">';
                echo '<thead><tr><th>Estudiante</th><th>C.I</th><th>Estado</th><th>Observación</th></tr></thead>';
                echo '<tbody>';
                while ($row = mysqli_fetch_assoc($result)) {
                    $estado = '';
                    if ((bool)$row['inasistencia']) {
                        $estado = '<span class="badge bg-danger">Ausente</span>';
                    } else if ((bool)$row['justificado']) {
                        $estado = '<span class="badge bg-warning">Justificado</span>';
                    } else {
                        $estado = '<span class="badge bg-success">Presente</span>';
                    }
                    echo '<tr>';
                    echo '<td>' . $row['nombre'] . ' ' . $row['apellido'] . '</td>';
                    echo '<td>' . $row['cedula'] . '</td>';
                    echo '<td>' . $estado . '</td>';
                    echo '<td>' . ($row['observacion'] ?: 'N/A') . '</td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
            } else {
                echo '<p class="text-muted">No hay registros para esta fecha y sección</p>';
            }
        }
        exit();

    case 'filtrar':
        if (isset($_POST['seccion']) || isset($_POST['fecha']) || isset($_POST['grado'])) {
            $seccion = $_POST['seccion'] ?? '';
            $fecha = $_POST['fecha'] ?? '';
            $grado = $_POST['grado'] ?? '';
            
            $result = $asistenciaModelo->filtrarAsistenciasAgrupadas($seccion, $fecha, $grado);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $presentes = $row['total_estudiantes'] - $row['ausentes'] - $row['justificados'];
                    echo '<tr>';
                    echo '<td>' . date('d/m/Y', strtotime($row['fecha'])) . '</td>';
                    echo '<td>' . $row['numero_anio'] . '° ' . $row['letra'] . '</td>';
                    echo '<td>' . $row['numero_anio'] . '° año</td>';
                    echo '<td><span class="badge bg-info">' . $row['total_estudiantes'] . '</span></td>';
                    echo '<td><span class="badge bg-success">' . $presentes . '</span></td>';
                    echo '<td><span class="badge bg-danger">' . $row['ausentes'] . '</span></td>';
                    echo '<td><span class="badge bg-warning">' . $row['justificados'] . '</span></td>';
                    echo '<td>';
                    echo '<button class="btn btn-warning btn-sm" onclick="consultarDetalle(\'' . $row['fecha'] . '\', ' . $row['id_seccion'] . ', \'' . $row['numero_anio'] . '° ' . $row['letra'] . '\')" title="Ver detalle">';
                    echo '<i class="bi bi-eye"></i> Consultar</button> ';
                    echo '<button class="btn btn-primary btn-sm" onclick="modificarAsistencia(\'' . $row['fecha'] . '\', ' . $row['id_seccion'] . ', \'' . $row['numero_anio'] . '° ' . $row['letra'] . '\')" title="Modificar">';
                    echo '<i class="bi bi-pencil"></i> Modificar</button> ';
                    echo '<button class="btn btn-danger btn-sm" onclick="eliminarAsistenciaFecha(\'' . $row['fecha'] . '\', ' . $row['id_seccion'] . '\')" title="Eliminar">';
                    echo '<i class="bi bi-trash"></i> Eliminar</button>';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="8" class="text-center">No hay registros de asistencia con estos filtros</td></tr>';
            }
        }
        exit();

    case 'consultar_detalle_editable':
        if (isset($_POST['fecha']) && isset($_POST['id_seccion'])) {
            $fecha = $_POST['fecha'];
            $id_seccion = $_POST['id_seccion'];
            $result = $asistenciaModelo->obtenerDetalleAsistencia($fecha, $id_seccion);

            if (mysqli_num_rows($result) > 0) {
                echo '<form id="formModificarAsistencia">';
                echo '<input type="hidden" name="fecha" value="' . $fecha . '">';
                echo '<input type="hidden" name="id_seccion" value="' . $id_seccion . '">';
                echo '<div class="table-responsive">';
                echo '<table class="table table-sm">';
                echo '<thead><tr><th>Estudiante</th><th>Estado</th><th>Observación</th></tr></thead>';
                echo '<tbody>';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . $row['nombre'] . ' ' . $row['apellido'] . '</td>';
                    echo '<td>';
                    echo '<input type="hidden" name="id_asistencia[]" value="' . $row['id_asistencia'] . '">';
                    
                    $presente = !$row['inasistencia'] && !$row['justificado'];
                    $ausente = $row['inasistencia'] && !$row['justificado'];
                    $justificado = $row['justificado'];
                    
                    echo '<div class="form-check form-check-inline">';
                    echo '<input class="form-check-input estado-radio" type="radio" name="estado_' . $row['id_asistencia'] . '" value="P"' . ($presente ? ' checked' : '') . '>';
                    echo '<label class="form-check-label">P</label>';
                    echo '</div>';
                    echo '<div class="form-check form-check-inline">';
                    echo '<input class="form-check-input estado-radio" type="radio" name="estado_' . $row['id_asistencia'] . '" value="A"' . ($ausente ? ' checked' : '') . '>';
                    echo '<label class="form-check-label">A</label>';
                    echo '</div>';
                    echo '<div class="form-check form-check-inline">';
                    echo '<input class="form-check-input estado-radio justificado-radio" type="radio" name="estado_' . $row['id_asistencia'] . '" value="J"' . ($justificado ? ' checked' : '') . '>';
                    echo '<label class="form-check-label">J</label>';
                    echo '</div>';
                    echo '</td>';
                    echo '<td>';
                    echo '<textarea class="form-control form-control-sm justificado-note" name="observacion_' . $row['id_asistencia'] . '" rows="2"' . (!$justificado ? ' style="display:none;"' : '') . '>' . htmlspecialchars($row['observacion'] ?? '') . '</textarea>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
                echo '</div>';
                echo '</form>';
            } else {
                echo '<p class="text-muted">No hay registros para esta fecha y sección</p>';
            }
        }
        exit();

    case 'actualizar_asistencia_masiva':
        if (isset($_POST['fecha']) && isset($_POST['id_seccion']) && isset($_POST['id_asistencia'])) {
            $fecha = $_POST['fecha'];
            $id_seccion = $_POST['id_seccion'];
            $ids_asistencia = $_POST['id_asistencia'];
            
            $success = true;
            
            foreach ($ids_asistencia as $id_asistencia) {
                $estado = $_POST['estado_' . $id_asistencia] ?? 'P';
                $observacion = $_POST['observacion_' . $id_asistencia] ?? '';
                
                $inasistencia = ($estado === 'A') ? 1 : 0;
                $justificado = ($estado === 'J') ? 1 : 0;
                
                $result = $asistenciaModelo->actualizarAsistencia($id_asistencia, $inasistencia, $justificado, $observacion);
                if (!$result) {
                    $success = false;
                }
            }
            
            if ($success) {
                echo "Asistencia actualizada correctamente";
            } else {
                echo "Error al actualizar la asistencia";
            }
        }
        exit();

    case 'obtener_para_editar':
        if (isset($_POST['id_asistencia'])) {
            $id_asistencia = $_POST['id_asistencia'];
            $result = $asistenciaModelo->obtenerAsistenciaPorId($id_asistencia);
            if (mysqli_num_rows($result) > 0) {
                echo json_encode(mysqli_fetch_assoc($result));
            }
        }
        exit();

    case 'actualizar':
        if (isset($_POST['actualizar_asistencia'])) {
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
        if (isset($_POST['id_asistencia'])) {
            $id_asistencia = $_POST['id_asistencia'];
            $asistenciaModelo->eliminarAsistencia($id_asistencia);
            echo "Registro eliminado correctamente";
        }
        exit();

    case 'eliminar_por_fecha':
        if (isset($_POST['fecha']) && isset($_POST['id_seccion'])) {
            $fecha = $_POST['fecha'];
            $id_seccion = $_POST['id_seccion'];
            $result = $asistenciaModelo->eliminarAsistenciaPorFechaSeccion($fecha, $id_seccion);
            echo $result ? "Registros eliminados correctamente" : "Error al eliminar los registros";
        }
        exit();

    case 'listar':
    default:
        $secciones = $asistenciaModelo->obtenerSecciones();
        $asistencias = $asistenciaModelo->obtenerAsistenciasAgrupadasPorFecha();
        $anio_activo_result = $anioAcademicoModelo->obtenerAnioActivo();
        $anio_activo = null;
        if (mysqli_num_rows($anio_activo_result) > 0) {
            $anio_activo = mysqli_fetch_assoc($anio_activo_result);
        }
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/asistencia_vista.php');
        break;
}
