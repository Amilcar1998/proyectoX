<?php
include '../models/modelProveedor.php';
$prov =new 	ModelProveedor();

if(isset($_REQUEST["btnGuardar"])){
	$p= new Proveedor($_REQUEST["txtId"],$_REQUEST["txtNombre"],$_REQUEST["txtContacto"],$_REQUEST["txtNit"],$_REQUEST["txtCorreo"],$_REQUEST["txtTelefono"]);
	$prov->insertar($p);
}
if(isset($_REQUEST["btnEliminar"])){
    $p= new Proveedor($_REQUEST["txtId"],$_REQUEST["txtNombre"],$_REQUEST["txtContacto"],$_REQUEST["txtNit"],$_REQUEST["txtCorreo"],$_REQUEST["txtTelefono"]);
	$prov->eliminar($p);
    
}
if(isset($_REQUEST["btnModificar"])){
   $p= new Proveedor($_REQUEST["txtId"],$_REQUEST["txtNombre"],$_REQUEST["txtContacto"],$_REQUEST["txtNit"],$_REQUEST["txtCorreo"],$_REQUEST["txtTelefono"]);
	$prov->modificar($p);
}

$tab = $prov->getTabla();


include "../views/vistaProveedor.php";

?>