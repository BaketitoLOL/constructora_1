<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'Administrador') {
    header("Location: login.php");
    exit;
}

require '../Modelo/db_connection.php';

// Obtener todas las sucursales
$stmt = $conn->query("SELECT * FROM sucursales");
$sucursales = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Sucursales</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Gestión de Sucursales</h1>
    <a href="admin_dashboard.php">Volver al Dashboard</a>
    <h2>Registrar Nueva Sucursal</h2>
    <form action="registrar_sucursal.php" method="POST">
        <input type="text" name="nombre" placeholder="Nombre de la Empresa" required>
        <input type="text" name="telefono" placeholder="Teléfono" required>
        <input type="email" name="correo" placeholder="Correo Electrónico" required>
        <input type="text" name="pagina" placeholder="Página Web (opcional)">
        <input type="text" name="direccion" placeholder="Dirección" required>
        <button type="submit">Registrar</button>
    </form>

    <h2>Lista de Sucursales</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Página Web</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($sucursales as $sucursal): ?>
        <tr>
            <td><?= htmlspecialchars($sucursal['id_sucursal']) ?></td>
            <td><?= htmlspecialchars($sucursal['nombre']) ?></td>
            <td><?= htmlspecialchars($sucursal['telefono']) ?></td>
            <td><?= htmlspecialchars($sucursal['CorreoSucursal']) ?></td>
            <td><?= htmlspecialchars($sucursal['PagWebSucursal'] ?? 'N/A') ?></td>
            <td>
                <a href="editar_sucursal.php?id=<?= $sucursal['id_sucursal'] ?>">Editar</a>
                <a href="eliminar_sucursal.php?id=<?= $sucursal['id_sucursal'] ?>" onclick="return confirm('¿Estás seguro de eliminar esta sucursal?')">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
