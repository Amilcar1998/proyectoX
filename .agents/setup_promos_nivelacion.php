<?php
require_once __DIR__ . '/../db/conexion.php';
$con = new Conexion();
$conn = $con->getConnection();

echo "=== CREAR TABLA DE HISTORIAL DE PRECIOS Y PROMOCIONES ===\n";

$sqlTabla = "
CREATE TABLE IF NOT EXISTS `historico_precios_promociones` (
    `idHistorico` INT(11) NOT NULL AUTO_INCREMENT,
    `idReceta` INT(11) NOT NULL,
    `tipo_cambio` ENUM('nivelacion', 'promocion') NOT NULL DEFAULT 'nivelacion',
    `precio_anterior` DECIMAL(10,2) NOT NULL,
    `precio_nuevo` DECIMAL(10,2) NOT NULL,
    `porcentaje_descuento` INT(11) DEFAULT 0,
    `fecha_inicio` DATETIME NULL DEFAULT NULL,
    `fecha_fin` DATETIME NULL DEFAULT NULL,
    `motivo` VARCHAR(255) NULL DEFAULT NULL,
    `usuario` VARCHAR(100) NOT NULL DEFAULT 'admin',
    `estado` ENUM('activa', 'finalizada', 'cancelada', 'aplicada') NOT NULL DEFAULT 'aplicada',
    `creado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`idHistorico`),
    KEY `idx_receta` (`idReceta`),
    KEY `idx_estado` (`estado`),
    KEY `idx_fechas` (`fecha_inicio`, `fecha_fin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

$conn->query($sqlTabla);
echo "Tabla historico_precios_promociones lista.\n";

// Verificar y agregar columnas de fechas a receta si no existen
$cols = [
    'fecha_inicio_promo' => 'DATETIME NULL DEFAULT NULL',
    'fecha_fin_promo' => 'DATETIME NULL DEFAULT NULL',
    'precio_base_regular' => 'DECIMAL(10,2) NULL DEFAULT NULL'
];

foreach ($cols as $colName => $colDef) {
    $check = $conn->query("SHOW COLUMNS FROM receta LIKE '$colName'");
    if ($check && $check->num_rows === 0) {
        $conn->query("ALTER TABLE receta ADD COLUMN `$colName` $colDef");
        echo "Columna $colName agregada a receta.\n";
    }
}

// Inicializar precio_base_regular con el PrecioUnitario o precio_anterior si es null
$conn->query("UPDATE receta SET precio_base_regular = IFNULL(precio_anterior, PrecioUnitario) WHERE precio_base_regular IS NULL");

// Configurar fechas de inicio y fin para las promociones activas actuales de ejemplo (activas hasta dentro de 7 días)
$fechaInicio = date('Y-m-d H:i:s', strtotime('-1 day'));
$fechaFin = date('Y-m-d H:i:s', strtotime('+7 days'));

$conn->query("UPDATE receta SET fecha_inicio_promo = '$fechaInicio', fecha_fin_promo = '$fechaFin' WHERE en_promocion = 1");

echo "=== TABLA RECETA ACTUALIZADA ===\n";
$res = $conn->query("SELECT idReceta, nombreReceta, PrecioUnitario, precio_anterior, precio_base_regular, en_promocion, fecha_inicio_promo, fecha_fin_promo FROM receta");
while ($r = $res->fetch_assoc()) {
    echo json_encode($r) . "\n";
}
