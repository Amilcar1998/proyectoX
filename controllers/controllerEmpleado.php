<?php
include "../models/empleadoModel.php";
$obEmp=new EmpleadoModel();


if(isset($_REQUEST["insertar"])){
 $e=new Empleado($_REQUEST["txtIdEmpleado"],$_REQUEST["txtNombreE"],$_REQUEST["txtApellidos"],$_REQUEST["txtCorreo"],$REQUEST["txtGenero"],$_REQUEST["idRol"],$_REQUEST["txtPass"],$_REQUEST["txtCargo"]);
    $obEmp->insertarEmpleado($e);
}
  $datos=$obEmp->getEmpleado();
  $data=$obEmp->getRol();
  $puesto=$obEmp->getCargo();


include "../views/vistaEmpleado.php";

