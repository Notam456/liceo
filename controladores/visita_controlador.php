<?php
session_start();
date_default_timezone_set('America/Caracas');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/visita_modelo.php');

$visitaModelo = new VisitaModelo($conn);
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

// NUEVO: Obtener año académico activo para los reportes
require_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/anio_academico_modelo.php');
$anio_modelo = new AnioAcademicoModelo($conn);
$anio_activo_result = $anio_modelo->obtenerAnioActivo();
$anio_activo = mysqli_fetch_assoc($anio_activo_result);

$anio_desde = $anio_activo ? $anio_activo['desde'] : date('Y-01-01');
$anio_hasta = $anio_activo ? $anio_activo['hasta'] : date('Y-12-31');

switch ($action) {
    case 'crear':
        if (isset($_POST['id_estudiante_visita']) && isset($_POST['fecha_visita'])) {
            $resultado = $visitaModelo->crearVisita(
                $_POST['id_estudiante_visita'],
                $_POST['fecha_visita']
            );
            if ($resultado) {
                $_SESSION['status'] = "Visita agendada correctamente";
            } else {
                $_SESSION['status'] = "Error al agendar la visita. El estudiante no tiene inasistencias registradas.";
            }
            header('Location: /liceo/controladores/visita_controlador.php');
            exit();
        }
        break;

    // NUEVO: Generar reporte PDF de visita
    case 'generar_reporte_visita':
        if (isset($_GET['id_visita'])) {
            $id_visita = $_GET['id_visita'];
            
            // Obtener datos de la visita
            $visita_result = $visitaModelo->obtenerVisitaPorId($id_visita);
            
            if ($visita_result && mysqli_num_rows($visita_result) > 0) {
                $visita = mysqli_fetch_assoc($visita_result);
                
                // Generar PDF
                require_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/TCPDF/tcpdf.php');
                
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('Liceo');
                $pdf->SetTitle('Reporte de Visita Domiciliaria');
                $pdf->SetSubject('Reporte de Visita Domiciliaria');
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
                <h1 style="text-align: center; margin-bottom: 20px;">REPORTE DE VISITA DOMICILIARIA</h1>
                
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <tr>
                        <td style="width: 30%;"><strong>Estudiante:</strong></td>
                        <td style="width: 70%;">' . $visita['nombre'] . ' ' . $visita['apellido'] . '</td>
                    </tr>
                    <tr>
                        <td><strong>Cédula:</strong></td>
                        <td>' . $visita['cedula'] . '</td>
                    </tr>
                    <tr>
                        <td><strong>Grado y Sección:</strong></td>
                        <td>' . $visita['numero_anio'] . '° año Sección ' . $visita['letra_seccion'] . '</td>
                    </tr>
                    <tr>
                        <td><strong>Fecha de Nacimiento:</strong></td>
                        <td>' . date('d/m/Y', strtotime($visita['fecha_nacimiento'])) . '</td>
                    </tr>
                    <tr>
                        <td><strong>Contacto:</strong></td>
                        <td>' . $visita['contacto'] . '</td>
                    </tr>
                </table>
                
                <h3 style="margin-bottom: 10px;">Información de Domicilio</h3>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <tr>
                        <td style="width: 30%;"><strong>Dirección Exacta:</strong></td>
                        <td style="width: 70%;">' . $visita['direccion_exacta'] . '</td>
                    </tr>
                    <tr>
                        <td><strong>Punto de Referencia:</strong></td>
                        <td>' . $visita['punto_referencia'] . '</td>
                    </tr>
                    <tr>
                        <td><strong>Sector:</strong></td>
                        <td>' . $visita['sector'] . '</td>
                    </tr>
                    <tr>
                        <td><strong>Parroquia:</strong></td>
                        <td>' . $visita['parroquia'] . '</td>
                    </tr>
                    <tr>
                        <td><strong>Municipio:</strong></td>
                        <td>' . $visita['municipio'] . '</td>
                    </tr>
                </table>
                
                <h3 style="margin-bottom: 10px;">Información de la Visita</h3>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <tr>
                        <td style="width: 30%;"><strong>Fecha Agendada:</strong></td>
                        <td style="width: 70%;">' . date('d/m/Y', strtotime($visita['fecha_visita'])) . '</td>
                    </tr>
                    <tr>
                        <td><strong>Estado:</strong></td>
                        <td>' . ucfirst($visita['estado']) . '</td>
                    </tr>';
                
                if ($visita['fecha_realizada']) {
                    $html .= '
                    <tr>
                        <td><strong>Fecha Realizada:</strong></td>
                        <td>' . date('d/m/Y', strtotime($visita['fecha_realizada'])) . '</td>
                    </tr>';
                }
                
                if ($visita['observaciones']) {
                    $html .= '
                    <tr>
                        <td><strong>Observaciones:</strong></td>
                        <td>' . $visita['observaciones'] . '</td>
                    </tr>';
                }
                
                if ($visita['nombre_tutor']) {
                    $html .= '
                    <tr>
                        <td><strong>Encargado:</strong></td>
                        <td>' . $visita['nombre_tutor'] . ' ' . $visita['apellido_tutor'] . '</td>
                    </tr>';
                }
                
                $html .= '</table>';
                
                // Fecha de generación
                $html .= '
                <div style="margin-top: 30px;">
                    <p>Reporte generado el: ' . date('d/m/Y') . '</p>
                </div>
                ';
                
                $pdf->writeHTML($html, true, false, true, false, '');
                
                $file_name = "Reporte_Visita_" . $visita['nombre'] . "_" . $visita['apellido'] . ".pdf";
                $pdf->Output($file_name, 'I');
                exit;
            } else {
                die("Visita no encontrada");
            }
        }
        break;

        // NUEVO: Generar reporte PDF de todas las visitas
case 'generar_reporte_general_visitas':
    // Obtener todas las visitas según el rol
    if ($_SESSION['rol'] == 'user') {
        $visitas_result = $visitaModelo->obtenerVisitasPorEncargado($_SESSION['profesor']);
    } else {
        $visitas_result = $visitaModelo->obtenerVisitas();
    }
    
    // Generar PDF
    require_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/TCPDF/tcpdf.php');
    
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Liceo');
    $pdf->SetTitle('Reporte General de Visitas Domiciliarias');
    $pdf->SetSubject('Reporte General de Visitas Domiciliarias');
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
    <h1 style="text-align: center; margin-bottom: 20px;">REPORTE GENERAL DE VISITAS DOMICILIARIAS</h1>
    
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <tr>
            <td><strong>Fecha de generación:</strong></td>
            <td>' . date('d/m/Y') . '</td>
        </tr>
        <tr>
            <td><strong>Total de visitas:</strong></td>
            <td>' . mysqli_num_rows($visitas_result) . '</td>
        </tr>
    </table>';
    
    if (mysqli_num_rows($visitas_result) > 0) {
        $html .= '
        <h3 style="margin-bottom: 10px;">Listado de Visitas</h3>
        <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
            <tr style="background-color: #f2f2f2;">
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;"><strong>Estudiante</strong></th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;"><strong>Cédula</strong></th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;"><strong>Fecha Visita</strong></th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;"><strong>Estado</strong></th>
            </tr>
        ';
        
        while ($visita = mysqli_fetch_assoc($visitas_result)) {
            $estado = ucfirst($visita['estado']);
            $html .= '
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px;">' . $visita['nombre'] . ' ' . $visita['apellido'] . '</td>
                <td style="border: 1px solid #ddd; padding: 8px;">' . $visita['cedula'] . '</td>
                <td style="border: 1px solid #ddd; padding: 8px;">' . date('d/m/Y', strtotime($visita['fecha_visita'])) . '</td>
                <td style="border: 1px solid #ddd; padding: 8px;">' . $estado . '</td>
            </tr>
            ';
        }
        
        $html .= '</table>';
    } else {
        $html .= '<p>No hay visitas agendadas para mostrar.</p>';
    }
    
    // Estadísticas
    mysqli_data_seek($visitas_result, 0); // Reset pointer para contar estados
    $estados = ['agendada' => 0, 'realizada' => 0, 'cancelada' => 0];
    while ($visita = mysqli_fetch_assoc($visitas_result)) {
        $estados[$visita['estado']]++;
    }
    
    $html .= '
    <div style="margin-top: 30px;">
        <h3>Resumen por Estado</h3>
        <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
            <tr style="background-color: #f2f2f2;">
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;"><strong>Estado</strong></th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;"><strong>Cantidad</strong></th>
            </tr>
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px;">Agendada</td>
                <td style="border: 1px solid #ddd; padding: 8px;">' . $estados['agendada'] . '</td>
            </tr>
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px;">Realizada</td>
                <td style="border: 1px solid #ddd; padding: 8px;">' . $estados['realizada'] . '</td>
            </tr>
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px;">Cancelada</td>
                <td style="border: 1px solid #ddd; padding: 8px;">' . $estados['cancelada'] . '</td>
            </tr>
        </table>
    </div>
    ';
    
    $pdf->writeHTML($html, true, false, true, false, '');
    
    $file_name = "Reporte_General_Visitas_" . date('Y-m-d') . ".pdf";
    $pdf->Output($file_name, 'I');
    exit;
    break;

    case 'ver':
        if (isset($_POST['id_visita'])) {
            $id = $_POST['id_visita'];
            $resultado = $visitaModelo->obtenerVisitaPorId($id);
            if ($resultado && mysqli_num_rows($resultado) > 0) {
                $row = mysqli_fetch_array($resultado);
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/visita_ver_modal.php');
            } else {
                echo "No se encontraron datos para la visita con el ID proporcionado.";
            }
        }
        break;

    case 'editar':
        if (isset($_POST['id_visita'])) {
            $id = $_POST['id_visita'];
            $resultado = $visitaModelo->obtenerVisitaPorId($id);
            $data = [];
            while($row = mysqli_fetch_assoc($resultado)) {
                $data[] = $row;
            }
            header('Content-Type: application/json');
            echo json_encode($data);
        }
        break;

    case 'ver_para_actualizar':
        if (isset($_POST['id_visita'])) {
            $id = $_POST['id_visita'];
            $action = $_POST['action_type'];
            $resultado = $visitaModelo->obtenerVisitaPorId($id);
            if ($resultado && mysqli_num_rows($resultado) > 0) {
                $row = mysqli_fetch_array($resultado);
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/visita_actualizar_modal.php');
            } else {
                echo "No se encontraron datos para la visita con el ID proporcionado.";
            }
        }
        break;

    case 'actualizar_visita':
        if (isset($_POST['id_visita']) && isset($_POST['estado'])) {
            $fecha_realizada = isset($_POST['fecha_realizada']) ? $_POST['fecha_realizada'] : null;
            $resultado = $visitaModelo->actualizarVisita(
                $_POST['id_visita'],
                $_POST['estado'],
                $_POST['observaciones'],
                $fecha_realizada
            );
            echo $resultado ? "Visita actualizada correctamente" : "No se pudo actualizar la visita";
        }
        break;

    case 'eliminar':
        if (isset($_POST['id_visita'])) {
            $id = $_POST['id_visita'];
            $resultado = $visitaModelo->eliminarVisita($id);
            echo $resultado ? "Visita eliminada correctamente" : "No se pudo eliminar la visita";
        }
        break;

    case 'listar':
    default:
        if ($_SESSION['rol'] == 'user') {
            $visitas = $visitaModelo->obtenerVisitasPorEncargado($_SESSION['profesor']);
        } else {
            $visitas = $visitaModelo->obtenerVisitas();
        }
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/visita_vista.php');
        break;
}
?>
