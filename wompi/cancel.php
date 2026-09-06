<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../db/conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Cancelado | Concentrados El Gordito</title>
    <meta name="description" content="El pago fue cancelado. Vuelve a intentarlo cuando quieras.">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        :root {
            --primary-emerald: #059669;
            --primary-dark: #064e3b;
            --primary-light: #10b981;
            --accent-gold: #f59e0b;
            --dark-surface: #0f172a;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #064e3b 50%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 15px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 50%, rgba(16, 185, 129, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(245, 158, 11, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 60% 80%, rgba(5, 150, 105, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .cancel-card {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.97);
            border-radius: 28px;
            box-shadow:
                0 30px 60px -15px rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.15) inset;
            overflow: hidden;
            max-width: 520px;
            width: 100%;
            animation: slideUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Header */
        .card-header-custom {
            background: linear-gradient(135deg, #d97706, #f59e0b);
            padding: 44px 36px 36px;
            text-align: center;
        }

        .icon-box {
            width: 90px;
            height: 90px;
            background: rgba(255, 255, 255, 0.2);
            border: 3px solid rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 42px;
            color: #ffffff;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.3); }
            50%       { box-shadow: 0 0 0 14px rgba(255, 255, 255, 0); }
        }

        .card-header-custom h1 {
            color: #ffffff;
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: 6px;
            letter-spacing: -0.3px;
        }

        .card-header-custom p {
            color: rgba(255, 255, 255, 0.88);
            font-size: 0.95rem;
            font-weight: 400;
        }

        /* Brand badge */
        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50px;
            padding: 6px 14px;
            margin-bottom: 20px;
            font-size: 0.82rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.95);
            letter-spacing: 0.2px;
        }

        .brand-badge i {
            font-size: 0.9rem;
        }

        /* Body */
        .card-body-custom {
            padding: 36px 36px 32px;
        }

        .info-box {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 16px;
            padding: 22px 24px;
            margin-bottom: 28px;
        }

        .info-box p {
            color: #78350f;
            font-size: 0.95rem;
            line-height: 1.6;
            font-weight: 500;
        }

        .info-box p + p {
            margin-top: 8px;
        }

        /* Actions */
        .actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .btn-primary-custom {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 15px 24px;
            background: linear-gradient(135deg, #059669, #064e3b);
            color: #ffffff;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 700;
            border: none;
            border-radius: 14px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 6px 20px rgba(5, 150, 105, 0.3);
        }

        .btn-primary-custom:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(5, 150, 105, 0.4);
        }

        .btn-secondary-custom {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 24px;
            background: transparent;
            color: var(--text-muted);
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: 600;
            border: 2px solid var(--border-color);
            border-radius: 14px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-secondary-custom:hover {
            color: var(--text-main);
            border-color: #cbd5e1;
            background: #f8fafc;
        }

        /* Footer */
        .card-footer-custom {
            padding: 20px 36px 28px;
            border-top: 1px solid var(--border-color);
            text-align: center;
        }

        .card-footer-custom p {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .card-footer-custom strong {
            color: var(--text-main);
        }

        @media (max-width: 480px) {
            .card-header-custom,
            .card-body-custom,
            .card-footer-custom {
                padding-left: 22px;
                padding-right: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="cancel-card">

        <!-- Header -->
        <div class="card-header-custom">
            <div class="brand-badge">
                <i class="fas fa-industry"></i>
                Concentrados El Gordito
            </div>
            <div class="icon-box">
                <i class="fas fa-times-circle"></i>
            </div>
            <h1>Pago Cancelado</h1>
            <p>No se realizó ningún cobro a tu cuenta.</p>
        </div>

        <!-- Body -->
        <div class="card-body-custom">
            <div class="info-box">
                <p><i class="fas fa-info-circle" style="color:#d97706; margin-right:6px;"></i>
                    La transacción fue cancelada antes de completarse. Esto ocurre cuando el usuario cierra la ventana o elige no continuar con el pago.
                </p>
                <p>Puedes volver a seleccionar tu plan y completar el proceso en cualquier momento, sin ningún costo adicional.</p>
            </div>

            <div class="actions">
                <a href="../controllers/controllerPlanPago.php" class="btn-primary-custom">
                    <i class="fas fa-redo"></i>
                    Volver a intentarlo
                </a>
                <a href="../controllers/controllerLanding.php" class="btn-secondary-custom">
                    <i class="fas fa-home"></i>
                    Ir al inicio
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="card-footer-custom">
            <p><strong>Concentrados El Gordito</strong> &copy; <?php echo date('Y'); ?></p>
            <p style="margin-top:4px;">Nutrición Animal de Alto Rendimiento — El Salvador</p>
        </div>

    </div>
</body>
</html>
