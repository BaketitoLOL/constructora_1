<?php
session_start();
include '../modelo/db_connection.php';

// Verificar si el cliente está logueado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Cliente') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id_presupuesto'])) {
    die("Presupuesto no especificado.");
}

$id_presupuesto = $_GET['id_presupuesto'];
$id_cliente = $_SESSION['id_usuario']; // Usamos el ID del cliente desde la sesión

// Depuración
echo "ID Cliente (Sesión): " . $id_cliente . "<br>";
echo "ID Presupuesto: " . $id_presupuesto . "<br>";

// Obtener el presupuesto
$query = "SELECT p.id_presupuesto, p.fecha_elaboracion, p.total, p.estatus, p.observaciones
          FROM presupuestos p
          WHERE p.id_presupuesto = ? AND p.id_cliente = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $id_presupuesto, $id_cliente);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Presupuesto no encontrado o no tienes permisos.");
}

$presupuesto = $result->fetch_assoc();

// Obtener los detalles del presupuesto
$query_detalle = "SELECT s.nombre AS servicio, dp.cantidad, dp.subtotal
                  FROM detalle_presupuesto dp
                  INNER JOIN servicios s ON dp.id_servicio = s.id_servicio
                  WHERE dp.id_presupuesto = ?";
$stmt_detalle = $conn->prepare($query_detalle);
$stmt_detalle->bind_param("i", $id_presupuesto);
$stmt_detalle->execute();
$result_detalle = $stmt_detalle->get_result();
?>
    