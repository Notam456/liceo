<?php

session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');

// INSERTAR DATOS
if (isset($_POST['save_data'])) {
    $id_anio = $_POST['id_anio'];
    $anio = $_POST['anio'];
    $anio_academico = $_POST['anio_academico'];

    $insert_query = "INSERT INTO 
    anio_academico(anio, anio_academico) 
    VALUES ('$anio', '$anio_academico')";
    $insert_query_run = mysqli_query($conn, $insert_query);

    if ($insert_query_run) {
        $_SESSION['status'] = "Datos de coordinador ingresados correctamente";
        header('location: crud_anio_academico.php'); // Asegúrate de que esta sea la página correcta
    } else {
        $_SESSION['status'] = "Datos de coordinador ingresados incorrectamente, vuelva a intentar";
        header('location: crud_anio_academico.php'); // Asegúrate de que esta sea la página correcta
    }
}

// MOSTRAR DATOS
if (isset($_POST['click-view-btn'])) {

    $id = $_POST['id_anio'];

    $fetch_query = "SELECT * FROM anio_academico WHERE id_anio = '$id'";
    $fetch_query_run = mysqli_query($conn, $fetch_query);

    if (mysqli_num_rows($fetch_query_run) > 0) {
        while ($row = mysqli_fetch_array($fetch_query_run)) {
            echo '
                <h6> Id primaria: '. $row['id_anio'] .'</h6>
                <h6> Año: '. $row['anio'] .'</h6>
                <h6> Año academico: '. $row['anio_academico'] .'</h6>
            ';
        }
    } else {
        echo '<h4>No se han encontrado datos del coordinador</h4>';
    }
}

// EDITAR DATOS (obtener datos para el formulario de edición)
if (isset($_POST['click-edit-btn'])) {

    $id = $_POST['id_anio'];
    $array_result = [];

    $fetch_query = "SELECT * FROM anio_academico WHERE id_anio = '$id'";
    $fetch_query_run = mysqli_query($conn, $fetch_query);

    if (mysqli_num_rows($fetch_query_run) > 0) {
        
        while ($row = mysqli_fetch_array($fetch_query_run)) {
            array_push($array_result,$row);
            header('content-type: application/json');
            echo json_encode($array_result);
        }
    } else {
        echo '<h4>No se han encontrado datos del coordinador</h4>';
    }
}

// CARGAR DATOS DEL EDIT (actualizar datos en la base de datos)
if(isset($_POST['update-data'])) {

    $id = $_POST['id_anio'];
    $anio = $_POST['anio'];
    $anio_academico = $_POST['anio_academico'];

    $anio = mysqli_real_escape_string($conn, $anio);
    $anio_academico = mysqli_real_escape_string($conn, $anio_academico);

    $update_query = "UPDATE anio_academico SET 

        anio = '$anio', 
        anio_academico = '$anio_academico' 

        WHERE id_anio = $id";
    $update_query_run = mysqli_query($conn, $update_query);

    if($update_query_run) {
        $_SESSION['status'] = "Datos del coordinador actualizados correctamente";
        header('location: crud_anio_academico.php'); // Asegúrate de que esta sea la página correcta
    } else {
        $_SESSION['status'] = "Los datos del coordinador no se pudieron actualizar: " . mysqli_error($conn);
        header('location: crud_anio_academico.php'); // Asegúrate de que esta sea la página correcta
    }
}

// ELIMINAR DATOS
if(isset($_POST['click-delete-btn'])) {

    $id = $_POST['id_anio'];
    $delete_query = "DELETE FROM anio_academico WHERE id_anio ='$id'";
    $delete_query_run = mysqli_query($conn, $delete_query);

    if($delete_query_run) {
        echo "Datos del coordinador eliminados correctamente";
    } else {
        echo "Los datos del coordinador no se han podido eliminar";
    }
}

?>