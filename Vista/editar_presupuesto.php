<?php
$conn = new mysqli('localhost', 'root', '', 'sistema_constructora');
if ($conn->connect_error) die('Error de conexión: ' . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_presupuesto = intval($_POST['id_presupuesto']);
    $id_cliente = intval($_POST['id_cliente']);
    $id_direccion = intval($_POST['id_direccion']);
    $observaciones = $conn->real_escape_string($_POST['observaciones']);
    $total = 0;

    // Actualizar presupuesto principal
    $query_update_presupuesto = "UPDATE presupuestos 
                                 SET id_cliente = $id_cliente, id_direccion = $id_direccion, observaciones = '$observaciones', total = 0
                                 WHERE id_presupuesto = $id_presupuesto";
    $conn->query($query_update_presupuesto);

    // Eliminar detalles existentes
    $conn->query("DELETE FROM detalle_presupuesto WHERE id_presupuesto = $id_presupuesto");

    // Insertar nuevos detalles
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
    $conn->query("UPDATE presupuestos SET total = $total WHERE id_presupuesto = $id_presupuesto");

    // Redirigir
    header('Location: presupuesto.php?success=1');
} else {
    echo 'Método no permitido.';
}
?>
