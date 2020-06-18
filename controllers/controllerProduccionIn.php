<?php 
include '../models/ModelProduccion.php';
include 'Sesiones.php';
$prod=new ModelProduccion();
$data=$prod->getProduccion();
$correo=$_SESSION['s2'];
$session = $prod->getSessionEmp($correo);
foreach ($session as $key) {
 $idEmp=$key['idEmpleado'];
 $nombres = $key['nombreEmp'].'&nbsp;&nbsp;'.$key['apellido'];
}
  $fechaActual = date('d/m/Y');


if(isset($_REQUEST['agregar'])){
	var_dump($_REQUEST);
$p=new Produccion('',$fechaActual,'activo',$_REQUEST['id'],$idEmp);
 $prod->insertar($p);
 $msj="agregado correctamente a produccion";
 $icon='success';
 $prod->alterPedido();
 //aqui iria la alteracion de la tabla jajajajaajajaja
}
if(isset($_REQUEST['eliminar'])){
 $p=new Produccion($_REQUEST['produccionID'],'','activo',"",$idEmp);
 $prod->eliminar($p);
}









include '../views/vistaProduccionIn.php';
 ?>
 