<?php
require dirname(__DIR__) . '/controllers/vendor/autoload.php';
require_once __DIR__ . '/../models/ModelDashboard.php';
require_once __DIR__ . '/sesiones.php';

$dao = new ModelDashboard();

$idRol = (int)($_SESSION['id_Rol'] ?? 0);
$correo = (string)($_SESSION['s1'] ?? ($_SESSION['s2'] ?? ($_SESSION['c1'] ?? '')));

$nombres = '';
$datosUsuario = $dao->obtenerDatosUsuarioPorSesion($correo);
if (!empty($datosUsuario)) {
    if (isset($datosUsuario['nombreEmp'])) {
        $nombres = trim($datosUsuario['nombreEmp'] . ' ' . ($datosUsuario['apellido'] ?? ''));
    } elseif (isset($datosUsuario['NombreCliente'])) {
        $nombres = trim($datosUsuario['NombreCliente'] . ' ' . ($datosUsuario['apellidosCliente'] ?? ''));
    }
}
if (empty($nombres)) {
    $nombres = $correo;
}

$resumen = [];
$pedidosMensuales = [];
$montoMensual = [];
$stockMaterias = [];
$pedidosRecientes = [];
$produccionEmpleado = [];
$misPedidosCliente = [];
$misPagosCliente = [];
$promocionesActivas = [];

if ($idRol === 1 || $idRol === 4) {
    // Gerente o Admin: visión gerencial completa
    $resumen = $dao->obtenerResumen();
    $pedidosMensuales = $dao->obtenerPedidosMensuales();
    $montoMensual = $dao->obtenerMontoMensual();
    $stockMaterias = $dao->obtenerStockMateriasPrimas();
    $pedidosRecientes = $dao->obtenerPedidosRecientes();
    $produccionEmpleado = $dao->obtenerProduccionPorEmpleado();
    $promocionesActivas = $dao->obtenerPromocionesActivas();
} elseif ($idRol === 2) {
    // Empleado: visión operativa de producción e inventario
    $resumen = $dao->obtenerResumenEmpleado($correo);
    $pedidosMensuales = $dao->obtenerPedidosMensuales();
    $stockMaterias = $dao->obtenerStockMateriasPrimas();
    $pedidosRecientes = $dao->obtenerPedidosRecientes();
    $produccionEmpleado = $dao->obtenerProduccionPorEmpleado();
} elseif ($idRol === 3) {
    // Cliente: visión personalizada de sus pedidos y pagos
    $resumen = $dao->obtenerResumenCliente($correo);
    $misPedidosCliente = $dao->obtenerPedidosCliente($correo);
    $misPagosCliente = $dao->obtenerPagosCliente($correo);
    $promocionesActivas = $dao->obtenerPromocionesActivas();
}

include __DIR__ . '/../views/vistaDashboard.php';

