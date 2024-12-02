function agregarFila() {
    const contenedor = document.getElementById('detallePresupuesto');
    
    // Crear un contenedor para la nueva fila
    const fila = document.createElement('div');
    fila.classList.add('detalle-item');
    
    // Agregar campos para el servicio, cantidad y subtotal
    fila.innerHTML = `
        <div class="form-group">
            <label for="id_servicio">Servicio:</label>
            <select name="id_servicio[]" class="id_servicio" required>
                <option value="" disabled selected>Seleccione un servicio</option>
                ${document.querySelector('.id_servicio').innerHTML}
            </select>
        </div>
        <div class="form-group">
            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad[]" class="cantidad" min="1" required>
        </div>
        <div class="form-group">
            <label for="subtotal">Subtotal:</label>
            <input type="text" name="subtotal[]" class="subtotal" readonly>
        </div>
    `;

    // Añadir la fila al contenedor
    contenedor.appendChild(fila);

    // Añadir eventos para recalcular el subtotal al cambiar cantidad o servicio
    fila.querySelector('.id_servicio').addEventListener('change', actualizarSubtotal);
    fila.querySelector('.cantidad').addEventListener('input', actualizarSubtotal);
}

document.addEventListener('input', (event) => {
    if (event.target.classList.contains('cantidad') || event.target.classList.contains('id_servicio')) {
        const fila = event.target.closest('.detalle-item');
        const cantidad = fila.querySelector('.cantidad').value || 0;
        const precio = fila.querySelector('.id_servicio').selectedOptions[0].dataset.precio || 0;
        const subtotal = fila.querySelector('.subtotal');
        subtotal.value = (cantidad * precio).toFixed(2);

        actualizarTotal();
    }
});

function actualizarTotal() {
    const subtotales = document.querySelectorAll('.subtotal');
    let total = 0;

    subtotales.forEach(subtotal => {
        total += parseFloat(subtotal.value) || 0;
    });

    document.getElementById('total').value = total.toFixed(2);
}

function actualizarSubtotal(event) {
    const fila = event.target.closest('.detalle-item');
    const servicioSeleccionado = fila.querySelector('.id_servicio').selectedOptions[0];
    const precio = parseFloat(servicioSeleccionado.getAttribute('data-precio')) || 0;
    const cantidad = parseInt(fila.querySelector('.cantidad').value, 10) || 0;
    const subtotal = fila.querySelector('.subtotal');

    subtotal.value = (precio * cantidad).toFixed(2);

    // Actualizar el total general
    actualizarTotal();
}
