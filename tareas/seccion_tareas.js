const contenedorTareasCompletadas = document.getElementById('listaCompletadas');
const contenedorTareasVencidas = document.getElementById('listaVencidas');
const overlay = document.getElementById('overlayEliminar');

let offsetActualCompletadas = 0;
let offsetActualVencidas = 0;
let idTareaGlobal;

async function cargarTareas(accion, contenedor, offset) {
    const response = await fetch(`api_seccion.php?accion=${accion}&offset=${offset}`);
    const data = await response.json();

    contenedor.innerHTML = '';
    const tareas = data.tareas;

    tareas.forEach(tarea => {
        const elementoTarea = crearElementoTarea(tarea, accion === 'completadas');
        contenedor.appendChild(elementoTarea);
    });
    actualizarPaginacion(accion, offset, data.limit, data.totalFilas);
}

function crearElementoTarea(tarea, esCompletada) {
    const elementoTarea = document.createElement('div');
    elementoTarea.classList.add('card-container');
    elementoTarea.style.backgroundColor = esCompletada ? '#185c7d' : '#772347';

    const descripcion = esCompletada ? `Completada el ${tarea.completada}` : `Vencida el ${tarea.fecha_limite}`;

    elementoTarea.innerHTML = `<h2>${tarea.titulo}</h2>
    <p>${tarea.descripcion}</p>
    <p><strong>${descripcion}</strong></p>
    <button onclick="mostrarConfirmacionEliminar('${tarea.id_tarea}', '${tarea.titulo}', '${tarea.descripcion}', '${esCompletada}', '${tarea.fecha_limite}')">Eliminar</button>`;

    return elementoTarea;
}

async function mostrarConfirmacionEliminar(id, titulo, descripcion, esCompletada, fechaLimite) {
    const mensaje = esCompletada ? `Completada el ${fechaLimite}` : `Vencida el ${fechaLimite}`;
    document.getElementById('confirmacionDetallesEliminar').innerHTML = `
      <h2><strong>${titulo}</strong></h2>
      <p>${descripcion}</p>
      <p><strong>${mensaje}</strong></p>`;

    overlay.style.display = 'block';
    eliminarMenu.style.display = 'block';
    idTareaGlobal = id;

    btnEliminar.addEventListener('click', async () => {
        const response = await fetch('api_seccion.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ idTareaGlobal })
        });
        const data = await response.json();
        console.log(data.message);
        idTareaGlobal = null;
        overlay.style.display = 'none';
        eliminarMenu.style.display = 'none';
        cargarTareas('completadas', contenedorTareasCompletadas, offsetActualCompletadas);
        cargarTareas('vencidas', contenedorTareasVencidas, offsetActualVencidas);
    });

    btnCancelarEliminar.addEventListener('click', () => {
        overlay.style.display = 'none';
        eliminarMenu.style.display = 'none';
        idTareaGlobal = null;
    });
}

function actualizarPaginacion(accion, offset, limitTareas, totalFilas) {
    const paginacionDiv = accion === 'completadas' ? document.getElementById('paginacionCompletadas') : document.getElementById('paginacionVencidas');

    paginacionDiv.innerHTML = '';
    if (totalFilas > limitTareas) {
        const btnAnterior = document.createElement('button');
        btnAnterior.textContent = 'Anterior';
        btnAnterior.addEventListener('click', () => cargarTareas(accion, accion === 'completadas' ? contenedorTareasCompletadas : contenedorTareasVencidas, offset - limitTareas >= 0 ? offset - limitTareas : 0));

        const btnSiguiente = document.createElement('button');
        btnSiguiente.textContent = 'Siguiente';

        if (offset + limitTareas < totalFilas) {
            btnSiguiente.addEventListener('click', () => cargarTareas(accion, accion === 'completadas' ? contenedorTareasCompletadas : contenedorTareasVencidas, offset + limitTareas));
        } else {
            btnSiguiente.disabled = true;
        }
        paginacionDiv.appendChild(btnAnterior);
        paginacionDiv.appendChild(btnSiguiente);
    }
}

async function iniciar() {
    await cargarTareas('completadas', contenedorTareasCompletadas, offsetActualCompletadas);
    await cargarTareas('vencidas', contenedorTareasVencidas, offsetActualVencidas);
}
iniciar();