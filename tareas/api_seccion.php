<?php
require_once '../conexion.php';
session_start();
$id_usuario = $_SESSION['id_usuario'];
date_default_timezone_set("America/Argentina/Buenos_Aires");
$fecha_actual = date('Y-m-d H:i');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $accion = $_GET['accion'];
    $offset = isset($_GET['offset']) ? $_GET['offset'] : 0; 
    $limit = 5; 

    if ($accion === 'completadas') {
        $traerTareasCompletadas = $conn->query("SELECT * FROM tareas WHERE id_usuario = '$id_usuario'
        AND completada != 'no' LIMIT $limit OFFSET $offset");
        $tareasCompletadas = $traerTareasCompletadas->fetchAll();
        $totalFilas = $conn->query("SELECT COUNT(*) FROM tareas WHERE id_usuario = '$id_usuario' AND completada != 'no'")->fetchColumn();
        echo json_encode(["tareas" => $tareasCompletadas, "limit" => $limit, "totalFilas" => $totalFilas]);
    } elseif ($accion === 'vencidas') {
        $traerTareasVencidas = $conn->query("SELECT * FROM tareas WHERE id_usuario = '$id_usuario'
        AND completada = 'no' AND fecha_limite < '$fecha_actual' LIMIT $limit OFFSET $offset");
        $tareasVencidas = $traerTareasVencidas->fetchAll();
        $totalFilas = $conn->query("SELECT COUNT(*) FROM tareas WHERE id_usuario = '$id_usuario' AND completada = 'no' AND fecha_limite < '$fecha_actual'")->fetchColumn();
        echo json_encode(["tareas" => $tareasVencidas, "limit" => $limit, "totalFilas" => $totalFilas]);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Acción no válida']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id_tarea = $data['id'];

    $conn->query("DELETE FROM tareas WHERE id_tarea = '$id_tarea'");

    echo json_encode(["message" => "Tarea eliminada con éxito"]);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
?>