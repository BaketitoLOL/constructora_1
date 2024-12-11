<?php
include '../modelo/db_connection.php';
session_start();

// Verificar si el usuario es empleado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Empleado') {
    header("Location: login.php");
    exit;
}

// Obtener la lista de clientes
$query = "SELECT c.id_cliente, c.nombre, c.apellido_paterno, c.telefono_personal, c.correo, d.calle, d.ciudad, d.estado 
          FROM clientes c 
          LEFT JOIN direccion_obra d ON c.id_direccion = d.id_direccion";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Clientes</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php require_once 'navbar_empleados.php'?>

<div class="container mt-5">
    <h1>Gestionar Clientes</h1>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregarCliente"><i class="fas fa-plus"></i> Agregar Cliente</button>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Dirección</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id_cliente'] ?></td>
                <td><?= $row['nombre'] . ' ' . $row['apellido_paterno'] ?></td>
                <td><?= $row['telefono_personal'] ?></td>
                <td><?= $row['correo'] ?></td>
                <td><?= $row['calle'] . ', ' . $row['ciudad'] . ', ' . $row['estado'] ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditarCliente" 
                            data-id="<?= $row['id_cliente'] ?>" 
                            data-nombre="<?= $row['nombre'] ?>" 
                            data-apellido="<?= $row['apellido_paterno'] ?>" 
                            data-telefono="<?= $row['telefono_personal'] ?>" 
                            data-correo="<?= $row['correo'] ?>">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <a href="eliminar_cliente.php?id=<?= $row['id_cliente'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este cliente?')">
                        <i class="fas fa-trash"></i> Eliminar
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal Agregar Cliente -->
<div class="modal fade" id="modalAgregarCliente" tabindex="-1" aria-labelledby="modalAgregarClienteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="guardar_cliente_empleado.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarClienteLabel">Agregar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono_personal" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono_personal" name="telefono_personal" required>
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Cliente -->
<div class="modal fade" id="modalEditarCliente" tabindex="-1" aria-labelledby="modalEditarClienteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="editar_cliente_empleado.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarClienteLabel">Editar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_cliente" name="id_cliente">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre_editar" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" id="apellido_paterno_editar" name="apellido_paterno" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono_personal" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono_personal_editar" name="telefono_personal" required>
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo_editar" name="correo" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const modalEditar = document.getElementById('modalEditarCliente');
    modalEditar.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        document.getElementById('id_cliente').value = button.getAttribute('data-id');
        document.getElementById('nombre_editar').value = button.getAttribute('data-nombre');
        document.getElementById('apellido_paterno_editar').value = button.getAttribute('data-apellido');
        document.getElementById('telefono_personal_editar').value = button.getAttribute('data-telefono');
        document.getElementById('correo_editar').value = button.getAttribute('data-correo');
    });
</script>
</body>
</html>
