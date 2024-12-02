<?php
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'] ?? null;
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo_personal'];
    $cargo = $_POST['cargo'];
    $sucursal = $_POST['sucursal_asociada'];
    $salario = $_POST['salario'];

    $stmt = $conn->prepare("INSERT INTO empleados (nombre, apellido_paterno, apellido_materno, telefono, correo_personal, cargo, sucursal_asociada, salario) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssd", $nombre, $apellido_paterno, $apellido_materno, $telefono, $correo, $cargo, $sucursal, $salario);
    $stmt->execute();

    header("Location: empleados.php");
    exit;
}
