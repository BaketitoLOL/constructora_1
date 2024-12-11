<?php
include '../modelo/db_connection.php';
session_start();

// Verificar si el empleado está autenticado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Empleado') {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Consultar datos del empleado asociado al usuario
$query = $conn->prepare("
    SELECT e.nombre, e.apellido_paterno, e.apellido_materno, e.telefono, e.correo_personal, e.cargo, u.email 
    FROM empleados e
    INNER JOIN usuarios u ON e.id_empleado = u.id_empleado
    WHERE u.id_usuario = ?
");
$query->bind_param("i", $id_usuario);
$query->execute();
$result = $query->get_result();
$empleado = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? $empleado['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'] ?? $empleado['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'] ?? $empleado['apellido_materno'];
    $telefono = $_POST['telefono'] ?? $empleado['telefono'];
    $correo_personal = $_POST['correo_personal'] ?? $empleado['correo_personal'];
    $cargo = $_POST['cargo'] ?? $empleado['cargo'];

    // Actualizar datos en la tabla empleados
    $update = $conn->prepare("
        UPDATE empleados 
        SET nombre = ?, apellido_paterno = ?, apellido_materno = ?, telefono = ?, correo_personal = ?, cargo = ? 
        WHERE id_empleado = (SELECT id_empleado FROM usuarios WHERE id_usuario = ?)
    ");
    $update->bind_param("ssssssi", $nombre, $apellido_paterno, $apellido_materno, $telefono, $correo_personal, $cargo, $id_usuario);
    if ($update->execute()) {
        $success = "Perfil actualizado exitosamente.";
        header("Refresh:0"); // Recargar para mostrar datos actualizados
    } else {
        $error = "Hubo un error al actualizar el perfil.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Empleados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <?php
    include 'navbar_empleados.php';
    ?>
    <div class="container">
        <h1 class="mb-4">Mi Perfil - Empleados</h1>
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form action="perfil_empleados.php" method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" value="<?= htmlspecialchars($empleado['nombre']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                <input type="text" class="form-control" name="apellido_paterno" id="apellido_paterno" value="<?= htmlspecialchars($empleado['apellido_paterno']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="apellido_materno" class="form-label">Apellido Materno</label>
                <input type="text" class="form-control" name="apellido_materno" id="apellido_materno" value="<?= htmlspecialchars($empleado['apellido_materno']) ?>">
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" name="telefono" id="telefono" value="<?= htmlspecialchars($empleado['telefono']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="correo_personal" class="form-label">Correo Personal</label>
                <input type="email" class="form-control" name="correo_personal" id="correo_personal" value="<?= htmlspecialchars($empleado['correo_personal']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="cargo" class="form-label">Cargo</label>
                <input type="text" class="form-control" name="cargo" id="cargo" value="<?= htmlspecialchars($empleado['cargo']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Actualizar</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
