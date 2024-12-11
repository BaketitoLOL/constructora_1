
<?php
include '../modelo/db_connection.php';

if (isset($_GET['id'])) {
    $id_empleado = $_GET['id'];

    // Cambiar estatus a "Inactivo" en lugar de eliminar físicamente
    $stmt = $conn->prepare("UPDATE empleados SET estatus = 'Inactivo' WHERE id_empleado = ?");
    $stmt->bind_param("i", $id_empleado);
    $stmt->execute();

    header("Location: empleados.php");
    exit;
}
