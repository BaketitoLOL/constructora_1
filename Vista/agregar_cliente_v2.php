<?php
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir datos del formulario
    $nombre = trim($_POST['nombre']);
    $apellido_paterno = trim($_POST['apellido_paterno']);
    $apellido_materno = trim($_POST['apellido_materno'] ?? null);
    $telefono = trim($_POST['telefono_personal']);
    $correo = trim($_POST['correo']);
    $num_ext = trim($_POST['num_ext']);
    $num_int = trim($_POST['num_int'] ?? null);
    $calle = trim($_POST['calle']);
    $ciudad = trim($_POST['ciudad']);
    $estado = trim($_POST['estado']);
    $codigo_postal = trim($_POST['codigo_postal']);

    // Validar campos obligatorios
    if (!$nombre || !$apellido_paterno || !$telefono || !$correo || !$num_ext || !$calle || !$ciudad || !$estado || !$codigo_postal) {
        echo "Error: Todos los campos obligatorios deben completarse.";
        exit;
    }

    // Iniciar transacción
    $conn->begin_transaction();

    try {
        // Insertar la dirección en la tabla `direcciones`
        $queryDireccion = "INSERT INTO direcciones (num_ext, num_int, calle, ciudad, estado, codigo_postal)
                           VALUES (?, ?, ?, ?, ?, ?)";
        $stmtDireccion = $conn->prepare($queryDireccion);
        $stmtDireccion->bind_param("ssssss", $num_ext, $num_int, $calle, $ciudad, $estado, $codigo_postal);
        $stmtDireccion->execute();

        // Obtener el ID de la dirección insertada
        $idDireccion = $stmtDireccion->insert_id;

        // Insertar el cliente en la tabla `clientes`
        $queryCliente = "INSERT INTO clientes (nombre, apellido_paterno, apellido_materno, telefono_personal, correo, id_direccion, estatus)
                         VALUES (?, ?, ?, ?, ?, ?, 'Activo')";
        $stmtCliente = $conn->prepare($queryCliente);
        $stmtCliente->bind_param("sssssi", $nombre, $apellido_paterno, $apellido_materno, $telefono, $correo, $idDireccion);
        $stmtCliente->execute();

        // Confirmar la transacción
        $conn->commit();
        echo "Cliente agregado exitosamente.";
        header("Location: clientes.php");
        exit;
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
        echo "Error al agregar el cliente: " . $e->getMessage();
    }
} else {
    echo "Método no permitido.";
}
?>
