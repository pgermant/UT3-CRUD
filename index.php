<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BinterMas - Acceso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <style>
        .bg-binter-green { background-color: #008000; }
        .text-binter-green { color: #008000; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-binter-green">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Binter_logo.svg/230px-Binter_logo.svg.png" alt="Binter Logo" style="height: 30px;">
            </a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 bg-white text-binter-green p-4">
                <h2>Identificación</h2>
                <form action="login.php" method="GET">
                    <div class="mb-3">
                        <label for="metodo_identificacion" class="form-label">Método de identificación</label>
                        <select class="form-select" id="metodo_identificacion" name="metodo_identificacion" required>
                            <option value="email">Correo electrónico</option>
                            <option value="numero_tarjeta">Tarjeta BinterMas</option>
                            <option value="dni_nie">DNI/NIE</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-outline-success">Login</button>
                </form>
            </div>
            <div class="col-md-6 bg-binter-green text-white p-4">
                <h2>Registro</h2>
                <p>Disfruta de las ventajas de ser cliente BinterMas:</p>
                <ul>
                    <li>Acumula puntos en tus vuelos</li>
                    <li>Accede a ofertas exclusivas</li>
                    <li>Gestiona tus reservas fácilmente</li>
                </ul>
                <a href="registro.php" class="btn btn-outline-light">Registrarme</a>
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