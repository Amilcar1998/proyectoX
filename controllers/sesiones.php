<?php 
require_once __DIR__ . '/../models/AuditoriaModel.php';
require_once __DIR__ . '/../models/AuditoriaHelper.php';

iniciarSesionSegura();

$sessionId = session_id();

if (isset($_REQUEST["c"])) {
    $username = (string)($_SESSION["s1"] ?? $_SESSION["s2"] ?? $_SESSION["c1"] ?? '');
    $idUsuario = 0;
    
    if (!empty($username)) {
        $idUsuario = obtenerIdUsuarioPorUsername($username);
    }
    
    // 1. Cerrar sesión en base de datos (sesiones_activas) y registrar log en auditoría
    cerrarSesionAuditoria($sessionId, $idUsuario, $username);
    
    // 2. Limpiar todas las variables de sesión
    $_SESSION = [];
    
    // 3. Destruir la cookie de sesión en el cliente
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    
    // 4. Destruir la sesión en el servidor
    session_destroy();
    
    // 5. Redirigir al inicio de sesión
    header("Location: controlUser.php");
    exit();
} elseif (isset($_SESSION["s1"])) {
    // Sesión de Administrador / Producción activa
} elseif (isset($_SESSION["s2"])) {
    $cliente = $_SESSION["s2"];
} elseif (isset($_SESSION["c1"])) {
    $cliente = $_SESSION["c1"];
} else {
    // Si no hay sesión válida en PHP pero existía en BD, limpiarla
    if (!empty($sessionId)) {
        $auditoria = new AuditoriaModel();
        $auditoria->cerrarSesionActiva($sessionId);
    }
    header("Location: controlUser.php");
    exit();
}

// Validación de Suscripción Activa para Inquilinos (Multi-Tenant)
$usuarioActual = (string)($_SESSION["s1"] ?? $_SESSION["s2"] ?? $_SESSION["c1"] ?? '');
$esSuperUsuario = !empty($_SESSION['esSuperUsuario']) || ($usuarioActual === 'amilcar199819@gmail.com');
$idEmpresaActual = (int)($_SESSION['idEmpresa'] ?? 0);
$idRolSesionActual = (int)($_SESSION['id_Rol'] ?? 0);
$esClienteDirecto = ($idRolSesionActual === 3) || isset($_SESSION['c1']);

if ($idEmpresaActual <= 0 && !empty($usuarioActual)) {
    require_once __DIR__ . '/../models/UsuarioModel.php';
    $usuarioModelSesion = new UsuarioModel();
    $idEmpresaActual = $usuarioModelSesion->obtenerIdEmpresaPorUsername($usuarioActual);
    $_SESSION['idEmpresa'] = $idEmpresaActual;
}

// Las empresas inquilinas (idEmpresa > 1) requieren suscripción activa; los clientes directos de compras están exentos
if (!$esSuperUsuario && !$esClienteDirecto && $idEmpresaActual > 1) {
    require_once __DIR__ . '/../models/EmpresaModel.php';
    $empresaModelSesion = new EmpresaModel();
    $suscripcion = $empresaModelSesion->verificarSuscripcionEmpresa($idEmpresaActual);
    
    if (empty($suscripcion['activa'])) {
        $archivoActual = basename($_SERVER['SCRIPT_NAME'] ?? ($_SERVER['PHP_SELF'] ?? ''));
        $permitidosSinSuscripcion = [
            'controllerDashboard.php',
            'controllerPlanPago.php',
            'controllerPagos.php',
            'controllerCambiarClave.php',
            'controlUser.php',
            'sesiones.php',
            'Sesiones.php'
        ];
        
        if (!in_array($archivoActual, $permitidosSinSuscripcion, true)) {
            header("Location: controllerDashboard.php?suscripcion_bloqueada=1");
            exit();
        }
    }
}
?>