<?php

date_default_timezone_set('America/Caracas');
require_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/ausencia_modelo.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/anio_academico_modelo.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/seccion_modelo.php');

session_start();

$anio_modelo = new AnioAcademicoModelo($conn);
$anio_activo_result = $anio_modelo->obtenerAnioActivo();
$anio_activo = mysqli_fetch_assoc($anio_activo_result);

$anio_desde = $anio_activo ? $anio_activo['desde'] : date('Y-01-01');
$anio_hasta = $anio_activo ? $anio_activo['hasta'] : date('Y-12-31');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');

    try {
        $desde = isset($_GET['desde']) ? $_GET['desde'] : null;
        $hasta = isset($_GET['hasta']) ? $_GET['hasta'] : null;

        // CORRECCIÓN CLAVE: Leer el valor del GET y asegurar que no sea un string vacío
        $id_seccion_raw = isset($_GET['id_seccion']) ? $_GET['id_seccion'] : null;

        // Si el valor es numérico y no está vacío, lo usamos; de lo contrario, es null (para "Todas las Secciones")
        $id_seccion = (is_numeric($id_seccion_raw) && $id_seccion_raw != '') ? (int)$id_seccion_raw : null;

        $modelo = new ReporteModelo($conn);
        $reporte = $modelo->obtenerReporteAusencias($desde, $hasta, $id_seccion);

        echo json_encode([
            'success' => true,
            'data' => $reporte
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage(),
            'data' => []
        ]);
    }
    exit();
}

// NUEVO: Generar reporte general por sección
if (isset($_GET['action']) && $_GET['action'] == 'generar_reporte_general_seccion') {
    while (ob_get_level()) {
        ob_end_clean();
    }
    if (isset($_GET['id_seccion'])) {
        $id_seccion = $_GET['id_seccion'];
        $desde = isset($_GET['desde']) ? $_GET['desde'] : $anio_desde;
        $hasta = isset($_GET['hasta']) ? $_GET['hasta'] : $anio_hasta;

        // Obtener información de la sección
        $seccionModelo = new SeccionModelo($conn);
        $seccion_result = $seccionModelo->obtenerSeccionPorId($id_seccion);

        if (!$seccion_result || mysqli_num_rows($seccion_result) == 0) {
            die("Sección no encontrada");
        }

        $seccion_data = mysqli_fetch_assoc($seccion_result);
        $grado = $seccion_data['numero_anio'] . '° año';
        $seccion_letra = $seccion_data['letra'];

        // Obtener reporte por sección
        $modelo = new ReporteModelo($conn);
        $reporte_seccion = $modelo->obtenerReportePorSeccion($id_seccion, $desde, $hasta);

        // Generar PDF
        require_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/TCPDF/tcpdf.php');

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Liceo');
        $pdf->SetTitle('Reporte General de Inasistencias por Sección');
        $pdf->SetSubject('Reporte General de Inasistencias');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        // Logo o membrete
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
        <h1 style="text-align: center; margin-bottom: 20px;">REPORTE GENERAL DE INASISTENCIAS</h1>
        
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr>
                <td style="width: 30%;"><strong>Grado y Sección:</strong></td>
                <td style="width: 70%;">' . $grado . ' Sección ' . $seccion_letra . '</td>
            </tr>
            <tr>
                <td><strong>Período:</strong></td>
                <td>' . date('d/m/Y', strtotime($desde)) . ' al ' . date('d/m/Y', strtotime($hasta)) . '</td>
            </tr>
            <tr>
                <td><strong>Total de Estudiantes:</strong></td>
                <td>' . count($reporte_seccion) . '</td>
            </tr>
            <tr>
                <td><strong>Fecha de Generación:</strong></td>
                <td>' . date('d/m/Y') . '</td>
            </tr>
        </table>
        ';

        // Tabla de estudiantes
        if (!empty($reporte_seccion)) {
            // Calcular estadísticas
            $total_estudiantes = count($reporte_seccion);
            $estudiantes_con_ausencias = array_filter($reporte_seccion, function ($item) {
                return $item['total'] > 0;
            });
            $estudiantes_sin_ausencias = $total_estudiantes - count($estudiantes_con_ausencias);
            $total_ausencias = array_sum(array_column($reporte_seccion, 'ausencias'));
            $total_justificadas = array_sum(array_column($reporte_seccion, 'justificadas'));

            $html .= '
            <h3 style="margin-bottom: 10px;">Resumen Estadístico</h3>
            <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd; margin-bottom: 20px;">
                <tr style="background-color: #f2f2f2;">
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;"><strong>Indicador</strong></th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;"><strong>Valor</strong></th>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">Estudiantes con inasistencias</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">' . count($estudiantes_con_ausencias) . '</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">Estudiantes sin inasistencias</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">' . $estudiantes_sin_ausencias . '</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">Total inasistencias injustificadas</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">' . $total_ausencias . '</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">Total inasistencias justificadas</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">' . $total_justificadas . '</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">Total general de inasistencias</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">' . ($total_ausencias + $total_justificadas) . '</td>
                </tr>
            </table>
            
            <h3 style="margin-bottom: 10px;">Listado de Estudiantes</h3>
            <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
                <tr style="background-color: #f2f2f2;">
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;"><strong>#</strong></th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;"><strong>Estudiante</strong></th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;"><strong>Cédula</strong></th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;"><strong>Contacto</strong></th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: center;"><strong>Ausencias</strong></th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: center;"><strong>Justificadas</strong></th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: center;"><strong>Total</strong></th>
                </tr>
            ';

            $contador = 1;
            foreach ($reporte_seccion as $estudiante) {
                $fila_color = $estudiante['total'] > 0 ? 'background-color: #fff8f8;' : 'background-color: #f8fff8;';
                $html .= '
                <tr style="' . $fila_color . '">
                    <td style="border: 1px solid #ddd; padding: 8px;">' . $contador . '</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">' . $estudiante['nombre'] . '</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">' . $estudiante['cedula'] . '</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">' . $estudiante['contacto'] . '</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">' . $estudiante['ausencias'] . '</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">' . $estudiante['justificadas'] . '</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;"><strong>' . $estudiante['total'] . '</strong></td>
                </tr>
                ';
                $contador++;
            }

            $html .= '</table>';
        } else {
            $html .= '<p>No hay estudiantes en esta sección.</p>';
        }

        $pdf->writeHTML($html, true, false, true, false, '');

        $file_name = "Reporte_General_" . $grado . "_Seccion_" . $seccion_letra . ".pdf";
        $pdf->Output($file_name, 'I');
        exit;
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'generar_reporte_ausencias') {
    while (ob_get_level()) {
        ob_end_clean();
    }
    if (isset($_GET['id_estudiante'])) {
        $id_estudiante = $_GET['id_estudiante'];

        // Obtener datos del estudiante
        require_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/estudiante_modelo.php');
        $estudianteModelo = new EstudianteModelo($conn);
        $estudiante_result = $estudianteModelo->obtenerEstudiantePorId($id_estudiante);
        $estudiante = mysqli_fetch_assoc($estudiante_result);

        if (!$estudiante) {
            die("Estudiante no encontrado");
        }

        // Obtener información de ubicación desde los datos del estudiante
        $municipio = isset($estudiante['municipio']) ? $estudiante['municipio'] : "No especificado";
        $parroquia = isset($estudiante['parroquia']) ? $estudiante['parroquia'] : "No especificada";
        $sector = isset($estudiante['sector']) ? $estudiante['sector'] : "No especificado";

        // Obtener fechas de ausencias
        $desde = isset($_GET['desde']) ? $_GET['desde'] : $anio_desde;
        $hasta = isset($_GET['hasta']) ? $_GET['hasta'] : $anio_hasta;

        $modelo = new ReporteModelo($conn);
        $fechas_ausencias = $modelo->obtenerFechasAusencias($id_estudiante, $desde, $hasta);

        // Generar PDF
        require_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/TCPDF/tcpdf.php');

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Liceo');
        $pdf->SetTitle('Reporte de Ausencias');
        $pdf->SetSubject('Reporte de Ausencias del Estudiante');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        // Obtener información de la sección
        $seccion = "No asignada";
        if ($estudiante['id_seccion']) {
            require_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/seccion_modelo.php');
            $seccionModelo = new SeccionModelo($conn);
            $seccion_result = $seccionModelo->obtenerSeccionPorId($estudiante['id_seccion']);
            if ($seccion_data = mysqli_fetch_assoc($seccion_result)) {
                $seccion = $seccion_data['letra'];
            }
        }

        // Obtener información del grado
        $grado = "No asignado";
        if ($estudiante['id_grado']) {
            require_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/grado_modelo.php');
            $gradoModelo = new GradoModelo($conn);
            $grado_result = $gradoModelo->obtenerGradoPorId($estudiante['id_grado']);
            if ($grado_data = mysqli_fetch_assoc($grado_result)) {
                $grado = $grado_data['numero_anio'] . '° año';
            }
        }

        // Logo o membrete
        $membrete_path = $_SERVER['DOCUMENT_ROOT'] . '/liceo/imgs/membrete.png';
        if (file_exists($membrete_path)) {
            $ancho_imagen = 180;
            $posicion_x = ($pdf->getPageWidth() - $ancho_imagen) / 2;
            $pdf->Image($membrete_path, $posicion_x, 5, $ancho_imagen, '', '', '', '', false, 300, '', false, false, 0);
        }

        $pdf->SetMargins(15, 50, 15);

        // Título
        $pdf->Ln(30); // jose yajure, ESPACIO DEL MEMBRETE Y EL CONTENDIOO
        $html = '
        <h1 style="text-align: center; margin-bottom: 20px;">REPORTE DE AUSENCIAS</h1>
        
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr>
                <td style="width: 30%;"><strong>Estudiante:</strong></td>
                <td style="width: 70%;">' . $estudiante['nombre'] . ' ' . $estudiante['apellido'] . '</td>
            </tr>
            <tr>
                <td><strong>Cédula:</strong></td>
                <td>' . $estudiante['cedula'] . '</td>
            </tr>
            <tr>
                <td><strong>Grado y Sección:</strong></td>
                <td>' . $grado . ' Sección ' . $seccion . '</td>
            </tr>
            <tr>
                <td><strong>Período:</strong></td>
                <td>' . date('d/m/Y', strtotime($desde)) . ' al ' . date('d/m/Y', strtotime($hasta)) . '</td>
            </tr>
            <tr>
                <td><strong>Total de inasistencias:</strong></td>
                <td>' . count($fechas_ausencias) . '</td>
            </tr>
            <tr>
                <td><strong>Dirección:</strong></td>
                <td>' . $municipio . ' - ' . $parroquia . ' - ' . $sector . ' ' . $estudiante['direccion_exacta'] . '</td>
            </tr>
        </table>
        ';

        // Tabla de ausencias
        if (!empty($fechas_ausencias)) {
            $html .= '
            <h3 style="margin-bottom: 10px;">Detalle de inasistencias</h3>
            <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
                <tr style="background-color: #f2f2f2;">
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;"><strong>Fecha</strong></th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;"><strong>Estado</strong></th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;"><strong>Justificación</strong></th>
                </tr>
            ';

            foreach ($fechas_ausencias as $ausencia) {
                $estado = $ausencia['justificado'] ? 'Justificado' : 'Injustificado';
                $observacion = isset($ausencia['observacion']) ? $ausencia['observacion'] : 'Sin justificación';
                $html .= '
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">' . date('d/m/Y', strtotime($ausencia['fecha'])) . '</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">' . $estado . '</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">' . $observacion . '</td>
                </tr>
                ';
            }

            $html .= '</table>';
        } else {
            $html .= '<p>No se registran inasistencias en el período seleccionado.</p>';
        }

        // Fecha de generación
        $html .= '
        <div style="margin-top: 30px;">
            <p>Reporte generado el: ' . date('d/m/Y') . '</p>
        </div>
        ';

        $pdf->writeHTML($html, true, false, true, false, '');

        $file_name = "Reporte_Ausencias_" . $estudiante['nombre'] . "_" . $estudiante['apellido'] . ".pdf";
        $pdf->Output($file_name, 'I');
        exit;
    }
}

// 4. NUEVO: Obtener la lista de secciones para la vista
$seccionModelo = new SeccionModelo($conn);
// Usamos obtenerTodasLasSecciones() que ya implementa la lógica de filtrado por tipo_cargo.
$secciones_result = $seccionModelo->obtenerTodasLasSecciones();

$secciones = [];
if ($secciones_result) {
    // Es importante verificar si $secciones_result es un objeto mysqli_result
    if ($secciones_result instanceof mysqli_result) {
        while ($row = mysqli_fetch_assoc($secciones_result)) {
            // Creamos un campo 'grado' combinado para la visualización en el select
            $row['grado'] = $row['numero_anio'] . '° ' . $row['letra'];
            $secciones[] = $row;
        }
    }
}

$reporte = [];
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    $modelo = new ReporteModelo($conn);
    $reporte = $modelo->obtenerReporteAusencias($anio_desde, $anio_hasta);
}

include($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/ausencia_vista.php');
