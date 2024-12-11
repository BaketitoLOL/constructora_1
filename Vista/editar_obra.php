<?php
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_obra = $_POST['id_obra'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $anticipo = $_POST['anticipo'];
    $adeudo = $_POST['adeudo'];
    $observaciones = $_POST['observaciones'];
    $estatus = $_POST['estatus'];

    $total = $anticipo + $adeudo;

    $query = $conn->prepare("UPDATE obras SET fecha_inicio = ?, anticipo = ?, adeudo = ?, total = ?, observaciones = ?, estatus = ? WHERE id_obra = ?");
    $query->bind_param("sdddssi", $fecha_inicio, $anticipo, $adeudo, $total, $observaciones, $estatus, $id_obra);
    if ($query->execute()) {
        header("Location: obras.php?mensaje=obra_actualizada");
    } else {
        echo "Error: " . $query->error;
    }
}
?>
