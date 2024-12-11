// Función para mostrar el modal de agregar empleado
function mostrarAgregarModal() {
    limpiarCamposAgregar();
    const modal = document.getElementById('modal-agregar');
    modal.style.display = 'flex';
}

// Función para cerrar el modal de agregar empleado
function cerrarAgregarModal() {
    const modal = document.getElementById('modal-agregar');
    modal.style.display = 'none';
}

// Función para mostrar el modal de editar empleado
function mostrarEditarModal(idEmpleado) {
    limpiarCamposEditar();
    const modal = document.getElementById('modal-editar');
    modal.style.display = 'flex';

    // Precargar datos del empleado
    fetch(`obtener_empleado.php?id_empleado=${idEmpleado}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                cerrarEditarModal();
                return;
            }
            // Precargar campos con los datos del empleado
            document.getElementById('id_empleado_editar').value = data.id_empleado;
            document.getElementById('nombre_editar').value = data.nombre;
            document.getElementById('apellido_paterno_editar').value = data.apellido_paterno;
            document.getElementById('apellido_materno_editar').value = data.apellido_materno;
            document.getElementById('telefono_editar').value = data.telefono;
            document.getElementById('correo_editar').value = data.correo_personal;
            document.getElementById('cargo_editar').value = data.cargo;
            document.getElementById('sucursal_editar').value = data.sucursal_asociada;
            document.getElementById('hora_entrada_editar').value = data.hora_entrada;
            document.getElementById('hora_salida_editar').value = data.hora_salida;
            document.getElementById('salario_editar').value = data.salario;
        })
        .catch(error => {
            console.error('Error al obtener los datos del empleado:', error);
            alert('No se pudieron cargar los datos del empleado.');
            cerrarEditarModal();
        });
}

// Función para cerrar el modal de editar empleado
function cerrarEditarModal() {
    const modal = document.getElementById('modal-editar');
    modal.style.display = 'none';
}

// Función para limpiar los campos del formulario de agregar
function limpiarCamposAgregar() {
    document.getElementById('nombre_agregar').value = '';
    document.getElementById('apellido_paterno_agregar').value = '';
    document.getElementById('apellido_materno_agregar').value = '';
    document.getElementById('telefono_agregar').value = '';
    document.getElementById('correo_agregar').value = '';
    document.getElementById('cargo_agregar').value = '';
    document.getElementById('sucursal_agregar').selectedIndex = 0;
    document.getElementById('hora_entrada_agregar').value = '';
    document.getElementById('hora_salida_agregar').value = '';
    document.getElementById('salario_agregar').value = '';
}

// Función para limpiar los campos del formulario de editar
function limpiarCamposEditar() {
    document.getElementById('id_empleado_editar').value = '';
    document.getElementById('nombre_editar').value = '';
    document.getElementById('apellido_paterno_editar').value = '';
    document.getElementById('apellido_materno_editar').value = '';
    document.getElementById('telefono_editar').value = '';
    document.getElementById('correo_editar').value = '';
    document.getElementById('cargo_editar').value = '';
    document.getElementById('sucursal_editar').selectedIndex = 0;
    document.getElementById('hora_entrada_editar').value = '';
    document.getElementById('hora_salida_editar').value = '';
    document.getElementById('salario_editar').value = '';
}

// Función para cambiar el estatus del empleado
function cambiarEstatusEmpleado(idEmpleado, estatusActual) {
    const nuevoEstatus = estatusActual === 'Activo' ? 'Inactivo' : 'Activo';
    if (confirm(`¿Estás seguro de ${nuevoEstatus === 'Activo' ? 'activar' : 'inactivar'} este empleado?`)) {
        fetch(`cambiar_estatus_empleado.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id_empleado=${idEmpleado}&estatus=${nuevoEstatus}`,
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('No se pudo cambiar el estatus del empleado.');
        });
    }
}

// Función para eliminar un empleado
function eliminarEmpleado(idEmpleado) {
    if (confirm('¿Estás seguro de eliminar este empleado permanentemente?')) {
        fetch(`eliminar_empleado.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id_empleado=${idEmpleado}`,
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('No se pudo eliminar el empleado.');
        });
    }
}
