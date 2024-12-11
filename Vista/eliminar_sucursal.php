<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$database = "sistema_constructora";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id_sucursal = intval($_GET['id']);

    $query = "DELETE FROM sucursales WHERE id_sucursal = $id_sucursal";

    if ($conn->query($query)) {
        header("Location: sucursales.php?success=delete");
        exit();
    } else {
        echo "<script>alert('Error al eliminar la sucursal: " . $conn->error . "');</script>";
    }
}
?>
