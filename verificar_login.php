<?php

session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');


if (isset($_POST)) {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $fetch_query = "SELECT u.*, CONCAT(p.nombre, ' ', p.apellido) AS profesor FROM usuario u LEFT JOIN profesor p ON u.id_profesor = p.id_profesor WHERE `usuario` = '$usuario'";
    $fetch_query_run = mysqli_query($conn, $fetch_query);
    $row = mysqli_fetch_array($fetch_query_run);
    if (mysqli_num_rows($fetch_query_run) > 0 && $row['contrasena'] == $contrasena) {
        $_SESSION['id_usuario'] = $row['id_usuario'];
        $_SESSION['rol'] = $row['rol'];
        $_SESSION['profesor'] = !isset($row['id_profesor']) ? 'Administrador' : $row['id_profesor'];
        $_SESSION['nombre_prof'] =!isset($row['id_profesor']) ? 'Administrador' : $row['profesor'];
        $_SESSION['usuario'] = $row['usuario'];
    } else if (mysqli_num_rows($fetch_query_run) == 0) {
        $_SESSION['status'] = 'No se ha encontrado el usuario';
        header("Location: index.php");
    } else if ($row['contrasena'] != $contrasena) {
        $_SESSION['status'] = 'Contraseña incorrecta';
        header("Location: index.php");
    }
}
