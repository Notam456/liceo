<?php
$conn = include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
if (!$conn) {
    http_response_code(500);
    echo "Error de conexión";
    exit;
}

$id_seccion = $_POST['seccion'];
$dia = $_POST['dia'];
$hora = $_POST['hora'];

$horas_inicio = [
    0 => "07:20:00",
    1 => "08:10:00",
    2 => "08:50:00",
    3 => "09:05:00",
    4 => "09:45:00",
    5 => "10:25:00",
    6 => "10:30:00",
    7 => "11:45:00",
    8 => "12:10:00",
    9 => "12:50:00"
];

$horas_fin = [
    0 => "08:10:00",
    1 => "08:50:00",
    2 => "09:05:00",
    3 => "09:45:00",
    4 => "10:25:00",
    5 => "10:30:00",
    6 => "11:45:00",
    7 => "12:10:00",
    8 => "12:50:00",
    9 => "13:30:00"
];

$hora_inicio_real = $horas_inicio[$hora] ?? null;
$hora_fin_real = $horas_fin[$hora] ?? null;

if (!$hora_inicio_real || !$hora_fin_real) {
    http_response_code(400);
    echo "Horas inválidas";
    exit;
}

$query = "DELETE FROM horario 
          WHERE id_seccion = ? AND dia = ? AND hora_inicio = ? AND hora_fin = ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "isss", $id_seccion, $dia, $hora_inicio_real, $hora_fin_real);

if (mysqli_stmt_execute($stmt)) {
    echo "OK";
} else {
    echo "Error al eliminar";
}
