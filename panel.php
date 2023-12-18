<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Panel</title>
    <link rel="stylesheet" href="css/tareas.css">
    <link rel="stylesheet" href="css/gastos.css">
    <link rel="stylesheet" href="css/general.css">
    <link rel="icon" href="icono.png">
</head>
<body>
    <header>
        <nav class="nav">
            <p><a href="index.html"><img src="css/DailyControl_logo.png" width="360" height="53"></a></p>
            <p><a href="tareas/seccion_tareas.php" class="hrefmargin" style="font-size: 1.5em;">Tareas</a>
            <a href="gastos/controlgastos.php" style="font-size: 1.5em;">Gastos</a></p>
            <p><a href="logout.php" class="iconomargin_logout"><img src="css/logout.png" width="45" height="45"></a></p>
        </nav>
    </header>
    <center>
    <div class="contenido_panel">
        <?php
            if (isset($_SESSION['bienvenido'])) {
                echo "<h1 class='titulos'>{$_SESSION['bienvenido']}</h1>";
                unset($_SESSION['bienvenido']);
            }
        ?>

        <h1 class="otro">Tareas Pendientes</h1>
        <button id="btnAbrirModal">Agregar Nueva Tarea</button>
        <div id="contenedor-tareas" class="contenedor-tareas"></div>

        <div id="modalAgregarTarea" class="modal">

            <h1>Nueva Tarea</h1>
            <p><input type="text" id="titulo" placeholder="Título" maxlength="30"></p>
            <p><textarea id="descripcion" placeholder="Descripción (opcional)" maxlength="250"></textarea></p>
            <p><b>Fecha Limite</b></p>
            <p><input type="datetime-local" id="fechaLimite" min="<?=date('Y-m-d'); ?>"></p>
            <p><select id="prioridad">
                    <option value="" disabled selected>Seleccione Prioridad</option>
                    <option value="si">Importante</option>
                    <option value="no">No tan Importante</option>
                </select></p>
            <p id="errorTarea" style="color: red;"></p>
            <p><button id="btnAgregarTarea">Añadir Tarea</button>
            <button id="btnCerrarModal">Cerrar</button></p>

        </div>

    <center><h1 class="otro">Gestión de Gastos</h1>
    <p style="color: white;"><span id="totalGastado"></span> Gastados en las últimas 48 horas. </p>
    <p><input type="text" id="monto" style="width: 4.5em;" maxlength="11">
    <button id="btnAgregarGasto">Añadir Gasto</button></p>
    <p style="color: magenta; background-color: black; width: 30em;" id="montoError">
    <table>
        <thead>
            <tr>
                <th>Monto</th>
                <th>Fecha</th>
                <th>.</th>
            </tr>
        </thead>
        <tbody id="tablaGastosBody"></tbody>
    </table></center>
    <h2 id="tituloPresupuestos" style="color: white;"></h2>
    <center><div id="presupuestosDiv"></div></center>
    <p><button id="btnAgregarPresupuestoModal">Añadir Presupuesto</button></p>
    <div id="agregarPresupuestoModal" class="menu">
        <h1>Nuevo Presupuesto</h1>
        <p><input type="text" style="font-weight: bold;" id="montoMaximo" placeholder="Monto Máximo" maxlength="11"></p>
        <p style="color: red;" id="montoErrorMaximo">
        <p>Fecha Inicio: <input type="date" id="fechaInicio" min="<?=date('Y-m-d'); ?>"></p>
        <p>Fecha Fin: <input type="date" id="fechaFin" min="<?=date('Y-m-d'); ?>"></p>
        <p style="color: red;" id="errorNuevoPresupuesto">
        <p>
            <button id="btnAgregarPresupuesto">Añadir</button>
            <button id="btnCancelarPresupuesto">Cancelar</button>
        </p>
    </div>
    <div id="editarPresupuestoModal" class="menu">
        <h1>Editar Presupuesto</h1>
        <p><input type="text" style="font-weight: bold;" id="montoMaximoEditar" placeholder="Monto Máximo" maxlength="11"></p>
        <p style="color: red;" id="montoMaximoError">
        <p>Fecha Inicio: <input type="date" id="fechaInicioEditar"></p>
        <p>Fecha Fin: <input type="date" id="fechaFinEditar"></p>
        <p style="color: red;" id="errorPresupuestoEditar">
        <p>
            <button id="btnModificarPre">Modificar</button>
            <button id="btnCancelarEditar">Cancelar</button>
        </p>
    </div>
    <div id="editarGastoModal" class="menu">
        <h1>Editar Gasto</h1>
        <input type="text" id="nuevoMonto" placeholder="Nuevo Monto" maxlength="11">
        <p style="color: red;" id="errorNuevoMonto">
        <p><button id="btnModificarGasto">Modificar</button>
        <button id="btnCancelarEdicionGasto">Cancelar</button></p>
    </div>
    <div id="overlayPresupuestoPanel" class="overlay">
    <div id="menuEliminarPresupuesto" class="menu">
            <h1><strong>¿Eliminar este Presupuesto?</strong></h1>
            <div id="detallesPresupuesto"></div>
            <button id="btnEliminarPre">Eliminar</button>
            <button id="btnCancelarEliminarPre">Cancelar</button>
    </div></div>
        <div id="confirmacionMenu" class="modal">
            <h1 style="font-size: 2.6em; margin-bottom: 0;">¿Completaste esta tarea?</h1>
            <h1 id="tituloCompleto"></h1>
            <p id="descripcionCompleta"></p>
            <p><strong>Fecha Limite: </strong><span id="fechaLimiteCompleta"></span></p>
            <button id="btnSi">Sí</button>
            <button id="btnNo">No</button>
        </div>
        </div>
        <div id="editarTareaModal" class="modal">
            <h1>Editar Tarea</h1>
            <p><input type="text" id="tituloEditar" placeholder="Título" maxlength="30"></p>
            <p><textarea id="descripcionEditar" placeholder="Descripción (opcional)" maxlength="250"></textarea></p>
            <p><b>Fecha Limite</b></p>
            <p><input type="datetime-local" id="fechaLimiteEditar" min="<?=date('Y-m-d'); ?>" required></p>
            <p><select id="prioridadEditar" required>
                <option value="si">Importante</option>
                <option value="no">No tan Importante</option>
            </select></p>
            <p style="color: red;" id="errorTareaEditar">
            <p><button id="btnModificar">Modificar</button>
            <button id="btnCancelarEdicion">Cancelar</button></p>

        </div>
        <div id="overlayEliminarTarea" class="overlay">
        <div id="confirmacionMenuEliminar" class="modal">
            <h1 style="font-size: 2.6em; margin-bottom: 0;">¿Eliminar esta tarea?</h1>
            <div id="confirmacionDetallesEliminar"></div>
            <button id="btnEliminar">Eliminar</button>
            <button id="btnCancelarEliminar">Cancelar</button>
        </div></div>
    </div></center>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            monto.addEventListener("input", () => validarNumeroDecimal(monto, montoError));
            montoMaximo.addEventListener("input", () => validarNumeroDecimal(montoMaximo, montoErrorMaximo));
            nuevoMonto.addEventListener("input", () => validarNumeroDecimal(nuevoMonto, errorNuevoMonto));
            montoMaximoEditar.addEventListener("input", () => validarNumeroDecimal(montoMaximoEditar, montoMaximoError));
            function validarNumeroDecimal(input, errorElement) {
                const valor = input.value;
                if (valor && !(/^\d+(\.\d{0,2})?$/.test(valor))) {
                    errorElement.textContent = "Solo puede ingresar numeros enteros o hasta dos decimales, con punto(.) y no coma(,)";
                    input.value = "";
                } else {
                    errorElement.textContent = "";
                }
            }
        });
    </script>
    <script src="tareas/tareas.js"></script>
    <script src="gastos/gastos.js"></script>
</body>
</html>