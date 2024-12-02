function abrirModalCliente(accion, idCliente = null) {
    const modal = document.createElement('div');
    modal.classList.add('modal-overlay');
    modal.innerHTML = `
        <div class="modal">
            <h2>${accion === 'Agregar' ? 'Agregar Cliente' : 'Editar Cliente'}</h2>
            <form id="formCliente" method="POST" action="${accion === 'Agregar' ? 'agregar_cliente.php' : 'editar_cliente.php'}">
                ${idCliente ? `<input type="hidden" name="id_cliente" value="${idCliente}">` : ''}
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" required>

                <label for="apellido_paterno">Apellido Paterno:</label>
                <input type="text" name="apellido_paterno" id="apellido_paterno" required>

                <label for="apellido_materno">Apellido Materno:</label>
                <input type="text" name="apellido_materno" id="apellido_materno">

                <label for="telefono_personal">Teléfono:</label>
                <input type="text" name="telefono_personal" id="telefono_personal" required>

                <label for="correo">Correo:</label>
                <input type="email" name="correo" id="correo" required>

                <h3>Dirección</h3>
                <label for="calle">Calle:</label>
                <input type="text" name="calle" id="calle">

                <label for="ciudad">Ciudad:</label>
                <input type="text" name="ciudad" id="ciudad">

                <label for="estado">Estado:</label>
                <input type="text" name="estado" id="estado">

                <label for="codigo_postal">Código Postal:</label>
                <input type="text" name="codigo_postal" id="codigo_postal">

                <button type="submit" class="btn btn-primary">${accion === 'Agregar' ? 'Guardar' : 'Actualizar'}</button>
                <button type="button" class="btn btn-danger" onclick="cerrarModalCliente()">Cancelar</button>
            </form>
        </div>
    `;
    document.body.appendChild(modal);

    if (accion === 'Editar' && idCliente) {
        precargarDatosCliente(idCliente);
    }
}

function precargarDatosCliente(idCliente) {
    // Implementar AJAX para cargar datos desde el servidor
    console.log('Precargar datos para cliente', idCliente);
}

function cerrarModalCliente() {
    const modal = document.querySelector('.modal-overlay');
    if (modal) {
        document.body.removeChild(modal);
    }
}

function eliminarCliente(idCliente) {
    if (confirm('¿Estás seguro de que deseas eliminar este cliente?')) {
        window.location.href = `eliminar_cliente.php?id=${idCliente}`;
    }
}

function validarFormularioCliente() {
    const nombre = document.getElementById('nombre').value.trim();
    const apellidoPaterno = document.getElementById('apellido_paterno').value.trim();
    const telefono = document.getElementById('telefono_personal').value.trim();
    const correo = document.getElementById('correo').value.trim();

    if (!nombre || !apellidoPaterno) {
        alert('El nombre y el apellido paterno son obligatorios.');
        return false;
    }
    if (!/^\d{10}$/.test(telefono)) {
        alert('El teléfono debe contener 10 dígitos.');
        return false;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo)) {
        alert('El correo no es válido.');
        return false;
    }
    return true;
}
function precargarDatosCliente(idCliente) {
    // Realizar una solicitud AJAX para obtener los datos del cliente
    fetch(`obtener_cliente.php?id=${idCliente}`)
        .then(response => response.json())
        .then(datos => {
            document.getElementById('nombre').value = datos.nombre;
            document.getElementById('apellido_paterno').value = datos.apellido_paterno;
            document.getElementById('apellido_materno').value = datos.apellido_materno || '';
            document.getElementById('telefono_personal').value = datos.telefono_personal;
            document.getElementById('correo').value = datos.correo;
            document.getElementById('calle').value = datos.calle || '';
            document.getElementById('ciudad').value = datos.ciudad || '';
            document.getElementById('estado').value = datos.estado || '';
            document.getElementById('codigo_postal').value = datos.codigo_postal || '';
        })
        .catch(error => {
            console.error('Error al cargar los datos del cliente:', error);
            alert('No se pudieron cargar los datos del cliente.');
        });
}
function filtrarClientes() {
    const input = document.getElementById('buscarCliente').value.toLowerCase();
    const filas = document.querySelectorAll('.table tbody tr');

    filas.forEach(fila => {
        const textoFila = fila.innerText.toLowerCase();
        fila.style.display = textoFila.includes(input) ? '' : 'none';
    });
}

function filtrarClientesConBoton(event) {
    event.preventDefault(); // Evitar que el formulario recargue la página
    filtrarClientes();
}
function cambiarEstatusCliente(idCliente, estatusActual) {
    const nuevoEstatus = estatusActual === 'Activo' ? 'Inactivo' : 'Activo';

    if (confirm(`¿Estás seguro de que deseas ${nuevoEstatus === 'Inactivo' ? 'inactivar' : 'reactivar'} este cliente?`)) {
        const formData = new FormData();
        formData.append('id_cliente', idCliente);
        formData.append('nuevo_estatus', nuevoEstatus);

        fetch('cambiar_estatus_cliente.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Cliente ${nuevoEstatus === 'Inactivo' ? 'inactivado' : 'reactivado'} correctamente.`);
                    location.reload();
                } else {
                    alert(`Error: ${data.error}`);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al cambiar el estatus del cliente.');
            });
    }
}

