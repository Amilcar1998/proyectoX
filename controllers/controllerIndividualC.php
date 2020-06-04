<?php 
include "../models/modelClienteIn.php";

$cli= new ModelClienteIn();






$dataCliente=$cli->getClienteIndividual();
foreach ($dataCliente as $fila) {
	$nombre = $fila['NombreCliente'];
	$apellido=$fila['apellidosCliente'];
}


include '../views/individualCliente.php';


 ?>