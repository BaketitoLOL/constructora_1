<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function enviarCorreo($destinatario, $asunto, $cuerpo) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'familyconstructora86@gmail.com'; // Cambia esto
        $mail->Password = 'uagtishcgtimpgnv';      // Cambia esto
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('tu-correo@gmail.com', 'Contructract');
        $mail->addAddress($destinatario);

        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $cuerpo;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Error al enviar el correo: {$mail->ErrorInfo}";
    }
}
?>
