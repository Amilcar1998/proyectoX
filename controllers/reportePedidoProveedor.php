<?php
require_once __DIR__ . '/sesiones.php';
require dirname(__DIR__) . '/controllers/vendor/autoload.php';
include '../models/ReportePedidoProveedorModel.php';

$idEmpresaFiltro = ($esSuperUsuario ?? false) ? 0 : (int)($_SESSION['idEmpresa'] ?? 1);
$reporte = new ReportePedidoProveedorModel();
$data = $reporte->getPedidosProveedor($idEmpresaFiltro);
$reporte->generarPDF($data, 'PEDIDOS A PROVEEDOR');
?>