<?php
date_default_timezone_set('America/El_Salvador');
if (!defined('SERVER')) {
    $mysqlUrl = getenv('MYSQL_URL') ?: getenv('DATABASE_URL') ?: '';
    if (!empty($mysqlUrl)) {
        $parsed = parse_url($mysqlUrl);
        $server   = $parsed['host'] ?? '127.0.0.1';
        $user     = $parsed['user'] ?? 'root';
        $password = $parsed['pass'] ?? '';
        $database = isset($parsed['path']) ? ltrim($parsed['path'], '/') : 'concentrados';
        $port     = isset($parsed['port']) ? (int)$parsed['port'] : 3306;
    } else {
        $server   = getenv('MYSQLHOST') ?: getenv('DB_HOST') ?: getenv('SERVER') ?: "127.0.0.1";
        $user     = getenv('MYSQLUSER') ?: getenv('DB_USER') ?: getenv('USER') ?: "root";
        $password = getenv('MYSQLPASSWORD') ?: getenv('DB_PASSWORD') ?: getenv('PASSWORD') ?: "";
        $database = getenv('MYSQLDATABASE') ?: getenv('DB_NAME') ?: getenv('BASE') ?: "concentrados";
        $port     = (int)(getenv('MYSQLPORT') ?: getenv('DB_PORT') ?: 3306);
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
    define("WOMPI_PUBLIC_KEY", getenv('WOMPI_PUBLIC_KEY') ?: "d01ac1a6-c618-48fb-870b-8276438a79b3");
    define("WOMPI_PRIVATE_KEY", getenv('WOMPI_PRIVATE_KEY') ?: "4bd17a7b-ed83-4505-b61e-7d456f3bfd5d");
    define("WOMPI_API_URL", getenv('WOMPI_API_URL') ?: "https://sandbox.wompi.co/v1/payment_intents");
}
