<?php
require_once __DIR__ . '/sesiones.php';
require_once __DIR__ . '/../models/ClienteModel.php';

$cliente = new ClienteModel();

$correo = $_SESSION['s1'] ?? ($_SESSION['s2'] ?? '');
$idEmpresaSesion = (int)($_SESSION['idEmpresa'] ?? 1);
$esSuperUsuario = !empty($_SESSION['esSuperUsuario']) || (($correo ?? '') === 'amilcar199819@gmail.com');
$idEmpresaFiltro = $esSuperUsuario ? 0 : $idEmpresaSesion;

if (isset($_REQUEST["insertar"])) {
    $u = new Usuario("", $_REQUEST["usuarioC"], sha1('123456'), '2');
    $cliente->getAddUs($u, $idEmpresaSesion);
    $user = $_REQUEST["usuarioC"];
    $usuario = $cliente->getUser($idEmpresaSesion);
    $id = '';
    foreach ($usuario as $us) {
        if ($us['username'] === $user) {
            $id = $us["idUsuario"];
            break;
        }
    }

    $e = new Cliente($_REQUEST["idCliente"], $_REQUEST["nombreC"], $_REQUEST["apellidoC"], $_REQUEST["telefonoC"], $_REQUEST["edadC"], $_REQUEST["generoC"], $id);
    $cliente->agregarCliente($e, $idEmpresaSesion);
    $msj = "Se ha agregado el registro exitosamente";
    $icon = "success";
}

if (isset($_REQUEST["modificar"])) {
    $e = new Cliente($_REQUEST["idCliente"], $_REQUEST["nombreC"], $_REQUEST["apellidoC"], $_REQUEST["telefonoC"], $_REQUEST["edadC"], $_REQUEST["generoC"], $_REQUEST["usuarioC"]);
    $cliente->modificarCliente($e);
    $msj = "Se ha modificado el registro exitosamente";
    $icon = "success";
}

if (isset($_REQUEST["eliminar"])) {
    $e = new Cliente($_REQUEST["idCliente"], $_REQUEST["nombreC"], $_REQUEST["apellidoC"], $_REQUEST["telefonoC"], $_REQUEST["edadC"], $_REQUEST["generoC"], $_REQUEST["usuarioC"]);
    $cliente->eliminarCliente($e);
    $msj = "Se ha eliminado el registro exitosamente";
    $icon = "success";
}

$user = $cliente->getUser($idEmpresaFiltro);
$Rcliente = $cliente->getCliente($idEmpresaFiltro);
$session = $cliente->getSessionEmp($correo);
$nombres = '';
foreach ($session as $key) {
    $nombres = trim(($key['nombreEmp'] ?? '') . ' ' . ($key['apellido'] ?? ''));
}

include __DIR__ . "/../views/vistaCliente.php";
