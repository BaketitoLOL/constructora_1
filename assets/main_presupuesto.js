function agregarDetalle() {
    const detallesContainer = document.getElementById('detalles-container');

    const nuevaFila = document.createElement('tr');
    nuevaFila.innerHTML = `
        <td>
            <select name="id_servicio[]" required>
                <option value="" disabled selected>Seleccione un servicio</option>
                ${servicios.map(servicio => `
                    <option value="${servicio.id}">${servicio.nombre}</option>
                `).join('')}
            </select>
        </td>
        <td>
            <input type="number" name="cantidad[]" min="1" oninput="calcularSubtotal(this)" required>
        </td>
        <td>
            <input type="number" name="precio_unitario[]" step="0.01" oninput="calcularSubtotal(this)" required>
        </td>
        <td>
            <input type="number" name="subtotal[]" step="0.01" readonly>
        </td>
        <td>
            <button type="button" class="btn btn-danger" onclick="eliminarDetalle(this)">Eliminar</button>
        </td>
    `;

    detallesContainer.appendChild(nuevaFila);
}

function calcularSubtotal(elemento) {
    const fila = elemento.closest('tr');
    const cantidad = parseFloat(fila.querySelector('input[name="cantidad[]"]').value) || 0;
    const precio = parseFloat(fila.querySelector('input[name="precio_unitario[]"]').value) || 0;
    const subtotalInput = fila.querySelector('input[name="subtotal[]"]');

    const subtotal = cantidad * precio;
    subtotalInput.value = subtotal.toFixed(2);

    actualizarTotal();
}

function actualizarTotal() {
    const subtotales = document.querySelectorAll('input[name="subtotal[]"]');
    let total = 0;

    subtotales.forEach(subtotal => {
        total += parseFloat(subtotal.value) || 0;
    });

    document.getElementById('total').textContent = total.toFixed(2);
}

function eliminarDetalle(boton) {
    const fila = boton.closest('tr');
    fila.remove();
    actualizarTotal();
}
