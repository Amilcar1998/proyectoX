<?php
require_once __DIR__ . '/../models/ModelPedido.php';
require_once __DIR__ . '/sesiones.php';

$pedido = new ModelPedido();

// Endpoint AJAX para obtener detalle completo del pedido
if (isset($_REQUEST['accion']) && $_REQUEST['accion'] === 'obtenerDetalle') {
    header('Content-Type: application/json; charset=utf-8');
    $idPedido = (int)($_REQUEST['idPedido'] ?? 0);
    if ($idPedido <= 0) {
        echo json_encode(['success' => false, 'mensaje' => 'ID de pedido inválido']);
        exit;
    }
    $detalle = $pedido->obtenerDetalleCompleto($idPedido);
    if (empty($detalle)) {
        echo json_encode(['success' => false, 'mensaje' => 'No se encontraron detalles para este pedido']);
        exit;
    }
    echo json_encode(['success' => true, 'data' => $detalle]);
    exit;
}

$correo = $_SESSION['s1'] ?? ($_SESSION['s2'] ?? '');
$idEmpresaSesion = (int)($_SESSION['idEmpresa'] ?? 1);
$esSuperUsuario = !empty($_SESSION['esSuperUsuario']) || (($correo ?? '') === 'amilcar199819@gmail.com');
$idEmpresaFiltro = $esSuperUsuario ? 0 : $idEmpresaSesion;

$datos = $pedido->obtenerPedidos($idEmpresaFiltro);

$session = !empty($correo) ? $pedido->obtenerSesionEmpleado($correo) : [];
$nombres = '';
foreach ($session as $key) {
    $nombres = trim(($key['nombreEmp'] ?? '') . ' ' . ($key['apellido'] ?? ''));
}

include __DIR__ . '/../views/vistaPedidos.php';

