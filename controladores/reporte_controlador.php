<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/reporte_modelo.php');

session_start();


if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    
    try {
        $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'semana';
        $modelo = new ReporteModelo($conn);
        $reporte = $modelo->obtenerReporteAusencias($filtro);
        
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


$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'semana';
$modelo = new ReporteModelo($conn);
$reporte = $modelo->obtenerReporteAusencias($filtro);

include($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/reporte_vista.php');
?>