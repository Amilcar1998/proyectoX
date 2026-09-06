<?php
require_once __DIR__ . '/sesiones.php';
require_once __DIR__ . '/../models/modelClienteIn.php';

$cli = new ModelClienteIn();
$correo = $_SESSION['c1'] ?? ($_SESSION['s1'] ?? ($_SESSION['s2'] ?? ''));
$cliente = $cli->obtenerClientePorCorreo($correo);

$idCliente = (int)($cliente['idCliente'] ?? 0);
$idUsuario = (int)($cliente['idUsuario'] ?? 0);
$nombres = trim(($cliente['NombreCliente'] ?? 'Cliente') . ' ' . ($cliente['apellidosCliente'] ?? ''));
$fechaActual = date('Y-m-d');

$suscripcion = $cli->obtenerSuscripcionUsuario($idUsuario);
$misPagos = ($idUsuario > 0) ? $cli->obtenerPagosPorUsuario($idUsuario) : [];

$msj = '';
$icon = '';
$pedidoSeleccionado = null;
$idPedidoActual = null;
$detalleRes = [];
$materiaPrimaRes = [];
$mostrarModalAgregar = false;

// 1. Crear nuevo pedido
if (isset($_POST['crear_pedido']) || isset($_REQUEST['pedidos'])) {
    if ($idCliente <= 0 && !empty($correo)) {
        $cliente = $cli->obtenerClientePorCorreo($correo);
        $idCliente = (int)($cliente['idCliente'] ?? 0);
        $idUsuario = (int)($cliente['idUsuario'] ?? 0);
    }

    if ($idCliente > 0) {
        $idNuevoPedido = $cli->crearPedido($fechaActual, $idCliente, 1);
        if ($idNuevoPedido > 0) {
            $idPedidoActual = $idNuevoPedido;
            $detalleRes = $cli->obtenerDetalleProductosPedido($idPedidoActual);
            $mostrarModalAgregar = true;
            $msj = "Pedido #$idNuevoPedido creado con éxito. Selecciona los productos que deseas incluir.";
            $icon = 'success';
        }
    } else {
        $msj = "No se pudo identificar la cuenta del cliente.";
        $icon = 'error';
    }
}

// 2. Agregar producto al pedido actual
if (isset($_POST['agregar_producto']) || isset($_REQUEST['agregarP']) || isset($_REQUEST['agregarReceta']) || isset($_REQUEST['agregar'])) {
    $idPedidoDestino = (int)($_POST['id_pedido'] ?? ($_REQUEST['pedido'] ?? 0));
    $idReceta = (int)($_POST['producto'] ?? ($_REQUEST['producto'] ?? 0));
    $cantidad = (int)($_POST['txtcantidad'] ?? ($_REQUEST['txtcantidad'] ?? 1));

    if ($idPedidoDestino <= 0 && $idCliente > 0) {
        $ultimos = $cli->obtenerUltimoPedidoCliente($idCliente);
        if (!empty($ultimos)) {
            $idPedidoDestino = (int)$ultimos[0]['idPedido'];
        } else {
            $idPedidoDestino = $cli->crearPedido($fechaActual, $idCliente, 1);
        }
    }

    if ($idPedidoDestino > 0 && $idReceta > 0 && $cantidad > 0) {
        $cli->agregarDetallePedido($cantidad, $idReceta, $idPedidoDestino);
        $idPedidoActual = $idPedidoDestino;
        $detalleRes = $cli->obtenerDetalleProductosPedido($idPedidoActual);
        $msj = "Producto agregado al pedido #$idPedidoDestino correctamente.";
        $icon = 'success';
    } else {
        $msj = "Verifica los datos del producto y la cantidad.";
        $icon = 'warning';
    }
}

// 2.1 Agregar producto directamente desde el Catálogo de Productos
if (isset($_POST['agregar_desde_catalogo'])) {
    $idReceta = (int)($_POST['id_receta'] ?? 0);
    $cantidad = (int)($_POST['cantidad'] ?? 1);
    $idPedidoDestino = (int)($_POST['id_pedido'] ?? ($idPedidoActual ?? 0));

    if ($idPedidoDestino <= 0 && $idCliente > 0) {
        $ultimos = $cli->obtenerUltimoPedidoCliente($idCliente);
        if (!empty($ultimos)) {
            $idPedidoDestino = (int)$ultimos[0]['idPedido'];
        } else {
            $idPedidoDestino = $cli->crearPedido($fechaActual, $idCliente, 1);
        }
    }

    if ($idPedidoDestino > 0 && $idReceta > 0 && $cantidad > 0) {
        $cli->agregarDetallePedido($cantidad, $idReceta, $idPedidoDestino);
        $idPedidoActual = $idPedidoDestino;
        $detalleRes = $cli->obtenerDetalleProductosPedido($idPedidoActual);
        $msj = "¡Producto añadido con éxito al pedido #$idPedidoDestino!";
        $icon = 'success';
    } else {
        $msj = "No se pudo agregar el producto seleccionado.";
        $icon = 'warning';
    }
}

// 3. Ver detalle de un pedido
if (isset($_REQUEST['detalle']) || isset($_GET['ver_detalle'])) {
    $idPedidoActual = (int)($_REQUEST['data'] ?? ($_GET['ver_detalle'] ?? 0));
    if ($idPedidoActual > 0) {
        $detalleRes = $cli->obtenerDetalleProductosPedido($idPedidoActual);
    }
}

// 4. Ver receta / materias primas
if (isset($_REQUEST['det']) || isset($_GET['ver_receta'])) {
    $idRecetaVer = (int)($_REQUEST['idRes'] ?? ($_GET['ver_receta'] ?? 0));
    $idPedidoActual = (int)($_REQUEST['algo'] ?? ($_GET['id_pedido'] ?? 0));
    if ($idPedidoActual > 0) {
        $detalleRes = $cli->obtenerDetalleProductosPedido($idPedidoActual);
    }
    if ($idRecetaVer > 0) {
        $materiaPrimaRes = $cli->obtenerMateriaPrimaReceta($idRecetaVer);
    }
}

// 5. Eliminar ítem del detalle
if (isset($_POST['eliminar_item']) || isset($_REQUEST['eliminar'])) {
    $idDetalleEliminar = (int)($_POST['id_detalle'] ?? ($_REQUEST['id'] ?? 0));
    $idPedidoActual = (int)($_POST['id_pedido'] ?? ($_REQUEST['algo'] ?? 0));
    if ($idDetalleEliminar > 0) {
        $cli->eliminarDetallePedido($idDetalleEliminar);
        $msj = "Producto eliminado del pedido.";
        $icon = 'success';
        if ($idPedidoActual > 0) {
            $detalleRes = $cli->obtenerDetalleProductosPedido($idPedidoActual);
        }
    }
}

// 6. Eliminar pedido completo
if (isset($_POST['eliminar_pedido']) || isset($_REQUEST['EliminarP'])) {
    $idPedidoEliminar = (int)($_POST['id_pedido'] ?? ($_REQUEST['data'] ?? 0));
    if ($idPedidoEliminar > 0) {
        $cli->eliminarPedidoPorId($idPedidoEliminar);
        $msj = "El pedido #$idPedidoEliminar ha sido eliminado.";
        $icon = 'success';
        $idPedidoActual = null;
        $detalleRes = [];
    }
}

$receta = $cli->obtenerRecetas();
$dataPedido = ($idCliente > 0) ? $cli->obtenerPedidosPorCliente($idCliente) : [];

include __DIR__ . '/../views/individualCliente.php';
