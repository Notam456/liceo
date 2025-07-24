<?php

session_start();
$conn = mysqli_connect("localhost", "root", "", "liceo");

// INSERTAR DATOS
if (isset($_POST['save_data'])) {
    $id_profesores = $_POST['id_profesores'];
    $nombre_profesores = $_POST['nombre_profesores'];
    $apellido_profesores = $_POST['apellido_profesores'];
    $cedula_profesores = $_POST['cedula_profesores'];
    $contacto_profesores = $_POST['contacto_profesores'];
    $materia_impartida = $_POST['materia_impartida'];
    $seccion_profesores = $_POST['seccion_profesores'];

     $insert_query = "INSERT INTO 
    profesores(nombre_profesores, apellido_profesores, cedula_profesores, contacto_profesores, materia_impartida, seccion_profesores) 
    VALUES ('$nombre_profesores', '$apellido_profesores', '$cedula_profesores', '$contacto_profesores', '$materia_impartida', '$seccion_profesores')";
    $insert_query_run = mysqli_query($conn, $insert_query);

    if ($insert_query_run) {
        $_SESSION['status'] = "Datos ingresados correctamente";
        header('location: crud_profesores.php');
    } else {
        $_SESSION['status'] = "Datos ingresados incorrectamente, vuelva a intentar";
        header('location: crud_profesores.php');
    }
}

// MOSTRAR DATOS
if (isset($_POST['click-view-btn'])) {

    $id = $_POST['id_profesores'];

    //echo $id;

    $fetch_query = "SELECT * FROM profesores WHERE id_profesores = '$id'";
    $fetch_query_run = mysqli_query($conn, $fetch_query);

    if (mysqli_num_rows($fetch_query_run) > 0) {
        while ($row = mysqli_fetch_array($fetch_query_run)) {
            echo '
                <h6> Id primaria: '. $row['id_profesores'] .'</h6>
                <h6> Nombres: '. $row['nombre_profesores'] .'</h6>
                <h6> Apellidos: '. $row['apellido_profesores'] .'</h6>
                <h6> C.I: '. $row['cedula_profesores'] .'</h6>
                <h6> Contacto: '. $row['contacto_profesores'] .'</h6>
                
                <h6> seccion_profesores: '. $row['seccion_profesores'] .'</h6>
            ';

        }
    } else {
        echo '<h4>no se han encontrado datos</h4>';
    }
}

// EDITAR DATOS
if (isset($_POST['click-edit-btn'])) {

    $id = $_POST['id_profesores'];
    $array_result = [];

    //echo $id;

    $fetch_query = "SELECT * FROM profesores WHERE id_profesores = '$id'";
    $fetch_query_run = mysqli_query($conn, $fetch_query);

    if (mysqli_num_rows($fetch_query_run) > 0) {
        
        while ($row = mysqli_fetch_array($fetch_query_run)) {

            array_push($array_result,$row);
            header('content-type: application/json');
            echo json_encode($array_result);
            // echo json_encode($row );

        }
    } else {
        echo '<h4>no se han encontrado datos</h4>';
    }
}

// CARGAR DATOS DEL EDIT
if(isset($_POST['update-data'])) {

    $id = $_POST['id_profesores'];
    $nombre_profesores = $_POST['nombre_profesores'];
    $apellido_profesores = $_POST['apellido_profesores'];
    $cedula_profesores = $_POST['cedula_profesores'];
    $contacto_profesores = $_POST['contacto_profesores'];
    $materia_impartida = $_POST['materia_impartida'];
    $seccion_profesores = $_POST['seccion_profesores'];

    $nombre_profesores = mysqli_real_escape_string($conn, $nombre_profesores);
    $apellido_profesores = mysqli_real_escape_string($conn, $apellido_profesores);
    $cedula_profesores = mysqli_real_escape_string($conn, $cedula_profesores);
    $contacto_profesores = mysqli_real_escape_string($conn, $contacto_profesores);
    $materia_impartida = mysqli_real_escape_string($conn, $materia_impartida);
    $seccion_profesores = mysqli_real_escape_string($conn, $seccion_profesores);

    $update_query = "UPDATE profesores SET 
        nombre_profesores = '$nombre_profesores', 
        apellido_profesores = '$apellido_profesores', 
        cedula_profesores = '$cedula_profesores', 
        contacto_profesores = '$contacto_profesores', 
        materia_impartida = '$materia_impartida', 
        seccion_profesores = '$seccion_profesores' 
        WHERE id_profesores = $id";
    $update_query_run = mysqli_query($conn, $update_query);

    if($update_query_run) {

        $_SESSION['status'] = "Datos actualizados correctamente";
        header('location: crud_profesores.php');
    } else {

        $_SESSION['status'] = "Los datos no se pudieron actualizar" . mysqli_error($conn);
        header('location: crud_profesores.php');
    }
}


// ELIMINAR DATOS
if(isset($_POST['click-delete-btn'])) {

    $id = $_POST['id_profesores'];

    $delete_query = "DELETE FROM profesores WHERE id_profesores ='$id'";
    $delete_query_run = mysqli_query($conn, $delete_query);

    if($delete_query_run) {

        echo "Datos eliminados correctamente";

    } else {

        echo "Los datos no se han podido eliminar";
    }
}