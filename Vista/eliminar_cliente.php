<?php
include '../modelo/db_connection.php';


if (isset($_GET['id'])) {
    $id_cliente = $_GET['id'];

    // Cambiar estatus a "Inactivo"
    $stmt = $conn->prepare("UPDATE clientes SET estatus = 'Inactivo' WHERE id_cliente = ?");
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();

    header("Location: clientes.php");
    exit;
}
