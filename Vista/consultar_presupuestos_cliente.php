<?php
include '../modelo/db_connection.php';
session_start();

// Verificar si el cliente está autenticado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Cliente') {
    header("Location: login.php");
    exit;
}

// Obtener los presupuestos asociados al cliente
$id_cliente = $_SESSION['user']['id'];
$query = "SELECT p.id_presupuesto, p.fecha_elaboracion, p.total, p.estatus FROM presupuestos p WHERE p.id_cliente = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Presupuestos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Mis Presupuestos</h1>
    <?php if ($result->num_rows > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id_presupuesto'] ?></td>
                    <td><?= $row['fecha_elaboracion'] ?></td>
                    <td>$<?= number_format($row['total'], 2) ?></td>
                    <td><?= $row['estatus'] ?></td>
                    <td>
                        <a href="ver_presupuesto_cliente.php?id=<?= $row['id_presupuesto'] ?>" class="btn btn-info btn-sm">Ver</a>
                        <a href="generar_pdf_presupuesto.php?id=<?= $row['id_presupuesto'] ?>" class="btn btn-secondary btn-sm">Descargar PDF</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="alert alert-warning">No tienes presupuestos registrados.</p>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
