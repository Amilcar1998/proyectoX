<?php
require dirname(__DIR__) . '/controllers/vendor/autoload.php';
include '../models/ModelMateriaPrima.php';
include 'sesiones.php';

$dao = new ModelMateriaPrima();

$correo = $_SESSION['s1'] ?? ($_SESSION['s2'] ?? '');
$idEmpresaSesion = (int)($_SESSION['idEmpresa'] ?? 1);
$esSuperUsuario = !empty($_SESSION['esSuperUsuario']) || (($correo ?? '') === 'amilcar199819@gmail.com');
$idEmpresaFiltro = $esSuperUsuario ? 0 : $idEmpresaSesion;

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
    $dao->insertar($obj, $idEmpresaSesion);
} else if (isset($_REQUEST["btnModificar"])) {
    $obj = new MateriaPrima();
    $obj->setIdMateriaPrima($_REQUEST["txtIdMP"] ?? '');
    $obj->setNombreMP($_REQUEST["txtNombreMP"] ?? '');
    $dao->modificar($obj);
} else if (isset($_REQUEST["btnEliminar"])) {
    $dao->eliminar($_REQUEST["txtIdMP"] ?? 0);
}

$tabla = $dao->getTabla($idEmpresaFiltro);

include "../views/vistaMateriaPrima.php";
?>