<?php
session_start();
include "conexion.php";

// Check if user is logged in
if (!isset($_SESSION['id'])) {
  header("Location: login.php");
  exit();
}

$conexion = conectar();
if (!$conexion) {
  die("Error de conexión: " . mysqli_connect_error());
}

// Fetch user data
$id = $_SESSION['id'];
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle logout
if (isset($_POST['logout'])) {
  session_destroy();
  header("Location: login.php");
  exit();
}

$conexion->close();
?>



<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menú Principal - BinterMas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
  <style>
    .carousel-item {
      height: 300px;
    }

    .card {
      height: 100%;
    }

    .user-info {
      font-size: 1.2em;
    }

    .highlight {
      font-weight: bold;
      color: #008000;
    }

    .carousel-control-prev,
    .carousel-control-next {
      background-color: rgba(0, 0, 0, 0.5);
      width: 5%;
    }

    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    main {
      flex: 1;
    }

    footer {
      margin-top: auto;
      background-color: #f8f9fa;
      padding: 20px 0;
    }
  </style>
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
            <a class="nav-link" href="entretemiento.php">Juego</a>
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
        <div class="card mb-4">
          <div class="card-body">

            <div class="row">
              <div class="col-md-4 text-center">
                <img src="./imagen/avatar.png" alt="Avatar por defecto" class="img-fluid rounded-circle mb-3" style="max-width: 150px;">
              </div>

              <div class="col-md-8">
                <h2 class="card-title">Bienvenido, <?php echo htmlspecialchars($user['nombre']); ?></h2>
                <p class="user-info">Tipo de tarjeta: <span class="highlight"><?php echo htmlspecialchars($user['tipo_tarjeta']); ?></span></p>
                <p class="user-info">Puntos: <span class="highlight"><?php echo htmlspecialchars($user['puntos']); ?></span></p>
                <p class="user-info">Número de tarjeta: <span class="highlight"><?php echo htmlspecialchars($user['numero_tarjeta']); ?></span></p>
              </div>
            </div>
            <hr>
            <h4>Datos del Usuario</h4>
            <p>Nombre completo: <?php echo htmlspecialchars($user['nombre'] . ' ' . $user['primer_apellido'] . ' ' . $user['segundo_apellido']); ?></p>
            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
            <p>Teléfono: <?php echo htmlspecialchars($user['telefono']); ?></p>
            <p>Dirección: <?php echo htmlspecialchars($user['isla_residencia'] . ', ' . $user['municipio']); ?></p>

          </div>

        </div>

      </div>
    </div>
  </main>

  <footer class="footer mt-auto py-3 bg-light">
    <div class="container text-center">
      <span class="text-muted">© BinterMas 2024 Paul German Mena T. Todos los derechos reservados.</span>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>