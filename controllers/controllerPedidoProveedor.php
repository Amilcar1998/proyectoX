<?php
include '../models/DAOPedidoProveedor.php';
include 'Sesiones.php';
$pedidoProv = new DAOPedidoProveedor();
$tabla=$pedidoProv->getTabla();
/*$correo=$_SESSION['s1'];
$session = $pedidoProv->getSessionEmp($correo);

foreach ($session as $key) {
    $nombres = $key['nombreEmp'].'&nbsp;&nbsp;'.$key['apellido'];

}*/
if(isset($_REQUEST["btnGuardar"])){
	$pedip = new PedidoProveedor($_REQUEST["txtIdPe"],$_REQUEST["txtIdPro"],$_REQUEST["txtIdEmp"],$_REQUEST["txtIdMp"],$_REQUEST["txtFec"],$_REQUEST["txtCan"],$_REQUEST["txtMon"],$_REQUEST["txtPre"]);
	$pedidoProv->Insertar($pedip);
	$tabla=$pedidoProv->getTabla();
}else if(isset($_REQUEST["btnModificar"])){
	$pedip = new PedidoProveedor($_REQUEST["txtIdPe"],$_REQUEST["txtIdPro"],$_REQUEST["txtIdEmp"],$_REQUEST["txtIdMp"],$_REQUEST["txtFec"],$_REQUEST["txtCan"],$_REQUEST["txtMon"],$_REQUEST["txtPre"]);
	$pedidoProv->modificar($pedip);
	$tabla=$pedidoProv->getTabla();
}else if(isset($_REQUEST["btnEliminar"])){
	$idPed = new PedidoProveedor($_REQUEST["txtIdPe"]);
	$tabla=$pedidoProv->getTabla();
}
include '../views/vistaPedidoProveedor.php';
?>