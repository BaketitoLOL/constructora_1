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
                // Configurar variables de sesión
                $_SESSION['id_usuario'] = $user['id_usuario'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['rol'] = $user['rol'];

                // Redirigir según el rol
                switch ($user['rol']) {
                    case 'Administrador':
                        header("Location: admin_dashboard.php");
                        break;
                    case 'Empleado':
                        header("Location: empleado_dashboard.php");
                        break;
                        case 'Cliente':
                            // Obtener id_cliente relacionado con el usuario
                            $query_cliente = $conn->prepare("SELECT id_cliente FROM clientes WHERE correo = ?");
                            $query_cliente->bind_param("s", $user['email']);
                            $query_cliente->execute();
                            $result_cliente = $query_cliente->get_result();
                        
                            if ($result_cliente->num_rows === 1) {
                                $cliente = $result_cliente->fetch_assoc();
                                $_SESSION['id_cliente'] = $cliente['id_cliente']; // Guardar el id_cliente en la sesión
                                header("Location: dashboard_cliente.php");
                            } else {
                                $error = "No se encontró el cliente asociado a este usuario.";
                            }
                            break;
                        
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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px;">
            <h1 class="text-center mb-4">Iniciar Sesión</h1>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <input type="hidden" name="action" value="login">
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Correo Electrónico" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                </div>
            </form>
            <div class="text-center mt-3">
                <a href="recuperar.php" class="d-block mb-2">¿Olvidaste tu contraseña?</a>
                ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
