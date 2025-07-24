<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


header("Access-Control-Allow-Origin: *"); 
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}


$servername = "localhost"; 
$username = "root";        
$password = "";            
$dbname = "liceo";         


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Error de conexión a la base de datos: " . $conn->connect_error]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get':
        handleGetRequest();
        break;
    case 'create':
        handlePostRequest();
        break;
    case 'update':
        handlePutRequest();
        break;
    case 'delete':
        handleDeleteRequest();
        break;
    default:
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Acción no válida o no especificada."]);
        break;
}


function handleGetRequest() {
    global $conn;
    $fecha = $_GET['fecha'] ?? null;
    $estudiante_id = $_GET['estudiante_id'] ?? null;

   
    $sql = "SELECT id, estudiante_id, fecha, estado, comentarios FROM asistencia";
    $conditions = [];
    $types = "";
    $params = [];

    if ($fecha) {
        $conditions[] = "fecha = ?";
        $types .= "s";
        $params[] = $fecha;
    }
    if ($estudiante_id) {
        $conditions[] = "estudiante_id = ?";
        $types .= "i";
        $params[] = $estudiante_id;
    }

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    $sql .= " ORDER BY fecha DESC, id DESC"; 

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo json_encode(["success" => false, "message" => "Error al preparar la consulta GET: " . $conn->error]);
        return;
    }
    if (!empty($params)) {
        $bind_params = array();
        $bind_params[] = &$types;
        for ($i = 0; $i < count($params); $i++) {
            $bind_params[] = &$params[$i];
        }
        call_user_func_array(array($stmt, 'bind_param'), $bind_params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $asistencias = [];
    while ($row = $result->fetch_assoc()) {
        $asistencias[] = $row;
    }
    $stmt->close();
    echo json_encode(["success" => true, "data" => $asistencias]);
}


function handlePostRequest() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    if (!is_array($data) || empty($data)) {
        echo json_encode(["success" => false, "message" => "Datos inválidos para la creación. Se esperaba un array de registros."]);
        return;
    }

    $conn->begin_transaction();
    try {
        
        $stmt = $conn->prepare("INSERT INTO asistencia (estudiante_id, fecha, estado, comentarios) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            throw new Exception("Error al preparar la consulta INSERT: " . $conn->error);
        }

        foreach ($data as $record) {
            $estudiante_id = $record['estudiante_id'] ?? null;
            $fecha = $record['fecha'] ?? null;
            $estado = $record['estado'] ?? null;
            $comentarios = $record['comentarios'] ?? null;

            if ($estudiante_id === null || $fecha === null || $estado === null) {
                throw new Exception("Datos incompletos para un registro de asistencia. Faltan estudiante_id, fecha o estado.");
            }
            
            $stmt->bind_param("isss", $estudiante_id, $fecha, $estado, $comentarios);
            $stmt->execute();
        }
        $stmt->close();
        $conn->commit();
        echo json_encode(["success" => true, "message" => "Asistencias registradas exitosamente."]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["success" => false, "message" => "Error al registrar asistencias: " . $e->getMessage()]);
    }
}


function handlePutRequest() {
    global $conn;
    $id = $_GET['id'] ?? null;
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$id || !is_array($data) || empty($data)) {
        echo json_encode(["success" => false, "message" => "ID o datos inválidos para la actualización."]);
        return;
    }

    $fecha = $data['fecha'] ?? null;
    $estado = $data['estado'] ?? null;
    $comentarios = $data['comentarios'] ?? null;
    $estudiante_id = $data['estudiante_id'] ?? null;

    if (!$fecha || !$estado || $estudiante_id === null) {
        echo json_encode(["success" => false, "message" => "Datos incompletos para la actualización (fecha, estado, o estudiante_id)."]);
        return;
    }

   
    $sql = "UPDATE asistencia SET estudiante_id = ?, fecha = ?, estado = ?, comentarios = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo json_encode(["success" => false, "message" => "Error al preparar la consulta UPDATE: " . $conn->error]);
        return;
    }

    
    $stmt->bind_param("isssi", $estudiante_id, $fecha, $estado, $comentarios, $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(["success" => true, "message" => "Asistencia actualizada exitosamente."]);
        } else {
            echo json_encode(["success" => false, "message" => "No se encontró el registro o no hubo cambios."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Error al actualizar asistencia: " . $stmt->error]);
    }
    $stmt->close();
}


function handleDeleteRequest() {
    global $conn;
    $id = $_GET['id'] ?? null;

    if (!$id) {
        echo json_encode(["success" => false, "message" => "ID no proporcionado para la eliminación."]);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM asistencia WHERE id = ?");
    if ($stmt === false) {
        echo json_encode(["success" => false, "message" => "Error al preparar la consulta DELETE: " . $conn->error]);
        return;
    }
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(["success" => true, "message" => "Asistencia eliminada exitosamente."]);
        } else {
            echo json_encode(["success" => false, "message" => "No se encontró el registro para eliminar."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Error al eliminar asistencia: " . $stmt->error]);
    }
    $stmt->close();
}

$conn->close();
?>
