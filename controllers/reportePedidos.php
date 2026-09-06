<?php
require_once __DIR__ . '/sesiones.php';
require dirname(__DIR__) . '/controllers/vendor/autoload.php';
include '../models/ReportePedidosModel.php';

$correo = $_SESSION['s1'] ?? ($_SESSION['s2'] ?? '');
$idEmpresaSesion = (int)($_SESSION['idEmpresa'] ?? 1);
$esSuperUsuario = !empty($_SESSION['esSuperUsuario']) || (($correo ?? '') === 'amilcar199819@gmail.com');
$idEmpresaFiltro = $esSuperUsuario ? 0 : $idEmpresaSesion;

$reporte = new ReportePedidosModel();
$data = $reporte->getPedidos($idEmpresaFiltro);
$reporte->generarPDF($data, 'PEDIDOS POR FECHA Y CLIENTE');
?>