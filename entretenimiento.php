<?php
include("funciones.php");
$localidades = [
    'Santa Cruz de Tenerife',
    'Las Palmas de Gran Canaria',
    'La Laguna',
    'Arona',
    'Puerto de la Cruz',
    'Adeje',
    'Arrecife',
    'Puerto del Rosario',
    'San Cristóbal de La Laguna',
    'Telde',
    'Granadilla de Abona',
    'La Orotava',
    'Gáldar',
    'Icod de los Vinos',
    'Güímar',
    'Valverde',
    'San Sebastián de La Gomera'
];

session_start();

// Inicializar variables
$mensaje = '';
$secuencia = $_SESSION['secuencia'] ?? [];
$nivel = $_SESSION['nivel'] ?? 1;
$tiempoLimite = 10;
$tiempoInicio = $_SESSION['tiempo_inicio'] ?? 0;
$mostrarOpciones = $_SESSION['mostrar_opciones'] ?? false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['iniciar'])) {
        $secuencia = [array_rand($localidades)];
        $nivel = 1;
        $tiempoInicio = time();
        $mostrarOpciones = false;
    } elseif (isset($_POST['mostrar_opciones'])) {
        $mostrarOpciones = true;
    } elseif (isset($_POST['respuesta'])) {
        $tiempoActual = time();
        $tiempoTranscurrido = $tiempoActual - $tiempoInicio;
        
        if ($tiempoTranscurrido <= $tiempoLimite) {
            $respuesta = $_POST['respuesta'];
            if ($respuesta === $localidades[$secuencia[$nivel - 1]]) {
                if ($nivel == count($secuencia)) {
                    $secuencia[] = array_rand($localidades);
                    $nivel++;
                    $mensaje = "¡Correcto! Nivel $nivel.";
                } else {
                    $nivel++;
                    $mensaje = "¡Correcto! Siguiente.";
                }
                $tiempoInicio = time();
                $mostrarOpciones = false;
            } else {
                $mensaje = "Incorrecto. Fin del juego. Nivel alcanzado: " . ($nivel - 1);
                $secuencia = [];
                $nivel = 1;
                $mostrarOpciones = false;
            }
        } else {
            $mensaje = "Tiempo agotado. Fin del juego. Nivel alcanzado: " . ($nivel - 1);
            $secuencia = [];
            $nivel = 1;
            $mostrarOpciones = false;
        }
    }
}

$_SESSION['secuencia'] = $secuencia;
$_SESSION['nivel'] = $nivel;
$_SESSION['tiempo_inicio'] = $tiempoInicio;
$_SESSION['mostrar_opciones'] = $mostrarOpciones;


// Calcular tiempo restante
$tiempoRestante = max(0, $tiempoLimite - (time() - $tiempoInicio));

// Redirigir para actualizar el temporizador solo si no se están mostrando opciones
if (!empty($secuencia) && $tiempoRestante > 0 && !$mostrarOpciones) {
    header("Refresh: 1; url=" . $_SERVER['PHP_SELF']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BinterMas - Juego Simón</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Juego Simón - Localidades Canarias</h1>
        <?php if ($mensaje): ?>
            <p><?= htmlspecialchars($mensaje) ?></p>
        <?php endif; ?>

        <form method="post">
            <?php if (empty($secuencia)): ?>
                <button type="submit" name="iniciar" class="btn btn-primary">Iniciar Juego</button>
            <?php elseif (!$mostrarOpciones): ?>
                <p>Nivel: <?= htmlspecialchars($nivel) ?></p>
                <p>Secuencia: <?= implode(', ', array_map(function($i) use ($localidades) { return htmlspecialchars($localidades[$i]); }, array_slice($secuencia, 0, $nivel))) ?></p>
                <p>Tiempo restante: <?= $tiempoRestante ?> segundos</p>
                <button type="submit" name="mostrar_opciones" class="btn btn-primary">Mostrar Opciones</button>
            <?php else: ?>
                <p>Nivel: <?= htmlspecialchars($nivel) ?></p>
                <p>Elige la localidad correcta:</p>
                <?php 
                    $opciones = obtenerOpcionesRespuesta($localidades[$secuencia[$nivel - 1]], $localidades);
                    foreach ($opciones as $opcion):
                ?>
                    <button type="submit" name="respuesta" value="<?= $opcion ?>" class="btn btn-secondary m-1"><?= $opcion ?></button>
                <?php endforeach; ?>
            <?php endif; ?>
        </form>

        <a href="menu.php" class="btn btn-secondary mt-3">Volver al menú</a>
    </div>
</body>
</html>