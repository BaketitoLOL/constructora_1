<?php
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_direccion = $_POST['id_direccion'] ?? null;
    $num_ext = $_POST['num_ext'];
    $num_int = $_POST['num_int'];
    $calle = $_POST['calle'];
    $ciudad = $_POST['ciudad'];
    $estado = $_POST['estado'];
    $codigo_postal = $_POST['codigo_postal'];

    if ($id_direccion) {
        $query = "
            UPDATE direcciones 
            SET num_ext = ?, num_int = ?, calle = ?, ciudad = ?, estado = ?, codigo_postal = ?
            WHERE id_direccion = ?
        ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssi", $num_ext, $num_int, $calle, $ciudad, $estado, $codigo_postal, $id_direccion);
    } else {
        $query = "
            INSERT INTO direcciones (num_ext, num_int, calle, ciudad, estado, codigo_postal, tipo_entidad, id_entidad) 
            VALUES (?, ?, ?, ?, ?, ?, 'Sucursal', ?)
        ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssi", $num_ext, $num_int, $calle, $ciudad, $estado, $codigo_postal, $id_sucursal);
    }

    if ($stmt->execute()) {
        header('Location: sucursales.php');
    } else {
        echo "Error al guardar la dirección.";
    }

    $stmt->close();
}
?>
