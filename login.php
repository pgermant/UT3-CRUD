<?php
session_start();
include "conexion.php";

$conexion = conectar();
if (!$conexion) {
  die("Error de conexión: " . mysqli_connect_error());
}
$metodo_identificacion = $_GET['metodo_identificacion'] ?? '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $identificacion = $_POST['identificacion'];
  $contrasena = $_POST['contrasena'];

  // Validar formato de identificación
  switch ($metodo_identificacion) {
    case 'email':
      if (!filter_var($identificacion, FILTER_VALIDATE_EMAIL)) {
        $error = "Formato de email inválido";
      }
      break;
    case 'numero_tarjeta':
      if (!preg_match("/^NT[123]0\d{4}$/", $identificacion)) {
        $error = "Formato de tarjeta BinterMas inválido";
      }
      break;
    case 'dni_nie':
      if (!preg_match("/^[0-9XYZ]\d{7}[A-Z]$/", $identificacion)) {
        $error = "Formato de DNI/NIE inválido";
      }
      break;
  }

  $stmt = $conexion->prepare("SELECT id, contrasena, tipo_tarjeta FROM usuarios WHERE email = ? OR numero_tarjeta = ? OR numero_documento = ?");
  $stmt->bind_param("sss", $identificacion, $identificacion, $identificacion);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 1) {
    $usuario = $result->fetch_assoc();
    if (password_verify($contrasena, $usuario['contrasena'])) {
      $_SESSION['id'] = $usuario['id'];
      $_SESSION['tipo_tarjeta'] = $usuario['tipo_tarjeta'];
      header("Location: menu_usuario.php");
      exit();
    } else {
      $error = "Credenciales incorrectas";
    }
  } else {
    $error = "Usuario no encontrado";
  }
  $stmt->close();
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - BinterMas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
</head>

<body>
  <nav class="navbar navbar-dark bg-binter-green">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.phph">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Binter_logo.svg/230px-Binter_logo.svg.png" alt="Binter Logo" style="height: 30px;">
      </a>
    </div>
  </nav>

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <h2 class="mb-4 text-center">Iniciar sesión en BinterMas</h2>

        <?php if (!empty($error)): ?>
          <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">

          <div class="mb-3">
            <label for="identificacion" class="form-label">
              <?php
              switch ($metodo_identificacion) {
                case 'email':
                  echo "Correo electrónico";
                  break;
                case 'numero_tarjeta':
                  echo "Número de tarjeta BinterMas";
                  break;
                case 'dni_nie':
                  echo "DNI/NIE";
                  break;
              }
              ?>

            </label>
            <input type="text" class="form-control" id="identificacion" name="identificacion" required>
          </div>


          <div class="mb-3">
            <label for="contrasena" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="contrasena" name="contrasena" required>
          </div>
          <button type="submit" class="btn btn-primary">Iniciar sesión</button>
        </form>
        <div class="mt-3">
          <a href="recuperar_contrasena.php">¿Has olvidado la contraseña?</a>
        </div>
        <div class="mt-2">
          <a href="index.php">Cambiar método de identificación</a>
        </div>
      </div>
    </div>
  </div>

  <footer class="footer mt-auto py-3 bg-light">
    <div class="container text-center">
      <span class="text-muted">© BinterMas 2024 Paul German Mena T. Todos los derechos reservados.</span>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>