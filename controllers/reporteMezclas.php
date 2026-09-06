<?php
require_once __DIR__ . '/sesiones.php';
require dirname(__DIR__) . '/controllers/vendor/autoload.php';
include '../models/ReporteMezclasModel.php';

$idEmpresaFiltro = ($esSuperUsuario ?? false) ? 0 : (int)($_SESSION['idEmpresa'] ?? 1);
$reporte = new ReporteMezclasModel();
$data = $reporte->getMezclas($idEmpresaFiltro);
$reporte->generarPDF($data, 'MEZCLAS HECHAS');
?>