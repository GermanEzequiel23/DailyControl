<?php
session_start();
require_once 'conexion.php';

$id_usuario = $_SESSION['id_usuario'];
date_default_timezone_set("America/Argentina/Buenos_Aires");;
    $fecha_actual = date('Y-m-d');

    $selectPresupuestos = $conn->query("SELECT * FROM presupuestos WHERE id_usuario = $id_usuario AND 
    fecha_inicio <= '$fecha_actual' AND fecha_fin >= '$fecha_actual'");
    $presupuestos = $selectPresupuestos->fetchAll();


    echo json_encode(["presupuestos" => $presupuestos]);
?>