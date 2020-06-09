<?php
include "../models/empleadoModel.php";
include "sesiones.php";
$obEmp=new EmpleadoModel();
$correo=$_SESSION["s1"];



if(isset($_REQUEST["insertar"])){
	$u = new Usuario("",$_REQUEST["txtUser"],sha1('123456'),$_REQUEST['txtCargo']);
	$obEmp->insertarUsuario($u);
        $user=$_REQUEST["txtUser"];
		$usuario=$obEmp->getUser($user);
		 foreach ($usuario as $us)  {
		  	$id = $us["idUsuario"];
            }
 $e= new Empleado($_REQUEST['txtIdEmpleado'],$_REQUEST['txtNombres'],$_REQUEST['txtApellidos'],$_REQUEST['txtGenero'],$_REQUEST['txtCargo'],$id);
 $obEmp->insertarEmpleado($e);

}
if(isset($_REQUEST["modificar"])){
$e= new Empleado($_REQUEST['txtIdEmpleado'],$_REQUEST['txtNombres'],$_REQUEST['txtApellidos'],$_REQUEST['txtGenero'],$_REQUEST['txtCargo'],$_REQUEST["txtUser"]);
      $obEmp->modificarEmpleado($e);
}
if(isset($_REQUEST["eliminar"])){
 $e=new Empleado($_REQUEST['txtIdEmpleado'],$_REQUEST['txtNombres'],$_REQUEST['txtApellidos'],$_REQUEST['txtGenero'],$_REQUEST['txtCargo'],$_REQUEST["txtUser"]);


 $emp = $_REQUEST["txtIdEmpleado"];
      $id=$obEmp->obtenerID($emp);
     foreach ($id as $i) {
     	$usuario=$i["idUsuario"];

     }
 $obEmp->eliminarEmpleado($e);
	 if(isset($usuario)){
	  $obEmp->eliminarUsuario($usuario);

		}
 }

  $session = $obEmp->getSessionEmp($correo);
  $datos=$obEmp->getEmpleado();
  $puesto=$obEmp->getCargo();
  foreach ($session as $key) {
 $nombres = $key['nombreEmp'].'&nbsp;&nbsp;'.$key['apellido'];
      
   }
  
  

include "../views/vistaEmpleado.php";

