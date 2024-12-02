<?php
session_start();
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'login') {
        // Código de inicio de sesión
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $query = $conn->prepare("SELECT id_usuario, email, password, rol, estatus FROM usuarios WHERE email = ?");
        $query->bind_param("s", $email);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if ($user['estatus'] !== 'Activo') {
                $error = "Tu cuenta está inactiva. Contacta al administrador.";
            } elseif (password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id_usuario'],
                    'email' => $user['email'],
                    'rol' => $user['rol'],
                ];
                if ($user['rol'] === 'Administrador') {
                    header("Location: admin_dashboard.php");
                } elseif ($user['rol'] === 'Empleado') {
                    header("Location: empleado_dashboard.php");
                } else {
                    header("Location: cliente_dashboard.php");
                }
                exit;
            } else {
                $error = "Correo o contraseña incorrectos.";
            }
        } else {
            $error = "Usuario no encontrado.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="login-container">
        <h1>Iniciar Sesión</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <input type="hidden" name="action" value="login">
            <input type="email" name="email" placeholder="Correo Electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <p>
            <a href="recuperar.php">¿Olvidaste tu contraseña?</a>
        </p>
        <p>
            ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
        </p>
    </div>
</body>
</html>
