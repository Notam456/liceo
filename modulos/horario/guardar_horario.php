<?php
$conn = include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
if (!$conn) {
    http_response_code(500);
    echo "Error de conexión";
    exit;
}


$id_seccion = $_POST['seccion'];
$dia = $_POST['dia'];
$hora_inicio = $_POST['inicio'];
$hora_fin = $_POST['fin'];
$id_materia = $_POST['materia'];
$id_profesor = $_POST['profesor'];

echo $id_seccion;
echo $dia;
echo $hora_inicio;
echo $hora_fin;
echo $id_materia;
echo $id_profesor;

// Convertimos el número a hora (asumiendo tabla base)
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
    9 => "12:50:00",
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

$hora_inicio_real = $horas_inicio[$hora_inicio] ?? "00:00:00";
$hora_fin_real = $horas_fin[$hora_fin] ?? "00:00:00";

$query = "INSERT INTO horario (id_seccion, dia, hora_inicio, hora_fin, id_materia, id_profesores) 
          VALUES (?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "isssii", $id_seccion, $dia, $hora_inicio_real, $hora_fin_real, $id_materia, $id_profesor);

if (mysqli_stmt_execute($stmt)) {
    echo mysqli_insert_id($conn);
} else {
    echo "Error al guardar";
}
