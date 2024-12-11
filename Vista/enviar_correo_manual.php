<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $archivo = $_FILES['archivo'];

    if ($archivo['error'] === UPLOAD_ERR_OK) {
        $rutaArchivo = $archivo['tmp_name'];
        $nombreArchivo = $archivo['name'];

        // Configurar PHPMailer
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Cambiar al servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'familyconstructora86@gmail.com'; // Cambiar al correo que enviarás
        $mail->Password = 'otaxpxmuepjobejf'; // Cambiar a la contraseña del correo
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('tu_correo@example.com', 'Constructora'); // Cambiar al correo del remitente
        $mail->addAddress($email); // Correo del cliente

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Contrato de Obra';
        $mail->Body = '
            <p>Estimado/a cliente,</p>
            <p>Adjunto encontrarás el contrato correspondiente a tu obra.</p>
            <p>Si tienes alguna duda, no dudes en contactarnos.</p>
            <br>
            <p>Saludos cordiales,</p>
            <p><b>Constructora</b></p>
        ';
        $mail->addAttachment($rutaArchivo, $nombreArchivo); // Adjuntar el PDF

        // Enviar el correo
        if (!$mail->send()) {
            echo 'Error al enviar el correo: ' . $mail->ErrorInfo;
        } else {
            echo 'Correo enviado correctamente al cliente.';
            header('obras.php');
        }
    } else {
        echo 'Error al cargar el archivo.';
    }
} else {
    echo 'Método no permitido.';
}
?>
