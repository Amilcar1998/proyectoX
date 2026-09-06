<?php
require_once __DIR__ . '/sesiones.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/AuditoriaModel.php';

$usuarioModel = new UsuarioModel();
$auditoriaModel = new AuditoriaModel();

$username = $_SESSION['s1'] ?? ($_SESSION['s2'] ?? ($_SESSION['c1'] ?? ''));

if (empty($_SESSION['debe_cambiar_pass'])) {
    header("Location: controllerDashboard.php");
    exit();
}

$error = '';
$success = '';
$claveActual = $_SESSION['pass_temp_ingresada'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_clave'])) {
    $claveActual = $_POST['clave_actual'] ?? '';
    $nuevaClave = $_POST['nueva_clave'] ?? '';
    $confirmarClave = $_POST['confirmar_clave'] ?? '';

    if (empty($claveActual) || empty($nuevaClave) || empty($confirmarClave)) {
        $error = 'Todos los campos son obligatorios.';
    } elseif ($nuevaClave !== $confirmarClave) {
        $error = 'La nueva contraseña y su confirmación no coinciden.';
    } else {
        $resultado = $usuarioModel->cambiarClaveObligatoria($username, $claveActual, $nuevaClave);
        if ($resultado['exito']) {
            $_SESSION['debe_cambiar_pass'] = 0;
            unset($_SESSION['pass_temp_ingresada']);
            $idUsuario = function_exists('obtenerIdUsuarioPorUsername') ? obtenerIdUsuarioPorUsername($username) : 0;
            $auditoriaModel->log($idUsuario, $username, 'cambio_clave_obligatorio', 'usuarios', 'Cambio de clave inicial completado exitosamente.');
            header("Location: controllerDashboard.php");
            exit();
        } else {
            $error = $resultado['mensaje'];
        }
    }
}

include __DIR__ . '/../views/vistaCambiarClaveObligatoria.php';
