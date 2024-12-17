<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$conexion = conectar();
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

$id = $_SESSION['id'];
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Actualizar datos del usuario
    $nombre = $_POST['nombre'];
    $primer_apellido = $_POST['primer_apellido'];
    $segundo_apellido = $_POST['segundo_apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $isla_residencia = $_POST['isla_residencia'];
    $municipio = $_POST['municipio'];

    // Actualizar datos en la base de datos
    $update_stmt = $conexion->prepare("UPDATE usuarios SET nombre = ?, primer_apellido = ?, segundo_apellido = ?, email = ?, telefono = ?, isla_residencia = ?, municipio = ? WHERE id = ?");
    $update_stmt->bind_param("sssssssi", $nombre, $primer_apellido, $segundo_apellido, $email, $telefono, $isla_residencia, $municipio, $id);

    if ($update_stmt->execute()) {
        $success_message = "Datos actualizados correctamente.";
        // Actualizar la información del usuario en la sesión
        $user['nombre'] = $nombre;
        $user['primer_apellido'] = $primer_apellido;
        $user['segundo_apellido'] = $segundo_apellido;
        $user['email'] = $email;
        $user['telefono'] = $telefono;
        $user['isla_residencia'] = $isla_residencia;
        $user['municipio'] = $municipio;
    } else {
        $error_message = "Error al actualizar los datos: " . $conexion->error;
    }

    // Manejo de la foto de perfil
    if (isset($_FILES['user_photo']) && $_FILES['user_photo']['error'] == UPLOAD_ERR_OK) {
        // Validar y mover la foto a un directorio específico
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["user_photo"]["name"]);
        move_uploaded_file($_FILES["user_photo"]["tmp_name"], $target_file);

        // Actualizar la ruta de la foto en la base de datos
        $stmt_update_photo = $conexion->prepare("UPDATE usuarios SET foto = ? WHERE id = ?");
        $stmt_update_photo->bind_param("si", $target_file, $id);
        if ($stmt_update_photo->execute()) {
            // Actualizar la sesión con la nueva foto
            $_SESSION['foto'] = htmlspecialchars($target_file);
            // Actualizar también en la variable del usuario
            $user['foto'] = htmlspecialchars($target_file);
            // Mensaje de éxito
            echo "<div class='alert alert-success'>Foto actualizada correctamente.</div>";
        }
        $stmt_update_photo->close();
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta - BinterMas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>

<body>
<nav class="navbar navbar-dark bg-binter-green bg-black">
    <div class="container-fluid">
      <a class="navbar-brand" href="binter2.php">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Binter_logo.svg/230px-Binter_logo.svg.png" alt="Binter Logo" style="height: 30px;">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="mi_cuenta.php">Mi Cuenta</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="mis_reservas.php">Mis Reservas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="reservas_vuelos.php">Crear/Modificar Reservas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="entretenimiento.php">Juego</a>
          </li>

          <form method="post" class="mt-3">
            <button type="submit" name="logout" class="btn btn-danger">Cerrar Sesión</button>
          </form>

        </ul>
        <form class="d-flex" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
      </div>
    </div>
  </nav>

    <main class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2>Mi Cuenta</h2>

                <!-- Sección para subir foto -->
                <h4>Cambiar Foto de Perfil</h4>
                <?php if (!empty($user['foto'])): ?>
                    <img src="<?php echo htmlspecialchars($user['foto']); ?>" alt="" style='max-width:150px;' />
                <?php else: ?>
                    <img src="./imagen/avatar.png" alt="" style='max-width:150px;' />
                <?php endif; ?>

                <input type='file' name='user_photo' accept='image/*' />

                <!-- Botón para actualizar -->
                <button type='submit' class='btn btn-primary'>Actualizar Datos</button>

                </form>

                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
                <?php endif; ?>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="primer_apellido" class="form-label">Primer Apellido</label>
                        <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" value="<?php echo htmlspecialchars($user['primer_apellido']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                        <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido" value="<?php echo htmlspecialchars($user['segundo_apellido']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($user['telefono']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="isla_residencia" class="form-label">Isla de Residencia</label>
                        <input type="text" class="form-control" id="isla_residencia" name="isla_residencia" value="<?php echo htmlspecialchars($user['isla_residencia']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="municipio" class="form-label">Municipio</label>
                        <input type="text" class="form-control" id="municipio" name="municipio" value="<?php echo htmlspecialchars($user['municipio']); ?>" required>
                    </div>



                    <!-- Botón para cerrar sesión -->
                    <form method='post' action='' style='display:inline;'>
                        <button type='submit' name='logout' class='btn btn-danger'>Cerrar Sesión</button>
                    </form>

            </div>
        </div>
    </main>

    <footer class='footer mt-auto py-3 bg-light'>
        <div class='container text-center'>
            <span class='text-muted'>© BinterMas 2024 Paul German Mena T. Todos los derechos reservados.</span>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>