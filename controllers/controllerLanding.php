<?php
require_once __DIR__ . '/../models/AuditoriaModel.php';
require_once __DIR__ . '/../models/AuditoriaHelper.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/LandingModel.php';

iniciarSesionSegura();

// Si se invoca directamente el script del controlador en el navegador, redirigir a la raíz
if (basename($_SERVER['SCRIPT_NAME'] ?? '') === 'controllerLanding.php') {
    $queryString = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
    header("Location: ../index.php" . $queryString);
    exit();
}

$sesionId = session_id();
$modeloAuditoria = new AuditoriaModel();
$sesionActiva = $modeloAuditoria->obtenerSesionActivaPorId($sesionId);
$modeloUsuario = new UsuarioModel();

// Si el usuario tiene sesión pero debe cambiar clave obligatoriamente
$usuarioSesion = (string)($_SESSION['s1'] ?? $_SESSION['s2'] ?? $_SESSION['c1'] ?? '');
if (!empty($usuarioSesion)) {
    $debeCambiar = $modeloUsuario->debeCambiarClave($usuarioSesion);
    if ($debeCambiar) {
        header("Location: controllers/controllerCambiarClave.php");
        exit();
    }
}

// Carga de datos para la Landing Page / Catálogo Público
$tiendaSlug = trim((string)($_GET['tienda'] ?? ''));
$modeloLanding = new LandingModel();
$catalogoProductos = $modeloLanding->obtenerProductosCatalogo();
$planesServicio = $modeloLanding->obtenerPlanesDisponibles();
$estadisticas = $modeloLanding->obtenerEstadisticas();
$infoEmpresa = $modeloLanding->obtenerInformacionEmpresa($tiendaSlug);

// Renderizar la vista pública del catálogo / landing
require_once __DIR__ . '/../views/landing.php';
