<?php
require('../vendor/setasign/fpdf/fpdf.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'sistema_constructora');
if ($conn->connect_error) die('Error de conexión: ' . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_obra'])) {
    $id_obra = intval($_POST['id_obra']);

    // Consulta para obtener los datos de la obra
    $query = "SELECT o.fecha_inicio, o.anticipo, o.adeudo, o.total, o.observaciones,
                     c.nombre AS cliente, c.correo AS correo_cliente,
                     CONCAT(d.calle, ', ', d.ciudad, ', ', d.estado) AS direccion
              FROM obras o
              INNER JOIN clientes c ON o.id_cliente = c.id_cliente
              INNER JOIN direccion_obra d ON o.id_clave_secundaria = d.clave_secundaria
              WHERE o.id_obra = $id_obra";
    $result = $conn->query($query);
    $obra = $result->fetch_assoc();

    if (!$obra) {
        die('Obra no encontrada.');
    }

    // Generar el PDF del contrato
    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(190, 10, 'JM Altomare Contract', 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(95, 10, 'Client Name: ' . $obra['cliente'], 0, 1);
    $pdf->Cell(95, 10, 'Address: ' . $obra['direccion'], 0, 1);
    $pdf->Cell(95, 10, 'Start Date: ' . $obra['fecha_inicio'], 0, 1);
    $pdf->Cell(95, 10, 'Total Amount: $' . number_format($obra['total'], 2), 0, 1);
    $pdf_file = 'Contrato_Obra_' . $id_obra . '.pdf';
    $pdf->Output('F', $pdf_file); // Guarda el PDF en el servidor

    // Configurar PHPMailer
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Cambiar al servidor SMTP que uses
    $mail->SMTPAuth = true;
    $mail->Username = 'familyconstructora86@gmail.com'; // Cambiar al correo que usarás para enviar
    $mail->Password = 'otaxpxmuepjobejf'; // Cambiar a la contraseña de tu correo
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('tu_correo@example.com', 'Constructora'); // Cambiar al correo del remitente
    $mail->addAddress($obra['correo_cliente'], $obra['cliente']); // Correo del cliente

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Contrato de Obra';
    $mail->Body = 'Estimado/a ' . $obra['cliente'] . ',<br><br>Adjunto encontrarás el contrato correspondiente a tu obra.<br><br>Saludos cordiales, <br><b>Constructora</b>';
    $mail->addAttachment($pdf_file); // Adjuntar el PDF

    // Enviar el correo
    if (!$mail->send()) {
        echo 'Error al enviar el correo: ' . $mail->ErrorInfo;
    } else {
        echo 'Correo enviado correctamente.';
    }

    // Eliminar el archivo PDF temporal
    unlink($pdf_file);
} else {
    echo 'ID de obra no proporcionado.';
}
?>
