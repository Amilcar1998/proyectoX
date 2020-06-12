<?php
include '../models/ModelPedido.php';
include 'Sesiones.php';
$pedido = new ModelPedido();
$datos = $pedido->getPedido();
$correo=$_SESSION['s1'];

$session = $pedido->getSessionEmp($correo);
if (isset($_REQUEST['detalle'])) {
	$id=$_REQUEST['id'];
	$detalle=$pedido->getDetalle($id);
	$receta=$pedido->getReceta($id);

	
}

foreach ($session as $key) {
    $nombres = $key['nombreEmp'].'&nbsp;&nbsp;'.$key['apellido'];

}

include '../views/vistaPedidos.php';



