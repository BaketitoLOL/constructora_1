<?php
session_start();
include '../modelo/db_connection.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Cliente') {
    header("Location: login.php");
    exit;
}

$id_cliente = $_SESSION['id_cliente'] ?? null;
if (!$id_cliente) {
    echo "Error: No se encontró información del cliente.";
    exit;
}

// Consultar las obras del cliente
$query_obras = $conn->prepare("SELECT o.fecha_inicio, o.anticipo, o.total, o.observaciones, o.estatus, CONCAT(c.nombre, ' ', c.apellido_paterno) AS nombre_cliente
                              FROM obras o
                              INNER JOIN clientes c ON o.id_cliente = c.id_cliente
                              WHERE o.id_cliente = ?");
$query_obras->bind_param("i", $id_cliente);
$query_obras->execute();
$result_obras = $query_obras->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Obras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar_clients.php';?>
<div class="container mt-5">
    <h1 class="mb-4">Mis Obras</h1>
    <?php if ($result_obras->num_rows > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre del Cliente</th>
                    <th>Fecha de Inicio</th>
                    <th>Anticipo</th>
                    <th>Total</th>
                    <th>Observaciones</th>
                    <th>Estatus</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($obra = $result_obras->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($obra['nombre_cliente']) ?></td>
                        <td><?= htmlspecialchars($obra['fecha_inicio']) ?></td>
                        <td>$<?= number_format($obra['anticipo'], 2) ?></td>
                        <td>$<?= number_format($obra['total'], 2) ?></td>
                        <td><?= htmlspecialchars($obra['observaciones']) ?></td>
                        <td><?= htmlspecialchars($obra['estatus']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No se encontraron obras asociadas a tu cuenta.</p>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
