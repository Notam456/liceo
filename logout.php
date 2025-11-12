<?php
session_start();

// Guardamos el motivo antes de destruir la sesión
$motivo = isset($_GET['motivo']) ? $_GET['motivo'] : null;

// Cerrar sesión
session_unset();
session_destroy();

// Redirigir a index con mensaje si fue por inactividad
if ($motivo === 'inactividad') {
    header("Location: index.php?mensaje=inactividad");
} else {
    header("Location: index.php");
}
exit;
?>