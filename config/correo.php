<?php
/**
 * Configuración del Servidor y API de Correos
 * Concentrados El Gordito
 */

if (file_exists(__DIR__ . '/../db/env_runtime.php')) {
    require_once __DIR__ . '/../db/env_runtime.php';
}
if (file_exists(__DIR__ . '/../db/parametros.php')) {
    require_once __DIR__ . '/../db/parametros.php';
}

$obtenerEnv = function(string $clave, string $defecto = ''): string {
    if (function_exists('obtenerParametroEnv')) {
        return obtenerParametroEnv($clave, $defecto);
    }
    $val = getenv($clave);
    if ($val !== false && $val !== '') return (string)$val;
    if (isset($_ENV[$clave]) && (string)$_ENV[$clave] !== '') return (string)$_ENV[$clave];
    if (isset($_SERVER[$clave]) && (string)$_SERVER[$clave] !== '') return (string)$_SERVER[$clave];
    return $defecto;
};

$resendApiKey = $obtenerEnv('RESEND_API_KEY');
$smtpHost     = $obtenerEnv('SMTP_HOST', 'sandbox.smtp.mailtrap.io');
$smtpUser     = $obtenerEnv('SMTP_USER', 'e159d11f9e693d');
$smtpPass     = $obtenerEnv('SMTP_PASSWORD') ?: $obtenerEnv('SMTP_PASS') ?: $obtenerEnv('clave', 'dc7970754f333b');
$smtpPort     = (int)$obtenerEnv('SMTP_PORT', '2525');
$smtpSecure   = $obtenerEnv('SMTP_SECURE', 'tls');
$fromMail     = $obtenerEnv('MAIL_FROM_ADDRESS', 'onboarding@resend.dev');
$fromName     = $obtenerEnv('MAIL_FROM_NAME', 'Concentrados El Gordito');
$metodo       = !empty($resendApiKey) ? 'resend' : (!empty($obtenerEnv('SMTP_HOST')) ? 'smtp' : 'resend');

return [
    'metodo'           => $metodo,
    'resend_api_key'   => $resendApiKey,
    'remitente_nombre' => $fromName,
    'remitente_correo' => $fromMail,
    'servidor'         => $smtpHost,
    'puerto'           => $smtpPort,
    'seguridad'        => $smtpSecure,
    'usuario'          => $smtpUser,
    'clave'            => $smtpPass,
    'tiempo_espera'    => 15,
    'depuracion'       => false
];
