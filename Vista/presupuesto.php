<?php
// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'sistema_constructora');
if ($conn->connect_error)
    die('Error de conexión: ' . $conn->connect_error);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Presupuestos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body>
<?php require 'navbar.php' ?>
    <div class="container mt-5">
        <h1>Gestión de Presupuestos</h1>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPresupuestoModal">
            <i class="fas fa-plus"></i> Agregar Presupuesto
        </button>
        <table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Dirección</th>
            <th>Fecha</th>
            <th>Total</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT p.id_presupuesto, c.nombre AS cliente, d.calle AS direccion, 
                         p.fecha_elaboracion, p.total
                  FROM presupuestos p
                  INNER JOIN clientes c ON p.id_cliente = c.id_cliente
                  INNER JOIN direccion_obra d ON p.id_direccion = d.id_direccion";
        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()):
            $id_presupuesto = htmlspecialchars($row['id_presupuesto']);
            $file_path = "../pdf/Proposal_" . $id_presupuesto . ".pdf";
        ?>
        <tr>
            <td><?= $row['id_presupuesto'] ?></td>
            <td><?= htmlspecialchars($row['cliente']) ?></td>
            <td><?= htmlspecialchars($row['direccion']) ?></td>
            <td><?= htmlspecialchars($row['fecha_elaboracion']) ?></td>
            <td>$<?= number_format($row['total'], 2) ?></td>
            <td>
                <!-- Botón para editar -->
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPresupuestoModal">
                    <i class="fas fa-edit"></i> Editar
                </button>
                <!-- Botón para eliminar -->
                <button class="btn btn-danger btn-sm" onclick="confirmarEliminacion(<?= $id_presupuesto ?>)">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
                <!-- Generar PDF -->
                <a href="generar_pdf_presupuesto.php?id_presupuesto=<?= $id_presupuesto ?>" class="btn btn-success btn-sm">
                    <i class="fas fa-file-pdf"></i> Generar PDF
                </a>
                <a href="enviar_correo_propuesta.php?id=<?= $id_presupuesto ?>&file=<?= urlencode($file_path) ?>" 
                   class="btn btn-secondary btn-sm send-button" 
                   title="Enviar PDF" 
                   data-folio="<?= $id_presupuesto ?>" 
                   onclick="enviarPDF(this)">
                    <i class="fas fa-envelope"></i> Enviar Presupuesto
                </a>
                <!-- Visualizar PDF si existe -->
                <?php if (file_exists($file_path)): ?>
                    <a href="<?= htmlspecialchars($file_path) ?>" target="_blank" class="btn btn-info btn-sm" title="Ver PDF">
                        <i class="fas fa-eye"></i> Ver Presupuesto
                    </a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
    </div>

    <!-- Modal: Agregar Presupuesto -->
    <div class="modal fade" id="addPresupuestoModal" tabindex="-1" aria-labelledby="addPresupuestoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPresupuestoModalLabel">Agregar Presupuesto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="guardar_presupuesto.php">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="cliente" class="form-label">Cliente</label>
                            <select name="id_cliente" id="cliente" class="form-control" required>
                                <!-- Opciones cargadas dinámicamente -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección de Obra</label>
                            <select name="id_direccion" id="direccion" class="form-control" required>
                                <!-- Opciones cargadas dinámicamente -->
                            </select>
                        </div>
                        <h4>Detalles del Presupuesto</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Servicio</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Subtotal</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="detalles-container">
                                <!-- Filas dinámicas -->
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-success mb-3" onclick="agregarFila()">Agregar
                            Servicio</button>
                        <h4>Total: $<span id="total">0.00</span></h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Presupuesto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editPresupuestoModal" tabindex="-1" aria-labelledby="editPresupuestoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPresupuestoModalLabel">Editar Presupuesto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="editar_presupuesto.php">
                    <input type="hidden" name="id_presupuesto" id="edit_id_presupuesto">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_cliente" class="form-label">Cliente</label>
                            <select name="id_cliente" id="edit_cliente" class="form-control" required></select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_direccion" class="form-label">Dirección de Obra</label>
                            <select name="id_direccion" id="edit_direccion" class="form-control" required></select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_observaciones" class="form-label">Observaciones</label>
                            <textarea name="observaciones" id="edit_observaciones" class="form-control"></textarea>
                        </div>
                        <h4>Detalles del Presupuesto</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Servicio</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Subtotal</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="edit_detalles_container">
                                <!-- Filas dinámicas cargadas -->
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-success mb-3" onclick="agregarFilaEditar()">Agregar
                            Servicio</button>
                        <h4>Total: $<span id="edit_total">0.00</span></h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            cargarClientes();

            document.getElementById('cliente').addEventListener('change', function () {
                cargarDirecciones(this.value);
            });
        });

        async function cargarClientes() {
            try {
                const response = await fetch('obtener_clientes.php');
                const clientes = await response.json();

                const clienteSelect = document.getElementById('cliente');
                clienteSelect.innerHTML = clientes.map(cliente => `
            <option value="${cliente.id_cliente}">${cliente.nombre}</option>
        `).join('');

                // Cargar direcciones del primer cliente automáticamente
                if (clientes.length > 0) {
                    cargarDirecciones(clientes[0].id_cliente);
                }
            } catch (error) {
                console.error("Error al cargar clientes:", error);
            }
        }

        async function cargarDirecciones(id_cliente) {
            try {
                const response = await fetch(`obtener_direcciones_obra.php?id_cliente=${id_cliente}`);
                const direcciones = await response.json();

                const direccionSelect = document.getElementById('direccion');
                direccionSelect.innerHTML = direcciones.map(direccion => `
            <option value="${direccion.id_direccion}">${direccion.direccion}</option>
        `).join('');
            } catch (error) {
                console.error("Error al cargar direcciones:", error);
            }
        }

        async function cargarServicios(selector) {
            try {
                const response = await fetch('obtener_servicios.php');
                const servicios = await response.json();

                selector.innerHTML = servicios.map(servicio => `
            <option value="${servicio.id_servicio}" data-precio="${servicio.precio}">
                ${servicio.nombre} - $${servicio.precio}
            </option>
        `).join('');
            } catch (error) {
                console.error("Error al cargar servicios:", error);
            }
        }

        function agregarFila() {
            const container = document.getElementById("detalles-container");
            const fila = document.createElement("tr");
            fila.innerHTML = `
        <td>
            <select name="id_servicios[]" class="form-control servicio-selector" required></select>
        </td>
        <td><input type="number" name="cantidades[]" class="form-control cantidad-input" placeholder="Cantidad" required></td>
        <td><input type="number" step="0.01" name="precios[]" class="form-control precio-input" placeholder="Precio Unitario" required></td>
        <td><input type="number" step="0.01" name="subtotales[]" class="form-control subtotal-input" readonly></td>
        <td><button type="button" class="btn btn-danger" onclick="eliminarFila(this)">Eliminar</button></td>
    `;
            container.appendChild(fila);

            // Cargar servicios en el selector
            const servicioSelector = fila.querySelector(".servicio-selector");
            cargarServicios(servicioSelector);

            // Eventos para calcular subtotal y total
            fila.querySelector('.cantidad-input').addEventListener('input', function () {
                calcularSubtotal(fila);
            });
            fila.querySelector('.precio-input').addEventListener('input', function () {
                calcularSubtotal(fila);
            });
        }


        fila.querySelector('.cantidad-input').addEventListener('input', function () {
            calcularSubtotal(fila);
        });

        function calcularSubtotal(fila) {
            const cantidad = parseFloat(fila.querySelector('.cantidad-input').value) || 0;
            const precio = parseFloat(fila.querySelector('.precio-input').value) || 0;
            const subtotal = cantidad * precio;
            fila.querySelector('.subtotal-input').value = subtotal.toFixed(2);
            calcularTotal();
        }


        function calcularTotal() {
            const subtotales = document.querySelectorAll('.subtotal-input');
            let total = 0;
            subtotales.forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            document.getElementById('total').textContent = total.toFixed(2);
        }

        function confirmarEliminacion(id_presupuesto) {
    if (confirm('¿Estás seguro de que deseas eliminar este presupuesto? Esta acción no se puede deshacer.')) {
        window.location.href = `eliminar_presupuesto.php?id_presupuesto=${id_presupuesto}`;
    }
}

    </script>
    <script>

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>