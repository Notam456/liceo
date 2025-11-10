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
$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

switch ($action) {
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_profesor = isset($_POST['id_profesor']) ? $_POST['id_profesor'] : '';
            $id_materia = isset($_POST['id_materia']) ? $_POST['id_materia'] : '';
            
            if (empty($id_profesor) || empty($id_materia)) {
                $_SESSION['status'] = 'Error: Debe seleccionar profesor y materia';
                $_SESSION['show_modal'] = true;
            } else {
                if ($modelo->crearAsignacion($id_profesor, $id_materia)) {
                    $_SESSION['status'] = 'Materia asignada exitosamente al profesor';
                } else {
                    $_SESSION['status'] = 'Error: Esta asignación ya existe';
                    $_SESSION['show_modal'] = true;
                }
            }
            // Mantener parámetros de paginación en la redirección
            $por_pagina = isset($_GET['por_pagina']) ? $_GET['por_pagina'] : 10;
            $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
            header('Location: asigna_materia_controlador.php?pagina=' . $pagina . '&por_pagina=' . $por_pagina);
            exit();
        }
        break;

    case 'eliminar':
        if (isset($_GET['id'])) {

            $id_asignacion = isset($_POST['id_asignacion']) ? $_POST['id_asignacion'] : $_GET['id'];

            if ($modelo->eliminarAsignacion($_GET['id'])) {
                $_SESSION['status'] = 'Asignación eliminada exitosamente';
            } else {
                $_SESSION['status'] = 'Error al eliminar la asignación';
            }
            // Mantener parámetros de paginación en la redirección
            $por_pagina = isset($_GET['por_pagina']) ? $_GET['por_pagina'] : 10;
            $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
            header('Location: asigna_materia_controlador.php?pagina=' . $pagina . '&por_pagina=' . $por_pagina);
            exit();
        }
        break;

    case 'ver':
        if (isset($_POST['id_asignacion'])) {
            $id = $_POST['id_asignacion'];
            $resultado = $modelo->obtenerAsignacionPorId($id);
            if ($resultado) {
                $row = $resultado;
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/asigna_materia_modal_view.php');
            } else {
                $row = [];
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/asigna_materia_modal_view.php');
            }
            exit();
        }
        break;

    case 'editar':
        if (isset($_POST['id_asignacion'])) {
            $asignacion = $modelo->obtenerAsignacionPorId($_POST['id_asignacion']);
            if ($asignacion) {
                echo json_encode([$asignacion]);
            } else {
                echo json_encode([]);
            }
            exit();
        }
        break;

    case 'actualizar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_asignacion = isset($_POST['id_asignacion']) ? $_POST['id_asignacion'] : '';
            $id_profesor = isset($_POST['id_profesor']) ? $_POST['id_profesor'] : '';
            $id_materia = isset($_POST['id_materia']) ? $_POST['id_materia'] : '';

            if (empty($id_asignacion) || empty($id_profesor) || empty($id_materia)) {
                $_SESSION['status'] = 'Error: Debe completar todos los campos';
            } else {
                if ($modelo->actualizarAsignacion($id_asignacion, $id_profesor, $id_materia)) {
                    $_SESSION['status'] = 'Asignación actualizada exitosamente';
                } else {
                    $_SESSION['status'] = 'Error: No se pudo actualizar la asignación';
                }
            }
            // Mantener parámetros de paginación en la redirección
            $por_pagina = isset($_GET['por_pagina']) ? $_GET['por_pagina'] : 10;
            $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
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