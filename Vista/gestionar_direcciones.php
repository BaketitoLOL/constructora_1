<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root"; // Cambia esto si tienes otro usuario
$password = ""; // Cambia esto si tienes una contraseña
$database = "sistema_constructora";

$conn = new mysqli($host, $user, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener el ID de la sucursal
$sucursal_id = isset($_GET['sucursal_id']) ? intval($_GET['sucursal_id']) : 0;

// Verificar si se proporcionó un ID válido
if ($sucursal_id === 0) {
    die("ID de sucursal no proporcionado.");
}

// Consultar las direcciones asociadas a la sucursal
$query = "SELECT * FROM direcciones WHERE tipo_entidad = 'Sucursal' AND id_entidad = $sucursal_id";
$result = $conn->query($query);
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
        <h1 class="mb-4">Direcciones de la Sucursal</h1>
        <a href="sucursales.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Volver</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Número Exterior</th>
                    <th>Calle</th>
                    <th>Ciudad</th>
                    <th>Estado</th>
                    <th>Código Postal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id_direccion'] ?></td>
                        <td><?= $row['num_ext'] ?></td>
                        <td><?= $row['calle'] ?></td>
                        <td><?= $row['ciudad'] ?></td>
                        <td><?= $row['estado'] ?></td>
                        <td><?= $row['codigo_postal'] ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editDireccionModal" onclick="setDireccionData(<?= htmlspecialchars(json_encode($row)) ?>)">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="eliminarDireccion(<?= $row['id_direccion'] ?>)">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para Editar Dirección -->
    <div class="modal fade" id="editDireccionModal" tabindex="-1" aria-labelledby="editDireccionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDireccionModalLabel">Editar Dirección</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="editar_direccion.php">
                    <input type="hidden" id="edit_id_direccion" name="id_direccion">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_num_ext" class="form-label">Número Exterior</label>
                            <input type="text" class="form-control" id="edit_num_ext" name="num_ext" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_calle" class="form-label">Calle</label>
                            <input type="text" class="form-control" id="edit_calle" name="calle" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_ciudad" class="form-label">Ciudad</label>
                            <input type="text" class="form-control" id="edit_ciudad" name="ciudad" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_estado" class="form-label">Estado</label>
                            <input type="text" class="form-control" id="edit_estado" name="estado" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_codigo_postal" class="form-label">Código Postal</label>
                            <input type="text" class="form-control" id="edit_codigo_postal" name="codigo_postal" required>
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
        function setDireccionData(direccion) {
            document.getElementById('edit_id_direccion').value = direccion.id_direccion;
            document.getElementById('edit_num_ext').value = direccion.num_ext;
            document.getElementById('edit_calle').value = direccion.calle;
            document.getElementById('edit_ciudad').value = direccion.ciudad;
            document.getElementById('edit_estado').value = direccion.estado;
            document.getElementById('edit_codigo_postal').value = direccion.codigo_postal;
        }

        function eliminarDireccion(id) {
            if (confirm('¿Estás seguro de que deseas eliminar esta dirección?')) {
                window.location.href = `eliminar_direccion.php?id=${id}`;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
