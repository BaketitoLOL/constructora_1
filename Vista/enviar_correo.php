<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../modelo/db_connection.php';
require '../vendor/autoload.php'; // Cambia el path si es necesario

if (isset($_GET['id']) && isset($_GET['file'])) {
    $id_obra = $_GET['id'];
    $file_path = urldecode($_GET['file']);

    $sql = "SELECT c.correo FROM obras o
            LEFT JOIN clientes c ON o.id_cliente = c.id_cliente
            WHERE o.id_obra = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_obra);
    $stmt->execute();
    $result = $stmt->get_result();
    $obra = $result->fetch_assoc();

    if ($obra && file_exists($file_path)) {
        $correo_cliente = $obra['correo']; 
    } else {
        echo "No se encontró el cliente o el archivo PDF.";
        exit;
    }
} else {
    echo "Parámetros inválidos proporcionados.";
    exit;
}

try {
    $mail = new PHPMailer(true);

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
    $mail->addAddress($correo_cliente, 'Cliente');

    // Adjuntar archivo
    $mail->addAttachment($file_path, basename($file_path));

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Contrato en PDF';
    $mail->Body = '<p>Se adjunta el contrato en formato PDF.</p>';

    // Enviar correo
    $mail->send();
    echo "Correo enviado con éxito.";
} catch (Exception $e) {
    echo "Error al enviar el correo: {$mail->ErrorInfo}";
}
?>
