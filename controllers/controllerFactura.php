<?php
require dirname(__DIR__) . '/controllers/vendor/autoload.php';
include '../models/ModelFactura.php';
include 'sesiones.php';

$dao = new ModelFactura();

$correo = $_SESSION['s1'] ?? ($_SESSION['s2'] ?? '');
$idEmpresaSesion = (int)($_SESSION['idEmpresa'] ?? 1);
$esSuperUsuario = !empty($_SESSION['esSuperUsuario']) || (($correo ?? '') === 'amilcar199819@gmail.com');
$idEmpresaFiltro = $esSuperUsuario ? 0 : $idEmpresaSesion;

$session = [];
$nombres = '';

if ($correo) {
    $session = $dao->getSessionEmp($correo);
    if (!empty($session)) {
        $nombres = $session[0]['nombreEmp'] . ' ' . $session[0]['apellido'];
    }
}

if (isset($_REQUEST["btnGuardar"])) {
    $obj = new Factura();
    $obj->setIdFacturaMP($_REQUEST["txtIdFactura"] ?? 0);
    $obj->setNumeroFac($_REQUEST["txtNumeroFac"] ?? '');
    $obj->setMonto($_REQUEST["txtMonto"] ?? 0);
    $obj->setFecha($_REQUEST["txtFecha"] ?? '');
    $obj->setIdProveedor($_REQUEST["txtIdProveedor"] ?? 0);
    $obj->setIdEmpleado($_REQUEST["txtIdEmpleado"] ?? 0);
    $dao->insertar($obj, $idEmpresaSesion);
} else if (isset($_REQUEST["btnModificar"])) {
    $obj = new Factura();
    $obj->setIdFacturaMP($_REQUEST["txtIdFactura"] ?? 0);
    $obj->setNumeroFac($_REQUEST["txtNumeroFac"] ?? '');
    $obj->setMonto($_REQUEST["txtMonto"] ?? 0);
    $obj->setFecha($_REQUEST["txtFecha"] ?? '');
    $obj->setIdProveedor($_REQUEST["txtIdProveedor"] ?? 0);
    $obj->setIdEmpleado($_REQUEST["txtIdEmpleado"] ?? 0);
    $dao->modificar($obj);
} else if (isset($_REQUEST["btnEliminar"])) {
    $dao->eliminar($_REQUEST["txtIdFactura"] ?? 0);
}

$tabla = $dao->getTabla($idEmpresaFiltro);
$proveedores = $dao->getProveedores($idEmpresaFiltro);
$empleados = $dao->getEmpleados($idEmpresaFiltro);

include "../views/vistaFactura.php";
?>