<?php
/**
 * Configuración del Servidor y API de Correos
 * Concentrados El Gordito
 */

$resendApiKey = getenv('RESEND_API_KEY') ?: ($_ENV['RESEND_API_KEY'] ?? ($_SERVER['RESEND_API_KEY'] ?? ''));
$smtpHost     = getenv('SMTP_HOST') ?: ($_ENV['SMTP_HOST'] ?? ($_SERVER['SMTP_HOST'] ?? 'sandbox.smtp.mailtrap.io'));
$smtpUser     = getenv('SMTP_USER') ?: ($_ENV['SMTP_USER'] ?? ($_SERVER['SMTP_USER'] ?? 'e159d11f9e693d'));
$smtpPass     = getenv('SMTP_PASSWORD') ?: getenv('SMTP_PASS') ?: ($_ENV['SMTP_PASSWORD'] ?? ($_ENV['SMTP_PASS'] ?? ($_SERVER['SMTP_PASSWORD'] ?? 'dc7970754f333b')));
$smtpPort     = (int)(getenv('SMTP_PORT') ?: ($_ENV['SMTP_PORT'] ?? ($_SERVER['SMTP_PORT'] ?? 2525)));
$smtpSecure   = getenv('SMTP_SECURE') ?: ($_ENV['SMTP_SECURE'] ?? ($_SERVER['SMTP_SECURE'] ?? 'tls'));
$fromMail     = getenv('MAIL_FROM_ADDRESS') ?: ($_ENV['MAIL_FROM_ADDRESS'] ?? ($_SERVER['MAIL_FROM_ADDRESS'] ?? 'onboarding@resend.dev'));
$fromName     = getenv('MAIL_FROM_NAME') ?: ($_ENV['MAIL_FROM_NAME'] ?? ($_SERVER['MAIL_FROM_NAME'] ?? 'Concentrados El Gordito'));
$metodo       = !empty($resendApiKey) ? 'resend' : (!empty(getenv('SMTP_HOST')) ? 'smtp' : 'resend');

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
