<?php
session_start();
date_default_timezone_set('America/Caracas');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/seccion_modelo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/grado_modelo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/estudiante_modelo.php');

$seccionModelo = new SeccionModelo($conn);
$gradoModelo = new GradoModelo($conn);
$estudianteModelo = new EstudianteModelo($conn);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'crear':
        if (isset($_POST['save_data'])) {
            $resultado = $seccionModelo->generarSecciones($_POST['cantidad'], $_POST['grado']);
            if ($resultado) {
                $_SESSION['status'] = "Sección creada correctamente";
            } else {
                $_SESSION['status'] = "Esta sección ya existe, vuelva a intentarlo";
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
            if ($tutor) {
                $seccionModelo->actualizarTutor($id, $tutor);
            }
            if ($resultado) {
                $_SESSION['status'] = "Datos actualizados correctamente";
            } else {
                $_SESSION['status'] = "Esta sección ya existe, vuelva a intentarlo";
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

    case 'generar_reporte_inasistencias':
            try {
                // Obtener fechas del período (opcional)
                $desde = isset($_GET['desde']) ? $_GET['desde'] : null;
                $hasta = isset($_GET['hasta']) ? $_GET['hasta'] : null;
                
                // Obtener el reporte de inasistencias por sección
                $reporte_inasistencias = $seccionModelo->obtenerReporteInasistenciasPorSeccion($desde, $hasta);
                
                // Obtener información del período usado
                $periodo_desde = $reporte_inasistencias[0]['periodo_desde'] ?? null;
                $periodo_hasta = $reporte_inasistencias[0]['periodo_hasta'] ?? null;
                
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
                    </tr>
                    <tr>
                        <td style="width: 30%;"><strong>Período Evaluado:</strong></td>
                        <td style="width: 70%;">' . date('d/m/Y', strtotime($periodo_desde)) . ' al ' . date('d/m/Y', strtotime($periodo_hasta)) . '</td>
                    </tr>
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
                        // Determinar color según el porcentaje
                        $color_fila = '';
                        if ($seccion['porcentaje_inasistencia'] > 15) {
                            $color_fila = 'background-color: #fff8f8;';
                        } elseif ($seccion['porcentaje_inasistencia'] > 10) {
                            $color_fila = 'background-color: #fffbf0;';
                        } else {
                            $color_fila = 'background-color: #f8fff8;';
                        }
                        
                        $html .= '
                        <tr style="' . $color_fila . '">
                            <td style="border: 1px solid #ddd; padding: 8px;"><strong>' . $seccion['grado_seccion'] . '</strong></td>
                            <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                                <span style="font-weight: bold; color: ' . ($seccion['porcentaje_inasistencia'] > 15 ? '#dc3545' : ($seccion['porcentaje_inasistencia'] > 10 ? '#ffc107' : '#28a745')) . ';">
                                    ' . $seccion['porcentaje_inasistencia'] . '%
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
                // Manejar errores mostrando un mensaje al usuario
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
