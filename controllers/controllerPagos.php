<?php
session_start();
if (isset($_SESSION['s1']) || isset($_SESSION['s2']) || isset($_SESSION['c1'])) {
    if ($_SESSION['s1']) {
        header('Location: controllerEmpleado.php');
    } elseif ($_SESSION['s2']) {
        header('Location: controllerPedidosIn.php');
    } else {
        header('Location: controllerIndividualC.php');
    }
    exit();
}

include '../models/PagoModel.php';
include '../controllers/sesiones.php';
include '../models/AuditoriaHelper.php';

$pagoModel = new PagoModel();
$esAdmin = false;
$idUsuarioFiltro = 0;

if (isset($_SESSION['s1'])) {
    $esAdmin = true;
    $idUsuarioFiltro = 0;
    $pagos = $pagoModel->getPagos(100, 0);
} elseif (isset($_SESSION['s2'])) {
    $username = $_SESSION['s2'];
    $idUsuarioFiltro = obtenerIdUsuarioPorUsername($username);
    $pagos = $pagoModel->getPagosPorUsuario($idUsuarioFiltro);
} elseif (isset($_SESSION['c1'])) {
    $username = $_SESSION['c1'];
    $idUsuarioFiltro = obtenerIdUsuarioPorUsername($username);
    $pagos = $pagoModel->getPagosPorUsuario($idUsuarioFiltro);
} else {
    $pagos = [];
}

$stats = $idUsuarioFiltro > 0 ? $pagoModel->getTotalPagosPorUsuario($idUsuarioFiltro) : ['total' => 0, 'totalMonto' => 0];

include '../views/vistaPagos.php';
