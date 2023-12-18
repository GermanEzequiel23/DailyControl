<?php
    session_start();
    require_once '../conexion.php';

    $id_usuario = $_SESSION['id_usuario'];
    date_default_timezone_set("America/Argentina/Buenos_Aires");
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmtGastosMeses = $conn->query("SELECT * FROM gastos WHERE id_usuario = '$id_usuario'");
        $gastos = $stmtGastosMeses->fetchAll();
        $fecha_actual = date('Y-m-d');
    
        $primer_dia_mes_actual = date('Y-m-01');
    
        $stmt = $conn->query("SELECT SUM(monto) AS total_gastos_mes FROM gastos WHERE id_usuario = $id_usuario AND
        fecha_comparar >= '$primer_dia_mes_actual'");
        $total_gastos_mes = $stmt->fetchColumn();
    
        $totalFilas = $conn->query("SELECT COUNT(*) FROM gastos WHERE id_usuario = '$id_usuario'")->fetchColumn();
    
        echo json_encode(["totalFilas" => $totalFilas, "total_gastos_mes" => $total_gastos_mes]);
    } 
    function obtenerGastosMes($conn, $mes, ) {
        return $gastos_mes;
        return $nombre_mes;
    }
?>