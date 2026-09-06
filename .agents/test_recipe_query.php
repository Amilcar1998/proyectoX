<?php
require_once __DIR__ . '/../db/conexion.php';
$con = new Conexion();
$conn = $con->getConnection();

$consulta = "
    SELECT r.idReceta, r.nombreReceta, r.PrecioUnitario, r.precio_anterior, r.en_promocion, r.porcentaje_descuento,
           GROUP_CONCAT(DISTINCT CONCAT(mp.NombreMP, ' (', dr.cantidaSa, ' lb)') ORDER BY dr.idDetalleReceta ASC SEPARATOR ', ') AS formula_ingredientes,
           GROUP_CONCAT(DISTINCT mp.NombreMP ORDER BY dr.idDetalleReceta ASC SEPARATOR ', ') AS lista_ingredientes
    FROM receta r
    LEFT JOIN detallereceta dr ON r.idReceta = dr.IdReceta
    LEFT JOIN materiaprima mp ON dr.idMateriaPrima = mp.idMateriaPrima
    GROUP BY r.idReceta, r.nombreReceta, r.PrecioUnitario, r.precio_anterior, r.en_promocion, r.porcentaje_descuento
    ORDER BY r.idReceta ASC
";

$res = $conn->query($consulta);
while ($r = $res->fetch_assoc()) {
    echo "ID: {$r['idReceta']} | {$r['nombreReceta']} | Precio: \${$r['PrecioUnitario']}";
    if ($r['en_promocion'] && $r['precio_anterior']) {
        echo " [PROMO ACTIVA: Antes \${$r['precio_anterior']} -> Hoy \${$r['PrecioUnitario']} (-{$r['porcentaje_descuento']}%) ]";
    }
    echo "\n  Fórmula: " . ($r['formula_ingredientes'] ?: 'Fórmula estándar de la granja') . "\n";
    echo "  Ingredientes: " . ($r['lista_ingredientes'] ?: 'Materia prima balanceada') . "\n\n";
}
