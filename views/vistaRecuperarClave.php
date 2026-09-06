<?php
$enControllers = (strpos($_SERVER['REQUEST_URI'] ?? '', '/controllers/') !== false || strpos($_SERVER['SCRIPT_NAME'] ?? '', '/controllers/') !== false);
$rutaBase = $enControllers ? '../' : '';
$rutaRecursos = $rutaBase . 'views/Recursos/';
$rutaIndex = $rutaBase . 'index.php';
$rutaLogin = $enControllers ? 'controlUser.php' : 'controllers/controlUser.php';

$error = $error ?? '';
$success = $success ?? '';
$showForm = $showForm ?? false;
$tokenValido = $tokenValido ?? false;
$token = $token ?? ($_GET['token'] ?? ($_POST['token'] ?? ''));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Recuperación de Contraseña - Concentrados El Gordito">
    <title>Recuperar Contraseña | Concentrados El Gordito</title>
    
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

        /* Main Center Area */
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
            width: 100%;
            max-width: 460px;
            animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(28px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 38px 34px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5),
                        0 0 0 1px rgba(255, 255, 255, 0.6) inset;
        }

        .login-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .logo-badge {
            width: 68px;
            height: 68px;
            background: linear-gradient(135deg, #2563eb, #1e40af);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
            color: #ffffff;
            font-size: 30px;
        }

        .login-header h1 {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
            letter-spacing: -0.02em;
        }

        .login-header p {
            font-size: 13.5px;
            color: #64748b;
            font-weight: 500;
        }

        .form-group {
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
            transition: color 0.2s ease;
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

        .form-control:focus ~ i.prefix-icon {
            color: #2563eb;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            font-size: 15px;
            background: none;
            border: none;
            padding: 6px;
            transition: color 0.2s ease;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(22, 163, 74, 0.35);
            margin-top: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(22, 163, 74, 0.45);
            color: #ffffff;
            text-decoration: none;
        }

        .btn-back {
            background: transparent;
            color: #64748b;
            border: 1.5px solid #e2e8f0;
            margin-top: 10px;
            box-shadow: none;
        }

        .btn-back:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #334155;
            box-shadow: none;
            transform: none;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .recovery-info {
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 20px;
            font-size: 13.5px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .footer-text {
            text-align: center;
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 12.5px;
        }

        .footer-text strong {
            color: #0f172a;
        }

        .back-to-landing {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 12px;
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .back-to-landing:hover {
            color: #2563eb;
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

        @media (max-width: 480px) {
            .login-card {
                padding: 28px 20px;
                border-radius: 16px;
            }
        }
    </style>
</head>
<body>

    <!-- NAVBAR CORPORATIVO INTEGRADO -->
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
                    <a href="<?php echo $rutaLogin; ?>" class="btn btn-primary btn-sm font-weight-bold">
                        <i class="fas fa-sign-in-alt mr-1"></i>Iniciar Sesión
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ÁREA PRINCIPAL -->
    <main class="main-auth-container">
        <div class="login-container">

            <div class="login-card">
                <div class="login-header">
                    <div class="logo-badge">
                        <i class="fas fa-key"></i>
                    </div>
                    <h1>Restablecer Contraseña</h1>
                    <p>Ingresa y confirma tu nueva clave de acceso</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span><?php echo htmlspecialchars($success); ?></span>
                    </div>
                    <script>
                        setTimeout(function(){
                            window.location.href = "<?php echo $rutaLogin; ?>";
                        }, 2500);
                    </script>
                <?php endif; ?>

                <?php if (!$showForm && !$tokenValido && empty($success)): ?>
                    <div class="recovery-info">
                        <i class="fas fa-shield-alt mt-1"></i>
                        <div>
                            <strong>Enlace expirado o no válido.</strong><br>
                            Por motivos de seguridad, este enlace de restablecimiento es válido por tiempo limitado y de un solo uso.
                        </div>
                    </div>
                    <a href="<?php echo $rutaLogin; ?>" class="btn-login">
                        <i class="fas fa-arrow-left"></i> Volver al Inicio de Sesión
                    </a>
                <?php endif; ?>

                <?php if ($showForm): ?>
                    <form method="POST" action="">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                        
                        <div class="form-group">
                            <label for="nueva_pass">Nueva Contraseña</label>
                            <div class="input-wrapper">
                                <input type="password" id="nueva_pass" name="nueva_pass" class="form-control" placeholder="Mínimo 6 caracteres" required minlength="6">
                                <i class="fas fa-lock prefix-icon"></i>
                                <button type="button" class="toggle-password" onclick="togglePasswordVisibility('nueva_pass', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="confirmar_pass">Confirmar Contraseña</label>
                            <div class="input-wrapper">
                                <input type="password" id="confirmar_pass" name="confirmar_pass" class="form-control" placeholder="Repite tu nueva contraseña" required minlength="6">
                                <i class="fas fa-lock prefix-icon"></i>
                                <button type="button" class="toggle-password" onclick="togglePasswordVisibility('confirmar_pass', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn-login" name="reset_password">
                            <i class="fas fa-save"></i> Guardar Nueva Contraseña
                        </button>

                        <a href="<?php echo $rutaLogin; ?>" class="btn-login btn-back">
                            <i class="fas fa-arrow-left"></i> Cancelar y Volver
                        </a>
                    </form>
                <?php endif; ?>

                <div class="footer-text">
                    <p><strong>Concentrados El Gordito</strong> &copy; <?php echo date('Y'); ?></p>
                    <a href="<?php echo $rutaIndex; ?>" class="back-to-landing">
                        <i class="fas fa-arrow-left"></i> Volver a la página principal
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
                        <i class="fas fa-shield-alt text-success mr-1"></i> Restablecimiento Seguro
                    </span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap & jQuery Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function togglePasswordVisibility(fieldId, button) {
            const field = document.getElementById(fieldId);
            const icon = button.querySelector('i');
            if (field.type === 'password') {
                field.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                field.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }
    </script>
</body>
</html>
