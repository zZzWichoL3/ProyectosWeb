let tablaActual = 'Empleados'; // Cargar la tabla seleccionaa

async function cargarTabla() {
    const res = await fetch(`api.php?tabla=${tablaActual}`); // Pedir los datos al servidor
    const datos = await res.json(); // Convertir la respuesta a JSON
    const contenedor = document.getElementById('tabla'); // Buscar el div donde va la tabla
    contenedor.innerHTML = '';

    if (!datos.length) {
        contenedor.innerHTML = '<p>No hay datos.</p>';
        return;
    }

    let html = '<table border="1"><tr>';
    for (const key in datos[0]) { // Crear los encabezados de la tabla
        html += `<th>${key}</th>`;
    }
    html += '<th>Acciones</th></tr>';

    datos.forEach(d => { 
        html += '<tr>';
        for (const key in d) {
            html += `<td data-campo="${key}" contenteditable="true">${d[key] ?? ''}</td>`; // Celda editable
        }
        html += `<td>
            <button onclick="editar(${d[tablaActual === 'Empleados' ? 'ID_Empleado' : 'ID_Puesto']}, this)">Guardar</button>
            <button onclick="eliminar(${d[tablaActual === 'Empleados' ? 'ID_Empleado' : 'ID_Puesto']})">Eliminar</button>
        </td></tr>`;
    });

    html += '</table>';
    contenedor.innerHTML = html; // Mostrar la tabla en la página
}

async function eliminar(id) {
    await fetch('api.php', {
        method: 'POST',
        body: JSON.stringify({ accion: 'eliminar', id, tabla: tablaActual })
    });
    cargarTabla(); // Recargar la tabla
}

async function editar(id, btn) {
    const fila = btn.closest('tr'); // Buscar la fila del botón
    const celdas = fila.querySelectorAll('td[data-campo]');
    let datos = { accion: 'editar', id, tabla: tablaActual };
    celdas.forEach(td => {
        datos[td.dataset.campo] = td.innerText.trim(); // Tomar los valores editados
    });

    const res = await fetch('api.php', {
        method: 'POST',
        body: JSON.stringify(datos)
    });

    const resultado = await res.json();
    if (resultado.ok) {
        alert("Actualizado correctamente");
        cargarTabla();
    } else {
        alert("Error al actualizar");
    }
}

async function agregar() {
    const form = document.querySelector('#formAgregar');
    const datos = Object.fromEntries(new FormData(form).entries());
    datos.accion = 'agregar';
    datos.tabla = tablaActual;

    await fetch('api.php', {
        method: 'POST',
        body: JSON.stringify(datos)
    });
    form.reset();
    cargarTabla();
}

window.addEventListener('DOMContentLoaded', () => {
    cambiarCamposFormulario();
    cargarTabla();

    document.getElementById('tablaSel').addEventListener('change', e => {
        tablaActual = e.target.value;
        cambiarCamposFormulario();
        cargarTabla();
    });
});

function cambiarCamposFormulario() {
    const empleados = `
        Clave Empleado: <input name="Clave_Empleado"><br>
        Nombre: <input name="Nombre"><br>
        A. Paterno: <input name="A_paterno"><br>
        A. Materno: <input name="A_materno"><br>
        ID Puesto: <input name="ID_Puesto" type="number"><br>
        Fecha Ingreso: <input name="Fecha_Ingreso" type="date"><br>
        Fecha Baja: <input name="Fecha_Baja" type="date"><br>
        Estatus: <input name="Estatus"><br>
    `;
    const puestos = `
        ID Puesto: <input name="ID_Puesto" type="number"><br>
        Puesto: <input name="Puesto"><br>
    `;
    document.getElementById('formCampos').innerHTML =
        tablaActual === 'Empleados' ? empleados : puestos;
}