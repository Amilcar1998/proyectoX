<?php
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/AuditoriaModel.php';
require_once __DIR__ . '/../models/AuditoriaHelper.php';

iniciarSesionSegura();

$enControllers = (strpos($_SERVER['REQUEST_URI'] ?? '', '/controllers/') !== false || strpos($_SERVER['SCRIPT_NAME'] ?? '', '/controllers/') !== false);
$prefijo = $enControllers ? '' : 'controllers/';
$rutaHome = $prefijo . 'controllerDashboard.php';
$rutaCambiarClave = $prefijo . 'controllerCambiarClave.php';

$obUser = new UsuarioModel();
$auditoria = new AuditoriaModel();
$sesionId = session_id();

// 1. Verificar si ya existe una sesión activa en la BD para este session_id
$sesionActivaBD = $auditoria->obtenerSesionActivaPorId($sesionId);

if ($sesionActivaBD) {
    $rolId = (int)$sesionActivaBD['id_Rol'];
    $usuarioBD = (string)$sesionActivaBD['username'];
    $_SESSION['id_Rol'] = $rolId;

    // Restaurar variables de sesión en PHP si faltaban
    if (($rolId === 1 || $rolId === 4) && empty($_SESSION['s1'])) {
        $_SESSION['s1'] = $usuarioBD;
    } elseif ($rolId === 2 && empty($_SESSION['s2'])) {
        $_SESSION['s2'] = $usuarioBD;
    } elseif ($rolId === 3 && empty($_SESSION['c1'])) {
        $_SESSION['c1'] = $usuarioBD;
    }
    $auditoria->actualizarActividadSesion($sesionId);

    $debeCambiar = $obUser->debeCambiarClave($usuarioBD);
    $_SESSION['debe_cambiar_pass'] = $debeCambiar ? 1 : 0;
    if ($debeCambiar) {
        header("Location: $rutaCambiarClave");
        exit();
    }

    // Enviar SIEMPRE al Home (Dashboard)
    header("Location: $rutaHome");
    exit();
}

// 2. Si hay sesión activa en PHP, enviar siempre al Home o a cambiar clave si es obligatorio
if (isset($_SESSION['s1']) || isset($_SESSION['s2']) || isset($_SESSION['c1'])) {
    $usuarioSesion = (string)($_SESSION['s1'] ?? $_SESSION['s2'] ?? $_SESSION['c1'] ?? '');
    if (!isset($_SESSION['debe_cambiar_pass'])) {
        $_SESSION['debe_cambiar_pass'] = $obUser->debeCambiarClave($usuarioSesion) ? 1 : 0;
    }
    if (!empty($_SESSION['debe_cambiar_pass'])) {
        header("Location: $rutaCambiarClave");
        exit();
    }
    header("Location: $rutaHome");
    exit();
}

$error = '';
$success = '';
$successMsg = '';
$resetLinkHtml = '';
$showReset = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['validar'])) {
        $login = trim($_POST['login'] ?? '');
        $pass = $_POST['pass'] ?? '';
        $rol = $obUser->validarUsuario($login, $pass);

        if ($rol > 0) {
            $idUsuario = obtenerIdUsuarioPorUsername($login);
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

            // Cerrar sesiones anteriores en BD para este usuario
            $auditoria->cerrarOtrasSesionesDeUsuario($idUsuario, $sesionId);

            $_SESSION['id_Rol'] = $rol;
            $_SESSION['idEmpresa'] = $obUser->obtenerIdEmpresaPorUsername($login);

            if ($rol === 1 || $rol === 4) {
                $_SESSION['s1'] = $login;
            } elseif ($rol === 2) {
                $_SESSION['s2'] = $login;
            } elseif ($rol === 3) {
                $_SESSION['c1'] = $login;
            }

            $auditoria->registrarSesionActiva($sesionId, $idUsuario, $login, $rol, $login, $ip, $ua);
            $auditoria->log($idUsuario, $login, 'login', 'sistema', 'Inicio de sesion exitoso');

            $debeCambiar = $obUser->debeCambiarClave($login);
            $_SESSION['debe_cambiar_pass'] = $debeCambiar ? 1 : 0;
            if ($debeCambiar) {
                $_SESSION['pass_temp_ingresada'] = $pass;
                header("Location: $rutaCambiarClave");
                exit();
            }

            header("Location: $rutaHome");
            exit();
        } elseif ($rol === -1) {
            $error = 'Esta cuenta se encuentra desactivada. Por favor comuníquese con el Administrador o Gerencia.';
        } else {
            $error = 'Usuario o contraseña incorrectos.';
        }
    } elseif (isset($_POST['solicitar_recuperacion'])) {
        $email = trim($_POST['email'] ?? '');
        $resultado = $obUser->solicitarRecuperacion($email);

        if (!$resultado['exito']) {
            $error = $resultado['mensaje'];
        } else {
            $successMsg = $resultado['mensaje'];
            if (!empty($resultado['enlace']) && empty($resultado['enviado'])) {
                $enlace = htmlspecialchars($resultado['enlace']);
                $resetLinkHtml = "<a href='$enlace' target='_blank' style='color:#1e40af;font-weight:bold;word-break:break-all;'>$enlace</a>";
            }
        }
    }
}

include __DIR__ . '/../views/login.php';