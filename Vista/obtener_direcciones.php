<?php
// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'sistema_constructora');
if ($conn->connect_error) die('Error de conexión: ' . $conn->connect_error);

if (isset($_GET['id_cliente']) && !empty($_GET['id_cliente'])) {
    $id_cliente = intval($_GET['id_cliente']);

    // Obtener direcciones relacionadas al cliente
    $query = "SELECT id_direccion, CONCAT(calle, ', ', ciudad, ', ', estado) AS direccion 
              FROM direcciones WHERE id_entidad = $id_cliente AND tipo_entidad = 'Cliente'";
    $result = $conn->query($query);

    $direcciones = [];
    while ($row = $result->fetch_assoc()) {
        $direcciones[] = $row;
    }

    // Devolver direcciones en formato JSON
    header('Content-Type: application/json');
    echo json_encode($direcciones);
} else {
    echo json_encode(['error' => 'ID de cliente no proporcionado']);
}
?>
