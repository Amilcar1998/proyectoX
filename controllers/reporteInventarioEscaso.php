<?php
require dirname(__DIR__) . '/controllers/vendor/autoload.php';
include '../models/ReporteInventarioModel.php';

$reporte = new ReporteInventarioMP();
$data = $reporte->getInventarioEscaso();
$reporte->generarPDF($data, 'INVENTARIO ESCASO');
?>