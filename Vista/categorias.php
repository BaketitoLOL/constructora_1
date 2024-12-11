<?php
include '../modelo/db_connection.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categorías</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Gestión de Categorías</h1>

        <!-- Botón para agregar categoría -->
        <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#modalCategoria">Agregar Categoría</button>
        <!-- Botón para regresar a servicios.php-->
         <a href="servicios.php" class="btn btn-secondary mb-4">Regresar</a>
         

        <!-- Tabla de categorías -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de la Categoría</th>
                    <th>Altura (pies)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM categorias";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_categoria']) ?></td>
                    <td><?= htmlspecialchars($row['nombre_categoria']) ?></td>
                    <td><?= htmlspecialchars($row['altura_pies']) ?> pies</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="editarCategoria(<?= $row['id_categoria'] ?>)">Editar</button>
                        <button class="btn btn-danger btn-sm" onclick="eliminarCategoria(<?= $row['id_categoria'] ?>)">Eliminar</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para agregar/editar categoría -->
    <div class="modal fade" id="modalCategoria" tabindex="-1" aria-labelledby="modalCategoriaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCategoriaLabel">Agregar Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formCategoria" method="POST" action="guardar_categoria.php" class="needs-validation" novalidate>
                        <input type="hidden" name="id_categoria" id="id_categoria">
                        <div class="mb-3">
                            <label for="nombre_categoria" class="form-label">Nombre de la Categoría</label>
                            <input type="text" name="nombre_categoria" id="nombre_categoria" class="form-control" required>
                            <div class="invalid-feedback">Por favor, introduzca el nombre de la categoría.</div>
                        </div>
                        <div class="mb-3">
                            <label for="altura_pies" class="form-label">Altura (pies)</label>
                            <input type="number" name="altura_pies" id="altura_pies" class="form-control" required>
                            <div class="invalid-feedback">Por favor, introduzca una altura válida.</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="formCategoria" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editarCategoria(id) {
            // Aquí se puede implementar la lógica para precargar datos en el modal para editar
            fetch(`obtener_categoria.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    document.getElementById('id_categoria').value = data.id_categoria;
                    document.getElementById('nombre_categoria').value = data.nombre_categoria;
                    document.getElementById('altura_pies').value = data.altura_pies;
                    new bootstrap.Modal(document.getElementById('modalCategoria')).show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cargar los datos de la categoría.');
                });
        }

        function eliminarCategoria(id) {
            if (confirm('¿Está seguro de eliminar esta categoría?')) {
                window.location.href = `eliminar_categoria.php?id=${id}`;
            }
        }
    </script>
</body>
</html>
