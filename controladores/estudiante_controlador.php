<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/estudiante_modelo.php');

$estudianteModelo = new EstudianteModelo($conn);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'crear':
        if (isset($_POST['save_data'])) {
            $resultado = $estudianteModelo->crearEstudiante(
                $_POST['nombre_estudiante'], $_POST['apellido_estudiante'], $_POST['cedula_estudiante'],
                $_POST['contacto_estudiante'], $_POST['Municipio'], $_POST['Parroquia'],
                $_POST['año_academico'], $_POST['seccion_estudiante']
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
            if ($row = mysqli_fetch_array($resultado)) {
                echo '<h6> Id primaria: '. $row['id_estudiante'] .'</h6>
                      <h6> Nombres: '. $row['nombre_estudiante'] .'</h6>
                      <h6> Apellidos: '. $row['apellido_estudiante'] .'</h6>
                      <h6> C.I: '. $row['cedula_estudiante'] .'</h6>
                      <h6> Contacto: '. $row['contacto_estudiante'] .'</h6>
                      <h6> Municipio: '. $row['Municipio'] .'</h6>
                      <h6> Parroquia: '. $row['Parroquia'] .'</h6>
                      <h6> Año Academico: '. $row['año_academico'] .'</h6>
                      <h6> Sección: '. $row['seccion_estudiante'] .'</h6>';
            } else {
                echo '<h4>No se han encontrado datos</h4>';
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
                $_POST['cedula_estudiante'], $_POST['contacto_estudiante'], $_POST['Municipio'],
                $_POST['Parroquia'], $_POST['año_academico'], $_POST['seccion_estudiante']
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

    case 'generar_constancia':
        if (isset($_GET['id'])) {
            $id_estudiante = $_GET['id'];
            $resultado = $estudianteModelo->obtenerEstudiantePorId($id_estudiante);

            if ($row = mysqli_fetch_array($resultado)) {
                require_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/PHPWord-master/src/PhpWord/Autoloader.php');
                \PhpOffice\PhpWord\Autoloader::register();
                $templatePath = $_SERVER['DOCUMENT_ROOT'] . '/liceo/PHPWord-master/estudianteconstancia.docx';
                $constancia = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

                $constancia->setValue('nombre_estudiante', $row['nombre_estudiante']);
                $constancia->setValue('apellido_estudiante', $row['apellido_estudiante']);
                $constancia->setValue('cedula_estudiante', $row['cedula_estudiante']);
                $constancia->setValue('contacto_estudiante', $row['contacto_estudiante']);
                $constancia->setValue('municipio', $row['Municipio']);
                $constancia->setValue('parroquia', $row['Parroquia']);
                $constancia->setValue('año_academico', $row['año_academico']);
                $constancia->setValue('seccion_estudiante', $row['seccion_estudiante']);

                $dias = ["Sunday" => "Domingo", "Monday" => "Lunes", "Tuesday" => "Martes", "Wednesday" => "Miércoles", "Thursday" => "Jueves", "Friday" => "Viernes", "Saturday" => "Sábado"];
                $dia_actual = $dias[date('l')];

                $constancia->setValue('dia_actual', $dia_actual);
                $constancia->setValue('fecha_actual', date('d/m/Y'));

                $file_name = "Constancia_" . $row['nombre_estudiante'] . "_" . $row['apellido_estudiante'] . ".docx";
                $constancia->saveAs($file_name);

                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . basename($file_name));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file_name));
                flush();
                readfile($file_name);
                unlink($file_name);
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
