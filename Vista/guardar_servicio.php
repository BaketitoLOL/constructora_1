<?php
include '../modelo/db_connection.php';

$id_servicio = $_POST['id_servicio'] ?? null;
$nombre = $_POST['nombre'] ?? '';
$id_categoria = $_POST['id_categoria'] ?? 0;
$precio = $_POST['precio'] ?? 0.0;
$imagen = $_FILES['imagen'] ?? null;

// Manejo de imagen
$uploadDir = '../uploads/';
$imagenRuta = null;

if ($imagen && $imagen['error'] === UPLOAD_ERR_OK) {
    $extension = pathinfo($imagen['name'], PATHINFO_EXTENSION);
    $nombreArchivo = uniqid('servicio_', true) . '.' . $extension;
    $imagenRuta = $uploadDir . $nombreArchivo;

    if (!move_uploaded_file($imagen['tmp_name'], $imagenRuta)) {
        die("Error al subir la imagen.");
    }
}

if ($id_servicio) {
    // Actualizar servicio existente
    $query = "UPDATE servicios SET nombre = ?, id_categoria = ?, precio = ?, imagen = IFNULL(?, imagen) WHERE id_servicio = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sidsi', $nombre, $id_categoria, $precio, $imagenRuta, $id_servicio);
} else {
    // Agregar nuevo servicio
    $query = "INSERT INTO servicios (nombre, id_categoria, precio, imagen) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sids', $nombre, $id_categoria, $precio, $imagenRuta);
}

if ($stmt->execute()) {
    header('Location: servicios.php');
} else {
    echo "Error al guardar el servicio: " . $stmt->error;
}
?>
