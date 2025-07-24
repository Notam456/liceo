<?php

session_start();
$conn = mysqli_connect("localhost", "root", "", "liceo");

// INSERTAR DATOS
if (isset($_POST['save_data'])) {
    $id_coordinadores = $_POST['id_coordinadores'];
    $nombre_coordinadores = $_POST['nombre_coordinadores'];
    $apellido_coordinadores = $_POST['apellido_coordinadores'];
    $cedula_coordinadores = $_POST['cedula_coordinadores'];
    $contacto_coordinadores = $_POST['contacto_coordinadores'];
    $area_coordinacion = $_POST['area_coordinacion']; // Cambiado de materia_impartida
    $seccion_coordinadores = $_POST['seccion_coordinadores'];

    $insert_query = "INSERT INTO 
    coordinadores(nombre_coordinadores, apellido_coordinadores, cedula_coordinadores, contacto_coordinadores, area_coordinacion, seccion_coordinadores) 
    VALUES ('$nombre_coordinadores', '$apellido_coordinadores', '$cedula_coordinadores', '$contacto_coordinadores', '$area_coordinacion', '$seccion_coordinadores')";
    $insert_query_run = mysqli_query($conn, $insert_query);

    if ($insert_query_run) {
        $_SESSION['status'] = "Datos de coordinador ingresados correctamente";
        header('location: crud_coordinadores.php'); // Asegúrate de que esta sea la página correcta
    } else {
        $_SESSION['status'] = "Datos de coordinador ingresados incorrectamente, vuelva a intentar";
        header('location: crud_coordinadores.php'); // Asegúrate de que esta sea la página correcta
    }
}

// MOSTRAR DATOS
if (isset($_POST['click-view-btn'])) {

    $id = $_POST['id_coordinadores'];

    $fetch_query = "SELECT * FROM coordinadores WHERE id_coordinadores = '$id'";
    $fetch_query_run = mysqli_query($conn, $fetch_query);

    if (mysqli_num_rows($fetch_query_run) > 0) {
        while ($row = mysqli_fetch_array($fetch_query_run)) {
            echo '
                <h6> Id primaria: '. $row['id_coordinadores'] .'</h6>
                <h6> Nombres: '. $row['nombre_coordinadores'] .'</h6>
                <h6> Apellidos: '. $row['apellido_coordinadores'] .'</h6>
                <h6> C.I: '. $row['cedula_coordinadores'] .'</h6>
                <h6> Contacto: '. $row['contacto_coordinadores'] .'</h6>
                <h6> Área de Coordinación: '. $row['area_coordinacion'] .'</h6> 
                <h6> Sección Coordinada: '. $row['seccion_coordinadores'] .'</h6>
            ';
        }
    } else {
        echo '<h4>No se han encontrado datos del coordinador</h4>';
    }
}

// EDITAR DATOS (obtener datos para el formulario de edición)
if (isset($_POST['click-edit-btn'])) {

    $id = $_POST['id_coordinadores'];
    $array_result = [];

    $fetch_query = "SELECT * FROM coordinadores WHERE id_coordinadores = '$id'";
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

    $id = $_POST['id_coordinadores'];
    $nombre_coordinadores = $_POST['nombre_coordinadores'];
    $apellido_coordinadores = $_POST['apellido_coordinadores'];
    $cedula_coordinadores = $_POST['cedula_coordinadores'];
    $contacto_coordinadores = $_POST['contacto_coordinadores'];
    $area_coordinacion = $_POST['area_coordinacion']; // Cambiado de materia_impartida
    $seccion_coordinadores = $_POST['seccion_coordinadores'];

    $nombre_coordinadores = mysqli_real_escape_string($conn, $nombre_coordinadores);
    $apellido_coordinadores = mysqli_real_escape_string($conn, $apellido_coordinadores);
    $cedula_coordinadores = mysqli_real_escape_string($conn, $cedula_coordinadores);
    $contacto_coordinadores = mysqli_real_escape_string($conn, $contacto_coordinadores);
    $area_coordinacion = mysqli_real_escape_string($conn, $area_coordinacion);
    $seccion_coordinadores = mysqli_real_escape_string($conn, $seccion_coordinadores);

    $update_query = "UPDATE coordinadores SET 
        nombre_coordinadores = '$nombre_coordinadores', 
        apellido_coordinadores = '$apellido_coordinadores', 
        cedula_coordinadores = '$cedula_coordinadores', 
        contacto_coordinadores = '$contacto_coordinadores', 
        area_coordinacion = '$area_coordinacion', 
        seccion_coordinadores = '$seccion_coordinadores' 
        WHERE id_coordinadores = $id";
    $update_query_run = mysqli_query($conn, $update_query);

    if($update_query_run) {
        $_SESSION['status'] = "Datos del coordinador actualizados correctamente";
        header('location: crud_coordinadores.php'); // Asegúrate de que esta sea la página correcta
    } else {
        $_SESSION['status'] = "Los datos del coordinador no se pudieron actualizar: " . mysqli_error($conn);
        header('location: crud_coordinadores.php'); // Asegúrate de que esta sea la página correcta
    }
}

// ELIMINAR DATOS
if(isset($_POST['click-delete-btn'])) {

    $id = $_POST['id_coordinadores'];
    $delete_query = "DELETE FROM coordinadores WHERE id_coordinadores ='$id'";
    $delete_query_run = mysqli_query($conn, $delete_query);

    if($delete_query_run) {
        echo "Datos del coordinador eliminados correctamente";
    } else {
        echo "Los datos del coordinador no se han podido eliminar";
    }
}

?>