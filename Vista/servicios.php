<?php
include '../modelo/db_connection.php';

// Consultar todos los servicios
$query = "SELECT * FROM servicios";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/styles.css">
    <title>Servicios</title>
</head>
<body>
    <div class="dashboard-container">
        <main class="main-content">
            <header>
                <h1>Gestión de Servicios</h1>
                <button class="btn btn-primary" onclick="abrirModalServicio('Agregar')">Agregar Servicio</button>
            </header>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Imagen</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nombre']) ?></td>
                            <td><?= htmlspecialchars($row['descripcion']) ?></td>
                            <td>
                                <?php if (!empty($row['imagen'])): ?>
                                    <img src="../uploads/<?= htmlspecialchars($row['imagen']) ?>" alt="Imagen del servicio" width="50">
                                <?php else: ?>
                                    Sin imagen
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($row['estatus']) ?></td>
                            <td>
                                <button class="btn btn-warning" onclick="abrirModalServicio('Editar', <?= $row['id_servicio'] ?>)">Editar</button>
                                <button class="btn btn-danger" onclick="cambiarEstatusServicio(<?= $row['id_servicio'] ?>, '<?= $row['estatus'] ?>')">
                                    <?= $row['estatus'] === 'Activo' ? 'Inactivar' : 'Reactivar' ?>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </main>
    </div>
    <script src="../assets/main_servicio.js"></script>
</body>
</html>
