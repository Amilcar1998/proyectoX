<?php
include '../models/ModelPedido.php';
include 'Sesiones.php';
$pedido = new ModelPedido();
$datos = $pedido->getPedido();
$correo=$_SESSION['s2'];
$session = $pedido->getSessionEmp($correo);
if (isset($_REQUEST['detalle'])) {
	$id=$_REQUEST['id'];
	$detalle=$pedido->getDetalle($id);
	$receta=$pedido->getReceta($id);

	
}
$fechaActual = date('d/m/Y');


foreach ($session as $key) {
    $nombres = $key['nombreEmp'].'&nbsp;&nbsp;'.$key['apellido'];

}

include '../views/vistaPedidosI.php';
