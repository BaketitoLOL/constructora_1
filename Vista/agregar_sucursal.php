<?php
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['CorreoSucursal'];
    $paginaWeb = $_POST['PagWebSucursal'] ?? null;
    $calle = $_POST['calle'] ?? null;
    $ciudad = $_POST['ciudad'] ?? null;
    $estado = $_POST['estado'] ?? null;
    $codigo_postal = $_POST['codigo_postal'] ?? null;

    // Insertar dirección si está presente
    $id_direccion = null;
    if ($calle && $ciudad && $estado && $codigo_postal) {
        $stmt = $conn->prepare("INSERT INTO direcciones (calle, ciudad, estado, codigo_postal) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $calle, $ciudad, $estado, $codigo_postal);
        $stmt->execute();
        $id_direccion = $stmt->insert_id;
    }

    // Insertar sucursal
    $stmt = $conn->prepare("INSERT INTO sucursales (nombre, telefono, CorreoSucursal, PagWebSucursal, id_direccion) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $nombre, $telefono, $correo, $paginaWeb, $id_direccion);
    $stmt->execute();

    header("Location: sucursales.php");
    exit;
}
?>
