<?php
require_once __DIR__ . '/sesiones.php';
require dirname(__DIR__) . '/controllers/vendor/autoload.php';
include '../models/ReporteInventarioModel.php';

$idEmpresaFiltro = ($esSuperUsuario ?? false) ? 0 : (int)($_SESSION['idEmpresa'] ?? 1);
$reporte = new ReporteInventarioMP();
$data = $reporte->getInventario($idEmpresaFiltro);
$reporte->generarPDF($data, 'INVENTARIO GENERAL DE MATERIA PRIMA');
?>