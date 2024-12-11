// Abrir modal para agregar o editar sucursal
function abrirModalSucursal(accion, idSucursal = null) {
    const modal = document.getElementById('modal-sucursal');
    const titulo = document.getElementById('modal-titulo');
    const form = document.getElementById('formSucursal');

    // Configurar el título y la acción del formulario
    if (accion === 'Agregar') {
        titulo.textContent = 'Agregar Sucursal';
        form.action = 'agregar_sucursal.php';
        limpiarCamposSucursal();
    } else if (accion === 'Editar') {
        titulo.textContent = 'Editar Sucursal';
        form.action = 'editar_sucursal.php';
        cargarDatosSucursal(idSucursal);
    }

    // Mostrar el modal
    modal.style.display = 'flex';
}

// Cerrar modal
function cerrarModalSucursal() {
    const modal = document.getElementById('modal-sucursal');
    modal.style.display = 'none';
}

// Limpiar los campos del formulario
function limpiarCamposSucursal() {
    console.log('Limpiando campos del formulario...');
    document.getElementById('id_sucursal').value = '';
    document.getElementById('nombre').value = '';
    document.getElementById('telefono').value = '';
    document.getElementById('CorreoSucursal').value = '';
    document.getElementById('PagWebSucursal').value = '';
    document.getElementById('num_ext').value = '';
    document.getElementById('num_int').value = '';
    document.getElementById('calle').value = '';
    document.getElementById('ciudad').value = '';
    document.getElementById('estado').value = '';
    document.getElementById('codigo_postal').value = '';
}

// Cargar datos de sucursal para edición
function cargarDatosSucursal(idSucursal) {
    fetch(`obtener_sucursales.php?id=${idSucursal}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener los datos de la sucursal');
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            // Precargar datos en el formulario
            document.getElementById('id_sucursal').value = data.id_sucursal;
            document.getElementById('nombre').value = data.nombre;
            document.getElementById('telefono').value = data.telefono;
            document.getElementById('CorreoSucursal').value = data.CorreoSucursal;
            document.getElementById('PagWebSucursal').value = data.PagWebSucursal || '';
            document.getElementById('num_ext').value = data.num_ext;
            document.getElementById('num_int').value = data.num_int || '';
            document.getElementById('calle').value = data.calle;
            document.getElementById('ciudad').value = data.ciudad;
            document.getElementById('estado').value = data.estado;
            document.getElementById('codigo_postal').value = data.codigo_postal;
        })
        .catch(error => {
            console.error('Error al cargar los datos de la sucursal:', error);
            alert('No se pudieron cargar los datos de la sucursal.');
        });
}


// Eliminar sucursal con confirmación
function eliminarSucursal(idSucursal) {
    if (confirm('¿Estás seguro de que deseas eliminar esta sucursal?')) {
        fetch(`eliminar_sucursal.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id=${idSucursal}`
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al eliminar la sucursal');
                }
                return response.text();
            })
            .then(data => {
                alert(data || 'Sucursal eliminada exitosamente.');
                location.reload(); // Recargar la página para reflejar los cambios
            })
            .catch(error => {
                console.error('Error al eliminar la sucursal:', error);
                alert('No se pudo eliminar la sucursal.');
            });
    }
}

// Validar el formulario antes de enviarlo
function validarFormularioSucursal() {
    const nombre = document.getElementById('nombre').value.trim();
    const telefono = document.getElementById('telefono').value.trim();
    const correo = document.getElementById('CorreoSucursal').value.trim();
    const calle = document.getElementById('calle').value.trim();
    const ciudad = document.getElementById('ciudad').value.trim();
    const estado = document.getElementById('estado').value.trim();
    const codigoPostal = document.getElementById('codigo_postal').value.trim();

    if (!nombre || !telefono || !correo || !calle || !ciudad || !estado || !codigoPostal) {
        alert('Todos los campos son obligatorios.');
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

// Asignar validación al formulario
document.getElementById('formSucursal').onsubmit = validarFormularioSucursal;
