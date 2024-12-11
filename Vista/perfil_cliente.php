<?php
session_start();
include '../modelo/db_connection.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Cliente') {
    header("Location: login.php");
    exit;
}

// Obtener información del cliente
$id_usuario = $_SESSION['id_usuario'];
$query = $conn->prepare("
    SELECT c.id_cliente, c.nombre, c.apellido_paterno, c.apellido_materno, c.telefono_personal, c.correo
    FROM clientes c
    INNER JOIN usuarios u ON c.id_cliente = u.id_cliente
    WHERE u.id_usuario = ?
");
$query->bind_param("i", $id_usuario);
$query->execute();
$result = $query->get_result();

if ($result->num_rows !== 1) {
    die("Error: No se encontró información del cliente.");
}

$cliente = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Perfil del Cliente</h1>
    <form method="POST" action="actualizar_perfil_cliente.php">
        <input type="hidden" name="id_cliente" value="<?= htmlspecialchars($cliente['id_cliente']) ?>">

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($cliente['nombre']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
            <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="<?= htmlspecialchars($cliente['apellido_paterno']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="apellido_materno" class="form-label">Apellido Materno</label>
            <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" value="<?= htmlspecialchars($cliente['apellido_materno']) ?>">
        </div>
        <div class="mb-3">
            <label for="telefono_personal" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono_personal" name="telefono_personal" value="<?= htmlspecialchars($cliente['telefono_personal']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="correo" name="correo" value="<?= htmlspecialchars($cliente['correo']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
