<?php
require_once __DIR__ . '/../db/conexion.php';
$con = new Conexion();
$conn = $con->getConnection();

echo "=== BASES DE DATOS EN MYSQL ===\n";
$dbs = $conn->query("SHOW DATABASES");
while ($db = $dbs->fetch_row()) {
    echo "DB: " . $db[0] . "\n";
}

echo "\n=== BUSCAR 'PLAN' EN TODAS LAS COLUMNAS Y TABLAS DE LA BD ACTUAL ===\n";
$tables = $conn->query("SHOW TABLES");
while ($t = $tables->fetch_row()) {
    $tbl = $t[0];
    $cols = $conn->query("DESCRIBE `$tbl`");
    $hasPlanCol = false;
    $colList = [];
    while ($c = $cols->fetch_assoc()) {
        $colList[] = $c['Field'];
        if (stripos($c['Field'], 'plan') !== false) {
            $hasPlanCol = true;
        }
    }
    if ($hasPlanCol) {
        echo "Tabla $tbl tiene columnas: " . implode(', ', $colList) . "\n";
    }
}
