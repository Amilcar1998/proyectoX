<?php
if (file_exists(__DIR__ . '/env_runtime.php')) {
    require_once __DIR__ . '/env_runtime.php';
}
date_default_timezone_set('America/El_Salvador');

if (!function_exists('obtenerParametroEnv')) {
    function obtenerParametroEnv(string $clave, string $defecto = ''): string {
        $val = getenv($clave);
        if ($val !== false && $val !== '') {
            return (string)$val;
        }
        if (isset($_ENV[$clave]) && (string)$_ENV[$clave] !== '') {
            return (string)$_ENV[$clave];
        }
        if (isset($_SERVER[$clave]) && (string)$_SERVER[$clave] !== '') {
            return (string)$_SERVER[$clave];
        }
        return $defecto;
    }
}

// ==============================================================================
// CONFIGURACIÓN DE BASE DE DATOS (Aiven / Remoto / Local)
// ==============================================================================
if (!defined('SERVER')) {
    $mysqlUrl = obtenerParametroEnv('MYSQL_URL') ?: obtenerParametroEnv('DATABASE_URL');
    if (!empty($mysqlUrl)) {
        $parsed = parse_url($mysqlUrl);
        $server   = $parsed['host'] ?? 'mysql-385afffc-amilcar199819-a010.e.aivencloud.com';
        $user     = $parsed['user'] ?? 'avnadmin';
        $password = $parsed['pass'] ?? base64_decode('QVZOU19vNEYzbmJUTXlHTXp0WHMzeWl5');
        $database = isset($parsed['path']) && ltrim($parsed['path'], '/') !== '' ? ltrim($parsed['path'], '/') : 'defaultdb';
        $port     = isset($parsed['port']) ? (int)$parsed['port'] : 24364;
    } else {
        $server   = obtenerParametroEnv('MYSQLHOST') ?: obtenerParametroEnv('MYSQL_HOST') ?: obtenerParametroEnv('DB_HOST', 'mysql-385afffc-amilcar199819-a010.e.aivencloud.com');
        $user     = obtenerParametroEnv('MYSQLUSER') ?: obtenerParametroEnv('MYSQL_USER') ?: obtenerParametroEnv('DB_USER', 'avnadmin');
        $password = obtenerParametroEnv('MYSQLPASSWORD') ?: obtenerParametroEnv('MYSQL_PASSWORD') ?: obtenerParametroEnv('DB_PASSWORD') ?: obtenerParametroEnv('DB_PASS') ?: obtenerParametroEnv('MYSQL_PASS') ?: obtenerParametroEnv('PASSWORD', base64_decode('QVZOU19vNEYzbmJUTXlHTXp0WHMzeWl5'));
        $database = obtenerParametroEnv('MYSQLDATABASE') ?: obtenerParametroEnv('MYSQL_DATABASE') ?: obtenerParametroEnv('DB_NAME', 'defaultdb');
        $port     = (int)(obtenerParametroEnv('MYSQLPORT') ?: obtenerParametroEnv('MYSQL_PORT') ?: obtenerParametroEnv('DB_PORT', '24364'));
    }

    define("SERVER", $server);
    define("USER", $user);
    define("PASSWORD", $password);
    define("BASE", $database);
    define("PORT", $port);
    define("CHAR", "utf8mb4");
    define("MYSQL_SSL", true);
}

// Wompi - credenciales leídas de variables de entorno o sandbox
if (!defined('WOMPI_PUBLIC_KEY')) {
    define("WOMPI_PUBLIC_KEY", obtenerParametroEnv('WOMPI_PUBLIC_KEY', 'd01ac1a6-c618-48fb-870b-8276438a79b3'));
    define("WOMPI_PRIVATE_KEY", obtenerParametroEnv('WOMPI_PRIVATE_KEY', '4bd17a7b-ed83-4505-b61e-7d456f3bfd5d'));
    define("WOMPI_API_URL", obtenerParametroEnv('WOMPI_API_URL', 'https://sandbox.wompi.co/v1/payment_intents'));
}

