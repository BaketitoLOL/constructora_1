// Función para abrir el modal de sucursal
function abrirModalSucursal(accion, idSucursal = null) {
    const modal = document.createElement('div');
    modal.classList.add('modal-overlay');
    modal.innerHTML = `
        <div class="modal">
            <h2>${accion === 'Agregar' ? 'Agregar Sucursal' : 'Editar Sucursal'}</h2>
            <form id="formSucursal" method="POST" action="${accion === 'Agregar' ? 'agregar_sucursal.php' : 'editar_sucursal.php'}">
                ${idSucursal ? `<input type="hidden" name="id_sucursal" value="${idSucursal}">` : ''}
                <label for="sucursal_nombre">Nombre:</label>
                <input type="text" name="nombre" id="sucursal_nombre" required>

                <label for="sucursal_telefono">Teléfono:</label>
                <input type="text" name="telefono" id="sucursal_telefono" required>

                <label for="sucursal_correo">Correo:</label>
                <input type="email" name="CorreoSucursal" id="sucursal_correo" required>

                <label for="sucursal_pagina">Página Web:</label>
                <input type="text" name="PagWebSucursal" id="sucursal_pagina">

                <h3>Dirección</h3>
                <label for="sucursal_calle">Calle:</label>
                <input type="text" name="calle" id="sucursal_calle">

                <label for="sucursal_ciudad">Ciudad:</label>
                <input type="text" name="ciudad" id="sucursal_ciudad">

                <label for="sucursal_estado">Estado:</label>
                <input type="text" name="estado" id="sucursal_estado">

                <label for="sucursal_codigo">Código Postal:</label>
                <input type="text" name="codigo_postal" id="sucursal_codigo">

                <button type="submit" class="btn btn-primary">${accion === 'Agregar' ? 'Guardar' : 'Actualizar'}</button>
                <button type="button" class="btn btn-danger" onclick="cerrarModal()">Cancelar</button>
            </form>
        </div>
    `;
    document.body.appendChild(modal);

    if (accion === 'Editar' && idSucursal) {
        precargarDatosSucursal(idSucursal);
    }
}

// Función para precargar datos en el modal (sucursales)
function precargarDatosSucursal(idSucursal) {
    // Simulación de datos precargados
    const datosSucursal = {
        nombre: 'Sucursal Prueba',
        telefono: '5551234567',
        CorreoSucursal: 'correo@sucursal.com',
        PagWebSucursal: 'http://ejemplo.com',
        calle: 'Calle Falsa 123',
        ciudad: 'Ciudad Ejemplo',
        estado: 'Estado Ejemplo',
        codigo_postal: '12345'
    };

    document.getElementById('sucursal_nombre').value = datosSucursal.nombre;
    document.getElementById('sucursal_telefono').value = datosSucursal.telefono;
    document.getElementById('sucursal_correo').value = datosSucursal.CorreoSucursal;
    document.getElementById('sucursal_pagina').value = datosSucursal.PagWebSucursal;
    document.getElementById('sucursal_calle').value = datosSucursal.calle;
    document.getElementById('sucursal_ciudad').value = datosSucursal.ciudad;
    document.getElementById('sucursal_estado').value = datosSucursal.estado;
    document.getElementById('sucursal_codigo').value = datosSucursal.codigo_postal;
}

// Función para cerrar el modal
function cerrarModal() {
    const modal = document.querySelector('.modal-overlay');
    if (modal) {
        document.body.removeChild(modal);
    }
}

// Función para eliminar sucursal
function eliminarSucursal(idSucursal) {
    if (confirm('¿Estás seguro de que deseas eliminar esta sucursal?')) {
        window.location.href = `eliminar_sucursal.php?id=${idSucursal}`;
    }
}
