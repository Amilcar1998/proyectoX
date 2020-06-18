<?php 
include '../models/ModelProduccion.php';
include 'Sesiones.php';
$prod=new ModelProduccion();
$data=$prod->getProduccion();
$correo=$_SESSION['s1'];
$session = $prod->getSessionEmp($correo);
foreach ($session as $key) {
 $idEmp=$key['idEmpleado'];
 $nombres = $key['nombreEmp'].'&nbsp;&nbsp;'.$key['apellido'];
}
  $fechaActual = date('d/m/Y');


if(isset($_REQUEST['agregar'])){
	var_dump($_REQUEST);
$id=$_REQUEST['id'];



$p=new Produccion('',$fechaActual,'activo',$_REQUEST['id'],$idEmp);
 $prod->insertar($p);
 $msj="se ha pasado a produccion exitosamente";
 $icon="success";
 $prod->alterPedido($id);
}
if(isset($_REQUEST['eliminar'])){
 $p=new Produccion($_REQUEST['produccionID'],'','activo',"",$idEmp);
 $prod->eliminar($p);
}









include '../views/vistaProduccion.php';
 ?>
 