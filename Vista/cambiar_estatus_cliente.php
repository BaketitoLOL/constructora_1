<?php
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idCliente = intval($_POST['id_cliente']);
    $nuevoEstatus = $_POST['estatus'];

    // Validar que el nuevo estatus sea válido
    if (!in_array($nuevoEstatus, ['Activo', 'Inactivo'])) {
        echo "Estatus inválido.";
        exit;
    }

    $query = "UPDATE clientes SET estatus = ? WHERE id_cliente = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $nuevoEstatus, $idCliente);

    if ($stmt->execute()) {
        echo "Estatus del cliente cambiado a {$nuevoEstatus}.";
    } else {
        echo "Error al cambiar el estatus del cliente.";
    }
} else {
    echo "Método no permitido.";
}
?>
