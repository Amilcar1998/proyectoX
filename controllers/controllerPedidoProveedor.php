<?php
require dirname(__DIR__) . '/controllers/vendor/autoload.php';
include '../models/ModelPedidoProveedorMVC.php';
include 'sesiones.php';

$pedidoProv = new ModelPedidoProveedor();

$pedidoDTO = new PedidoProveedor();

$correo = $_SESSION['s1'] ?? ($_SESSION['s2'] ?? '');
$idEmpresaSesion = (int)($_SESSION['idEmpresa'] ?? 1);
$esSuperUsuario = !empty($_SESSION['esSuperUsuario']) || (($correo ?? '') === 'amilcar199819@gmail.com');
$idEmpresaFiltro = $esSuperUsuario ? 0 : $idEmpresaSesion;

$session = $pedidoProv->getSessionEmp($correo);

$nombres = '';
if (!empty($session)) {
    $nombres = trim(($session[0]['nombreEmp'] ?? '') . ' ' . ($session[0]['apellido'] ?? ''));
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
    $pedip->setIdEmpresa($idEmpresaSesion);
    $pedidoProv->insertar($pedip, $idEmpresaSesion);
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
}else if(isset($_REQUEST["btnEliminar"])){
    $pedidoProv->eliminar($_REQUEST["txtIdPe"]);
}

$tabla = $pedidoProv->getTabla($idEmpresaFiltro);
$proveedores = $pedidoProv->getProveedores($idEmpresaFiltro);
$empleados = $pedidoProv->getEmpleados($idEmpresaFiltro);
$materiasPrimas = $pedidoProv->getMateriasPrimas($idEmpresaFiltro);

include '../views/vistaPedidoProveedor.php';
?>
