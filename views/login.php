<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (isset($_SESSION['s1']) || isset($_SESSION['s2']) || isset($_SESSION['c1'])) {
    header('Location: controllerEmpleado.php');
    exit();
}

$error = '';
$success = '';
$successMsg = '';
$resetLinkHtml = '';
$showRecovery = false;
$showReset = false;
$tokenValido = false;
$emailRecovery = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = new mysqli('localhost', 'root', '', 'concentrados');
    if (!$mysqli->connect_errno) {
        $mysqli->set_charset('utf8mb4');
        
        if (isset($_POST['solicitar_recuperacion'])) {
            $email = trim($_POST['email'] ?? '');
            
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Por favor ingrese un correo válido';
            } else {
                $stmt = $mysqli->prepare("SELECT u.idUsuario, u.username FROM usuarios u WHERE u.username = ?");
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $usuario = $result->fetch_assoc();
                    $token = bin2hex(random_bytes(32));
                    $expiracion = date('Y-m-d H:i:s', strtotime('+1 hour'));
                    
                    $stmtInsert = $mysqli->prepare("INSERT INTO recuperacion_pass (email, token, expiracion) VALUES (?, ?, ?)");
                    $stmtInsert->bind_param('sss', $email, $token, $expiracion);
                    $stmtInsert->execute();
                    
                    $resetLink = "http://localhost/ProyectoX/reset_password.php?token=$token";
                    
                    $asunto = "Recuperación de contraseña - Concentrados El Gordito";
                    $mensaje = "<html><body style='font-family:Arial,sans-serif;background:#f4f4f4;padding:30px;'><div style='max-width:600px;margin:0 auto;background:white;padding:30px;border-radius:10px;'><h2 style='color:#1e3a8a;'>Recuperación de contraseña</h2><p>Hola, haz clic en el siguiente enlace para restablecer tu contraseña:</p><a href='$resetLink' style='display:inline-block;padding:14px 28px;background:#7c3aed;color:white;text-decoration:none;border-radius:8px;font-weight:600;'>Restablecer contraseña</a><p style='margin-top:20px;color:#666;'>El enlace expira en 1 hora.</p></div></body></html>";
                    $cabeceras = "MIME-Version: 1.0\r\n";
                    $cabeceras .= "Content-type: text/html; charset=utf-8\r\n";
                    $cabeceras .= "From: Concentrados El Gordito <admin@localhost>\r\n";
                    $cabeceras .= "Reply-To: admin@localhost\r\n";
                    
                    $enviado = @mail($email, $asunto, $mensaje, $cabeceras);
                    
                    if ($enviado) {
                        $successMsg = "Correo de recuperación enviado a $email.";
                    } else {
                        $successMsg = "No se pudo enviar el correo automáticamente. Usa el enlace directo:";
                        $resetLinkHtml = "<a href='$resetLink' target='_blank' style='color:#1e40af;font-weight:bold;word-break:break-all;'>$resetLink</a>";
                    }
                } else {
                    $success = "Si el correo existe en nuestro sistema, recibirás un enlace de recuperación.";
                }
            }
        }
        
        if (isset($_POST['reset_password'])) {
            $token = $_POST['token'] ?? '';
            $nuevaPass = $_POST['nueva_pass'] ?? '';
            $confirmarPass = $_POST['confirmar_pass'] ?? '';
            
            if (empty($token) || empty($nuevaPass) || empty($confirmarPass)) {
                $error = 'Todos los campos son obligatorios';
            } elseif ($nuevaPass !== $confirmarPass) {
                $error = 'Las contraseñas no coinciden';
            } elseif (strlen($nuevaPass) < 6) {
                $error = 'La contraseña debe tener al menos 6 caracteres';
            } else {
                $stmt = $mysqli->prepare("SELECT id, email, expiracion, usado FROM recuperacion_pass WHERE token = ? AND usado = 0");
                $stmt->bind_param('s', $token);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    if (strtotime($row['expiracion']) > time()) {
                        $passHash = sha1($nuevaPass);
                        
                        $stmtUpdate = $mysqli->prepare("UPDATE usuarios SET pass = ? WHERE username = ?");
                        $stmtUpdate->bind_param('ss', $passHash, $row['email']);
                        $stmtUpdate->execute();
                        
                        $stmtUpdate2 = $mysqli->prepare("UPDATE recuperacion_pass SET usado = 1 WHERE id = ?");
                        $stmtUpdate2->bind_param('i', $row['id']);
                        $stmtUpdate2->execute();
                        
                        $success = 'Contraseña actualizada correctamente. Ahora puedes iniciar sesión.';
                        $showReset = false;
                    } else {
                        $error = 'El enlace de recuperación ha expirado o ya fue utilizado';
                        $showReset = false;
                    }
                } else {
                    $error = 'El enlace de recuperación es inválido o ha expirado';
                    $showReset = false;
                }
            }
            
            if (!empty($error) && !empty($token)) {
                $stmtToken = $mysqli->prepare("SELECT id, email, expiracion, usado FROM recuperacion_pass WHERE token = ? AND usado = 0");
                $stmtToken->bind_param('s', $token);
                $stmtToken->execute();
                $resToken = $stmtToken->get_result();
                if ($resToken->num_rows > 0) {
                    $rowToken = $resToken->fetch_assoc();
                    if (strtotime($rowToken['expiracion']) > time()) {
                        $showReset = true;
                        $tokenValido = true;
                        $emailRecovery = $rowToken['email'];
                    }
                }
            }
        }
        
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            $stmt = $mysqli->prepare("SELECT id, email, expiracion, usado FROM recuperacion_pass WHERE token = ?");
            $stmt->bind_param('s', $token);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (strtotime($row['expiracion']) > time() && !$row['usado']) {
                    $showReset = true;
                    $tokenValido = true;
                    $emailRecovery = $row['email'];
                } else {
                    $error = 'El enlace de recuperación ha expirado o ya fue utilizado';
                }
            } else {
                $error = 'Token de recuperación inválido';
            }
        }
        
        $mysqli->close();
    }
}

require_once __DIR__ . '/../models/UsuarioModel.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['validar']) && empty($error)) {
    $obUser = new UsuarioModel();
    $r = $obUser->validarUsuario(trim($_POST['login'] ?? ''), $_POST['pass'] ?? '');
    
    if ($r == 1) {
        $_SESSION['s1'] = trim($_POST['login']);
        require_once __DIR__ . '/../models/AuditoriaModel.php';
        $aud = new AuditoriaModel();
        $aud->log(0, trim($_POST['login']), 'login', 'empleado', 'Inicio de sesion exitoso');
        header('Location: controllerEmpleado.php');
        exit();
    } elseif ($r == 2) {
        $_SESSION['s2'] = trim($_POST['login']);
        require_once __DIR__ . '/../models/AuditoriaModel.php';
        $aud = new AuditoriaModel();
        $aud->log(0, trim($_POST['login']), 'login', 'cliente', 'Inicio de sesion exitoso');
        header('Location: controllerPedidosIn.php');
        exit();
    } elseif ($r == 3) {
        $_SESSION['c1'] = trim($_POST['login']);
        require_once __DIR__ . '/../models/AuditoriaModel.php';
        $aud = new AuditoriaModel();
        $aud->log(0, trim($_POST['login']), 'login', 'cliente_individual', 'Inicio de sesion exitoso');
        header('Location: controllerIndividualC.php');
        exit();
    } else {
        $error = 'Usuario o contraseña incorrectos';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión | Concentrados El Gordito</title>
    
    <link href="../controllers/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 30%, #7c3aed 70%, #db2777 100%);
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(59, 130, 246, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(168, 85, 247, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(236, 72, 153, 0.2) 0%, transparent 50%);
            animation: float 20s linear infinite;
        }

        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(30px, -30px) rotate(5deg); }
            100% { transform: translate(0, 0) rotate(0deg); }
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            padding: 20px;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.25),
                0 0 0 1px rgba(255, 255, 255, 0.2) inset;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
        }

        .login-header {
            text-align: center;
            margin-bottom: 36px;
        }

        .logo {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #1e3a8a, #7c3aed);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 10px 30px rgba(30, 58, 138, 0.4);
            position: relative;
        }

        .logo i {
            font-size: 36px;
            color: white;
        }

        .logo::after {
            content: '';
            position: absolute;
            top: -4px;
            right: -4px;
            width: 16px;
            height: 16px;
            background: #10b981;
            border-radius: 50%;
            border: 3px solid white;
        }

        .login-header h1 {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
            letter-spacing: -0.3px;
        }

        .login-header p {
            font-size: 14px;
            color: #64748b;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 16px;
            transition: color 0.2s ease;
        }

        .form-control {
            width: 100%;
            padding: 14px 48px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            font-family: inherit;
            transition: all 0.2s ease;
            background: #f8fafc;
            color: #0f172a;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .form-control:focus ~ i {
            color: #3b82f6;
        }

        .form-control::placeholder {
            color: #a0aec0;
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            font-size: 16px;
            transition: color 0.2s ease;
            background: none;
            border: none;
            padding: 0;
        }

        .toggle-password:hover {
            color: #475569;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #1e3a8a 0%, #7c3aed 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(30, 58, 138, 0.3);
            margin-top: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(124, 58, 237, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            line-height: 1.5;
        }

        .alert-error {
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .alert i {
            font-size: 18px;
        }

        .recovery-section {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #e2e8f0;
        }

        .recovery-link {
            color: #3b82f6;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.2s ease;
            cursor: pointer;
            display: inline-block;
            padding: 8px 16px;
            border-radius: 8px;
            background: rgba(59, 130, 246, 0.08);
        }

        .recovery-link:hover {
            color: #1e40af;
            background: rgba(59, 130, 246, 0.18);
        }

        .recovery-form {
            margin-top: 20px;
            text-align: left;
        }

        .recovery-form .form-group {
            margin-bottom: 16px;
        }

        .btn-recovery {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(71, 85, 105, 0.2);
        }

        .btn-recovery:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(71, 85, 105, 0.3);
        }

        .btn-back {
            background: transparent;
            color: #64748b;
            border: 2px solid #e2e8f0;
            margin-top: 10px;
        }

        .btn-back:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .footer-text {
            text-align: center;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 13px;
        }

        .footer-text strong {
            color: #0f172a;
        }

        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 8px;
            transition: all 0.3s ease;
        }

        .strength-weak { background: #ef4444; width: 33%; }
        .strength-medium { background: #f59e0b; width: 66%; }
        .strength-strong { background: #10b981; width: 100%; }

        @media (max-width: 480px) {
            .login-card {
                padding: 32px 24px;
                border-radius: 20px;
            }
            
            .login-header h1 {
                font-size: 20px;
            }
            
            .logo {
                width: 64px;
                height: 64px;
                border-radius: 16px;
            }
            
            .logo i {
                font-size: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo">
                    <i class="fas fa-industry"></i>
                </div>
                <h1>Concentrados El Gordito</h1>
                <p>Sistema de Gestión</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($success) || !empty($successMsg) || !empty($resetLinkHtml)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>
                        <?php if (!empty($success)): ?>
                            <?php echo htmlspecialchars($success); ?>
                        <?php endif; ?>
                        <?php if (!empty($successMsg)): ?>
                            <?php echo htmlspecialchars($successMsg); ?>
                        <?php endif; ?>
                        <?php if (!empty($resetLinkHtml)): ?>
                            <br><br><?php echo $resetLinkHtml; ?>
                        <?php endif; ?>
                    </span>
                </div>
            <?php endif; ?>

            <?php if ($showReset): ?>
                <form method="POST" action="">
                    <div class="recovery-form">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                        
                        <div class="form-group">
                            <label for="nueva_pass">Nueva Contraseña</label>
                            <div class="input-wrapper">
                                <input 
                                    type="password" 
                                    id="nueva_pass" 
                                    name="nueva_pass" 
                                    class="form-control" 
                                    placeholder="••••••••"
                                    required
                                    minlength="6"
                                >
                                <i class="fas fa-lock"></i>
                                <button type="button" class="toggle-password" onclick="togglePasswordVisibility('nueva_pass', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-strength" id="strength"></div>
                        </div>

                        <div class="form-group">
                            <label for="confirmar_pass">Confirmar Contraseña</label>
                            <div class="input-wrapper">
                                <input 
                                    type="password" 
                                    id="confirmar_pass" 
                                    name="confirmar_pass" 
                                    class="form-control" 
                                    placeholder="••••••••"
                                    required
                                    minlength="6"
                                >
                                <i class="fas fa-lock"></i>
                                <button type="button" class="toggle-password" onclick="togglePasswordVisibility('confirmar_pass', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn-login" name="reset_password">
                            <i class="fas fa-key"></i>
                            Actualizar Contraseña
                        </button>
                        
                        <button type="button" class="btn-login btn-back" onclick="window.location.href='login.php'">
                            <i class="fas fa-arrow-left"></i>
                            Volver al Login
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <form method="POST" action="" id="loginForm">
                    <div class="form-group">
                        <label for="login">Usuario / Correo</label>
                        <div class="input-wrapper">
                            <input 
                                type="email" 
                                id="login" 
                                name="login" 
                                class="form-control" 
                                placeholder="correo@empresa.com"
                                required
                                autofocus
                                autocomplete="email"
                            >
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pass">Contraseña</label>
                        <div class="input-wrapper">
                            <input 
                                type="password" 
                                id="pass" 
                                name="pass" 
                                class="form-control" 
                                placeholder="••••••••"
                                required
                                autocomplete="current-password"
                            >
                            <i class="fas fa-lock"></i>
                            <button type="button" class="toggle-password" onclick="togglePasswordVisibility('pass', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-login" id="validar" name="validar">
                        <i class="fas fa-sign-in-alt"></i>
                        Iniciar Sesión
                    </button>
                </form>

                <div class="recovery-section">
                    <button type="button" class="recovery-link" onclick="showRecoveryForm(event)">
                        <i class="fas fa-question-circle"></i>
                        ¿Olvidaste tu contraseña?
                    </button>
                    
                    <div id="recoveryForm" style="display: none; margin-top: 20px;">
                        <form method="POST" action="">
                            <div class="recovery-form">
                                <div class="form-group">
                                    <label for="email">Correo Electrónico</label>
                                    <div class="input-wrapper">
                                        <input 
                                            type="email" 
                                            id="email" 
                                            name="email" 
                                            class="form-control" 
                                            placeholder="correo@empresa.com"
                                            required
                                        >
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                </div>
                                <button type="submit" class="btn-recovery" name="solicitar_recuperacion">
                                    <i class="fas fa-paper-plane"></i>
                                    Enviar enlace de recuperación
                                </button>
                                <button type="button" class="btn-login btn-back" onclick="hideRecoveryForm()">
                                    <i class="fas fa-arrow-left"></i>
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <div class="footer-text">
                <p><strong>Concentrados El Gordito</strong> &copy; 2026</p>
                <p style="margin-top: 4px;">Todos los derechos reservados</p>
            </div>
        </div>
    </div>

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

        function showRecoveryForm(event) {
            if (event) event.preventDefault();
            document.getElementById('recoveryForm').style.display = 'block';
            document.querySelector('.recovery-section a').style.display = 'none';
            document.getElementById('email').focus();
        }

        function hideRecoveryForm() {
            document.getElementById('recoveryForm').style.display = 'none';
            const link = document.querySelector('.recovery-section a');
            if (link) link.style.display = 'inline';
        }

        const nuevaPassField = document.getElementById('nueva_pass');
        if (nuevaPassField) {
            nuevaPassField.addEventListener('input', function() {
                const strength = this.value;
                const strengthBar = document.getElementById('strength');
                if (strengthBar) {
                    strengthBar.className = 'password-strength';
                    if (strength.length < 6) {
                        strengthBar.classList.add('strength-weak');
                    } else if (strength.length < 10) {
                        strengthBar.classList.add('strength-medium');
                    } else {
                        strengthBar.classList.add('strength-strong');
                    }
                }
            });
        }

        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>