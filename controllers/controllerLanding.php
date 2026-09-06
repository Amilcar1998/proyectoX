<?php
require_once __DIR__ . '/../models/AuditoriaModel.php';
require_once __DIR__ . '/../models/AuditoriaHelper.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/LandingModel.php';

iniciarSesionSegura();

// Si se invoca directamente el script del controlador en el navegador, redirigir a la raíz
if (basename($_SERVER['SCRIPT_NAME'] ?? '') === 'controllerLanding.php') {
    header("Location: ../index.php");
    exit();
}

$sesionId = session_id();
$modeloAuditoria = new AuditoriaModel();
$sesionActiva = $modeloAuditoria->obtenerSesionActivaPorId($sesionId);
$modeloUsuario = new UsuarioModel();

// Si el usuario ya cuenta con sesión activa en el sistema, redirigirlo según su estado
if ($sesionActiva) {
    $rolId = (int)$sesionActiva['id_Rol'];
    $usuarioBD = (string)$sesionActiva['username'];
    $modeloAuditoria->actualizarActividadSesion($sesionId);

    $_SESSION['id_Rol'] = $rolId;
    if ($rolId === 1 || $rolId === 4) {
        $_SESSION['s1'] = $usuarioBD;
    } elseif ($rolId === 2) {
        $_SESSION['s2'] = $usuarioBD;
    } elseif ($rolId === 3) {
        $_SESSION['c1'] = $usuarioBD;
    }

    $debeCambiar = $modeloUsuario->debeCambiarClave($usuarioBD);
    $_SESSION['debe_cambiar_pass'] = $debeCambiar ? 1 : 0;
    if ($debeCambiar) {
        header("Location: controllers/controllerCambiarClave.php");
        exit();
    }

    header("Location: controllers/controllerDashboard.php");
    exit();
} elseif (isset($_SESSION['s1']) || isset($_SESSION['s2']) || isset($_SESSION['c1'])) {
    $usuarioSesion = (string)($_SESSION['s1'] ?? $_SESSION['s2'] ?? $_SESSION['c1'] ?? '');
    if (!isset($_SESSION['debe_cambiar_pass'])) {
        $_SESSION['debe_cambiar_pass'] = $modeloUsuario->debeCambiarClave($usuarioSesion) ? 1 : 0;
    }
    if (!empty($_SESSION['debe_cambiar_pass'])) {
        header("Location: controllers/controllerCambiarClave.php");
        exit();
    }

    header("Location: controllers/controllerDashboard.php");
    exit();
}

// Carga de datos para la Landing Page
$modeloLanding = new LandingModel();
$catalogoProductos = $modeloLanding->obtenerProductosCatalogo();
$planesServicio = $modeloLanding->obtenerPlanesDisponibles();
$estadisticas = $modeloLanding->obtenerEstadisticas();
$infoEmpresa = $modeloLanding->obtenerInformacionEmpresa();

// Renderizar la vista
require_once __DIR__ . '/../views/landing.php';
