<?php
// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'sistema_constructora');
if ($conn->connect_error) die('Error de conexión: ' . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir datos del cliente
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellido_paterno = $conn->real_escape_string($_POST['apellido_paterno']);
    $apellido_materno = isset($_POST['apellido_materno']) ? $conn->real_escape_string($_POST['apellido_materno']) : null;
    $telefono_personal = $conn->real_escape_string($_POST['telefono_personal']);
    $correo = $conn->real_escape_string($_POST['correo']);

    // Insertar cliente en la tabla clientes
    $query_cliente = "INSERT INTO clientes (nombre, apellido_paterno, apellido_materno, telefono_personal, correo) 
                      VALUES ('$nombre', '$apellido_paterno', '$apellido_materno', '$telefono_personal', '$correo')";

    if ($conn->query($query_cliente)) {
        $id_cliente = $conn->insert_id;

        // Redirigir al listado de clientes
        header('Location: clientes.php?success=1');
    } else {
        echo 'Error al guardar el cliente: ' . $conn->error;
    }
} else {
    echo 'Método no permitido.';
}
?>
