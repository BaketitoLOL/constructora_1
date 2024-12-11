<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root"; // Cambia esto si tu base de datos tiene otro usuario
$password = ""; // Cambia esto si tu base de datos tiene una contraseña
$database = "sistema_constructora";

$conn = new mysqli($host, $user, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Manejar solicitudes POST para agregar sucursales
// Manejar solicitudes POST para agregar sucursales
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'], $_POST['telefono'], $_POST['correo'], $_POST['pag_web'])) {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $telefono = $conn->real_escape_string($_POST['telefono']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $pag_web = $conn->real_escape_string($_POST['pag_web']);

    $query = "INSERT INTO sucursales (nombre, telefono, CorreoSucursal, PagWebSucursal) 
              VALUES ('$nombre', '$telefono', '$correo', '$pag_web')";
    if ($conn->query($query)) {
        // Redirigir después de insertar los datos
        header("Location: sucursales.php?success=true");
        exit(); // Asegúrate de detener la ejecución
    } else {
        echo "<script>alert('Error al agregar la sucursal: " . $conn->error . "');</script>";
    }
}

// Mostrar mensaje de éxito si existe
if (isset($_GET['success']) && $_GET['success'] === 'true') {
    echo "<script>alert('Sucursal agregada exitosamente');</script>";
}


// Manejar solicitudes POST para agregar dirección
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sucursal_id'], $_POST['num_ext'], $_POST['calle'], $_POST['ciudad'], $_POST['estado'], $_POST['codigo_postal'])) {
    $sucursal_id = intval($_POST['sucursal_id']);
    $num_ext = $conn->real_escape_string($_POST['num_ext']);
    $num_int = $conn->real_escape_string($_POST['num_int']);
    $calle = $conn->real_escape_string($_POST['calle']);
    $ciudad = $conn->real_escape_string($_POST['ciudad']);
    $estado = $conn->real_escape_string($_POST['estado']);
    $codigo_postal = $conn->real_escape_string($_POST['codigo_postal']);

    // Validar si ya existe una dirección para esta sucursal
    $checkQuery = "SELECT COUNT(*) as count FROM direcciones WHERE tipo_entidad = 'Sucursal' AND id_entidad = $sucursal_id";
    $result = $conn->query($checkQuery);
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        echo "<script>alert('Esta sucursal ya tiene una dirección asociada.');</script>";
    } else {
        $query = "INSERT INTO direcciones (num_ext, num_int, calle, ciudad, estado, codigo_postal, tipo_entidad, id_entidad)
                  VALUES ('$num_ext', '$num_int', '$calle', '$ciudad', '$estado', '$codigo_postal', 'Sucursal', $sucursal_id)";
        if ($conn->query($query)) {
            echo "<script>alert('Dirección agregada exitosamente');</script>";
        } else {
            echo "<script>alert('Error al agregar la dirección: " . $conn->error . "');</script>";
        }
    }
}


// Consultar sucursales
$sucursales = $conn->query("SELECT id_sucursal, nombre, telefono, CorreoSucursal, PagWebSucursal FROM sucursales");
?>
<!-- Modal: Editar Sucursal -->
<div class="modal fade" id="editSucursalModal" tabindex="-1" aria-labelledby="editSucursalModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSucursalModalLabel">Editar Sucursal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="editar_sucursal.php">
                <input type="hidden" id="edit_id_sucursal" name="id_sucursal">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="edit_telefono" name="telefono" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_correo" class="form-label">Correo</label>
                        <input type="email" class="form-control" id="edit_correo" name="correo" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_pag_web" class="form-label">Página Web</label>
                        <input type="url" class="form-control" id="edit_pag_web" name="pag_web" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Sucursales</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <?php
    include 'navbar.php';
    ?>
    <div class="container">
        <h1 class="text-center mb-4">Gestión de Sucursales</h1>

        <!-- Botón para agregar sucursal -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addSucursalModal">
            <i class="fas fa-plus"></i> Agregar Sucursal
        </button>

        <!-- Tabla de sucursales -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Página Web</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $sucursales->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id_sucursal'] ?></td>
                        <td><?= $row['nombre'] ?></td>
                        <td><?= $row['telefono'] ?></td>
                        <td><a href="<?= $row['PagWebSucursal'] ?>" target="_blank"><?= $row['PagWebSucursal'] ?></a></td>
                        <td><?= $row['CorreoSucursal'] ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editSucursalModal" onclick="setEditSucursalData(
                                            '<?= $row['id_sucursal'] ?>',
                                            '<?= $row['nombre'] ?>',
                                         '<?= $row['telefono'] ?>',
        '<?= $row['CorreoSucursal'] ?>',
        '<?= $row['PagWebSucursal'] ?>'
    )">
                                <i class="fas fa-edit"></i> 
                            </button>


                            <?php
                            // Consultar si la sucursal tiene una dirección asociada
                            $direccionQuery = "SELECT COUNT(*) as count FROM direcciones WHERE tipo_entidad = 'Sucursal' AND id_entidad = " . $row['id_sucursal'];
                            $direccionResult = $conn->query($direccionQuery);
                            $direccionData = $direccionResult->fetch_assoc();
                            // Mostrar botón para gestionar direcciones si la sucursal tiene una dirección asociada, o agregar una nueva si no
                        
                            if ($direccionData['count'] > 0): ?>
                                <button class="btn btn-success btn-sm"
                                    onclick="window.location.href='gestionar_direcciones.php?sucursal_id=<?= $row['id_sucursal'] ?>'">
                                    <i class="fas fa-cog"></i>
                                </button>
                            <?php else: ?>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#addDireccionModal"
                                    onclick="setSucursalId(<?= $row['id_sucursal'] ?>)">
                                    <i class="fas fa-map-marker-alt"></i>
                                </button>
                            <?php endif; ?>
                            <button class="btn btn-danger btn-sm" onclick="eliminarSucursal(<?= $row['id_sucursal'] ?>)">
                                <i class="fas fa-trash"></i> 
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <!-- Modal: Editar Sucursal -->
    <div class="modal fade" id="editSucursalModal" tabindex="-1" aria-labelledby="editSucursalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSucursalModalLabel">Editar Sucursal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="editar_sucursal.php">
                    <input type="hidden" id="edit_id_sucursal" name="id_sucursal">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="edit_telefono" name="telefono" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_correo" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="edit_correo" name="correo" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_pag_web" class="form-label">Página Web</label>
                            <input type="url" class="form-control" id="edit_pag_web" name="pag_web" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Agregar Dirección -->
    <div class="modal fade" id="addDireccionModal" tabindex="-1" aria-labelledby="addDireccionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDireccionModalLabel">Agregar Dirección</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="">
                    <input type="hidden" id="sucursal_id" name="sucursal_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="num_ext" class="form-label">Número Exterior</label>
                            <input type="text" class="form-control" id="num_ext" name="num_ext" required>
                        </div>
                        <div class="mb-3">
                            <label for="num_int" class="form-label">Número Interior (Opcional)</label>
                            <input type="text" class="form-control" id="num_int" name="num_int">
                        </div>
                        <div class="mb-3">
                            <label for="calle" class="form-label">Calle</label>
                            <input type="text" class="form-control" id="calle" name="calle" required>
                        </div>
                        <div class="mb-3">
                            <label for="ciudad" class="form-label">Ciudad</label>
                            <input type="text" class="form-control" id="ciudad" name="ciudad" required>
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <input type="text" class="form-control" id="estado" name="estado" required>
                        </div>
                        <div class="mb-3">
                            <label for="codigo_postal" class="form-label">Código Postal</label>
                            <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Dirección</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Agregar Sucursal -->
    <div class="modal fade" id="addSucursalModal" tabindex="-1" aria-labelledby="addSucursalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSucursalModalLabel">Agregar Sucursal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        <div class="mb-3">
                            <label for="pag_web" class="form-label">Página Web</label>
                            <input type="url" class="form-control" id="pag_web" name="pag_web"
                                placeholder="https://www.ejemplo.com" required>
                        </div>

                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="correo" name="correo" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FontAwesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
    <script>
        function setSucursalId(id) {
            document.getElementById('sucursal_id').value = id;
        }
    </script>
    <script>
        function setEditSucursalData(id, nombre, telefono, correo, pagWeb) {
            document.getElementById('edit_id_sucursal').value = id;
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_telefono').value = telefono;
            document.getElementById('edit_correo').value = correo;
            document.getElementById('edit_pag_web').value = pagWeb;
        }
    </script>
    <script>
        function eliminarSucursal(id) {
            if (confirm('¿Estás seguro de que deseas eliminar esta sucursal?')) {
                window.location.href = `eliminar_sucursal.php?id=${id}`;
            }
        }
    </script>


</body>

</html>