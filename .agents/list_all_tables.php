<?php
require_once __DIR__ . '/../db/conexion.php';
$con = new Conexion();
$conn = $con->getConnection();

echo "=== TODAS LAS TABLAS DE LA BASE DE DATOS ===\n";
$tables = $conn->query("SHOW TABLES");
while ($t = $tables->fetch_row()) {
    $tbl = $t[0];
    echo "- $tbl\n";
}
