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



}
if(isset($_REQUEST['receta'])){
	$id=$_REQUEST['idDetalle'];
	$idReceta=$_REQUEST['id'];
    $detalle=$pedido->getDetalle($id);
	$receta=$pedido->getReceta($idReceta);


}

foreach ($session as $key) {
    $nombres = $key['nombreEmp'].'&nbsp;&nbsp;'.$key['apellido'];

}

include '../views/vistaPedidos.php';



