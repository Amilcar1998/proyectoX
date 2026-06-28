<?php
require dirname(__DIR__) . '/controllers/vendor/autoload.php';
include '../models/ReportePedidoProveedorModel.php';

$reporte = new ReportePedidoProveedorModel();
$data = $reporte->getPedidosProveedor();
$reporte->generarPDF($data, 'PEDIDOS A PROVEEDOR');
?>