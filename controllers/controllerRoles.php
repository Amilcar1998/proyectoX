<?php
require_once __DIR__ . '/sesiones.php';
require_once __DIR__ . '/../models/RolModel.php';
require_once __DIR__ . '/../models/PermisoModel.php';
require_once __DIR__ . '/../models/AuditoriaModel.php';

$rolModel = new RolModel();
$permisoModel = new PermisoModel();
$auditoriaModel = new AuditoriaModel();
$correo = $_SESSION["s1"] ?? ($_SESSION['s2'] ?? '');

$msj = null;
$icon = null;

// Endpoint AJAX para obtener datos de un rol específico
if (isset($_GET['accion']) && $_GET['accion'] === 'obtenerRol') {
    header('Content-Type: application/json');
    $idR = (int)($_GET['idRol'] ?? 0);
    $datosRol = $rolModel->obtenerPorId($idR);
    if ($datosRol) {
        $subArr = !empty($datosRol['submodulos']) ? json_decode($datosRol['submodulos'], true) : [];
        $datosRol['submodulosArr'] = is_array($subArr) ? $subArr : [];
        echo json_encode(['exito' => true, 'rol' => $datosRol]);
    } else {
        echo json_encode(['exito' => false, 'mensaje' => 'Rol no encontrado']);
    }
    exit();
}

if (isset($_POST["insertar"])) {
    $datosRol = [
        'nombreRol' => trim($_POST['txtNombreRol'] ?? ''),
        'descripcion' => trim($_POST['txtDescripcion'] ?? ''),
        'idRolPadre' => !empty($_POST['txtIdRolPadre']) ? (int)$_POST['txtIdRolPadre'] : null,
        'acceso_total' => isset($_POST['chkAccesoTotal']) ? 1 : 0,
        'submodulos' => isset($_POST['submodulos']) && is_array($_POST['submodulos']) ? $_POST['submodulos'] : []
    ];

    $resultado = $rolModel->guardar($datosRol);
    $msj = $resultado['mensaje'];
    $icon = $resultado['exito'] ? 'success' : 'error';

    if ($resultado['exito']) {
        logAccionAuditoria(0, (string)$correo, 'crear_rol', 'controllerRoles.php', "Nuevo rol/subrol '{$datosRol['nombreRol']}' registrado con ID #{$resultado['idRol']}");
    }
}

if (isset($_POST["modificar"])) {
    $idRol = (int)($_POST['txtIdRol'] ?? 0);
    $datosRol = [
        'nombreRol' => trim($_POST['txtNombreRol'] ?? ''),
        'descripcion' => trim($_POST['txtDescripcion'] ?? ''),
        'idRolPadre' => !empty($_POST['txtIdRolPadre']) ? (int)$_POST['txtIdRolPadre'] : null,
        'acceso_total' => isset($_POST['chkAccesoTotal']) ? 1 : 0,
        'submodulos' => isset($_POST['submodulos']) && is_array($_POST['submodulos']) ? $_POST['submodulos'] : [],
        'activo' => isset($_POST['chkActivo']) ? 1 : 0
    ];

    $resultado = $rolModel->actualizar($idRol, $datosRol);
    $msj = $resultado['mensaje'];
    $icon = $resultado['exito'] ? 'success' : 'error';

    if ($resultado['exito']) {
        logAccionAuditoria(0, (string)$correo, 'modificar_rol', 'controllerRoles.php', "Rol #$idRol ('{$datosRol['nombreRol']}') actualizado");
    }
}

if (isset($_POST["cambiar_estado"])) {
    $idRol = (int)($_POST['txtIdRol'] ?? 0);
    $nuevoEstado = (int)($_POST['nuevoEstado'] ?? 1);

    $resultado = $rolModel->cambiarEstado($idRol, $nuevoEstado);
    $msj = $resultado['mensaje'];
    $icon = $resultado['exito'] ? 'success' : 'error';

    if ($resultado['exito']) {
        $estadoTxt = ($nuevoEstado === 1) ? 'activado' : 'desactivado';
        logAccionAuditoria(0, (string)$correo, 'cambiar_estado_rol', 'controllerRoles.php', "Rol #$idRol $estadoTxt");
    }
}

$listaRoles = $rolModel->listarRoles();
$catalogoSubmodulos = $permisoModel->obtenerCatalogoSubmodulosConModulo();
$rolesPadre = array_filter($listaRoles, function($r) {
    return empty($r['idRolPadre']) && (int)$r['activo'] === 1;
});

$nombres = $permisoModel->obtenerNombreUsuario();
if (empty($nombres)) {
    $nombres = (string)$correo;
}

include __DIR__ . "/../views/vistaRoles.php";
