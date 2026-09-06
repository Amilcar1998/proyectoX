<?php
require_once __DIR__ . '/../models/UsuarioModel.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$esRaiz = (basename($_SERVER['SCRIPT_NAME'] ?? '') === 'reset_password.php');
$rutaLogin = $esRaiz ? 'controllers/controlUser.php' : 'controlUser.php';
$rutaBase = $esRaiz ? 'controllers/' : '';

if (isset($_SESSION['s1']) || isset($_SESSION['s2']) || isset($_SESSION['c1'])) {
    $rutaEmpleado = $esRaiz ? 'controllers/controllerEmpleado.php' : 'controllerEmpleado.php';
    header("Location: $rutaEmpleado");
    exit();
}

$obUser = new UsuarioModel();
$error = '';
$success = '';
$showForm = false;
$tokenValido = false;
$token = $_GET['token'] ?? $_POST['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevaPass = $_POST['nueva_pass'] ?? '';
    $confirmarPass = $_POST['confirmar_pass'] ?? '';

    if (empty($token) || empty($nuevaPass) || empty($confirmarPass)) {
        $error = 'Todos los campos son obligatorios.';
    } elseif ($nuevaPass !== $confirmarPass) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        $resultado = $obUser->restablecerClave($token, $nuevaPass);
        if ($resultado['exito']) {
            $success = $resultado['mensaje'];
        } else {
            $error = $resultado['mensaje'];
        }
    }

    if (!empty($error) && !empty($token)) {
        $validacion = $obUser->validarTokenRecuperacion($token);
        if ($validacion['valido']) {
            $showForm = true;
            $tokenValido = true;
        }
    }
} else {
    if (!empty($token)) {
        $validacion = $obUser->validarTokenRecuperacion($token);
        if ($validacion['valido']) {
            $showForm = true;
            $tokenValido = true;
        } else {
            $error = $validacion['mensaje'];
        }
    } else {
        header("Location: $rutaLogin");
        exit();
    }
}

include __DIR__ . '/../views/vistaRecuperarClave.php';
