<?php
include '../modelo/db_connection.php';

if (isset($_GET['id'])) {
    $id_sucursal = $_GET['id'];

    // Eliminar sucursal
    $stmt = $conn->prepare("DELETE FROM sucursales WHERE id_sucursal = ?");
    $stmt->bind_param("i", $id_sucursal);
    $stmt->execute();

    header("Location: sucursales.php");
    exit;
}
