<?php
include '../models/ModelInventario.php';
include 'Sesiones.php';
$inventario = new ModelInventario();
$datos=$inventario->getInventario();
$correo=$_SESSION['s1'];
$session = $inventario->getSessionEmp($correo);

foreach ($session as $key) {
    $nombres = $key['nombreEmp'].'&nbsp;&nbsp;'.$key['apellido'];

}

if(isset($_REQUEST["btnGuardar"])){
	$inv= new Inventario($_REQUEST["txtId"],$_REQUEST["txtIdMateriaPrima"],$_REQUEST["txtExistencias"],$_REQUEST["txtDetalleCompra"]);
	$inventario->InsertarInventario($inv);
	$msj="se ha Agregado el registro exitosamente";
    $icon="success";
}

    
include '../views/vistaInventario.php';
