<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="../css/controltareas.css">
    <link rel="stylesheet" href="../css/tareas.css">
    <link rel="stylesheet" href="../css/general.css">
    <link rel="icon" href="../icono.png">
    <title>Sección de Tareas</title>
</head>
<body>
    <header>
        <nav class="nav">
            <p><a href="../index.html"><img src="../css/DailyControl_logo.png" width="360" height="53"></a></p>
            <p><a href="../gastos/controlgastos.php" style="font-size: 1.5em;" class="hrefmarginotros">Gastos</a></p>
            <p><a href="../panel.php" class="iconomargin"><img src="../css/login.png" width="45" height="45"></a></p>
        </nav>
    </header>
    <!-- <center><h1 class="titulos_tareas">Control de tareas</h1></center> -->
    <center><div class="contenido_tareas">
    <div class="list_contenido">
        <center><h1 class="otro">Tareas Completadas</h1>
        <div id="paginacionCompletadas"></div>
        <div id="listaCompletadas" class="card-container"></div></center>
    </div>

    <div class="list_contenido">
        <center><h1 class="otro">Tareas Vencidas</h1>
        <div id="paginacionVencidas"></div>
        <div id="listaVencidas" class="card-container"></div></center>
    </div></p>
    <div id="overlayEliminar" class="overlay"></div>
    <div id="eliminarMenu" class="modal">
            <h1>¿Eliminar esta tarea?</h1>
            <div id="confirmacionDetallesEliminar"></div>
            <button id="btnEliminar">Eliminar</button>
            <button id="btnCancelarEliminar">Cancelar</button>
    </div></div></center>
    <script src="seccion_tareas.js"></script>
</body>
</html>