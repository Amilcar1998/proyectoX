<?php 
include '../models/ModelProduccion.php';
include 'Sesiones.php';
$prod=new ModelProduccion();
$data=$prod->getProduccion();
$session = $prod->getSessionEmp();
foreach ($session as $key) {
 $idEmp=$key['idEmpleado'];
 $nombres = $key['nombreEmp'].'&nbsp;&nbsp;'.$key['apellido'];
}
  $fechaActual = date('d/m/Y');


if(isset($_REQUEST['agregar'])){
$p=new Produccion('',$fechaActual,'activo',$_REQUEST['id'],$idEmp);
 $prod->insertar($p);
}
if(isset($_REQUEST['eliminar'])){
 $p=new Produccion($_REQUEST['produccionID'],'','activo',"",$idEmp);
 $prod->eliminar($p);
}









include '../views/vistaProduccion.php';
 ?>
 