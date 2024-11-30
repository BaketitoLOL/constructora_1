<?php
require '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Verificar si el correo existe
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generar un token único
        $token = bin2hex(random_bytes(50));

        // Guardar el token en la base de datos con una validez de 15 minutos
        $stmt = $conn->prepare("UPDATE usuarios SET reset_token = ?, reset_expiry = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE email = ?");
        $stmt->execute([$token, $email]);

        // Enviar correo con el enlace
        $reset_link = "http://localhost/CONSTRUCTORADEFINITIC/Vista/restablecer_password.php?token=$token";
        mail($email, "Restablecimiento de contraseña", "Haz clic en el siguiente enlace para restablecer tu contraseña: $reset_link");

        $message = "Se ha enviado un enlace a tu correo electrónico.";
    } else {
        $error = "El correo ingresado no está registrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <form method="POST">
            <h2>Recuperar Contraseña</h2>
            <?php if (isset($message)): ?>
                <p style="color: green;"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <input type="email" name="email" placeholder="Correo Electrónico" required>
            <button type="submit">Enviar Enlace</button>
        </form>
    </div>
</body>
</html>
