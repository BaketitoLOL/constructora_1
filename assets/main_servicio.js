function abrirModalServicio(accion, idServicio = null) {
    const modal = document.createElement('div');
    modal.classList.add('modal-overlay');
    modal.innerHTML = `
        <div class="modal">
            <h2>${accion === 'Agregar' ? 'Agregar Servicio' : 'Editar Servicio'}</h2>
            <form id="formServicio" method="POST" enctype="multipart/form-data" action="${accion === 'Agregar' ? 'agregar_servicio.php' : 'editar_servicio.php'}">
                ${idServicio ? `<input type="hidden" name="id_servicio" value="${idServicio}">` : ''}
                ${idServicio ? `<input type="hidden" name="imagen_actual" id="imagen_actual">` : ''}
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" required>

                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion" id="descripcion" required></textarea>

                <label for="imagen">Imagen:</label>
                <input type="file" name="imagen" id="imagen">

                <button type="submit" class="btn btn-primary">${accion === 'Agregar' ? 'Guardar' : 'Actualizar'}</button>
                <button type="button" class="btn btn-danger" onclick="cerrarModal()">Cancelar</button>
            </form>
        </div>
    `;
    document.body.appendChild(modal);

    if (accion === 'Editar' && idServicio) {
        precargarDatosServicio(idServicio);
    }
}

function cambiarEstatusServicio(idServicio, estatusActual) {
    const nuevoEstatus = estatusActual === 'Activo' ? 'Inactivo' : 'Activo';

    if (confirm(`¿Estás seguro de que deseas ${nuevoEstatus === 'Inactivo' ? 'inactivar' : 'reactivar'} este servicio?`)) {
        const formData = new FormData();
        formData.append('id_servicio', idServicio);
        formData.append('nuevo_estatus', nuevoEstatus);

        fetch('cambiar_estatus_servicio.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Servicio ${nuevoEstatus === 'Inactivo' ? 'inactivado' : 'reactivado'} correctamente.`);
                    location.reload();
                } else {
                    alert(`Error: ${data.error}`);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al cambiar el estatus del servicio.');
            });
    }
}

function precargarDatosServicio(idServicio) {
    fetch(`obtener_servicio.php?id=${idServicio}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('nombre').value = data.nombre;
            document.getElementById('descripcion').value = data.descripcion;
            document.getElementById('imagen_actual').value = data.imagen;
        })
        .catch(error => {
            console.error('Error al cargar los datos del servicio:', error);
            alert('No se pudieron cargar los datos del servicio.');
        });
}

function cerrarModal() {
    const modal = document.querySelector('.modal-overlay');
    if (modal) {
        document.body.removeChild(modal);
    }
}
