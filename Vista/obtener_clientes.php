<?php
$conn = new mysqli('localhost', 'root', '', 'sistema_constructora');
if ($conn->connect_error) die('Error de conexión: ' . $conn->connect_error);

$query = "SELECT id_cliente, CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) AS nombre 
          FROM clientes WHERE estatus = 'Activo'";
$result = $conn->query($query);

$clientes = [];
while ($row = $result->fetch_assoc()) {
    $clientes[] = $row;
}

header('Content-Type: application/json');
echo json_encode($clientes);
?>
