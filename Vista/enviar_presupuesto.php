<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_presupuesto = $_POST['id_presupuesto'];
    $correo = $_POST['correo'];

    // Consulta para obtener los detalles del presupuesto
    $query = "SELECT p.id_presupuesto, p.total, c.nombre AS cliente 
              FROM presupuestos p
              INNER JOIN clientes c ON p.id_cliente = c.id_cliente
              WHERE p.id_presupuesto = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_presupuesto);
    $stmt->execute();
    $result = $stmt->get_result();
    $presupuesto = $result->fetch_assoc();

    if ($presupuesto) {
        // Lógica para generar el PDF y enviar por correo
        $asunto = "Presupuesto #" . $presupuesto['id_presupuesto'];
        $mensaje = "Hola " . $presupuesto['cliente'] . ",\n\nAdjunto encontrarás tu presupuesto.";
        $archivo = "ruta_al_archivo_pdf"; // Generar PDF previamente.

        // Usar mail() o PHPMailer para enviar el correo
        // ...

        echo "Correo enviado exitosamente a $correo.";
    } else {
        echo "Presupuesto no encontrado.";
    }
}
?>
