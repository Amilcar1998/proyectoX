<?php
// Simular sesión de Gerente
session_start();
$_SESSION['s1'] = 'gerente_test';
$_SESSION['id_Rol'] = 1;

require_once __DIR__ . '/../models/PromocionModel.php';
$model = new PromocionModel();
$recetas = $model->listarRecetas();

echo "=== TEST PROMOCIONES MODEL ===\n";
echo "Total recetas: " . count($recetas) . "\n";
foreach ($recetas as $r) {
    echo " - ID: {$r['idReceta']} | {$r['nombreReceta']} | \${$r['PrecioUnitario']} | Promo: {$r['en_promocion']}\n";
}

// Probar actualización
$res = $model->actualizarPrecioYPromocion(1, [
    'PrecioUnitario' => 2.54,
    'precio_anterior' => 3.10,
    'en_promocion' => 1,
    'porcentaje_descuento' => 18
]);
echo "\nActualización de prueba ID 1: " . ($res ? 'EXITOSA' : 'FALLIDA') . "\n";
