<?php
require_once __DIR__ . '/sesiones.php';
require_once __DIR__ . '/../models/empleadoModel.php';
require_once __DIR__ . '/../models/AuditoriaModel.php';
require_once __DIR__ . '/../models/PermisoModel.php';

$obEmp = new EmpleadoModel();
$auditoriaModel = new AuditoriaModel();
$permisoModel = new PermisoModel();
$correo = $_SESSION["s1"] ?? ($_SESSION['s2'] ?? '');

// Endpoint AJAX para obtener los submódulos asignados a un usuario específico
if (isset($_GET['accion']) && $_GET['accion'] === 'obtenerSubmodulosUsuario') {
    header('Content-Type: application/json');
    $idUser = (int)($_GET['idUsuario'] ?? 0);
    $subs = $permisoModel->obtenerSubmodulosDeUsuario($idUser);
    echo json_encode(['exito' => true, 'submodulos' => $subs]);
    exit();
}

$msj = null;
$icon = null;

if (isset($_POST["insertar"])) {
    $datosEmpleado = [
        'nombre' => trim($_POST['txtNombres'] ?? ''),
        'apellido' => trim($_POST['txtApellidos'] ?? ''),
        'genero' => $_POST['txtGenero'] ?? '',
        'idPuesto' => (int)($_POST['txtCargo'] ?? 2),
        'idRol' => (int)($_POST['txtRol'] ?? 2)
    ];

    $submodulosSeleccionados = isset($_POST['submodulos']) && is_array($_POST['submodulos']) ? $_POST['submodulos'] : [];

    $resultado = $obEmp->crearEmpleadoConUsuario($datosEmpleado);
    if ($resultado['exito']) {
        if (!empty($resultado['idUsuario']) && !empty($submodulosSeleccionados)) {
            $permisoModel->guardarPermisosSubmodulosUsuario((int)$resultado['idUsuario'], $submodulosSeleccionados);
        }
        $msj = $resultado['mensaje'];
        $icon = "success";
        logAccionAuditoria(0, (string)$correo, 'crear_empleado', 'controllerEmpleado.php', "Nuevo empleado {$datosEmpleado['nombre']} {$datosEmpleado['apellido']} creado con usuario {$resultado['username']} y rol #{$resultado['idRol']}");
    } else {
        $msj = $resultado['mensaje'];
        $icon = "error";
    }
}

if (isset($_POST["modificar"])) {
    $idEmpleado = (int)($_POST['txtIdEmpleado'] ?? 0);
    $nombres = trim($_POST['txtNombres'] ?? '');
    $apellidos = trim($_POST['txtApellidos'] ?? '');
    $genero = $_POST['txtGenero'] ?? '';
    $idPuesto = (int)($_POST['txtCargo'] ?? 0);
    $idUsuario = (int)($_POST['txtIdUsuario'] ?? 0);
    $idRol = (int)($_POST['txtRol'] ?? 2);
    $submodulosSeleccionados = isset($_POST['submodulos']) && is_array($_POST['submodulos']) ? $_POST['submodulos'] : [];

    $e = new Empleado($idEmpleado, $nombres, $apellidos, $genero, '', '', $idPuesto, $idUsuario, $idRol);
    $exito = $obEmp->modificarEmpleado($e);

    if ($exito) {
        if (isset($_POST['chkActivo'])) {
            $nuevoEst = (int)$_POST['chkActivo'];
            $obEmp->cambiarEstadoEmpleado($idEmpleado, $nuevoEst);
        }
        if ($idUsuario > 0) {
            $permisoModel->guardarPermisosSubmodulosUsuario($idUsuario, $submodulosSeleccionados);
        }
        $msj = "Se ha modificado el empleado exitosamente";
        $icon = "success";
        logAccionAuditoria(0, (string)$correo, 'modificar_empleado', 'controllerEmpleado.php', "Empleado #$idEmpleado ($nombres $apellidos) actualizado con rol #$idRol");
    } else {
        $msj = "Error al modificar el empleado";
        $icon = "error";
    }
}

if (isset($_POST["cambiar_estado"])) {
    $idEmpleado = (int)($_POST['txtIdEmpleado'] ?? 0);
    $nuevoEstado = (int)($_POST['nuevoEstado'] ?? 1);
    $exito = $obEmp->cambiarEstadoEmpleado($idEmpleado, $nuevoEstado);

    $estadoTxt = ($nuevoEstado === 1) ? 'activado' : 'desactivado';
    if ($exito) {
        $msj = "Se ha $estadoTxt el empleado y su cuenta de acceso exitosamente";
        $icon = "success";
        logAccionAuditoria(0, (string)$correo, 'cambiar_estado_empleado', 'controllerEmpleado.php', "Empleado #$idEmpleado $estadoTxt");
    } else {
        $msj = "Error al cambiar el estado del empleado";
        $icon = "error";
    }
}

if (isset($_POST["eliminar"])) {
    $idEmpleado = (int)($_POST['txtIdEmpleado'] ?? 0);
    $exito = $obEmp->cambiarEstadoEmpleado($idEmpleado, 0);

    if ($exito) {
        $msj = "Se ha desactivado el empleado exitosamente";
        $icon = "success";
        logAccionAuditoria(0, (string)$correo, 'desactivar_empleado', 'controllerEmpleado.php', "Empleado #$idEmpleado desactivado");
    } else {
        $msj = "Error al desactivar el empleado";
        $icon = "error";
    }
}

$session = $obEmp->getSessionEmp($correo);
$datos = $obEmp->obtenerEmpleados();
$puesto = $obEmp->obtenerCargos();
$rolesSistema = $obEmp->obtenerRolesSistema();
$rolesJerarquia = $permisoModel->obtenerRolesConJerarquia();
$catalogoSubmodulos = $permisoModel->obtenerCatalogoSubmodulosConModulo();

$nombres = '';
foreach ($session as $key) {
    $nombres = trim(($key['nombreEmp'] ?? '') . ' ' . ($key['apellido'] ?? ''));
}

include __DIR__ . "/../views/vistaEmpleado.php";


