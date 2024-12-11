<?php
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_empleado = intval($_POST['id_empleado']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellido_paterno = $conn->real_escape_string($_POST['apellido_paterno']);
    $apellido_materno = $conn->real_escape_string($_POST['apellido_materno']);
    $telefono = $conn->real_escape_string($_POST['telefono']);
    $correo_personal = $conn->real_escape_string($_POST['correo_personal']);
    $sucursal_asociada = intval($_POST['sucursal_asociada']);
    $salario = floatval($_POST['salario']);
    $hora_entrada = $conn->real_escape_string($_POST['hora_entrada']);
    $hora_salida = $conn->real_escape_string($_POST['hora_salida']);

    $query = "
        UPDATE empleados
        SET nombre = '$nombre', apellido_paterno = '$apellido_paterno', apellido_materno = '$apellido_materno', 
            telefono = '$telefono', correo_personal = '$correo_personal', sucursal_asociada = $sucursal_asociada, 
            salario = $salario, hora_entrada = '$hora_entrada', hora_salida = '$hora_salida'
        WHERE id_empleado = $id_empleado
    ";

    if ($conn->query($query)) {
        header('Location: empleados.php?success=1');
    } else {
        header('Location: empleados.php?error=1');
    }
}
?>
