document.addEventListener('DOMContentLoaded', () => {
    let offsetActualGastos = 0;
    let offsetActualPresupuestos = 0;

    async function cargarDatos(offsetGastos, offsetPresupuestos) {
        const responseGastos = await fetch(`api_gastos.php?offset=${offsetGastos}`);
        const dataGastos = await responseGastos.json();
    
        const responsePresupuestos = await fetch(`api_gastos.php?offset_presupuestos=${offsetPresupuestos}`);
        const dataPresupuestos = await responsePresupuestos.json();

        cargarGastos(dataGastos.gastos, dataGastos.total_gastos_mes);
        cargarProximosPresupuestos(dataPresupuestos.proximos_presupuestos);
        cargarPresupuestosVencidos(dataPresupuestos.presupuestos_vencidos);
    
        actualizarPaginacion(offsetGastos, offsetPresupuestos, dataGastos.totalFilas, dataPresupuestos.totalPresupuestosVencidos,
        dataGastos.limit_gastos, dataPresupuestos.limit_presupuestos);
    }

    function cargarGastos(gastos, ultimomes) {
        const tablaGastosBody = document.getElementById('tablaGastosBody');
        tablaGastosBody.innerHTML = '';

        gastos.forEach(gasto => {
            const fila = document.createElement('tr');
            fila.innerHTML = `<td>$${gasto.monto}</td> 
            <td>${gasto.fecha}</td>`;
            tablaGastosBody.appendChild(fila);
        });
        ultimo_mes.textContent = ultimomes;
    }

    function cargarProximosPresupuestos(proximosPresupuestos) {
        const proximosPresupuestosDiv = document.getElementById('proximosPresupuestosDiv');
        proximosPresupuestosDiv.innerHTML = '';

        proximosPresupuestos.forEach(presupuesto => {
            proximosPresupuestosDiv.innerHTML += `<p><strong>Monto Máximo: </strong>$${presupuesto.monto_maximo} - Inicia el ${presupuesto.fecha_inicio}</p>`;
        });
    }

    function cargarPresupuestosVencidos(presupuestosVencidos) {
        const presupuestosVencidosDiv = document.getElementById('presupuestosVencidosDiv');
        presupuestosVencidosDiv.innerHTML = '';
    
        presupuestosVencidos.forEach(presupuesto => {
            presupuestosVencidosDiv.innerHTML += `<p><strong>$${presupuesto.gasto_actual}/$${presupuesto.monto_maximo}</strong> - Finalizó el ${presupuesto.fecha_fin}</p>`;
        });
    }

    btnAgregarGasto.addEventListener('click', async () => {
        const monto = inputMonto.value;
        if (monto === '' || monto == 0) {
            montoError.textContent = "¡Ingrese un gasto!";
            return;
        }
        inputMonto.value = '';
    
        const response = await fetch('api_gastos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ monto }),
        });
        const data = await response.json();
    
        console.log(data.message);
        cargarDatos(offsetActualGastos, offsetActualPresupuestos);
    });    

    function actualizarPaginacion(offsetGastos, offsetPresupuestos, totalFilasGastos, totalFilasPresupuestos, limitGastos, limitPresupuestos) {
        const paginacionDivGastos = document.getElementById('paginacion');
        const paginacionDivPresupuestos = document.getElementById('presupuestosVencidosPaginacion');
    
        paginacionDivGastos.innerHTML = '';
        paginacionDivPresupuestos.innerHTML = '';
        if (totalFilasGastos > limitGastos) {
            const btnAnteriorGastos = document.createElement('button');
            btnAnteriorGastos.textContent = 'Anterior';
            btnAnteriorGastos.addEventListener('click', () => cargarDatos(offsetGastos - limitGastos >= 0 ? offsetGastos - limitGastos : 0, offsetPresupuestos, limitGastos, limitPresupuestos));
        
            const btnSiguienteGastos = document.createElement('button');
            btnSiguienteGastos.textContent = 'Siguiente';
            if (offsetGastos + limitGastos < totalFilasGastos) {
                btnSiguienteGastos.addEventListener('click', () => cargarDatos(offsetGastos + limitGastos < totalFilasGastos ? offsetGastos + limitGastos : offsetGastos, offsetPresupuestos, limitGastos, limitPresupuestos));
            } else {
                btnSiguienteGastos.disabled = true;
            }
            paginacionDivGastos.appendChild(btnAnteriorGastos);
            paginacionDivGastos.appendChild(btnSiguienteGastos);
        } 
        if (totalFilasPresupuestos > limitPresupuestos) {
            const btnAnteriorPresupuestos = document.createElement('button');
            btnAnteriorPresupuestos.textContent = 'Anterior';
            btnAnteriorPresupuestos.addEventListener('click', () => cargarDatos(offsetGastos, offsetPresupuestos - limitPresupuestos >= 0 ? offsetPresupuestos - limitPresupuestos : 0, limitGastos, limitPresupuestos));
        
            const btnSiguientePresupuestos = document.createElement('button');
            btnSiguientePresupuestos.textContent = 'Siguiente';
            if (offsetPresupuestos + limitPresupuestos < totalFilaPresupuestoss) {
                btnSiguientePresupuestos.addEventListener('click', () => cargarDatos(offsetGastos, offsetPresupuestos + limitPresupuestos < totalFilasPresupuestos ? offsetPresupuestos + limitPresupuestos : offsetPresupuestos, limitGastos, limitPresupuestos));
            } else {
                btnSiguientePresupuestos.disabled = true;
            }
            paginacionDivPresupuestos.appendChild(btnAnteriorPresupuestos);
            paginacionDivPresupuestos.appendChild(btnSiguientePresupuestos);
        }
    }    

    cargarDatos(offsetActualGastos, offsetActualPresupuestos);
});