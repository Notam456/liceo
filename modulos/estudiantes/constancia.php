<?php
session_start();

require_once dirname(__FILE__) . '/PHPWord-master/src/PhpWord/Autoloader.php';
\PhpOffice\PhpWord\Autoloader::register();

use PhpOffice\PhpWord\TemplateProcessor;

include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');

if (isset($_GET['id'])) {
    $id_estudiante = $_GET['id'];

    $fetch_query = "SELECT * FROM estudiante WHERE id_estudiante = '$id_estudiante'";
    $fetch_query_run = mysqli_query($conn, $fetch_query);

    if (mysqli_num_rows($fetch_query_run) > 0) {
        $row = mysqli_fetch_array($fetch_query_run);

        $constancia = new TemplateProcessor('estudianteconstancia.docx');

        //jfyajure, FALTA COLOCAR LOS DATOS DEL DIRECTOR CONSULTADOS Y CAMBIAR EL NUMERO Y DIRECCION DEL LICEO
        // CREAR DIRECTOR Y AÑADIR EL VALUE

        $constancia->setValue('nombre_estudiante', $row['nombre_estudiante']);
        $constancia->setValue('apellido_estudiante', $row['apellido_estudiante']);
        $constancia->setValue('cedula_estudiante', $row['cedula_estudiante']);
        $constancia->setValue('contacto_estudiante', $row['contacto_estudiante']);
        $constancia->setValue('municipio', $row['Municipio']);
        $constancia->setValue('parroquia', $row['Parroquia']);
        $constancia->setValue('año_academico', $row['año_academico']);
        $constancia->setValue('seccion_estudiante', $row['seccion_estudiante']);

        // jfyajure, NO TOCAR ESTE ARRAY TRADUCE LOS DIAS DE INGLES A ESPAÑOL
        $dias = [
            "Sunday" => "Domingo", 
            "Monday" => "Lunes", 
            "Tuesday" => "Martes", 
            "Wednesday" => "Miércoles", 
            "Thursday" => "Jueves", 
            "Friday" => "Viernes", 
            "Saturday" => "Sábado"
        ];

        $dia_actual = $dias[date('l')];

        $constancia->setValue('dia_actual',$dia_actual);
        $constancia->setValue('fecha_actual', date('d/m/Y'));

        //jfyajure, DESCARGA DEL ARCHIVO, NO TOCAR POR FAVOR
        $file_name = "Constancia_" . $row['nombre_estudiante'] . "_" . $row['apellido_estudiante'] . ".docx";
        $constancia->saveAs($file_name);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $file_name);
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
} else {
    echo "ID de estudiante no proporcionado.";
}
