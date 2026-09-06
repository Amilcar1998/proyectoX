<?php
require dirname(__DIR__) . '/controllers/vendor/autoload.php';
require_once __DIR__ . '/../models/ModelDetalleCompra.php';
require_once __DIR__ . '/sesiones.php';

$dao = new ModelDetalleCompra();

$tabla = $dao->obtenerTabla();
$correo = $_SESSION['s1'] ?? '';
$session = [];
$nombres = '';

if ($correo) {
    $session = $dao->obtenerSesionEmpleado($correo);
    if (!empty($session)) {
        $nombres = $session[0]['nombreEmp'] . ' ' . $session[0]['apellido'];
    }
}

$materiasPrimas = $dao->obtenerMateriasPrimas();
$facturas = $dao->obtenerFacturas();

if (isset($_REQUEST["btnGuardar"])) {
    $obj = new DetalleCompra();
    $obj->setIdDetalleCompra($_REQUEST["txtIdDetalle"] ?? 0);
    $obj->setIdMateriaPrima($_REQUEST["txtIdMP"] ?? 0);
    $obj->setCantidadMP($_REQUEST["txtCantidad"] ?? 0);
    $obj->setPrecioMP($_REQUEST["txtPrecio"] ?? 0);
    $obj->setIdFacturaMP($_REQUEST["txtIdFMP"] ?? 0);
    $dao->insertar($obj);
    $tabla = $dao->obtenerTabla();
} else if (isset($_REQUEST["btnModificar"])) {
    $obj = new DetalleCompra();
    $obj->setIdDetalleCompra($_REQUEST["txtIdDetalle"] ?? 0);
    $obj->setIdMateriaPrima($_REQUEST["txtIdMP"] ?? 0);
    $obj->setCantidadMP($_REQUEST["txtCantidad"] ?? 0);
    $obj->setPrecioMP($_REQUEST["txtPrecio"] ?? 0);
    $obj->setIdFacturaMP($_REQUEST["txtIdFMP"] ?? 0);
    $dao->modificar($obj);
    $tabla = $dao->obtenerTabla();
} else if (isset($_REQUEST["btnEliminar"])) {
    $dao->eliminar($_REQUEST["txtIdDetalle"] ?? 0);
    $tabla = $dao->obtenerTabla();
}

include __DIR__ . "/../views/vistaDetalleCompra.php";
?>
