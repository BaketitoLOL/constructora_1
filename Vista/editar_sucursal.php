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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_sucursal'], $_POST['nombre'], $_POST['telefono'], $_POST['correo'], $_POST['pag_web'])) {
    $id_sucursal = intval($_POST['id_sucursal']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $telefono = $conn->real_escape_string($_POST['telefono']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $pag_web = $conn->real_escape_string($_POST['pag_web']);

    $query = "UPDATE sucursales 
              SET nombre = '$nombre', telefono = '$telefono', CorreoSucursal = '$correo', PagWebSucursal = '$pag_web'
              WHERE id_sucursal = $id_sucursal";

    if ($conn->query($query)) {
        header("Location: sucursales.php?success=edit");
        exit();
    } else {
        echo "<script>alert('Error al actualizar la sucursal: " . $conn->error . "');</script>";
    }
}
?>
