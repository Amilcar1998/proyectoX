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

$idEmpresaSesion = (int)($_SESSION['idEmpresa'] ?? 1);
$esSuperUsuario = !empty($_SESSION['esSuperUsuario']) || (($correo ?? '') === 'amilcar199819@gmail.com');
$idEmpresaFiltro = $esSuperUsuario ? 0 : $idEmpresaSesion;

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
    // Gerente o Admin: visión gerencial de su empresa (o global si superusuario)
    $resumen = $dao->obtenerResumen($idEmpresaFiltro);
    $pedidosMensuales = $dao->obtenerPedidosMensuales($idEmpresaFiltro);
    $montoMensual = $dao->obtenerMontoMensual($idEmpresaFiltro);
    $stockMaterias = $dao->obtenerStockMateriasPrimas($idEmpresaFiltro);
    $pedidosRecientes = $dao->obtenerPedidosRecientes($idEmpresaFiltro);
    $produccionEmpleado = $dao->obtenerProduccionPorEmpleado($idEmpresaFiltro);
    $promocionesActivas = $dao->obtenerPromocionesActivas($idEmpresaFiltro);
} elseif ($idRol === 2) {
    // Empleado: visión operativa de producción e inventario de su empresa
    $resumen = $dao->obtenerResumenEmpleado($correo, $idEmpresaFiltro);
    $pedidosMensuales = $dao->obtenerPedidosMensuales($idEmpresaFiltro);
    $stockMaterias = $dao->obtenerStockMateriasPrimas($idEmpresaFiltro);
    $pedidosRecientes = $dao->obtenerPedidosRecientes($idEmpresaFiltro);
    $produccionEmpleado = $dao->obtenerProduccionPorEmpleado($idEmpresaFiltro);
} elseif ($idRol === 3) {
    // Cliente: visión personalizada de sus pedidos y pagos
    $resumen = $dao->obtenerResumenCliente($correo);
    $misPedidosCliente = $dao->obtenerPedidosCliente($correo);
    $misPagosCliente = $dao->obtenerPagosCliente($correo);
    $promocionesActivas = $dao->obtenerPromocionesActivas($idEmpresaFiltro);
}

include __DIR__ . '/../views/vistaDashboard.php';

