<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: Vista/login.php");
    exit;
}

$user = $_SESSION['user'];

echo "<h1>Bienvenido al sistema</h1>";
echo "<p>Usuario: " . htmlspecialchars($user['email']) . "</p>";
echo "<p>Rol: " . htmlspecialchars($user['rol']) . "</p>";

switch ($user['rol']) {
    case 'Administrador':
        echo "<a href='Vista/admin_dashboard.php'>Ir al Dashboard del Administrador</a>";
        break;
    case 'Empleado':
        echo "<a href='Vista/empleado_dashboard.php'>Ir al Dashboard del Empleado</a>";
        break;
    case 'Cliente':
        echo "<a href='Vista/cliente_dashboard.php'>Ir al Dashboard del Cliente</a>";
        break;
    default:
        echo "<p>Rol desconocido.</p>";
}
?>
