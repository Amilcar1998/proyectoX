<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../db/conexion.php';
require_once __DIR__ . '/../models/PlanPagoModel.php';
require_once __DIR__ . '/../controllers/sesiones.php';
require_once __DIR__ . '/../models/AuditoriaHelper.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['s1']) && !isset($_SESSION['s2']) && !isset($_SESSION['c1'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$planId = $input['planId'] ?? 0;

if ($planId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Plan invalido']);
    exit();
}

$planModel = new PlanPagoModel();
$plan = $planModel->getPlanPorId($planId);

if (!$plan || !$plan['activo']) {
    http_response_code(404);
    echo json_encode(['error' => 'Plan no disponible']);
    exit();
}

$wompiPrivateKey = WOMPI_PRIVATE_KEY ?? '';
if (empty($wompiPrivateKey)) {
    http_response_code(500);
    echo json_encode(['error' => 'Wompi no configurado']);
    exit();
}

$username = $_SESSION['s1'] ?? ($_SESSION['s2'] ?? ($_SESSION['c1'] ?? ''));
$idUsuario = obtenerIdUsuarioPorUsername($username);

$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
    . '://' . $_SERVER['HTTP_HOST']
    . dirname($_SERVER['SCRIPT_NAME']);

$monto = (int)($plan['monto'] * 100);

$payload = [
    'amount_in_cents' => $monto,
    'currency' => 'COP',
    'checkout' => [
        'redirect_url' => rtrim($baseUrl, '/') . '/wompi/success.php?plan_id=' . $planId . '&usuario=' . $idUsuario
    ],
    'reference' => 'CONC-' . $idUsuario . '-' . time(),
    'metadata' => [
        'idUsuario' => $idUsuario,
        'idPlanPago' => $planId,
        'nombrePlan' => $plan['nombrePlan']
    ]
];

$ch = curl_init(WOMPI_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $wompiPrivateKey
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode >= 200 && $httpCode < 300) {
    $sessionData = json_decode($response, true);
    $checkoutUrl = $sessionData['data']['checkout_url'] ?? '';
    $paymentIntentId = $sessionData['data']['id'] ?? '';

    if ($checkoutUrl) {
        echo json_encode([
            'url' => $checkoutUrl,
            'payment_intent_id' => $paymentIntentId,
            'reference' => $payload['reference']
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'No se pudo obtener la URL de pago']);
    }
} else {
    http_response_code($httpCode);
    $errorData = json_decode($response, true);
    echo json_encode(['error' => $errorData['status']['message'] ?? 'Error al crear la intencion de pago']);
}
