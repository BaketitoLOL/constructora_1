<?php
include '../modelo/db_connection.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = $_POST['id_cliente'];
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'] ?? null;
    $telefono_personal = $_POST['telefono_personal'];
    $correo = $_POST['correo'];

    // Actualizar cliente
    $stmt = $conn->prepare("UPDATE clientes SET nombre = ?, apellido_paterno = ?, apellido_materno = ?, telefono_personal = ?, correo = ? WHERE id_cliente = ?");
    $stmt->bind_param("sssssi", $nombre, $apellido_paterno, $apellido_materno, $telefono_personal, $correo, $id_cliente);
    $stmt->execute();

    header("Location: clientes.php");
    exit;
}
