<?php
include '../modelo/db_connection.php';

$id = $_GET['id'] ?? null;

$query = "DELETE FROM servicios WHERE id_servicio = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    header('Location: servicios.php');
} else {
    echo "Error al eliminar el servicio: " . $stmt->error;
}
?>
