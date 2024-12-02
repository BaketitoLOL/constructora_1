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
    <link rel="stylesheet" href="../assets/styles.css">
    <title>Admin Dashboard</title>
</head>
<body>
    <!-- Contenedor principal -->
    <div class="dashboard-container">
        <!-- Barra lateral -->
        <aside class="sidebar">
            <ul>
                <li><a href="admin_dashboard.php" class="active"><i class="icon ion-md-speedometer"></i> Dashboard</a></li>
                <li><a href="sucursales.php"><i class="icon ion-md-business"></i> Sucursales</a></li>
                <li><a href="empleados.php"><i class="icon ion-md-people"></i> Empleados</a></li>
                <li><a href="clientes.php"><i class="icon ion-md-person"></i> Clientes</a></li>
                <li><a href="presupuestos.php"><i class="icon ion-md-document"></i> Presupuestos</a></li>
            </ul>
        </aside>
        <!-- Contenido principal -->
        <main class="main-content">
            <header>
                <h1>Dashboard del Administrador</h1>
            </header>
            <!-- Tarjetas resumen -->
            <section class="cards">
                <div class="card">
                    <h3>Sucursales</h3>
                    <p><?= $total_sucursales ?></p>
                </div>
                <div class="card">
                    <h3>Empleados</h3>
                    <p><?= $total_empleados ?></p>
                </div>
                <div class="card">
                    <h3>Clientes</h3>
                    <p><?= $total_clientes ?></p>
                </div>
                <div class="card">
                    <h3>Servicios Activos</h3>
                    <p><?= $total_servicios ?></p>
                </div>
            </section>
            <!-- Gráficos -->
            <section class="charts">
                <div class="chart">
                    <h3>Ingresos Mensuales</h3>
                    <canvas id="chartIngresos"></canvas>
                </div>
                <div class="chart">
                    <h3>Obras por Estatus</h3>
                    <canvas id="chartObras"></canvas>
                </div>
            </section>
        </main>
    </div>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico de ingresos mensuales
        const ctxIngresos = document.getElementById('chartIngresos').getContext('2d');
        new Chart(ctxIngresos, {
            type: 'line',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril'], // Modificar según datos reales
                datasets: [{
                    label: 'Ingresos ($)',
                    data: [5000, 10000, 15000, 20000], // Modificar según datos reales
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true
                }]
            }
        });

        // Gráfico de obras por estatus
        const ctxObras = document.getElementById('chartObras').getContext('2d');
        new Chart(ctxObras, {
            type: 'pie',
            data: {
                labels: ['En Progreso', 'Finalizadas', 'Canceladas'],
                datasets: [{
                    label: 'Obras',
                    data: [10, 5, 2], // Modificar según datos reales
                    backgroundColor: ['#007bff', '#28a745', '#dc3545']
                }]
            }
        });
    </script>
</body>
</html>
