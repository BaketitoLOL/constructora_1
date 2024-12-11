// Mostrar modal para agregar cliente
function mostrarAgregarModal() {
    const modalAgregar = document.getElementById('modal-agregar');
    modalAgregar.style.display = 'flex';

    // Limpiar los campos del formulario
    const formAgregar = document.getElementById('formAgregar');
    formAgregar.reset();
}

// Cerrar modal para agregar cliente
function cerrarAgregarModal() {
    const modalAgregar = document.getElementById('modal-agregar');
    modalAgregar.style.display = 'none';
}

// Mostrar modal para editar cliente
function mostrarEditarModal(idCliente) {
    const modalEditar = document.getElementById('modal-editar');
    modalEditar.style.display = 'flex';

    // Realizar solicitud para obtener datos del cliente
    fetch(`obtener_cliente.php?id=${idCliente}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                cerrarEditarModal();
                return;
            }

            // Llenar los campos del formulario con los datos del cliente
            document.getElementById('id_cliente_editar').value = data.id_cliente;
            document.getElementById('nombre_editar').value = data.nombre;
            document.getElementById('apellido_paterno_editar').value = data.apellido_paterno;
            document.getElementById('apellido_materno_editar').value = data.apellido_materno || '';
            document.getElementById('telefono_personal_editar').value = data.telefono_personal;
            document.getElementById('correo_editar').value = data.correo;
            document.getElementById('num_ext_editar').value = data.num_ext;
            document.getElementById('num_int_editar').value = data.num_int || '';
            document.getElementById('calle_editar').value = data.calle;
            document.getElementById('ciudad_editar').value = data.ciudad;
            document.getElementById('estado_editar').value = data.estado;
            document.getElementById('codigo_postal_editar').value = data.codigo_postal;
        })
        .catch(error => {
            console.error('Error al cargar los datos del cliente:', error);
            alert('No se pudieron cargar los datos del cliente.');
        });
}

// Cerrar modal para editar cliente
function cerrarEditarModal() {
    const modalEditar = document.getElementById('modal-editar');
    modalEditar.style.display = 'none';
}

// Eliminar cliente
function eliminarCliente(idCliente) {
    if (confirm('¿Estás seguro de eliminar este cliente?')) {
        fetch(`eliminar_cliente.php?id=${idCliente}`, { method: 'POST' })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            })
            .catch(error => {
                console.error('Error al eliminar el cliente:', error);
                alert('No se pudo eliminar el cliente.');
            });
    }
}
function cambiarEstatusCliente(idCliente, estatusActual) {
    const nuevoEstatus = estatusActual === 'Activo' ? 'Inactivo' : 'Activo';
    if (confirm(`¿Estás seguro de ${nuevoEstatus === 'Activo' ? 'activar' : 'inactivar'} este cliente?`)) {
        fetch(`cambiar_estatus_cliente.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id_cliente=${idCliente}&estatus=${nuevoEstatus}`,
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('No se pudo cambiar el estatus del cliente.');
        });
    }
}
function eliminarCliente(idCliente) {
    if (confirm('¿Estás seguro de eliminar este cliente permanentemente?')) {
        fetch(`eliminar_cliente.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id_cliente=${idCliente}`,
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('No se pudo eliminar el cliente.');
        });
    }
}
