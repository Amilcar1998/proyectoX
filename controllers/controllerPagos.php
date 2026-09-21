<?php
require_once __DIR__ . '/sesiones.php';
require_once __DIR__ . '/../models/PagoModel.php';
require_once __DIR__ . '/../models/AuditoriaHelper.php';
require_once __DIR__ . '/../models/ModelDashboard.php';

$pagoModel = new PagoModel();

// Endpoint AJAX: Obtener detalle completo de un pago para el modal
if (isset($_GET['accion']) && $_GET['accion'] === 'obtenerDetalle') {
    header('Content-Type: application/json');
    $idPago = (int)($_GET['idPago'] ?? 0);
    $pagoDetalle = $pagoModel->obtenerPagoPorId($idPago);
    if ($pagoDetalle) {
        echo json_encode(['status' => 'success', 'pago' => $pagoDetalle]);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'mensaje' => 'Pago no encontrado']);
    }
    exit();
}

// Endpoint AJAX / POST: Procesar Reembolso
if (isset($_POST['accion']) && $_POST['accion'] === 'reembolsar') {
    header('Content-Type: application/json');
    $idRolSesion = (int)($_SESSION['id_Rol'] ?? 0);
    $esAdmin = ($idRolSesion === 1 || $idRolSesion === 4 || isset($_SESSION['s1']));
    
    if (!$esAdmin) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'mensaje' => 'No tienes permisos de administrador para realizar reembolsos.']);
        exit();
    }

    $idPago = (int)($_POST['idPago'] ?? 0);
    $motivo = trim((string)($_POST['motivo'] ?? ''));
    $usuarioAdmin = $_SESSION['s1'] ?? ($_SESSION['s2'] ?? 'Administrador');

    $exito = $pagoModel->reembolsarPago($idPago, $motivo, $usuarioAdmin);
    if ($exito) {
        logAccionAuditoria('reembolso', 'pagos', "Reembolso procesado para el pago #$idPago. Motivo: $motivo", $usuarioAdmin);
        echo json_encode(['status' => 'success', 'mensaje' => "El pago #$idPago ha sido marcado como reembolsado correctamente."]);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'mensaje' => 'No se pudo procesar el reembolso. Verifica el identificador del pago.']);
    }
    exit();
}

$idRolSesion = (int)($_SESSION['id_Rol'] ?? 0);
$esAdmin = ($idRolSesion === 1 || $idRolSesion === 4 || isset($_SESSION['s1']));

// Obtener nombre del usuario para el navbar
$correoUsuario = $_SESSION['s1'] ?? ($_SESSION['s2'] ?? ($_SESSION['c1'] ?? ''));
$daoDash = new ModelDashboard();
$nombres = $correoUsuario;
if (!empty($correoUsuario)) {
    $sessionEmp = $daoDash->getSessionEmp($correoUsuario);
    if (!empty($sessionEmp)) {
        $nombres = ($sessionEmp[0]['nombreEmp'] ?? '') . ' ' . ($sessionEmp[0]['apellido'] ?? '');
    }
}

$idEmpresaSesion = (int)($_SESSION['idEmpresa'] ?? 1);

if ($esAdmin) {
    if ($idRolSesion === 4 || $idEmpresaSesion === 1) {
        // Super Administrador de la plataforma ve global
        $pagos = $pagoModel->listarPagos(200, 0);
        $estadisticas = $pagoModel->obtenerEstadisticasGlobales();
    } else {
        // Administrador de Empresa ve únicamente los pagos de su empresa
        $pagos = $pagoModel->listarPagosPorEmpresa($idEmpresaSesion, 200, 0);
        $estadisticas = $pagoModel->obtenerEstadisticasPorEmpresa($idEmpresaSesion);
    }
} else {
    $idUsuario = obtenerIdUsuarioPorUsername($correoUsuario);
    $pagos = $pagoModel->obtenerPagosPorUsuario($idUsuario, 100, 0);
    $estadisticasUsuario = $pagoModel->obtenerEstadisticasPorUsuario($idUsuario);
    $estadisticas = [
        'totalTransacciones' => $estadisticasUsuario['total'],
        'totalRecaudado' => $estadisticasUsuario['totalMonto'],
        'totalCompletados' => $estadisticasUsuario['total'],
        'totalPendientes' => 0,
        'totalFallidos' => 0,
        'ticketPromedio' => $estadisticasUsuario['total'] > 0 ? ($estadisticasUsuario['totalMonto'] / $estadisticasUsuario['total']) : 0.0
    ];
}

include __DIR__ . '/../views/vistaPagos.php';
