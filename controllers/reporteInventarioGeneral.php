<?php
require dirname(__DIR__) . '/controllers/vendor/autoload.php';
include '../models/ReporteInventarioModel.php';

$reporte = new ReporteInventarioMP();
$data = $reporte->getInventario();
$reporte->generarPDF($data, 'INVENTARIO GENERAL DE MATERIA PRIMA');
?>