<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root"; // Cambia esto si tienes otro usuario
$password = ""; // Cambia esto si tienes una contraseña
$database = "sistema_constructora";

$conn = new mysqli($host, $user, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si los datos se enviaron correctamente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_direccion'], $_POST['num_ext'], $_POST['calle'], $_POST['ciudad'], $_POST['estado'], $_POST['codigo_postal'])) {
    $id_direccion = intval($_POST['id_direccion']);
    $num_ext = $conn->real_escape_string($_POST['num_ext']);
    $calle = $conn->real_escape_string($_POST['calle']);
    $ciudad = $conn->real_escape_string($_POST['ciudad']);
    $estado = $conn->real_escape_string($_POST['estado']);
    $codigo_postal = $conn->real_escape_string($_POST['codigo_postal']);

    // Actualizar la dirección en la base de datos
    $query = "UPDATE direcciones 
              SET num_ext = '$num_ext', calle = '$calle', ciudad = '$ciudad', estado = '$estado', codigo_postal = '$codigo_postal'
              WHERE id_direccion = $id_direccion";
    if ($conn->query($query)) {
        header("Location: gestionar_direcciones.php?sucursal_id={$_POST['sucursal_id']}&success=edit");
        exit();
    } else {
        echo "<script>alert('Error al actualizar la dirección: " . $conn->error . "');</script>";
    }
} else {
    echo "<script>alert('Datos incompletos para actualizar la dirección.');</script>";
}
?>
