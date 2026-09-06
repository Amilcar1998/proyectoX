<?php
$enControllers = (strpos($_SERVER['REQUEST_URI'] ?? '', '/controllers/') !== false || strpos($_SERVER['SCRIPT_NAME'] ?? '', '/controllers/') !== false);
$rutaBase = $enControllers ? '../' : '';
$rutaRecursos = $rutaBase . 'views/Recursos/';
$rutaIndex = $rutaBase . 'index.php';
$rutaLogin = $enControllers ? 'controlUser.php' : 'controllers/controlUser.php';
$rutaLogout = $enControllers ? 'Sesiones.php?c=c' : 'controllers/Sesiones.php?c=c';

$moduloNombre = $moduloNombre ?? 'Módulo Protegido';
$rolNombre = $rolNombre ?? 'Tu Rol';
$rutaHome = $rutaHome ?? ($enControllers ? 'controllerDashboard.php' : 'controllers/controllerDashboard.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>⛔ Acceso Denegado - Concentrados El Gordito</title>
  
  <!-- Bootstrap 4.6 & FontAwesome & Google Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary: #1e3a8a;
      --primary-hover: #1e40af;
      --success: #16a34a;
      --dark: #0f172a;
      --light-bg: #f8fafc;
      --border-color: #e2e8f0;
    }

    * {
      font-family: 'Inter', sans-serif;
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      background: linear-gradient(135deg, rgba(15, 23, 42, 0.90) 0%, rgba(15, 23, 42, 0.94) 100%),
                  url('<?php echo $rutaRecursos; ?>banner_planta.jpg') center center / cover no-repeat fixed;
      position: relative;
      color: #334155;
      overflow-x: hidden;
    }

    /* Navbar Header */
    .navbar-main {
      background-color: #0f172a !important;
      border-bottom: 2px solid #1e293b;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35);
      z-index: 1000;
    }
    .navbar-brand {
      font-weight: 800;
      letter-spacing: -0.02em;
      font-size: 1.25rem;
    }
    .nav-link {
      font-weight: 600;
      color: #cbd5e1 !important;
      transition: color 0.2s ease;
      font-size: 0.95rem;
    }
    .nav-link:hover, .nav-link.active {
      color: #38bdf8 !important;
    }

    /* Main Area */
    .main-auth-container {
      flex: 1 0 auto;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
      position: relative;
      z-index: 2;
    }

    .denied-card {
      background: rgba(30, 41, 59, 0.96);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(239, 68, 68, 0.35);
      border-radius: 20px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6), 0 0 30px rgba(239, 68, 68, 0.2);
      max-width: 520px;
      width: 100%;
      text-align: center;
      padding: 40px 32px;
      animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(28px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .icon-badge {
      width: 80px;
      height: 80px;
      margin: 0 auto 20px;
      border-radius: 50%;
      background: rgba(239, 68, 68, 0.15);
      border: 2px solid rgba(239, 68, 68, 0.4);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #ef4444;
      font-size: 36px;
      animation: pulseAlert 2s infinite ease-in-out;
    }

    @keyframes pulseAlert {
      0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
      50% { transform: scale(1.05); box-shadow: 0 0 20px 4px rgba(239, 68, 68, 0.2); }
    }

    .code-pill {
      display: inline-block;
      padding: 5px 14px;
      background: rgba(239, 68, 68, 0.2);
      color: #fca5a5;
      font-weight: 700;
      font-size: 12px;
      border-radius: 9999px;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      margin-bottom: 14px;
      border: 1px solid rgba(239, 68, 68, 0.3);
    }

    h1 {
      color: #ffffff;
      font-size: 24px;
      font-weight: 800;
      margin-bottom: 10px;
      letter-spacing: -0.02em;
    }

    p.description {
      color: #94a3b8;
      font-size: 14px;
      line-height: 1.6;
      margin-bottom: 22px;
    }

    .info-box {
      background: rgba(15, 23, 42, 0.7);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      padding: 16px;
      margin-bottom: 26px;
      text-align: left;
    }

    .info-row {
      display: flex;
      justify-content: space-between;
      color: #cbd5e1;
      font-size: 13.5px;
      margin-bottom: 8px;
    }

    .info-row:last-child {
      margin-bottom: 0;
    }

    .info-row span:first-child {
      color: #94a3b8;
      font-weight: 600;
    }

    .info-row span:last-child {
      font-weight: 700;
    }

    .btn-return {
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      color: #ffffff;
      font-weight: 700;
      border: none;
      border-radius: 10px;
      padding: 13px 24px;
      transition: all 0.2s ease;
      text-decoration: none;
      display: inline-block;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .btn-return:hover {
      background: linear-gradient(135deg, #1d4ed8, #1e40af);
      color: #ffffff;
      transform: translateY(-1px);
      box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
      text-decoration: none;
    }

    .btn-logout {
      background: transparent;
      color: #94a3b8;
      border: 1px solid rgba(148, 163, 184, 0.2);
      border-radius: 10px;
      padding: 12px 20px;
      font-weight: 600;
      margin-left: 10px;
      transition: all 0.2s ease;
      text-decoration: none;
      display: inline-block;
    }

    .btn-logout:hover {
      color: #ffffff;
      border-color: rgba(255, 255, 255, 0.4);
      background: rgba(255, 255, 255, 0.05);
      text-decoration: none;
    }

    /* Corporate Footer */
    .footer-corporate {
      background-color: #0f172a;
      border-top: 2px solid #1e293b;
      padding: 22px 0;
      color: #94a3b8;
      font-size: 0.88rem;
      flex-shrink: 0;
      z-index: 10;
    }
  </style>
</head>
<body>

  <!-- NAVBAR CORPORATIVO -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-main sticky-top">
    <div class="container">
      <a class="navbar-brand text-white d-flex align-items-center" href="<?php echo $rutaIndex; ?>">
        <i class="fas fa-seedling text-success mr-2 fa-lg"></i>
        <span>Concentrados El Gordito</span>
      </a>

      <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#navbarAuth" aria-controls="navbarAuth" aria-expanded="false" aria-label="Navegación">
        <i class="fas fa-bars text-white"></i>
      </button>

      <div class="collapse navbar-collapse" id="navbarAuth">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $rutaIndex; ?>"><i class="fas fa-home mr-1"></i>Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $rutaIndex; ?>#productos"><i class="fas fa-boxes mr-1"></i>Productos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $rutaIndex; ?>#planes"><i class="fas fa-crown mr-1"></i>Planes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $rutaIndex; ?>#nosotros"><i class="fas fa-shield-alt mr-1"></i>Calidad & Planta</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $rutaIndex; ?>#contacto"><i class="fas fa-envelope mr-1"></i>Contacto</a>
          </li>
        </ul>

        <div class="d-flex align-items-center">
          <a href="<?php echo $rutaHome; ?>" class="btn btn-outline-light btn-sm font-weight-bold mr-2">
            <i class="fas fa-home mr-1"></i>Ir a mi Panel
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- ÁREA PRINCIPAL -->
  <main class="main-auth-container">
    <div class="denied-card">
      <div class="icon-badge">
        <i class="fas fa-lock"></i>
      </div>
      <div class="code-pill">Error 403 &bull; Módulo Restringido</div>
      <h1>Acceso Denegado</h1>
      <p class="description">
        No cuentas con los permisos suficientes para acceder a este módulo del sistema. Esta acción ha sido registrada en la auditoría de seguridad.
      </p>

      <div class="info-box">
        <div class="info-row">
          <span>Módulo Solicitado:</span>
          <span class="text-danger"><?php echo htmlspecialchars($moduloNombre); ?></span>
        </div>
        <div class="info-row">
          <span>Rol Asignado:</span>
          <span class="text-info"><?php echo htmlspecialchars($rolNombre); ?></span>
        </div>
      </div>

      <div>
        <a href="<?php echo htmlspecialchars($rutaHome); ?>" class="btn-return">
          <i class="fas fa-home mr-2"></i>Volver a mi Home
        </a>
        <a href="<?php echo $rutaLogout; ?>" class="btn-logout">
          <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
        </a>
      </div>
    </div>
  </main>

  <!-- FOOTER CORPORATIVO -->
  <footer class="footer-corporate">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-left mb-2 mb-md-0">
          <span class="font-weight-bold text-white">
            <i class="fas fa-seedling text-success mr-1"></i> Concentrados El Gordito &copy; <?php echo date('Y'); ?>
          </span>
          <span class="d-block small text-muted">
            Nutrición Animal de Alto Rendimiento &bull; El Salvador
          </span>
        </div>
        <div class="col-md-6 text-center text-md-right">
          <span class="badge badge-dark px-3 py-2 border border-secondary text-light">
            <i class="fas fa-shield-alt text-danger mr-1"></i> Control de Acceso y Auditoría Activa
          </span>
        </div>
      </div>
    </div>
  </footer>

  <!-- Bootstrap & jQuery Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
