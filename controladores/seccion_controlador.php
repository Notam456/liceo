<?php
session_start();
date_default_timezone_set('America/Caracas');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/seccion_modelo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/grado_modelo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/estudiante_modelo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/anio_academico_modelo.php');

$seccionModelo = new SeccionModelo($conn);
$gradoModelo = new GradoModelo($conn);
$estudianteModelo = new EstudianteModelo($conn);
$anioAcademicoModelo = new AnioAcademicoModelo($conn);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'crear':
    if (isset($_POST['save_data'])) {
        $resultado = $seccionModelo->generarSecciones($_POST['cantidad'], $_POST['grado']);

        switch (true) {
            case $resultado === 'success':
                $_SESSION['status'] = "Secciones generadas correctamente.";
                break;

            case $resultado === 'maximo alcanzado':
                $_SESSION['status'] = "Este grado ya tiene el máximo de 7 secciones.";
                break;

            case $resultado === 'sin cambios':
                $_SESSION['status'] = "No se realizaron cambios, las secciones ya existen o están activas.";
                break;

            case preg_match('/^se crearon \d+ de \d+ secciones$/', $resultado):
                $_SESSION['status'] = ucfirst($resultado) . ".";
                break;

            case $resultado === '1062':
                $_SESSION['status'] = "Error: secciones duplicadas.";
                break;

            default:
                $_SESSION['status'] = "Error al generar secciones: $resultado";
        }

        header('Location: /liceo/controladores/seccion_controlador.php');
        exit();
    }
    break;

    case 'ver':
        if (isset($_POST['id_seccion'])) {
            $id = $_POST['id_seccion'];
            $resultado = $seccionModelo->obtenerSeccionPorId($id);
            if ($row = mysqli_fetch_array($resultado)) {
                $estudiantes = $estudianteModelo->obtenerEstudiantesPorSeccion($id);
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/seccion_modal_view.php');
            } else {
                $row = [];
                $estudiantes = false;
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/seccion_modal_view.php');
            }
        }
        break;

    case 'editar':
        if (isset($_POST['id_seccion'])) {
            $id = $_POST['id_seccion'];
            $resultado = $seccionModelo->obtenerSeccionPorId($id);
            $data = [];
            while ($row = mysqli_fetch_assoc($resultado)) {
                $data[] = $row;
            }
            header('Content-Type: application/json');
            echo json_encode($data);
        }
        break;

    case 'actualizar':
        if (isset($_POST['update-data'])) {
            $id = $_POST['idEdit'];
            $nombre = $_POST['nombreEdit']; 
            $anio = $_POST['añoEdit'];      
            $tutor = $_POST['tutorEdit'];

            $resultado = $seccionModelo->actualizarSeccion($id, $nombre, $anio);

            if ($resultado === "existe") {
                $_SESSION['status'] = "Esta sección ya existe, vuelva a intentarlo.";
            } elseif ($resultado) {
      
                if ($tutor) {
                    $seccionModelo->actualizarTutor($id, $tutor);
                }
                $_SESSION['status'] = "Datos actualizados correctamente.";
            } else {
                $_SESSION['status'] = "Ocurrió un error al actualizar la sección.";
            }

            header('Location: /liceo/controladores/seccion_controlador.php');
            exit();
        }
        break;

    case 'eliminar':
        if (isset($_POST['id_seccion'])) {
            $id = $_POST['id_seccion'];
            $resultado = $seccionModelo->eliminarSeccion($id);
            echo $resultado ? "Datos eliminados correctamente" : "Los datos no se han podido eliminar";
        }
        break;

    case 'generar_matricula_completa':
        while (ob_get_level()) {
            ob_end_clean();
        }
        try {
            if (!isset($_GET['id_seccion'])) {
                throw new Exception("No se ha especificado la sección");
            }

            $id_seccion = $_GET['id_seccion'];

            // Obtener información de la sección
            $seccion_result = $seccionModelo->obtenerSeccionPorId($id_seccion);
            if (!$seccion_result || mysqli_num_rows($seccion_result) == 0) {
                throw new Exception("Sección no encontrada");
            }
            $seccion_data = mysqli_fetch_assoc($seccion_result);

            // Obtener información del año académico activo
            $anio_academico_result = $anioAcademicoModelo->obtenerAnioActivo();
            if (!$anio_academico_result || mysqli_num_rows($anio_academico_result) == 0) {
                throw new Exception("No hay un año académico activo configurado");
            }
            $anio_academico = mysqli_fetch_assoc($anio_academico_result);

            // Formatear el período del año académico
            $periodo_academico = date('d/m/Y', strtotime($anio_academico['desde'])) . ' - ' . date('d/m/Y', strtotime($anio_academico['hasta']));

            // Obtener la matrícula completa
            $matricula = $seccionModelo->obtenerMatriculaCompletaPorSeccion($id_seccion);

            $grado_seccion = $seccion_data['numero_anio'] . '° ' . $seccion_data['letra'];

            // CORRECCIÓN: Verificar si es un array de estudiantes o un array informativo vacío
            $total_estudiantes = 0;
            $tiene_estudiantes = false;

            // Verificar si el array contiene estudiantes (índices numéricos) o solo información de la sección
            if (!empty($matricula)) {
                // Verificar si es un array de estudiantes (tiene índices numéricos)
                $tiene_estudiantes = false;
                foreach ($matricula as $key => $value) {
                    if (is_numeric($key)) {
                        $tiene_estudiantes = true;
                        $total_estudiantes++;
                    }
                }
            }

            // Obtener información del tutor
            $tutor_nombre = "No asignado";
            if (!empty($seccion_data['nombre_tutor'])) {
                $tutor_nombre = $seccion_data['nombre_tutor'] . ' ' . $seccion_data['apellido_tutor'];
                if (!empty($seccion_data['cedula_tutor'])) {
                    $tutor_nombre .= ' (C.I: ' . $seccion_data['cedula_tutor'] . ')';
                }
            }

            // Generar PDF
            require_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/TCPDF/tcpdf.php');

            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Liceo');
            $pdf->SetTitle('Matrícula Completa - ' . $grado_seccion);
            $pdf->SetSubject('Matrícula de Estudiantes');
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->AddPage();

            // Membrete
            $membrete_path = $_SERVER['DOCUMENT_ROOT'] . '/liceo/imgs/membrete.png';
            if (file_exists($membrete_path)) {
                $ancho_imagen = 180;
                $posicion_x = ($pdf->getPageWidth() - $ancho_imagen) / 2;
                $pdf->Image($membrete_path, $posicion_x, 5, $ancho_imagen, '', '', '', '', false, 300, '', false, false, 0);
            }

            $pdf->SetMargins(15, 50, 15);

            // Título
            $pdf->Ln(30);
            $html = '
                    <h1 style="text-align: center; margin-bottom: 20px;">MATRÍCULA COMPLETA DE ESTUDIANTES</h1>
                    
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                        <tr>
                            <td style="width: 30%;"><strong>Fecha de Generación:</strong></td>
                            <td style="width: 70%;">' . date('d/m/Y') . '</td>
                        </tr>
                        <tr>
                            <td style="width: 30%;"><strong>Sección:</strong></td>
                            <td style="width: 70%;">' . $grado_seccion . '</td>
                        </tr>
                        <tr>
                            <td style="width: 30%;"><strong>Tutor:</strong></td>
                            <td style="width: 70%;">' . $tutor_nombre . '</td>
                        </tr>
                        <tr>
                            <td style="width: 30%;"><strong>Total de Estudiantes:</strong></td>
                            <td style="width: 70%;">' . $total_estudiantes . '</td>
                        </tr>
                        <tr>
                            <td style="width: 30%;"><strong>Período Académico:</strong></td>
                            <td style="width: 70%;">' . $periodo_academico . '</td>
                        </tr>
                    </table>
                ';

            // CORRECCIÓN: Solo generar la tabla si hay estudiantes reales
            if ($tiene_estudiantes) {
                $html .= '
                        <h3 style="margin-bottom: 10px;">Lista de Estudiantes Matriculados</h3>
                        <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd; font-size: 10px;">
                            <tr style="background-color: #f2f2f2;">
                                <th style="border: 1px solid #ddd; padding: 6px; text-align: left; width: 15%;"><strong>Apellidos</strong></th>
                                <th style="border: 1px solid #ddd; padding: 6px; text-align: left; width: 15%;"><strong>Nombres</strong></th>
                                <th style="border: 1px solid #ddd; padding: 6px; text-align: center; width: 12%;"><strong>Cédula</strong></th>
                                <th style="border: 1px solid #ddd; padding: 6px; text-align: center; width: 12%;"><strong>Fecha Nac.</strong></th>
                                <th style="border: 1px solid #ddd; padding: 6px; text-align: left; width: 46%;"><strong>Dirección Completa</strong></th>
                            </tr>
                    ';

                foreach ($matricula as $index => $estudiante) {
                    // CORRECCIÓN: Solo procesar elementos con índices numéricos (estudiantes reales)
                    if (is_numeric($index)) {
                        $color_fila = $index % 2 == 0 ? 'background-color: #f8f9fa;' : 'background-color: #ffffff;';

                        $html .= '
                                <tr style="' . $color_fila . '">
                                    <td style="border: 1px solid #ddd; padding: 6px;">' . $estudiante['apellido'] . '</td>
                                    <td style="border: 1px solid #ddd; padding: 6px;">' . $estudiante['nombre'] . '</td>
                                    <td style="border: 1px solid #ddd; padding: 6px; text-align: center;">' . $estudiante['cedula'] . '</td>
                                    <td style="border: 1px solid #ddd; padding: 6px; text-align: center;">' . $estudiante['fecha_nacimiento'] . '</td>
                                    <td style="border: 1px solid #ddd; padding: 6px; font-size: 9px;">' . $estudiante['direccion_completa'] . '</td>
                                </tr>
                            ';
                    }
                }

                $html .= '</table>';
            } else {
                // Mostrar mensaje cuando no hay estudiantes
                $html .= '
                        <div style="text-align: center; padding: 40px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px;">
                            <h3 style="color: #6c757d; margin-bottom: 15px;">No hay estudiantes matriculados</h3>
                            <p style="color: #6c757d;">No se han asignado estudiantes a esta sección.</p>
                        </div>
                    ';
            }

            $pdf->writeHTML($html, true, false, true, false, '');

            $file_name = "Matricula_Completa_" . str_replace(' ', '_', $grado_seccion) . "_" . date('Y-m-d') . ".pdf";
            $pdf->Output($file_name, 'I');
        } catch (Exception $e) {
            echo "<script>
                        alert('Error al generar el reporte: " . addslashes($e->getMessage()) . "');
                        window.history.back();
                    </script>";
            exit;
        }
        exit;
        break;

    case 'generar_reporte_inasistencias':
        while (ob_get_level()) {
            ob_end_clean();
        }
        try {
            // Obtener fechas del período (opcional)
            $desde = isset($_GET['desde']) ? $_GET['desde'] : null;
            $hasta = isset($_GET['hasta']) ? $_GET['hasta'] : null;

            // Obtener el reporte de inasistencias por sección
            $reporte_inasistencias = $seccionModelo->obtenerReporteInasistenciasPorSeccion($desde, $hasta);

            // CORRECCIÓN: Convertir los porcentajes a float para evitar errores de tipo
            if (!empty($reporte_inasistencias)) {
                foreach ($reporte_inasistencias as &$seccion) {
                    $seccion['porcentaje_inasistencia'] = floatval($seccion['porcentaje_inasistencia']);
                }
                unset($seccion); // Romper la referencia
            }

            // Obtener información del período usado
            $periodo_desde = isset($reporte_inasistencias[0]['periodo_desde']) ? $reporte_inasistencias[0]['periodo_desde'] : null;
            $periodo_hasta = isset($reporte_inasistencias[0]['periodo_hasta']) ? $reporte_inasistencias[0]['periodo_hasta'] : null;

            // Generar PDF
            require_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/TCPDF/tcpdf.php');

            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Liceo');
            $pdf->SetTitle('Reporte General de Inasistencias por Sección');
            $pdf->SetSubject('Reporte de Inasistencias');
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->AddPage();

            // Membrete
            $membrete_path = $_SERVER['DOCUMENT_ROOT'] . '/liceo/imgs/membrete.png';
            if (file_exists($membrete_path)) {
                $ancho_imagen = 180;
                $posicion_x = ($pdf->getPageWidth() - $ancho_imagen) / 2;
                $pdf->Image($membrete_path, $posicion_x, 5, $ancho_imagen, '', '', '', '', false, 300, '', false, false, 0);
            }

            $pdf->SetMargins(15, 50, 15);

            // Título
            $pdf->Ln(30);
            $html = '
            <h1 style="text-align: center; margin-bottom: 20px;">REPORTE GENERAL DE INASISTENCIAS POR SECCIÓN</h1>
            
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <tr>
                    <td style="width: 30%;"><strong>Fecha de Generación:</strong></td>
                    <td style="width: 70%;">' . date('d/m/Y') . '</td>
                </tr>';

            // Solo mostrar período si está disponible
            if ($periodo_desde && $periodo_hasta) {
                $html .= '
                <tr>
                    <td style="width: 30%;"><strong>Período Evaluado:</strong></td>
                    <td style="width: 70%;">' . date('d/m/Y', strtotime($periodo_desde)) . ' al ' . date('d/m/Y', strtotime($periodo_hasta)) . '</td>
                </tr>
            ';
            }

            $html .= '
                <tr>
                    <td style="width: 30%;"><strong>Año Académico:</strong></td>
                    <td style="width: 70%;">Activo</td>
                </tr>
            </table>
            
            <h3 style="margin-bottom: 10px;">Resumen por Sección</h3>
            <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
                <tr style="background-color: #f2f2f2;">
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;"><strong>Grado y Sección</strong></th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: center;"><strong>% de Inasistencia</strong></th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;"><strong>Estudiante con más Inasistencias</strong></th>
                </tr>
            ';

            if (!empty($reporte_inasistencias)) {
                foreach ($reporte_inasistencias as $seccion) {
                    // CORRECCIÓN: Usar variable numérica para las comparaciones
                    $porcentaje = floatval($seccion['porcentaje_inasistencia']);

                    // Determinar color según el porcentaje
                    $color_fila = '';
                    if ($porcentaje > 15) {
                        $color_fila = 'background-color: #fff8f8;';
                    } elseif ($porcentaje > 10) {
                        $color_fila = 'background-color: #fffbf0;';
                    } else {
                        $color_fila = 'background-color: #f8fff8;';
                    }

                    $html .= '
                    <tr style="' . $color_fila . '">
                        <td style="border: 1px solid #ddd; padding: 8px;"><strong>' . $seccion['grado_seccion'] . '</strong></td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                            <span style="font-weight: bold; color: ' . ($porcentaje > 15 ? '#dc3545' : ($porcentaje > 10 ? '#ffc107' : '#28a745')) . ';">
                                ' . number_format($seccion['porcentaje_inasistencia'], 2) . '%
                            </span>
                        </td>
                        <td style="border: 1px solid #ddd; padding: 8px;">' . $seccion['estudiante_mas_inasistencias'] . '</td>
                    </tr>
                    ';
                }
            } else {
                $html .= '
                <tr>
                    <td colspan="3" style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                        No hay datos de inasistencias para mostrar en el período seleccionado.
                    </td>
                </tr>
                ';
            }

            $html .= '</table>';

            // Nota explicativa
            $html .= '
            <div style="margin-top: 20px; font-size: 10px; color: #666;">
                <p><strong>Nota:</strong> El porcentaje de inasistencia se calcula con la fórmula: (Total inasistencias de la sección / Total asistencia posible) × 100</p>
                <p>Donde: Total asistencia posible = (N° estudiantes) × (Días hábiles en el período)</p>
            </div>
            ';

            $pdf->writeHTML($html, true, false, true, false, '');

            $file_name = "Reporte_Inasistencias_General_" . date('Y-m-d') . ".pdf";
            $pdf->Output($file_name, 'I');
        } catch (Exception $e) {
            echo "<script>
                alert('Error al generar el reporte: " . addslashes($e->getMessage()) . "');
                window.history.back();
            </script>";
            exit;
        }
        exit;
        break;

    case 'listar':
    default:
        if ($_SESSION['rol'] == 'user') {
            $secciones = $seccionModelo->obtenerSeccionesPorTutor($_SESSION['profesor']);
        } else {
            $secciones = $seccionModelo->obtenerTodasLasSecciones();
        }
        $horarios_status = [];
        if ($secciones) {
            $secciones_copy = [];
            while ($row = $secciones->fetch_assoc()) {
                $secciones_copy[] = $row;
            }
            foreach ($secciones_copy as $row) {

                $horario_result = $seccionModelo->obtenerHorarioPorSeccion($row['id_seccion']);
                $horarios_status[$row['id_seccion']] = (mysqli_num_rows($horario_result) > 0);
            }
        }

        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/seccion_vista.php');
        break;
}
