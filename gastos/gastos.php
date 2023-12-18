<?php
session_start();
require_once '../conexion.php';

$id_usuario = $_SESSION['id_usuario'];
date_default_timezone_set("America/Argentina/Buenos_Aires");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $fecha_actual = date('Y-m-d');
    $_48_horas = date('Y-m-d H:i:s', strtotime('-47 hours, -1 minute, -20 seconds'));

    $selectPresupuestos = $conn->query("SELECT * FROM presupuestos WHERE id_usuario = '$id_usuario' AND 
    fecha_inicio <= '$fecha_actual' AND fecha_fin >= '$fecha_actual'");
    $presupuestos = $selectPresupuestos->fetchAll();
    $stmt = $conn->query("SELECT * FROM gastos WHERE id_usuario = $id_usuario AND fecha_comparar >= '$_48_horas' ORDER BY fecha DESC");
    $gastos = $stmt->fetchAll();

    $totalGastadoQuery = $conn->query("SELECT SUM(monto) FROM gastos WHERE id_usuario = $id_usuario AND
    fecha_comparar >= '$_48_horas'");
    $totalGastado = $totalGastadoQuery->fetchColumn();

    echo json_encode(["gastos" => $gastos, "totalGastado" => $totalGastado, "presupuestos" => $presupuestos]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $fecha_actual = date('d-m-Y H:i');
    $fecha_comparar = date('Y-m-d H:i:s');
    if (isset($data['mensaje'])) {
        $monto_maximo = $data['monto_maximo'];
        $fecha_inicio = $data['fecha_inicio'];
        $fecha_fin = $data['fecha_fin'];

        $stmtInsertPresupuesto = $conn->prepare("INSERT INTO presupuestos (id_usuario, monto_maximo, fecha_inicio,
        fecha_fin, gasto_actual) VALUES (?, ?, ?, ?, 0)");
        $stmtInsertPresupuesto->execute([$id_usuario, $monto_maximo, $fecha_inicio, $fecha_fin]);

        echo json_encode(["success" => true, "message" => "Presupuesto agregado con éxito"]);
    } else {
        $monto = $data['monto'];

        $presupuesto = obtenerPresupuestoActivo($conn, $id_usuario);
        
        if ($presupuesto) {
            actualizarGastoActual($conn, $presupuesto['id_presupuesto'], $monto);

            $stmtInsert = $conn->prepare("INSERT INTO gastos (id_usuario, monto, fecha, id_presupuesto, fecha_comparar)
            VALUES (?, ?, ?, ?, ?)");
            $stmtInsert->execute([$id_usuario, $monto, $fecha_actual, $presupuesto['id_presupuesto'], $fecha_comparar]);
        } else {
            $stmtInsert = $conn->prepare("INSERT INTO gastos (id_usuario, monto, fecha, fecha_comparar) VALUES (?, ?, ?, ?)");
            $stmtInsert->execute([$id_usuario, $monto, $fecha_actual, $fecha_comparar]);
        }

        echo json_encode(["success" => true, "message" => "Gasto agregado con éxito"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    if(isset($data['presupuesto'])) {
        $id_presupuesto = $data['id'];
        $monto_maximo = $data['montoMaximoEditar'];
        $fecha_inicio = $data['fechaInicioEditar'];
        $fecha_fin = $data['fechaFinEditar'];

        $conn->query("UPDATE presupuestos SET monto_maximo = '$monto_maximo', fecha_inicio = '$fecha_inicio',
        fecha_fin = '$fecha_fin' WHERE id_presupuesto = '$id_presupuesto'");

        echo json_encode(["success" => true, "message" => "Presupuesto modificado con éxito"]);
    } else {
        $id_gasto = $data['id'];
        $nuevo_monto = $data['monto'];

        $MontoAnterior = $conn->query("SELECT monto, id_presupuesto FROM gastos WHERE id_gasto = '$id_gasto'");
        $row = $MontoAnterior->fetch();

        $monto_anterior = $row['monto'];
        $id_presupuesto = $row['id_presupuesto'];

        $conn->query("UPDATE gastos SET monto = '$nuevo_monto' WHERE id_gasto = '$id_gasto'");

        $verificarPresupuesto = $conn->query("SELECT * FROM presupuestos WHERE id_presupuesto = '$id_presupuesto'");
        if ($verificarPresupuesto->rowCount() > 0) {
            $totalGastadoQuery = $conn->query("SELECT SUM(monto) FROM gastos WHERE id_presupuesto = '$id_presupuesto'");
            $totalGastado = $totalGastadoQuery->fetchColumn();
            $stmtUpdatePresupuesto = $conn->prepare("UPDATE presupuestos SET gasto_actual = ? WHERE id_presupuesto = ?");
            $stmtUpdatePresupuesto->execute([$totalGastado, $id_presupuesto]);
        }
        echo json_encode(["success" => true, "message" => "Gasto modificado con éxito"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id_presupuesto = $data['id'];

    $conn->query("DELETE FROM presupuestos WHERE id_presupuesto = '$id_presupuesto'");

    echo json_encode(["message" =>  "Presupuesto eliminado con éxito"]);
}

function obtenerPresupuestoActivo($conn, $id_usuario) {
    $fecha_actual = date('Y-m-d');
    $stmt = $conn->query("SELECT * FROM presupuestos WHERE id_usuario = $id_usuario 
    AND fecha_inicio <= '$fecha_actual' AND fecha_fin >= '$fecha_actual'");
    $presupuesto = $stmt->fetch();

    return $presupuesto;
}

function actualizarGastoActual($conn, $id_presupuesto, $monto_gasto) {
    $stmtUpdate = $conn->prepare("UPDATE presupuestos SET gasto_actual = gasto_actual + ? WHERE id_presupuesto = ?");
    $stmtUpdate->execute([$monto_gasto, $id_presupuesto]);
}
?>