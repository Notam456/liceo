<?php
include '../includes/db_connection.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $grado = trim($_POST['grado']);
    $fecha_nacimiento = trim($_POST['fecha_nacimiento']);
    $email = trim($_POST['email']);
    $seccion = trim($_POST['seccion']); 


    if (empty($nombre) || empty($apellido)) {
        $message = "Error: Nombre y Apellido son campos obligatorios.";
    } else {
        
        $stmt = $conn->prepare("INSERT INTO estudiantes (nombre, apellido, grado, fecha_nacimiento, email, seccion) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            $message = "Error en la preparación de la consulta: " . $conn->error;
        } else {
            
            $stmt->bind_param("ssssss", $nombre, $apellido, $grado, $fecha_nacimiento, $email, $seccion);

            if ($stmt->execute()) {
                $message = "Estudiante '$nombre $apellido' añadido correctamente.";
            } else {
                if ($conn->errno == 1062) { 
                    $message = "Error: El correo electrónico '$email' ya está registrado.";
                } else {
                    $message = "Error al añadir estudiante: " . $stmt->error;
                }
            }
            $stmt->close();
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
    <title>Añadir Estudiante</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Añadir Nuevo Estudiante</h2>
        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form action="add_student.php" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required><br>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required><br>

            <label for="grado">Grado:</label>
            <input type="text" id="grado" name="grado"><br>

            <label for="seccion">Sección:</label> <input type="text" id="seccion" name="seccion"><br>

            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email"><br>

            <button type="submit">Añadir Estudiante</button>
        </form>
        <div class="navigation-links">
            <a href="index.php">Volver al Inicio</a>
        </div>
    </div>
</body>
</html>