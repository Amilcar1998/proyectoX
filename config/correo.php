<?php
/**
 * Configuración del Servidor y API de Correos
 * Concentrados El Gordito
 */

return [
    // Método de envío: 'resend' (API REST gratuita), 'brevo' (API REST) o 'smtp'
    'metodo' => 'resend',
    
    // Configuración Resend API (3,000 correos reales gratis al mes)
    'resend_api_key' => getenv('RESEND_API_KEY') ?: '',
    
    // Remitente: usa 'onboarding@resend.dev' para enviar correos directamente a tu correo registrado,
    // o el correo de tu dominio verificado en Resend
    'remitente_nombre' => 'Concentrados El Gordito',
    'remitente_correo' => 'onboarding@resend.dev',
    
    // Configuración SMTP de respaldo
    'servidor' => 'sandbox.smtp.mailtrap.io',
    'puerto' => 2525,
    'seguridad' => 'tls',
    'usuario' => 'e159d11f9e693d',
    'clave'   => 'dc7970754f333b',
    
    'tiempo_espera' => 15,
    'depuracion' => true
];
