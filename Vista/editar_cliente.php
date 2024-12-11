<?php
// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'sistema_constructora');
if ($conn->connect_error) die('Error de conexión: ' . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir datos del cliente
    $id_cliente = intval($_POST['id_cliente']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellido_paterno = $conn->real_escape_string($_POST['apellido_paterno']);
    $apellido_materno = isset($_POST['apellido_materno']) ? $conn->real_escape_string($_POST['apellido_materno']) : null;
    $telefono_personal = $conn->real_escape_string($_POST['telefono_personal']);
    $correo = $conn->real_escape_string($_POST['correo']);

    // Actualizar cliente
    $query = "UPDATE clientes 
              SET nombre = '$nombre', 
                  apellido_paterno = '$apellido_paterno', 
                  apellido_materno = '$apellido_materno', 
                  telefono_personal = '$telefono_personal', 
                  correo = '$correo'
              WHERE id_cliente = $id_cliente";

    if ($conn->query($query)) {
        header('Location: clientes.php?success=1');
    } else {
        echo 'Error al actualizar el cliente: ' . $conn->error;
    }
} else {
    echo 'Método no permitido.';
}
?>
