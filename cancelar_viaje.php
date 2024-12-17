<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancelar Viaje</title>
    <link rel="stylesheet" href="styles.css"> <!-- Enlace a una hoja de estilos opcional -->
</head>
<body>
    <h1>Cancelar Viaje</h1>
    <form action="cancelar_viaje.php" method="post">
        <label for="nombre_usuario">Nombre del Usuario:</label>
        <input type="text" id="nombre_usuario" name="nombre_usuario" required><br><br>

        <label for="numero_reserva">Número de Reserva:</label>
        <input type="text" id="numero_reserva" name="numero_reserva" required><br><br>

        <label for="motivo_cancelacion">Motivo de Cancelación:</label><br>
        <textarea id="motivo_cancelacion" name="motivo_cancelacion" rows="4" cols="50" required></textarea><br><br>

        <input type="submit" value="Cancelar Viaje">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Recibir datos del formulario
        $nombre_usuario = htmlspecialchars($_POST['nombre_usuario']);
        $numero_reserva = htmlspecialchars($_POST['numero_reserva']);
        $motivo_cancelacion = htmlspecialchars($_POST['motivo_cancelacion']);

        // Procesar la cancelación (aquí puedes agregar lógica para guardar los datos en una base de datos o realizar otras acciones necesarias)
        echo "<h2>Cancelación Recibida</h2>";
        echo "<p><strong>Usuario:</strong> $nombre_usuario</p>";
        echo "<p><strong>Número de Reserva:</strong> $numero_reserva</p>";
        echo "<p><strong>Motivo:</strong> $motivo_cancelacion</p>";
    }
    ?>
</body>
</html>
