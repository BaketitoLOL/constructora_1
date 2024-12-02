<?php
include '../modelo/db_connection.php';


$query = "SELECT id_sucursal, nombre FROM sucursales WHERE id_direccion IS NOT NULL";
$result = $conn->query($query);

$sucursales = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sucursales[] = $row;
    }
}

echo json_encode(["success" => true, "sucursales" => $sucursales]);
exit;
