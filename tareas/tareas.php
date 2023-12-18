<?php
require_once '../conexion.php';
session_start();
$id_usuario = $_SESSION['id_usuario'];
date_default_timezone_set("America/Argentina/Buenos_Aires");
$fecha_actual = date('Y-m-d H:i', strtotime('+1 hour, -1 minute'));

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $traerTareas = $conn->query("SELECT * FROM tareas WHERE id_usuario = '$id_usuario' AND completada = 'no'
    AND fecha_limite >= '$fecha_actual'");
    $tareas = $traerTareas->fetchAll();
    echo json_encode($tareas);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $titulo = $data['titulo'];
    $descripcion = $data['descripcion'];
    $fechaLimite = str_replace("T", " ", $data['fechaLimite']);
    $prioridad = $data['prioridad'];

    $stmtInsert = $conn->prepare("INSERT INTO tareas (id_usuario, titulo, descripcion, fecha_limite,
    prioridad, completada) VALUES (?, ?, ?, ?, ?, ?)");
    $stmtInsert->execute([$id_usuario, $titulo, $descripcion, $fechaLimite, $prioridad, "no"]);

    echo json_encode(["message" => "Tarea agregada con éxito"]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['id']) && !isset($data['mensaje'])) {
        $id_tarea = $data['id'];
        $titulo = $data['titulo'];
        $descripcion = $data['descripcion'];
        $fechaLimite = str_replace("T", " ", $data['fechaLimite']);
        $prioridad = $data['prioridad'];

        $stmtEditar = $conn->prepare("UPDATE tareas SET titulo = ?, descripcion = ?, fecha_limite = ?,
        prioridad = ? WHERE id_tarea = ?");
        $stmtEditar->execute([$titulo, $descripcion, $fechaLimite, $prioridad, $id_tarea]);

        echo json_encode(["message" => "Tarea editada con éxito"]);
    } else {
        $id_tarea = $data['id'];
        $conn->query("UPDATE tareas SET completada = '$fecha_actual' WHERE id_tarea = '$id_tarea'");

        echo json_encode(["message" => "Tarea completada con éxito"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
   $data = json_decode(file_get_contents('php://input'), true);
   $id_tarea = $data['id'];

   $conn->query("DELETE FROM tareas WHERE id_tarea = '$id_tarea'");

   echo json_encode(["message" => "Tarea eliminada con éxito"]);
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
?>