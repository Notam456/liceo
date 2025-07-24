<?php

session_start();
$conn = mysqli_connect("localhost", "root", "", "liceo");

// INSERTAR DATOS
if (isset($_POST['save_data'])) {
    $nombre_estudiante = $_POST['nombre_estudiante'];
    $apellido_estudiante = $_POST['apellido_estudiante'];
    $cedula_estudiante = $_POST['cedula_estudiante'];
    $contacto_estudiante = $_POST['contacto_estudiante'];
    $año_academico = $_POST['año_academico'];
    $seccion_estudiante = $_POST['seccion_estudiante'];

    $insert_query = "INSERT INTO 
    estudiante(nombre_estudiante, apellido_estudiante, cedula_estudiante, contacto_estudiante, año_academico, seccion_estudiante) 
    VALUES ('$nombre_estudiante', '$apellido_estudiante', '$cedula_estudiante', '$contacto_estudiante', '$año_academico', '$seccion_estudiante')";
    $insert_query_run = mysqli_query($conn, $insert_query);

    if ($insert_query_run) {
        $_SESSION['status'] = "Datos ingresados correctamente";
        header('location: crud_estudiantes.php');
    } else {
        $_SESSION['status'] = "Datos ingresados incorrectamente, vuelva a intentar";
        header('location: crud_estudiantes.php');
    }
}

// MOSTRAR DATOS
if (isset($_POST['click-view-btn'])) {

    $id = $_POST['id_estudiante'];

    //echo $id;

    $fetch_query = "SELECT * FROM estudiante WHERE id_estudiante = '$id'";
    $fetch_query_run = mysqli_query($conn, $fetch_query);

    if (mysqli_num_rows($fetch_query_run) > 0) {
        while ($row = mysqli_fetch_array($fetch_query_run)) {
            echo '
                <h6> Id primaria: '. $row['id_estudiante'] .'</h6>
                <h6> Nombres: '. $row['nombre_estudiante'] .'</h6>
                <h6> Apellidos: '. $row['apellido_estudiante'] .'</h6>
                <h6> C.I: '. $row['cedula_estudiante'] .'</h6>
                <h6> Contacto: '. $row['contacto_estudiante'] .'</h6>
                <h6> Año Academico: '. $row['año_academico'] .'</h6>
                <h6> seccion_estudiante: '. $row['seccion_estudiante'] .'</h6>
            ';

        }
    } else {
        echo '<h4>no se han encontrado datos</h4>';
    }
}

// EDITAR DATOS
if (isset($_POST['click-edit-btn'])) {

    $id = $_POST['id_estudiante'];
    $array_result = [];

    //echo $id;

    $fetch_query = "SELECT * FROM estudiante WHERE id_estudiante = '$id'";
    $fetch_query_run = mysqli_query($conn, $fetch_query);

    if (mysqli_num_rows($fetch_query_run) > 0) {
        while ($row = mysqli_fetch_array($fetch_query_run)) {

            array_push($array_result,$row);
            header('content-type: application/json');
            echo json_encode($array_result);

        }
    } else {
        echo '<h4>no se han encontrado datos</h4>';
    }
}

// CARGAR DATOS DEL EDIT
if(isset($_POST['update-data'])) {

    $id = $_POST['id_estudiante'];
    $nombre_estudiante = $_POST['nombre_estudiante'];
    $apellido_estudiante = $_POST['apellido_estudiante'];
    $cedula_estudiante = $_POST['cedula_estudiante'];
    $contacto_estudiante = $_POST['contacto_estudiante'];
    $año_academico = $_POST['año_academico'];
    $seccion_estudiante = $_POST['seccion_estudiante'];

    $nombre_estudiante = mysqli_real_escape_string($conn, $nombre_estudiante);
    $apellido_estudiante = mysqli_real_escape_string($conn, $apellido_estudiante);
    $cedula_estudiante = mysqli_real_escape_string($conn, $cedula_estudiante);
    $contacto_estudiante = mysqli_real_escape_string($conn, $contacto_estudiante);
    $año_academico = mysqli_real_escape_string($conn, $año_academico);
    $seccion_estudiante = mysqli_real_escape_string($conn, $seccion_estudiante);

    $update_query = "UPDATE estudiante SET 
        nombre_estudiante = '$nombre_estudiante', 
        apellido_estudiante = '$apellido_estudiante', 
        cedula_estudiante = '$cedula_estudiante', 
        contacto_estudiante = '$contacto_estudiante', 
        año_academico = '$año_academico', 
        seccion_estudiante = '$seccion_estudiante',  
        WHERE id_estudiante = $id";
    $update_query_run = mysqli_query($conn, $update_query);

    if($update_query_run) {

        $_SESSION['status'] = "Datos actualizados correctamente";
        header('location: crud_estudiantes.php');
    } else {

        $_SESSION['status'] = "Los datos no se pudieron actualizar" . mysqli_error($conn);
        header('location: crud_estudiantes.php');
    }
}


// ELIMINAR DATOS
if(isset($_POST['click-delete-btn'])) {

    $id = $_POST['id_estudiante'];

    $delete_query = "DELETE FROM estudiante WHERE id_estudiante ='$id'";
    $delete_query_run = mysqli_query($conn, $delete_query);

    if($delete_query_run) {

        echo "Datos eliminados correctamente";

    } else {

        echo "Los datos no se han podido eliminar";
    }
}