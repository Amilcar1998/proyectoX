<?php
include '../models/ClienteModel.php';
include "sesiones.php";

$cliente = new ClienteModel();

if(isset($_REQUEST["insertar"])) {
	$u = new Usuario("",$_REQUEST["usuarioC"],sha1('123456'),'2');
	$cliente->getAddUs($u);
        $user=$_REQUEST["usuarioC"];
		$usuario=$cliente->getUser($user);
		 foreach ($usuario as $us)  {
		  	$id = $us["idUsuario"];
            }
    

	$e = new Cliente($_REQUEST["idCliente"],$_REQUEST["nombreC"],$_REQUEST["apellidoC"],$_REQUEST["telefonoC"],$_REQUEST["edadC"],$_REQUEST["generoC"],$id);
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
$session = $cliente->getSessionEmp();

foreach ($session as $key) {
 $nombres = $key['nombreEmp'].'&nbsp;&nbsp;'.$key['apellido'];
      
   }


include "../views/vistaCliente.php";

