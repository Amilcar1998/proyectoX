<?php
$moduloNombre = $moduloNombre ?? 'Módulo Protegido';
$rolNombre = $rolNombre ?? 'Tu Rol';
$rutaHome = $rutaHome ?? 'controlUser.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>⛔ Acceso Denegado - Concentrados El Gordito</title>
  
  <!-- Bootstrap & FontAwesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

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
      padding: 20px;
    }
    .denied-card {
      background: rgba(30, 41, 59, 0.95);
      border: 1px solid rgba(239, 68, 68, 0.3);
      border-radius: 16px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 30px rgba(239, 68, 68, 0.15);
      max-width: 520px;
      width: 100%;
      overflow: hidden;
      text-align: center;
      padding: 40px 30px;
    }
    .icon-badge {
      width: 80px;
      height: 80px;
      margin: 0 auto 24px;
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
      padding: 4px 12px;
      background: rgba(239, 68, 68, 0.2);
      color: #fca5a5;
      font-weight: 700;
      font-size: 13px;
      border-radius: 9999px;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      margin-bottom: 12px;
    }
    h1 {
      color: #ffffff;
      font-size: 26px;
      font-weight: 800;
      margin-bottom: 12px;
    }
    p.description {
      color: #94a3b8;
      font-size: 15px;
      line-height: 1.6;
      margin-bottom: 24px;
    }
    .info-box {
      background: rgba(15, 23, 42, 0.6);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 10px;
      padding: 14px;
      margin-bottom: 28px;
      text-align: left;
    }
    .info-row {
      display: flex;
      justify-content: space-between;
      color: #cbd5e1;
      font-size: 14px;
      margin-bottom: 6px;
    }
    .info-row:last-child {
      margin-bottom: 0;
    }
    .info-row span:first-child {
      color: #64748b;
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
      padding: 12px 24px;
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
      padding: 11px 20px;
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
  </style>
</head>
<body>

  <div class="denied-card">
    <div class="icon-badge">
      <i class="fas fa-lock"></i>
    </div>
    <div class="code-pill">Error 403 &bull; Prohibido</div>
    <h1>Acceso Denegado</h1>
    <p class="description">
      No cuentas con los permisos suficientes para acceder a este módulo. Esta acción ha sido registrada en el sistema de auditoría.
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
      <a href="controlUser.php?c=c" class="btn-logout">
        <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
      </a>
    </div>
  </div>

</body>
</html>
