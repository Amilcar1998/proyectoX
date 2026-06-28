<?php
require dirname(__DIR__) . '/controllers/vendor/autoload.php';
include '../models/ModelPedidoProveedorMVC.php';
include 'sesiones.php';

$pedidoProv = new ModelPedidoProveedor();

$pedidoDTO = new PedidoProveedor();
$tabla = $pedidoProv->getTabla();

$correo = $_SESSION['s1'] ?? '';
$session = $pedidoProv->getSessionEmp($correo);

$nombres = '';
if (!empty($session)) {
    $nombres = $session[0]['nombreEmp'] . '&nbsp;&nbsp;' . $session[0]['apellido'];
}

if(isset($_REQUEST["btnGuardar"])){
    $pedip = new PedidoProveedor();
    $pedip->setIdProveedor($_REQUEST["txtIdPro"]);
    $pedip->setIdEmpleado($_REQUEST["txtIdEmp"]);
    $pedip->setIdMateriaPrima($_REQUEST["txtIdMp"]);
    $pedip->setFecha($_REQUEST["txtFec"]);
    $pedip->setCantidadMP($_REQUEST["txtCan"]);
    $pedip->setMonto($_REQUEST["txtMon"]);
    $pedip->setPrecioMP($_REQUEST["txtPre"]);
    $pedidoProv->insertar($pedip);
    $tabla = $pedidoProv->getTabla();
}else if(isset($_REQUEST["btnModificar"])){
    $pedip = new PedidoProveedor();
    $pedip->setIdPedido($_REQUEST["txtIdPe"]);
    $pedip->setIdProveedor($_REQUEST["txtIdPro"]);
    $pedip->setIdEmpleado($_REQUEST["txtIdEmp"]);
    $pedip->setIdMateriaPrima($_REQUEST["txtIdMp"]);
    $pedip->setFecha($_REQUEST["txtFec"]);
    $pedip->setCantidadMP($_REQUEST["txtCan"]);
    $pedip->setMonto($_REQUEST["txtMon"]);
    $pedip->setPrecioMP($_REQUEST["txtPre"]);
    $pedidoProv->modificar($pedip);
    $tabla = $pedidoProv->getTabla();
}else if(isset($_REQUEST["btnEliminar"])){
    $pedidoProv->eliminar($_REQUEST["txtIdPe"]);
    $tabla = $pedidoProv->getTabla();
}
include '../views/vistaPedidoProveedor.php';
?>
