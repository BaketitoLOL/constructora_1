<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Cambia el path si es necesario

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo_compania = $_POST['correo_compania'];
    $archivo_pdf = $_FILES['archivo_pdf'];

    if ($archivo_pdf['error'] === UPLOAD_ERR_OK) {
        $ruta_archivo = $archivo_pdf['tmp_name'];
        $nombre_archivo = $archivo_pdf['name'];

        // Configuración de PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Cambiar según tu proveedor SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'andresrodriguezj09d2@gmail.com'; // Cambia por tu correo
            $mail->Password = 'ifpkkbutexhqrzcs'; // Cambia por tu contraseña
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Remitente y destinatarios
            $mail->setFrom('tu_correo@gmail.com', 'Constructora'); // Cambia por tu correo
            $mail->addAddress($correo_compania, 'Constructora');

            // Adjuntar archivo
            $mail->addAttachment($ruta_archivo, $nombre_archivo);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Propuesta en PDF';
            $mail->Body = '<p>Se adjunta la propuesta en formato PDF.</p>';

            // Enviar correo
            $mail->send();
            echo "Correo enviado con éxito.";
        } catch (Exception $e) {
            echo "Error al enviar el correo: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error al cargar el archivo.";
    }
}
?>
