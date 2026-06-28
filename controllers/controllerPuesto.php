<?php
require dirname(__DIR__) . '/controllers/vendor/autoload.php';
include '../models/ModelPuesto.php';
include 'sesiones.php';

use Puesto;

$dao = new ModelPuesto();

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

if (isset($_REQUEST["btnGuardar"])) {
    $obj = new Puesto();
    $obj->setId($_REQUEST["txtId"] ?? 0);
    $obj->setNombrePuesto($_REQUEST["txtNombre"] ?? '');
    $dao->insertar($obj);
    $tabla = $dao->getTabla();
} else if (isset($_REQUEST["btnModificar"])) {
    $obj = new Puesto();
    $obj->setId($_REQUEST["txtId"] ?? 0);
    $obj->setNombrePuesto($_REQUEST["txtNombre"] ?? '');
    $dao->modificar($obj);
    $tabla = $dao->getTabla();
} else if (isset($_REQUEST["btnEliminar"])) {
    $dao->eliminar($_REQUEST["txtId"] ?? 0);
    $tabla = $dao->getTabla();
}

include "../views/vistaPuesto.php";
?>
