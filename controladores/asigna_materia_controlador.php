<?php
// Habilitar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar sesión
session_start();

// Incluir archivo de configuración de base de datos
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');


// Incluir modelo
$model_path = $_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/asigna_materia_modelo.php';
if (file_exists($model_path)) {
    require_once($model_path);
} else {
    die("Error: No se encontró el archivo del modelo");
}

// Crear instancia del modelo
$modelo = new AsignaMateriaModelo($conn);

// Manejar acciones
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_profesor = $_POST['id_profesor'] ?? '';
            $id_materia = $_POST['id_materia'] ?? '';
            
            if (empty($id_profesor) || empty($id_materia)) {
                $_SESSION['status'] = 'Error: Debe seleccionar profesor y materia';
                $_SESSION['show_modal'] = true;
            } else {
                if ($modelo->crearAsignacion($id_profesor, $id_materia)) {
                    $_SESSION['status'] = '✅ Materia asignada exitosamente al profesor';
                } else {
                    $_SESSION['status'] = '⚠️ Error: Esta asignación ya existe';
                    $_SESSION['show_modal'] = true;
                }
            }
            // Mantener parámetros de paginación en la redirección
            $por_pagina = $_GET['por_pagina'] ?? 10;
            $pagina = $_GET['pagina'] ?? 1;
            header('Location: asigna_materia_controlador.php?pagina=' . $pagina . '&por_pagina=' . $por_pagina);
            exit();
        }
        break;

    case 'eliminar':
        if (isset($_GET['id'])) {
            if ($modelo->eliminarAsignacion($_GET['id'])) {
                $_SESSION['status'] = '✅ Asignación eliminada exitosamente';
            } else {
                $_SESSION['status'] = '❌ Error al eliminar la asignación';
            }
            // Mantener parámetros de paginación en la redirección
            $por_pagina = $_GET['por_pagina'] ?? 10;
            $pagina = $_GET['pagina'] ?? 1;
            header('Location: asigna_materia_controlador.php?pagina=' . $pagina . '&por_pagina=' . $por_pagina);
            exit();
        }
        break;
}

// Obtener datos para la vista
try {
    $asignaciones = $modelo->obtenerAsignaciones();
    $profesores = $modelo->obtenerProfesores();
    $materias = $modelo->obtenerMaterias();
} catch (Exception $e) {
    die("Error al obtener datos: " . $e->getMessage());
}

// Incluir vista
$vista_path = $_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/asigna_materia_vista.php';
if (file_exists($vista_path)) {
    include($vista_path);
} else {
    die("Error: No se encontró el archivo de vista en: " . $vista_path);
}
?>