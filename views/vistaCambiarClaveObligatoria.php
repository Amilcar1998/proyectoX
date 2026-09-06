<?php
$enControllers = (strpos($_SERVER['REQUEST_URI'] ?? '', '/controllers/') !== false || strpos($_SERVER['SCRIPT_NAME'] ?? '', '/controllers/') !== false);
$rutaBase = $enControllers ? '../' : '';
$rutaRecursos = $rutaBase . 'views/Recursos/';
$rutaIndex = $rutaBase . 'index.php';
$rutaLogin = $enControllers ? 'controlUser.php' : 'controllers/controlUser.php';
$rutaLogout = $enControllers ? 'Sesiones.php?c=c' : 'controllers/Sesiones.php?c=c';

$error = $error ?? '';
$success = $success ?? '';
$username = $username ?? ($_SESSION['s1'] ?? ($_SESSION['s2'] ?? ($_SESSION['c1'] ?? 'Usuario')));
$claveActual = $claveActual ?? ($_SESSION['pass_temp_ingresada'] ?? '');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>🔐 Cambio de Contraseña Obligatorio - Concentrados El Gordito</title>

  <!-- Bootstrap 4.6 & FontAwesome & Google Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    body {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      background: linear-gradient(135deg, rgba(15, 23, 42, 0.88) 0%, rgba(15, 23, 42, 0.93) 100%),
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

    .login-container {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 490px;
      animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(28px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .change-card {
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(20px);
      border-radius: 20px;
      padding: 38px 34px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5),
                  0 0 0 1px rgba(255, 255, 255, 0.6) inset;
      text-align: center;
    }

    .icon-badge {
      width: 68px;
      height: 68px;
      margin: 0 auto 16px;
      border-radius: 18px;
      background: linear-gradient(135deg, #d97706, #b45309);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #ffffff;
      font-size: 30px;
      box-shadow: 0 8px 25px rgba(217, 119, 6, 0.35);
    }

    .pill-security {
      display: inline-block;
      padding: 5px 14px;
      background: rgba(217, 119, 6, 0.12);
      color: #b45309;
      font-weight: 700;
      font-size: 11.5px;
      border-radius: 9999px;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      margin-bottom: 12px;
      border: 1px solid rgba(217, 119, 6, 0.3);
    }

    h1 {
      color: #0f172a;
      font-size: 22px;
      font-weight: 800;
      margin-bottom: 6px;
      letter-spacing: -0.02em;
    }

    p.subtitle {
      color: #64748b;
      font-size: 13.5px;
      line-height: 1.5;
      margin-bottom: 24px;
    }

    .form-group {
      text-align: left;
      margin-bottom: 18px;
    }

    .form-group label {
      display: block;
      font-size: 12.5px;
      font-weight: 700;
      color: #475569;
      margin-bottom: 7px;
      text-transform: uppercase;
      letter-spacing: 0.04em;
    }

    .input-wrapper {
      position: relative;
    }

    .input-wrapper i.prefix-icon {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 15px;
    }

    .form-control {
      width: 100%;
      padding: 13px 44px;
      border: 1.5px solid #e2e8f0;
      border-radius: 10px;
      font-size: 14.5px;
      transition: all 0.2s ease;
      background: #f8fafc;
      color: #0f172a;
      height: auto;
    }

    .form-control:focus {
      outline: none;
      border-color: #2563eb;
      background: #ffffff;
      box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
    }

    .btn-submit {
      background: linear-gradient(135deg, #16a34a, #15803d);
      color: #ffffff;
      font-weight: 700;
      font-size: 15px;
      border: none;
      border-radius: 10px;
      padding: 14px;
      width: 100%;
      margin-top: 10px;
      box-shadow: 0 4px 15px rgba(22, 163, 74, 0.35);
      transition: all 0.2s ease;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(22, 163, 74, 0.45);
      color: #ffffff;
    }

    .btn-logout {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-top: 18px;
      color: #64748b;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      transition: color 0.2s;
    }

    .btn-logout:hover {
      color: #dc2626;
      text-decoration: none;
    }

    .alert-danger {
      background: #fef2f2;
      border: 1px solid #fecaca;
      color: #dc2626;
      font-size: 13.5px;
      border-radius: 10px;
      padding: 12px 16px;
      text-align: left;
    }

    .alert-success {
      background: #f0fdf4;
      border: 1px solid #bbf7d0;
      color: #16a34a;
      font-size: 13.5px;
      border-radius: 10px;
      padding: 12px 16px;
      text-align: left;
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

    @media (max-width: 480px) {
      .change-card {
        padding: 28px 20px;
        border-radius: 16px;
      }
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
          <a href="<?php echo $rutaLogout; ?>" class="btn btn-outline-danger btn-sm font-weight-bold">
            <i class="fas fa-sign-out-alt mr-1"></i>Cerrar Sesión
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- ÁREA PRINCIPAL -->
  <main class="main-auth-container">
    <div class="login-container">
      
      <div class="change-card">
        <div class="icon-badge">
          <i class="fas fa-shield-alt"></i>
        </div>
        <div class="pill-security">Seguridad de la Cuenta</div>
        <h1>Cambio Obligatorio de Contraseña</h1>
        <p class="subtitle">
          Hola, <strong><?php echo htmlspecialchars($username); ?></strong>. Tu cuenta requiere que actualices tu contraseña antes de poder ingresar al sistema.
        </p>

        <?php if (!empty($error)): ?>
          <div class="alert alert-danger mb-3">
            <i class="fas fa-exclamation-circle mr-2"></i><?php echo htmlspecialchars($error); ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
          <div class="alert alert-success mb-3">
            <i class="fas fa-check-circle mr-2"></i><?php echo htmlspecialchars($success); ?>
          </div>
        <?php endif; ?>

        <form method="POST" action="controllerCambiarClave.php">
          <div class="form-group">
            <label for="clave_actual">Contraseña Actual / Temporal</label>
            <div class="input-wrapper">
              <input type="password" class="form-control" id="clave_actual" name="clave_actual" required 
                     placeholder="Ingresa tu contraseña temporal" 
                     value="<?php echo htmlspecialchars($claveActual); ?>"
                     <?php echo empty($claveActual) ? 'autofocus' : ''; ?>>
              <i class="fas fa-lock prefix-icon"></i>
            </div>
          </div>

          <div class="form-group">
            <label for="nueva_clave">Nueva Contraseña (mínimo 6 caracteres)</label>
            <div class="input-wrapper">
              <input type="password" class="form-control" id="nueva_clave" name="nueva_clave" required minlength="6" 
                     placeholder="Ingresa tu nueva contraseña segura"
                     <?php echo !empty($claveActual) ? 'autofocus' : ''; ?>>
              <i class="fas fa-key prefix-icon"></i>
            </div>
          </div>

          <div class="form-group">
            <label for="confirmar_clave">Confirmar Nueva Contraseña</label>
            <div class="input-wrapper">
              <input type="password" class="form-control" id="confirmar_clave" name="confirmar_clave" required minlength="6" 
                     placeholder="Repite tu nueva contraseña">
              <i class="fas fa-check-double prefix-icon"></i>
            </div>
          </div>

          <button type="submit" name="cambiar_clave" class="btn-submit">
            <i class="fas fa-save mr-2"></i>Guardar Contraseña y Continuar
          </button>
        </form>

        <div>
          <a href="<?php echo $rutaLogout; ?>" class="btn-logout">
            <i class="fas fa-sign-out-alt mr-1"></i>Cerrar Sesión y Salir
          </a>
        </div>
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
            <i class="fas fa-shield-alt text-success mr-1"></i> Módulo Seguro de Autenticación
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
