<?php
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $idSucursal = intval($_GET['id']);
    $response = ['tieneEmpleados' => false, 'tieneDireccion' => false];

    // Verificar empleados asociados
    $queryEmpleados = "SELECT COUNT(*) as total FROM empleados WHERE sucursal_asociada = ?";
    $stmtEmpleados = $conn->prepare($queryEmpleados);
    $stmtEmpleados->bind_param("i", $idSucursal);
    $stmtEmpleados->execute();
    $resultEmpleados = $stmtEmpleados->get_result();
    if ($resultEmpleados->fetch_assoc()['total'] > 0) {
        $response['tieneEmpleados'] = true;
    }

    // Verificar dirección asociada
    $queryDireccion = "SELECT COUNT(*) as total FROM direcciones WHERE tipo_entidad = 'Sucursal' AND id_entidad = ?";
    $stmtDireccion = $conn->prepare($queryDireccion);
    $stmtDireccion->bind_param("i", $idSucursal);
    $stmtDireccion->execute();
    $resultDireccion = $stmtDireccion->get_result();
    if ($resultDireccion->fetch_assoc()['total'] > 0) {
        $response['tieneDireccion'] = true;
    }

    echo json_encode($response);
} else {
    echo json_encode(['error' => 'Solicitud inválida.']);
}
?>
