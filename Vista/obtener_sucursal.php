<?php
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $idSucursal = intval($_GET['id']);

    $query = "SELECT id_sucursal, nombre, telefono, CorreoSucursal, PagWebSucursal FROM sucursales WHERE id_sucursal = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $idSucursal);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        echo json_encode(['error' => 'No se encontró la sucursal.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'Solicitud inválida.']);
}
?>
