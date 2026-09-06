<?php
require_once __DIR__ . '/../db/conexion.php';
$con = new Conexion();
$conn = $con->getConnection();

echo "=== TABLAS EN LA BASE DE DATOS ===\n";
$tables = $conn->query("SHOW TABLES");
while ($t = $tables->fetch_row()) {
    $tableName = $t[0];
    echo "Tabla: $tableName\n";
    if (stripos($tableName, 'plan') !== false || stripos($tableName, 'pago') !== false || stripos($tableName, 'suscri') !== false || stripos($tableName, 'memb') !== false || stripos($tableName, 'serv') !== false) {
        echo "  --> CONTENIDO DE $tableName:\n";
        $data = $conn->query("SELECT * FROM `$tableName`");
        if ($data) {
            while ($row = $data->fetch_assoc()) {
                echo "      " . json_encode($row) . "\n";
            }
        }
    }
}

echo "\n=== TODAS LAS FILAS DE plan_pago (sin filtros) ===\n";
$resPlan = $conn->query("SELECT * FROM plan_pago");
if ($resPlan) {
    while ($r = $resPlan->fetch_assoc()) {
        echo json_encode($r) . "\n";
    }
}
