<?php
include '../modelo/db_connection.php';

if (isset($_GET['id'])) {
    $id_empleado = intval($_GET['id']);

    $query = "DELETE FROM empleados WHERE id_empleado = $id_empleado";

    if ($conn->query($query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
}
?>