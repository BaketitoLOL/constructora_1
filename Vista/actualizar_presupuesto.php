<?php
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_presupuesto = $_POST['id_presupuesto'];
    $fecha_elaboracion = $_POST['fecha_elaboracion'];
    $observaciones = $_POST['observaciones'];
    $total = $_POST['total'];

    // Validar los datos
    if (!$id_presupuesto || !$fecha_elaboracion || !$total) {
        die("Error: Datos incompletos.");
    }

    // Iniciar transacción
    $conn->begin_transaction();

    try {
        // Actualizar datos generales del presupuesto
        $stmt = $conn->prepare("
            UPDATE presupuestos
            SET fecha_elaboracion = ?, observaciones = ?, total = ?
            WHERE id_presupuesto = ? AND estatus = 'Pendiente'
        ");
        $stmt->bind_param("ssdi", $fecha_elaboracion, $observaciones, $total, $id_presupuesto);
        $stmt->execute();

        // Si no se actualizó ninguna fila, significa que el presupuesto ya fue enviado o no existe
        if ($stmt->affected_rows === 0) {
            throw new Exception("No se puede actualizar este presupuesto. Verifique el estatus.");
        }

        // Eliminar detalles antiguos
        $conn->query("DELETE FROM detalle_presupuesto WHERE id_presupuesto = $id_presupuesto");

        // Insertar nuevos detalles del presupuesto
        $id_servicio = $_POST['id_servicio'];
        $cantidad = $_POST['cantidad'];
        $subtotal = $_POST['subtotal'];

        $stmtDetalle = $conn->prepare("
            INSERT INTO detalle_presupuesto (id_presupuesto, id_servicio, cantidad, subtotal)
            VALUES (?, ?, ?, ?)
        ");

        foreach ($id_servicio as $key => $servicio) {
            $cant = $cantidad[$key];
            $sub = $subtotal[$key];
            $stmtDetalle->bind_param("iiid", $id_presupuesto, $servicio, $cant, $sub);
            $stmtDetalle->execute();
        }

        // Confirmar la transacción
        $conn->commit();

        // Redirigir a la lista de presupuestos
        header("Location: lista_presupuestos.php");
        exit();
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
        die("Error al actualizar el presupuesto: " . $e->getMessage());
    }
}
?>
