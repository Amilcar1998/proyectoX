<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../db/conexion.php';
require_once __DIR__ . '/../models/PlanPagoModel.php';

$con = new Conexion();
$conn = $con->getConnection();

// 1. Recibir parámetros de retorno de Wompi El Salvador o URL
$ref = trim((string)($_GET['ref'] ?? $_GET['identificadorEnlaceComercio'] ?? ''));
$idTransaccion = trim((string)($_GET['idTransaccion'] ?? $_GET['id'] ?? ''));
$idEnlace = trim((string)($_GET['idEnlace'] ?? ''));
$esAprobada = $_GET['esAprobada'] ?? '1';

// 2. Buscar el registro de pago en la base de datos
$pago = null;
if (!empty($ref)) {
    $stmt = $conn->prepare("SELECT * FROM pagos WHERE referencia = ? ORDER BY idPago DESC LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("s", $ref);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $row = $res->fetch_assoc()) {
            $pago = $row;
        }
        $stmt->close();
    }
} elseif (!empty($idEnlace)) {
    $enlacePattern = '%Enlace: ' . $idEnlace . '%';
    $stmt = $conn->prepare("SELECT * FROM pagos WHERE descripcion LIKE ? ORDER BY idPago DESC LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("s", $enlacePattern);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $row = $res->fetch_assoc()) {
            $pago = $row;
            $ref = $pago['referencia'];
        }
        $stmt->close();
    }
}

if (!$pago) {
    // Si no vino ref en la URL, buscar el último pago en la BD
    $res = $conn->query("SELECT * FROM pagos ORDER BY idPago DESC LIMIT 1");
    if ($res && $row = $res->fetch_assoc()) {
        $pago = $row;
        $ref = $pago['referencia'];
    }
}

$pedidoCreadoId = 0;
$planActivado = false;
$nombrePlanActivado = '';
$metadatos = [];
$items = [];
$nombreCliente = 'Cliente Web';
$telefonoCliente = '';
$correoCliente = '';
$montoPagado = 0.0;
$fechaHora = date('d/m/Y h:i A');

if ($pago) {
    $montoPagado = (float)$pago['monto'];
    $metadatos = json_decode($pago['metadata'] ?? '{}', true) ?: [];
    $nombreCliente = $metadatos['nombreCliente'] ?? 'Cliente Web';
    $telefonoCliente = $metadatos['telefonoCliente'] ?? '';
    $correoCliente = $metadatos['correoCliente'] ?? '';
    $items = $metadatos['items'] ?? [];
    $idUsuario = (int)($pago['idUsuario'] ?? $metadatos['idUsuario'] ?? 0);
    $idPlanPago = (int)($pago['idPlanPago'] ?? $metadatos['idPlanPago'] ?? 0);

    // 3. Capturar y estructurar toda la información devuelta por Wompi
    $codigoAutorizacion = trim((string)($_GET['codigoAutorizacion'] ?? $_GET['auth'] ?? $_GET['cod'] ?? ''));
    $formaPago = trim((string)($_GET['formaPago'] ?? $_GET['metodo'] ?? 'Tarjeta / Wompi SV'));
    $mensajeWompi = trim((string)($_GET['mensaje'] ?? $_GET['message'] ?? 'Transacción Aprobada'));

    $datosWompiRetorno = [
        'idTransaccion' => $idTransaccion,
        'idEnlace' => $idEnlace,
        'referencia' => $ref,
        'esAprobada' => $esAprobada,
        'codigoAutorizacion' => $codigoAutorizacion,
        'formaPago' => $formaPago,
        'mensaje' => $mensajeWompi,
        'parametros_get_completos' => $_GET,
        'parametros_post_completos' => $_POST,
        'query_string_raw' => $_SERVER['QUERY_STRING'] ?? '',
        'fecha_retorno' => date('Y-m-d H:i:s'),
        'ip_retorno' => $_SERVER['REMOTE_ADDR'] ?? '',
        'user_agent_retorno' => $_SERVER['HTTP_USER_AGENT'] ?? ''
    ];

    $metadatos['wompi_retorno'] = $datosWompiRetorno;
    $metadatos['wompi_raw'] = $_GET;
    if (!empty($idTransaccion)) {
        $metadatos['idTransaccion'] = $idTransaccion;
    }
    if (!empty($codigoAutorizacion)) {
        $metadatos['codigoAutorizacion'] = $codigoAutorizacion;
    }

    // Actualizar estado del pago a completado y guardar metadatos completos
    $txnDesc = !empty($idTransaccion) ? 'Txn: ' . $idTransaccion : (!empty($idEnlace) ? 'Enlace: ' . $idEnlace : 'Wompi SV');
    if (!empty($codigoAutorizacion)) {
        $txnDesc .= ' | Auth: ' . $codigoAutorizacion;
    }
    $nuevoMetadataJson = json_encode($metadatos, JSON_UNESCAPED_UNICODE);

    $stmtUp = $conn->prepare(
        "UPDATE pagos 
         SET estado = 'completado', 
             descripcion = CONCAT(descripcion, ' | Aprobado: ', ?), 
             metadata = ? 
         WHERE idPago = ?"
    );
    if ($stmtUp) {
        $stmtUp->bind_param("ssi", $txnDesc, $nuevoMetadataJson, $pago['idPago']);
        $stmtUp->execute();
        $stmtUp->close();
    }

    // 4. Procesar activación de PLAN si aplica
    if ($idPlanPago > 0) {
        $planModel = new PlanPagoModel();
        $plan = $planModel->getPlanPorId($idPlanPago);
        if ($plan) {
            $nombrePlanActivado = $plan['nombrePlan'];
            $planActivado = true;
            $dias = (int)($plan['duracion_dias'] ?: 30);

            // Asegurar existencia de usuario para el plan
            if ($idUsuario === 0) {
                if (!empty($correoCliente)) {
                    $stmtCheckU = $conn->prepare("SELECT idUsuario FROM usuarios WHERE username = ? LIMIT 1");
                    if ($stmtCheckU) {
                        $stmtCheckU->bind_param("s", $correoCliente);
                        $stmtCheckU->execute();
                        $resCheckU = $stmtCheckU->get_result();
                        if ($resCheckU && $rowU = $resCheckU->fetch_assoc()) {
                            $idUsuario = (int)$rowU['idUsuario'];
                        }
                        $stmtCheckU->close();
                    }
                }
                if ($idUsuario === 0) {
                    $qMaxU = $conn->query("SELECT COALESCE(MAX(idUsuario), 391200) + 1 AS nextId FROM usuarios");
                    $nextUId = (int)$qMaxU->fetch_assoc()['nextId'];
                    $userMail = !empty($correoCliente) ? $correoCliente : 'cliente' . $nextUId . '@concentradoselgordito.com';
                    $passHash = sha1('12345');
                    $stmtNewU = $conn->prepare("INSERT INTO usuarios (idUsuario, username, pass, id_Rol) VALUES (?, ?, ?, 3)");
                    if ($stmtNewU) {
                        $stmtNewU->bind_param("iss", $nextUId, $userMail, $passHash);
                        $stmtNewU->execute();
                        $idUsuario = $nextUId;
                        $stmtNewU->close();
                    }
                }
            }

            if ($idUsuario > 0) {
                $stmtPlan = $conn->prepare(
                    "INSERT INTO usuario_plan_pago (idUsuario, idPlanPago, fecha_inicio, fecha_fin, estado, monto_pagado, observaciones)
                     VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL ? DAY), 'activo', ?, ?)"
                );
                if ($stmtPlan) {
                    $obs = 'Wompi SV Pago #' . $pago['idPago'] . ' | Ref: ' . $pago['referencia'];
                    $stmtPlan->bind_param("iiids", $idUsuario, $idPlanPago, $dias, $montoPagado, $obs);
                    $stmtPlan->execute();
                    $stmtPlan->close();
                }
            }
        }
    }

    // Si el pago proviene de un pedido existente del portal de clientes, actualizar su estado a Pagado (idEstadoPedido=3)
    if (!empty($metadatos['idPedido']) && (int)$metadatos['idPedido'] > 0) {
        $pedidoCreadoId = (int)$metadatos['idPedido'];
        $stmtUpPed = $conn->prepare("UPDATE pedido SET idEstadoPedido = 3 WHERE idPedido = ?");
        if ($stmtUpPed) {
            $stmtUpPed->bind_param("i", $pedidoCreadoId);
            $stmtUpPed->execute();
            $stmtUpPed->close();
        }
    }

    $debeCrarPedido = empty($metadatos['idPedidoCreado']) && empty($metadatos['idPedido']) && $montoPagado > 0;
    if ($debeCrarPedido) {
        // A. Buscar o crear cliente en cliente
        $idCliente = 0;

        // Buscar por idUsuario del pago (viene del registro en la landing)
        if ($idUsuario > 0) {
            $stmtCU = $conn->prepare("SELECT idCliente FROM cliente WHERE idUsuario = ? LIMIT 1");
            if ($stmtCU) {
                $stmtCU->bind_param("i", $idUsuario);
                $stmtCU->execute();
                $resC = $stmtCU->get_result();
                if ($resC && $rowC = $resC->fetch_assoc()) {
                    $idCliente = (int)$rowC['idCliente'];
                }
                $stmtCU->close();
            }
        }

        // Buscar por teléfono si no encontró por idUsuario
        if ($idCliente === 0 && !empty($telefonoCliente)) {
            $stmtC = $conn->prepare("SELECT idCliente FROM cliente WHERE telefono = ? LIMIT 1");
            if ($stmtC) {
                $stmtC->bind_param("s", $telefonoCliente);
                $stmtC->execute();
                $resC = $stmtC->get_result();
                if ($resC && $rowC = $resC->fetch_assoc()) {
                    $idCliente = (int)$rowC['idCliente'];
                }
                $stmtC->close();
            }
        }

        // Si no existe, crear cliente nuevo vinculado al usuario
        if ($idCliente === 0) {
            if ($idUsuario === 0) {
                $qU = $conn->query("SELECT idUsuario FROM usuarios WHERE id_Rol = 3 OR id_Rol = 2 LIMIT 1");
                $idUsuario = ($qU && $rowU = $qU->fetch_assoc()) ? (int)$rowU['idUsuario'] : 391001;
            }
            $partes     = explode(' ', trim($nombreCliente), 2);
            $nomC       = $partes[0];
            $apeC       = $partes[1] ?? '';
            $stmtNewC   = $conn->prepare(
                "INSERT INTO cliente (NombreCliente, apellidosCliente, telefono, idUsuario) VALUES (?, ?, ?, ?)"
            );
            if ($stmtNewC) {
                $stmtNewC->bind_param("sssi", $nomC, $apeC, $telefonoCliente, $idUsuario);
                $stmtNewC->execute();
                $idCliente = (int)$stmtNewC->insert_id;
                $stmtNewC->close();
            }
        }

        // B. Crear orden en pedido
        $qMaxP = $conn->query("SELECT COALESCE(MAX(idPedido), 391100) + 1 AS nextId FROM pedido");
        $pedidoCreadoId = (int)$qMaxP->fetch_assoc()['nextId'];
        $fechaActual = date('d/m/Y');
        $idEstado = 1; // 1: No trabajado / Pendiente de producción
        $stmtPed = $conn->prepare("INSERT INTO pedido (idPedido, fechaPedido, idCliente, idEstadoPedido) VALUES (?, ?, ?, ?)");
        if ($stmtPed) {
            $stmtPed->bind_param("isii", $pedidoCreadoId, $fechaActual, $idCliente, $idEstado);
            $stmtPed->execute();
            $stmtPed->close();

            // C. Insertar detalles en detallePedido
            if (!empty($items)) {
                // Carrito de productos — un detalle por cada ítem
                foreach ($items as $it) {
                    $idReceta = (int)($it['id'] ?? 1);
                    $cantidad = max(1, (int)($it['cantidad'] ?? 1));
                    $stmtDet  = $conn->prepare(
                        "INSERT INTO detallePedido (cantidad, idReceta, IdPedido) VALUES (?, ?, ?)"
                    );
                    if ($stmtDet) {
                        $stmtDet->bind_param("iii", $cantidad, $idReceta, $pedidoCreadoId);
                        $stmtDet->execute();
                        $stmtDet->close();
                    }
                }
            } else {
                // Plan de pago — registrar un detalle genérico (idReceta=1) con cantidad 1
                $cantPlan   = 1;
                $idRecetaRef = 1;
                $stmtDet    = $conn->prepare(
                    "INSERT INTO detallePedido (cantidad, idReceta, IdPedido) VALUES (?, ?, ?)"
                );
                if ($stmtDet) {
                    $stmtDet->bind_param("iii", $cantPlan, $idRecetaRef, $pedidoCreadoId);
                    $stmtDet->execute();
                    $stmtDet->close();
                }
            }

            // Guardar idPedidoCreado en metadatos para evitar duplicados si recarga
            $metadatos['idPedidoCreado'] = $pedidoCreadoId;
            $metaJsonActualizado = json_encode($metadatos);
            $stmtUpMeta = $conn->prepare("UPDATE pagos SET metadata = ? WHERE idPago = ?");
            if ($stmtUpMeta) {
                $stmtUpMeta->bind_param("si", $metaJsonActualizado, $pago['idPago']);
                $stmtUpMeta->execute();
                $stmtUpMeta->close();
            }
        }
    } elseif (!empty($metadatos['idPedidoCreado'])) {
        $pedidoCreadoId = (int)$metadatos['idPedidoCreado'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Compra Confirmada con Éxito! | Concentrados El Gordito</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #064e3b 50%, #0f172a 100%);
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 15px;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 50%, rgba(16, 185, 129, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(245, 158, 11, 0.08) 0%, transparent 50%);
            pointer-events: none;
        }

        .brand-bar {
            position: relative;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 50px;
            padding: 7px 18px;
            color: rgba(255,255,255,0.92);
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            margin-bottom: 20px;
            backdrop-filter: blur(8px);
        }

        .receipt-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 45px -10px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            max-width: 650px;
            width: 100%;
        }

        .receipt-header {
            background: linear-gradient(135deg, #059669, #064e3b);
            color: #ffffff;
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }

        .success-icon-box {
            width: 80px;
            height: 80px;
            background: #ffffff;
            color: #059669;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
            margin: 0 auto 18px auto;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            animation: bounceIn 0.8s ease;
        }

        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.08); }
            70% { transform: scale(0.95); }
            100% { transform: scale(1); opacity: 1; }
        }

        .receipt-body {
            padding: 35px 30px;
        }

        .info-grid {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 0.93rem;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-label {
            color: #64748b;
            font-weight: 500;
        }

        .info-value {
            font-weight: 700;
            color: #0f172a;
            text-align: right;
        }

        .items-table {
            width: 100%;
            margin-bottom: 25px;
            font-size: 0.92rem;
        }

        .items-table th {
            color: #64748b;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
        }

        .items-table td {
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .total-box {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 16px;
            padding: 18px 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .btn-home {
            background: linear-gradient(135deg, #059669, #064e3b);
            color: #ffffff;
            font-weight: 700;
            padding: 13px 26px;
            border-radius: 12px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .btn-home:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(5, 150, 105, 0.35);
        }

        .btn-wa {
            background: #25d366;
            color: #ffffff;
            font-weight: 700;
            padding: 13px 26px;
            border-radius: 12px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .btn-wa:hover {
            background: #1eb956;
            color: #ffffff;
            transform: translateY(-2px);
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .receipt-card {
                box-shadow: none;
                border: none;
            }
            .btn-actions {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Brand Badge -->
    <div class="brand-bar">
        <i class="fas fa-industry" style="color:#10b981;"></i>
        Concentrados El Gordito
    </div>

    <div class="receipt-card" style="position:relative; z-index:1;">
        <!-- Header -->
        <div class="receipt-header">
            <div class="success-icon-box">
                <i class="fas fa-check"></i>
            </div>
            <h2 class="h3 fw-bold mb-1">¡Pago Aprobado con Éxito!</h2>
            <p class="text-white-50 small mb-0">Pasarela Oficial Wompi El Salvador • Transacción Confirmada</p>
        </div>

        <!-- Body -->
        <div class="receipt-body">
            <?php if ($pedidoCreadoId > 0): ?>
                <div class="alert alert-success d-flex align-items-center gap-3 mb-4 rounded-3 border-0 bg-success bg-opacity-10 text-success">
                    <i class="fas fa-boxes-packing fs-3"></i>
                    <div>
                        <div class="fw-bold">Pedido ingresado al sistema #<?= $pedidoCreadoId ?></div>
                        <div class="small">Tu orden ya fue registrada en la cola de producción y despacho de la planta.</div>
                    </div>
                </div>
            <?php elseif ($planActivado): ?>
                <div class="alert alert-success d-flex align-items-center gap-3 mb-4 rounded-3 border-0 bg-success bg-opacity-10 text-success">
                    <i class="fas fa-certificate fs-3"></i>
                    <div>
                        <div class="fw-bold">Plan <?= htmlspecialchars($nombrePlanActivado) ?> Activado</div>
                        <div class="small">Tu suscripción comercial ya está registrada y vinculada en el sistema.</div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-success mb-4 rounded-3">
                    <div class="fw-bold"><i class="fas fa-check-circle me-1"></i> Transacción Registrada</div>
                    <div class="small text-muted">Hemos procesado tu pago en Wompi y registrado los datos en la base de datos.</div>
                </div>
            <?php endif; ?>

            <!-- Información General -->
            <div class="info-grid">
                <div class="info-row">
                    <span class="info-label">Referencia de Pago:</span>
                    <span class="info-value font-monospace"><?= htmlspecialchars($pago['referencia'] ?? $ref) ?></span>
                </div>
                <?php if (!empty($idTransaccion)): ?>
                    <div class="info-row">
                        <span class="info-label">ID Transacción Wompi:</span>
                        <span class="info-value font-monospace"><?= htmlspecialchars($idTransaccion) ?></span>
                    </div>
                <?php endif; ?>
                <div class="info-row">
                    <span class="info-label">Cliente / Granja:</span>
                    <span class="info-value"><?= htmlspecialchars($nombreCliente) ?></span>
                </div>
                <?php if (!empty($telefonoCliente)): ?>
                    <div class="info-row">
                        <span class="info-label">Teléfono:</span>
                        <span class="info-value"><?= htmlspecialchars($telefonoCliente) ?></span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($correoCliente)): ?>
                    <div class="info-row">
                        <span class="info-label">Correo:</span>
                        <span class="info-value"><?= htmlspecialchars($correoCliente) ?></span>
                    </div>
                <?php endif; ?>
                <div class="info-row">
                    <span class="info-label">Fecha y Hora:</span>
                    <span class="info-value"><?= $fechaHora ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Método de Pago:</span>
                    <span class="info-value text-success"><i class="fas fa-credit-card me-1"></i> Wompi El Salvador</span>
                </div>
            </div>

            <!-- Desglose de Productos o Plan -->
            <?php if (!empty($items)): ?>
                <h6 class="fw-bold text-dark mb-3 small text-uppercase">Productos Comprados:</h6>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Cant.</th>
                            <th>Producto</th>
                            <th class="text-end">P. Unit.</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $it): 
                            $cant = max(1, (int)($it['cantidad'] ?? 1));
                            $prec = (float)($it['precio'] ?? 0);
                            $sub = $cant * $prec;
                        ?>
                            <tr>
                                <td class="fw-bold"><?= $cant ?></td>
                                <td><?= htmlspecialchars($it['nombre'] ?? 'Concentrado') ?></td>
                                <td class="text-end text-muted">$<?= number_format($prec, 2) ?></td>
                                <td class="text-end fw-bold text-dark">$<?= number_format($sub, 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php elseif ($planActivado): ?>
                <div class="p-3 bg-light rounded-3 mb-4">
                    <div class="fw-bold text-dark mb-1"><i class="fas fa-box-open text-primary me-2"></i> <?= htmlspecialchars($nombrePlanActivado) ?></div>
                    <div class="small text-muted">Suscripción comercial por 30 días de cobertura nutricional y despacho prioritario.</div>
                </div>
            <?php endif; ?>

            <!-- Total Box -->
            <div class="total-box">
                <div>
                    <div class="small text-muted text-uppercase fw-bold">Total Pagado en Wompi</div>
                    <div class="text-muted small">Sin recargos adicionales</div>
                </div>
                <div class="fs-3 fw-bold text-success">
                    $<?= number_format($montoPagado, 2) ?> <span class="fs-6 text-dark">USD</span>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="d-flex flex-column flex-sm-row gap-3 btn-actions">
                <a href="../index.php" class="btn-home flex-grow-1">
                    <i class="fas fa-house"></i>
                    <span>Volver al Inicio</span>
                </a>
                <?php if (isset($_SESSION['s1']) || isset($_SESSION['s2'])): ?>
                    <a href="../controllers/controllerPagos.php" class="btn btn-outline-dark py-3 px-4 rounded-3 fw-bold d-inline-flex align-items-center justify-content-center gap-2">
                        <i class="fas fa-money-bill-wave text-success"></i>
                        <span>Ver en Módulo Pagos</span>
                    </a>
                <?php endif; ?>
                <button type="button" class="btn btn-outline-secondary py-3 px-4 rounded-3 fw-bold" onclick="window.print()">
                    <i class="fas fa-print me-1"></i> Imprimir
                </button>
                <?php 
                    $mensajeWa = "¡Hola Concentrados El Gordito! Ya realicé mi pago en Wompi por $" . number_format($montoPagado, 2) . " USD (Ref: " . ($pago['referencia'] ?? $ref) . "). Deseo coordinar el despacho para " . urlencode($nombreCliente) . ".";
                    $urlWa = "https://wa.me/50378905678?text=" . $mensajeWa;
                ?>
                <a href="<?= $urlWa ?>" target="_blank" class="btn-wa">
                    <i class="fab fa-whatsapp fs-5"></i>
                    <span>WhatsApp</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Limpiar carrito local en el navegador del cliente tras compra confirmada -->
    <script>
        try {
            localStorage.removeItem('concentrados_el_gordito_carrito');
        } catch (e) {}
    </script>
</body>
</html>
