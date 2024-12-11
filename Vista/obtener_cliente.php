<?php
include '../modelo/db_connection.php';

$idCliente = intval($_GET['id']);

$query = "SELECT c.*, d.num_ext, d.num_int, d.calle, d.ciudad, d.estado, d.codigo_postal
          FROM clientes c
          LEFT JOIN direcciones d ON c.id_direccion = d.id_direccion
          WHERE c.id_cliente = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idCliente);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(['error' => 'Cliente no encontrado.']);
}
?>
