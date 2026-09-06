<?php
require dirname(__DIR__) . '/controllers/vendor/autoload.php';
include '../models/ModelInventario.php';
include 'sesiones.php';

$dao = new ModelInventario();

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
    $obj = new Inventario();
    $obj->setIdInventario($_REQUEST["txtId"] ?? '');
    $obj->setIdMateriaPrima($_REQUEST["txtIdMateriaPrima"] ?? '');
    $obj->setExistencias($_REQUEST["txtExistencias"] ?? '');
    $obj->setIdDetalleCompra($_REQUEST["txtDetalleCompra"] ?? '');
    $dao->InsertarInventario($obj, $idEmpresaSesion);
} else if (isset($_REQUEST["btnModificar"])) {
    $obj = new Inventario();
    $obj->setIdInventario($_REQUEST["txtId"] ?? '');
    $obj->setIdMateriaPrima($_REQUEST["txtIdMateriaPrima"] ?? '');
    $obj->setExistencias($_REQUEST["txtExistencias"] ?? '');
    $obj->setIdDetalleCompra($_REQUEST["txtDetalleCompra"] ?? '');
    $dao->setInventario($obj);
} else if (isset($_REQUEST["btnEliminar"])) {
    $dao->eliminar($_REQUEST["txtId"] ?? 0);
}

$tabla = $dao->getTabla($idEmpresaFiltro);
$materiasPrimas = $dao->getMateriasPrimas($idEmpresaFiltro);

include '../views/vistaInventario.php';
?>
