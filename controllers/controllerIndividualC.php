<?php 
include "../models/modelClienteIn.php";
include 'Sesiones.php';

$cli= new ModelClienteIn();
$correo=$_SESSION['c1'];
$dataCliente=$cli->getClienteIndividual($correo);
foreach ($dataCliente as $fila) {
	$idCliente=$fila['idCliente'];
	$nombre = $fila['NombreCliente'];
	$apellido=$fila['apellidosCliente'];
}
$fechaActual = date('d/m/Y');

if (isset($_REQUEST['pedidos'])) {
	$pedido=$cli->getIdProd($idCliente);
	foreach($pedido as $key){
		$idPedid=$key['idPedido'];
	}
	$detalleRes=$cli->getResProducto($idPedid);
	var_dump($detalleRes);
	

}
if (isset($_REQUEST["agregarP"])) {
	$p=new Pedidos('',$fechaActual,$idCliente,'1');
	$cli->insertarPedido($p);


}



$dataPedido=$cli->getAll($idCliente);
//$receta=$cli->getReceta();
$nombres=$nombre." ".$apellido;
include '../views/individualCliente.php';


 ?>