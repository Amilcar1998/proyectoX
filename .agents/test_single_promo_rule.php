<?php
require_once __DIR__ . '/../models/PromocionModel.php';
require_once __DIR__ . '/../models/LandingModel.php';

$promocionModel = new PromocionModel();
$landingModel = new LandingModel();

echo "=== TEST: REGLA DE PROMOCIÓN ÚNICA ===\n";

// 1. Activar promoción en Receta 1 (Pollo Inicio)
echo "\n1. Activando promoción en Receta 1 (Pollo Inicio: Regular $3.10 -> Oferta $2.54)...\n";
$promocionModel->crearPromocionTemporal(1, [
    'precio_oferta' => 2.54,
    'precio_regular' => 3.10,
    'fecha_inicio' => date('Y-m-d H:i:s'),
    'fecha_fin' => date('Y-m-d H:i:s', strtotime('+7 days')),
    'motivo' => 'Campaña Avícola Inicial'
], 'gerente_comercial');

$activa1 = $promocionModel->obtenerPromocionActiva();
echo "Promoción activa actual en el sistema: " . ($activa1 ? "Receta #{$activa1['idReceta']} ({$activa1['nombreReceta']}) | Oferta: \${$activa1['PrecioUnitario']}" : 'NINGUNA') . "\n";

// 2. Intentar activar nueva promoción en Receta 4 (Cerdo Engorde)
echo "\n2. Activando NUEVA promoción en Receta 4 (Cerdo Engorde: Regular $2.95 -> Oferta $2.40)...\n";
$promocionModel->crearPromocionTemporal(4, [
    'precio_oferta' => 2.40,
    'precio_regular' => 2.95,
    'fecha_inicio' => date('Y-m-d H:i:s'),
    'fecha_fin' => date('Y-m-d H:i:s', strtotime('+5 days')),
    'motivo' => 'Campaña Porcina Destacada'
], 'gerente_comercial');

// 3. Verificar que Receta 1 se canceló y restauró, y solo Receta 4 quedó activa
$receta1 = $promocionModel->obtenerPorId(1);
$receta4 = $promocionModel->obtenerPorId(4);
$activaFinal = $promocionModel->obtenerPromocionActiva();

echo "\n3. Estado post-reemplazo:\n";
echo " - Receta 1 (Anterior): Precio = \${$receta1['PrecioUnitario']} | Promo = {$receta1['en_promocion']} (Restaurada al precio base)\n";
echo " - Receta 4 (Nueva): Precio = \${$receta4['PrecioUnitario']} | Promo = {$receta4['en_promocion']} (Activa)\n";
echo " - Única Promoción Activa Global: Receta #{$activaFinal['idReceta']} ({$activaFinal['nombreReceta']})\n";

// 4. Verificar qué ve el cliente en la Landing Page
echo "\n4. Visualización en la Landing Page:\n";
$catalogo = $landingModel->obtenerProductosCatalogo();
$totalPromosLanding = 0;
foreach ($catalogo as $p) {
    if ($p['en_promocion']) {
        echo " [PROMO ACTIVA EN LANDING] {$p['nombre']} | Hoy: \${$p['precio_formato']} (Antes \${$p['precio_anterior_formato']} -{$p['porcentaje_descuento']}%)\n";
        $totalPromosLanding++;
    }
}
echo "Total de promociones visibles en la Landing: $totalPromosLanding (Debe ser exactamente 1)\n";
