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
$id=$_REQUEST['id'];
$pedido=$prod->getPedido($id);

foreach ($pedido as $ask) {
	$receta=$ask['idReceta'];
	$Nreceta=$ask['nombreReceta'];
	$unidades=$ask['total_Unidades'];
	$Datareceta=$prod->getObtenerReceta($receta);
	foreach ($Datareceta as $ksa) {
		$inventario=$ksa['idInventario'];
		$existencias=$ksa['Existencias'];
		$total=	$unidades * $usa=$ksa['quintal'];
		$r=$prod->validarProduccion($inventario,$total);
		if($r==1){
			$Inv=$existencias-$total;
			$prod->actualizarInventario($Inv,$inventario);
		}
	}

}

if($r==1){
$p=new Produccion('',$fechaActual,'activo',$_REQUEST['id'],$idEmp);
 $prod->insertar($p);
 $msj="se ha pasado a produccion exitosamente";
 $icon="success";
 $prod->alterPedido($id);
}
 if($r==0){
	$msj="Error en la operacion, por falta de materia prima";
	$icon='warning';
		}
}



if(isset($_REQUEST['eliminar'])){
 $p=new Produccion($_REQUEST['produccionID'],'','activo',"",$idEmp);
 $prod->eliminar($p);
}





if(isset($_REQUEST['Pterminar'])){
	
	$id=$_REQUEST['idPedido'];
	$prod->TerminarPedido($id);
	$idProduccion=$_REQUEST['idPr'];
	$prod->alterProduccion($idProduccion);
	$msj='pedido terminado con exito';
	$icon='success';
}









include '../views/vistaProduccion.php';
 ?>
 