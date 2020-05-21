<?php
include "../models/empleadoModel.php";
$obEmp=new EmpleadoModel();

if(isset($_REQUEST["insertar"])){

 $e= new Empleado($_REQUEST['txtIdEmpleado'],$_REQUEST['txtNombreE'],$_REQUEST['txtApellidos'],$_REQUEST['txtCorreo'],$_REQUEST['txtGenero'],$_REQUEST['idRol'],sha1($_REQUEST['txtPass']),$_REQUEST['txtCargo']);
      $obEmp->insertarEmpleado($e);
}
if(isset($_REQUEST["modificar"])){
 $e= new Empleado($_REQUEST['txtIdEmpleado'],$_REQUEST['txtNombreE'],$_REQUEST['txtApellidos'],$_REQUEST['txtCorreo'],$_REQUEST['txtGenero'],$_REQUEST['idRol'],sha1($_REQUEST['txtPass']),$_REQUEST['txtCargo']);
      $obEmp->modificarEmpleado($e);

}
if(isset($_REQUEST["eliminar"])){
 $e= new Empleado($_REQUEST['txtIdEmpleado'],$_REQUEST['txtNombreE'],$_REQUEST['txtApellidos'],$_REQUEST['txtCorreo'],$_REQUEST['txtGenero'],$_REQUEST['idRol'],sha1($_REQUEST['txtPass']),$_REQUEST['txtCargo']);
      $obEmp->eliminarEmpleado($e);

}
  $datos=$obEmp->getEmpleado();
  $data=$obEmp->getRol();
  $puesto=$obEmp->getCargo();


include "../views/vistaEmpleado.php";

