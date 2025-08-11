<?php

session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');

// INSERTAR DATOS
if (isset($_POST['save_data'])) {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $rol = $_POST['rol'];


    $insert_query = "INSERT INTO 
    usuario(usuario, contrasena, rol) 
    VALUES ('$usuario', '$contrasena', '$rol')";
    $insert_query_run = mysqli_query($conn, $insert_query);

    if ($insert_query_run) {
        $_SESSION['status'] = "Datos ingresados correctamente";
        header('location: crud_usuarios.php');
    } else {
        $_SESSION['status'] = "Datos ingresados incorrectamente, vuelva a intentar";
        header('location: crud_usuarios.php');
    }
}

// MOSTRAR DATOS
if (isset($_POST['click-view-btn'])) {

    $id = $_POST['id'];

    //echo $id;

    $fetch_query = "SELECT * FROM usuario WHERE `id` = '$id'";
    $fetch_query_run = mysqli_query($conn, $fetch_query);

    if (mysqli_num_rows($fetch_query_run) > 0) {
        while ($row = mysqli_fetch_array($fetch_query_run)) {
            echo '
                <h6> Id primaria: ' . $row['id'] . '</h6>
                <h6> Nombre de usuario: ' . $row['usuario'] . '</h6>
                <h6> Contraseña: ' . $row['contrasena'] . '</h6>
                <h6> Rol: ' . $row['rol'] . '</h6>
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

    $fetch_query = "SELECT * FROM usuario WHERE `id` = '$id'";
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

    $id = $_POST['id'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $rol = $_POST['rol'];

    $usuario = mysqli_real_escape_string($conn, $usuario);
    $contrasena = mysqli_real_escape_string($conn, $contrasena);
    $rol = mysqli_real_escape_string($conn, $rol);


    $update_query = "UPDATE usuario SET 
        usuario = '$usuario', 
        contrasena = '$contrasena', 
        rol = '$rol'   
        WHERE `id` = $id";
    $update_query_run = mysqli_query($conn, $update_query);

    if ($update_query_run) {

        $_SESSION['status'] = "Datos actualizados correctamente";
        header('location: crud_usuarios.php');
    } else {

        $_SESSION['status'] = "Los datos no se pudieron actualizar" . mysqli_error($conn);
        header('location: crud_usuarios.php');
    }
}


// ELIMINAR DATOS
if (isset($_POST['click-delete-btn'])) {

    $id = $_POST['id'];

    $delete_query = "DELETE FROM usuario WHERE id ='$id'";
    $delete_query_run = mysqli_query($conn, $delete_query);

    if ($delete_query_run) {

        echo "Datos eliminados correctamente";
    } else {

        echo "Los datos no se han podido eliminar";
    }
}
