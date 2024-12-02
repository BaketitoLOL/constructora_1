<?php
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_servicio = $_POST['id_servicio'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    // Manejar la imagen
    $imagen = $_POST['imagen_actual'];
    if (!empty($_FILES['imagen']['name'])) {
        $target_dir = "uploads/";
        $imagen = basename($_FILES['imagen']['name']);
        $target_file = $target_dir . $imagen;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file);
    }

    $stmt = $conn->prepare("UPDATE servicios SET nombre = ?, descripcion = ?, imagen = ? WHERE id_servicio = ?");
    $stmt->bind_param("sssi", $nombre, $descripcion, $imagen, $id_servicio);
    $stmt->execute();

    header("Location: servicios.php");
    exit;
}
