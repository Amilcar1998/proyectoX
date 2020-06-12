<?php 
include "../models/ModelUser.php";
include "Sesiones.php";
$objU= new ModelUser();

if (isset($_REQUEST["empleado"])) {
	$userEmp=$objU->getUsuario();
	$nombre='Datos de Empleado';
	$thead ="<th>usuario</th><th>Nombre</th><th>Usuario</th><th>Contraseña</th><th>Accion</th>";
}
if (isset($_REQUEST["cliente"])) {
	$userCli = $objU->getUsuarioCli();
	$thead ="<th>usuario</th><th>Nombre</th><th>Usuario</th><th>Contraseña</th><th>Accion</th>";
	$nombre = "Datos Cliente";
	
}
if (isset($userEmp) !=true && isset($userCli)!=true) {
	$m=$objU->getUsuarios();
	$nombre='Datos de Usuario';
	$thead ="<th>Id usuario</th><th>Nombre Usuario</th><th>Contraseña</th><th>Rol</th><th>Accion</th>";

}
if(isset($_REQUEST["modificar"])){
	$u=new Usuario($_REQUEST["txtUsuario"],$_REQUEST["txtUser"],sha1($_REQUEST["txtPass"]),$_REQUEST["txtRol"],);
	$objU->modificarUsuario($u);
	 $msj="se ha Modificado el registro exitosamente";
	 $icon="success";


	
}



$rol = $objU->getRol();
$session = $objU->getSessionEmp();
foreach ($session as $key) {
 $nombres = $key['nombreEmp'].'&nbsp;&nbsp;'.$key['apellido'];
 }

include "../views/vistaUsuarios.php";

 ?>