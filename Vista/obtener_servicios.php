<?php
$conn = new mysqli('localhost', 'root', '', 'sistema_constructora');
if ($conn->connect_error) die('Error de conexión: ' . $conn->connect_error);

$query = "SELECT id_servicio, nombre, precio FROM servicios WHERE estatus = 'Activo'";
$result = $conn->query($query);

$servicios = [];
while ($row = $result->fetch_assoc()) {
    $servicios[] = $row;
}

header('Content-Type: application/json');
echo json_encode($servicios);
?>
