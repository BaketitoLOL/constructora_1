<?php
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idCliente = intval($_GET['id'] ?? 0);

    if (!$idCliente) {
        echo "Error: ID de cliente no válido.";
        exit;
    }

    try {
        $query = "UPDATE clientes SET estatus = 'Inactivo' WHERE id_cliente = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idCliente);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            echo "No se pudo inactivar el cliente o ya está inactivo.";
        } else {
            echo "Cliente inactivado exitosamente.";
        }
    } catch (Exception $e) {
        echo "Error al inactivar el cliente: " . $e->getMessage();
    }
} else {
    echo "Método no permitido.";
}
?>
