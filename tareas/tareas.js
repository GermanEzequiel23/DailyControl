const contenedorTareas = document.getElementById('contenedor-tareas');
let idTareaGlobal;

async function obtenerTareasPendientes() {
    const response = await fetch('tareas/tareas.php');
    const tareas = await response.json();

    contenedorTareas.innerHTML = '';

    tareas.forEach(tarea => {
        const elementoTarea = document.createElement('div');
        elementoTarea.classList.add('card');
        elementoTarea.style.backgroundColor = tarea.prioridad === 'si' ? 'lightgreen' : 'yellow';

        const btnCompletar = document.createElement('button');
        btnCompletar.innerText = 'Completar';
        btnCompletar.addEventListener('click', () => mostrarConfirmacion(tarea));

        const btnEditar = document.createElement('button');
        btnEditar.innerText = 'Editar';
        btnEditar.addEventListener('click', () => mostrarEdicion(tarea));

        const btnEliminar = document.createElement('button');
        btnEliminar.innerText = 'Eliminar';
        btnEliminar.addEventListener('click', () => mostrarConfirmacionEliminar(tarea));

        elementoTarea.innerHTML = `<h1>${tarea.titulo}</h1>
        <p>${tarea.descripcion}</p>
        <p><strong>Fecha límite: </strong>${tarea.fecha_limite}</p>`;
        
        elementoTarea.appendChild(btnCompletar);
        elementoTarea.appendChild(btnEditar);
        elementoTarea.appendChild(btnEliminar);
        contenedorTareas.appendChild(elementoTarea);
    });
}
btnAbrirModal.addEventListener('click', () => modalAgregarTarea.style.display = 'block');
btnCerrarModal.addEventListener('click', () => {
    modalAgregarTarea.style.display = 'none';
    errorTarea.textContent = "";
    titulo.value = '';
    descripcion.value = '';
    fechaLimite.value = '';
    prioridad.value = '';
})

function ajustarAltura() {
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        const descripcion = card.querySelector('p');
        descripcion.style.maxHeight = (card.offsetHeight - 40) + 'px';
    });
}
window.addEventListener('resize', ajustarAltura);

btnAgregarTarea.addEventListener('click', async () => {
    const titulo = document.getElementById('titulo').value;
    const descripcion = document.getElementById('descripcion').value;
    const fechaLimite = document.getElementById('fechaLimite').value;
    const prioridad = document.getElementById('prioridad').value;

    if (titulo === '' || fechaLimite == '' || prioridad == '') {
        errorTarea.textContent = "¡Complete los datos!";
        return;
    }

    const response = await fetch('tareas/tareas.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ titulo, descripcion, fechaLimite, prioridad })
    });
    const data = await response.json();

    document.getElementById('titulo').value = '';
    document.getElementById('descripcion').value = '';
    document.getElementById('fechaLimite').value = '';
    document.getElementById('prioridad').value = '';
    errorTarea.textContent = "";

    console.log(data.message);
    modalAgregarTarea.style.display = 'none'; 
    obtenerTareasPendientes();
});

function mostrarConfirmacion(tarea) {
    idTareaGlobal = tarea.id_tarea;
    tituloCompleto.textContent = tarea.titulo;
    descripcionCompleta.textContent = tarea.descripcion;
    fechaLimiteCompleta.textContent = tarea.fecha_limite;

    confirmacionMenu.style.display = 'block';
    confirmacionMenu.style.width = '23em';

    btnSi.addEventListener('click', async () => {
        const response = await fetch('tareas/tareas.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: idTareaGlobal, mensaje: "hola" })
        });
        const data = await response.json();
        
        console.log(data.message);
        confirmacionMenu.style.display = 'none';
        obtenerTareasPendientes();
    });
    btnNo.addEventListener('click', () => { 
        confirmacionMenu.style.display = 'none'
        idTareaGlobal = null;
    });
}

function mostrarEdicion(tarea) {
    idTareaGlobal = tarea.id_tarea; 
    document.getElementById('tituloEditar').value = tarea.titulo;
    document.getElementById('descripcionEditar').value = tarea.descripcion;
    document.getElementById('fechaLimiteEditar').value = tarea.fecha_limite;
    document.getElementById('prioridadEditar').value = tarea.prioridad;

    editarTareaModal.style.display = 'block';

    btnModificar.addEventListener('click', async () => {
        const nuevoTitulo = document.getElementById('tituloEditar').value;
        const nuevaDescripcion = document.getElementById('descripcionEditar').value;
        const nuevaFechaLimite = document.getElementById('fechaLimiteEditar').value;
        const nuevaPrioridad = document.getElementById('prioridadEditar').value;

        if (nuevoTitulo === '' || nuevaFechaLimite == '' || nuevaPrioridad == '') {
            errorTareaEditar.textContent = "¡Complete los datos!";
            return;
        }

        const response = await fetch('tareas/tareas.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: idTareaGlobal,
                titulo: nuevoTitulo,
                descripcion: nuevaDescripcion,
                fechaLimite: nuevaFechaLimite,
                prioridad: nuevaPrioridad
            })
        });
        const data = await response.json();

        console.log(data.message);
        editarTareaModal.style.display = 'none';
        obtenerTareasPendientes();
    });
    btnCancelarEdicion.addEventListener('click', () => {
        editarTareaModal.style.display = 'none';
        errorTareaEditar.textContent = "";
        idTareaGlobal = null; 
    });
}

function mostrarConfirmacionEliminar(tarea) {
    idTareaGlobal = tarea.id_tarea;
    confirmacionDetallesEliminar.innerHTML = `
        <h1>${tarea.titulo}</h1>
        <p>${tarea.descripcion}</p>
        <p><strong>Fecha Límite:</strong> ${tarea.fecha_limite}</p>`;

    confirmacionMenuEliminar.style.display = 'block';
    overlayEliminarTarea.style.display = 'block';
    confirmacionMenuEliminar.style.width = '23em';

    btnEliminar.addEventListener('click', async () => {
        const response = await fetch('tareas/tareas.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: idTareaGlobal })
        });
        const data = await response.json();
        console.log(data.message);

        overlayEliminarTarea.style.display = 'none';
        confirmacionMenuEliminar.style.display = 'none';
        obtenerTareasPendientes();
    });
    btnCancelarEliminar.addEventListener('click', () => {
        overlayEliminarTarea.style.display = 'none',
        confirmacionMenuEliminar.style.display = 'none', 
        idTareaGlobal = null; 
    });
}
obtenerTareasPendientes();