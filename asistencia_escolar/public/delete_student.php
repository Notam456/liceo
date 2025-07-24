<?php
// Incluir el archivo de conexión
include '../includes/db_connection.php';

$message = '';

// Verificar si se ha enviado el ID del estudiante a eliminar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $student_id = $_GET['id'];

    // Preparar la consulta SQL para eliminar al estudiante
    // La cláusula ON DELETE CASCADE en la tabla 'asistencia' se encargará de eliminar los registros de asistencia asociados.
    $stmt = $conn->prepare("DELETE FROM estudiantes WHERE id = ?");

    if ($stmt === false) {
        $message = "Error en la preparación de la consulta: " . $conn->error;
    } else {
        $stmt->bind_param("i", $student_id); // 'i' indica que $student_id es un entero

        if ($stmt->execute()) {
            // Verificar si se eliminó alguna fila
            if ($stmt->affected_rows > 0) {
                $message = "Estudiante eliminado correctamente.";
            } else {
                $message = "Error: No se encontró el estudiante con ID " . htmlspecialchars($student_id) . ".";
            }
        } else {
            $message = "Error al eliminar estudiante: " . $stmt->error;
        }
        $stmt->close();
    }
} else {
    $message = "Error: ID de estudiante no proporcionado para la eliminación.";
}

$conn->close(); // Cerrar la conexión

// Redirigir de vuelta a la página de lista de estudiantes con un mensaje
header("Location: view_students.php?message=" . urlencode($message));
exit();
?>