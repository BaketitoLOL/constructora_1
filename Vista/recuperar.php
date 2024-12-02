<?php
include '../modelo/db_connection.php';
include 'send_mail.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    // Verificar si el correo existe en la base de datos
    $query = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 1) {
        // Generar token y caducidad
        $token = bin2hex(random_bytes(16));
        $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Guardar token en la base de datos
        $update = $conn->prepare("UPDATE usuarios SET reset_token = ?, reset_expira = ? WHERE email = ?");
        $update->bind_param("sss", $token, $expira, $email);
        $update->execute();

        // Enviar correo
        $link = "http://localhost/ConstructoraDEFINITIC/Vista/restablecer.php?token=" . $token;
        $asunto = "Recuperación de Contraseña";
        $cuerpo = "
            <p>Hola,</p>
            <p>Haz clic en el siguiente enlace para restablecer tu contraseña:</p>
            <p><a href='$link'>$link</a></p>
            <p>Si no solicitaste este correo, ignóralo.</p>
        ";

        if (enviarCorreo($email, $asunto, $cuerpo)) {
            $success = "Se ha enviado un enlace de recuperación a tu correo.";
        } else {
            $error = "Hubo un problema al enviar el correo.";
        }
    } else {
        $error = "Correo no encontrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
</head>
<body>
    <h1>Recuperar Contraseña</h1>
    <?php if (isset($success)) echo "<p>$success</p>"; ?>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <form method="POST" action="recuperar.php">
        <input type="email" name="email" placeholder="Correo Electrónico" required>
        <button type="submit">Enviar Enlace</button>
    </form>
</body>
</html>
