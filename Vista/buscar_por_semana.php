<?php
include '../modelo/db_connection.php';

if (isset($_GET['semana'])) {
    $semana = intval($_GET['semana']);

    $query = "
        SELECT e.nombre, e.apellido_paterno, e.apellido_materno, n.dias_trabajados, n.sueldo_semanal
        FROM nomina n
        JOIN empleados e ON n.id_empleado = e.id_empleado
        WHERE n.semana = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $semana);
    $stmt->execute();
    $result = $stmt->get_result();

    $pagos = [];
    while ($row = $result->fetch_assoc()) {
        $pagos[] = $row;
    }

    echo json_encode($pagos);
    exit;
}

http_response_code(400);
echo json_encode(["error" => "Semana no especificada."]);
?>
