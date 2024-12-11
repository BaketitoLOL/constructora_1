<?php
include '../modelo/db_connection.php';

$id = $_GET['id'] ?? null;

$query = "DELETE FROM categorias WHERE id_categoria = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    header('Location: categorias.php');
} else {
    echo "Error al eliminar la categoría: " . $stmt->error;
}
?>
