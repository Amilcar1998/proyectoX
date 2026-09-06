<?php
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/RegistroLandingModel.php';

$input = json_decode(file_get_contents('php://input'), true) ?? [];

$accion  = trim((string)($input['accion'] ?? ''));
$correo  = trim(strtolower((string)($input['correo'] ?? '')));
$clave   = (string)($input['clave'] ?? '');
$nombre  = trim((string)($input['nombre'] ?? ''));
$telefono = trim((string)($input['telefono'] ?? ''));

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['exito' => false, 'mensaje' => 'Por favor ingresa un correo electrónico válido.']);
    exit;
}

$model = new RegistroLandingModel();

if ($accion === 'login') {
    $resultado = procesarLogin($model, $correo, $clave);
} elseif ($accion === 'registro') {
    $resultado = procesarRegistro($model, $nombre, $correo, $clave, $telefono);
} else {
    $resultado = ['exito' => false, 'mensaje' => 'Acción no reconocida.'];
}

echo json_encode($resultado);

// ──────────────────────────────────────────────
// Funciones auxiliares del controlador
// ──────────────────────────────────────────────

function procesarLogin(RegistroLandingModel $model, string $correo, string $clave): array
{
    if (strlen($clave) < 4) {
        return ['exito' => false, 'mensaje' => 'La contraseña es demasiado corta.'];
    }

    $usuario = $model->autenticarCliente($correo, $clave);
    if (!$usuario) {
        return ['exito' => false, 'mensaje' => 'Correo o contraseña incorrectos.'];
    }

    return [
        'exito'     => true,
        'idUsuario' => $usuario['idUsuario'],
        'nombre'    => $usuario['nombre'],
        'correo'    => $usuario['correo'],
        'telefono'  => $usuario['telefono']
    ];
}

function procesarRegistro(RegistroLandingModel $model, string $nombre, string $correo, string $clave, string $telefono): array
{
    if (empty($nombre)) {
        return ['exito' => false, 'mensaje' => 'El nombre es obligatorio.'];
    }
    if (strlen($clave) < 6) {
        return ['exito' => false, 'mensaje' => 'La contraseña debe tener al menos 6 caracteres.'];
    }
    if (empty($telefono)) {
        return ['exito' => false, 'mensaje' => 'El teléfono es obligatorio.'];
    }

    return $model->registrarUsuarioCliente([
        'nombre'   => $nombre,
        'correo'   => $correo,
        'clave'    => $clave,
        'telefono' => $telefono
    ]);
}
