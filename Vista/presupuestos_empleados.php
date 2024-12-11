<?php
include '../modelo/db_connection.php';
session_start();

// Verificar si el empleado está autenticado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Empleado') {
    header("Location: login.php");
    exit;
}

// Obtener todos los presupuestos
$query = "
    SELECT p.id_presupuesto, p.fecha_elaboracion, p.total, p.estatus, c.nombre AS cliente_nombre, c.apellido_paterno AS cliente_apellido 
    FROM presupuestos p
    INNER JOIN clientes c ON p.id_cliente = c.id_cliente
    ORDER BY p.fecha_elaboracion DESC
";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Presupuestos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
    include 'navbar_empleados.php';
    ?>
<div class="container mt-5">
    <h1 class="mb-4">Consultar Presupuestos</h1>
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Fecha</th>
            <th>Total</th>
            <th>Estatus</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id_presupuesto'] ?></td>
                    <td><?= htmlspecialchars($row['cliente_nombre'] . ' ' . $row['cliente_apellido']) ?></td>
                    <td><?= htmlspecialchars($row['fecha_elaboracion']) ?></td>
                    <td>$<?= number_format($row['total'], 2) ?></td>
                    <td><?= htmlspecialchars($row['estatus']) ?></td>
                    <td>
                        <a href="ver_presupuesto.php?id=<?= $row['id_presupuesto'] ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Ver
                        </a>
                        <a href="generar_pdf_presupuesto.php?id=<?= $row['id_presupuesto'] ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">No hay presupuestos registrados.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal para enviar presupuesto por correo -->
<div class="modal fade" id="enviarCorreoModal" tabindex="-1" aria-labelledby="enviarCorreoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="enviar_correo_presupuesto.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="enviarCorreoModalLabel">Enviar Presupuesto por Correo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_presupuesto" id="id_presupuesto">
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo del Cliente</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="cliente@ejemplo.com" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Llenar datos en el modal al hacer clic en "Enviar"
    const enviarCorreoModal = document.getElementById('enviarCorreoModal');
    enviarCorreoModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const idPresupuesto = button.getAttribute('data-id');
        const cliente = button.getAttribute('data-cliente');
        
        const modalTitle = enviarCorreoModal.querySelector('.modal-title');
        const inputIdPresupuesto = enviarCorreoModal.querySelector('#id_presupuesto');
        
        modalTitle.textContent = `Enviar presupuesto de ${cliente}`;
        inputIdPresupuesto.value = idPresupuesto;
    });
</script>
</body>
</html>
