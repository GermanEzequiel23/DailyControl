document.addEventListener('DOMContentLoaded', () => {
    const inputMonto = document.getElementById('monto');
    let idGastoGlobal;

    btnAgregarGasto.addEventListener('click', async () => {
        const monto = inputMonto.value;
        if (monto === '' || monto == 0) {
            montoError.textContent = "¡Ingrese un gasto!";
            return;
        }
        inputMonto.value = '';

        const response = await fetch('gastos/gastos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ monto }),
        });
        const data = await response.json();

        console.log(data.message);
        cargarGastos();
    });

    async function cargarGastos() {
        const response = await fetch('gastos/gastos.php');
        const data = await response.json();

        tablaGastosBody.innerHTML = '';

        data.gastos.forEach(gasto => {
            const fila = document.createElement('tr');
            fila.innerHTML = `<td>$${gasto.monto}</td>
                              <td><center>${gasto.fecha}</center></td>
                              <td><button class="btnEditarGasto" data-id="${gasto.id_gasto}">✏️</button></td>`;
            tablaGastosBody.appendChild(fila);

            const btnEditarGasto = fila.querySelector('.btnEditarGasto');
            btnEditarGasto.addEventListener('click', () => formularioEditar(gasto));
        });
        totalGastado.textContent = `$${data.totalGastado}`;

        let conta = 0;
        presupuestosDiv.innerHTML = '';
        data.presupuestos.forEach(presupuesto => {
            const presupuestosContent = document.createElement('div');
            presupuestosContent.classList.add('presupuesto-content');

            const btn_mod_pre = document.createElement('button');
            btn_mod_pre.textContent = '📝';
            btn_mod_pre.classList.add('btn_presupuestos');
            btn_mod_pre.addEventListener('click', () => { edicionPresupuesto(presupuesto) });

            const btn_elim_pre = document.createElement('button');
            btn_elim_pre.textContent = '❌';
            btn_elim_pre.classList.add('btn_presupuestos');
            btn_elim_pre.addEventListener('click', () => { eliminarPresupuesto(presupuesto) });

            const textContent = document.createElement('p');
            textContent.textContent = `$${presupuesto.gasto_actual}/$${presupuesto.monto_maximo} Finaliza el ${presupuesto.fecha_fin}`;
            textContent.classList.add('text-content');

            presupuestosContent.appendChild(textContent);
            presupuestosContent.appendChild(btn_mod_pre);
            presupuestosContent.appendChild(btn_elim_pre);

            presupuestosDiv.appendChild(presupuestosContent);
            conta++;
        });
        if (conta > 0) {
            tituloPresupuestos.textContent = "Presupuestos Actuales";
        } else {
            tituloPresupuestos.textContent = "";
        }
    }

    function formularioEditar(gasto) {
        idGastoGlobal = gasto.id_gasto;
        nuevoMonto.value = gasto.monto;
        editarGastoModal.style.display = 'block';

        btnModificarGasto.addEventListener('click', async () => {
            const nuevoMonto = document.getElementById('nuevoMonto').value;

            if (nuevoMonto === '') {
                errorNuevoMonto.textContent = "¡Ingrese un gasto!";
                return;
            }
            editarGastoModal.style.display = 'none';

            const response = await fetch('gastos/gastos.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: idGastoGlobal,
                    monto: nuevoMonto
                })
            });
            const data = await response.json();
            console.log(data.message);
            idGastoGlobal = null;
            cargarGastos();
        });
        btnCancelarEdicionGasto.addEventListener('click', () => {
            editarGastoModal.style.display = 'none';
            errorNuevoMonto.textContent = "";
            idGastoGlobal = null;
        });
    }

    btnAgregarPresupuestoModal.addEventListener('click', () => {
        agregarPresupuestoModal.style.display = 'block';
    });

    btnCancelarPresupuesto.addEventListener('click', () => {
        agregarPresupuestoModal.style.display = 'none';
        montoErrorMaximo.textContent = "";
        montoMaximo.value = "";
        fechaInicio.value = "";
        fechaFin.value = "";
        errorNuevoPresupuesto.textContent = "";
    });

    btnAgregarPresupuesto.addEventListener('click', async () => {
        const montoMaximo = document.getElementById('montoMaximo').value;
        const fechaInicio = document.getElementById('fechaInicio').value;  
        const fechaFin = document.getElementById('fechaFin').value;

        if (montoMaximo === '' || montoMaximo == 0 || fechaInicio == '' || fechaFin == '') {
            errorNuevoPresupuesto.textContent = "¡Complete los datos!";
            return;
        }
        const InicioInputAgregar = new Date(fechaInicio);
        const FinInputAgregar = new Date(fechaFin);
        if (InicioInputAgregar === FinInputAgregar || InicioInputAgregar > FinInputAgregar) {
            errorNuevoPresupuesto.textContent = "¡Seleccione fechas válidas!";
            return;
        }

        const response = await fetch('gastos/gastos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                monto_maximo: montoMaximo,
                fecha_inicio: fechaInicio,
                fecha_fin: fechaFin,
                mensaje: "hola"
            })
        });

        const data = await response.json();

        if (data.success) {
            console.log(data.message);
            agregarPresupuestoModal.style.display = 'none';
            errorNuevoPresupuesto.textContent = "";
            document.getElementById('montoMaximo').value = "";
            document.getElementById('fechaInicio').value = "";
            document.getElementById('fechaFin').value = "";
            cargarGastos();
        } else {
            alert('Error al agregar el presupuesto');
        }
    });

    function edicionPresupuesto (presupuesto){
        idGastoGlobal = presupuesto.id_presupuesto; 
        document.getElementById('montoMaximoEditar').value = presupuesto.monto_maximo;
        document.getElementById('fechaInicioEditar').value = presupuesto.fecha_inicio;
        document.getElementById('fechaFinEditar').value = presupuesto.fecha_fin;

        editarPresupuestoModal.style.display = 'block';

        btnModificarPre.addEventListener('click', async () => {
            const nuevoMontoMaximo = document.getElementById('montoMaximoEditar').value;
            const nuevaFechaInicio = document.getElementById('fechaInicioEditar').value;
            const nuevaFechaFin = document.getElementById('fechaFinEditar').value;

            if (nuevoMontoMaximo === '' || nuevaFechaInicio == '' || nuevaFechaFin == '' || nuevoMontoMaximo == 0) {
                errorPresupuestoEditar.textContent = "¡Complete los datos!";
                return;
            }
            const InicioInput = new Date(nuevaFechaInicio);
            const FinInput = new Date(nuevaFechaFin);
            if (InicioInput === FinInput || InicioInput > FinInput) {
                errorPresupuestoEditar.textContent = "¡Seleccione fechas válidas!";
                return;
            }
            const response = await fetch('gastos/gastos.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: idGastoGlobal,
                    montoMaximoEditar: nuevoMontoMaximo,
                    fechaInicioEditar: nuevaFechaInicio,
                    fechaFinEditar: nuevaFechaFin,
                    presupuesto: "presupuesto"
                })
            });
            const data = await response.json();

            console.log(data.message);
            editarPresupuestoModal.style.display = 'none';
            cargarGastos();
        });
        btnCancelarEditar.addEventListener('click', () => {
            editarPresupuestoModal.style.display = 'none';
            errorPresupuestoEditar.textContent = "";
            montoMaximoError.textContent = "";
            idGastoGlobal = null; 
        });
    }

    function eliminarPresupuesto(presupuesto) {
        idGastoGlobal = presupuesto.id_presupuesto;
        detallesPresupuesto.innerHTML = `
            <p><strong>Monto Máximo:</strong> $${presupuesto.monto_maximo}</p>
            <p><strong>Fecha Inicio:</strong> ${presupuesto.fecha_inicio}</p>
            <p><strong>Fecha Fin:</strong> ${presupuesto.fecha_fin}</p>`;

        menuEliminarPresupuesto.style.display = 'block';
        overlayPresupuestoPanel.style.display = 'block';
        menuEliminarPresupuesto.style.width = '20em';

        btnEliminarPre.addEventListener('click', async () => {
            const response = await fetch('gastos/gastos.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: idGastoGlobal })
            });
            const data = await response.json();
            console.log(data.message);

            overlayPresupuestoPanel.style.display = 'none';
            menuEliminarPresupuesto.style.display = 'none';
            cargarGastos();
        });
        btnCancelarEliminarPre.addEventListener('click', () => {
            overlayPresupuestoPanel.style.display = 'none';
            menuEliminarPresupuesto.style.display = 'none', 
            idGastoGlobal = null; 
        });
    }
    cargarGastos();
});