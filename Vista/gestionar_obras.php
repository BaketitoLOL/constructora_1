<?php
include '../modelo/db_connection.php';
session_start();

// Verificar si el empleado está autenticado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Empleado') {
    header("Location: login.php");
    exit;
}

// Obtener todas las obras
$query = "
    SELECT o.id_obra, o.fecha_inicio, o.anticipo, o.adeudo, o.total, o.estatus, 
           c.nombre AS cliente_nombre, c.apellido_paterno AS cliente_apellido
    FROM obras o
    INNER JOIN clientes c ON o.id_cliente = c.id_cliente
    ORDER BY o.fecha_inicio DESC
";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Obras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
    include 'navbar_empleados.php';
    ?>
<div class="container mt-5">
    <h1 class="mb-4">Gestión de Obras</h1>
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Fecha Inicio</th>
            <th>Total</th>
            <th>Estatus</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id_obra'] ?></td>
                    <td><?= htmlspecialchars($row['cliente_nombre'] . ' ' . $row['cliente_apellido']) ?></td>
                    <td><?= htmlspecialchars($row['fecha_inicio']) ?></td>
                    <td>$<?= number_format($row['total'], 2) ?></td>
                    <td><?= htmlspecialchars($row['estatus']) ?></td>
                    <td>
                        <a href="ver_contrato.php?id=<?= $row['id_obra'] ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Ver
                        </a>
                        <a href="generar_pdf_contrato.php?id=<?= $row['id_obra'] ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">No hay obras registradas.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
