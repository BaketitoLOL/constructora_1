<?php
// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'sistema_constructora');
if ($conn->connect_error)
    die('Error de conexión: ' . $conn->connect_error);

// Verificar ID del cliente
if (!isset($_GET['id_cliente']) || empty($_GET['id_cliente'])) {
    die('ID de cliente no proporcionado.');
}

$id_cliente = intval($_GET['id_cliente']);

// Obtener cliente
$query_cliente = "SELECT nombre, apellido_paterno, apellido_materno FROM clientes WHERE id_cliente = $id_cliente";
$result_cliente = $conn->query($query_cliente);
$cliente = $result_cliente->fetch_assoc();

// Obtener direcciones del cliente
$query_direcciones = "SELECT * FROM direccion_obra WHERE id_cliente = $id_cliente";
$result_direcciones = $conn->query($query_direcciones);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Direcciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Direcciones de Obra de
            <?= $cliente['nombre'] . ' ' . $cliente['apellido_paterno'] . ' ' . $cliente['apellido_materno'] ?></h1>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addDireccionModal">
            <i class="fas fa-plus"></i> Agregar Dirección
        </button>
        <a href="clientes.php" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Regresar a Clientes
        </a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Calle</th>
                    <th>Ciudad</th>
                    <th>Estado</th>
                    <th>Código Postal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($direccion = $result_direcciones->fetch_assoc()): ?>
                    <tr>
                        <td><?= $direccion['id_direccion'] ?></td>
                        <td><?= $direccion['calle'] ?></td>
                        <td><?= $direccion['ciudad'] ?></td>
                        <td><?= $direccion['estado'] ?></td>
                        <td><?= $direccion['codigo_postal'] ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editDireccionModal"
                                onclick="cargarDatosEditar(<?= htmlspecialchars(json_encode($direccion)) ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm"
                                onclick="eliminarDireccion(<?= $direccion['id_direccion'] ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal: Agregar Dirección -->
    <div class="modal fade" id="addDireccionModal" tabindex="-1" aria-labelledby="addDireccionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDireccionModalLabel">Agregar Dirección</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="guardar_direccion.php">
                    <input type="hidden" name="id_cliente" value="<?= $id_cliente ?>">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="calle" class="form-label">Calle</label>
                            <input type="text" name="calle" id="calle" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="ciudad" class="form-label">Ciudad</label>
                            <input type="text" name="ciudad" id="ciudad" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <input type="text" name="estado" id="estado" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="codigo_postal" class="form-label">Código Postal</label>
                            <input type="text" name="codigo_postal" id="codigo_postal" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Dirección</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editDireccionModal" tabindex="-1" aria-labelledby="editDireccionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDireccionModalLabel">Editar Dirección</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="editar_direccion_obra.php">
                    <input type="hidden" name="id_direccion" id="edit_id_direccion">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_calle" class="form-label">Calle</label>
                            <input type="text" name="calle" id="edit_calle" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_ciudad" class="form-label">Ciudad</label>
                            <input type="text" name="ciudad" id="edit_ciudad" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_estado" class="form-label">Estado</label>
                            <input type="text" name="estado" id="edit_estado" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_codigo_postal" class="form-label">Código Postal</label>
                            <input type="text" name="codigo_postal" id="edit_codigo_postal" class="form-control"
                                required>
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function cargarDatosEditar(direccion) {
            document.getElementById('edit_id_direccion').value = direccion.id_direccion;
            document.getElementById('edit_calle').value = direccion.calle;
            document.getElementById('edit_ciudad').value = direccion.ciudad;
            document.getElementById('edit_estado').value = direccion.estado;
            document.getElementById('edit_codigo_postal').value = direccion.codigo_postal;
        }

        function eliminarDireccion(id_direccion) {
            if (confirm("¿Estás seguro de que deseas eliminar esta dirección?")) {
                window.location.href = `eliminar_direccion.php?id_direccion=${id_direccion}`;
            }
        }
    </script>
</body>

</html>