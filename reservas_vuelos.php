<?php
session_start();
include "conexion.php";
include "funciones.php";


// Verificar sesión
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
if (isset($_POST['logout'])) {
    session_start();
    session_destroy();
    header("Location: login.php");
    exit();
}
// Conexión a la base de datos
$conexion = conectar();
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Inicializar variables
$user_id = $_SESSION['id'];
$mensaje = '';
$vuelos_disponibles = [];

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['buscar_vuelos'])) {
        $aeropuerto_origen = $_POST['aeropuerto_origen'];
        $aeropuerto_destino = $_POST['aeropuerto_destino'];

    } elseif (isset($_POST['reservar'])) {
        $vuelo_id = $_POST['vuelo_id'];
        $tipo_tarifa = $_POST['tipo_tarifa'];

        if (crearReserva($conexion, $user_id, $vuelo_id, $tipo_tarifa)) {
            $mensaje = "Reserva creada con éxito. Puedes verla en 'Mis Reservas'.";
        } else {
            $mensaje = "Error al crear la reserva.";
        }
    }
}

// Cerrar conexión
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Vuelos - BinterMas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-binter-green bg-black">
      <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
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

<div class="container mt-4">
    <h1 class="mb-4">Reserva de Vuelos</h1>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <!-- Formulario para buscar vuelos -->
   <!-- Formulario para buscar vuelos -->
<div class="card mb-4">
    <div class="card-header">Buscar Vuelos Disponibles</div>
    <div class="card-body">
        <form method="POST">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="aeropuerto_origen" class="form-label">Origen</label>
                    <select class="form-select" id="aeropuerto_origen" name="aeropuerto_origen" required>
                        <option value="">Selecciona un aeropuerto</option>
                        <option value="Tenerife Norte">Tenerife Norte</option>
                        <option value="Gran Canaria">Gran Canaria</option>
                        <option value="La Palma">La Palma</option>
                        <option value="Lanzarote">Lanzarote</option>
                        <option value="Fuerteventura">Fuerteventura</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="aeropuerto_destino" class="form-label">Destino</label>
                    <select class="form-select" id="aeropuerto_destino" name="aeropuerto_destino" required>
                        <option value="">Selecciona un aeropuerto</option>
                        <option value="Tenerife Norte">Tenerife Norte</option>
                        <option value="Gran Canaria">Gran Canaria</option>
                        <option value="La Palma">La Palma</option>
                        <option value="Lanzarote">Lanzarote</option>
                        <option value="Fuerteventura">Fuerteventura</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="hora_salida" class="form-label">Hora de Salida</label>
                    <select class="form-select" id="hora_salida" name="hora_salida" required>
                        <option value="">Selecciona una hora</option>
                        <?php
                        for ($i = 6; $i <= 22; $i++) {
                            $hora = sprintf("%02d:00", $i);
                            echo "<option value=\"$hora\">$hora</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="hora_llegada" class="form-label">Hora de Llegada</label>
                    <select class="form-select" id="hora_llegada" name="hora_llegada" required>
                        <option value="">Selecciona una hora</option>
                        <?php
                        for ($i = 7; $i <= 23; $i++) {
                            $hora = sprintf("%02d:00", $i);
                            echo "<option value=\"$hora\">$hora</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <button type="submit" name="buscar_vuelos" class="btn btn-primary">Buscar</button>
        </form>
    </div>
</div>

    <!-- Tabla de vuelos disponibles -->
    <?php if (!empty($vuelos_disponibles)): ?>
        <div class="card mb-4">
            <div class="card-header">Vuelos Disponibles</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Vuelo</th>
                            <th>aeropuerto_Origen</th>
                            <th>aeropuerto_Destino</th>
                            <th>Hora Salida</th>
                            <th>Hora Llegada</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo generarTablaVuelos($vuelos_disponibles); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
        <div class="alert alert-warning">No se encontraron vuelos para los criterios seleccionados.</div>
    <?php endif; ?>
</div>

<footer class="footer mt-auto py-3 bg-light">
    <div class="container text-center">
        <span class="text-muted">© BinterMas 2024. Todos los derechos reservados.</span>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
