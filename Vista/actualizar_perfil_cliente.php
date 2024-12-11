<?php
session_start();
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = $_POST['id_cliente'];
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $telefono_personal = $_POST['telefono_personal'];
    $correo = $_POST['correo'];

    // Actualizar información del cliente
    $query = $conn->prepare("
        UPDATE clientes 
        SET nombre = ?, apellido_paterno = ?, apellido_materno = ?, telefono_personal = ?, correo = ? 
        WHERE id_cliente = ?
    ");
    $query->bind_param("sssssi", $nombre, $apellido_paterno, $apellido_materno, $telefono_personal, $correo, $id_cliente);

    if ($query->execute()) {
        $_SESSION['mensaje'] = "Perfil actualizado exitosamente.";
    } else {
        $_SESSION['mensaje'] = "Error al actualizar el perfil.";
    }

    header("Location: perfil_cliente.php");
    exit;
}
?>
