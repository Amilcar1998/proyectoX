<?php 
require_once __DIR__ . "/sesiones.php";
require_once __DIR__ . "/../models/ModelUser.php";

$objU = new ModelUser();

$msj = null;
$icon = null;

if (isset($_POST["cambiar_estado"])) {
    $idUsuario = (int)($_POST['txtIdUsuario'] ?? 0);
    $nuevoEstado = (int)($_POST['nuevoEstado'] ?? 1);
    $objU->cambiarEstadoUsuario($idUsuario, $nuevoEstado);
    $estadoTxt = ($nuevoEstado === 1) ? 'activado' : 'desactivado';
    $msj = "Se ha $estadoTxt el usuario exitosamente";
    $icon = "success";
}

if (isset($_REQUEST["modificar"])) {
    $u = new Usuario($_REQUEST["txtUsuario"], $_REQUEST["txtUser"], sha1($_REQUEST["txtPass"]), $_REQUEST["txtRol"]);
    $objU->modificarUsuario($u);
    $msj = "Se ha modificado el usuario exitosamente";
    $icon = "success";
}

if (isset($_REQUEST["empleado"])) {
    $userEmp = $objU->getUsuario();
    $nombre = 'Cuentas de Usuarios Empleados';
    $thead = "<th># ID</th><th>Nombre y Apellido</th><th>Usuario Institucional</th><th>Rol</th><th>Estado</th><th class='text-center'>Acciones</th>";
} elseif (isset($_REQUEST["cliente"])) {
    $userCli = $objU->getUsuarioCli();
    $thead = "<th># ID</th><th>Nombre Cliente</th><th>Usuario / Correo</th><th>Rol</th><th>Estado</th><th class='text-center'>Acciones</th>";
    $nombre = "Cuentas de Usuarios Clientes";
} else {
    $m = $objU->getUsuarios();
    $nombre = 'Todas las Cuentas de Usuarios';
    $thead = "<th># ID</th><th>Nombre de Usuario / Correo</th><th>Rol en Sistema</th><th>Estado</th><th class='text-center'>Acciones</th>";
}

$rol = $objU->getRol();
$correo = $_SESSION["s1"] ?? ($_SESSION['s2'] ?? '');
$session = $objU->getSessionEmp($correo);
$nombres = '';
foreach ($session as $key) {
    $nombres = trim(($key['nombreEmp'] ?? '') . ' ' . ($key['apellido'] ?? ''));
}

include __DIR__ . "/../views/vistaUsuarios.php";
