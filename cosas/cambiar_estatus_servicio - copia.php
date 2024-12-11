<?php
include '../modelo/db_connection.php';

if (isset($_POST['id_servicio']) && isset($_POST['nuevo_estatus'])) {
    $id_servicio = $_POST['id_servicio'];
    $nuevo_estatus = $_POST['nuevo_estatus'];

    $stmt = $conn->prepare("UPDATE servicios SET estatus = ? WHERE id_servicio = ?");
    $stmt->bind_param("si", $nuevo_estatus, $id_servicio);
    $stmt->execute();

    echo json_encode(["success" => true]);
    exit;
}
echo json_encode(["success" => false, "error" => "Datos incompletos."]);
