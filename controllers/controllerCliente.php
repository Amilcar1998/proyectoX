<?php
include '../models/ClienteModel.php';
include "sesiones.php";

$cliente = new ClienteModel();

if(isset($_REQUEST["insertar"])) {
	$e = new Cliente($_REQUEST["idCliente"],$_REQUEST["nombreC"],$_REQUEST["apellidoC"],$_REQUEST["telefonoC"],$_REQUEST["edadC"],$_REQUEST["generoC"],$_REQUEST["usuarioC"]);
	$cliente->agregarCliente($e);
}
if(isset($_REQUEST["modificar"])) {
	$e = new Cliente($_REQUEST["idCliente"],$_REQUEST["nombreC"],$_REQUEST["apellidoC"],$_REQUEST["telefonoC"],$_REQUEST["edadC"],$_REQUEST["generoC"],$_REQUEST["usuarioC"]);
	$cliente->modificarCliente($e);
}
if(isset($_REQUEST["eliminar"])) {
	$e = new Cliente($_REQUEST["idCliente"],$_REQUEST["nombreC"],$_REQUEST["apellidoC"],$_REQUEST["telefonoC"],$_REQUEST["edadC"],$_REQUEST["generoC"],$_REQUEST["usuarioC"]);
	$cliente->eliminarCliente($e);
}

$user=$cliente->getUser();
$Rcliente = $cliente->getCliente();


include "../views/vistaCliente.php";

