<?php
require '../vendor/setasign/fpdf/fpdf.php'; // Ruta a FPDF
include '../modelo/db_connection.php';

if (!isset($_GET['id'])) {
    die("ID del presupuesto no proporcionado.");
}

$id_presupuesto = intval($_GET['id']);

// Consultar datos del presupuesto
$query_presupuesto = "
    SELECT p.id_presupuesto, p.fecha_elaboracion, p.total, p.estatus, p.observaciones, 
           c.nombre AS cliente_nombre, c.apellido_paterno AS cliente_apellido, c.correo AS cliente_correo,
           c.telefono_personal AS cliente_telefono
    FROM presupuestos p
    INNER JOIN clientes c ON p.id_cliente = c.id_cliente
    WHERE p.id_presupuesto = ?
";
$stmt_presupuesto = $conn->prepare($query_presupuesto);
$stmt_presupuesto->bind_param("i", $id_presupuesto);
$stmt_presupuesto->execute();
$result_presupuesto = $stmt_presupuesto->get_result();
$presupuesto = $result_presupuesto->fetch_assoc();

if (!$presupuesto) {
    die("Presupuesto no encontrado.");
}

// Consultar detalles del presupuesto
$query_detalle = "
    SELECT dp.cantidad, dp.subtotal, s.nombre, s.precio
    FROM detalle_presupuesto dp
    INNER JOIN servicios s ON dp.id_servicio = s.id_servicio
    WHERE dp.id_presupuesto = ?
";
$stmt_detalle = $conn->prepare($query_detalle);
$stmt_detalle->bind_param("i", $id_presupuesto);
$stmt_detalle->execute();
$result_detalle = $stmt_detalle->get_result();

// Crear PDF con FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Cabecera
$pdf->Cell(190, 10, 'PROPOSAL', 0, 1, 'C');
$pdf->Ln(5);

// Información de la empresa
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(190, 10, 'FAMILY DRYWALL LLC.', 0, 1, 'C');
$pdf->Cell(190, 10, '1100 S. NEW RD', 0, 1, 'C');
$pdf->Cell(190, 10, 'PLEASANTVILLE, N.J. 08232', 0, 1, 'C');
$pdf->Ln(5);

// Información del cliente y del presupuesto
$pdf->Cell(95, 10, 'Proposal Submitted To:', 0, 0);
$pdf->Cell(95, 10, 'Date: ' . $presupuesto['fecha_elaboracion'], 0, 1);
$pdf->Cell(95, 10, 'Client Name: ' . $presupuesto['cliente_nombre'] . ' ' . $presupuesto['cliente_apellido'], 0, 0);
$pdf->Cell(95, 10, 'Phone: ' . $presupuesto['cliente_telefono'], 0, 1);
$pdf->Cell(95, 10, 'Email: ' . $presupuesto['cliente_correo'], 0, 1);
$pdf->Ln(5);

// Especificaciones del trabajo
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 10, 'Specifications:', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(190, 10, 'This proposal includes the following services as per the specifications discussed:');
$pdf->Ln(5);

// Servicios incluidos
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(90, 10, 'Service', 1);
$pdf->Cell(30, 10, 'Quantity', 1);
$pdf->Cell(30, 10, 'Unit Price', 1);
$pdf->Cell(40, 10, 'Subtotal', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
while ($row = $result_detalle->fetch_assoc()) {
    $pdf->Cell(90, 10, $row['nombre'], 1);
    $pdf->Cell(30, 10, $row['cantidad'], 1);
    $pdf->Cell(30, 10, '$' . number_format($row['precio'], 2), 1);
    $pdf->Cell(40, 10, '$' . number_format($row['subtotal'], 2), 1);
    $pdf->Ln();
}
$pdf->Ln(5);

// Total y pagos
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 10, 'Payment Terms:', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(190, 10, 'Half of the total amount is due when work starts, and the remaining half upon completion.');
$pdf->Cell(95, 10, 'Total Amount:', 0, 0);
$pdf->Cell(95, 10, '$' . number_format($presupuesto['total'], 2), 0, 1);

// Firma y aceptación
$pdf->Ln(10);
$pdf->Cell(190, 10, 'Authorized Signature: ________________________', 0, 1);
$pdf->Ln(5);
$pdf->Cell(190, 10, 'Acceptance of Proposal:', 0, 1);
$pdf->Ln(5);
$pdf->Cell(190, 10, 'Accepted By: ________________________________', 0, 1);

// Salida del PDF
$pdf->Output('I', 'Presupuesto_' . $presupuesto['id_presupuesto'] . '.pdf');
?>
