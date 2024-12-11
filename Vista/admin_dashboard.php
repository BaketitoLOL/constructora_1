<?php
include '../modelo/db_connection.php';

// Consultar métricas clave
$query_sucursales = "SELECT COUNT(*) AS total FROM sucursales";
$query_empleados = "SELECT COUNT(*) AS total FROM empleados";
$query_clientes = "SELECT COUNT(*) AS total FROM clientes";
$query_servicios = "SELECT COUNT(*) AS total FROM servicios WHERE estatus = 'Activo'";

$total_sucursales = $conn->query($query_sucursales)->fetch_assoc()['total'];
$total_empleados = $conn->query($query_empleados)->fetch_assoc()['total'];
$total_clientes = $conn->query($query_clientes)->fetch_assoc()['total'];
$total_servicios = $conn->query($query_servicios)->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard del Administrador</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: #fff;
            position: fixed;
            height: 100%;
            padding-top: 20px;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }
        .sidebar a:hover {
            background-color: #495057;
            border-radius: 5px;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-icon {
            font-size: 2rem;
            color: #fff;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center">Menú</h4>
        <a href="admin_dashboard.php"><i class="fas fa-home"></i> Inicio</a>
        <a href="sucursales.php"><i class="fas fa-building"></i> Sucursales</a>
        <a href="empleados.php"><i class="fas fa-users"></i> Empleados</a>
        <a href="clientes.php"><i class="fas fa-user-circle"></i> Clientes</a>
        <a href="presupuesto.php"><i class="fas fa-file-invoice-dollar"></i> Presupuestos</a>
        <a href="servicios.php"><i class="fas fa-cogs"></i> Servicios</a>
        <a href="nomina.php"><i class="fas fa-money-bill"></i> Nóminas</a>
        <a href="obras.php"><i class="fas fa-hard-hat"></i> Obras</a>
        <a href="login.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </div>

    <!-- Contenido Principal -->
    <div class="main-content">
        <h1 class="mb-4">Dashboard del Administrador</h1>
        <div class="row">
            <!-- Tarjeta de Sucursales -->
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-building card-icon me-3"></i>
                            <div>
                                <h5 class="card-title">Sucursales</h5>
                                <p class="card-text"><?= $total_sucursales ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tarjeta de Empleados -->
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users card-icon me-3"></i>
                            <div>
                                <h5 class="card-title">Empleados</h5>
                                <p class="card-text"><?= $total_empleados ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tarjeta de Clientes -->
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-circle card-icon me-3"></i>
                            <div>
                                <h5 class="card-title">Clientes</h5>
                                <p class="card-text"><?= $total_clientes ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tarjeta de Servicios Activos -->
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-cogs card-icon me-3"></i>
                            <div>
                                <h5 class="card-title">Servicios Activos</h5>
                                <p class="card-text"><?= $total_servicios ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
