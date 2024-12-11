<?php
$conn = new mysqli('localhost', 'root', '', 'sistema_constructora');
if ($conn->connect_error) die('Error de conexión: ' . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = intval($_POST['id_cliente']);
    $calle = $conn->real_escape_string($_POST['calle']);
    $ciudad = $conn->real_escape_string($_POST['ciudad']);
    $estado = $conn->real_escape_string($_POST['estado']);
    $codigo_postal = $conn->real_escape_string($_POST['codigo_postal']);

    $query = "INSERT INTO direccion_obra (calle, ciudad, estado, codigo_postal, id_cliente)
              VALUES ('$calle', '$ciudad', '$estado', '$codigo_postal', $id_cliente)";

    if ($conn->query($query)) {
        header('Location: gestionar_direcciones.php?id_cliente=' . $id_cliente . '&success=1');
    } else {
        echo 'Error al guardar la dirección: ' . $conn->error;
    }
} else {
    echo 'Método no permitido.';
}
?>
