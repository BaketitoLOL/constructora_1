<?php
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_sucursal = $_POST['id_sucursal'];
    $num_ext = $_POST['num_ext'];
    $num_int = $_POST['num_int'];
    $calle = $_POST['calle'];
    $ciudad = $_POST['ciudad'];
    $estado = $_POST['estado'];
    $codigo_postal = $_POST['codigo_postal'];

    $query = "INSERT INTO direcciones (num_ext, num_int, calle, ciudad, estado, codigo_postal, tipo_entidad, id_entidad) 
              VALUES ('$num_ext', '$num_int', '$calle', '$ciudad', '$estado', '$codigo_postal', 'Sucursal', '$id_sucursal')";

    if ($conn->query($query) === TRUE) {
        echo "Dirección guardada exitosamente.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
