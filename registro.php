<?php
session_start();
include "conexion.php";
$conexion = conectar();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $nombre = $_POST['nombre'];
    $primer_apellido = $_POST['primer_apellido'];
    $segundo_apellido = $_POST['segundo_apellido'] ?? NULL;
    $dni_nie = $_POST['dni_nie'];
    $numero_documento = trim($_POST['numero_documento']);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $nacionalidad = $_POST['nacionalidad'];
    $isla_residencia = $_POST['isla_residencia'];
    $municipio = $_POST['municipio'];
    $telefono = $_POST['telefono'];
    $email = trim($_POST['email']);
    $contrasena = $_POST['contrasena'];
    $confirm_password = $_POST['confirm_password'];

    $errors = [];

    // Validaciones
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El email no es válido.";
    }
    if ($dni_nie === "DNI" && !preg_match("/^\d{8}[A-Z]$/", $numero_documento)) {
        $errors[] = "Número de DNI inválido.";
    }
    if ($dni_nie === "NIE" && !preg_match("/^[XYZ]\d{7}[A-Z]$/", $numero_documento)) {
        $errors[] = "Número de NIE inválido.";
    }
    if (strlen($contrasena) < 6 || !preg_match('/[A-Za-z]/', $contrasena) || !preg_match('/[0-9]/', $contrasena)) {
        $errors[] = "La contraseña debe tener al menos 6 caracteres, una letra y un número.";
    }
    if ($contrasena !== $confirm_password) {
        $errors[] = "Las contraseñas no coinciden.";
    }

    if (empty($errors)) {
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE email = ? OR numero_documento = ?");
        $stmt->bind_param("ss", $email, $numero_documento);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors[] = "Ya existe un usuario con este email o documento.";
        }
        $stmt->close();
    }

    if (empty($errors)) {
        $tipo_tarjeta = 'Verde';
        $puntos = 100;
        $numero_tarjeta = 'NT10' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);

        $stmt = $conexion->prepare("INSERT INTO usuarios (dni_nie, titulo, nombre, primer_apellido, segundo_apellido, fecha_nacimiento, nacionalidad, isla_residencia, municipio, telefono, email, contrasena, puntos, numero_tarjeta, tipo_tarjeta, numero_documento) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssssssss", $dni_nie, $titulo, $nombre, $primer_apellido, $segundo_apellido, $fecha_nacimiento, $nacionalidad, $isla_residencia, $municipio, $telefono, $email, $contrasena_hash, $puntos, $numero_tarjeta, $tipo_tarjeta, $numero_documento);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Usuario registrado correctamente.";
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Error al registrar el usuario: " . $stmt->error;
        }
        $stmt->close();
    }

    $_SESSION['error'] = implode("<br>", $errors);
}
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro - BinterMas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-dark bg-binter-green">
        <div class="container-fluid">
            <a class="navbar-brand" href="login.php">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Binter_logo.svg/230px-Binter_logo.svg.png" alt="Binter Logo" style="height: 30px;">
            </a>
            <span class="navbar-text">Registro de usuario</span>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4 text-center">Registro de Nuevo Usuario</h2>
        <form method="POST" action="registro.php">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <select class="form-select" id="titulo" name="titulo" required>
                    <option value="">Seleccione</option>
                    <option value="Sr.">Sr.</option>
                    <option value="Sra.">Sra.</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="primer_apellido" class="form-label">Primer Apellido</label>
                <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" required>
            </div>
            <div class="mb-3">
                <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido">
            </div>
            <div class="mb-3">
                <label for="dni_nie" class="form-label">Tipo de Documento</label>
                <select class="form-select" id="dni_nie" name="dni_nie" required>
                    <option value="">Seleccione</option>
                    <option value="DNI">DNI</option>
                    <option value="NIE">NIE</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="numero_documento" class="form-label">Número de DNI/NIE</label>
                <input type="text" class="form-control" id="numero_documento" name="numero_documento" required>
            </div>
            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
            </div>
            <div class="mb-3">
                <label for="nacionalidad" class="form-label">Nacionalidad</label>
                <select class="form-select" id="nacionalidad" name="nacionalidad" required>
                    <option value="">Seleccione</option>
                    <option value="Española">Española</option>
                    <option value="Otra">Otra</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="isla_residencia" class="form-label">Isla de Residencia</label>
                <select class="form-select" id="isla_residencia" name="isla_residencia" required>
                    <option value="">Seleccione</option>
                    <option value="Tenerife">Tenerife</option>
                    <option value="Gran Canaria">Gran Canaria</option>
                    <option value="La Palma">La Palma</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="municipio" class="form-label">Municipio</label>
                <select class="form-select" id="municipio" name="municipio" required>
                    <option value="">Seleccione</option>
                    <option value="Santa Cruz de Tenerife">Santa Cruz de Tenerife</option>
                    <option value="Las Palmas de Gran Canaria">Las Palmas de Gran Canaria</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                    <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('contrasena').type='text'; setTimeout(() => document.getElementById('contrasena').type='password', 3000)">👁️</button>
                </div>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
            <button type="reset" class="btn btn-info">Borrar Formulario</button>
            <a href="login.php" class="btn btn-waring">Cancelar</a>
        </form>
    </div>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <span class="text-muted">© German Paul Tipanluisa. 2024</span>
        </div>
    </footer>
</body>

</html>