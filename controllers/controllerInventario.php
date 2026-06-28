<?php
require dirname(__DIR__) . '/controllers/vendor/autoload.php';
include '../models/ModelInventario.php';
include 'sesiones.php';

$dao = new ModelInventario();

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

$materiasPrimas = $dao->getMateriasPrimas();

if (isset($_REQUEST["btnGuardar"])) {
    $obj = new Inventario();
    $obj->setIdInventario($_REQUEST["txtId"] ?? '');
    $obj->setIdMateriaPrima($_REQUEST["txtIdMateriaPrima"] ?? '');
    $obj->setExistencias($_REQUEST["txtExistencias"] ?? '');
    $obj->setIdDetalleCompra($_REQUEST["txtDetalleCompra"] ?? '');
    $dao->InsertarInventario($obj);
    $tabla = $dao->getTabla();
} else if (isset($_REQUEST["btnModificar"])) {
    $obj = new Inventario();
    $obj->setIdInventario($_REQUEST["txtId"] ?? '');
    $obj->setIdMateriaPrima($_REQUEST["txtIdMateriaPrima"] ?? '');
    $obj->setExistencias($_REQUEST["txtExistencias"] ?? '');
    $obj->setIdDetalleCompra($_REQUEST["txtDetalleCompra"] ?? '');
    $dao->setInventario($obj);
    $tabla = $dao->getTabla();
} else if (isset($_REQUEST["btnEliminar"])) {
    $dao->eliminar($_REQUEST["txtId"] ?? 0);
    $tabla = $dao->getTabla();
}

include '../views/vistaInventario.php';
?>
