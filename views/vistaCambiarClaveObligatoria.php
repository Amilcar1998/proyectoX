<?php
$error = $error ?? '';
$success = $success ?? '';
$username = $username ?? ($_SESSION['s1'] ?? ($_SESSION['s2'] ?? ($_SESSION['c1'] ?? 'Usuario')));
$claveActual = $claveActual ?? ($_SESSION['pass_temp_ingresada'] ?? '');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>🔐 Cambio de Contraseña Obligatorio - Concentrados El Gordito</title>

  <!-- Bootstrap & FontAwesome & Google Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    * {
      font-family: 'Inter', sans-serif;
    }
    body {
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
      padding: 24px;
    }
    .change-card {
      background: rgba(30, 41, 59, 0.95);
      border: 1px solid rgba(245, 158, 11, 0.3);
      border-radius: 16px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 30px rgba(245, 158, 11, 0.15);
      max-width: 480px;
      width: 100%;
      padding: 36px 30px;
      text-align: center;
    }
    .icon-badge {
      width: 72px;
      height: 72px;
      margin: 0 auto 20px;
      border-radius: 50%;
      background: rgba(245, 158, 11, 0.15);
      border: 2px solid rgba(245, 158, 11, 0.4);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #f59e0b;
      font-size: 30px;
    }
    .pill-security {
      display: inline-block;
      padding: 4px 12px;
      background: rgba(245, 158, 11, 0.2);
      color: #fcd34d;
      font-weight: 700;
      font-size: 12px;
      border-radius: 9999px;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      margin-bottom: 12px;
    }
    h1 {
      color: #ffffff;
      font-size: 22px;
      font-weight: 800;
      margin-bottom: 8px;
    }
    p.subtitle {
      color: #94a3b8;
      font-size: 14px;
      line-height: 1.5;
      margin-bottom: 24px;
    }
    .form-group {
      text-align: left;
      margin-bottom: 16px;
    }
    .form-group label {
      color: #cbd5e1;
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 6px;
    }
    .form-control {
      background: rgba(15, 23, 42, 0.7);
      border: 1px solid rgba(255, 255, 255, 0.12);
      color: #ffffff !important;
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 14px;
      transition: all 0.2s ease;
    }
    .form-control:focus {
      background: rgba(15, 23, 42, 0.9);
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
    }
    .input-group-text {
      background: rgba(15, 23, 42, 0.7);
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-left: none;
      color: #94a3b8;
      cursor: pointer;
    }
    .btn-submit {
      background: linear-gradient(135deg, #f59e0b, #d97706);
      color: #ffffff;
      font-weight: 700;
      border: none;
      border-radius: 8px;
      padding: 12px;
      width: 100%;
      margin-top: 10px;
      box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
      transition: all 0.2s ease;
    }
    .btn-submit:hover {
      background: linear-gradient(135deg, #d97706, #b45309);
      transform: translateY(-1px);
      box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
      color: #ffffff;
    }
    .btn-logout {
      display: inline-block;
      margin-top: 16px;
      color: #64748b;
      font-size: 13px;
      text-decoration: none;
      transition: color 0.2s;
    }
    .btn-logout:hover {
      color: #cbd5e1;
      text-decoration: none;
    }
    .alert-danger {
      background: rgba(239, 68, 68, 0.15);
      border: 1px solid rgba(239, 68, 68, 0.3);
      color: #fca5a5;
      font-size: 13px;
      border-radius: 8px;
      padding: 10px 14px;
      text-align: left;
    }
    .alert-success {
      background: rgba(16, 185, 129, 0.15);
      border: 1px solid rgba(16, 185, 129, 0.3);
      color: #6ee7b7;
      font-size: 13px;
      border-radius: 8px;
      padding: 10px 14px;
      text-align: left;
    }
  </style>
</head>
<body>

  <div class="change-card">
    <div class="icon-badge">
      <i class="fas fa-key"></i>
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
        <label for="clave_actual"><i class="fas fa-lock mr-1"></i> Contraseña Actual / Temporal</label>
        <input type="password" class="form-control" id="clave_actual" name="clave_actual" required 
               placeholder="Ingresa tu contraseña actual" 
               value="<?php echo htmlspecialchars($claveActual); ?>"
               <?php echo empty($claveActual) ? 'autofocus' : ''; ?>>
      </div>

      <div class="form-group">
        <label for="nueva_clave"><i class="fas fa-shield-alt mr-1"></i> Nueva Contraseña (mínimo 6 caracteres)</label>
        <input type="password" class="form-control" id="nueva_clave" name="nueva_clave" required minlength="6" 
               placeholder="Ingresa tu nueva contraseña"
               <?php echo !empty($claveActual) ? 'autofocus' : ''; ?>>
      </div>

      <div class="form-group">
        <label for="confirmar_clave"><i class="fas fa-check-double mr-1"></i> Confirmar Nueva Contraseña</label>
        <input type="password" class="form-control" id="confirmar_clave" name="confirmar_clave" required minlength="6" placeholder="Repite tu nueva contraseña">
      </div>

      <button type="submit" name="cambiar_clave" class="btn btn-submit">
        <i class="fas fa-save mr-2"></i>Guardar Contraseña y Continuar
      </button>
    </form>

    <div>
      <a href="sesiones.php?c=c" class="btn-logout">
        <i class="fas fa-sign-out-alt mr-1"></i>Cerrar Sesión
      </a>
    </div>
  </div>

</body>
</html>
