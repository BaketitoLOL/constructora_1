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
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $telefono_personal = $_POST['telefono_personal'];
    $correo = $_POST['correo'];

    // Validar si el correo ya existe
    $query = "SELECT id_cliente FROM clientes WHERE correo = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "El correo ya está registrado. Por favor, usa otro.";
        header("Location: gestionar_clientes.php");
        exit;
    }

    // Insertar el cliente
    $query = "INSERT INTO clientes (nombre, apellido_paterno, telefono_personal, correo) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $nombre, $apellido_paterno, $telefono_personal, $correo);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Cliente agregado exitosamente.";
    } else {
        $_SESSION['error'] = "Ocurrió un error al agregar el cliente. Intenta nuevamente.";
    }

    header("Location: gestionar_clientes.php");
    exit;
}
?>
