<?php
include '../modelo/db_connection.php';
session_start();

// Verificar si el empleado está autenticado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Empleado') {
    header("Location: login.php");
    exit;
}

// Verificar si se recibió el ID del presupuesto
if (!isset($_GET['id'])) {
    header("Location: presupuestos_empleados.php");
    exit;
}

$id_presupuesto = intval($_GET['id']);

// Obtener detalles del presupuesto
$query_presupuesto = "
    SELECT p.id_presupuesto, p.fecha_elaboracion, p.total, p.estatus, p.observaciones, 
           c.nombre AS cliente_nombre, c.apellido_paterno AS cliente_apellido, c.correo AS cliente_correo
    FROM presupuestos p
    INNER JOIN clientes c ON p.id_cliente = c.id_cliente
    WHERE p.id_presupuesto = ?
";
$stmt_presupuesto = $conn->prepare($query_presupuesto);
$stmt_presupuesto->bind_param("i", $id_presupuesto);
$stmt_presupuesto->execute();
$result_presupuesto = $stmt_presupuesto->get_result();
$presupuesto = $result_presupuesto->fetch_assoc();

if (!$presupuesto) {
    header("Location: presupuestos_empleados.php");
    exit;
}

// Obtener servicios del presupuesto
$query_detalle = "
    SELECT dp.cantidad, dp.subtotal, s.nombre, s.precio
    FROM detalle_presupuesto dp
    INNER JOIN servicios s ON dp.id_servicio = s.id_servicio
    WHERE dp.id_presupuesto = ?
";
$stmt_detalle = $conn->prepare($query_detalle);
$stmt_detalle->bind_param("i", $id_presupuesto);
$stmt_detalle->execute();
$result_detalle = $stmt_detalle->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Presupuesto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Detalles del Presupuesto</h1>

    <!-- Información del cliente -->
    <div class="mb-4">
        <h3>Información del Cliente</h3>
        <p><strong>Nombre:</strong> <?= htmlspecialchars($presupuesto['cliente_nombre'] . ' ' . $presupuesto['cliente_apellido']) ?></p>
        <p><strong>Correo:</strong> <?= htmlspecialchars($presupuesto['cliente_correo']) ?></p>
    </div>

    <!-- Información del presupuesto -->
    <div class="mb-4">
        <h3>Información del Presupuesto</h3>
        <p><strong>ID Presupuesto:</strong> <?= htmlspecialchars($presupuesto['id_presupuesto']) ?></p>
        <p><strong>Fecha:</strong> <?= htmlspecialchars($presupuesto['fecha_elaboracion']) ?></p>
        <p><strong>Total:</strong> $<?= number_format($presupuesto['total'], 2) ?></p>
        <p><strong>Estatus:</strong> <?= htmlspecialchars($presupuesto['estatus']) ?></p>
        <p><strong>Observaciones:</strong> <?= htmlspecialchars($presupuesto['observaciones'] ?? 'Ninguna') ?></p>
    </div>

    <!-- Detalles de los servicios -->
    <div class="mb-4">
        <h3>Servicios Incluidos</h3>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Servicio</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $result_detalle->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                    <td><?= intval($row['cantidad']) ?></td>
                    <td>$<?= number_format($row['precio'], 2) ?></td>
                    <td>$<?= number_format($row['subtotal'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Botón para regresar -->
    <a href="presupuestos_empleados.php" class="btn btn-secondary">Regresar</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
