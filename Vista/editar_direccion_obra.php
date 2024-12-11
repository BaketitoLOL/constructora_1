<?php
$conn = new mysqli('localhost', 'root', '', 'sistema_constructora');
if ($conn->connect_error) die('Error de conexión: ' . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_direccion = intval($_POST['id_direccion']);
    $calle = $conn->real_escape_string($_POST['calle']);
    $ciudad = $conn->real_escape_string($_POST['ciudad']);
    $estado = $conn->real_escape_string($_POST['estado']);
    $codigo_postal = $conn->real_escape_string($_POST['codigo_postal']);

    $query = "UPDATE direccion_obra 
              SET calle = '$calle', ciudad = '$ciudad', estado = '$estado', codigo_postal = '$codigo_postal'
              WHERE id_direccion = $id_direccion";

    if ($conn->query($query)) {
        header('Location: gestionar_direcciones_obra.php?id_cliente=' . $_POST['id_cliente'] . '&success=1');
    } else {
        echo 'Error al actualizar la dirección: ' . $conn->error;
    }
} else {
    echo 'Método no permitido.';
}
?>
