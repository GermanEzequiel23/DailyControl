<?php
session_start();
require_once '../conexion.php';

$id_usuario = $_SESSION['id_usuario'];
date_default_timezone_set("America/Argentina/Buenos_Aires");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $offset = isset($_GET['offset']) ? $_GET['offset'] : 0; 
    $offsetPresupuestos = isset($_GET['offset_presupuestos']) ? $_GET['offset_presupuestos'] : 0;
    $limit_gastos = 10;
    $limit_presupuestos = 10;

    $stmtGastos = $conn->query("SELECT * FROM gastos WHERE id_usuario = '$id_usuario' ORDER BY fecha DESC 
    LIMIT $limit_gastos  OFFSET $offset");
    $gastos = $stmtGastos->fetchAll();
    $fecha_actual = date('Y-m-d');

    $primer_dia_mes_actual = date('Y-m-01');

    $stmt = $conn->query("SELECT SUM(monto) AS total_gastos_mes FROM gastos WHERE id_usuario = $id_usuario AND
    fecha_comparar >= '$primer_dia_mes_actual'");
    $total_gastos_mes = $stmt->fetchColumn();
    
    $stmtPresupuestos = $conn->query("SELECT * FROM presupuestos WHERE id_usuario = '$id_usuario'");
    $presupuestos = $stmtPresupuestos->fetchAll();

    $stmtProximosPresupuestos = $conn->query("SELECT * FROM presupuestos WHERE id_usuario = '$id_usuario' AND 
    fecha_inicio > '$fecha_actual'");
    $proximosPresupuestos = $stmtProximosPresupuestos->fetchAll();

    $totalFilas = $conn->query("SELECT COUNT(*) FROM gastos WHERE id_usuario = '$id_usuario'")->fetchColumn();

    $stmtPresupuestosVencidos = $conn->query("SELECT * FROM presupuestos WHERE id_usuario = '$id_usuario' AND
    fecha_fin < '$fecha_actual' LIMIT $limit_presupuestos OFFSET $offsetPresupuestos");
    $presupuestosVencidos = $stmtPresupuestosVencidos->fetchAll();

    $totalPresupuestosVencidos = $conn->query("SELECT COUNT(*) FROM presupuestos WHERE id_usuario = '$id_usuario' AND fecha_fin < '$fecha_actual'")->fetchColumn();
    
    echo json_encode(["gastos" => $gastos, "presupuestos" => $presupuestos, "proximos_presupuestos" => $proximosPresupuestos,
    "presupuestos_vencidos" => $presupuestosVencidos, "totalFilas" => $totalFilas, "totalPresupuestosVencidos" => $totalPresupuestosVencidos,
    "limit_gastos" => $limit_gastos, "limit_presupuestos" => $limit_presupuestos, "total_gastos_mes" => $total_gastos_mes]);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $fecha_actual = date('Y-m-d H:i', strtotime('+1 hour, -1 minute'));
        $fecha_comparar = date('Y-m-d H:i:s', strtotime('+1 hour, -1 minute, -20 seconds'));
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
    } else {
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
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