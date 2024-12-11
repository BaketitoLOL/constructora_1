<?php
include '../modelo/db_connection.php';

$id_direccion = intval($_GET['id_direccion'] ?? 0);

$query = "SELECT * FROM direcciones WHERE id_direccion = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_direccion);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['error' => 'Dirección no encontrada.']);
    exit;
}

echo json_encode($result->fetch_assoc());
