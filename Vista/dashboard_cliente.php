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
    </style>
</head>
<body>
<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h3 class="text-center">Cliente</h3>
        <a href="perfil_cliente.php"><i class="fas fa-user-circle"></i> Mi Perfil</a>
        <a href="consultar_presu_clientes.php"><i class="fas fa-file-alt"></i> Mis Presupuestos</a>
        <a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </div>

    <!-- Contenido Principal -->
    <div class="content">
        <h1>Bienvenido, <?= htmlspecialchars($nombre_cliente) ?></h1>
        <p>Desde este panel puedes gestionar tus presupuestos, contratos, y actualizar tu perfil.</p>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Mis Presupuestos</h5>
                        <p class="card-text">Consulta los presupuestos generados y descarga los PDF.</p>
                        <a href="consultar_presu_clientes.php" class="btn btn-light">Ver Presupuestos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Mi Perfil</h5>
                        <p class="card-text">Actualiza tu información personal y mantén tus datos al día.</p>
                        <a href="perfil_cliente.php" class="btn btn-light">Actualizar Perfil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
