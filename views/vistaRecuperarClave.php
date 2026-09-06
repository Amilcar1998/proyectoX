<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Contraseña | Concentrados El Gordito</title>
    
    <link href="<?php echo $rutaBase ?? ''; ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
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
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(circle at 20% 80%, rgba(59,130,246,0.3) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(168,85,247,0.3) 0%, transparent 50%);
            animation: float 20s linear infinite;
        }
        @keyframes float {
            0% { transform: translate(0, 0); }
            50% { transform: translate(30px, -30px); }
            100% { transform: translate(0, 0); }
        }
        .login-container {
            position: relative; z-index: 1;
            width: 100%; max-width: 440px; padding: 20px;
            animation: slideUp 0.6s ease-out;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .login-card {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        }
        .login-header { text-align: center; margin-bottom: 32px; }
        .logo {
            width: 72px; height: 72px;
            background: linear-gradient(135deg, #1e3a8a, #7c3aed);
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 10px 30px rgba(30,58,138,0.4);
        }
        .logo i { font-size: 36px; color: white; }
        .login-header h1 { font-size: 22px; font-weight: 700; color: #0f172a; margin-bottom: 6px; }
        .login-header p { font-size: 14px; color: #64748b; }
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block; font-size: 13px; font-weight: 600;
            color: #475569; margin-bottom: 8px;
            text-transform: uppercase; letter-spacing: 0.3px;
        }
        .input-wrapper { position: relative; }
        .input-wrapper i {
            position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
            color: #94a3b8; font-size: 16px;
        }
        .form-control {
            width: 100%; padding: 14px 48px;
            border: 2px solid #e2e8f0; border-radius: 12px;
            font-size: 15px; font-family: inherit;
            transition: all 0.2s ease; background: #f8fafc;
        }
        .form-control:focus {
            outline: none; border-color: #3b82f6; background: white;
            box-shadow: 0 0 0 4px rgba(59,130,246,0.1);
        }
        .form-control::placeholder { color: #a0aec0; }
        .toggle-password {
            position: absolute; right: 16px; top: 50%; transform: translateY(-50%);
            color: #94a3b8; cursor: pointer; font-size: 16px;
            background: none; border: none; padding: 0;
        }
        .btn-login {
            width: 100%; padding: 15px;
            background: linear-gradient(135deg, #1e3a8a 0%, #7c3aed 100%);
            color: white; border: none; border-radius: 12px;
            font-size: 15px; font-weight: 600; cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(30,58,138,0.3);
            margin-top: 24px;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            text-decoration: none;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(124,58,237,0.4); }
        .alert {
            padding: 14px 16px; border-radius: 12px; margin-bottom: 20px;
            font-size: 14px; display: flex; align-items: center; gap: 10px;
        }
        .alert-error { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .alert-success { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
        .recovery-info {
            background: #eff6ff; color: #1e40af;
            border: 1px solid #bfdbfe; border-radius: 12px;
            padding: 16px; margin-bottom: 24px; font-size: 13px;
        }
        .footer-text {
            text-align: center; margin-top: 32px; padding-top: 24px;
            border-top: 1px solid #e2e8f0; color: #64748b; font-size: 13px;
        }
        @media (max-width: 480px) {
            .login-card { padding: 32px 24px; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo"><i class="fas fa-key"></i></div>
                <h1>Recuperar Contraseña</h1>
                <p>Ingresa tu nueva contraseña</p>
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
                        window.location.href = "<?php echo $rutaLogin ?? 'controllers/controlUser.php'; ?>";
                    }, 3000);
                </script>
            <?php endif; ?>

            <?php if (!$showForm && !$tokenValido && empty($success)): ?>
                <div class="recovery-info">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <strong>Token expirado o inválido.</strong><br>
                        Por seguridad, este enlace solo es válido por 1 hora y se puede usar una sola vez.
                    </div>
                </div>
                <a href="<?php echo $rutaLogin ?? 'controllers/controlUser.php'; ?>" class="btn-login">
                    <i class="fas fa-arrow-left"></i> Volver al Login
                </a>
            <?php endif; ?>

            <?php if ($showForm): ?>
                <form method="POST" action="">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    
                    <div class="form-group">
                        <label for="nueva_pass">Nueva Contraseña</label>
                        <div class="input-wrapper">
                            <input type="password" id="nueva_pass" name="nueva_pass" class="form-control" placeholder="Mínimo 6 caracteres" required minlength="6">
                            <i class="fas fa-lock"></i>
                            <button type="button" class="toggle-password" onclick="togglePasswordVisibility('nueva_pass', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirmar_pass">Confirmar Contraseña</label>
                        <div class="input-wrapper">
                            <input type="password" id="confirmar_pass" name="confirmar_pass" class="form-control" placeholder="Repite tu nueva contraseña" required minlength="6">
                            <i class="fas fa-lock"></i>
                            <button type="button" class="toggle-password" onclick="togglePasswordVisibility('confirmar_pass', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-login" name="reset_password">
                        <i class="fas fa-save"></i> Guardar Nueva Contraseña
                    </button>
                </form>
            <?php endif; ?>

            <div class="footer-text">
                <p><strong>Concentrados El Gordito</strong> &copy; 2026</p>
                <p style="margin-top:4px;">Todos los derechos reservados</p>
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
    </script>
</body>
</html>
