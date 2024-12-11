<?php
include '../modelo/db_connection.php';

$id = $_GET['id'] ?? null;

$query = "SELECT * FROM categorias WHERE id_categoria = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$categoria = $result->fetch_assoc();

if ($categoria) {
    echo json_encode($categoria);
} else {
    echo json_encode(['error' => 'Categoría no encontrada']);
}
?>
