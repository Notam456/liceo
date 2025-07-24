<?php
include '../includes/db_connection.php';

$message = '';
$students = [];

// lista de estudiantes para el selector
$sql_students = "SELECT id, nombre, apellido FROM estudiantes ORDER BY apellido, nombre";
$result_students = $conn->query($sql_students);

if ($result_students === false) {
    $message = "Error al cargar la lista de estudiantes: " . $conn->error;
} else {
    if ($result_students->num_rows > 0) {
        while($row = $result_students->fetch_assoc()) {
            $students[] = $row;
        }
    } else {
        $message = "No hay estudiantes registrados. Por favor, añade estudiantes primero.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $estudiante_id = $_POST['estudiante_id'];
    $estado = $_POST['estado'];
    $comentarios = trim($_POST['comentarios']);
    $fecha = date('Y-m-d');
    $hora_entrada = date('H:i:s');

    if (empty($estudiante_id) || !is_numeric($estudiante_id)) {
        $message = "Error: Por favor, selecciona un estudiante válido.";
    } else {
       
        $check_sql = "SELECT id FROM asistencia WHERE estudiante_id = ? AND fecha = ?";
        $stmt_check = $conn->prepare($check_sql);
        if ($stmt_check === false) {
            $message = "Error en la preparación de la verificación: " . $conn->error;
        } else {
            $stmt_check->bind_param("is", $estudiante_id, $fecha);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                $message = "Ya existe un registro de asistencia para este estudiante hoy. Puedes modificarlo en 'Ver Reportes'.";
            } else {
                
                $stmt = $conn->prepare("INSERT INTO asistencia (estudiante_id, fecha, hora_entrada, estado, comentarios) VALUES (?, ?, ?, ?, ?)");
                if ($stmt === false) {
                    $message = "Error en la preparación de la inserción: " . $conn->error;
                } else {
                    $stmt->bind_param("issss", $estudiante_id, $fecha, $hora_entrada, $estado, $comentarios);

                    if ($stmt->execute()) {
                        $message = "Asistencia registrada correctamente.";
                    } else {
                        $message = "Error al registrar asistencia: " . $stmt->error;
                    }
                    $stmt->close();
                }
            }
            $stmt_check->close();
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
    <title>Registrar Asistencia</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Registrar Asistencia</h2>
        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form action="register_attendance.php" method="POST">
            <label for="estudiante_id">Estudiante:</label>
            <select id="estudiante_id" name="estudiante_id" required>
                <option value="">Selecciona un estudiante</option>
                <?php if (!empty($students)): ?>
                    <?php foreach ($students as $student): ?>
                        <option value="<?php echo htmlspecialchars($student['id']); ?>">
                            <?php echo htmlspecialchars($student['nombre'] . ' ' . $student['apellido']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select><br>

            <label for="estado">Estado:</label>
            <select id="estado" name="estado" required>
                <option value="Presente">Presente</option>
                <option value="Ausente">Ausente</option>
                <option value="Retardo">Retardo</option>
                <option value="Justificada">Ausencia Justificada</option>
            </select><br>

            <label for="comentarios">Comentarios (opcional):</label>
            <textarea id="comentarios" name="comentarios" rows="3"></textarea><br>

            <button type="submit">Registrar Asistencia</button>
        </form>
        <div class="navigation-links">
            <a href="index.php">Volver al Inicio</a>
        </div>
    </div>
</body>
</html>