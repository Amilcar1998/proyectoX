<?php
include '../models/ModelProveedor.php';
include "sesiones.php";
$prov =new 	ModelProveedor();

$correo = $_SESSION['s1'] ?? ($_SESSION['s2'] ?? '');
$idEmpresaSesion = (int)($_SESSION['idEmpresa'] ?? 1);
$esSuperUsuario = !empty($_SESSION['esSuperUsuario']) || (($correo ?? '') === 'amilcar199819@gmail.com');
$idEmpresaFiltro = $esSuperUsuario ? 0 : $idEmpresaSesion;

if(isset($_REQUEST["btnGuardar"])){
	$p= new Proveedor($_REQUEST["txtId"],$_REQUEST["txtNombre"],$_REQUEST["txtContacto"],$_REQUEST["txtNit"],$_REQUEST["txtCorreo"],$_REQUEST["txtTelefono"]);
	$prov->insertar($p, $idEmpresaSesion);
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

$tab = $prov->getTabla($idEmpresaFiltro);
$session = $prov->getSessionEmp($correo);
$nombres = '';
foreach ($session as $key) {
    $nombres = trim(($key['nombreEmp'] ?? '') . ' ' . ($key['apellido'] ?? ''));
}

include "../views/vistaProveedor.php";

?>
