<?php
include '../modelo/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['Firma_administrador'], $_POST['id_obra'])) {
    $folio_obra = $_POST['id_obra'];
    $file = $_FILES['Firma_administrador'];

    // Validar el archivo
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed_types)) {
        echo "Formato de archivo no permitido. Usa JPG, PNG o GIF.";
        exit();
    }

    // Definir la ruta de almacenamiento
    $upload_dir = "../Firma_administrador/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $file_name = "Firma_." . pathinfo($file['name'], PATHINFO_EXTENSION);
    $file_path = $upload_dir . $file_name;

    // Mover el archivo cargado
    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        echo "Firma cargada exitosamente.";
    } else {
        echo "Error al cargar la firma.";
    }
}
?>