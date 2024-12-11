<?php
include '../modelo/db_connection.php';

$id = $_GET['id'] ?? null;

$query = "SELECT * FROM servicios WHERE id_servicio = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$servicio = $result->fetch_assoc();

if ($servicio) {
    echo json_encode($servicio);
} else {
    echo json_encode(['error' => 'Servicio no encontrado']);
}
?>
