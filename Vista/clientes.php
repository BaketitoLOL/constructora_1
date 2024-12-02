<?php
include '../modelo/db_connection.php';

// Consultar todos los clientes con sus direcciones
$query = "
    SELECT c.id_cliente, c.nombre, c.apellido_paterno, c.apellido_materno, c.telefono_personal, c.correo,
           d.calle, d.ciudad, d.estado, d.codigo_postal, c.estatus
    FROM clientes c
    LEFT JOIN direcciones d ON c.id_direccion = d.id_direccion
";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/styles.css">
    <title>Clientes</title>
</head>

<body>
    <div class="dashboard-container">
        <main class="main-content">
            <header>
                <h1>Gestión de Clientes</h1>
                <button class="btn btn-primary" onclick="abrirModalCliente('Agregar')">Agregar Cliente</button>
            </header>
            <hr>
            <form id="formBuscarCliente" onsubmit="return filtrarClientesConBoton(event)">
                <input type="text" id="buscarCliente" placeholder="Buscar cliente...">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>

            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Dirección</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno']) ?>
                            </td>
                            <td><?= htmlspecialchars($row['telefono_personal']) ?></td>
                            <td><?= htmlspecialchars($row['correo']) ?></td>
                            <td>
                                <?= htmlspecialchars($row['calle'] ?? 'Sin dirección') ?>,
                                <?= htmlspecialchars($row['ciudad'] ?? '') ?>,
                                <?= htmlspecialchars($row['estado'] ?? '') ?>,
                                <?= htmlspecialchars($row['codigo_postal'] ?? '') ?>
                            </td>
                            <td><?= htmlspecialchars($row['estatus']) ?></td>
                            <td>
                                <button class="btn btn-warning"
                                    onclick="abrirModalCliente('Editar', <?= $row['id_cliente'] ?>)">Editar</button>

                                <button class="btn btn-danger"
                                    onclick="eliminarCliente(<?= $row['id_cliente'] ?>)">Eliminar</button>
                            </td>
                            <td class="<?= $row['estatus'] === 'Activo' ? 'status-activo' : 'status-inactivo' ?>">
                                <?= htmlspecialchars($row['estatus']) ?>
                                <button class="btn btn-danger"
                                    onclick="cambiarEstatusCliente(<?= $row['id_cliente'] ?>, '<?= $row['estatus'] ?>')">
                                    <?= $row['estatus'] === 'Activo' ? 'Inactivar' : 'Reactivar' ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </main>
    </div>
    <script src="../assets/main_cliente.js"></script>
</body>

</html>