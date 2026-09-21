<?php
/**
 * Modelo para el Control de Cambios y Migraciones de Base de Datos
 * Arquitectura MVC - 100% Español
 * Concentrados El Gordito
 */

class GestorControlCambios
{
    private string $archivoManifiesto;

    public function __construct()
    {
        $this->archivoManifiesto = __DIR__ . '/control_cambios.json';
    }

    /**
     * Obtiene el listado completo de cambios registrados
     */
    public function listarCambios(): array
    {
        if (!file_exists($this->archivoManifiesto)) {
            return [];
        }
        $contenido = file_get_contents($this->archivoManifiesto);
        $arreglo = json_decode($contenido, true);
        return is_array($arreglo) ? $arreglo : [];
    }

    /**
     * Registra un nuevo cambio en el manifiesto
     */
    public function registrarCambio(string $identificador, string $descripcion, string $autor = 'Desarrollador'): bool
    {
        $cambios = $this->listarCambios();
        
        // Evitar duplicados por identificador
        foreach ($cambios as $cambio) {
            if (($cambio['id'] ?? '') === $identificador) {
                return false;
            }
        }

        $nuevoCambio = [
            'id' => $identificador,
            'fecha' => date('Y-m-d H:i:s'),
            'descripcion' => $descripcion,
            'autor' => $autor,
            'estado_local' => 'aplicado',
            'estado_remoto' => 'pendiente'
        ];

        $cambios[] = $nuevoCambio;
        return (bool)file_put_contents(
            $this->archivoManifiesto,
            json_encode($cambios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    /**
     * Marca un cambio como sincronizado con la base de datos remota
     */
    public function marcarComoSincronizado(string $identificador): bool
    {
        $cambios = $this->listarCambios();
        $encontrado = false;

        foreach ($cambios as &$cambio) {
            if (($cambio['id'] ?? '') === $identificador) {
                $cambio['estado_remoto'] = 'sincronizado';
                $cambio['fecha_sincronizacion'] = date('Y-m-d H:i:s');
                $encontrado = true;
                break;
            }
        }

        if ($encontrado) {
            return (bool)file_put_contents(
                $this->archivoManifiesto,
                json_encode($cambios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
        }

        return false;
    }

    /**
     * Obtiene los cambios pendientes de sincronizar con el repositorio/remoto
     */
    public function obtenerCambiosPendientes(): array
    {
        $cambios = $this->listarCambios();
        return array_values(array_filter($cambios, function ($item) {
            return ($item['estado_remoto'] ?? '') === 'pendiente';
        }));
    }
}
