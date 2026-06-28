<?php
require dirname(__DIR__) . '/controllers/vendor/autoload.php';
include '../models/ModelFactura.php';
include 'sesiones.php';

$dao = new ModelFactura();

$tabla = $dao->getTabla();
$correo = $_SESSION['s1'] ?? '';
$session = [];
$nombres = '';

if ($correo) {
    $session = $dao->getSessionEmp($correo);
    if (!empty($session)) {
        $nombres = $session[0]['nombreEmp'] . ' ' . $session[0]['apellido'];
    }
}

$proveedores = $dao->getProveedores();
$empleados = $dao->getEmpleados();

if (isset($_REQUEST["btnGuardar"])) {
    $obj = new Factura();
    $obj->setIdFacturaMP($_REQUEST["txtIdFactura"] ?? 0);
    $obj->setNumeroFac($_REQUEST["txtNumeroFac"] ?? '');
    $obj->setMonto($_REQUEST["txtMonto"] ?? 0);
    $obj->setFecha($_REQUEST["txtFecha"] ?? '');
    $obj->setIdProveedor($_REQUEST["txtIdProveedor"] ?? 0);
    $obj->setIdEmpleado($_REQUEST["txtIdEmpleado"] ?? 0);
    $dao->insertar($obj);
    $tabla = $dao->getTabla();
} else if (isset($_REQUEST["btnModificar"])) {
    $obj = new Factura();
    $obj->setIdFacturaMP($_REQUEST["txtIdFactura"] ?? 0);
    $obj->setNumeroFac($_REQUEST["txtNumeroFac"] ?? '');
    $obj->setMonto($_REQUEST["txtMonto"] ?? 0);
    $obj->setFecha($_REQUEST["txtFecha"] ?? '');
    $obj->setIdProveedor($_REQUEST["txtIdProveedor"] ?? 0);
    $obj->setIdEmpleado($_REQUEST["txtIdEmpleado"] ?? 0);
    $dao->modificar($obj);
    $tabla = $dao->getTabla();
} else if (isset($_REQUEST["btnEliminar"])) {
    $dao->eliminar($_REQUEST["txtIdFactura"] ?? 0);
    $tabla = $dao->getTabla();
}

include "../views/vistaFactura.php";
?>