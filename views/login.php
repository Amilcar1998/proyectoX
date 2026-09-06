<?php
if (basename($_SERVER['PHP_SELF']) === 'login.php') {
    header("Location: ../controllers/controlUser.php");
    exit();
}

$error = $error ?? '';
$success = $success ?? '';
$successMsg = $successMsg ?? '';
$resetLinkHtml = $resetLinkHtml ?? '';
$showReset = $showReset ?? false;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión | Concentrados El Gordito</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="../controllers/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f172a 0%, #064e3b 40%, #059669 80%, #0f172a 100%);
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
                radial-gradient(circle at 20% 80%, rgba(16, 185, 129, 0.25) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(5, 150, 105, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(245, 158, 11, 0.1) 0%, transparent 50%);
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
            background: linear-gradient(135deg, #064e3b, #059669);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 10px 30px rgba(5, 150, 105, 0.45);
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
            background: linear-gradient(135deg, #059669 0%, #064e3b 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.35);
            margin-top: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: inherit;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(5, 150, 105, 0.45);
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
        <!-- Brand Badge -->
        <div style="text-align:center; margin-bottom:18px; animation: slideUp 0.4s ease-out;">
            <span style="display:inline-flex; align-items:center; gap:8px; background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.2); border-radius:50px; padding:7px 18px; color:rgba(255,255,255,0.92); font-size:0.82rem; font-weight:600; letter-spacing:0.3px; backdrop-filter:blur(8px);">
                <i class="fas fa-industry" style="color:#10b981;"></i>
                Concentrados El Gordito
            </span>
        </div>
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
                        
                        <button type="button" class="btn-login btn-back" onclick="window.location.href='controlUser.php'">
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