<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/seccion_modelo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/grado_modelo.php');

$seccionModelo = new SeccionModelo($conn);
$gradoModelo = new GradoModelo($conn);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'listar';

switch ($action) {
    case 'crear':
        if (isset($_POST['save_data'])) {
            $resultado = $seccionModelo->generarSecciones($_POST['cantidad'], $_POST['grado']);
            if ($resultado) {
                $_SESSION['status'] = "Sección creada correctamente";
            } else {
                $_SESSION['status'] = "Esta sección ya existe, vuelva a intentarlo";
            }
            header('Location: /liceo/controladores/seccion_controlador.php');
            exit();
        }
        break;

    case 'ver':
        if (isset($_POST['id_seccion'])) {
            $id = $_POST['id_seccion'];
            $resultado = $seccionModelo->obtenerSeccionPorId($id);
            if ($row = mysqli_fetch_array($resultado)) {
                echo '<h6> Id primaria: ' . $row['id_seccion'] . '</h6>
                      <h6> Nombre de la sección: ' . $row['numero_anio'] . '° '.$row['letra'].'</h6>
                      <h6> Año de la sección: ' . $row['numero_anio'] . '° año </h6>
                      <a class="btn btn-primary" href="/liceo/controladores/estudiante_controlador.php?action=listarPorSeccion&id_seccion=' . $row['id_seccion'] . '" role="button">Ver listado de estudiantes</a>
                      <a class="btn btn-primary" href="/liceo/controladores/horario_controlador.php?secc=' .  $row['id_seccion'] . '" role="button">Crear/modificar Horario</a>';
            } else {
                echo '<h4>No se han encontrado datos</h4>';
            }
        }
        break;

    case 'editar':
        if (isset($_POST['id_seccion'])) {
            $id = $_POST['id_seccion'];
            $resultado = $seccionModelo->obtenerSeccionPorId($id);
            $data = [];
            while ($row = mysqli_fetch_assoc($resultado)) {
                $data[] = $row;
            }
            header('Content-Type: application/json');
            echo json_encode($data);
        }
        break;

    case 'actualizar':
        if (isset($_POST['update-data'])) {
            $id = $_POST['idEdit'];
            $nombre = $_POST['nombreEdit'];
            $anio = $_POST['añoEdit'];
            $resultado = $seccionModelo->actualizarSeccion($id, $nombre, $anio);
            if ($resultado) {
                $_SESSION['status'] = "Datos actualizados correctamente";
            } else {
                $_SESSION['status'] = "Esta sección ya existe, vuelva a intentarlo";
            }
            header('Location: /liceo/controladores/seccion_controlador.php');
            exit();
        }
        break;

    case 'eliminar':
        if (isset($_POST['id_seccion'])) {
            $id = $_POST['id_seccion'];
            $resultado = $seccionModelo->eliminarSeccion($id);
            echo $resultado ? "Datos eliminados correctamente" : "Los datos no se han podido eliminar";
        }
        break;

    case 'listar':
    default:
        $secciones = $seccionModelo->obtenerTodasLasSecciones();
        $horarios_status = [];
        if ($secciones) {
            $secciones_copy = [];
            while ($row = $secciones->fetch_assoc()) {
                $secciones_copy[] = $row;
            }
            foreach ($secciones_copy as $row) {
                
                $horario_result = $seccionModelo->obtenerHorarioPorSeccion($row['id_seccion']);
                $horarios_status[$row['id_seccion']] = (mysqli_num_rows($horario_result) > 0);
            }
        }

        include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/seccion_vista.php');
        break;
}
