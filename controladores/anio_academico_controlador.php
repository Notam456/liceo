<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/anio_academico_modelo.php');
$anioAcademicoModelo = new AnioAcademicoModelo($conn);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'crear':
        if (isset($_POST['save_data'])) {
            $desde = $_POST['inicio'];
            $hasta = $_POST['fin'];
            $resultado = $anioAcademicoModelo->crearAnioAcademico($desde, $hasta);
            $_SESSION['status'] = $resultado ? "Año académico creado correctamente" : "Error al crear el año académico";
            header('Location: /liceo/controladores/anio_controlador.php');
            exit();
        }
        break;

    case 'ver':
        if (isset($_POST['id_anio'])) {
            $id = $_POST['id_anio'];
            $resultado = $anioAcademicoModelo->obtenerAnioAcademicoPorId($id);
            if (mysqli_num_rows($resultado) > 0) {
                $row = mysqli_fetch_array($resultado);
                
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/anio_modal_view.php');
            } else {

                $row = [];
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/anio_modal_view.php');
            }
        }
        break; 

    case 'editar':
        if (isset($_POST['id_anio'])) {
            $id = $_POST['id_anio'];
            $resultado = $anioAcademicoModelo->obtenerAnioAcademicoPorId($id);
            $data = [];
            if (mysqli_num_rows($resultado) > 0) {
                while($row = mysqli_fetch_assoc($resultado)) {
                    $data[] = $row;
                }
            }
            header('Content-Type: application/json');
            echo json_encode($data);
        }
        break;

    case 'actualizar':
        if (isset($_POST['update-data'])) {
            $id = $_POST['id_anio'];
            $desde = $_POST['inicio'];
            $hasta = $_POST['fin'];
            $resultado = $anioAcademicoModelo->actualizarAnioAcademico($id, $desde, $hasta);
            $_SESSION['status'] = $resultado ? "Datos actualizados correctamente" : "No se pudieron actualizar los datos";
            header('Location: /liceo/controladores/anio_controlador.php');
            exit();
        }
        break;

    case 'eliminar':
        if (isset($_POST['id_anio'])) {
            $id = $_POST['id_anio'];
            $resultado = $anioAcademicoModelo->eliminarAnioAcademico($id);
            if ($resultado) {
                echo "Datos eliminados correctamente";
            } else {
                echo "Los datos no se han podido eliminar";
            }
        }
        break;

    case 'establecerActivo':
        if (isset($_POST['id_anio'])){
            $id = $_POST['id_anio'];
            $resultado = $anioAcademicoModelo->establecerAnioActivo($id);
            
            if ($resultado['resultado'] && isset($_SESSION['id_usuario'])) {
                // Registrar desactivación del año anterior si existía
                if ($resultado['anio_anterior'] && $resultado['anio_anterior'] != $id) {
                    $anioAcademicoModelo->registrarLogAnio($resultado['anio_anterior'], $_SESSION['id_usuario'], 'desactivar');
                }
                
                // Registrar activación del nuevo año
                $anioAcademicoModelo->registrarLogAnio($id, $_SESSION['id_usuario'], 'activar');
                
            }
            
            $_SESSION['status'] = $resultado['resultado'] ? "Año activo actualizado correctamente" : "No se pudo actualizar el año activo";
        }
        break;

    case 'historialLogs':
        $filtro_usuario = isset($_GET['filtro_usuario']) ? $_GET['filtro_usuario'] : null;
        $filtro_anio = isset($_GET['filtro_anio']) ? $_GET['filtro_anio'] : null;
        $filtro_accion = isset($_GET['filtro_accion']) ? $_GET['filtro_accion'] : null;
        
        $historial_logs = $anioAcademicoModelo->obtenerHistorialLogs($filtro_usuario, $filtro_anio, $filtro_accion);
        $usuarios_filtro = $anioAcademicoModelo->obtenerUsuariosParaFiltro();
        $anios_filtro = $anioAcademicoModelo->obtenerAniosParaFiltro();
        
        if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
            // Respuesta AJAX para actualizar solo la tabla
            include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/historial_logs_tabla.php');
        } else {
            // Cargar la vista completa
            include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/anio_academico_vista.php');
        }
        break;

    case 'listar':
    default:
        $anios_academicos = $anioAcademicoModelo->obtenerTodosLosAniosAcademicos();
        
        // Cargar datos para los filtros del historial
        $usuarios_filtro = $anioAcademicoModelo->obtenerUsuariosParaFiltro();
        $anios_filtro = $anioAcademicoModelo->obtenerAniosParaFiltro();
        
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/anio_academico_vista.php');
        break;
}
?>
