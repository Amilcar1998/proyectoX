<?php
require_once __DIR__ . '/../db/conexion.php';
$con = new Conexion();
$conn = $con->getConnection();

echo "=== RECETAS Y SUS INGREDIENTES / MATERIAS PRIMAS ===\n";
$res = $conn->query("
    SELECT r.idReceta, r.nombreReceta, r.PrecioUnitario, 
           GROUP_CONCAT(CONCAT(mp.NombreMP, ' (', dr.cantidaSa, ' lb)') SEPARATOR ', ') AS ingredientes
    FROM receta r
    LEFT JOIN detallereceta dr ON r.idReceta = dr.IdReceta
    LEFT JOIN materiaprima mp ON dr.idMateriaPrima = mp.idMateriaPrima
    GROUP BY r.idReceta, r.nombreReceta, r.PrecioUnitario
    ORDER BY r.idReceta ASC
");

while ($r = $res->fetch_assoc()) {
    echo "ID: {$r['idReceta']} | {$r['nombreReceta']} | \${$r['PrecioUnitario']}\n";
    echo "  Ingredientes/Fórmula: {$r['ingredientes']}\n\n";
}
