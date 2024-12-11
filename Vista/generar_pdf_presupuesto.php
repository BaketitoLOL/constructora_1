<?php
require '../vendor/setasign/fpdf/fpdf.php'; // Ruta a FPDF
include '../modelo/db_connection.php';

// Validar que el parámetro `id_presupuesto` existe y es válido
if (!isset($_GET['id_presupuesto']) || !is_numeric($_GET['id_presupuesto'])) {
    die("ID del presupuesto no proporcionado o inválido.");
}
$id_presupuesto = intval($_GET['id_presupuesto']);

// Consultar datos del presupuesto
$query_presupuesto = "
    SELECT p.fecha_elaboracion, p.observaciones, p.total, 
           c.nombre AS cliente, d.calle, d.ciudad, d.estado, d.codigo_postal
    FROM presupuestos p
    INNER JOIN clientes c ON p.id_cliente = c.id_cliente
    INNER JOIN direccion_obra d ON p.id_direccion = d.id_direccion
    WHERE p.id_presupuesto = ?";

$stmt_presupuesto = $conn->prepare($query_presupuesto);
if (!$stmt_presupuesto) {
    die("Error al preparar la consulta del presupuesto: " . $conn->error);
}
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
    WHERE dp.id_presupuesto = ?";
$stmt_detalle = $conn->prepare($query_detalle);
if (!$stmt_detalle) {
    die("Error al preparar la consulta de detalles: " . $conn->error);
}
$stmt_detalle->bind_param("i", $id_presupuesto);
$stmt_detalle->execute();
$result_detalle = $stmt_detalle->get_result();

// Crear PDF con FPDF
try {
    $file_path = "../pdf/Proposal_" . $id_presupuesto . ".pdf";

    // Eliminar archivo existente
    if (file_exists($file_path)) {
        unlink($file_path);
    }

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Cabecera
    $pdf->Cell(190, 10, 'PROPOSAL', 0, 1, 'C');
    $pdf->Ln(5);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(190, 10, 'FAMILY DRYWALL LLC.', 0, 1, 'C');
    $pdf->Cell(190, 10, '1100 S. NEW RD', 0, 1, 'C');
    $pdf->Cell(190, 10, 'PLEASANTVILLE, N.J. 08232', 0, 1);
    $pdf->Ln(10);

    // Datos del cliente y trabajo
    $pdf->Cell(95, 10, 'PROPOSAL SUBMITTED TO:', 0, 0);
    $pdf->Cell(95, 10, 'DATE: ' . $presupuesto['fecha_elaboracion'], 0, 1);
    $pdf->Cell(95, 10, 'BUILDER NAME: ' . $presupuesto['cliente'], 0, 1);
    $pdf->Cell(95, 10, 'JOB LOCATION: ' . $presupuesto['calle'], 0, 1);
    $pdf->Cell(95, 10, 'CITY, STATE: ' . $presupuesto['ciudad'] . ', ' . $presupuesto['estado'], 0, 1);
    $pdf->Ln(5);

    // Descripción del trabajo
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, 'JOB: Drywall and Spackle.', 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(190, 10, 'SPECIFICATIONS: Use Screws.', 0, 1);
    $pdf->Cell(190, 10, '144 SHEETROCK TO INSTALL AND SPACKLE.', 0, 1);
    $pdf->Ln(5);

    // Condiciones de pago
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(190, 10, "We hereby propose to furnish labor --complete in accordance with the above specifications, for the sum of \n$" . number_format($presupuesto['total'], 2), 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(190, 10, 'HALF OF THE AMOUNT DUE WHEN SHEETROCK INSTALLED----$', 0, 1);
    $pdf->Cell(190, 10, 'FINAL PAYMENT DUE AT COMPLETION OF JOB----$', 0, 1);
    $pdf->Ln(5);

    // Garantías y condiciones
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(190, 10, "All material is guaranteed to be as specified. All work to be completed in a workmanlike manner according to standard practices. Any alteration or deviation from above specifications involving extra costs will be executed only upon written orders and will become an extra charge over and above the estimate. All agreements contingent upon strikes, accident, or delays beyond our control.", 0, 'L');
    $pdf->Ln(5);

    // Crear directorio si no existe
    if (!is_dir('../pdf')) {
        mkdir('../pdf', 0755, true);
    }

    $pdf->Output('F', $file_path);
    echo "PDF generado exitosamente en la ruta: " . $file_path;
} catch (Exception $e) {
    die("Error al generar el PDF: " . $e->getMessage());
}

// Cerrar recursos
$stmt_presupuesto->close();
$stmt_detalle->close();
$conn->close();
