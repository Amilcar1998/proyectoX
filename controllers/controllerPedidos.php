<?php
include '../models/ModelPedido.php';
include 'sesiones.php';
$pedido = new ModelPedido();
$datos = $pedido->getPedido();
$correo=$_SESSION['s1'] ?? '';

$session = $pedido->getSessionEmp($correo);
$nombres = '';
foreach ($session as $key) {
    $nombres = trim(($key['nombreEmp'] ?? '') . ' ' . ($key['apellido'] ?? ''));
}
$id = null;
$detalle = [];
$receta = null;

if (isset($_REQUEST['detalle'])) {
    $id = (int)($_REQUEST['id'] ?? 0);
    if ($id > 0) {
        $detalle = $pedido->obtenerDetallePedido($id);
    }
} elseif (isset($_REQUEST['receta'])) {
    $id = (int)($_REQUEST['idDetalle'] ?? 0);
    $idReceta = (int)($_REQUEST['id'] ?? 0);
    if ($id > 0) {
        $detalle = $pedido->obtenerDetallePedido($id);
    }
    if ($idReceta > 0) {
        $receta = $pedido->obtenerReceta($idReceta);
    }
}

include '../views/vistaPedidos.php';
