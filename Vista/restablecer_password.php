<?php
require '../modelo/db_connection.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verificar si el token es válido
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE reset_token = ? AND reset_expiry > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Actualizar la contraseña y eliminar el token
            $stmt = $conn->prepare("UPDATE usuarios SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE reset_token = ?");
            $stmt->execute([$new_password, $token]);

            header("Location: login.php?reset=success");
            exit;
        }
    } else {
        $error = "El enlace de restablecimiento es inválido o ha expirado.";
    }
} else {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <?php if (isset($user)): ?>
            <form method="POST">
                <h2>Restablecer Contraseña</h2>
                <?php if (isset($error)): ?>
                    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
                <input type="password" name="password" placeholder="Nueva Contraseña" required>
                <button type="submit">Restablecer</button>
            </form>
        <?php else: ?>
            <p style="color: red;">El enlace es inválido o ha expirado.</p>
        <?php endif; ?>
    </div>
</body>
</html>
