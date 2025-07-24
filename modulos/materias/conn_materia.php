<?php

session_start();
$conn = mysqli_connect("localhost", "root", "", "liceo");

// INSERTAR DATOS
if (isset($_POST['save_data'])) {
    $nombre_materia = $_POST['nombre_materia'];
    $info_materia = $_POST['info_materia'];


    $insert_query = "INSERT INTO 
    materia(nombre_materia, info_materia) 
    VALUES ('$nombre_materia', '$info_materia')";
    $insert_query_run = mysqli_query($conn, $insert_query);

    if ($insert_query_run) {
        $_SESSION['status'] = "Datos ingresados correctamente";
        header('location: crud_materia.php');
    } else {
        $_SESSION['status'] = "Datos ingresados incorrectamente, vuelva a intentar";
        header('location: crud_materia.php');
    }
}

// MOSTRAR DATOS
if (isset($_POST['click-view-btn'])) {

    $id = $_POST['id'];

    //echo $id;

    $fetch_query = "SELECT * FROM materia WHERE `id` = '$id'";
    $fetch_query_run = mysqli_query($conn, $fetch_query);

    if (mysqli_num_rows($fetch_query_run) > 0) {
        while ($row = mysqli_fetch_array($fetch_query_run)) {
            echo '
                <h6> Id primaria: ' . $row['id'] . '</h6>
                <h6> Nombre de la materia: ' . $row['nombre_materia'] . '</h6>
                <h6> Descripción: ' . $row['info_materia'] . '</h6>
            ';
        }
    } else {
        echo '<h4>no se han encontrado datos</h4>';
    }
}

// EDITAR DATOS
if (isset($_POST['click-edit-btn'])) {

    $id = $_POST['id'];
    $array_result = [];

    //echo $id;

    $fetch_query = "SELECT * FROM materia WHERE `id` = '$id'";
    $fetch_query_run = mysqli_query($conn, $fetch_query);

    if (mysqli_num_rows($fetch_query_run) > 0) {
        while ($row = mysqli_fetch_array($fetch_query_run)) {

            array_push($array_result, $row);
            header('content-type: application/json');
            echo json_encode($array_result);
        }
    } else {
        echo '<h4>no se han encontrado datos</h4>';
    }
}

// CARGAR DATOS DEL EDIT
if (isset($_POST['update-data'])) {
    print_r($_POST);
    $id = $_POST['idEdit'];
    $nombre_materia = $_POST['nombre_materia_edit'];
    $info_materia = $_POST['info_materia_edit'];

    $nombre_materia = mysqli_real_escape_string($conn, $nombre_materia);
    $info_materia = mysqli_real_escape_string($conn, $info_materia);


    $update_query = "UPDATE materia SET
        nombre_materia = '$nombre_materia',
        info_materia = '$info_materia'
        WHERE `id` = $id";
        
    $update_query_run = mysqli_query($conn, $update_query);

    if ($update_query_run) {

        $_SESSION['status'] = "Datos actualizados correctamente";
        header('location: crud_materia.php');
    } else {

        $_SESSION['status'] = "Los datos no se pudieron actualizar" . mysqli_error($conn);
        header('location: crud_materia.php');
    }
}


// ELIMINAR DATOS
if (isset($_POST['click-delete-btn'])) {

    $id = $_POST['id'];

    $delete_query = "DELETE FROM materia WHERE id ='$id'";
    $delete_query_run = mysqli_query($conn, $delete_query);

    if ($delete_query_run) {

        echo "Datos eliminados correctamente";
    } else {

        echo "Los datos no se han podido eliminar";
    }
}
