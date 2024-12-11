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

// Verificar si se proporcionó el ID de la dirección
if (isset($_GET['id'])) {
    $id_direccion = intval($_GET['id']);

    // Eliminar la dirección de la base de datos
    $query = "DELETE FROM direcciones WHERE id_direccion = $id_direccion";
    if ($conn->query($query)) {
        header("Location: gestionar_direcciones.php?sucursal_id={$_GET['sucursal_id']}&success=delete");
        exit();
    } else {
        echo "<script>alert('Error al eliminar la dirección: " . $conn->error . "');</script>";
    }
} else {
    echo "<script>alert('Accion Completada.');</script>";
}
?>
