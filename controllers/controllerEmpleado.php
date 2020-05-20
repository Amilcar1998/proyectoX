<?php
include "../models/empleadoModel.php";
var_dump($_REQUEST);
$obEmp=new EmpleadoModel();
if(isset($_REQUEST["insertar"])){

    $e=new Empleado($_REQUEST["txtIdEmpleado"],$_REQUEST["txtNombre"],$_REQUEST["txtApellidos"],$_REQUEST["txtCorreo"],$REQUEST["txtGenero"],$_REQUEST["idRol"],$_REQUEST["txtPass"],$_REQUEST["txtCargo"]);
    $obEmp->insertarEmpleado($e);
}
  $datos=$obEmp->getEmpleado();
  $data=$obEmp->getRol();


include "../views/vistaEmpleado.php";

