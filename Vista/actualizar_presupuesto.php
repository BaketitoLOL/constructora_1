<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_servicio = $_POST['id_servicio'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $imagen_actual = $_POST['imagen_actual'];

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen = basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], "../uploads/" . $imagen);
    } else {
        $imagen = $imagen_actual;
    }

    require 'conexion.php';
    $sql = "UPDATE servicios SET nombre = ?, descripcion = ?, imagen = ? WHERE id_servicio = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nombre, $descripcion, $imagen, $id_servicio);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
