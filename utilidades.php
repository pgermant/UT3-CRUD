<?php
// Incluir archivos necesarios
include 'funciones.php';
include 'lib_datos.php';

// Inicializar variables
$origen = $_POST['origen'] ?? '';
$destino = $_POST['destino'] ?? '';
$horaInicio = $_POST['hora_inicio'] ?? '';
$horaFin = $_POST['hora_fin'] ?? '';
$vuelosEncontrados = [];

// Procesar el formulario si se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($vueloAeroSalida as $vuelo => $aeroSalida) {
        $aeroLlegada = $vueloAeroLlegada[$vuelo];
        $horasalida = $vueloHoraSalida[$vuelo];
        $horallegada = $vueloHoraLlegada[$vuelo];
        if ($aeroSalida == $origen && $aeroLlegada == $destino && $horaInicio >= $horasalida && $horaFin <= $horallegada) {
            $vuelosEncontrados[$vuelo] = [
                'hora_inicio' => $horasalida,
                'hora_fin' => $horallegada,
                'origen' => $islas[$aeroSalida],
                'destino' => $islas[$aeroLlegada]
            ];
        }
    }
}

// Función para generar opciones de islas

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BinterMas - Utilidades de Viaje</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Utilidades de Viaje</h1>
        <form method="post">
            <div class="mb-3">
                <label for="origen" class="form-label">Origen:</label>
                <select class="form-select" id="origen" name="origen" required>
                    <option value="">Seleccione una isla</option>
                    <?php echo generarOpcionesIslas($islas, $origen); ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="destino" class="form-label">Destino:</label>
                <select class="form-select" id="destino" name="destino" required>
                    <option value="">Seleccione una isla</option>
                    <?php echo generarOpcionesIslas($islas, $destino); ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="hora_inicio" class="form-label">Hora inicio:</label>
                <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" value="<?php echo htmlspecialchars($horaInicio); ?>" required>
            </div>
            <div class="mb-3">
                <label for="hora_fin" class="form-label">Hora fin:</label>
                <input type="time" class="form-control" id="hora_fin" name="hora_fin" value="<?php echo htmlspecialchars($horaFin); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Buscar vuelos</button>
        </form>

        <?php if (!empty($vuelosEncontrados)): ?>
            <h2 class="mt-4">Vuelos encontrados:</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Vuelo</th>
                        <th>Hora Salida</th>
                        <th>Hora Llegada</th>
                        <th>Origen</th>
                        <th>Destino</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo generarTablaVuelos($vuelosEncontrados); ?>
                </tbody>
            </table>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <p class="mt-4 alert alert-info">No se encontraron vuelos para los criterios seleccionados.</p>
        <?php endif; ?>

        <a href="menu.php" class="btn btn-secondary mt-3">Volver al menú</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>