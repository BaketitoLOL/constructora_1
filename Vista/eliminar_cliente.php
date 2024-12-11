<?php
// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'sistema_constructora');
if ($conn->connect_error) die('Error de conexión: ' . $conn->connect_error);

if (isset($_GET['id_cliente']) && !empty($_GET['id_cliente'])) {
    $id_cliente = intval($_GET['id_cliente']);

    // Eliminar direcciones relacionadas en direccion_obra
    $query_direccion = "DELETE FROM direccion_obra WHERE id_cliente = $id_cliente";
    if ($conn->query($query_direccion)) {
        // Eliminar cliente
        $query_cliente = "DELETE FROM clientes WHERE id_cliente = $id_cliente";
        if ($conn->query($query_cliente)) {
            header('Location: clientes.php?success=1');
        } else {
            echo 'Error al eliminar el cliente: ' . $conn->error;
        }
    } else {
        echo 'Error al eliminar las direcciones relacionadas: ' . $conn->error;
    }
} else {
    echo 'ID de cliente no proporcionado.';
}
?>
