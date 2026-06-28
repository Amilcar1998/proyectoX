<?php
require dirname(__DIR__) . '/controllers/vendor/autoload.php';
include '../models/ReporteMezclasModel.php';

$reporte = new ReporteMezclasModel();
$data = $reporte->getMezclas();
$reporte->generarPDF($data, 'MEZCLAS HECHAS');
?>