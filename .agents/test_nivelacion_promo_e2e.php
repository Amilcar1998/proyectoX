<?php
require_once __DIR__ . '/../models/PromocionModel.php';
require_once __DIR__ . '/../models/LandingModel.php';

$promocionModel = new PromocionModel();
$landingModel = new LandingModel();

echo "=== 1. TEST NIVELACIÓN DE PRECIO (Receta 3) ===\n";
$exitoNivelacion = $promocionModel->aplicarNivelacion(3, 1.65, 'Ajuste de costo de soya y avena', 'gerente_test');
echo "Nivelación aplicada: " . ($exitoNivelacion ? 'SI' : 'NO') . "\n";

$receta3 = $promocionModel->obtenerPorId(3);
echo "Receta 3 en BD: PrecioUnitario = \${$receta3['PrecioUnitario']} | Base = \${$receta3['precio_base_regular']} | Promo = {$receta3['en_promocion']}\n";

echo "\n=== 2. TEST CREACIÓN DE PROMOCIÓN TEMPORAL (Receta 5) ===\n";
$fechaInicio = date('Y-m-d H:i:s');
$fechaFin = date('Y-m-d H:i:s', strtotime('+3 days'));
$exitoPromo = $promocionModel->crearPromocionTemporal(5, [
    'precio_oferta' => 1.25,
    'precio_regular' => 1.55,
    'fecha_inicio' => $fechaInicio,
    'fecha_fin' => $fechaFin,
    'motivo' => 'Semana de descuento en fórmulas de finalización'
], 'gerente_test');
echo "Promoción temporal creada: " . ($exitoPromo ? 'SI' : 'NO') . "\n";

$receta5 = $promocionModel->obtenerPorId(5);
echo "Receta 5 en BD: PrecioUnitario = \${$receta5['PrecioUnitario']} | Base = \${$receta5['precio_base_regular']} | Promo = {$receta5['en_promocion']} | Fin = {$receta5['fecha_fin_promo']}\n";

echo "\n=== 3. TEST HISTORIAL REGISTRADO ===\n";
$historial = $promocionModel->obtenerHistorial(5);
foreach ($historial as $h) {
    echo "ID Histórico: {$h['idHistorico']} | Receta: {$h['nombreReceta']} | Tipo: {$h['tipo_cambio']} | Antes: \${$h['precio_anterior']} -> Nuevo: \${$h['precio_nuevo']} | Estado: {$h['estado']} | Usuario: {$h['usuario']}\n";
}

echo "\n=== 4. TEST DE EXPIRACIÓN AUTOMÁTICA ===\n";
// Simular una promoción expirada (fecha_fin en el pasado)
$con = new Conexion();
$con->getConnection()->query("UPDATE receta SET en_promocion = 1, fecha_fin_promo = '2025-01-01 10:00:00', PrecioUnitario = 1.00, precio_base_regular = 1.55 WHERE idReceta = 5");

$expiradas = $promocionModel->actualizarPromocionesExpiradas();
echo "Promociones expiradas detectadas y procesadas: $expiradas\n";

$receta5Restaurada = $promocionModel->obtenerPorId(5);
echo "Receta 5 post-expiración: PrecioUnitario = \${$receta5Restaurada['PrecioUnitario']} (restaurado) | Promo = {$receta5Restaurada['en_promocion']}\n";

// Reactivar promo de prueba para Receta 1, 2, 4, 10
$promocionModel->crearPromocionTemporal(1, ['precio_oferta' => 2.54, 'precio_regular' => 3.10, 'fecha_inicio' => date('Y-m-d H:i:s'), 'fecha_fin' => date('Y-m-d H:i:s', strtotime('+7 days')), 'motivo' => 'Campaña Avícola'], 'admin');
$promocionModel->crearPromocionTemporal(2, ['precio_oferta' => 2.25, 'precio_regular' => 2.80, 'fecha_inicio' => date('Y-m-d H:i:s'), 'fecha_fin' => date('Y-m-d H:i:s', strtotime('+7 days')), 'motivo' => 'Campaña Avícola'], 'admin');
$promocionModel->crearPromocionTemporal(4, ['precio_oferta' => 2.43, 'precio_regular' => 2.95, 'fecha_inicio' => date('Y-m-d H:i:s'), 'fecha_fin' => date('Y-m-d H:i:s', strtotime('+7 days')), 'motivo' => 'Campaña Porcina'], 'admin');
$promocionModel->crearPromocionTemporal(10, ['precio_oferta' => 2.69, 'precio_regular' => 3.30, 'fecha_inicio' => date('Y-m-d H:i:s'), 'fecha_fin' => date('Y-m-d H:i:s', strtotime('+7 days')), 'motivo' => 'Campaña Integral'], 'admin');

echo "\n=== 5. CATÁLOGO EN LANDING PAGE ===\n";
$catalogo = $landingModel->obtenerProductosCatalogo();
foreach ($catalogo as $p) {
    echo "Fórmula #{$p['id']}: {$p['nombre']} | Precio Venta: \${$p['precio_formato']}";
    if ($p['en_promocion']) {
        echo " [OFERTA ACTIVA: Antes \${$p['precio_anterior_formato']} -> Hoy \${$p['precio_formato']} (-{$p['porcentaje_descuento']}%) Ahorro: \${$p['ahorro_formato']}]";
    } else {
        echo " [PRECIO DIRECTO NIVELADO]";
    }
    echo "\n";
}
