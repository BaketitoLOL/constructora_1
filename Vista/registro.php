<?php
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['email'] ?? ''; // Aquí usamos "email" del formulario, pero verificamos contra "correo" en la tabla
    $password = $_POST['password'] ?? '';

    // Validar que el correo exista en la tabla clientes
    $query = $conn->prepare("SELECT id_cliente FROM clientes WHERE correo = ?");
    $query->bind_param("s", $correo);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 1) {
        // Crear cuenta en la tabla usuarios
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $rol = 'Cliente';

        $insert = $conn->prepare("INSERT INTO usuarios (email, password, rol, estatus) VALUES (?, ?, ?, 'Activo')");
        $insert->bind_param("sss", $correo, $passwordHash, $rol);
        $insert->execute();

        $success = "Cuenta creada exitosamente. Puedes iniciar sesión.";
    } else {
        $error = "Correo no encontrado en nuestra base de datos de clientes.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Cliente</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="login-container">
        <h1>Registro</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        <form action="registro.php" method="POST">
            <input type="email" name="email" placeholder="Correo Electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Registrarse</button>
        </form>
    </div>
</body>
</html>
