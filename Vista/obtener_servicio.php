<?php
include '../modelo/db_connection.php';

// Verificar que se reciba el ID del servicio
if (isset($_GET['id'])) {
    $id_servicio = intval($_GET['id']); // Asegurarse de que el ID sea un entero

    // Consulta para obtener los datos del servicio
    $query = "SELECT * FROM servicios WHERE id_servicio = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_servicio);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el servicio
    if ($result->num_rows === 1) {
        echo json_encode($result->fetch_assoc());
    } else {
        // Servicio no encontrado
        http_response_code(404);
        echo json_encode(["error" => "Servicio no encontrado."]);
    }
    exit;
}

// Si no se envió un ID válido
http_response_code(400);
echo json_encode(["error" => "ID de servicio no especificado."]);
?>
