<?php
include '../includes/db_connection.php';

$students = [];
$message = ''; 

if (isset($_GET['message'])) {
    $message = htmlspecialchars(urldecode($_GET['message']));
}

$search_term = '';
$search_grado = '';
$search_seccion = '';

// filtro de búsqueda
$sql = "SELECT id, nombre, apellido, grado, seccion, fecha_nacimiento, email, fecha_registro FROM estudiantes WHERE 1";
$params = [];
$types = '';

if (isset($_GET['search_term']) && $_GET['search_term'] != '') {
    $search_term = trim($_GET['search_term']);
    $sql .= " AND (nombre LIKE ? OR apellido LIKE ? OR email LIKE ?)";
    $params[] = '%' . $search_term . '%';
    $params[] = '%' . $search_term . '%';
    $params[] = '%' . $search_term . '%';
    $types .= 'sss';
}

if (isset($_GET['search_grado']) && $_GET['search_grado'] != '') {
    $search_grado = trim($_GET['search_grado']);
    $sql .= " AND grado = ?";
    $params[] = $search_grado;
    $types .= 's';
}

if (isset($_GET['search_seccion']) && $_GET['search_seccion'] != '') {
    $search_seccion = trim($_GET['search_seccion']);
    $sql .= " AND seccion = ?";
    $params[] = $search_seccion;
    $types .= 's';
}

$sql .= " ORDER BY apellido, nombre";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    $message = "Error en la preparación de la consulta: " . $conn->error;
} else {
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    }
    $stmt->close();
}

$grados_unicos = [];
$secciones_unicas = [];

$res_grados = $conn->query("SELECT DISTINCT grado FROM estudiantes WHERE grado IS NOT NULL AND grado != '' ORDER BY grado");
if ($res_grados) {
    while($row = $res_grados->fetch_assoc()) {
        $grados_unicos[] = $row['grado'];
    }
} else {
    
}

$res_secciones = $conn->query("SELECT DISTINCT seccion FROM estudiantes WHERE seccion IS NOT NULL AND seccion != '' ORDER BY seccion");
if ($res_secciones) {
    while($row = $res_secciones->fetch_assoc()) {
        $secciones_unicas[] = $row['seccion'];
    }
} else {
   
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Estudiantes</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function confirmDelete(studentName) {
            return confirm("¿Estás seguro de que deseas eliminar al estudiante " + studentName + "? Esto también eliminará todos sus registros de asistencia.");
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Lista de Estudiantes</h2>
        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="view_students.php" method="GET" class="filter-form">
            <label for="search_term">Buscar por Nombre/Apellido/Email:</label>
            <input type="text" id="search_term" name="search_term" value="<?php echo htmlspecialchars($search_term); ?>">

            <label for="search_grado">Filtrar por Grado:</label>
            <select id="search_grado" name="search_grado">
                <option value="">Todos los Grados</option>
                <?php foreach ($grados_unicos as $grado): ?>
                    <option value="<?php echo htmlspecialchars($grado); ?>" <?php echo ($search_grado == $grado) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($grado); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="search_seccion">Filtrar por Sección:</label>
            <select id="search_seccion" name="search_seccion">
                <option value="">Todas las Secciones</option>
                <?php foreach ($secciones_unicas as $seccion): ?>
                    <option value="<?php echo htmlspecialchars($seccion); ?>" <?php echo ($search_seccion == $seccion) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($seccion); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Aplicar Filtro</button>
            <a href="view_students.php" class="button-reset">Limpiar Filtros</a>
        </form>
        <br>

        <?php if (empty($students)): ?>
            <div class="message success">
                <p>No se encontraron estudiantes con los criterios de búsqueda.</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Grado</th>
                        <th>Sección</th>
                        <th>Fecha Nac.</th>
                        <th>Email</th>
                        <th>Reg. Desde</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['id']); ?></td>
                            <td><?php echo htmlspecialchars($student['nombre'] . ' ' . $student['apellido']); ?></td>
                            <td><?php echo htmlspecialchars($student['grado'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($student['seccion'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($student['fecha_nacimiento'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($student['email'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($student['fecha_registro']); ?></td>
                            <td>
                                <a href="delete_student.php?id=<?php echo htmlspecialchars($student['id']); ?>"
                                   onclick="return confirmDelete('<?php echo htmlspecialchars($student['nombre'] . ' ' . $student['apellido']); ?>');"
                                   class="delete-link">Eliminar</a>
                            </td>
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