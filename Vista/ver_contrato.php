<?php
include '../modelo/db_connection.php';
session_start();

// Verificar si el empleado está autenticado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Empleado') {
    header("Location: login.php");
    exit;
}

// Verificar si se recibió el ID de la obra
if (!isset($_GET['id'])) {
    header("Location: gestionar_obras.php");
    exit;
}

$id_obra = intval($_GET['id']);

// Obtener detalles de la obra
$query_obra = "
    SELECT o.id_obra, o.fecha_inicio, o.anticipo, o.adeudo, o.total, o.estatus, o.observaciones, 
           c.nombre AS cliente_nombre, c.apellido_paterno AS cliente_apellido, c.correo AS cliente_correo, 
           d.calle, d.ciudad, d.estado, d.codigo_postal
    FROM obras o
    INNER JOIN clientes c ON o.id_cliente = c.id_cliente
    INNER JOIN direccion_obra d ON o.id_clave_secundaria = d.clave_secundaria
    WHERE o.id_obra = ?
";
$stmt_obra = $conn->prepare($query_obra);
$stmt_obra->bind_param("i", $id_obra);
$stmt_obra->execute();
$result_obra = $stmt_obra->get_result();
$obra = $result_obra->fetch_assoc();

if (!$obra) {
    header("Location: gestionar_obras.php");
    exit;
}

// Obtener detalles de los servicios asociados a la obra
$query_detalle = "
    SELECT do.cantidad, do.subtotal, s.nombre, s.precio
    FROM detalle_obras do
    INNER JOIN servicios s ON do.id_servicio = s.id_servicio
    WHERE do.id_obra = ?
";
$stmt_detalle = $conn->prepare($query_detalle);
$stmt_detalle->bind_param("i", $id_obra);
$stmt_detalle->execute();
$result_detalle = $stmt_detalle->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Contrato</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Detalles del Contrato</h1>

    <!-- Información del cliente -->
    <div class="mb-4">
        <h3>Información del Cliente</h3>
        <p><strong>Nombre:</strong> <?= htmlspecialchars($obra['cliente_nombre'] . ' ' . $obra['cliente_apellido']) ?></p>
        <p><strong>Correo:</strong> <?= htmlspecialchars($obra['cliente_correo']) ?></p>
    </div>

    <!-- Dirección de la obra -->
    <div class="mb-4">
        <h3>Dirección de la Obra</h3>
        <p><strong>Calle:</strong> <?= htmlspecialchars($obra['calle']) ?></p>
        <p><strong>Ciudad:</strong> <?= htmlspecialchars($obra['ciudad']) ?></p>
        <p><strong>Estado:</strong> <?= htmlspecialchars($obra['estado']) ?></p>
        <p><strong>Código Postal:</strong> <?= htmlspecialchars($obra['codigo_postal']) ?></p>
    </div>

    <!-- Información de la obra -->
    <div class="mb-4">
        <h3>Información de la Obra</h3>
        <p><strong>ID Obra:</strong> <?= htmlspecialchars($obra['id_obra']) ?></p>
        <p><strong>Fecha de Inicio:</strong> <?= htmlspecialchars($obra['fecha_inicio']) ?></p>
        <p><strong>Anticipo:</strong> $<?= number_format($obra['anticipo'], 2) ?></p>
        <p><strong>Adeudo:</strong> $<?= number_format($obra['adeudo'], 2) ?></p>
        <p><strong>Total:</strong> $<?= number_format($obra['total'], 2) ?></p>
        <p><strong>Estatus:</strong> <?= htmlspecialchars($obra['estatus']) ?></p>
        <p><strong>Observaciones:</strong> <?= htmlspecialchars($obra['observaciones'] ?? 'Ninguna') ?></p>
    </div>

    <!-- Detalles de los servicios -->
    <div class="mb-4">
        <h3>Servicios Asociados</h3>
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
    <a href="gestionar_obras.php" class="btn btn-secondary">Regresar</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
