<?php
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_sucursal = $_POST['id_sucursal'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['CorreoSucursal'];
    $paginaWeb = $_POST['PagWebSucursal'] ?? null;

    // Actualizar sucursal
    $stmt = $conn->prepare("UPDATE sucursales SET nombre = ?, telefono = ?, CorreoSucursal = ?, PagWebSucursal = ? WHERE id_sucursal = ?");
    $stmt->bind_param("ssssi", $nombre, $telefono, $correo, $paginaWeb, $id_sucursal);
    $stmt->execute();

    header("Location: sucursales.php");
    exit;
}
