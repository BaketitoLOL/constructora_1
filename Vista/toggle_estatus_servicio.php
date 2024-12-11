<?php
include '../modelo/db_connection.php';

$id = $_GET['id'] ?? null;

$query = "UPDATE servicios SET estatus = IF(estatus = 'Activo', 'Inactivo', 'Activo') WHERE id_servicio = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    header('Location: servicios.php');
} else {
    echo "Error al cambiar el estatus del servicio: " . $stmt->error;
}
?>
