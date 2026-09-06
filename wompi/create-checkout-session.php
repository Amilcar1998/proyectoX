<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../db/conexion.php';
require_once __DIR__ . '/../models/PlanPagoModel.php';
require_once __DIR__ . '/../models/AuditoriaHelper.php';

if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

require_once __DIR__ . '/../models/EmpresaModel.php';

$input = json_decode(file_get_contents('php://input'), true) ?? [];

$empresaModel = new EmpresaModel();
$idEmpresa = (int)($input['idEmpresa'] ?? 1);
$slugTienda = trim((string)($input['tienda'] ?? ''));

if (!empty($slugTienda)) {
    $empresaData = $empresaModel->obtenerPorSlug($slugTienda);
    if ($empresaData) {
        $idEmpresa = (int)$empresaData['idEmpresa'];
    }
}

$credenciales = $empresaModel->obtenerCredencialesWompi($idEmpresa);
$wompiClientId = $credenciales['app_id'] ?? '';
$wompiClientSecret = $credenciales['api_key'] ?? '';
$nombreComercio = $credenciales['nombre_empresa'] ?? 'Concentrados El Gordito';

if (empty($wompiClientId) || empty($wompiClientSecret)) {
    http_response_code(500);
    echo json_encode(['error' => 'Este comercio no tiene configuradas credenciales de Wompi SV activas.']);
    exit();
}

// 1. Determinar el usuario o cliente
$idUsuario = 0;

// Prioridad 1: sesión PHP activa (usuario logueado en el sistema)
if (isset($_SESSION['s1']) || isset($_SESSION['s2']) || isset($_SESSION['c1'])) {
    $username = (string)($_SESSION['s1'] ?? $_SESSION['s2'] ?? $_SESSION['c1'] ?? '');
    try {
        $con = new Conexion();
        $conn = $con->getConnection();
        $stmtU = $conn->prepare("SELECT idUsuario FROM usuarios WHERE username = ? LIMIT 1");
        if ($stmtU) {
            $stmtU->bind_param("s", $username);
            $stmtU->execute();
            $resU = $stmtU->get_result();
            if ($resU && $rowU = $resU->fetch_assoc()) {
                $idUsuario = (int)$rowU['idUsuario'];
            }
            $stmtU->close();
        }
    } catch (Exception $e) {
        $idUsuario = 0;
    }
}

// Prioridad 2: idUsuario enviado desde el formulario de registro/login de la landing
if ($idUsuario === 0 && !empty($input['idUsuario']) && (int)$input['idUsuario'] > 0) {
    $idUsuario = (int)$input['idUsuario'];
}

$nombreCliente = trim((string)($input['nombre'] ?? 'Cliente'));
$telefonoCliente = trim((string)($input['telefono'] ?? ''));
$correoCliente = trim((string)($input['correo'] ?? ''));
if (empty($correoCliente) || !filter_var($correoCliente, FILTER_VALIDATE_EMAIL)) {
    $correoCliente = 'ventas@concentradoselgordito.com';
}

$planId = (int)($input['planId'] ?? 0);
$items = $input['items'] ?? [];

$montoTotalUSD = 0.0;
$nombreProducto = '';
$metadatos = [
    'idUsuario' => $idUsuario,
    'nombreCliente' => $nombreCliente,
    'telefonoCliente' => $telefonoCliente,
    'correoCliente' => $correoCliente,
    'origen' => 'landing_page'
];

if ($planId > 0) {
    // Compra de un plan
    $planModel = new PlanPagoModel();
    $plan = $planModel->getPlanPorId($planId);

    if (!$plan || !$plan['activo']) {
        http_response_code(404);
        echo json_encode(['error' => 'El plan seleccionado no está disponible']);
        exit();
    }

    $montoTotalUSD = (float)$plan['monto'];
    $nombreProducto = 'Plan ' . $plan['nombrePlan'] . ' - Concentrados El Gordito';
    $metadatos['idPlanPago'] = $planId;
    $metadatos['nombrePlan'] = $plan['nombrePlan'];
} elseif (!empty($items) && is_array($items)) {
    // Compra de carrito con productos/planes
    $resumenItems = [];
    foreach ($items as $item) {
        $cant = max(1, (int)($item['cantidad'] ?? 1));
        $precio = (float)($item['precio'] ?? 0);
        $montoTotalUSD += ($precio * $cant);
        $resumenItems[] = $cant . 'x ' . ($item['nombre'] ?? 'Producto');
    }
    $nombreProducto = 'Pedido Carrito: ' . implode(', ', array_slice($resumenItems, 0, 3));
    if (count($resumenItems) > 3) {
        $nombreProducto .= ' y más';
    }
    $metadatos['tipo'] = 'carrito_productos';
    $metadatos['total_items'] = count($items);
    $metadatos['items'] = $items;
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No se proporcionaron productos ni planes válidos para procesar el pago.']);
    exit();
}

$idPedidoExistente = (int)($input['idPedido'] ?? 0);
if ($idPedidoExistente > 0) {
    $metadatos['idPedido'] = $idPedidoExistente;
    $metadatos['idPedidoCreado'] = $idPedidoExistente;
    $nombreProducto = 'Pago de Pedido #' . $idPedidoExistente . ' - Concentrados El Gordito';
}

if ($montoTotalUSD <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'El monto a pagar debe ser mayor a $0.00 USD.']);
    exit();
}

// 2. Obtener Token de Acceso desde Wompi El Salvador OAuth2
$chAuth = curl_init('https://id.wompi.sv/connect/token');
curl_setopt($chAuth, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chAuth, CURLOPT_POST, true);
curl_setopt($chAuth, CURLOPT_POSTFIELDS, http_build_query([
    'grant_type' => 'client_credentials',
    'client_id' => $wompiClientId,
    'client_secret' => $wompiClientSecret,
    'audience' => 'wompi_api'
]));
curl_setopt($chAuth, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
curl_setopt($chAuth, CURLOPT_TIMEOUT, 15);

$authResponse = curl_exec($chAuth);
$authHttpCode = curl_getinfo($chAuth, CURLINFO_HTTP_CODE);
curl_close($chAuth);

$authData = json_decode($authResponse, true);
$accessToken = $authData['access_token'] ?? '';

if ($authHttpCode !== 200 || empty($accessToken)) {
    http_response_code(502);
    echo json_encode(['error' => 'No se pudo autenticar con Wompi SV. Verifica las credenciales configuradas.']);
    exit();
}

// 3. Crear Enlace de Pago en Wompi El Salvador (https://api.wompi.sv/EnlacePago)
$referencia = 'CONC-' . ($idUsuario > 0 ? $idUsuario : 'GUEST') . '-' . time();
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
    . '://' . $_SERVER['HTTP_HOST']
    . dirname(dirname($_SERVER['SCRIPT_NAME']));

$redirectUrl = rtrim($baseUrl, '/') . '/wompi/success.php?ref=' . $referencia . '&usuario=' . $idUsuario;

$bodyEnlace = [
    'identificadorEnlaceComercio' => $referencia,
    'monto' => round($montoTotalUSD, 2),
    'nombreProducto' => substr($nombreProducto, 0, 100),
    'configuracion' => [
        'urlRedirect' => $redirectUrl,
        'emailsNotificacion' => $correoCliente
    ]
];

$chEnlace = curl_init('https://api.wompi.sv/EnlacePago');
curl_setopt($chEnlace, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chEnlace, CURLOPT_POST, true);
curl_setopt($chEnlace, CURLOPT_POSTFIELDS, json_encode($bodyEnlace));
curl_setopt($chEnlace, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $accessToken
]);
curl_setopt($chEnlace, CURLOPT_TIMEOUT, 20);

$enlaceResponse = curl_exec($chEnlace);
$enlaceHttpCode = curl_getinfo($chEnlace, CURLINFO_HTTP_CODE);
curl_close($chEnlace);

$enlaceData = json_decode($enlaceResponse, true);

if ($enlaceHttpCode >= 200 && $enlaceHttpCode < 300 && !empty($enlaceData['urlEnlace'])) {
    // Registrar pago inicial en la base de datos
    try {
        $con = new Conexion();
        $conn = $con->getConnection();
        $stmtP = $conn->prepare(
            "INSERT INTO pagos (idUsuario, idPlanPago, monto, moneda, metodo_pago, estado, referencia, descripcion, fecha_hora, ip_address, user_agent, metadata)
             VALUES (?, ?, ?, 'USD', 'wompi', 'pendiente', ?, ?, NOW(), ?, ?, ?)"
        );
        if ($stmtP) {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
            $desc = 'Wompi SV Enlace: ' . ($enlaceData['idEnlace'] ?? '');
            $metaJson = json_encode($metadatos);
            $stmtP->bind_param("iidsssss", $idUsuario, $planId, $montoTotalUSD, $referencia, $desc, $ip, $ua, $metaJson);
            $stmtP->execute();
            $stmtP->close();
        }
    } catch (Exception $e) {
        // Registro de auditoría opcional
    }

    $urlCheckout = !empty($enlaceData['urlEnlaceLargo']) ? $enlaceData['urlEnlaceLargo'] : $enlaceData['urlEnlace'];

    echo json_encode([
        'url' => $urlCheckout,
        'urlEnlace' => $enlaceData['urlEnlace'],
        'qr' => $enlaceData['urlQrCodeEnlace'] ?? '',
        'idEnlace' => $enlaceData['idEnlace'] ?? '',
        'reference' => $referencia
    ]);
} else {
    http_response_code(400);
    $errorMsg = 'Error al generar el enlace de pago en Wompi SV.';
    if (!empty($enlaceData['mensajes']) && is_array($enlaceData['mensajes'])) {
        $errorMsg = implode('. ', $enlaceData['mensajes']);
    }
    echo json_encode(['error' => $errorMsg]);
}
