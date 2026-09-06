<?php
require_once __DIR__ . '/../db/conexion.php';
$con = new Conexion();
$conn = $con->getConnection();

echo "=== COLUMNAS DE TODAS LAS TABLAS ===\n";
$tables = $conn->query("SHOW TABLES");
while ($t = $tables->fetch_row()) {
    $tbl = $t[0];
    echo "Tabla $tbl:\n";
    $cols = $conn->query("DESCRIBE `$tbl`");
    while ($c = $cols->fetch_assoc()) {
        echo "  - " . $c['Field'] . " (" . $c['Type'] . ")\n";
    }
}
