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

if (!defined('SERVER')) {
    $mysqlUrl = obtenerParametroEnv('MYSQL_PRIVATE_URL') 
             ?: obtenerParametroEnv('MYSQL_URL') 
             ?: obtenerParametroEnv('DATABASE_URL');

    if (!empty($mysqlUrl)) {
        $parsed = parse_url($mysqlUrl);
        $server   = $parsed['host'] ?? '127.0.0.1';
        $user     = $parsed['user'] ?? 'root';
        $password = $parsed['pass'] ?? '';
        $database = isset($parsed['path']) && ltrim($parsed['path'], '/') !== '' ? ltrim($parsed['path'], '/') : obtenerParametroEnv('MYSQLDATABASE', 'railway');
        $port     = isset($parsed['port']) ? (int)$parsed['port'] : 3306;
    } else {
        $server   = obtenerParametroEnv('MYSQLHOST') 
                 ?: obtenerParametroEnv('MYSQL_HOST') 
                 ?: obtenerParametroEnv('DB_HOST') 
                 ?: obtenerParametroEnv('SERVER', '127.0.0.1');
        $user     = obtenerParametroEnv('MYSQLUSER') 
                 ?: obtenerParametroEnv('MYSQL_USER') 
                 ?: obtenerParametroEnv('DB_USER') 
                 ?: obtenerParametroEnv('USER', 'root');
        $password = obtenerParametroEnv('MYSQLPASSWORD') 
                 ?: obtenerParametroEnv('MYSQL_PASSWORD') 
                 ?: obtenerParametroEnv('DB_PASSWORD') 
                 ?: obtenerParametroEnv('PASSWORD', '');
        $database = obtenerParametroEnv('MYSQLDATABASE') 
                 ?: obtenerParametroEnv('MYSQL_DATABASE') 
                 ?: obtenerParametroEnv('DB_NAME') 
                 ?: obtenerParametroEnv('BASE', 'railway');
        $port     = (int)(obtenerParametroEnv('MYSQLPORT') 
                 ?: obtenerParametroEnv('MYSQL_PORT') 
                 ?: obtenerParametroEnv('DB_PORT', '3306'));
    }

    define("SERVER", $server);
    define("USER", $user);
    define("PASSWORD", $password);
    define("BASE", $database);
    define("PORT", $port);
    define("CHAR", "utf8mb4");
}

// Wompi - credenciales leídas de variables de entorno o sandbox
if (!defined('WOMPI_PUBLIC_KEY')) {
    define("WOMPI_PUBLIC_KEY", obtenerParametroEnv('WOMPI_PUBLIC_KEY', 'd01ac1a6-c618-48fb-870b-8276438a79b3'));
    define("WOMPI_PRIVATE_KEY", obtenerParametroEnv('WOMPI_PRIVATE_KEY', '4bd17a7b-ed83-4505-b61e-7d456f3bfd5d'));
    define("WOMPI_API_URL", obtenerParametroEnv('WOMPI_API_URL', 'https://sandbox.wompi.co/v1/payment_intents'));
}

