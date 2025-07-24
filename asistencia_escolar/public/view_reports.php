<?php
include '../includes/db_connection.php';

$reports = [];
$message = ''; 

$sql = "SELECT a.id, e.nombre, e.apellido, a.fecha, a.hora_entrada, a.hora_salida, a.estado, a.comentarios
        FROM asistencia a
        JOIN estudiantes e ON a.estudiante_id = e.id
        ORDER BY a.fecha DESC, a.hora_entrada DESC";

$result = $conn->query($sql);

if ($result === false) {
    $error_message = "Error al cargar los reportes: " . $conn->error;
} else {
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $reports[] = $row;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Asistencia</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Reportes de Asistencia</h2>
        <?php if (isset($error_message)): ?>
            <div class="message error">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php elseif (empty($reports)): ?>
            <div class="message success">
                <p>No hay registros de asistencia aún.</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Reg.</th>
                        <th>Estudiante</th>
                        <th>Fecha</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Estado</th>
                        <th>Comentarios</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reports as $report): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($report['id']); ?></td>
                            <td><?php echo htmlspecialchars($report['nombre'] . ' ' . $report['apellido']); ?></td>
                            <td><?php echo htmlspecialchars($report['fecha']); ?></td>
                            <td><?php echo htmlspecialchars($report['hora_entrada'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($report['hora_salida'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($report['estado']); ?></td>
                            <td><?php echo htmlspecialchars($report['comentarios'] ?? 'N/A'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <div class="navigation-links">
            <a href="index.php">Volver al Inicio</a>
        </div>
    </div>
</body>
</html>