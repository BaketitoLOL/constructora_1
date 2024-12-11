<?php
$conn = new mysqli('localhost', 'root', '', 'sistema_constructora');
if ($conn->connect_error) die('Error de conexión: ' . $conn->connect_error);

if (isset($_GET['id_presupuesto'])) {
    $id_presupuesto = intval($_GET['id_presupuesto']);

    // Eliminar detalles asociados
    $query_detalles = "DELETE FROM detalle_presupuesto WHERE id_presupuesto = $id_presupuesto";
    $conn->query($query_detalles);

    // Eliminar el presupuesto
    $query_presupuesto = "DELETE FROM presupuestos WHERE id_presupuesto = $id_presupuesto";
    if ($conn->query($query_presupuesto)) {
        header('Location: presupuesto.php?success=1');
    } else {
        echo 'Error al eliminar el presupuesto: ' . $conn->error;
    }
} else {
    echo 'ID de presupuesto no proporcionado.';
}
?>
