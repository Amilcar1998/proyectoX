<?php
$html = file_get_contents('http://localhost/ProyectoX/');

echo "=== VERIFICACIÓN DEL CATÁLOGO DE PRODUCTOS EN LA LANDING ===\n";
preg_match_all('/<h3 class="product-title">([^<]+)<\/h3>/', $html, $titulos);
preg_match_all('/<div class="product-recipe-desc">\s*([^<]+)\s*<\/div>/', $html, $recetas);
preg_match_all('/<div class="price-val">\s*<span>\$<\/span>([^<]+)\s*<\/div>/', $html, $precios);
preg_match_all('/<span class="price-old"[^>]*>\s*\$([^<]+)\s*<\/span>/', $html, $preciosOld);

echo "Total productos renderizados: " . count($titulos[1]) . "\n";
for ($i = 0; $i < count($titulos[1]); $i++) {
    $t = trim($titulos[1][$i]);
    $p = trim($precios[1][$i] ?? '0.00');
    $r = trim($recetas[1][$i] ?? 'N/A');
    echo "\nProducto #" . ($i + 1) . ": $t | Precio Hoy: \$$p\n";
    echo "  Receta/Fórmula: $r\n";
}

echo "\nPromociones activas encontradas con precio anterior (" . count($preciosOld[1]) . "):\n";
foreach ($preciosOld[1] as $po) {
    echo "  - Precio anterior tachado: \$$po\n";
}
