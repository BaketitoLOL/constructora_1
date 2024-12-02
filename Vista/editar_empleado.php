<?php
include '../modelo/db_connection.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_empleado = $_POST['id_empleado'];
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'] ?? null;
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo_personal'];
    $cargo = $_POST['cargo'];
    $sucursal = $_POST['sucursal_asociada'];
    $salario = $_POST['salario'];

    $stmt = $conn->prepare("UPDATE empleados SET nombre = ?, apellido_paterno = ?, apellido_materno = ?, telefono = ?, correo_personal = ?, cargo = ?, sucursal_asociada = ?, salario = ? WHERE id_empleado = ?");
    $stmt->bind_param("ssssssdsi", $nombre, $apellido_paterno, $apellido_materno, $telefono, $correo, $cargo, $sucursal, $salario, $id_empleado);
    $stmt->execute();

    header("Location: empleados.php");
    exit;
}
