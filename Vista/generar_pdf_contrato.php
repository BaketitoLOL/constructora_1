<?php
require '../vendor/setasign/fpdf/fpdf.php'; // Ruta a FPDF
include '../modelo/db_connection.php';

if (!isset($_GET['id_obra']) || !is_numeric($_GET['id_obra'])) {
    die("ID de la obra no proporcionado o inválido.");
}
$id_obra = intval($_GET['id_obra']);

$query_obra = 
"SELECT o.fecha_inicio, o.anticipo, o.adeudo, o.total, o.observaciones,
                     c.nombre AS cliente, c.correo, CONCAT(d.calle, ', ', d.ciudad, ', ', d.estado) AS direccion,
                     d.codigo_postal
              FROM obras o
              INNER JOIN clientes c ON o.id_cliente = c.id_cliente
              INNER JOIN direccion_obra d ON o.id_clave_secundaria = d.clave_secundaria
              WHERE o.id_obra = $id_obra";

$stmt_obra = $conn->prepare($query_obra);
if (!$stmt_obra) {
    die("Error al preparar la consulta de la obra: " . $conn->error);
}
$stmt_obra->execute();
$result_obra = $stmt_obra->get_result();
$obra = $result_obra->fetch_assoc();

if (!$obra) {
    die("Obra no encontrada.");
}

// Crear PDF con FPDF
try {
    $file_path = "../pdf/Contract_" . $id_obra . ".pdf";

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
    $pdf->Cell(95, 10, 'DATE: ' . $obra['fecha_inicio'], 0, 1);
    $pdf->Cell(95, 10, 'BUILDER NAME: ' . $obra['cliente'], 0, 1);
    $pdf->Cell(95, 10, 'JOB LOCATION: ' . $obra['direccion'], 0, 1);
    $pdf->Cell(95, 10, 'ZIP CODE: ' . $obra['codigo_postal'], 0, 1);
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
    $pdf->MultiCell(190, 10, "We hereby propose to furnish labor -- complete in accordance with the above specifications, for the sum of \n$" . number_format($obra['total'], 2), 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(190, 10, 'HALF OF THE AMOUNT DUE WHEN SHEETROCK INSTALLED----$', 0, 1);
    $pdf->Cell(190, 10, 'FINAL PAYMENT DUE AT COMPLETION OF JOB----$', 0, 1);
    $pdf->Ln(5);

    // Garantías y condiciones
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(190, 10, "All material is guaranteed to be as specified. All work to be completed in a workmanlike manner according to standard practices. Any alteration or deviation from above specifications involving extra costs will be executed only upon written orders and will become an extra charge over and above the estimate. All agreements contingent upon strikes, accident, or delays beyond our control.", 0, 'L');
    $pdf->Ln(5);

    $signature_path = "../Firma_administrador/firma_.png"; // Ruta de la firma
                $pdf->Cell(10, 10, 'Authorized: Signature:', 0, 0, 'L');
                if (file_exists($signature_path)) {
                    $pdf->Image($signature_path, $pdf->GetX() + 40, $pdf->GetY() - 5, 30); // Ajusta la anchura a 30px
                }
                $pdf->Ln(20);
                
                // Aceptación del contrato
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(0, 10, 'ACCEPTANCE OF CONTRACT', 0, 1, 'C');
                $pdf->MultiCell(0, 6, 'The above prices, specifications and conditions are hereby accepted. You are authorized to do the work as specified. Payment will be made as outlined above.', 0, 'L');
                $pdf->Ln(10);
                $pdf->Cell(30, 10, 'Accepted: Signature:', 0, 0, 'L');
                $pdf->Cell(0, 10, '       _______________________________', 0, 1, 'L');

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
$stmt_obra->close();
$conn->close();
?>
