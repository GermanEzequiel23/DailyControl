<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/controlgastos.css"> <!-- Nuevo archivo CSS para estilos específicos de controlgastos.php -->
    <link rel="icon" href="../icono.png">
    <title>Control de Gastos</title>
</head>
<body>
    <header>
        <nav class="nav">
            <p><a href="../index.html"><img src="../css/DailyControl_logo.png" width="360" height="53"></a></p>
            <p><a href="../tareas/seccion_tareas.php" style="font-size: 1.5em;" class="hrefmarginotros">Tareas</a></p>
            <p><a href="../panel.php" style="margin-left: 31em;"><img src="../css/login.png" width="45" height="45"></a></p>
        </nav>
    </header>

    <div class="content-container">
        <center><section id="gastosPaginacion" class="list-container gastos-section">
            <h1 style="font-size: 2em;">Todos los Gastos</h1>
            <p><input type="text" id="inputMonto" maxlength="11"> <button id="btnAgregarGasto">Añadir Gasto</button></p>
            <p id="montoError" style="color: magenta;"></p>
            <div id="paginacion" class="paginacion"></div>
            <table class="gastos-table">
                <thead>
                    <tr>
                        <th>Monto</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody id="tablaGastosBody"></tbody>
            </table>
            <p>$<span id="ultimo_mes"></span> gastados este mes.</p>
        </section></center>

        <center><section id="presupuestosContainer" class="list-container presupuestos-section">
            <div id="proximosPresupuestos" class="presupuestos-section">
                <h1 style="font-size: 2em;">Próximos Presupuestos</h1>
                <div id="proximosPresupuestosDiv" class="list"></div>
            </div>

            <h1 style="font-size: 2em;">Presupuestos Finalizados</h1>
            <div id="presupuestosVencidos" class="presupuestos-section">
                <section id="presupuestosVencidosPaginacion" class="paginacion"></section>
                <div id="presupuestosVencidosDiv" class="list"></div>
            </div>
        </section></center>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            inputMonto.addEventListener("input", () => validarNumeroDecimal(inputMonto, montoError));
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
    <script src="controlgastos.js"></script>
</body>
</html>
