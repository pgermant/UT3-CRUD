<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos (asegúrate de incluir tu archivo de conexión)
include "conexion.php";
$conexion = conectar();

// Obtener datos del usuario
$user_id = $_SESSION['user_id'];
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

// Procesar la subida de la foto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto'])) {
    $foto = $_FILES['foto'];
    $directorio_destino = "uploads/";
    $nombre_archivo = $user_id . "_" . basename($foto['name']);
    $ruta_archivo = $directorio_destino . $nombre_archivo;

    if (move_uploaded_file($foto['tmp_name'], $ruta_archivo)) {
        // Actualizar la ruta de la foto en la base de datos
        $stmt = $conexion->prepare("UPDATE usuarios SET foto = ? WHERE id = ?");
        $stmt->bind_param("si", $ruta_archivo, $user_id);
        $stmt->execute();
        
        // Actualizar la sesión con la nueva ruta de la foto
        $_SESSION['foto'] = $ruta_archivo;
        
        $mensaje = "Foto subida con éxito.";
    } else {
        $mensaje = "Error al subir la foto.";
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2 class="mb-4">Mi Cuenta</h2>
                
                <?php if (isset($mensaje)): ?>
                    <div class="alert alert-info"><?php echo $mensaje; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <h5>Información Personal</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <img src="<?php echo isset($_SESSION['foto']) ? $_SESSION['foto'] : 'path/to/default/avatar.jpg'; ?>" 
                                     alt="Foto de perfil" class="img-fluid rounded-circle mb-3" style="max-width: 150px;">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Subir Foto</button>
                                </form>
                            </div>
                            <div class="col-md-8">
                                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['primer_apellido'] . ' ' . $usuario['segundo_apellido']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
                                <p><strong>DNI/NIE:</strong> <?php echo htmlspecialchars($usuario['dni_nie'] . ': ' . $usuario['numero_documento']); ?></p>
                                <p><strong>Fecha de Nacimiento:</strong> <?php echo htmlspecialchars($usuario['fecha_nacimiento']); ?></p>
                                <p><strong>Nacionalidad:</strong> <?php echo htmlspecialchars($usuario['nacionalidad']); ?></p>
                                <p><strong>Isla de Residencia:</strong> <?php echo htmlspecialchars($usuario['isla_residencia']); ?></p>
                                <p><strong>Municipio:</strong> <?php echo htmlspecialchars($usuario['municipio']); ?></p>
                                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($usuario['telefono']); ?></p>
                                <p><strong>Tipo de Tarjeta:</strong> <?php echo htmlspecialchars($usuario['tipo_tarjeta']); ?></p>
                                <p><strong>Número de Tarjeta:</strong> <?php echo htmlspecialchars($usuario['numero_tarjeta']); ?></p>
                                <p><strong>Puntos:</strong> <?php echo htmlspecialchars($usuario['puntos']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <a href="menu.php" class="btn btn-secondary mt-3">Volver al Menú</a>
            </div>
        </div>
    </div>
</body>
</html>