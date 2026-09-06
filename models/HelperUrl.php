<?php
/**
 * Helper Global de URLs Dinámicas y Rutas Base
 * Arquitectura MVC - 100% Español
 * Concentrados El Gordito
 */

if (!function_exists('obtenerUrlBase')) {
    /**
     * Retorna la URL base absoluta del sistema detectando protocolo, host, puerto y subcarpetas en cualquier servidor
     */
    function obtenerUrlBase(string $rutaRelativa = ''): string
    {
        $protocolo = 'http';
        if (
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
            (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
            (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') ||
            (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443)
        ) {
            $protocolo = 'https';
        }

        $host = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? 'localhost');
        
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $dirScript = str_replace('\\', '/', dirname($scriptName));
        
        // Si la petición actual se ejecuta dentro de una subcarpeta interna del proyecto
        if (preg_match('#/(controllers|views|wompi|models|api|scratch|db)$#i', $dirScript)) {
            $dirBase = dirname($dirScript);
        } else {
            $dirBase = $dirScript;
        }
        
        $dirBase = rtrim(str_replace('\\', '/', $dirBase), '/');
        $urlBase = $protocolo . '://' . $host . ($dirBase !== '' && $dirBase !== '.' ? $dirBase : '');

        if (!empty($rutaRelativa)) {
            return rtrim($urlBase, '/') . '/' . ltrim($rutaRelativa, '/');
        }

        return rtrim($urlBase, '/');
    }
}

if (!function_exists('obtenerPrefijoRelativo')) {
    /**
     * Retorna '../' si la ejecución actual está dentro de /controllers/ o /views/, o '' si está en la raíz
     */
    function obtenerPrefijoRelativo(): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $dirScript = str_replace('\\', '/', dirname($scriptName));
        if (preg_match('#/(controllers|views|wompi|models|api|scratch|db)$#i', $dirScript)) {
            return '../';
        }
        return '';
    }
}

if (!function_exists('obtenerUrlControlador')) {
    /**
     * Retorna la URL de un controlador
     */
    function obtenerUrlControlador(string $nombreControlador): string
    {
        $prefijo = (strpos($_SERVER['REQUEST_URI'] ?? '', '/controllers/') !== false || strpos($_SERVER['SCRIPT_NAME'] ?? '', '/controllers/') !== false) ? '' : 'controllers/';
        return $prefijo . ltrim($nombreControlador, '/');
    }
}
