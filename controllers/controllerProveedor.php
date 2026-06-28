<?php
include '../models/ModelProveedor.php';
include "sesiones.php";
$prov =new 	ModelProveedor();

if(isset($_REQUEST["btnGuardar"])){
	$p= new Proveedor($_REQUEST["txtId"],$_REQUEST["txtNombre"],$_REQUEST["txtContacto"],$_REQUEST["txtNit"],$_REQUEST["txtCorreo"],$_REQUEST["txtTelefono"]);
	$prov->insertar($p);
	$msj="se ha Agregado el registro exitosamente";
    $icon="success";
}
if(isset($_REQUEST["btnEliminar"])){
    $p= new Proveedor($_REQUEST["txtId"],$_REQUEST["txtNombre"],$_REQUEST["txtContacto"],$_REQUEST["txtNit"],$_REQUEST["txtCorreo"],$_REQUEST["txtTelefono"]);
	$prov->eliminar($p);
	$msj="se ha Eliminado el registro exitosamente";
    $icon="success";
    
}
if(isset($_REQUEST["btnModificar"])){
   $p= new Proveedor($_REQUEST["txtId"],$_REQUEST["txtNombre"],$_REQUEST["txtContacto"],$_REQUEST["txtNit"],$_REQUEST["txtCorreo"],$_REQUEST["txtTelefono"]);
	$prov->modificar($p);
	$msj="se ha Modificado el registro exitosamente";
 	$icon="success";
}

$tab = $prov->getTabla();
$session = $prov->getSessionEmp($_SESSION['s1'] ?? '');
$nombres = '';
foreach ($session as $key) {
    $nombres = $key['nombreEmp'].'&nbsp;&nbsp;'.$key['apellido'];
}


include "../views/vistaProveedor.php";

?>
