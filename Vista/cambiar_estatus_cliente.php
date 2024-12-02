<?php
include '../modelo/db_connection.php';

// Verificar que los datos POST existan
if (!empty($_POST['id_cliente']) && !empty($_POST['nuevo_estatus'])) {
    $id_cliente = $_POST['id_cliente'];
    $nuevo_estatus = $_POST['nuevo_estatus'];

    // Actualizar el estatus del cliente
    $stmt = $conn->prepare("UPDATE clientes SET estatus = ? WHERE id_cliente = ?");
    $stmt->bind_param("si", $nuevo_estatus, $id_cliente);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "No se pudo actualizar el cliente."]);
    }
    exit;
}

// Si los datos no están completos, enviar error
http_response_code(400);
echo json_encode(["success" => false, "error" => "Datos incompletos."]);

