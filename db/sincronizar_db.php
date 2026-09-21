<?php
/**
 * Herramienta de Sincronización de Base de Datos (Local <-> Nube)
 * Concentrados El Gordito
 * 
 * Uso:
 *   php db/sincronizar_db.php --estado
 *   php db/sincronizar_db.php --clonar-remota-a-local
 */

require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/migraciones/GestorControlCambios.php';

$opcion = $argv[1] ?? '--estado';
$gestor = new GestorControlCambios();

echo "\n===============================================================\n";
echo "   CONCENTRADOS EL GORDITO - GESTOR DE BASE DE DATOS Y ENTORNO\n";
echo "===============================================================\n";

if ($opcion === '--estado') {
    echo "Entorno detectado:   [" . strtoupper(defined('ENTORNO_APP') ? ENTORNO_APP : 'desconocido') . "]\n";
    echo "Servidor activo:     " . SERVER . ":" . PORT . "\n";
    echo "Base de datos:       " . BASE . "\n";
    echo "Usuario BD:          " . USER . "\n";
    echo "Modo SSL:            " . (MYSQL_SSL ? 'Activado (Nube/Aiven)' : 'Desactivado (Local)') . "\n\n";

    $cambios = $gestor->listarCambios();
    echo "--- HISTORIAL DE CONTROL DE CAMBIOS (" . count($cambios) . " registros) ---\n";
    foreach ($cambios as $idx => $c) {
        $num = $idx + 1;
        $remotoBadge = ($c['estado_remoto'] === 'sincronizado') ? '[SYNC REMOTO]' : '[PENDIENTE REMOTO]';
        echo "$num. {$c['id']} $remotoBadge\n";
        echo "   Fecha: {$c['fecha']} | Autor: {$c['autor']}\n";
        echo "   Detalle: {$c['descripcion']}\n\n";
    }

    $pendientes = $gestor->obtenerCambiosPendientes();
    if (empty($pendientes)) {
        echo "[✓] Todo se encuentra sincronizado entre local y remoto.\n";
    } else {
        echo "[!] Hay " . count($pendientes) . " cambio(s) pendientes de sincronizar con producción.\n";
    }
} elseif ($opcion === '--clonar-remota-a-local') {
    require __DIR__ . '/../scratch/sincronizar_remota_a_local.php';
} else {
    echo "Comandos disponibles:\n";
    echo "  php db/sincronizar_db.php --estado                 (Ver estado y log de cambios)\n";
    echo "  php db/sincronizar_db.php --clonar-remota-a-local   (Traer datos de Aiven a MySQL Local)\n";
}

echo "===============================================================\n\n";
