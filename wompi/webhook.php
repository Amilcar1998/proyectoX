<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../db/conexion.php';
require_once __DIR__ . '/../models/PlanPagoModel.php';

$input = file_get_contents('php://input');
$event = json_decode($input, true);

if (empty($event)) {
    http_response_code(400);
    echo json_encode(['error' => 'Evento vacio']);
    exit();
}

$eventType = $event['event'] ?? '';
$data = $event['data'] ?? [];
$transaction = $data['transaction'] ?? [];

if (empty($transaction)) {
    http_response_code(200);
    echo json_encode(['status' => 'ignored']);
    exit();
}

$status = $transaction['status'] ?? '';
$reference = $transaction['reference'] ?? '';
$amount = ($transaction['amount_in_cents'] ?? 0) / 100;
$currency = $transaction['currency'] ?? 'COP';
$paymentIntentId = $transaction['payment_intent_id'] ?? '';
$metadata = $transaction['metadata'] ?? [];

$idUsuario = isset($metadata['idUsuario']) ? (int)$metadata['idUsuario'] : 0;
$idPlanPago = isset($metadata['idPlanPago']) ? (int)$metadata['idPlanPago'] : 0;

$estado = 'pendiente';
if ($status === 'APPROVED') {
    $estado = 'completado';
} elseif ($status === 'DECLINED' || $status === 'ERROR' || $status === 'FAILED') {
    $estado = 'fallido';
} elseif ($status === 'VOIDED' || $status === 'REFUNDED') {
    $estado = 'reembolsado';
} elseif ($status === 'CANCELLED' || $status === 'ABANDONED') {
    $estado = 'cancelado';
}

$con = new Conexion();
$conn = $con->getConnection();

$obs = 'Wompi TXN: ' . $paymentIntentId . ' | Ref: ' . $reference;
$meta = json_encode($metadata);

$stmtPago = $conn->prepare(
    "INSERT INTO pagos (idUsuario, idPlanPago, monto, moneda, metodo_pago, estado, referencia, descripcion, fecha_hora, ip_address, user_agent, metadata)
     VALUES (?, ?, ?, ?, 'wompi', ?, ?, ?, NOW(), ?, ?, ?)"
);
$idPago = 0;
if ($stmtPago) {
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
    $stmtPago->bind_param("iidssssss", $idUsuario, $idPlanPago, $amount, $currency, $estado, $reference, $obs, $ipAddress, $userAgent, $meta);
    $stmtPago->execute();
    $idPago = $stmtPago->insert_id;
    $stmtPago->close();
}

if ($estado === 'completado' && $idUsuario > 0 && $idPlanPago > 0) {
    $planModel = new PlanPagoModel();
    $plan = $planModel->getPlanPorId($idPlanPago);

    $fechaInicio = date('Y-m-d H:i:s');
    $fechaFin = null;
    if ($plan && !empty($plan['duracion_dias'])) {
        $fechaFin = date('Y-m-d H:i:s', strtotime('+' . (int)$plan['duracion_dias'] . ' days'));
    }

    $monto = $plan['monto'] ?? $amount;
    $obs2 = 'Wompi: ' . $reference;

    $stmtPlan = $conn->prepare(
        "INSERT INTO usuario_plan_pago (idUsuario, idPlanPago, fecha_inicio, fecha_fin, estado, monto_pagado, observaciones, id_pago)
         VALUES (?, ?, ?, ?, 'activo', ?, ?, ?)
         ON DUPLICATE KEY UPDATE estado = VALUES(estado), monto_pagado = VALUES(monto_pagado), id_pago = VALUES(id_pago), fecha_fin = VALUES(fecha_fin)"
    );
    if ($stmtPlan) {
        $stmtPlan->bind_param("iissdsi", $idUsuario, $idPlanPago, $fechaInicio, $fechaFin, $monto, $obs2, $idPago);
        $stmtPlan->execute();
        $stmtPlan->close();
    }
}

http_response_code(200);
echo json_encode([
    'status' => 'ok',
    'event' => $eventType,
    'transaction_status' => $status,
    'idPago' => $idPago
]);
