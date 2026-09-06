<?php
require_once __DIR__ . '/../models/LandingModel.php';
$model = new LandingModel();
$planes = $model->obtenerPlanesDisponibles();

echo "=== PLANES EXTRAÍDOS DE LA BD (" . count($planes) . ") ===\n";
foreach ($planes as $p) {
    echo "\nID: {$p['id']} | Nombre: {$p['nombre']} | Monto: \${$p['monto_formato']} | Duración: {$p['duracion_dias']} días\n";
    echo "  Descripción BD: \"{$p['descripcion']}\"\n";
    echo "  Características renderizadas:\n";
    foreach ($p['caracteristicas'] as $c) {
        echo "    * $c\n";
    }
}
