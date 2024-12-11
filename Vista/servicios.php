<?php
include '../modelo/db_connection.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Servicios</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</head>
<body>
    <?php
    require_once 'navbar.php';
    ?>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Gestión de Servicios</h1>

        <!-- Botón para agregar servicio -->
        <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#modalServicio">Agregar Servicio</button>
        <!-- Botón para ir a agregar categoria.php -->
         <a href="categorias.php" class="btn btn-primary mb-4">Agregar Categoría</a>
         <!-- Botón para regresa a dashboard de administrador -->
          <a href="admin_dashboard.php" class="btn btn-primary mb-4">Regresar</a>

        <!-- Tabla de servicios -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "
                    SELECT s.id_servicio, s.nombre, s.precio, s.estatus, s.imagen, c.nombre_categoria
                    FROM servicios s
                    JOIN categorias c ON s.id_categoria = c.id_categoria
                ";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td>
                        <?php if ($row['imagen']): ?>
                            <img src="<?= htmlspecialchars($row['imagen']) ?>" alt="<?= htmlspecialchars($row['nombre']) ?>" style="width: 50px; height: auto;">
                        <?php else: ?>
                            <span>No Imagen</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                    <td><?= htmlspecialchars($row['nombre_categoria']) ?></td>
                    <td>$<?= htmlspecialchars($row['precio']) ?></td>
                    <td><?= $row['estatus'] === 'Activo' ? 'Activo' : 'Inactivo' ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="editarServicio(<?= $row['id_servicio'] ?>)">Editar</button>
                        <button class="btn btn-danger btn-sm" onclick="eliminarServicio(<?= $row['id_servicio'] ?>)">Eliminar</button>
                        <button class="btn btn-secondary btn-sm" onclick="toggleEstatus(<?= $row['id_servicio'] ?>)">
                            <?= $row['estatus'] === 'Activo' ? 'Desactivar' : 'Activar' ?>
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para agregar/editar servicio -->
    <div class="modal fade" id="modalServicio" tabindex="-1" aria-labelledby="modalServicioLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalServicioLabel">Agregar Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formServicio" method="POST" action="guardar_servicio.php" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <input type="hidden" name="id_servicio" id="id_servicio">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Servicio</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" required>
                            <div class="invalid-feedback">Por favor, introduzca el nombre del servicio.</div>
                        </div>
                        <div class="mb-3">
                            <label for="id_categoria" class="form-label">Categoría</label>
                            <select name="id_categoria" id="id_categoria" class="form-select" required>
                                <option value="" disabled selected>Seleccione una categoría</option>
                                <?php
                                $categorias = $conn->query("SELECT * FROM categorias");
                                while ($categoria = $categorias->fetch_assoc()):
                                ?>
                                <option value="<?= $categoria['id_categoria'] ?>"><?= htmlspecialchars($categoria['nombre_categoria']) ?> (<?= $categoria['altura_pies'] ?> pies)</option>
                                <?php endwhile; ?>
                            </select>
                            <div class="invalid-feedback">Por favor, seleccione una categoría.</div>
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="number" name="precio" id="precio" class="form-control" step="0.01" required>
                            <div class="invalid-feedback">Por favor, introduzca un precio válido.</div>
                        </div>
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen del Servicio</label>
                            <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*">
                            <div class="form-text">Formatos permitidos: JPG, PNG. Tamaño máximo: 2MB.</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="formServicio" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
<script>
    function editarServicio(id) {
    fetch(`obtener_servicio.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            document.getElementById('id_servicio').value = data.id_servicio;
            document.getElementById('nombre').value = data.nombre;
            document.getElementById('id_categoria').value = data.id_categoria;
            document.getElementById('precio').value = data.precio;
            new bootstrap.Modal(document.getElementById('modalServicio')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los datos del servicio.');
        });
}

function eliminarServicio(id) {
    if (confirm('¿Está seguro de eliminar este servicio?')) {
        window.location.href = `eliminar_servicio.php?id=${id}`;
    }
}

function toggleEstatus(id) {
    window.location.href = `toggle_estatus_servicio.php?id=${id}`;
}
</script>
</body>
</html>
