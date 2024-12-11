<?php
// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'sistema_constructora');
if ($conn->connect_error) die('Error de conexión: ' . $conn->connect_error);

// Verificar si se proporcionó el ID del servicio
if (isset($_GET['id_servicio']) && !empty($_GET['id_servicio'])) {
    $id_servicio = intval($_GET['id_servicio']);

    // Obtener el precio del servicio
    $query = "SELECT precio FROM servicios WHERE id_servicio = $id_servicio AND estatus = 'Activo'";
    $result = $conn->query($query);

    if ($result && $row = $result->fetch_assoc()) {
        // Devolver el precio en formato JSON
        header('Content-Type: application/json');
        echo json_encode(['precio' => $row['precio']]);
    } else {
        // En caso de error o si no se encuentra el servicio
        http_response_code(404);
        echo json_encode(['error' => 'Servicio no encontrado.']);
    }
} else {
    // Si no se proporciona un ID válido
    http_response_code(400);
    echo json_encode(['error' => 'ID de servicio no proporcionado o inválido.']);
}
?>
