<?php
include '../modelo/db_connection.php';

if (isset($_GET['id'])) {
    $id_cliente = $_GET['id'];

    $query = "
        SELECT c.*, d.calle, d.ciudad, d.estado, d.codigo_postal
        FROM clientes c
        LEFT JOIN direcciones d ON c.id_direccion = d.id_direccion
        WHERE c.id_cliente = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        echo json_encode($result->fetch_assoc());
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Cliente no encontrado."]);
    }
    exit;
}
http_response_code(400);
echo json_encode(["error" => "ID de cliente no especificado."]);
