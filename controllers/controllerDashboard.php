<?php
require dirname(__DIR__) . '/controllers/vendor/autoload.php';
include '../models/ModelDashboard.php';
include 'sesiones.php';

$dao = new ModelDashboard();

$resumen = $dao->getResumen();
$pedidosMensuales = $dao->getPedidosMensuales();
$stockMaterias = $dao->getStockMateriasPrimas();
$pedidosRecientes = $dao->getPedidosRecientes();
$produccionEmpleado = $dao->getProduccionPorEmpleado();

$correo = $_SESSION['s1'] ?? '';
$session = [];
$nombres = '';

if ($correo) {
    $session = $dao->getSessionEmp($correo);
    if (!empty($session)) {
        $nombres = $session[0]['nombreEmp'] . ' ' . $session[0]['apellido'];
    }
}

include '../views/vistaDashboard.php';
