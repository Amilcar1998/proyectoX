<?php
include '../models/ModelPedido.php';
include 'sesiones.php';
$pedido = new ModelPedido();
$datos = $pedido->getPedido();
$correo=$_SESSION['s1'] ?? '';

$session = $pedido->getSessionEmp($correo);
$nombres = '';
foreach ($session as $key) {
    $nombres = $key['nombreEmp'].'&nbsp;&nbsp;'.$key['apellido'];
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



