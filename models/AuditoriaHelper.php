<?php
include_once "../db/conexion.php";
include_once __DIR__ . "/AuditoriaModel.php";

if (!function_exists('obtenerIdUsuarioPorUsername')) {
    function obtenerIdUsuarioPorUsername($username)
    {
        if (empty($username)) return 0;
        $con = new Conexion();
        $conn = $con->getConnection();
        $stmt = $conn->prepare("SELECT idUsuario FROM usuarios WHERE username = ?");
        if (!$stmt) return 0;
        $ok = $stmt->bind_param("s", $username);
        if (!$ok) return 0;
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ? (int)$row['idUsuario'] : 0;
    }
}

if (!function_exists('registrarActividadSesion')) {
    function registrarActividadSesion($sessionId, $idUsuario, $username, $idRol, $nombreUsuario)
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

        if ((int)$idUsuario <= 0 && !empty($username)) {
            $idUsuario = obtenerIdUsuarioPorUsername($username);
        }

        $model = new AuditoriaModel();
        $model->registrarSesionActiva($sessionId, (int)$idUsuario, (string)$username, (int)$idRol, (string)$nombreUsuario, $ipAddress, $userAgent);
    }
}

if (!function_exists('actualizarActividadSesion')) {
    function actualizarActividadSesion($sessionId)
    {
        $model = new AuditoriaModel();
        $model->actualizarActividadSesion($sessionId);
    }
}

if (!function_exists('cerrarSesionAuditoria')) {
    function cerrarSesionAuditoria($sessionId, $idUsuario, $username)
    {
        if ((int)$idUsuario <= 0 && !empty($username)) {
            $idUsuario = obtenerIdUsuarioPorUsername($username);
        }

        $model = new AuditoriaModel();
        $model->cerrarSesionActiva($sessionId);
        $model->log((int)$idUsuario, (string)$username, 'logout', 'sistema', 'Cierre de sesion');
    }
}

if (!function_exists('logModuloAcceso')) {
    function logModuloAcceso($idUsuario, $username, $modulo = null)
    {
        if (empty($modulo)) {
            $modulo = basename($_SERVER['PHP_SELF'], '.php');
        }
        if ((int)$idUsuario <= 0 && !empty($username)) {
            $idUsuario = obtenerIdUsuarioPorUsername($username);
        }
        $model = new AuditoriaModel();
        $model->log((int)$idUsuario, (string)$username, 'vista', $modulo, 'Acceso a modulo');
    }
}
