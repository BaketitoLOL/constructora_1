<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root"; // Cambia esto si tienes otro usuario
$password = ""; // Cambia esto si tienes una contraseña
$database = "sistema_constructora";

$conn = new mysqli($host, $user, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conn->connect_error]));
}

// Verificar si se proporcionó el ID de sucursal
if (isset($_GET['id_sucursal'])) {
    $id_sucursal = intval($_GET['id_sucursal']);

    // Consultar el conteo de empleados
    $query = "SELECT COUNT(*) AS empleados_asociados FROM empleados WHERE sucursal_asociada = $id_sucursal";
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        echo json_encode(["empleados_asociados" => $row['empleados_asociados']]);
    } else {
        echo json_encode(["error" => "Error al realizar la consulta: " . $conn->error]);
    }
} else {
    echo json_encode(["error" => "ID de sucursal no proporcionado."]);
}
?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const rows = document.querySelectorAll("tr[data-sucursal-id]");

        rows.forEach(row => {
            const idSucursal = row.getAttribute("data-sucursal-id");
            const empleadosCell = row.querySelector(".empleados-asociados");

            fetch(`contador_empleados.php?id_sucursal=${idSucursal}`)
                .then(response => response.json())
                .then(data => {
                    if (data.empleados_asociados !== undefined) {
                        empleadosCell.textContent = data.empleados_asociados;
                    } else {
                        empleadosCell.textContent = "Error";
                    }
                })
                .catch(error => {
                    empleadosCell.textContent = "Error";
                    console.error("Error al obtener empleados asociados:", error);
                });
        });
    });
</script>
