<?php
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = $_POST['id_cliente'];
    $id_sucursal = $_POST['id_sucursal'];
    $id_direccion = $_POST['id_direccion'];
    $fecha_elaboracion = $_POST['fecha_elaboracion'];
    $total_obra = $_POST['total_obra'];
    $observaciones = $_POST['observaciones'];

    // Generar folio automáticamente
    $queryFolio = "SELECT MAX(id_presupuesto) AS max_folio FROM presupuestos";
    $resultFolio = $conn->query($queryFolio);
    $folio = $resultFolio->fetch_assoc()['max_folio'] + 1;

    $query = "INSERT INTO presupuestos 
              (id_presupuesto, id_cliente, id_sucursal, id_direccion, fecha_elaboracion, total, observaciones, estatus) 
              VALUES (?, ?, ?, ?, ?, ?, ?, 'Pendiente')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiissds", $folio, $id_cliente, $id_sucursal, $id_direccion, $fecha_elaboracion, $total_obra, $observaciones);

    if ($stmt->execute()) {
        echo "Presupuesto registrado exitosamente.";
    } else {
        echo "Error al registrar el presupuesto: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
