function abrirModalNomina() {
    const modal = document.getElementById('modalNomina'); // Buscar el modal por ID
    if (modal) {
        modal.classList.add('active'); // Agregar la clase active para mostrar el modal
    } else {
        console.error("No se encontró el modal con ID 'modalNomina'");
    }
}

function cerrarModalNomina() {
    const modal = document.getElementById('modalNomina'); // Buscar el modal por ID
    if (modal) {
        modal.classList.remove('active'); // Quitar la clase active para ocultar el modal
    }
}

// Calcular sueldo semanal
document.addEventListener('DOMContentLoaded', () => {
    const empleadoSelect = document.getElementById('id_empleado');
    const diasTrabajadosInput = document.getElementById('dias_trabajados');
    const sueldoSemanalInput = document.getElementById('sueldo_semanal');

    function actualizarSueldoSemanal() {
        const empleadoSeleccionado = empleadoSelect.options[empleadoSelect.selectedIndex];
        const salarioMensual = parseFloat(empleadoSeleccionado.getAttribute('data-salario')) || 0;
        const sueldoDiario = salarioMensual / 30;
        const diasTrabajados = parseInt(diasTrabajadosInput.value, 10) || 0;

        sueldoSemanalInput.value = (sueldoDiario * diasTrabajados).toFixed(2);
    }

    empleadoSelect.addEventListener('change', actualizarSueldoSemanal);
    diasTrabajadosInput.addEventListener('input', actualizarSueldoSemanal);
});

// Búsqueda por empleado
function buscarPorEmpleado(event) {
    event.preventDefault();
    const empleadoId = document.getElementById('buscarEmpleado').value;

    fetch(`buscar_por_empleado.php?id_empleado=${empleadoId}`)
        .then(response => response.json())
        .then(data => {
            const resultadoDiv = document.getElementById('resultadoEmpleado');

            if (data.error) {
                resultadoDiv.innerHTML = `<p class="error">${data.error}</p>`;
                return;
            }

            const sueldoSemanal = data.sueldoSemanalActual
                ? `<p><strong>Sueldo Semanal Actual:</strong> $${data.sueldoSemanalActual.sueldo_semanal}</p>`
                : `<p>No se encontró un registro reciente.</p>`;

            const ultimosPagos = data.ultimosPagos.length > 0
                ? `
                    <h3>Últimos 4 Pagos:</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Semana</th>
                                <th>Días Trabajados</th>
                                <th>Sueldo Semanal</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.ultimosPagos.map(pago => `
                                <tr>
                                    <td>${pago.semana}</td>
                                    <td>${pago.dias_trabajados}</td>
                                    <td>$${pago.sueldo_semanal}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `
                : `<p>No hay pagos registrados.</p>`;

            resultadoDiv.innerHTML = sueldoSemanal + ultimosPagos;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('resultadoEmpleado').innerHTML = `<p class="error">Error al buscar los datos del empleado.</p>`;
        });
}

// Búsqueda por semana
function buscarPorSemana(event) {
    event.preventDefault();
    const semana = document.getElementById('buscarSemana').value;

    fetch(`buscar_por_semana.php?semana=${semana}`)
        .then(response => response.json())
        .then(data => {
            const resultadoDiv = document.getElementById('resultadoSemana');

            if (data.error) {
                resultadoDiv.innerHTML = `<p class="error">${data.error}</p>`;
                return;
            }

            if (data.length > 0) {
                const tabla = `
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Empleado</th>
                                <th>Días Trabajados</th>
                                <th>Sueldo Semanal</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.map(pago => `
                                <tr>
                                    <td>${pago.nombre} ${pago.apellido_paterno} ${pago.apellido_materno}</td>
                                    <td>${pago.dias_trabajados}</td>
                                    <td>$${pago.sueldo_semanal}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
                resultadoDiv.innerHTML = tabla;
            } else {
                resultadoDiv.innerHTML = `<p>No hay registros para la semana ${semana}.</p>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('resultadoSemana').innerHTML = `<p class="error">Error al buscar los datos de la semana.</p>`;
        });
}
