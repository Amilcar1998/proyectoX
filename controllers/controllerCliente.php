<?php
require_once __DIR__ . '/sesiones.php';
require_once __DIR__ . '/../models/ClienteModel.php';
require_once __DIR__ . '/../models/EmpresaModel.php';

$cliente = new ClienteModel();
$empresaModel = new EmpresaModel();

$correo = $_SESSION['s1'] ?? ($_SESSION['s2'] ?? '');
$idEmpresaSesion = (int)($_SESSION['idEmpresa'] ?? 1);
$esSuperUsuario = !empty($_SESSION['esSuperUsuario']) || (($correo ?? '') === 'amilcar199819@gmail.com');
$idEmpresaFiltro = $esSuperUsuario ? 0 : $idEmpresaSesion;

$listaEmpresas = $empresaModel->listarTodas();
$empresaActual = $empresaModel->obtenerPorId($idEmpresaSesion);

$dominioEmpresaActual = 'gordito.com';
if ($empresaActual && !empty($empresaActual['correo']) && strpos($empresaActual['correo'], '@') !== false) {
    $dominioEmpresaActual = trim(explode('@', $empresaActual['correo'])[1]);
}

if (isset($_REQUEST["insertar"])) {
    $idEmpresaTarget = $esSuperUsuario ? (int)($_REQUEST['idEmpresa'] ?? $idEmpresaSesion) : $idEmpresaSesion;
    if ($idEmpresaTarget <= 0) $idEmpresaTarget = 1;

    $resultado = $cliente->registrarClienteAutogenerado($_REQUEST, $idEmpresaTarget);
    if ($resultado['exito']) {
        $msj = "Se ha registrado el cliente y su usuario autogenerado: " . $resultado['username'];
        $icon = "success";
    } else {
        $msj = $resultado['mensaje'] ?? "Error: No se pudo agregar la persona.";
        $icon = "warning";
    }
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

$Rcliente = $cliente->getCliente($idEmpresaFiltro);
$session = $cliente->getSessionEmp($correo);
$nombres = '';
foreach ($session as $key) {
    $nombres = trim(($key['nombreEmp'] ?? '') . ' ' . ($key['apellido'] ?? ''));
}
if (empty($nombres)) {
    require_once __DIR__ . '/../models/PermisoModel.php';
    $nombres = (new PermisoModel())->obtenerNombreUsuario((int)($_SESSION['idUsuario'] ?? 0), $correo);
}

include __DIR__ . "/../views/vistaCliente.php";
