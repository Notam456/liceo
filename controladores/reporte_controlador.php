<?php
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

$modelo = new ReporteModelo($conn);
$reporte = $modelo->obtenerReporteAusencias($anio_desde, $anio_hasta);

include($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/reporte_vista.php');
?>