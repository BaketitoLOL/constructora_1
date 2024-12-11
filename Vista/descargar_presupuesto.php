<?php
require_once '../modelo/db_connection.php';
require_once '../vendor/setasign/fpdf/fpdf.php'; // Asegúrate de tener FPDF instalado

class PDF extends FPDF
{
    // Encabezado
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Presupuesto', 0, 1, 'C');
        $this->Ln(10);
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$id_presupuesto = 16; // ID fijo para este caso

// Consulta para obtener los datos del presupuesto
$query = $conn->prepare("
    SELECT p.*, c.nombre AS cliente, c.apellido_paterno, c.apellido_materno, c.correo,
           d.calle, d.ciudad, d.estado, d.codigo_postal
    FROM presupuestos p
    INNER JOIN clientes c ON p.id_cliente = c.id_cliente
    INNER JOIN direccion_obra d ON p.id_direccion = d.id_direccion
    WHERE p.id_presupuesto = ?
");
$query->bind_param('i', $id_presupuesto);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    die("No se encontró el presupuesto.");
}

$data = $result->fetch_assoc();

// Consulta para obtener los detalles del presupuesto
$query_details = $conn->prepare("
    SELECT dp.*, s.nombre AS servicio, s.precio
    FROM detalle_presupuesto dp
    INNER JOIN servicios s ON dp.id_servicio = s.id_servicio
    WHERE dp.id_presupuesto = ?
");
$query_details->bind_param('i', $id_presupuesto);
$query_details->execute();
$details = $query_details->get_result();

// Crear una nueva instancia de FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

// Título y encabezado
$pdf->Cell(0, 10, 'JM Altomare Contract', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, 'Ph # 609-966-9947 & Email-familydrywall1@gmail.com', 0, 1, 'C');
$pdf->Cell(0, 5, 'FAMILY DRYWALL LLC.', 0, 1, 'C');
$pdf->Cell(0, 5, '1100 S. NEW RD', 0, 1, 'C');
$pdf->Cell(0, 5, 'PLEASANTVILLE, N.J. 08232', 0, 1, 'C');
$pdf->Ln(10);

// Información del cliente y propuesta
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'PROPOSAL', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(50, 8, 'PROPOSAL SUBMITTED TO:', 0, 0, 'L');
$pdf->Cell(0, 8, 'DecTrinity LLC.', 0, 1, 'L'); // Dinámico
$pdf->Cell(50, 8, 'DATE:', 0, 0, 'L');
$pdf->Cell(0, 8, '4/26/2024', 0, 1, 'L'); // Dinámico
$pdf->Cell(50, 8, 'JOB LOCATION:', 0, 0, 'L');
$pdf->Cell(0, 8, '2641 Boardwalk', 0, 1, 'L'); // Dinámico
$pdf->Cell(50, 8, 'CITY, STATE:', 0, 0, 'L');
$pdf->Cell(0, 8, 'Atlantic City, NJ', 0, 1, 'L'); // Dinámico
$pdf->Cell(50, 8, 'EMAIL:', 0, 0, 'L');
$pdf->Cell(0, 8, 'vangale1@yahoo.com', 0, 1, 'L'); // Dinámico
$pdf->Cell(50, 8, 'JOB:', 0, 0, 'L');
$pdf->Cell(0, 8, 'Drywall and Spackle', 0, 1, 'L'); // Dinámico

// Especificaciones
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'SPECIFICATIONS:', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 8, 'Use Screws. 144 SHEETROCK TO INSTALL AND SPACKLE.', 0, 'L');

// Propuesta de costo
$pdf->Ln(5);
$pdf->Cell(0, 8, 'We hereby propose to furnish labor --complete in accordance with the above specifications, for the sum of', 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, '$1200.00', 0, 1, 'C'); // Dinámico
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 8, 'with payment to be made as follows:', 0, 1, 'L');
$pdf->Cell(0, 8, 'HALF OF THE AMOUNT DUE WHEN SHEETROCK INSTALLED----$600.00', 0, 1, 'L'); // Dinámico
$pdf->Cell(0, 8, 'FINAL PAYMENT DUE AT COMPLETION OF JOB----$600.00', 0, 1, 'L'); // Dinámico

// Firma y aceptación
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'All material is guaranteed to be as specified.', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 8, 'All work to be completed in a workmanlike manner according to standard practices. Any alteration or deviation from above specifications involving extra costs will be executed only upon written orders and will become an extra charge over and above the estimate.', 0, 'L');
$pdf->Cell(0, 8, 'Authorized Signature: JOSE MARTINEZ', 0, 1, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'Acceptance of Proposal', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 8, 'The above prices, specifications and conditions are hereby accepted. You are authorized to do the work as specified. Payment will be made as outlined above.', 0, 'L');
$pdf->Ln(5);
$pdf->Cell(0, 8, 'Accepted: Signature: _________________________________', 0, 1, 'L');

// Salvar el PDF
$pdf->Output('I', 'Proposal.pdf');
?>