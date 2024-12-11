<?php
session_start();
include '../modelo/db_connection.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Cliente') {
    header("Location: login.php");
    exit;
}

// Obtener el ID del cliente asociado al usuario
$id_usuario = $_SESSION['id_usuario'];
$query_cliente = $conn->prepare("
    SELECT id_cliente 
    FROM usuarios 
    WHERE id_usuario = ?
");
$query_cliente->bind_param("i", $id_usuario);
$query_cliente->execute();
$result_cliente = $query_cliente->get_result();

if ($result_cliente->num_rows !== 1) {
    die("Error: Cliente no encontrado.");
}

$id_cliente = $result_cliente->fetch_assoc()['id_cliente'];

// Consultar presupuestos del cliente
$query_presupuestos = $conn->prepare("
    SELECT id_presupuesto, fecha_elaboracion, total, estatus 
    FROM presupuestos 
    WHERE id_cliente = ?
");
$query_presupuestos->bind_param("i", $id_cliente);
$query_presupuestos->execute();
$result_presupuestos = $query_presupuestos->get_result();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Presupuestos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Mis Presupuestos</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha de Elaboración</th>
                    <th>Total</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($presupuesto = $result_presupuestos->fetch_assoc()): ?>
                    <tr>
                        <td><?= $presupuesto['id_presupuesto'] ?></td>
                        <td><?= $presupuesto['fecha_elaboracion'] ?></td>
                        <td>$<?= number_format($presupuesto['total'], 2) ?></td>
                        <td><?= $presupuesto['estatus'] ?></td>
                        <td>
                            <a href="descargar_presupuesto.php?id_presupuesto=<?= $presupuesto['id_presupuesto'] ?>"
                                class="btn btn-success btn-sm">
                                <i class="fas fa-download"></i> Descargar PDF
                            </a>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#emailModal">
                                <i class="fas fa-envelope"></i> Enviar
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <!-- Modal para enviar correo -->
    <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="emailForm" action="enviar_correo_propuesta.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="emailModalLabel">Enviar Propuesta por Correo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="correo_compania" class="form-label">Correo de la Compañía</label>
                            <input type="email" class="form-control" id="correo_compania" name="correo_compania"
                                value="familyconstructora86@gmail.com" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="archivo_pdf" class="form-label">Archivo PDF</label>
                            <input type="file" class="form-control" id="archivo_pdf" name="archivo_pdf" accept=".pdf"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </form>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function prepararEnvioCorreo(idPresupuesto) {
                document.getElementById('id_presupuesto').value = idPresupuesto;
            }

        </script>

</body>

</html>