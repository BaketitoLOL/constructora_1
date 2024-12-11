<?php
include '../modelo/db_connection.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empleados</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <?php require_once 'navbar.php'; ?>

    <div class="container mt-5">
        <h1 class="mb-4">Gestión de Empleados</h1>

        <!-- Botón para Abrir Modal de Agregar -->
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
            data-bs-target="#modalAgregarEmpleado">
            Agregar Empleado
        </button>

        <!-- Modal para Agregar Empleado -->
        <!-- Modal para Agregar Empleado -->
        <div class="modal fade" id="modalAgregarEmpleado" tabindex="-1" aria-labelledby="modalAgregarEmpleadoLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAgregarEmpleadoLabel">Agregar Empleado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formAgregarEmpleado" method="POST" action="agregar_empleado.php">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                                <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="apellido_materno" class="form-label">Apellido Materno</label>
                                <input type="text" class="form-control" id="apellido_materno" name="apellido_materno">
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" required>
                            </div>
                            <div class="mb-3">
                                <label for="correo_personal" class="form-label">Correo</label>
                                <input type="email" class="form-control" id="correo_personal" name="correo_personal"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="cargo" class="form-label">Cargo</label>
                                <input type="text" class="form-control" id="cargo" name="cargo" required>
                            </div>
                            <div class="mb-3">
                                <label for="actividades" class="form-label">Actividades</label>
                                <textarea class="form-control" id="actividades" name="actividades" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="sucursal_asociada" class="form-label">Sucursal Asociada</label>
                                <select class="form-select" id="sucursal_asociada" name="sucursal_asociada" required>
                                    <option value="" disabled selected>Seleccione una sucursal</option>
                                    <?php
                                    $sucursales = $conn->query("SELECT id_sucursal, nombre FROM sucursales");
                                    while ($sucursal = $sucursales->fetch_assoc()):
                                        ?>
                                        <option value="<?= $sucursal['id_sucursal'] ?>">
                                            <?= htmlspecialchars($sucursal['nombre']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="salario" class="form-label">Salario</label>
                                <input type="number" class="form-control" id="salario" name="salario" step="0.01"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="hora_entrada" class="form-label">Hora de Entrada</label>
                                <input type="time" class="form-control" id="hora_entrada" name="hora_entrada" required>
                            </div>
                            <div class="mb-3">
                                <label for="hora_salida" class="form-label">Hora de Salida</label>
                                <input type="time" class="form-control" id="hora_salida" name="hora_salida" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Editar Empleado -->
        <!-- Modal para Editar Empleado -->
        <div class="modal fade" id="modalEditarEmpleado" tabindex="-1" aria-labelledby="modalEditarEmpleadoLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarEmpleadoLabel">Editar Empleado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formEditarEmpleado" method="POST" action="editar_empleado.php">
                            <input type="hidden" name="id_empleado" id="id_empleado">

                            <div class="mb-3">
                                <label for="edit_nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_apellido_paterno" class="form-label">Apellido Paterno</label>
                                <input type="text" class="form-control" id="edit_apellido_paterno"
                                    name="apellido_paterno" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_apellido_materno" class="form-label">Apellido Materno</label>
                                <input type="text" class="form-control" id="edit_apellido_materno"
                                    name="apellido_materno">
                            </div>
                            <div class="mb-3">
                                <label for="edit_telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="edit_telefono" name="telefono" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_correo_personal" class="form-label">Correo</label>
                                <input type="email" class="form-control" id="edit_correo_personal"
                                    name="correo_personal" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_cargo" class="form-label">Cargo</label>
                                <input type="text" class="form-control" id="edit_cargo" name="cargo">
                            </div>
                            <div class="mb-3">
                                <label for="edit_actividades" class="form-label">Actividades</label>
                                <textarea class="form-control" id="edit_actividades" name="actividades" ></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="edit_sucursal_asociada" class="form-label">Sucursal Asociada</label>
                                <select class="form-select" id="edit_sucursal_asociada" name="sucursal_asociada"
                                    required>
                                    <option value="" disabled selected>Seleccione una sucursal</option>
                                    <?php
                                    $sucursales = $conn->query("SELECT id_sucursal, nombre FROM sucursales");
                                    while ($sucursal = $sucursales->fetch_assoc()):
                                        ?>
                                        <option value="<?= $sucursal['id_sucursal'] ?>">
                                            <?= htmlspecialchars($sucursal['nombre']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="edit_salario" class="form-label">Salario</label>
                                <input type="number" class="form-control" id="edit_salario" name="salario" step="0.01"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_hora_entrada" class="form-label">Hora de Entrada</label>
                                <input type="time" class="form-control" id="edit_hora_entrada" name="hora_entrada"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_hora_salida" class="form-label">Hora de Salida</label>
                                <input type="time" class="form-control" id="edit_hora_salida" name="hora_salida"
                                    required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal para Confirmar Eliminación -->
        <div class="modal fade" id="modalEliminarEmpleado" tabindex="-1" aria-labelledby="modalEliminarEmpleadoLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEliminarEmpleadoLabel">Eliminar Empleado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar este empleado?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="btnConfirmarEliminacion">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Empleados -->
        <div class="card">
            <div class="card-header">Listado de Empleados</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Cargo</th>
                            <th>Actividades</th>
                            <th>Sucursal</th>
                            <th>Salario</th>
                            <th>Hora Entrada</th>
                            <th>Hora Salida</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    < <tbody>
                        <?php
                        $query = "
                            SELECT e.*, s.nombre AS sucursal_nombre
                            FROM empleados e
                            JOIN sucursales s ON e.sucursal_asociada = s.id_sucursal
                            ORDER BY e.id_empleado DESC
                        ";
                        $result = $conn->query($query);

                        while ($row = $result->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno']) ?>
                                </td>
                                <td><?= htmlspecialchars($row['telefono']) ?></td>
                                <td><?= htmlspecialchars($row['correo_personal']) ?></td>
                                <td><?= htmlspecialchars($row['cargo']) ?></td>
                                <td><?= htmlspecialchars($row['actividades']) ?></td>
                                <td><?= htmlspecialchars($row['sucursal_nombre']) ?></td>
                                <td>$<?= htmlspecialchars($row['salario']) ?></td>
                                <td><?= htmlspecialchars($row['hora_entrada']) ?></td>
                                <td><?= htmlspecialchars($row['hora_salida']) ?></td>
                                <td><?= htmlspecialchars($row['estatus']) ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalEditarEmpleado"
                                        onclick="cargarDatosEmpleado(<?= $row['id_empleado'] ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <br>
                                    <br>
                                    <button class="btn btn-danger btn-sm"
                                        onclick="confirmarEliminacion(<?= $row['id_empleado'] ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Scripts personalizados -->
    <script>
        function cargarDatosEmpleado(idEmpleado) {
            fetch(`obtener_empleado.php?id=${idEmpleado}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        // Asignar valores a los campos del modal
                        document.getElementById('id_empleado').value = data.id_empleado;
                        document.getElementById('edit_nombre').value = data.nombre;
                        document.getElementById('edit_apellido_paterno').value = data.apellido_paterno;
                        document.getElementById('edit_apellido_materno').value = data.apellido_materno;
                        document.getElementById('edit_telefono').value = data.telefono;
                        document.getElementById('edit_correo_personal').value = data.correo_personal;
                        document.getElementById('edit_cargo').value = data.cargo;
                        document.getElementById('edit_actividades').value = data.actividades;
                        document.getElementById('edit_sucursal_asociada').value = data.sucursal_asociada;
                        document.getElementById('edit_salario').value = data.salario;
                        document.getElementById('edit_hora_entrada').value = data.hora_entrada;
                        document.getElementById('edit_hora_salida').value = data.hora_salida;
                    }
                })
                .catch(error => console.error('Error:', error));
        }


        function confirmarEliminacion(idEmpleado) {
            if (confirm('¿Estás seguro de que deseas eliminar este empleado?')) {
                fetch(`eliminar_empleado.php?id=${idEmpleado}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Empleado eliminado con éxito.');
                            location.reload();
                        } else {
                            alert('Error al eliminar el empleado.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }

    </script>
</body>

</html>