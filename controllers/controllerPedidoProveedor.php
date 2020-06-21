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
	$msj="se ha Agregado el registro exitosamente";
	$icon="success";
	$tabla=$pedidoProv->getTabla();
}else if(isset($_REQUEST["btnModificar"])){
	$inv = new Inventario($_REQUEST["txtId"],$_REQUEST["txtIdMateriaPrima"],$_REQUEST["txtExistencias"],$_REQUEST["txtDetalleCompra"]);
	$inventario->setInventario($inv);
	$msj="Se ha mofificado exitosamente";
	$icon="success";
	$datos=$inventario->getInventario();
}else if(isset($_REQUEST["btnEliminar"])){
	$inv = new Inventario($_REQUEST["txtId"],$_REQUEST["txtIdMateriaPrima"],$_REQUEST["txtExistencias"],$_REQUEST["txtDetalleCompra"]);
	$inventario->eliminarInventario($inv);
	$msj="Se ha eliminado exitosamente";
	$icon="success";
	$datos=$inventario->getInventario();
}
include '../views/vistaPedidoProveedor.php';
?>