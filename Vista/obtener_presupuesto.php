<?php
include '../modelo/db_connection.php';

if (isset($_GET['id'])) {
    $idPresupuesto = $_GET['id'];

    $query = "
        SELECT p.*, dp.id_servicio, dp.cantidad, dp.subtotal
        FROM presupuestos p
        LEFT JOIN detalle_presupuesto dp ON p.id_presupuesto = dp.id_presupuesto
        WHERE p.id_presupuesto = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $idPresupuesto);
    $stmt->execute();
    $result = $stmt->get_result();

    $datos = [];
    while ($row = $result->fetch_assoc()) {
        $datos[] = $row;
    }

    echo json_encode($datos);
}
?>
