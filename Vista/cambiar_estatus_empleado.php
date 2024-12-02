<?php
include '../modelo/db_connection.php';

if (isset($_POST['id_empleado']) && isset($_POST['nuevo_estatus'])) {
    $id_empleado = $_POST['id_empleado'];
    $nuevo_estatus = $_POST['nuevo_estatus'];

    // Actualizar el estatus del empleado
    $stmt = $conn->prepare("UPDATE empleados SET estatus = ? WHERE id_empleado = ?");
    $stmt->bind_param("si", $nuevo_estatus, $id_empleado);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "No se pudo actualizar el empleado."]);
    }
    exit;
}

// Si los datos no están completos, enviar error
http_response_code(400);
echo json_encode(["success" => false, "error" => "Datos incompletos."]);
