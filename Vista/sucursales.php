<?php
include '../modelo/db_connection.php';

// Consultar todas las sucursales con sus direcciones
$query = "
    SELECT s.id_sucursal, s.nombre, s.telefono, s.CorreoSucursal, s.PagWebSucursal,
           d.calle, d.ciudad, d.estado, d.codigo_postal
    FROM sucursales s
    LEFT JOIN direcciones d ON s.id_direccion = d.id_direccion
";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/styles.css">
    <title>Sucursales</title>
</head>
<body>
    <div class="dashboard-container">
        <main class="main-content">
            <header>
                <h1>Gestión de Sucursales</h1>
                <button class="btn btn-primary" onclick="abrirModalSucursal('Agregar')">Agregar Sucursal</button>
            </header>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Página Web</th>
                        <th>Dirección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nombre']) ?></td>
                            <td><?= htmlspecialchars($row['telefono']) ?></td>
                            <td><?= htmlspecialchars($row['CorreoSucursal']) ?></td>
                            <td><?= htmlspecialchars($row['PagWebSucursal'] ?? 'N/A') ?></td>
                            <td>
                                <?= htmlspecialchars($row['calle'] ?? 'Sin dirección') ?>,
                                <?= htmlspecialchars($row['ciudad'] ?? '') ?>,
                                <?= htmlspecialchars($row['estado'] ?? '') ?>,
                                <?= htmlspecialchars($row['codigo_postal'] ?? '') ?>
                            </td>
                            <td>
                                <button class="btn btn-warning" onclick="abrirModalSucursal('Editar', <?= $row['id_sucursal'] ?>)">Editar</button>
                                <button class="btn btn-danger" onclick="eliminarSucursal(<?= $row['id_sucursal'] ?>)">Eliminar</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </main>
    </div>
    <script src="../assets/main_sucursal.js"></script>
</body>
</html>
