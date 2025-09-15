<?php

date_default_timezone_set('America/Caracas');
require_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/reporte_modelo.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/anio_academico_modelo.php');

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

        $modelo = new ReporteModelo($conn);
        $reporte = $modelo->obtenerReporteAusencias($desde, $hasta);
        
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

if (isset($_GET['action']) && $_GET['action'] == 'generar_reporte_ausencias') {
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
        $pdf -> Ln(30); // jose yajure, ESPACIO DEL MEMBRETE Y EL CONTENDIOO
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
        </table>
        ';
        
        // Tabla de ausencias
        if (!empty($fechas_ausencias)) {
            $html .= '
            <h3 style="margin-bottom: 10px;">Detalle de inasistencias</h3>
            <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
                <tr style="background-color: #f2f2f2;">
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Fecha</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Estado</th>
                </tr>
            ';
            
            foreach ($fechas_ausencias as $ausencia) {
                $estado = $ausencia['justificado'] ? 'Justificado' : 'Injustificado';
                $html .= '
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">' . date('d/m/Y', strtotime($ausencia['fecha'])) . '</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">' . $estado . '</td>
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

$modelo = new ReporteModelo($conn);
$reporte = $modelo->obtenerReporteAusencias($anio_desde, $anio_hasta);

include($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/reporte_vista.php');
?>