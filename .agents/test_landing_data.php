<?php
// Test rápido: simular la carga de datos que hace controllerLanding.php
require_once __DIR__ . '/../models/LandingModel.php';

$model = new LandingModel();
$planes = $model->obtenerPlanesDisponibles();
$productos = $model->obtenerProductosCatalogo();

echo "=== PLANES (" . count($planes) . " encontrados) ===\n";
foreach ($planes as $p) {
    echo "  ID: {$p['id']} | {$p['nombre']} | \${$p['monto_formato']} | activo\n";
    echo "  Características: " . count($p['caracteristicas']) . " items\n";
}

echo "\n=== PRODUCTOS (" . count($productos) . " encontrados) ===\n";
foreach ($productos as $p) {
    echo "  ID: {$p['id']} | {$p['nombre']} | \${$p['precio_formato']}\n";
}
