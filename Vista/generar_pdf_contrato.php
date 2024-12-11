<?php
require '../vendor/setasign/fpdf/fpdf.php'; // Ruta correcta a FPDF
include '../modelo/db_connection.php';

if (!isset($_GET['id'])) {
    die("ID de la obra no proporcionado.");
}

$id_obra = intval($_GET['id']);

// Consultar datos de la obra
$query_obra = "
    SELECT o.id_obra, o.fecha_inicio, o.anticipo, o.adeudo, o.total, o.estatus, o.observaciones,
           c.nombre AS cliente_nombre, c.apellido_paterno AS cliente_apellido, c.correo AS cliente_correo, c.telefono_personal,
           d.calle, d.ciudad, d.estado, d.codigo_postal
    FROM obras o
    INNER JOIN clientes c ON o.id_cliente = c.id_cliente
    INNER JOIN direccion_obra d ON o.id_clave_secundaria = d.clave_secundaria
    WHERE o.id_obra = ?
";
$stmt_obra = $conn->prepare($query_obra);
$stmt_obra->bind_param("i", $id_obra);
$stmt_obra->execute();
$result_obra = $stmt_obra->get_result();
$obra = $result_obra->fetch_assoc();

if (!$obra) {
    die("Obra no encontrada.");
}

// Consultar detalles de los servicios
$query_detalle = "
    SELECT do.cantidad, do.subtotal, s.nombre, s.precio
    FROM detalle_obras do
    INNER JOIN servicios s ON do.id_servicio = s.id_servicio
    WHERE do.id_obra = ?
";
$stmt_detalle = $conn->prepare($query_detalle);
$stmt_detalle->bind_param("i", $id_obra);
$stmt_detalle->execute();
$result_detalle = $stmt_detalle->get_result();

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Cabecera
$pdf->Cell(190, 10, 'PROPOSAL', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(190, 10, 'Ph # 609-966-9947 & Email-familydrywall1@gmail.com', 0, 1, 'C');
$pdf->Cell(190, 10, 'FAMILY DRYWALL LLC.', 0, 1, 'C');
$pdf->Cell(190, 10, '1100 S. NEW RD', 0, 1, 'C');
$pdf->Cell(190, 10, 'PLEASANTVILLE, N.J. 08232', 0, 1, 'C');
$pdf->Ln(10);

// Información del cliente
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(95, 10, 'Proposal Submitted To:', 0, 0);
$pdf->Cell(95, 10, 'Date: ' . $obra['fecha_inicio'], 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(95, 10, 'Builder Name: ' . $obra['cliente_nombre'] . ' ' . $obra['cliente_apellido'], 0, 0);
$pdf->Cell(95, 10, 'Job Location: ' . $obra['calle'], 0, 1);
$pdf->Cell(95, 10, 'City, State: ' . $obra['ciudad'] . ', ' . $obra['estado'], 0, 0);
$pdf->Cell(95, 10, 'Email: ' . $obra['cliente_correo'], 0, 1);
$pdf->Ln(10);

// Especificaciones del trabajo
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 10, 'Job: Drywall and Spackle.', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(190, 10, 'Specifications: Use Screws. 144 SHEETROCK TO INSTALL AND SPACKLE.');
$pdf->Ln(5);

// Servicios incluidos
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 10, 'We hereby propose to furnish labor --complete in accordance with the above specifications.', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(95, 10, 'Half of the amount due when sheetrock installed:', 0, 0);
$pdf->Cell(95, 10, '$' . number_format($obra['anticipo'], 2), 0, 1);
$pdf->Cell(95, 10, 'Final payment due at completion of job:', 0, 0);
$pdf->Cell(95, 10, '$' . number_format($obra['adeudo'], 2), 0, 1);
$pdf->Ln(5);

// Resumen total
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(95, 10, 'Total:', 0, 0);
$pdf->Cell(95, 10, '$' . number_format($obra['total'], 2), 0, 1);
$pdf->Ln(10);

// Firma
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(190, 10, 'Authorized Signature: ________________________', 0, 1);
$pdf->Ln(10);
$pdf->Cell(190, 10, 'Acceptance of Proposal', 0, 1);
$pdf->Ln(5);
$pdf->Cell(190, 10, 'Accepted By: ________________________________', 0, 1);

// Salida del PDF
$pdf->Output('I', 'Contract_' . $obra['id_obra'] . '.pdf');
?>
