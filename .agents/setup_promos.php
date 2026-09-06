<?php
require_once __DIR__ . '/../db/conexion.php';
$con = new Conexion();
$conn = $con->getConnection();

// Verificar si existen las columnas de promoción en receta
$cols = $conn->query("SHOW COLUMNS FROM receta LIKE 'precio_anterior'");
if ($cols && $cols->num_rows === 0) {
    $conn->query("ALTER TABLE receta ADD COLUMN precio_anterior DOUBLE NULL DEFAULT NULL AFTER PrecioUnitario");
    $conn->query("ALTER TABLE receta ADD COLUMN en_promocion TINYINT(1) DEFAULT 0 AFTER precio_anterior");
    $conn->query("ALTER TABLE receta ADD COLUMN porcentaje_descuento INT DEFAULT 0 AFTER en_promocion");
    echo "Columnas agregadas a receta exitosamente.\n";
} else {
    echo "Las columnas de promoción ya existen en receta.\n";
}

// Establecer algunas promociones de ejemplo si están en 0
$conn->query("UPDATE receta SET precio_anterior = 3.10, en_promocion = 1, porcentaje_descuento = 18 WHERE idReceta = 1"); // Pollo Inicio
$conn->query("UPDATE receta SET precio_anterior = 2.80, en_promocion = 1, porcentaje_descuento = 20 WHERE idReceta = 2"); // Pollo Engorde
$conn->query("UPDATE receta SET precio_anterior = 2.95, en_promocion = 1, porcentaje_descuento = 18 WHERE idReceta = 4"); // Cerdo Engorde
$conn->query("UPDATE receta SET precio_anterior = 3.30, en_promocion = 1, porcentaje_descuento = 18 WHERE idReceta = 10"); // Balanceada

echo "=== TABLA RECETA ACTUALIZADA ===\n";
$res = $conn->query("SELECT * FROM receta ORDER BY idReceta ASC");
while ($r = $res->fetch_assoc()) {
    echo json_encode($r) . "\n";
}
