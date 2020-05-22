<?php
include "../models/empleadoModel.php";
include "sesiones.php";
$obEmp=new EmpleadoModel();


if(isset($_REQUEST["insertar"])){
 $e= new Empleado($_REQUEST['txtIdEmpleado'],$_REQUEST['txtNombreE'],$_REQUEST['txtApellidos'],$_REQUEST['txtGenero'],$_REQUEST['txtCargo'],$_REQUEST["txtUser"]);
      $obEmp->insertarEmpleado($e);
}
$session = $obEmp->getSessionEmp();
  $datos=$obEmp->getEmpleado();
  $puesto=$obEmp->getCargo();
  $usuario=$obEmp->getUser();


include "../views/vistaEmpleado.php";

