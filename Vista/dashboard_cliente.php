<?php
session_start();
include '../modelo/db_connection.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Cliente') {
    header("Location: login.php");
    exit;
}

// Obtener el nombre del cliente basado en la sesión
$id_cliente = $_SESSION['id_cliente'] ?? null;
$nombre_cliente = "Cliente";

if ($id_cliente) {
    $query = $conn->prepare("SELECT CONCAT(nombre, ' ', apellido_paterno) AS nombre_completo FROM clientes WHERE id_cliente = ?");
    $query->bind_param("i", $id_cliente);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows === 1) {
        $nombre_cliente = $result->fetch_assoc()['nombre_completo'];
    }
}

// Obtener los servicios
$query_servicios = "SELECT * FROM servicios WHERE estatus = 'Activo'";
$result_servicios = $conn->query($query_servicios);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Cliente</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .dashboard-container {
            display: flex;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: #fff;
            min-height: 100vh;
            padding: 20px;
        }
        .sidebar a {
            text-decoration: none;
            color: #fff;
            display: block;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .service-card img {
            max-height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h3 class="text-center">Cliente</h3>
        <a href="perfil_cliente.php"><i class="fas fa-user-circle"></i> Mi Perfil</a>
        <a href="consultar_obras_clientes.php"><i class="fas solid fa-building"></i> Mis obras</a>
        <a href="ver_servicios.php"><i class="fa-brands fa-readme"></i></i> Ver Servicios</a>
        <a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </div>

    <!-- Contenido Principal -->
    <div class="content">
        <h1>Bienvenido, <?= htmlspecialchars($nombre_cliente) ?></h1>
        <p>Consulta nuestros servicios disponibles:</p>

        <div class="row">
            <?php while ($servicio = $result_servicios->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card service-card">
                        <img src="<?= htmlspecialchars($servicio['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($servicio['nombre']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($servicio['nombre']) ?></h5>
                            <p class="card-text text-truncate"><?= htmlspecialchars($servicio['descripcion']) ?></p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#serviceModal<?= $servicio['id_servicio'] ?>">
                                Ver Más
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal para servicio -->
                <div class="modal fade" id="serviceModal<?= $servicio['id_servicio'] ?>" tabindex="-1" aria-labelledby="serviceModalLabel<?= $servicio['id_servicio'] ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="serviceModalLabel<?= $servicio['id_servicio'] ?>">
                                    <?= htmlspecialchars($servicio['nombre']) ?>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <img src="<?= htmlspecialchars($servicio['imagen']) ?>" class="img-fluid mb-3" alt="<?= htmlspecialchars($servicio['nombre']) ?>">
                                <p><?= htmlspecialchars($servicio['descripcion']) ?></p>
                                <p><strong>Precio:</strong> $<?= number_format($servicio['precio'], 2) ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
