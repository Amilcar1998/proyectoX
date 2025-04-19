<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>concentrados - El Gordito</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Animate.css -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    
    <!-- Font Awesome (Para íconos) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .hero-section {
            background: url('https://noticiasrealessv.com/wp-content/uploads/2022/09/FcoIDXlWAAAIrxf-1170x779.jpg') center/cover no-repeat;
            height: 100vh;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .hero-section h1 {
            font-size: 4rem;
            animation: fadeInDown 2s;
        }
        .hero-section p {
            font-size: 1.5rem;
            margin-top: 20px;
            animation: fadeInUp 2s;
        }
        .carousel img {
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<!-- Hero Section -->
<section class="hero-section">
    <div>
        <h1 class="animate__animated animate__fadeInDown">¡Bienvenido a El Gordito!</h1>
        <p class="animate__animated animate__fadeInUp">Soluciones de alta calidad para tus necesidades</p>
        <a href="#productos" class="btn btn-warning btn-lg mt-4 animate__animated animate__pulse animate__infinite">Descubre más</a> &nbsp;&nbsp;
        <a href="controllers/controlUser.php" class="btn btn-warning btn-lg mt-4 animate__animated animate__pulse animate__infinite">Iniciar - Session</a>
    </div>
</section>

<!-- Carrusel de Imágenes -->
<div id="productos" class="container my-5">
    <h2 class="text-center mb-4">Nuestros Productos Destacados</h2>
    <div id="productosCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://via.placeholder.com/800x400" class="d-block w-100" alt="Producto 1">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Concentrados para Aves</h5>
                    <p>La mejor calidad para el crecimiento y desarrollo.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://via.placeholder.com/800x400" class="d-block w-100" alt="Producto 2">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Concentrados para Ganado</h5>
                    <p>Rendimiento óptimo y resultados garantizados.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://via.placeholder.com/800x400" class="d-block w-100" alt="Producto 3">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Concentrados para Mascotas</h5>
                    <p>Nutrición completa y equilibrada.</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#productosCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#productosCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>
</div>

<!-- Información Adicional -->
<section class="container my-5">
    <div class="row">
        <div class="col-md-4 text-center">
            <i class="fas fa-seedling fa-3x text-success"></i>
            <h3 class="my-3">Productos Naturales</h3>
            <p>Comprometidos con el medio ambiente y la calidad.</p>
        </div>
        <div class="col-md-4 text-center">
            <i class="fas fa-truck fa-3x text-primary"></i>
            <h3 class="my-3">Cobertura Nacional</h3>
            <p>Distribuimos a lo largo y ancho del país.</p>
        </div>
        <div class="col-md-4 text-center">
            <i class="fas fa-award fa-3x text-warning"></i>
            <h3 class="my-3">Calidad Garantizada</h3>
            <p>Productos con altos estándares y tecnología de punta.</p>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white py-4">
    <div class="container text-center">
        <p>&copy; 2025 Concentrados El Gordito. Todos los derechos reservados.</p>
        <p>
            <a href="https://www.facebook.com/Concentrados-el-Gordito-106721037742036/" class="text-white me-2"><i class="fab fa-facebook"></i></a>
            <a href="https://www.instagram.com/concentradosel/" class="text-white"><i class="fab fa-instagram"></i></a>
        </p>
    </div>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>