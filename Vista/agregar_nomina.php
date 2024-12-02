<?php
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_empleado = $_POST['id_empleado'];
    $semana = $_POST['semana'];
    $dias_trabajados = $_POST['dias_trabajados'];

    // Calcular sueldo semanal
    $query = $conn->prepare("SELECT salario FROM empleados WHERE id_empleado = ?");
    $query->bind_param("i", $id_empleado);
    $query->execute();
    $result = $query->get_result();
    $empleado = $result->fetch_assoc();

    if ($empleado) {
        $sueldo_diario = $empleado['salario'] / 30;
        $sueldo_semanal = $dias_trabajados * $sueldo_diario;

        // Insertar en la tabla nómina
        $stmt = $conn->prepare("INSERT INTO nomina (id_empleado, semana, dias_trabajados, sueldo_semanal) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $id_empleado, $semana, $dias_trabajados, $sueldo_semanal);
        $stmt->execute();

        header("Location: nomina.php");
        exit;
    }
}
?>
