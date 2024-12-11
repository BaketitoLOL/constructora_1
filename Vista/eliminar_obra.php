<?php
include '../modelo/db_connection.php';

if (isset($_GET['id_obra'])) {
    $id_obra = $_GET['id_obra'];

    $query = $conn->prepare("DELETE FROM obras WHERE id_obra = ?");
    $query->bind_param("i", $id_obra);

    if ($query->execute()) {
        header("Location: obras.php?mensaje=obra_eliminada");
    } else {
        echo "Error: " . $query->error;
    }
}
?>
