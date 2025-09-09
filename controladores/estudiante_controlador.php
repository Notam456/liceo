<?php
session_start();
// jose yajure, ES NECESARIO LA LINEA DEL TIMEZONE PARA QUE LA CONSTANCIA TOME LA FECHA CORRECTA
date_default_timezone_set('America/Caracas');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/estudiante_modelo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/grado_modelo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/parroquia_modelo.php');

$estudianteModelo = new EstudianteModelo($conn);
$gradoModelo = new GradoModelo($conn);
$parroquiaModelo = new ParroquiaModelo($conn);
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'crear':
        if (isset($_POST['save_data'])) {
            $resultado = $estudianteModelo->crearEstudiante(
                $_POST['nombre_estudiante'], $_POST['apellido_estudiante'], $_POST['cedula_estudiante'],
                $_POST['contacto_estudiante'], $_POST['parroquia'],
                $_POST['grado']
            );
            $_SESSION['status'] = $resultado ? "Estudiante creado correctamente" : "Error al crear el estudiante";
            header('Location: /liceo/controladores/estudiante_controlador.php');
            exit();
        }
        break;

    case 'ver':
        if (isset($_POST['id_estudiante'])) {
            $id = $_POST['id_estudiante'];
            $resultado = $estudianteModelo->obtenerEstudiantePorId($id);
            if (mysqli_num_rows($resultado) > 0) {
                $row = mysqli_fetch_array($resultado);
                
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/estudiante_modal_view.php');
            } else {

                $row = [];
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/estudiante_modal_view.php');
            }
        }
        break; 

    case 'editar':
        if (isset($_POST['id_estudiante'])) {
            $id = $_POST['id_estudiante'];
            $resultado = $estudianteModelo->obtenerEstudiantePorId($id);
            $data = [];
            while($row = mysqli_fetch_assoc($resultado)) {
                $data[] = $row;
            }
            header('Content-Type: application/json');
            echo json_encode($data);
        }
        break;

    case 'actualizar':
        if (isset($_POST['update-data'])) {
            $resultado = $estudianteModelo->actualizarEstudiante(
                $_POST['id_estudiante'], $_POST['nombre_estudiante'], $_POST['apellido_estudiante'],
                $_POST['cedula_estudiante'], $_POST['contacto_estudiante'],
                $_POST['parroquia'], $_POST['grado']
            );
            $_SESSION['status'] = $resultado ? "Datos actualizados correctamente" : "No se pudieron actualizar los datos";
            header('Location: /liceo/controladores/estudiante_controlador.php');
            exit();
        }
        break;

    case 'eliminar':
        if (isset($_POST['id_estudiante'])) {
            $id = $_POST['id_estudiante'];
            $resultado = $estudianteModelo->eliminarEstudiante($id);
            echo $resultado ? "Datos eliminados correctamente" : "Los datos no se han podido eliminar";
        }
        break;

    case 'listarPorSeccion':
        if(isset($_GET['id_seccion'])){
            $id_seccion = $_GET['id_seccion'];
            $estudiantes = $estudianteModelo->obtenerEstudiantesPorSeccion($id_seccion);
            include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/estudiante_vista.php');
        }
        break;
        // jose yajure, AÑADIR TCPDF PARA GENERAR EL ARCHIVO EN PDF!!
    case 'generar_constancia':
        if (isset($_GET['id'])) {
            $id_estudiante = $_GET['id'];
            $resultado = $estudianteModelo->obtenerEstudiantePorId($id_estudiante);

            if ($row = mysqli_fetch_array($resultado)) {
                require_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/TCPDF/tcpdf.php');
    
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('Liceo');
                $pdf->SetTitle('Constancia de Estudio');
                $pdf->SetSubject('Constancia de Estudio para Estudiante');
                $pdf->setPrintHeader(false);
                $pdf->setPrintFooter(false);
                $pdf->AddPage();

                $dias = ["Sunday" => "Domingo", "Monday" => "Lunes", "Tuesday" => "Martes", "Wednesday" => "Miércoles", "Thursday" => "Jueves", "Friday" => "Viernes", "Saturday" => "Sábado"];
                $dia_actual = $dias[date('l')];

                $fecha_actual = date('d/m/Y');
                

                $membrete_path = $_SERVER['DOCUMENT_ROOT'] . '/liceo/imgs/membrete.png';
                $ancho_imagen = 180;
                $posicion_x = ($pdf->getPageWidth() - $ancho_imagen) / 2;

                $pdf->Image($membrete_path, $posicion_x, 5, $ancho_imagen, '', '', '', '', false, 300, '', false, false, 0);
            
                $pdf->SetMargins(15, 60, 15);
                $pdf->SetY(50);

                // jose yajure, EN EL PARRAFO DIRECTOR HAY QUE COLOCAR LA CONSULTA 
                //QUE NOS TRAIGA EL NOMBRE Y CEDULA DEL DIRECTOR!!

                $html = '
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td colspan="3" style="text-align: center;"><h1>CONSTANCIA DE ESTUDIO</h1></td>
                        </tr>
                    </table>

                    '.$pdf->Ln(10).'
    
                    <p style="text-align: justify; line-height: 1.5;">
                        Quien suscribe Prof.<strong style="text-transform: uppercase;"> “NOMBRE del DIRECTOR”, </strong> titular de la Cédula de Identidad 
                        N° <strong style="text-transform: uppercase;">“ C.I del Director”</strong> Director del 
                        <strong style="text-transform: uppercase;">LICEO PROFESOR FERNANDO RAMÍREZ</strong> ubicada en el Barrio las Madres detrás del Polideportivo San Felipe Edo. Yaracuy. 
                        Hace constar por medio de la presente que el (la) Estudiante <strong style="text-transform: uppercase;">' .
                         $row['nombre'] . ' ' . $row['apellido'] . '</strong>, titular de la Cédula 
                         <strong style="text-transform: uppercase;"> ' . 
                         $row['cedula'] . ' </strong>, cursa el Grado ' . $row['id_grado'] . ' durante el periodo escolar ' . 
                         $row['id_grado'] . ' de Educación Secundaria y Reside en el Municipio ' . $row['id_parroquia'] . '
                    </p>
                    <br>
                    <p style="text-align: justify;">
                        Constancia que se expide en San Felipe, hoy ' . $dia_actual . ' de fecha ' . $fecha_actual . '
                    </p>
                    <br>
                ';
                
                $pdf->writeHTML($html, true, false, true, false, '');

                $pdf->Ln(30);
                // jose yajure, AÑADÍ $html2 YA QUE EL TCPDF NO ME LEE MAS DE UN <br>
                // TUVE QUE USAR LA FUNCIONA Ln PARA PODER AÑADIR MAS ESPACIO EN DONDE SE FIRMA Y SELLA
                $html2 = '                    
                <p style="text-align: center;">__________________________________</p>
                <p style="text-align: center;">Prof. NOMBRE DIRECTOR</p> 
                <p style="text-align: center;">Barrio Las Madres</p>
                <p style="text-align: center;">Municipio San Felipe</p>';

                $pdf->writeHTML($html2, true, false, true, false, '');

                $file_name = "Constancia_" . $row['nombre'] . "_" . $row['apellido'] . ".pdf";
                $pdf->Output($file_name, 'I');
                exit;
    
            } else {
                echo "No se encontró el estudiante con el ID proporcionado.";
            }
        }
        break;

    case 'listar':
    default:
        $estudiantes = $estudianteModelo->obtenerTodosLosEstudiantes();
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/estudiante_vista.php');
        break;
}
?>
