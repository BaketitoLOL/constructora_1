<?php
require('../vendor/setasign/fpdf/fpdf.php');

$conn = new mysqli('localhost', 'root', '', 'sistema_constructora');
if ($conn->connect_error) die('Error de conexión: ' . $conn->connect_error);

if (isset($_GET['id_presupuesto'])) {
    $id_presupuesto = intval($_GET['id_presupuesto']);

    // Obtener datos del presupuesto
    $query_presupuesto = "SELECT p.fecha_elaboracion, p.observaciones, p.total, 
                          c.nombre AS cliente, d.calle, d.ciudad, d.estado, d.codigo_postal
                          FROM presupuestos p
                          INNER JOIN clientes c ON p.id_cliente = c.id_cliente
                          INNER JOIN direccion_obra d ON p.id_direccion = d.id_direccion
                          WHERE p.id_presupuesto = $id_presupuesto";
    $result_presupuesto = $conn->query($query_presupuesto);
    $presupuesto = $result_presupuesto->fetch_assoc();

    // Crear el PDF
    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->AddPage();

    // Cabecera
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(190, 10, 'PROPOSAL', 0, 1, 'C');
    $pdf->Ln(5);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(190, 10, 'FAMILY DRYWALL LLC.', 0, 1, 'C');
    $pdf->Cell(190, 10, '1100 S. NEW RD', 0, 1, 'C');
    $pdf->Cell(190, 10, 'PLEASANTVILLE, N.J. 08232', 0, 1, 'C');
    $pdf->Ln(10);

    // Datos del cliente y trabajo
    $pdf->SetFont('Arial', '', 12);
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

    // Firma
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, 'Authorized Signature: JOSEMARTINEZ', 0, 1, 'R');
    $pdf->Ln(10);

    // Aceptación de la propuesta
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(190, 10, 'Acceptance of Proposal', 0, 1, 'C');
    $pdf->Ln(5);
    $pdf->Cell(190, 10, 'The above prices, specifications and conditions are hereby accepted.', 0, 1, 'C');
    $pdf->Cell(190, 10, 'Accepted: Signature: _________________________________', 0, 1, 'C');

    // Salida del PDF
    $pdf->Output('I', 'Presupuesto_' . $id_presupuesto . '.pdf');
} else {
    echo 'ID de presupuesto no proporcionado.';
}
?>
