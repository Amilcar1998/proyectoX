<?php
require dirname(__DIR__) . '/controllers/vendor/autoload.php';
include '../models/ReportePedidosModel.php';

$reporte = new ReportePedidosModel();
$data = $reporte->getPedidos();
$reporte->generarPDF($data, 'PEDIDOS POR FECHA Y CLIENTE');
?>