<?php
include '../modelo/db_connection.php';
session_start();

// Verificar si el usuario es empleado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Empleado') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir datos del formulario
    $id_cliente = $_POST['id_cliente'];
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $telefono_personal = $_POST['telefono_personal'];
    $correo = $_POST['correo'];

    // Validar si el correo ya está en uso por otro cliente
    $query = "SELECT id_cliente FROM clientes WHERE correo = ? AND id_cliente != ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $correo, $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "El correo ya está en uso por otro cliente.";
        header("Location: gestionar_clientes.php");
        exit;
    }

    // Actualizar cliente
    $query = "UPDATE clientes SET nombre = ?, apellido_paterno = ?, telefono_personal = ?, correo = ? WHERE id_cliente = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $nombre, $apellido_paterno, $telefono_personal, $correo, $id_cliente);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Cliente actualizado exitosamente.";
    } else {
        $_SESSION['error'] = "Ocurrió un error al actualizar el cliente. Intenta nuevamente.";
    }

    header("Location: gestionar_clientes.php");
    exit;
}
?>
