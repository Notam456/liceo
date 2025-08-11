<?php
session_start();

// Establecer conexión con la base de datos
include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Función para ejecutar consultas con manejo de errores
function executeQuery($conn, $query) {
    $result = mysqli_query($conn, $query);
    if ($result === false) {
        die("Error en la consulta: " . mysqli_error($conn) . "\nConsulta: " . $query);
    }
    return $result;
}

// Registrar nueva asistencia
if(isset($_POST['guardar_asistencia'])) {
    $fecha = mysqli_real_escape_string($conn, $_POST['fecha']);
    $seccion = mysqli_real_escape_string($conn, $_POST['seccion']);
    
    // Validar datos
    if(empty($fecha) || empty($seccion)) {
        $_SESSION['status'] = "Fecha y sección son requeridas";
        header("Location: crud_asistencia.php");
        exit();
    }
    
    // Recorremos todos los estudiantes y sus estados de asistencia
    if(isset($_POST['asistencia']) && is_array($_POST['asistencia'])) {
        foreach($_POST['asistencia'] as $id_estudiante => $datos) {
            $id_estudiante = mysqli_real_escape_string($conn, $id_estudiante);
            $estado = mysqli_real_escape_string($conn, $datos['estado']);
            $justificacion = isset($datos['justificacion']) ? mysqli_real_escape_string($conn, $datos['justificacion']) : '';
            
            $query = "INSERT INTO asistencia (id_estudiante, fecha, estado, justificacion) 
                      VALUES ('$id_estudiante', '$fecha', '$estado', '$justificacion')";
            executeQuery($conn, $query);
        }
        
        $_SESSION['status'] = "Asistencia registrada correctamente";
        header("Location: crud_asistencia.php");
        exit();
    } else {
        $_SESSION['status'] = "No se seleccionaron estudiantes";
        header("Location: crud_asistencia.php");
        exit();
    }
}

// Obtener estudiantes por sección (para AJAX)
if(isset($_POST['obtener_estudiantes'])) {
    $seccion = mysqli_real_escape_string($conn, $_POST['seccion']);
    
    $query = "SELECT id_estudiante, nombre_estudiante, apellido_estudiante 
              FROM estudiante 
              WHERE seccion_estudiante = '$seccion' 
              ORDER BY apellido_estudiante, nombre_estudiante";
    $result = executeQuery($conn, $query);
    
    if(mysqli_num_rows($result) > 0) {
        echo '<table class="table">';
        echo '<thead><tr><th>Estudiante</th><th>Estado</th><th>Justificación</th></tr></thead>';
        echo '<tbody>';
        
        while($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>'.htmlspecialchars($row['nombre_estudiante']).' '.htmlspecialchars($row['apellido_estudiante']).'</td>';
            echo '<td>';
            echo '<div class="form-check">';
            echo '<input class="form-check-input" type="radio" name="asistencia['.$row['id_estudiante'].'][estado]" value="P" checked> Presente<br>';
            echo '<input class="form-check-input" type="radio" name="asistencia['.$row['id_estudiante'].'][estado]" value="A"> Ausente<br>';
            echo '<input class="form-check-input justificado-radio" type="radio" name="asistencia['.$row['id_estudiante'].'][estado]" value="J"> Justificado';
            echo '</div>';
            echo '</td>';
            echo '<td>';
            echo '<textarea class="form-control justificado-note" name="asistencia['.$row['id_estudiante'].'][justificacion]" rows="2" style="display: none;"></textarea>';
            echo '</td>';
            echo '</tr>';
        }
        
        echo '</tbody></table>';
        
        // Script para manejar la visibilidad del campo de justificación
        echo '<script>
                $(document).ready(function() {
                    $(".justificado-radio").change(function() {
                        if($(this).is(":checked")) {
                            $(this).closest("tr").find(".justificado-note").show();
                        }
                    });
                    $("input[name^=\"asistencia[\"][value=\"P\"], input[name^=\"asistencia[\"][value=\"A\"]").change(function() {
                        $(this).closest("tr").find(".justificado-note").hide();
                    });
                });
              </script>';
    } else {
        echo '<p class="text-muted">No hay estudiantes en esta sección</p>';
    }
    exit();
}

// Filtrar asistencia (para AJAX)
if(isset($_POST['filtrar_asistencia'])) {
    $seccion = mysqli_real_escape_string($conn, $_POST['seccion']);
    $fecha = mysqli_real_escape_string($conn, $_POST['fecha']);
    
    $query = "SELECT a.id_asistencia, a.fecha, a.estado, a.justificacion, 
              e.nombre_estudiante, e.apellido_estudiante, e.seccion_estudiante
              FROM asistencia a
              JOIN estudiante e ON a.id_estudiante = e.id_estudiante
              WHERE 1=1";
    
    if(!empty($seccion)) {
        $query .= " AND e.seccion_estudiante = '$seccion'";
    }
    
    if(!empty($fecha)) {
        $query .= " AND a.fecha = '$fecha'";
    }
    
    $query .= " ORDER BY a.fecha DESC";
    
    $result = executeQuery($conn, $query);
    
    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $estado = '';
            switch($row['estado']) {
                case 'P': $estado = 'Presente'; break;
                case 'A': $estado = 'Ausente'; break;
                case 'J': $estado = 'Justificado'; break;
            }
            
            echo '<tr>';
            echo '<td style="display: none;">'.$row['id_asistencia'].'</td>';
            echo '<td>'.date('d/m/Y', strtotime($row['fecha'])).'</td>';
            echo '<td>'.$row['seccion_estudiante'].'</td>';
            echo '<td>'.$row['nombre_estudiante'].' '.$row['apellido_estudiante'].'</td>';
            echo '<td>'.$estado.'</td>';
            echo '<td>'.($row['justificacion'] ?: 'N/A').'</td>';
            echo '<td>';
            echo '<a href="#" class="btn btn-primary btn-sm edit-asistencia">Modificar</a> ';
            echo '<input type="hidden" class="delete_id_asistencia" value="'.$row['id_asistencia'].'">';
            echo '<a href="#" class="btn btn-danger btn-sm delete-asistencia">Eliminar</a>';
            echo '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="7">No hay registros de asistencia con estos filtros</td></tr>';
    }
    exit();
}

// Obtener datos de asistencia para editar (para AJAX)
if(isset($_POST['obtener_asistencia'])) {
    $id_asistencia = mysqli_real_escape_string($conn, $_POST['id_asistencia']);
    
    $query = "SELECT a.*, e.nombre_estudiante, e.apellido_estudiante
              FROM asistencia a
              JOIN estudiante e ON a.id_estudiante = e.id_estudiante
              WHERE a.id_asistencia = '$id_asistencia'";
    $result = executeQuery($conn, $query);
    
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row);
    }
    exit();
}

// Actualizar asistencia
if(isset($_POST['actualizar_asistencia'])) {
    $id_asistencia = mysqli_real_escape_string($conn, $_POST['id_asistencia']);
    $fecha = mysqli_real_escape_string($conn, $_POST['fecha']);
    $estado = mysqli_real_escape_string($conn, $_POST['estado']);
    $justificacion = ($estado == 'J') ? mysqli_real_escape_string($conn, $_POST['justificacion']) : '';
    
    $query = "UPDATE asistencia SET 
              fecha = '$fecha',
              estado = '$estado',
              justificacion = '$justificacion'
              WHERE id_asistencia = '$id_asistencia'";
    executeQuery($conn, $query);
    
    $_SESSION['status'] = "Asistencia actualizada correctamente";
    header("Location: crud_asistencia.php");
    exit();
}

// Eliminar asistencia
if(isset($_POST['eliminar_asistencia'])) {
    $id_asistencia = mysqli_real_escape_string($conn, $_POST['id_asistencia']);
    
    $query = "DELETE FROM asistencia WHERE id_asistencia = '$id_asistencia'";
    executeQuery($conn, $query);
    
    echo "Registro eliminado correctamente";
    exit();
}
?>