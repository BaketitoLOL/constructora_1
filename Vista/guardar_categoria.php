<?php
include '../modelo/db_connection.php';

$id_categoria = $_POST['id_categoria'] ?? null;
$nombre_categoria = $_POST['nombre_categoria'] ?? '';
$altura_pies = $_POST['altura_pies'] ?? 0;

if ($id_categoria) {
    $query = "UPDATE categorias SET nombre_categoria = ?, altura_pies = ? WHERE id_categoria = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sii', $nombre_categoria, $altura_pies, $id_categoria);
} else {
    $query = "INSERT INTO categorias (nombre_categoria, altura_pies) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $nombre_categoria, $altura_pies);
}

if ($stmt->execute()) {
    header('Location: categorias.php');
} else {
    echo "Error al guardar la categoría: " . $stmt->error;
}
?>
