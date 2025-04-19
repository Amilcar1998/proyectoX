<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Concentrados El Gordito</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Roboto', sans-serif;
        }
        .card img {
            transition: transform 0.3s ease;
            border-radius: 10px;
        }
        .card img:hover {
            transform: scale(1.05);
        }
        .card-title {
            font-weight: bold;
            font-size: 1rem;
        }
        .container h2 {
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .row {
            justify-content: center;
        }
        footer {
            background-color: #343a40;
            color: white;
            padding: 15px 0;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<!-- Encabezado -->
<div class="container mt-4">
    <h2 class="animate__animated animate__fadeInDown">Reportes de Concentrados El Gordito</h2>
</div>

<!-- Cards de Reportes en una sola pantalla -->
<div class="container">
    <div class="row">
        <!-- Reporte 1 -->
        <div class="col-md-2 mb-3">
            <div class="card shadow-sm">
                <img src="Recursos/reporte.png" class="card-img-top" alt="Inventario General">
                <div class="card-body text-center">
                    <h5 class="card-title">Inventario General</h5>
                    <a href="../models/Reporte1.php" class="btn btn-primary btn-sm">Ver Reporte</a>
                </div>
            </div>
        </div>

        <!-- Reporte 2 -->
        <div class="col-md-2 mb-3">
            <div class="card shadow-sm">
                <img src="Recursos/reporte.png" class="card-img-top" alt="Inventario Escaso">
                <div class="card-body text-center">
                    <h5 class="card-title">Inventario Escaso</h5>
                    <a href="../models/Reporte2.php" class="btn btn-warning btn-sm">Ver Reporte</a>
                </div>
            </div>
        </div>

        <!-- Reporte 3 -->
        <div class="col-md-2 mb-3">
            <div class="card shadow-sm">
                <img src="Recursos/reporte.png" class="card-img-top" alt="Mezclas">
                <div class="card-body text-center">
                    <h5 class="card-title">Mezclas</h5>
                    <a href="../models/Reporte3.php" class="btn btn-success btn-sm">Ver Reporte</a>
                </div>
            </div>
        </div>

        <!-- Reporte 4 -->
        <div class="col-md-2 mb-3">
            <div class="card shadow-sm">
                <img src="Recursos/reporte.png" class="card-img-top" alt="Pedidos">
                <div class="card-body text-center">
                    <h5 class="card-title">Pedidos</h5>
                    <a href="../models/Reporte4.php" class="btn btn-danger btn-sm">Ver Reporte</a>
                </div>
            </div>
        </div>

        <!-- Reporte 5 -->
        <div class="col-md-2 mb-3">
            <div class="card shadow-sm">
                <img src="Recursos/reporte.png" class="card-img-top" alt="Solicitud de Materia Prima">
                <div class="card-body text-center">
                    <h5 class="card-title">Solicitud de Materia Prima</h5>
                    <a href="../models/Reporte5.php" class="btn btn-info btn-sm">Ver Reporte</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2025 Concentrados El Gordito. Todos los derechos reservados.</p>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>