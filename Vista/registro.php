<?php 
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Verificar si el correo pertenece a un empleado
    $queryEmpleado = $conn->prepare("SELECT id_empleado FROM empleados WHERE correo_personal = ?");
    $queryEmpleado->bind_param("s", $correo);
    $queryEmpleado->execute();
    $resultEmpleado = $queryEmpleado->get_result();

    // Verificar si el correo pertenece a un cliente
    $queryCliente = $conn->prepare("SELECT id_cliente FROM clientes WHERE correo = ?");
    $queryCliente->bind_param("s", $correo);
    $queryCliente->execute();
    $resultCliente = $queryCliente->get_result();

    if ($resultEmpleado->num_rows === 1) {
        $rowEmpleado = $resultEmpleado->fetch_assoc();
        $id_empleado = $rowEmpleado['id_empleado'];

        // Verificar que el empleado no tenga ya una cuenta
        $checkUser = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
        $checkUser->bind_param("s", $correo);
        $checkUser->execute();
        $userResult = $checkUser->get_result();

        if ($userResult->num_rows === 0) {
            // Registrar al empleado
            $rol = 'Empleado';
            $insert = $conn->prepare("INSERT INTO usuarios (email, password, rol, estatus, id_empleado) VALUES (?, ?, ?, 'Activo', ?)");
            $insert->bind_param("sssi", $correo, $passwordHash, $rol, $id_empleado);
            $insert->execute();
            $success = "Cuenta de empleado creada exitosamente.";
        } else {
            $error = "Este correo ya está asociado a una cuenta.";
        }
    } elseif ($resultCliente->num_rows === 1) {
        $rowCliente = $resultCliente->fetch_assoc();
        $id_cliente = $rowCliente['id_cliente'];

        // Verificar que el cliente no tenga ya una cuenta
        $checkUser = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
        $checkUser->bind_param("s", $correo);
        $checkUser->execute();
        $userResult = $checkUser->get_result();

        if ($userResult->num_rows === 0) {
            // Registrar al cliente
            $rol = 'Cliente';
            $insert = $conn->prepare("INSERT INTO usuarios (email, password, rol, estatus, id_cliente) VALUES (?, ?, ?, 'Activo', ?)");
            $insert->bind_param("sssi", $correo, $passwordHash, $rol, $id_cliente);
            $insert->execute();
            $success = "Cuenta de cliente creada exitosamente.";
        } else {
            $error = "Este correo ya está asociado a una cuenta.";
        }
    } else {
        $error = "Correo no encontrado en nuestra base de datos de empleados o clientes.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .login-container h1 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Registro</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <form action="registro.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Correo Electrónico" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Registrarse</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
