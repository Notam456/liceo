<?php

session_start();
$conn = mysqli_connect("localhost", "root", "", "liceo");

// INSERTAR DATOS
if (isset($_POST['save_data'])) {
    $nombre = $_POST['año'] . "°" . $_POST['nombre'];
    $año = $_POST['año'];


    $insert_query = "INSERT INTO 
    seccion(nombre, año) 
    VALUES ('$nombre', '$año')";

    try {
        $insert_query_run = mysqli_query($conn, $insert_query);
        $_SESSION['status'] = "Datos ingresados correctamente";
        header('location: crud_secciones.php');
    } catch (Exception $e) {
        if (strpos($e, 'Duplicate entry') !== false) {
            $_SESSION['status'] = "Esta sección ya existe, vuelva a intentarlo";
        } else {
            $_SESSION['status'] = "Datos ingresados incorrectamente, vuelva a intentar";
        }
        header('location: crud_secciones.php');
    }
}

// MOSTRAR DATOS
if (isset($_POST['click-view-btn'])) {

    $id = $_POST['id_seccion'];

    //echo $id;

    $fetch_query = "SELECT * FROM seccion WHERE id_seccion = '$id'";
    $fetch_query_run = mysqli_query($conn, $fetch_query);

    if (mysqli_num_rows($fetch_query_run) > 0) {
        while ($row = mysqli_fetch_array($fetch_query_run)) {
            echo '
                <h6> Id primaria: ' . $row['id_seccion'] . '</h6>
                <h6> Nombre de la sección: ' . $row['nombre'] . '</h6>
                <h6> Año de la sección: ' . $row['año'] . '</h6>
                <a
                    name=""
                    id=""
                    class="btn btn-primary"
                    href="#"
                    role="button"
                    >Ver listado de estudiantes</a
                >
                <a
                    name=""
                    id=""
                    class="btn btn-primary"
                    href="../horario/construct_horario.php?secc=' .  $row['id_seccion'] . '"
                    role="button"
                    >Crear/modificar Horario</a
                >
            ';
        }
    } else {
        echo '<h4>no se han encontrado datos</h4>';
    }
}

// EDITAR DATOS
if (isset($_POST['click-edit-btn'])) {

    $id = $_POST['id_seccion'];
    $array_result = [];

    //echo $id;

    $fetch_query = "SELECT * FROM seccion WHERE id_seccion = '$id'";
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
    $nombre = $_POST['añoEdit'] . "°" . $_POST['nombreEdit'];
    $año = $_POST['añoEdit'];

    $nombre = mysqli_real_escape_string($conn, $nombre);
    $año = mysqli_real_escape_string($conn, $año);


    $update_query = "UPDATE seccion SET 
        nombre = '$nombre',  
        año = '$año'
        WHERE id_seccion = $id";
    try {
        $update_query_run = mysqli_query($conn, $update_query);
        $_SESSION['status'] = "Datos ingresados correctamente";
        header('location: crud_secciones.php');
    } catch (Exception $e) {
        if (strpos($e, 'Duplicate entry') !== false) {
            $_SESSION['status'] = "Esta sección ya existe, vuelva a intentarlo";
        } else {
            $_SESSION['status'] = "Datos ingresados incorrectamente, vuelva a intentar";
        }
        header('location: crud_secciones.php');
    }
}


// ELIMINAR DATOS
if (isset($_POST['click-delete-btn'])) {

    $id = $_POST['id_seccion'];

    $delete_query = "DELETE FROM seccion WHERE id_seccion ='$id'";
    $delete_query_run = mysqli_query($conn, $delete_query);

    if ($delete_query_run) {

        echo "Datos eliminados correctamente";
    } else {

        echo "Los datos no se han podido eliminar";
    }
}
