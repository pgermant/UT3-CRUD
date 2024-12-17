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

$user_id = $_SESSION['id'];

// Fetch user's reservations
$stmt = $conexion->prepare("SELECT r.*, v.aeropuerto_origen, v.aeropuerto_destino, v.fecha_hora_salida 
                            FROM reservas r 
                            JOIN vuelos v ON r.vuelo_id = v.id 
                            WHERE r.usuario_id = ?
                            ORDER BY r.fecha_hora_reserva DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$reservas = $result->fetch_all(MYSQLI_ASSOC);

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mis Reservas - BinterMas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
</head>

<body>

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
      <div class="container mt-4">
        <h1 class="mb-4">Mis Reservas</h1>

        <div class="card mb-4">
          <div class="card-header">
            Mis Reservas Actuales
          </div>
          <div class="card-body">
            <?php if (empty($reservas)): ?>
              <p>No tienes reservas actualmente.</p>
            <?php else: ?>
              <table class="table">
                <thead>
                  <tr>
                    <th>Fecha Reserva</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Fecha Salida</th>
                    <th>Tarifa</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($reservas as $reserva): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($reserva['fecha_hora_reserva']); ?></td>
                      <td><?php echo htmlspecialchars($reserva['aeropuerto_origen']); ?></td>
                      <td><?php echo htmlspecialchars($reserva['aeropuerto_destino']); ?></td>
                      <td><?php echo htmlspecialchars($reserva['fecha_hora_salida']); ?></td>
                      <td><?php echo htmlspecialchars($reserva['tipo_tarifa']); ?></td>
                      <td><?php echo htmlspecialchars($reserva['estado']); ?></td>
                      <td>
                        <form method="POST" action="modificar_reserva.php" style="display: inline;">
                          <input type="hidden" name="reserva_id" value="<?php echo $reserva['id']; ?>">
                          <button type="submit" class="btn btn-primary btn-sm">Modificar</button>
                        </form>
                        <form method="POST" action="borrar_reserva.php" style="display: inline;">
                          <input type="hidden" name="reserva_id" value="<?php echo $reserva['id']; ?>">
                          <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres borrar esta reserva?');">Borrar</button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </main>

    <footer class="footer mt-auto py-3 bg-light">
      <div class="container text-center">
        <span class="text-muted">© BinterMas 2024. Todos los derechos reservados.</span>
      </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  </body>

</html>