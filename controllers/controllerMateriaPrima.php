<?php
require dirname(__DIR__) . '/controllers/vendor/autoload.php';
include '../models/ModelMateriaPrima.php';
include 'sesiones.php';

$dao = new ModelMateriaPrima();

$tabla = $dao->getTabla();
$correo = $_SESSION['s1'] ?? '';
$session = [];
$nombres = '';

if ($correo) {
    // Obtener datos de sesión empleado
    $session = $dao->getSessionEmp($correo);
    if (!empty($session)) {
        $nombres = $session[0]['nombreEmp'] . ' ' . $session[0]['apellido'];
    }
}

if (isset($_REQUEST["btnGuardar"])) {
    $obj = new MateriaPrima();
    $obj->setIdMateriaPrima($_REQUEST["txtIdMP"] ?? '');
    $obj->setNombreMP($_REQUEST["txtNombreMP"] ?? '');
    $dao->insertar($obj);
    $tabla = $dao->getTabla();
} else if (isset($_REQUEST["btnModificar"])) {
    $obj = new MateriaPrima();
    $obj->setIdMateriaPrima($_REQUEST["txtIdMP"] ?? '');
    $obj->setNombreMP($_REQUEST["txtNombreMP"] ?? '');
    $dao->modificar($obj);
    $tabla = $dao->getTabla();
} else if (isset($_REQUEST["btnEliminar"])) {
    $dao->eliminar($_REQUEST["txtIdMP"] ?? 0);
    $tabla = $dao->getTabla();
}

include "../views/vistaMateriaPrima.php";
?>