<?php
session_start();
date_default_timezone_set('America/Caracas');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/profesor_modelo.php');

$profesorModelo = new ProfesorModelo($conn);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'crear':
        if (isset($_POST['save_data'])) {
            $resultado = $profesorModelo->crearProfesor(
                $_POST['nombre_profesor'],
                $_POST['apellido_profesor'],
                $_POST['cedula_profesor']
            );
            if ($resultado === true) {
                $_SESSION['status'] = "Profesor creado correctamente";
            } elseif ($resultado === 1062) {
                $_SESSION['status'] = "La cédula ya existe. Intente con otra.";
            } else {
                $_SESSION['status'] = "Ocurrió un error inesperado.";
            }
            header('Location: /liceo/controladores/profesor_controlador.php');
            exit();
        }
        break;

    case 'ver':
        if (isset($_POST['id_profesor'])) {
            $id = $_POST['id_profesor'];
            $resultado = $profesorModelo->obtenerProfesorPorId($id);
            if (mysqli_num_rows($resultado) > 0) {
                $row = mysqli_fetch_array($resultado);
                $cargos = $profesorModelo->obtenerCargosPorProfesor($id);
                $materias = $profesorModelo->obtenerMateriasPorProfesor($id);

                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/profesor_modal_view.php');
            } else {

                $row = [];
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/profesor_modal_view.php');
            }
        }
        break;

    case 'editar':
        if (isset($_POST['id_profesor'])) {
            $id = $_POST['id_profesor'];
            $resultado = $profesorModelo->obtenerProfesorPorId($id);
            $data = [];
            while ($row = mysqli_fetch_assoc($resultado)) {
                $data[] = $row;
            }
            header('Content-Type: application/json');
            echo json_encode($data);
        }
        break;

    case 'generar_reporte_profesores':
        try {
            while (ob_get_level()) {
                ob_end_clean();
            }
            // Obtener información del año académico activo
            include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/anio_academico_modelo.php');
            $anioAcademicoModelo = new AnioAcademicoModelo($conn);
            $anio_academico_result = $anioAcademicoModelo->obtenerAnioActivo();

            $periodo_academico = "No definido";
            if ($anio_academico_result && mysqli_num_rows($anio_academico_result) > 0) {
                $anio_academico = mysqli_fetch_assoc($anio_academico_result);
                $periodo_academico = date('d/m/Y', strtotime($anio_academico['desde'])) . ' - ' . date('d/m/Y', strtotime($anio_academico['hasta']));
            }

            // Obtener el reporte completo de profesores
            $reporte_profesores = $profesorModelo->obtenerReporteCompletoProfesores();

            if (empty($reporte_profesores)) {
                throw new Exception("No hay profesores registrados en el sistema");
            }

            $total_profesores = count($reporte_profesores);

            // Generar PDF
            require_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/TCPDF/tcpdf.php');

            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Liceo');
            $pdf->SetTitle('Reporte Completo de Profesores');
            $pdf->SetSubject('Reporte de Profesores con Cargos y Materias');
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
                <h1 style="text-align: center; margin-bottom: 20px;">REPORTE COMPLETO DE PROFESORES</h1>
                
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <tr>
                        <td style="width: 30%;"><strong>Fecha de Generación:</strong></td>
                        <td style="width: 70%;">' . date('d/m/Y') . '</td>
                    </tr>
                    <tr>
                        <td style="width: 30%;"><strong>Total de Profesores:</strong></td>
                        <td style="width: 70%;">' . $total_profesores . '</td>
                    </tr>
                    <tr>
                        <td style="width: 30%;"><strong>Período Académico:</strong></td>
                        <td style="width: 70%;">' . $periodo_academico . '</td>
                    </tr>
                </table>
                
                <h3 style="margin-bottom: 10px;">Lista de Profesores con Cargos y Materias</h3>
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd; font-size: 10px;">
                    <tr style="background-color: #f2f2f2;">
                        <th style="border: 1px solid #ddd; padding: 6px; text-align: left; width: 12%;"><strong>Apellidos</strong></th>
                        <th style="border: 1px solid #ddd; padding: 6px; text-align: left; width: 12%;"><strong>Nombres</strong></th>
                        <th style="border: 1px solid #ddd; padding: 6px; text-align: center; width: 10%;"><strong>Cédula</strong></th>
                        <th style="border: 1px solid #ddd; padding: 6px; text-align: left; width: 23%;"><strong>Cargos Asignados</strong></th>
                        <th style="border: 1px solid #ddd; padding: 6px; text-align: left; width: 43%;"><strong>Materias que Imparte</strong></th>
                    </tr>
                ';

            foreach ($reporte_profesores as $index => $profesor) {
                $color_fila = $index % 2 == 0 ? 'background-color: #f8f9fa;' : 'background-color: #ffffff;';

                // Acortar textos largos para mejor visualización
                $cargos = $profesor['cargos'];
                $materias = $profesor['materias'];

                if (strlen($cargos) > 50) {
                    $cargos = substr($cargos, 0, 47) . '...';
                }

                if (strlen($materias) > 80) {
                    $materias = substr($materias, 0, 77) . '...';
                }

                $html .= '
                    <tr style="' . $color_fila . '">
                        <td style="border: 1px solid #ddd; padding: 6px;">' . $profesor['apellido'] . '</td>
                        <td style="border: 1px solid #ddd; padding: 6px;">' . $profesor['nombre'] . '</td>
                        <td style="border: 1px solid #ddd; padding: 6px; text-align: center;">' . $profesor['cedula'] . '</td>
                        <td style="border: 1px solid #ddd; padding: 6px; font-size: 9px;">' . $cargos . '</td>
                        <td style="border: 1px solid #ddd; padding: 6px; font-size: 9px;">' . $materias . '</td>
                    </tr>
                    ';
            }

            $html .= '</table>';

            // Información adicional
            $html .= '
                <div style="margin-top: 20px; font-size: 10px; color: #666;">
                    <p><strong>Nota:</strong> Este reporte incluye todos los profesores registrados en el sistema con sus cargos y materias activas.</p>
                </div>
                ';

            $pdf->writeHTML($html, true, false, true, false, '');

            $file_name = "Reporte_Profesores_Completo_" . date('Y-m-d') . ".pdf";
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

    case 'actualizar':
        if (isset($_POST['update-data'])) {
            print_r($_POST);
            $resultado = $profesorModelo->actualizarProfesor(
                $_POST['id_profesor'],
                $_POST['nombre_profesor'],
                $_POST['apellido_profesor'],
                $_POST['cedula_profesor']
            );
            if ($resultado === true) {
                $_SESSION['status'] = "Datos actualizados correctamente";
            } elseif ($resultado === 1062) {
                $_SESSION['status'] = "La cédula ya existe. Intente con otra.";
            } else {
                $_SESSION['status'] = "Ocurrió un error inesperado.";
            }
            header('Location: /liceo/controladores/profesor_controlador.php');
            exit();
        }
        break;

    case 'eliminar':
        if (isset($_POST['id_profesor'])) {
            $id = $_POST['id_profesor'];
            $resultado = $profesorModelo->eliminarProfesor($id);
            echo $resultado ? "Datos eliminados correctamente" : "Los datos no se han podido eliminar";
        }
        break;

    case 'listar':
    default:
        $profesores = $profesorModelo->obtenerTodosLosProfesores();
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/profesor_vista.php');
        break;
}
