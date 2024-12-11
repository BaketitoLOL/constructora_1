<?php
// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'sistema_constructora');
if ($conn->connect_error)
    die('Error de conexión: ' . $conn->connect_error);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'navbar.php';
    ?>
    <div class="container mt-5">
        <h1>Gestión de Clientes</h1>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addClienteModal">
            <i class="fas fa-plus"></i> Agregar Cliente
        </button>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Dirección de Obra</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT c.id_cliente, CONCAT(c.nombre, ' ', c.apellido_paterno, ' ', c.apellido_materno) AS nombre_completo,
                                 c.telefono_personal, c.correo,
                                 d.calle AS direccion
                          FROM clientes c
                          LEFT JOIN direccion_obra d ON c.id_cliente = d.id_cliente";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id_cliente'] ?></td>
                        <td><?= $row['nombre_completo'] ?></td>
                        <td><?= $row['telefono_personal'] ?></td>
                        <td><?= $row['correo'] ?></td>
                        <td><?= $row['direccion'] ? $row['direccion'] : 'Sin dirección' ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editClienteModal"
                                onclick="cargarDatosEditar(<?= htmlspecialchars(json_encode($row)) ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="eliminarCliente(<?= $row['id_cliente'] ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                            <a href="gestionar_direcciones_obra.php?id_cliente=<?= $row['id_cliente'] ?>"
                                class="btn btn-info btn-sm">
                                <i class="fas fa-map-marker-alt"></i> 
                            </a>


                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal: Agregar Cliente -->
    <div class="modal fade" id="addClienteModal" tabindex="-1" aria-labelledby="addClienteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClienteModalLabel">Agregar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="guardar_cliente.php">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                            <input type="text" name="apellido_paterno" id="apellido_paterno" class="form-control"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="apellido_materno" class="form-label">Apellido Materno</label>
                            <input type="text" name="apellido_materno" id="apellido_materno" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" name="telefono_personal" id="telefono" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" name="correo" id="correo" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cliente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editClienteModal" tabindex="-1" aria-labelledby="editClienteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClienteModalLabel">Editar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="editar_cliente.php">
                    <input type="hidden" name="id_cliente" id="edit_id_cliente">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_apellido_paterno" class="form-label">Apellido Paterno</label>
                            <input type="text" name="apellido_paterno" id="edit_apellido_paterno" class="form-control"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_apellido_materno" class="form-label">Apellido Materno</label>
                            <input type="text" name="apellido_materno" id="edit_apellido_materno" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="edit_telefono_personal" class="form-label">Teléfono</label>
                            <input type="text" name="telefono_personal" id="edit_telefono_personal" class="form-control"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_correo" class="form-label">Correo</label>
                            <input type="email" name="correo" id="edit_correo" class="form-control" required>
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
        function cargarDatosEditar(cliente) {
            document.getElementById('edit_id_cliente').value = cliente.id_cliente;
            document.getElementById('edit_nombre').value = cliente.nombre_completo.split(' ')[0];
            document.getElementById('edit_apellido_paterno').value = cliente.nombre_completo.split(' ')[1];
            document.getElementById('edit_apellido_materno').value = cliente.nombre_completo.split(' ')[2] || '';
            document.getElementById('edit_telefono_personal').value = cliente.telefono_personal;
            document.getElementById('edit_correo').value = cliente.correo;
        }
        function eliminarCliente(id_cliente) {
            if (confirm("¿Estás seguro de que deseas eliminar este cliente? Esta acción no se puede deshacer.")) {
                window.location.href = `eliminar_cliente.php?id_cliente=${id_cliente}`;
            }
        }

    </script>
</body>

</html>