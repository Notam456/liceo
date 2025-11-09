<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/asistencia_modelo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/anio_academico_modelo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/horario_modelo.php'); // Incluir HorarioModelo

$asistenciaModelo = new AsistenciaModelo($conn);
$anioAcademicoModelo = new AnioAcademicoModelo($conn);
$horarioModelo = new HorarioModelo($conn); // Instanciar HorarioModelo

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
                    $materias_asistidas = isset($datos['materias']) ? $datos['materias'] : [];
                    $justificacion = isset($datos['justificacion']) ? trim($datos['justificacion']) : '';

                    $asistenciaModelo->registrarAsistencia($id_estudiante, $fecha, $materias_asistidas, $justificacion, $seccion, $profesor);
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

    case 'obtener_estudiantes_para_asistencia':
        if (isset($_POST['seccion']) && isset($_POST['fecha'])) {
            $id_seccion = $_POST['seccion'];
            $fecha = $_POST['fecha'];

            // Convertir fecha a nombre del día
            $dias_semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
            $dia_semana = $dias_semana[date('N', strtotime($fecha)) - 1];

            // Verificar si hay materias para ese día
            $materias_del_dia = $horarioModelo->getMateriasPorSeccionYDia($id_seccion, $dia_semana);

            if (empty($materias_del_dia)) {
                echo '<div class="alert alert-danger">No hay materias registradas para esta sección en el día seleccionado. No se puede registrar la asistencia.</div>';
                exit();
            }

            // Verificar si ya existe asistencia para esta fecha y sección
            if ($asistenciaModelo->verificarAsistenciaExistente($fecha, $id_seccion)) {
                echo '<div class="alert alert-warning">Ya existe un registro de asistencia para esta fecha y sección.</div>';
                exit();
            }

            $estudiantes = $asistenciaModelo->obtenerEstudiantesPorSeccion($id_seccion);

            if (mysqli_num_rows($estudiantes) > 0) {
                echo '<table class="table table-striped">';
                echo '<thead><tr><th>Estudiante</th><th>Materias Asistidas</th><th>Justificación</th></tr></thead>';
                echo '<tbody>';
                while ($estudiante = mysqli_fetch_assoc($estudiantes)) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($estudiante['nombre']) . ' ' . htmlspecialchars($estudiante['apellido']) . '</td>';
                    echo '<td>';
                    foreach ($materias_del_dia as $materia) {
                        echo '<div class="form-check">';
                        echo '<input class="form-check-input materia-checkbox" type="checkbox" name="asistencia[' . $estudiante['id_estudiante'] . '][materias][]" value="' . $materia['id_asignacion'] . '" checked>';
                        echo '<label class="form-check-label">' . htmlspecialchars($materia['nombre_materia']) . '</label>';
                        echo '</div>';
                    }
                    echo '</td>';
                    echo '<td>';
                    echo '<textarea class="form-control justificacion-input" name="asistencia[' . $estudiante['id_estudiante'] . '][justificacion]" rows="2" style="display:none;" placeholder="Justificación..."></textarea>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
            } else {
                echo '<p class="text-muted">No hay estudiantes en esta sección.</p>';
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
                echo '<thead><tr><th>Estudiante</th><th>C.I</th><th>Estado</th><th>Observación/Materias</th></tr></thead>';
                echo '<tbody>';
                while ($row = mysqli_fetch_assoc($result)) {
                    $estado = '';
                    $detalle = '';
                    if ($row['presente']) {
                        $estado = '<span class="badge bg-success">Presente</span>';
                        $materias_result = $asistenciaModelo->obtenerDetalleMateriasAsistidas($row['id_asistencia']);
                        $materias = [];
                        while ($materia = mysqli_fetch_assoc($materias_result)) {
                            $materias[] = $materia['nombre'];
                        }
                        $detalle = 'Asistió a: ' . implode(', ', $materias);
                    } else if ($row['justificado']) {
                        $estado = '<span class="badge bg-warning">Justificado</span>';
                        $detalle = $row['observacion'] ?: 'N/A';
                    } else {
                        $estado = '<span class="badge bg-danger">Inasistente</span>';
                        $detalle = 'N/A';
                    }
                    echo '<tr>';
                    echo '<td>' . $row['nombre'] . ' ' . $row['apellido'] . '</td>';
                    echo '<td>' . $row['cedula'] . '</td>';
                    echo '<td>' . $estado . '</td>';
                    echo '<td>' . $detalle . '</td>';
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
            $seccion = $_POST['seccion'];
            $fecha = $_POST['fecha'];
            $grado = $_POST['grado'];

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
                echo '<thead><tr><th>Estudiante</th><th>Materias Asistidas</th><th>Justificación</th></tr></thead>';
                echo '<tbody>';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . $row['nombre'] . ' ' . $row['apellido'] . '</td>';
                    echo '<td>';
                    echo '<input type="hidden" name="id_asistencia[]" value="' . $row['id_asistencia'] . '">';

                    $dias_semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                    $dia_semana = $dias_semana[date('N', strtotime($fecha)) - 1];
                    $materias_del_dia = $horarioModelo->getMateriasPorSeccionYDia($id_seccion, $dia_semana);

                    $materias_asistidas_result = $asistenciaModelo->obtenerDetalleMateriasAsistidas($row['id_asistencia']);
                    $materias_asistidas = [];
                    while ($materia = mysqli_fetch_assoc($materias_asistidas_result)) {
                        $materias_asistidas[] = $materia['nombre'];
                    }

                    foreach ($materias_del_dia as $materia) {
                        $checked = in_array($materia['nombre_materia'], $materias_asistidas) ? 'checked' : '';
                        echo '<div class="form-check">';
                        echo '<input class="form-check-input materia-checkbox" type="checkbox" name="asistencia[' . $row['id_asistencia'] . '][materias][]" value="' . $materia['id_asignacion'] . '" ' . $checked . '>';
                        echo '<label class="form-check-label">' . htmlspecialchars($materia['nombre_materia']) . '</label>';
                        echo '</div>';
                    }

                    echo '</td>';
                    echo '<td>';
                    $justificacion_style = 'display:none;';
                    if (empty($materias_asistidas)) {
                        $justificacion_style = '';
                    }
                    echo '<textarea class="form-control form-control-sm justificacion-input" name="asistencia[' . $row['id_asistencia'] . '][justificacion]" rows="2" style="' . $justificacion_style . '">' . htmlspecialchars($row['observacion']) . '</textarea>';
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
                $materias_asistidas = isset($_POST['asistencia'][$id_asistencia]['materias']) ? $_POST['asistencia'][$id_asistencia]['materias'] : [];
                $justificacion = isset($_POST['asistencia'][$id_asistencia]['justificacion']) ? trim($_POST['asistencia'][$id_asistencia]['justificacion']) : '';
                $inasistente = 0;
                $justificado = 0;
                if (empty($materias_asistidas)) {
                    if (!empty($justificacion)) {
                        $justificado = 1; 
                    } else {
                        $inasistente = 1;
                    }
                }

                $result = $asistenciaModelo->actualizarAsistencia($id_asistencia, $justificado, $justificacion, $inasistente);
                $asistenciaModelo->actualizarAsistenciaDetallada($id_asistencia, $materias_asistidas);
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
