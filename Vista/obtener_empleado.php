<?php
include '../modelo/db_connection.php';

if (isset($_GET['id'])) {
    $id_empleado = intval($_GET['id']);

    $query = "SELECT * FROM empleados WHERE id_empleado = $id_empleado";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['error' => 'Empleado no encontrado']);
    }
} else {
    echo json_encode(['error' => 'ID no proporcionado']);
}
?>
