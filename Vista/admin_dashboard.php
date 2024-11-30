<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'Administrador') {
    header("Location: login.php");
    exit;
}

echo "<h1>Dashboard del Administrador</h1>";
echo "<p>Bienvenido, " . htmlspecialchars($_SESSION['user']['email']) . "</p>";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="navigation">
        <ul>
            <li><a href="admin_dashboard.php">Inicio</a></li>
            <li><a href="gestionar_sucursales.php">Sucursales</a></li>
            <li><a href="gestionar_empleados.php">Empleados</a></li>
            <li><a href="gestionar_servicios.php">Servicios</a></li>
            <li><a href="gestionar_clientes.php">Clientes</a></li>
            <li><a href="../logout.php">Cerrar Sesión</a></li>
        </ul>
    </div>
    <div class="content">
        <h2>Gestión del Sistema</h2>
        <p>Seleccione una opción del menú para comenzar.</p>
    </div>
</body>
</html>
