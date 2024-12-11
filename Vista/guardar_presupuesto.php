<?php
// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'sistema_constructora');
if ($conn->connect_error) die('Error de conexión: ' . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Datos del presupuesto
    $id_cliente = intval($_POST['id_cliente']);
    $id_direccion = intval($_POST['id_direccion']);
    $observaciones = $conn->real_escape_string($_POST['observaciones'] ?? '');
    $fecha = date('Y-m-d');

    // Insertar presupuesto
    $query_presupuesto = "INSERT INTO presupuestos (id_cliente, id_direccion, fecha_elaboracion, total, observaciones)
                          VALUES ($id_cliente, $id_direccion, '$fecha', 0, '$observaciones')";
    if ($conn->query($query_presupuesto)) {
        $id_presupuesto = $conn->insert_id;

        // Insertar detalles del presupuesto
        $total = 0;
        foreach ($_POST['id_servicios'] as $index => $id_servicio) {
            $id_servicio = intval($id_servicio);
            $cantidad = intval($_POST['cantidades'][$index]);
            $precio = floatval($_POST['precios'][$index]);
            $subtotal = $cantidad * $precio;
            $total += $subtotal;

            $query_detalle = "INSERT INTO detalle_presupuesto (id_presupuesto, id_servicio, cantidad, subtotal)
                              VALUES ($id_presupuesto, $id_servicio, $cantidad, $subtotal)";
            $conn->query($query_detalle);
        }

        // Actualizar el total del presupuesto
        $query_update_total = "UPDATE presupuestos SET total = $total WHERE id_presupuesto = $id_presupuesto";
        $conn->query($query_update_total);

        // Redirigir con éxito
        header('Location: presupuesto.php?success=1');
    } else {
        echo 'Error al guardar el presupuesto: ' . $conn->error;
    }
} else {
    echo 'Método no permitido.';
}
?>
