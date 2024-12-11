<?php
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['CorreoSucursal']);
    $paginaWeb = trim($_POST['PagWebSucursal']) ?: null; // Campo opcional

    // Validar campos obligatorios
    if (!$nombre || !$telefono || !$correo) {
        echo "Error: Todos los campos obligatorios deben completarse.";
        exit;
    }

    try {
        // Insertar sucursal con id_direccion en NULL inicialmente
        $querySucursal = "INSERT INTO sucursales (nombre, telefono, CorreoSucursal, PagWebSucursal, id_direccion) VALUES (?, ?, ?, ?, NULL)";
        $stmt = $conn->prepare($querySucursal);
        $stmt->bind_param("ssss", $nombre, $telefono, $correo, $paginaWeb);
        $stmt->execute();

        echo "Sucursal agregada exitosamente.";
        header("Location: sucursales.php"); // Redirigir a la lista de sucursales
        exit;
    } catch (Exception $e) {
        echo "Error al agregar la sucursal: " . $e->getMessage();
    }
} else {
    echo "Método no permitido.";
}
?>
 