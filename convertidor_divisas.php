<?php
include("lib_datos.php");
include("funciones.php");
$cantidad = $de_moneda = $a_moneda = $resultado = $mensaje_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['convertir'])) {
    $cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_FLOAT);
    $de_moneda = $_POST['de_moneda'] ?? '';
    $a_moneda = $_POST['a_moneda'] ?? '';

    if ($cantidad !== false && array_key_exists($de_moneda, $divisas) && array_key_exists($a_moneda, $divisas)) {
        $resultado = ($cantidad / $divisas[$de_moneda]['tasa']) * $divisas[$a_moneda]['tasa'];
    } else {
        $mensaje_error = "Datos inválidos.";
    }
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Convertidor de Divisas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Binter_logo.svg/230px-Binter_logo.svg.png" alt="Binter Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="menu.php">Servicios</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">Convertidor de Divisas</h1>
        
        <?php if ($mensaje_error): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($mensaje_error) ?>
            </div>
        <?php endif; ?>

        <form method="post" class="mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-md-3">
                    <label for="cantidad" class="form-label">Cantidad:</label>
                    <input type="text" id="cantidad" name="cantidad" value="<?= htmlspecialchars($cantidad) ?>" required placeholder="Cantidad" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="de_moneda" class="form-label">De:</label>
                    <select id="de_moneda" name="de_moneda" required class="form-select">
                        <?= generarOpcionesMonedas($divisas, $de_moneda) ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="a_moneda" class="form-label">A:</label>
                    <select id="a_moneda" name="a_moneda" required class="form-select">
                        <?= generarOpcionesMonedas($divisas, $a_moneda) ?>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" name="convertir" class="btn btn-primary w-100">Convertir</button>
                </div>
            </div>
        </form>

        <?php if ($resultado !== ''): ?>
            <div class="alert alert-success" role="alert">
                Resultado: <?= number_format($resultado, 2) . ' ' . $divisas[$a_moneda]['simbolo'] ?>
            </div>
        <?php endif; ?>

        <a href="menu.php" class="btn btn-secondary">Volver al menú</a>
    </div>

    <footer class="footer mt-auto py-3">
        <div class="container">
            <span class="text-muted">© German Paul Tipanluisa. 2024</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>