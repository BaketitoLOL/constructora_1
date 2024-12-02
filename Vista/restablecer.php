<?php
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';

    // Verificar token
    $query = $conn->prepare("SELECT id_usuario FROM usuarios WHERE reset_token = ? AND reset_expira > NOW()");
    $query->bind_param("s", $token);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 1) {
        // Actualizar contraseña
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $update = $conn->prepare("UPDATE usuarios SET password = ?, reset_token = NULL, reset_expira = NULL WHERE reset_token = ?");
        $update->bind_param("ss", $hashedPassword, $token);
        $update->execute();

        echo "Contraseña restablecida con éxito.";
    } else {
        echo "Token inválido o expirado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
</head>
<body>
    <h1>Restablecer Contraseña</h1>
    <form method="POST" action="restablecer.php">
        <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">
        <input type="password" name="password" placeholder="Nueva Contraseña" required>
        <button type="submit">Restablecer Contraseña</button>
    </form>
    <a href="login.php">Regresar</a>
</body>
</html>
