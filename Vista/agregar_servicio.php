<?php
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $estatus = 'Activo';
    $imagen = null;

    // Manejar la imagen
    if (!empty($_FILES['imagen']['name'])) {
        $target_dir = "../uploads/";
        $imagen = basename($_FILES['imagen']['name']);
        $target_file = $target_dir . $imagen;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file);
    }

    $stmt = $conn->prepare("INSERT INTO servicios (nombre, descripcion, imagen, estatus) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $descripcion, $imagen, $estatus);
    $stmt->execute();

    header("Location: servicios.php");
    exit;
}
