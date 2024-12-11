<?php
$conn = new mysqli('localhost', 'root', '', 'sistema_constructora');
if ($conn->connect_error) die('Error de conexión: ' . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = intval($_POST['id_cliente']);
    $id_clave_secundaria = intval($_POST['id_clave_secundaria']);
    $fecha_inicio = $conn->real_escape_string($_POST['fecha_inicio']);
    $anticipo = floatval($_POST['anticipo']);
    $adeudo = floatval($_POST['adeudo']);
    $total = $anticipo + $adeudo;
    $observaciones = $conn->real_escape_string($_POST['observaciones']);
    $estatus = $conn->real_escape_string($_POST['estatus']);

    // Verificar si id_clave_secundaria existe en direccion_obra
    $query_verificar = "SELECT COUNT(*) AS total FROM direccion_obra WHERE clave_secundaria = $id_clave_secundaria";
    $result_verificar = $conn->query($query_verificar);
    $row = $result_verificar->fetch_assoc();

    if ($row['total'] > 0) {
        // Insertar obra
        $query_obra = "INSERT INTO obras (id_cliente, id_clave_secundaria, fecha_inicio, anticipo, adeudo, total, observaciones, estatus)
                       VALUES ($id_cliente, $id_clave_secundaria, '$fecha_inicio', $anticipo, $adeudo, $total, '$observaciones', '$estatus')";
        if ($conn->query($query_obra)) {
            header('Location: obras.php?success=1');
        } else {
            echo 'Error al guardar la obra: ' . $conn->error;
        }
    } else {
        echo 'Error: La dirección seleccionada no es válida.';
    }
} else {
    echo 'Método no permitido.';
}
?>
