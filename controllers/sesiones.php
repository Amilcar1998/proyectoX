<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../models/AuditoriaHelper.php';

$currentSessionId = session_id();

if(isset($_REQUEST["c"])) {
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
elseif(isset($_SESSION["s1"])){
    $username = (string)$_SESSION["s1"];
    registrarActividadSesion($currentSessionId, 0, $username, 3, '');
    logModuloAcceso(0, $username, 'empleado');
}
elseif(isset($_SESSION["s2"])){
   $username = (string)$_SESSION["s2"];
   registrarActividadSesion($currentSessionId, 0, $username, 2, '');
   logModuloAcceso(0, $username, 'cliente');
}
elseif(isset($_SESSION["c1"])){
   $username = (string)$_SESSION["c1"];
   registrarActividadSesion($currentSessionId, 0, $username, 1, '');
   logModuloAcceso(0, $username, 'cliente_individual');
}
else{
    header("Location:controlUser.php");
    exit();
}

?>
