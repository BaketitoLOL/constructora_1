// Función para abrir el modal de empleado
function abrirModalEmpleado(accion, idEmpleado = null) {
    const modal = document.createElement('div');
    modal.classList.add('modal-overlay');
    modal.innerHTML = `
        <div class="modal">
            <h2>${accion === 'Agregar' ? 'Agregar Empleado' : 'Editar Empleado'}</h2>
            <form id="formEmpleado" method="POST" action="${accion === 'Agregar' ? 'agregar_empleado.php' : 'editar_empleado.php'}">
                ${idEmpleado ? `<input type="hidden" name="id_empleado" value="${idEmpleado}">` : ''}
                <label for="empleado_nombre">Nombre:</label>
                <input type="text" name="nombre" id="empleado_nombre" required>

                <label for="empleado_apellido_paterno">Apellido Paterno:</label>
                <input type="text" name="apellido_paterno" id="empleado_apellido_paterno" required>

                <label for="empleado_apellido_materno">Apellido Materno:</label>
                <input type="text" name="apellido_materno" id="empleado_apellido_materno">

                <label for="empleado_telefono">Teléfono:</label>
                <input type="text" name="telefono" id="empleado_telefono" required>

                <label for="empleado_correo">Correo:</label>
                <input type="email" name="correo_personal" id="empleado_correo" required>

                <label for="empleado_cargo">Cargo:</label>
                <input type="text" name="cargo" id="empleado_cargo">

                <label for="empleado_sucursal">Sucursal:</label>
                <select name="sucursal_asociada" id="empleado_sucursal" required>
                    <!-- Opciones cargadas por AJAX o PHP -->
                </select>

                <label for="empleado_salario">Salario:</label>
                <input type="number" step="0.01" name="salario" id="empleado_salario" required>

                <button type="submit" class="btn btn-primary">${accion === 'Agregar' ? 'Guardar' : 'Actualizar'}</button>
                <button type="button" class="btn btn-danger" onclick="cerrarModal()">Cancelar</button>
            </form>
        </div>
    `;
    document.body.appendChild(modal);

    if (accion === 'Editar' && idEmpleado) {
        precargarDatosEmpleado(idEmpleado);
    }
}

// Función para precargar datos en el modal (empleados)
function precargarDatosEmpleado(idEmpleado) {
    // Simulación de datos precargados
    const datosEmpleado = {
        nombre: 'Empleado Prueba',
        apellido_paterno: 'Pérez',
        apellido_materno: 'López',
        telefono: '5551234567',
        correo_personal: 'empleado@ejemplo.com',
        cargo: 'Supervisor',
        sucursal_asociada: 1,
        salario: 15000
    };

    document.getElementById('empleado_nombre').value = datosEmpleado.nombre;
    document.getElementById('empleado_apellido_paterno').value = datosEmpleado.apellido_paterno;
    document.getElementById('empleado_apellido_materno').value = datosEmpleado.apellido_materno;
    document.getElementById('empleado_telefono').value = datosEmpleado.telefono;
    document.getElementById('empleado_correo').value = datosEmpleado.correo_personal;
    document.getElementById('empleado_cargo').value = datosEmpleado.cargo;
    document.getElementById('empleado_sucursal').value = datosEmpleado.sucursal_asociada;
    document.getElementById('empleado_salario').value = datosEmpleado.salario;
}

// Función para cerrar el modal
function cerrarModal() {
    const modal = document.querySelector('.modal-overlay');
    if (modal) {
        document.body.removeChild(modal);
    }
}

// Función para eliminar empleado
function eliminarEmpleado(idEmpleado) {
    if (confirm('¿Estás seguro de que deseas eliminar este empleado?')) {
        window.location.href = `eliminar_empleado.php?id=${idEmpleado}`;
    }
}

function filtrarEmpleadosConBoton() {
    const input = document.getElementById('buscarEmpleado').value.toLowerCase();
    const filas = document.querySelectorAll('.table tbody tr');

    filas.forEach(fila => {
        const textoFila = fila.innerText.toLowerCase();
        fila.style.display = textoFila.includes(input) ? '' : 'none';
    });

    // Prevenir que el formulario recargue la página
    return false;
}
