<?php
$conn = new mysqli('localhost', 'root', '', 'sistema_constructora');
if ($conn->connect_error)
    die('Error de conexión: ' . $conn->connect_error);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Obras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <h1>Gestión de Obras</h1>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addObraModal">
            <i class="fas fa-plus"></i> Agregar Obra
        </button>
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
                $query = "SELECT o.id_obra, c.nombre AS cliente, CONCAT(d.calle, ', ', d.ciudad) AS direccion, 
                          o.fecha_inicio, o.total, o.estatus
                          FROM obras o
                          INNER JOIN clientes c ON o.id_cliente = c.id_cliente
                          INNER JOIN direccion_obra d ON o.id_clave_secundaria = d.clave_secundaria";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id_obra'] ?></td>
                        <td><?= $row['cliente'] ?></td>
                        <td><?= $row['direccion'] ?></td>
                        <td><?= $row['fecha_inicio'] ?></td>
                        <td>$<?= number_format($row['total'], 2) ?></td>
                        <td><?= $row['estatus'] ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editObraModal"
                                onclick="cargarDatosEditar(<?= htmlspecialchars(json_encode($row)) ?>)">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="eliminarObra(<?= $row['id_obra'] ?>)">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                            <a href="generar_contrato.php?id_obra=<?= $row['id_obra'] ?>" class="btn btn-success btn-sm"
                                target="_blank">
                                <i class="fas fa-file-pdf"></i> Generar Contrato
                            </a>
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#emailModal">
                                <i class="fas fa-envelope"></i> Enviar Correo
                            </button>

                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal: Agregar Obra -->
    <div class="modal fade" id="addObraModal" tabindex="-1" aria-labelledby="addObraModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="guardar_obra.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addObraModalLabel">Agregar Obra</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="cliente" class="form-label">Cliente</label>
                            <select name="id_cliente" id="cliente" class="form-control" required>
                                <?php
                                $query_clientes = "SELECT id_cliente, nombre FROM clientes";
                                $result_clientes = $conn->query($query_clientes);
                                while ($row = $result_clientes->fetch_assoc()): ?>
                                    <option value="<?= $row['id_cliente'] ?>"><?= $row['nombre'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <select name="id_clave_secundaria" id="direccion" class="form-control" required>
                                <?php
                                $query_direcciones = "SELECT clave_secundaria, CONCAT(calle, ', ', ciudad) AS direccion FROM direccion_obra";
                                $result_direcciones = $conn->query($query_direcciones);
                                while ($row = $result_direcciones->fetch_assoc()): ?>
                                    <option value="<?= $row['clave_secundaria'] ?>"><?= $row['direccion'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="anticipo" class="form-label">Anticipo</label>
                            <input type="number" step="0.01" name="anticipo" id="anticipo" class="form-control"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="adeudo" class="form-label">Adeudo</label>
                            <input type="number" step="0.01" name="adeudo" id="adeudo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="estatus" class="form-label">Estatus</label>
                            <select name="estatus" id="estatus" class="form-control" required>
                                <option value="En Progreso">En Progreso</option>
                                <option value="Finalizada">Finalizada</option>
                                <option value="Cancelada">Cancelada</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="emailForm" action="enviar_correo_manual.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="emailModalLabel">Enviar Contrato por Correo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo del Cliente</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="ejemplo@correo.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="archivo" class="form-label">Archivo PDF</label>
                            <input type="file" class="form-control" id="archivo" name="archivo" accept=".pdf" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal: Editar Obra -->
    <div class="modal fade" id="editObraModal" tabindex="-1" aria-labelledby="editObraModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="editar_obra.php">
                    <input type="hidden" name="id_obra" id="edit_id_obra">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editObraModalLabel">Editar Obra</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" name="fecha_inicio" id="edit_fecha_inicio" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_anticipo" class="form-label">Anticipo</label>
                            <input type="number" step="0.01" name="anticipo" id="edit_anticipo" class="form-control"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_adeudo" class="form-label">Adeudo</label>
                            <input type="number" step="0.01" name="adeudo" id="edit_adeudo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_observaciones" class="form-label">Observaciones</label>
                            <textarea name="observaciones" id="edit_observaciones" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_estatus" class="form-label">Estatus</label>
                            <select name="estatus" id="edit_estatus" class="form-control" required>
                                <option value="En Progreso">En Progreso</option>
                                <option value="Finalizada">Finalizada</option>
                                <option value="Cancelada">Cancelada</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        function enviarCorreo(idObra) {
            if (confirm('¿Seguro que deseas enviar el contrato por correo?')) {
                const formData = new FormData();
                formData.append('id_obra', idObra);

                fetch('enviar_correo.php', {
                    method: 'POST',
                    body: formData,
                })
                    .then(response => response.text())
                    .then(data => alert(data))
                    .catch(error => console.error('Error al enviar el correo:', error));
            }
        }
    </script>
    <script>
        function prepararEnvioCorreo(cliente, correo) {
            document.getElementById('email').value = correo;
        }
    </script>
    <script>
        function cargarDatosEditar(obra) {
            document.getElementById('edit_id_obra').value = obra.id_obra;
            document.getElementById('edit_fecha_inicio').value = obra.fecha_inicio;
            document.getElementById('edit_anticipo').value = obra.anticipo;
            document.getElementById('edit_adeudo').value = obra.adeudo;
            document.getElementById('edit_observaciones').value = obra.observaciones || '';
            document.getElementById('edit_estatus').value = obra.estatus;
        }

        function eliminarObra(idObra) {
            if (confirm('¿Estás seguro de que deseas eliminar esta obra?')) {
                window.location.href = `eliminar_obra.php?id_obra=${idObra}`;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>