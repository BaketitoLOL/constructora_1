<?php
require('../vendor/setasign/fpdf/fpdf.php');


// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'sistema_constructora');
if ($conn->connect_error) die('Error de conexión: ' . $conn->connect_error);

if (isset($_GET['id_obra'])) {
    $id_obra = intval($_GET['id_obra']);

    // Consulta para obtener los datos de la obra
    $query = "SELECT o.fecha_inicio, o.anticipo, o.adeudo, o.total, o.observaciones,
                     c.nombre AS cliente, c.correo, CONCAT(d.calle, ', ', d.ciudad, ', ', d.estado) AS direccion,
                     d.codigo_postal
              FROM obras o
              INNER JOIN clientes c ON o.id_cliente = c.id_cliente
              INNER JOIN direccion_obra d ON o.id_clave_secundaria = d.clave_secundaria
              WHERE o.id_obra = $id_obra";
    $result = $conn->query($query);
    $obra = $result->fetch_assoc();

    if (!$obra) {
        die('Obra no encontrada.');
    }

    // Crear el PDF
    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->AddPage();

    // Cabecera
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(190, 10, 'JM Altomare Contract', 0, 1, 'C');
    $pdf->Ln(5);

    // Subtítulo
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(190, 10, 'PROPOSAL', 0, 1, 'C');
    $pdf->Ln(10);

    // Información del proveedor
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(190, 6, 'Ph # 609-966-9947 & Email: familydrywall1@gmail.com', 0, 1, 'C');
    $pdf->Cell(190, 6, 'FAMILY DRYWALL LLC.', 0, 1, 'C');
    $pdf->Cell(190, 6, '1100 S. NEW RD', 0, 1, 'C');
    $pdf->Cell(190, 6, 'PLEASANTVILLE, N.J. 08232', 0, 1, 'C');
    $pdf->Ln(10);

    // Información del cliente y obra
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(95, 8, 'PROPOSAL SUBMITTED TO:', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(95, 8, $obra['cliente'], 0, 1);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(95, 8, 'DATE:', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(95, 8, date('m/d/Y', strtotime($obra['fecha_inicio'])), 0, 1);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(95, 8, 'JOB LOCATION:', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(95, 8, $obra['direccion'], 0, 1);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(95, 8, 'CITY, STATE:', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(95, 8, $obra['direccion'], 0, 1);

    // Especificaciones del trabajo
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 8, 'SPECIFICATIONS:', 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(190, 8, $obra['observaciones']);

    // Total de la obra
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 8, 'TOTAL FOR THE WORK:', 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(190, 8, '$' . number_format($obra['total'], 2), 0, 1);

    // Condiciones de pago
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 8, 'We hereby propose to furnish labor as follows:', 0, 1);
    $pdf->Ln(5);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(190, 8, 'HALF OF THE AMOUNT DUE WHEN WORK STARTS: $' . number_format($obra['anticipo'], 2), 0, 1);
    $pdf->Cell(190, 8, 'FINAL PAYMENT DUE AT COMPLETION OF JOB: $' . number_format($obra['adeudo'], 2), 0, 1);

    // Términos y aceptación
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(190, 8, "All material is guaranteed to be as specified. All work to be completed in a workmanlike manner according to standard practices. Any alteration or deviation from above specifications involving extra costs will be executed only upon written orders and will become an extra charge over and above the estimate. All agreements contingent upon strikes, accidents, or delays beyond our control.");

    // Firmas
    $pdf->Ln(15);
    $pdf->Cell(190, 8, 'Authorized Signature: ___________________________', 0, 1);
    $pdf->Ln(5);
    $pdf->Cell(190, 8, 'Accepted: Signature: ___________________________', 0, 1);

    // Salida del PDF
    $pdf->Output('I', 'Contrato_Obra_' . $id_obra . '.pdf');
} else {
    echo 'ID de obra no proporcionado.';
}
?>
