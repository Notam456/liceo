<?php

session_start();
$conn = mysqli_connect("localhost", "root", "", "liceo");

if (isset($_POST)) {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $fetch_query = "SELECT * FROM usuario WHERE `usuario` = '$usuario'";
    $fetch_query_run = mysqli_query($conn, $fetch_query);
    $row = mysqli_fetch_array($fetch_query_run);

    if (mysqli_num_rows($fetch_query_run) > 0 && $row['contrasena'] == $contrasena) {
        $_SESSION['usuario'] = $row['usuario'];
        header("Location: main.php");
    } else if (mysqli_num_rows($fetch_query_run) == 0) {
        $_SESSION['status'] = 'No se ha encontrado el usuario';
        header("Location: index.php");
    } else if ($row['contrasena'] != $contrasena) {
         $_SESSION['status'] = 'Contraseña incorrecta';
         header("Location: index.php");
    }
}