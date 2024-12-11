<?php
$conn = new mysqli('localhost', 'root', '', 'sistema_constructora');
if ($conn->connect_error) die('Error de conexión: ' . $conn->connect_error);

if (isset($_GET['id_presupuesto']) && !empty($_GET['id_presupuesto'])) {
    $id_presupuesto = intval($_GET['id_presupuesto']);

    // Obtener datos del presupuesto principal
    $query_presupuesto = "SELECT id_cliente, id_direccion, observaciones FROM presupuestos WHERE id_presupuesto = $id_presupuesto";
    $result_presupuesto = $conn->query($query_presupuesto);
    $presupuesto = $result_presupuesto->fetch_assoc();

    // Obtener detalles del presupuesto
    $query_detalles = "SELECT dp.id_servicio, s.nombre AS nombre_servicio, dp.cantidad, dp.subtotal, s.precio AS precio_unitario
                       FROM detalle_presupuesto dp
                       INNER JOIN servicios s ON dp.id_servicio = s.id_servicio
                       WHERE dp.id_presupuesto = $id_presupuesto";
    $result_detalles = $conn->query($query_detalles);

    $detalles = [];
    while ($row = $result_detalles->fetch_assoc()) {
        $detalles[] = $row;
    }

    // Combinar datos
    $response = [
        'presupuesto' => $presupuesto,
        'detalles' => $detalles
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    echo json_encode(['error' => 'ID de presupuesto no proporcionado.']);
}
?>
