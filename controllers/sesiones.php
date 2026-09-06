<?php
include_once __DIR__ . '/../models/AuditoriaHelper.php';
require_once __DIR__ . '/../models/PermisoModel.php';
require_once __DIR__ . '/../models/AuditoriaModel.php';
require_once __DIR__ . '/../models/UsuarioModel.php';

iniciarSesionSegura();

$currentSessionId = session_id();

if (isset($_REQUEST["c"])) {
    $username = '';
    if (isset($_SESSION["s1"])) {
        $username = $_SESSION["s1"];
    } elseif (isset($_SESSION["s2"])) {
        $username = $_SESSION["s2"];
    } elseif (isset($_SESSION["c1"])) {
        $username = $_SESSION["c1"];
    }
    cerrarSesionAuditoria($currentSessionId, 0, (string)$username);
    session_destroy();
    header("Location:controlUser.php");
    exit();
}

$idRol = (int)($_SESSION['id_Rol'] ?? 0);
$username = '';

if (isset($_SESSION["s1"])) {
    $username = (string)$_SESSION["s1"];
    if ($idRol === 0) { $idRol = 1; $_SESSION['id_Rol'] = 1; }
    registrarActividadSesion($currentSessionId, 0, $username, $idRol, '');
    logModuloAcceso(0, $username, ($idRol === 4 ? 'admin' : 'gerente'));
} elseif (isset($_SESSION["s2"])) {
    $username = (string)$_SESSION["s2"];
    if ($idRol === 0) { $idRol = 2; $_SESSION['id_Rol'] = 2; }
    registrarActividadSesion($currentSessionId, 0, $username, $idRol, '');
    logModuloAcceso(0, $username, 'empleado');
} elseif (isset($_SESSION["c1"])) {
    $username = (string)$_SESSION["c1"];
    if ($idRol === 0) { $idRol = 3; $_SESSION['id_Rol'] = 3; }
    registrarActividadSesion($currentSessionId, 0, $username, $idRol, '');
    logModuloAcceso(0, $username, 'cliente');
} else {
    // Si no hay variables en memoria, verificar si ya existe una sesión activa dentro de la BD
    $sesionActiva = verificarSesionActivaEnBD($currentSessionId);
    if ($sesionActiva) {
        $idRol = (int)$sesionActiva['id_Rol'];
        $username = (string)$sesionActiva['username'];
        $_SESSION['id_Rol'] = $idRol;
        actualizarActividadSesion($currentSessionId);

        if ($idRol === 1 || $idRol === 4) {
            $_SESSION['s1'] = $username;
            logModuloAcceso(0, $username, 'admin');
        } elseif ($idRol === 2) {
            $_SESSION['s2'] = $username;
            logModuloAcceso(0, $username, 'empleado');
        } elseif ($idRol === 3) {
            $_SESSION['c1'] = $username;
            logModuloAcceso(0, $username, 'cliente');
        } else {
            header("Location:controlUser.php");
            exit();
        }
    } else {
        header("Location:controlUser.php");
        exit();
    }
}

// Control de Acceso Basado en Roles (RBAC)
$controladorActual = basename($_SERVER['SCRIPT_NAME'] ?? ($_SERVER['PHP_SELF'] ?? ''));

// Verificación de cambio obligatorio de contraseña
if (!isset($_SESSION['debe_cambiar_pass'])) {
    $obUserSesion = new UsuarioModel();
    $_SESSION['debe_cambiar_pass'] = $obUserSesion->debeCambiarClave((string)$username) ? 1 : 0;
}

if (!empty($_SESSION['debe_cambiar_pass'])) {
    if (strtolower($controladorActual) !== 'controllercambiarclave.php') {
        header("Location: controllerCambiarClave.php");
        exit();
    }
}

$permisoModel = new PermisoModel();
$idUsuarioSesion = function_exists('obtenerIdUsuarioPorUsername') ? obtenerIdUsuarioPorUsername((string)$username) : 0;
if (!isset($_SESSION['idUsuario']) || (int)$_SESSION['idUsuario'] !== $idUsuarioSesion) {
    $_SESSION['idUsuario'] = $idUsuarioSesion;
}

// Resolución de Tenant (Aislamiento por Empresa)
if (!isset($_SESSION['idEmpresa']) || !isset($_SESSION['esSuperUsuario'])) {
    $obUserSesion = new UsuarioModel();
    $idEmpresaUsuario = $obUserSesion->obtenerIdEmpresaPorUsername((string)$username);
    $_SESSION['idEmpresa'] = $idEmpresaUsuario;

    // Superusuario: cuenta amilcar199819@gmail.com o Admin de la plataforma matriz (idEmpresa 1)
    $esSuper = ($username === 'amilcar199819@gmail.com' || ($idRol === 4 && $idEmpresaUsuario === 1));
    $_SESSION['esSuperUsuario'] = $esSuper ? 1 : 0;
}

if (strtolower($controladorActual) === 'sesiones.php') {
    $rutaHome = $permisoModel->obtenerRutaHome($idRol);
    header("Location: $rutaHome");
    exit();
}

if (!$permisoModel->verificarAcceso($idRol, $controladorActual, $idUsuarioSesion)) {
    http_response_code(403);

    $moduloNombre = $permisoModel->obtenerNombreModulo($controladorActual);
    $rolNombre = $permisoModel->obtenerNombreRol($idRol);
    $rutaHome = $permisoModel->obtenerRutaHome($idRol);

    $auditoriaModel = new AuditoriaModel();
    $auditoriaModel->log(
        $idUsuarioSesion,
        (string)$username,
        'acceso_denegado',
        $controladorActual,
        "Intento de acceso no autorizado al modulo $moduloNombre ($controladorActual) por el rol $rolNombre"
    );

    include __DIR__ . '/../views/vistaAccesoDenegado.php';
    exit();
}

?>
