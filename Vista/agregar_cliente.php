<?php
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'] ?? null;
    $telefono_personal = $_POST['telefono_personal'];
    $correo = $_POST['correo'];
    $calle = $_POST['calle'] ?? null;
    $ciudad = $_POST['ciudad'] ?? null;
    $estado = $_POST['estado'] ?? null;
    $codigo_postal = $_POST['codigo_postal'] ?? null;

    // Insertar dirección si está presente
    $id_direccion = null;
    if ($calle && $ciudad && $estado && $codigo_postal) {
        $stmt = $conn->prepare("INSERT INTO direcciones (calle, ciudad, estado, codigo_postal) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $calle, $ciudad, $estado, $codigo_postal);
        $stmt->execute();
        $id_direccion = $stmt->insert_id;
    }

    // Insertar cliente
    $stmt = $conn->prepare("INSERT INTO clientes (nombre, apellido_paterno, apellido_materno, telefono_personal, correo, id_direccion) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $nombre, $apellido_paterno, $apellido_materno, $telefono_personal, $correo, $id_direccion);
    $stmt->execute();

    header("Location: clientes.php");
    exit;
}
