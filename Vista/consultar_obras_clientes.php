<?php
session_start();
include '../modelo/db_connection.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Cliente') {
    header("Location: login.php");
    exit;
}

$id_cliente = $_SESSION['id_cliente'] ?? null;
if (!$id_cliente) {
    echo "Error: No se encontró información del cliente.";
    exit;
}

// Consultar las obras del cliente
$query_obras = $conn->prepare("SELECT o.fecha_inicio, o.anticipo, o.total, o.observaciones, o.estatus, CONCAT(c.nombre, ' ', c.apellido_paterno) AS nombre_cliente
                              FROM obras o
                              INNER JOIN clientes c ON o.id_cliente = c.id_cliente
                              WHERE o.id_cliente = ?");
$query_obras->bind_param("i", $id_cliente);
$query_obras->execute();
$result_obras = $query_obras->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Obras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar_clients.php';?>
<div class="container mt-5">
    <h1 class="mb-4">Mis Obras</h1>
    <?php if ($result_obras->num_rows > 0): ?>
        <table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Dirección</th>
            <th>Fecha de Inicio</th>
            <th>Total</th>
            <th>Estatus</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Consulta para obtener los datos de las obras
        $query = "SELECT o.id_obra, c.nombre AS cliente, CONCAT(d.calle, ', ', d.ciudad) AS direccion, 
                          o.fecha_inicio, o.total, o.estatus
                  FROM obras o
                  INNER JOIN clientes c ON o.id_cliente = c.id_cliente
                  INNER JOIN direccion_obra d ON o.id_clave_secundaria = d.clave_secundaria";
        $result = $conn->query($query);

        // Iterar sobre los resultados
        while ($row = $result->fetch_assoc()):
            $id_obra = htmlspecialchars($row['id_obra']);
            $file_path = "../pdf/Contract_" . $id_obra . ".pdf"; // Ruta del archivo PDF
        ?>
            <tr>
                <td><?= $row['id_obra'] ?></td>
                <td><?= htmlspecialchars($row['cliente']) ?></td>
                <td><?= htmlspecialchars($row['direccion']) ?></td>
                <td><?= htmlspecialchars($row['fecha_inicio']) ?></td>
                <td>$<?= number_format($row['total'], 2) ?></td>
                <td><?= htmlspecialchars($row['estatus']) ?></td>
                <td>
                    <!-- Generar contrato PDF -->
                    <a href="generar_pdf.php?id_obra=<?= $id_obra ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-file-pdf"></i> Generar Contrato
                    </a>
                    <!-- Enviar PDF por correo -->
                    <a href="enviar_correo.php?id=<?= $id_obra ?>&file=<?= urlencode($file_path) ?>" 
                       class="btn btn-secondary btn-sm send-button" 
                       title="Enviar PDF" 
                       data-folio="<?= $id_obra ?>" 
                       onclick="enviarPDF(this)">
                        <i class="fas fa-envelope"></i> Enviar Contrato
                    </a>
                    <!-- Visualizar PDF si existe -->
                    <?php if (file_exists($file_path)): ?>
                        <a href="<?= htmlspecialchars($file_path) ?>" target="_blank" class="btn btn-info btn-sm" title="Ver PDF">
                            <i class="fas fa-eye"></i> Ver Contrato
                        </a>
                        <button class="btn btn-primary btn-sm me-2 signature-btn" data-bs-toggle="modal" data-bs-target="#uploadSignatureModal" 
                            onclick="openSignatureModal('<?= $id_obra ?>')" title="Agregar Firma">
                        <i class="fas fa-pen"></i>Cargar Firma
                    </button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
    <?php else: ?>
        <p>No se encontraron obras asociadas a tu cuenta.</p>
    <?php endif; ?>
</div>

<div class="modal fade" id="uploadSignatureModal" tabindex="-1" aria-labelledby="uploadSignatureModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="cargar_firma_cliente.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadSignatureModalLabel">Upload Signature</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_obra" id="modalFolioObra">
                    
                    <div class="mb-3">
                        <label for="signature" class="form-label fw-bold">Signature File:</label>
                        <input 
                            type="file" 
                            name="Firma_administrador" 
                            id="signature" 
                            class="form-control" 
                            accept="image/*" 
                            required
                        >
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">

function openSignatureModal(id_obra) {
        document.getElementById('modalFolioObra').value = id_ob;
    }

</script>
</body>
</html>
