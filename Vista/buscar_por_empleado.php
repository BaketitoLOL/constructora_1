<?php
include '../modelo/db_connection.php';

if (isset($_GET['id_empleado'])) {
    $id_empleado = intval($_GET['id_empleado']);

    // Obtener el sueldo semanal actual
    $queryActual = "
        SELECT dias_trabajados, sueldo_semanal
        FROM nomina
        WHERE id_empleado = ?
        ORDER BY semana DESC
        LIMIT 1
    ";
    $stmtActual = $conn->prepare($queryActual);
    $stmtActual->bind_param("i", $id_empleado);
    $stmtActual->execute();
    $resultadoActual = $stmtActual->get_result();

    $sueldoSemanalActual = $resultadoActual->fetch_assoc();

    // Obtener los últimos 4 pagos
    $queryUltimos = "
        SELECT semana, dias_trabajados, sueldo_semanal
        FROM nomina
        WHERE id_empleado = ?
        ORDER BY semana DESC
        LIMIT 4
    ";
    $stmtUltimos = $conn->prepare($queryUltimos);
    $stmtUltimos->bind_param("i", $id_empleado);
    $stmtUltimos->execute();
    $resultadoUltimos = $stmtUltimos->get_result();

    $pagos = [];
    while ($row = $resultadoUltimos->fetch_assoc()) {
        $pagos[] = $row;
    }

    echo json_encode([
        "sueldoSemanalActual" => $sueldoSemanalActual,
        "ultimosPagos" => $pagos
    ]);
    exit;
}

http_response_code(400);
echo json_encode(["error" => "Empleado no especificado."]);
?>
