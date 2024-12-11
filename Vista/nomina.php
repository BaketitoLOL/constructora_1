<?php
include '../modelo/db_connection.php';

// Obtener empleados para los selectores
$query = "SELECT id_empleado, nombre, apellido_paterno, apellido_materno, salario FROM empleados WHERE estatus = 'Activo'";
$result = $conn->query($query);
$empleados = [];
while ($row = $result->fetch_assoc()) {
    $empleados[] = $row;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/nomina.css"> 
    <link rel="stylesheet" href="../assets/cards.css"><!-- Estilos específicos para nóminas -->
    <title>Gestión de Nómina</title>
</head>

<body>

    <div class="dashboard-container">
        <main class="main-content">
            <header>
                <h1>Gestión de Nómina</h1>
                <button class="btn btn-primary" onclick="abrirModalNomina()">Registrar Nómina</button>
                <hr>
            </header>
            <!-- Tabla de registros de nómina -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Semana</th>
                        <th>Días Trabajados</th>
                        <th>Sueldo Semanal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "
                        SELECT n.*, e.nombre, e.apellido_paterno, e.apellido_materno
                        FROM nomina n
                        JOIN empleados e ON n.id_empleado = e.id_empleado
                        ORDER BY n.semana DESC
                    ";
                    $nominaResult = $conn->query($query);
                    while ($row = $nominaResult->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno']) ?>
                            </td>
                            <td><?= htmlspecialchars($row['semana']) ?></td>
                            <td><?= htmlspecialchars($row['dias_trabajados']) ?></td>
                            <td>$<?= htmlspecialchars($row['sueldo_semanal']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </main>
    </div>

    <!-- Modal para agregar nómina -->
    <div class="modal-overlay-nomina" id="modalNomina">
        <div class="modal-nomina">
            <h2>Registrar Nómina</h2>
            <form id="formNomina" method="POST" action="agregar_nomina.php">
                <label for="id_empleado">Empleado:</label>
                <select name="id_empleado" id="id_empleado" required>
                    <option value="" disabled selected>Seleccione un empleado</option>
                    <?php foreach ($empleados as $empleado): ?>
                        <option value="<?= $empleado['id_empleado'] ?>" data-salario="<?= $empleado['salario'] ?>">
                            <?= htmlspecialchars($empleado['nombre'] . ' ' . $empleado['apellido_paterno'] . ' ' . $empleado['apellido_materno']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="semana">Semana:</label>
                <input type="number" name="semana" id="semana" min="1" max="52" required>

                <label for="dias_trabajados">Días Trabajados:</label>
                <input type="number" name="dias_trabajados" id="dias_trabajados" min="1" max="7" required>

                <label for="sueldo_semanal">Sueldo Semanal:</label>
                <input type="text" id="sueldo_semanal" readonly>

                <button type="submit" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-danger" onclick="cerrarModalNomina()">Cancelar</button>
            </form>
        </div>
    </div>

    <section class="card-container">
        <div class="card">
            <h2>Búsqueda por Empleado</h2>
            <form id="buscarEmpleadoForm" onsubmit="return buscarPorEmpleado(event)">
                <label for="buscarEmpleado">Seleccione un Empleado:</label>
                <select name="id_empleado" id="buscarEmpleado" required>
                    <option value="" disabled selected>Seleccione un empleado</option>
                    <?php foreach ($empleados as $empleado): ?>
                        <option value="<?= $empleado['id_empleado'] ?>">
                            <?= htmlspecialchars($empleado['nombre'] . ' ' . $empleado['apellido_paterno'] . ' ' . $empleado['apellido_materno']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
            <div id="resultadoEmpleado">
                <!-- Resultados de la búsqueda por empleado -->
            </div>
        </div>

        <div class="card">
            <h2>Búsqueda por Semana</h2>
            <form id="buscarSemanaForm" onsubmit="return buscarPorSemana(event)">
                <label for="buscarSemana">Semana:</label>
                <input type="number" id="buscarSemana" name="semana" min="1" max="52" required>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
            <div id="resultadoSemana">
                <!-- Resultados de la búsqueda por semana -->
            </div>
        </div>
    </section>

    <script>
        const empleados = <?= json_encode($empleados) ?>; // Datos dinámicos de empleados
    </script>
    <script src="../assets/main_nomina.js"></script>
</body>

</html>