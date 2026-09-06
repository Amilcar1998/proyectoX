<?php
require_once __DIR__ . '/../models/ModelPedido.php';
require_once __DIR__ . '/sesiones.php';
$pedido = new ModelPedido();
$datos = $pedido->getPedido();
$correo = $_SESSION['s2'] ?? '';
$session = !empty($correo) ? $pedido->getSessionEmp($correo) : [];
$nombres = $pedido->obtenerNombreUsuario();
$fechaActual = date('d/m/Y');

if (!empty($session) && is_array($session)) {
    foreach ($session as $key) {
        $nombreEmp = $key['nombreEmp'] ?? '';
        $apellido = $key['apellido'] ?? '';
        $nombres = trim($nombreEmp . ' ' . $apellido);
    }
}
$id = null;
$detalle = [];
$receta = [];

if (isset($_REQUEST['detalle']) || isset($_REQUEST['receta'])) {
    $id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : (int)($_REQUEST['idDetalle'] ?? 0);
    if ($id > 0) {
        $detalle = $pedido->obtenerDetallePedido($id);
        $receta = $pedido->obtenerRecetaPorPedido($id);
    }
}

include __DIR__ . '/../views/vistaPedidosI.php';
