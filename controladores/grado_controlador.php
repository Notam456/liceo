<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/grado_modelo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/anio_academico_modelo.php');

$gradoModelo = new gradoModelo($conn);
$anioModelo = new AnioAcademicoModelo($conn);


$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'crear':
        if (isset($_POST['save_data'])) {
            $anioActivo = mysqli_fetch_array($anioModelo->obtenerAnioActivo());
            if(empty($anioActivo)){
                $_SESSION['status'] = 'No existe un año académico activo. Ponte en contacto con el administrador';
                header('Location: /liceo/controladores/grado_controlador.php');
                exit();
            }
            try {
                $resultado = $gradoModelo->generarGrados($_POST['cantidad'], $anioActivo['id_anio']);
                switch ($resultado) {
                    case 'success':
                        $_SESSION['status'] =  "Grados generados correctamente";
                        break;
                    case 'muchos':
                        $_SESSION['status'] =  "Error al generar grados: La cantidad de grados totales es mayor a 6";
                        break;
                    case '1062':
                        $_SESSION['status'] = "Error al crear los grados: grados duplicados";
                        break;
                    default:
                        $_SESSION['status'] = $resultado;
                }
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                    $_SESSION['status'] = "Error al crear los grados: grados duplicados";
                } else {
                    $_SESSION['status'] = "Error al crear los grados: " . $e->getMessage();
                }
                $_SESSION['form_data'] = $_POST;
            }

        
            header('Location: /liceo/controladores/grado_controlador.php');
            exit();
        }
        break;

    case 'ver':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $gradoModelo->obtenerGradoPorId($id);
            if (mysqli_num_rows($resultado) > 0) {
                $row = mysqli_fetch_array($resultado);
                
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/grado_modal_view.php');
            } else {

                $row = [];
                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/grado_modal_view.php');
            }
        }
        break;

    case 'editar':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultado = $gradoModelo->obtenerGradoPorId($id);
            $data = [];
            while($row = mysqli_fetch_assoc($resultado)) {
                $data[] = $row;
            }
            header('Content-Type: application/json');
            echo json_encode($data);
        }
        break;

    case 'actualizar':
        if (isset($_POST['update-data'])) {
            $id = $_POST['idEdit'];
            $numero_anio = $_POST['numero_anio_edit'];
            try {
                $resultado = $gradoModelo->actualizarGrado($id, $numero_anio);
                $_SESSION['status'] = $resultado ? "Datos actualizados correctamente" : "No se pudieron actualizar los datos";
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                    $_SESSION['status'] = "Ya existe un grado con esos datos";
                } else {
                    $_SESSION['status'] = "Error al actualizar el grado: " . $e->getMessage();
                }
                $_SESSION['form_data'] = $_POST;
            }
            header('Location: /liceo/controladores/grado_controlador.php');
            exit();
        }
        break;

    case 'eliminar':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            try {
                $resultado = $gradoModelo->eliminarGrado($id);
                echo $resultado ? "Datos eliminados correctamente" : "Los datos no se han podido eliminar";
            } catch (mysqli_sql_exception $e) {
                echo "Error al eliminar: " . $e->getMessage();
            }
        }
        break;

    case 'listar':
    default:
        $materias = $gradoModelo->obtenerTodosLosGrados();
        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/grado_vista.php');
        break;
}
?>
