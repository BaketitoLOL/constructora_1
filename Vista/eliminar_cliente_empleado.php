<?php
include '../modelo/db_connection.php';
session_start();

// Verificar si el usuario es empleado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Empleado') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id_cliente = $_GET['id'];

    // Verificar si el cliente existe
    $query = "SELECT id_cliente FROM clientes WHERE id_cliente = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "El cliente no existe.";
        header("Location: gestionar_clientes.php");
        exit;
    }

    // Eliminar cliente
    $query = "DELETE FROM clientes WHERE id_cliente = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_cliente);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Cliente eliminado exitosamente.";
    } else {
        $_SESSION['error'] = "Ocurrió un error al eliminar el cliente. Intenta nuevamente.";
    }

    header("Location: gestionar_clientes.php");
    exit;
} else {
    $_SESSION['error'] = "Solicitud inválida.";
    header("Location: gestionar_clientes.php");
    exit;
}
?>
