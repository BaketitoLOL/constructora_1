<?php
include '../modelo/db_connection.php';

// Consultar empleados y sus sucursales
$query = "
    SELECT e.id_empleado, e.nombre, e.apellido_paterno, e.apellido_materno, e.telefono,
           e.correo_personal, e.cargo, e.salario, e.estatus, s.nombre AS sucursal
    FROM empleados e
    LEFT JOIN sucursales s ON e.sucursal_asociada = s.id_sucursal
";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/styles.css">
    <title>Empleados</title>
</head>

<body>
    <div class="dashboard-container">
        <main class="main-content">
            <header>
                <h1>Gestión de Empleados</h1>
                <button class="btn btn-primary" onclick="abrirModalEmpleado('Agregar')">Agregar Empleado</button>
            </header>
            <hr>
            <form id="formBuscarEmpleado" onsubmit="return filtrarEmpleadosConBoton()">
                <input type="text" id="buscarEmpleado" placeholder="Buscar empleado...">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>

            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Cargo</th>
                        <th>Sucursal</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nombre']) ?></td>
                            <td><?= htmlspecialchars($row['apellido_paterno'] . ' ' . $row['apellido_materno']) ?></td>
                            <td><?= htmlspecialchars($row['telefono']) ?></td>
                            <td><?= htmlspecialchars($row['correo_personal']) ?></td>
                            <td><?= htmlspecialchars($row['cargo']) ?></td>
                            <td><?= htmlspecialchars($row['sucursal']) ?></td>
                            <td><?= htmlspecialchars($row['estatus']) ?></td>
                            <td>
                                <button class="btn btn-warning"
                                    onclick="abrirModalEmpleado('Editar', <?= $row['id_empleado'] ?>)">Editar</button>
                                <button class="btn btn-danger"
                                    onclick="eliminarEmpleado(<?= $row['id_empleado'] ?>)">Eliminar</button>
                            </td>
                            <td class="<?= $row['estatus'] === 'Activo' ? 'status-activo' : 'status-inactivo' ?>">
                                <?= htmlspecialchars($row['estatus']) ?>
                                <button class="btn btn-danger"
                                    onclick="cambiarEstatusEmpleado(<?= $row['id_empleado'] ?>, '<?= $row['estatus'] ?>')">
                                    <?= $row['estatus'] === 'Activo' ? 'Inactivar' : 'Reactivar' ?>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </main>
    </div>
    <script src="../assets/main_empleados.js"></script>
</body>

</html>